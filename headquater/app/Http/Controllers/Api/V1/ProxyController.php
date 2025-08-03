<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProxyController extends Controller
{
    public function checkCourierSuccess(Request $request)
    {
        $validator = Validator::make($request->all(), ['phone_number' => 'required|string']);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        $phoneNumber = $request->phone_number;
        $cacheKey = 'courier_results_' . $phoneNumber;

        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $site = Site::with('plan')->where('domain', $request->header('X-Site-Domain'))->firstOrFail();
        $limit = $site->plan->limit_courier_checks;
        if ($limit > 0 && $site->usage_courier_checks >= $limit) {
            return response()->json(['message' => 'You have reached your monthly limit for courier API checks.'], 403);
        }
        $site->increment('usage_courier_checks');

        $pathao_data = $this->getPathaoData($phoneNumber);
        $steadfast_data = $this->getSteadfastData($phoneNumber);
        $redx_data = $this->getRedxData($phoneNumber);

        $results = $this->aggregateResults($pathao_data, $steadfast_data, $redx_data);

        // --- THIS IS THE NEW LOGIC ---
        // Determine if any of the API calls returned an error.
        $shouldCache = !$results['pathao']['error'] && !$results['steadfast']['error'] && !$results['redx']['error'];

        // Only save the result to the cache if all calls were successful.
        if ($shouldCache) {
            Cache::put($cacheKey, $results, now()->addMinutes(360));
        }
        // --- END OF NEW LOGIC ---

        return response()->json($results);
    }

    private function getPathaoAccessToken($credential, $index)
    {
        return Cache::remember('pathao_access_token_' . $index, 3600, function () use ($credential) {
            $response = Http::acceptJson()->post(config('services.pathao.url') . '/aladdin/api/v1/issue-token', [
                'client_id' => $credential['client_id'],
                'client_secret' => $credential['client_secret'],
                'username' => $credential['username'],
                'password' => $credential['password'],
                'grant_type' => 'password',
            ]);
            return $response->successful() ? $response->json('access_token') : null;
        });
    }

    private function getPathaoData($phone)
    {
        $credentials = config('services.pathao.credentials');
        for ($i = 0; $i < count($credentials); $i++) {
            $token = $this->getPathaoAccessToken($credentials[$i], $i);
            if (!$token)
                continue;

            $response = Http::acceptJson()->withToken($token)->post('https://merchant.pathao.com/api/v1/user/success', ['phone' => $phone]);

            if ($response->status() === 429)
                continue; // Rate limited, try next credential

            if ($response->successful() && isset($response->json('data')['customer']['total_delivery'])) {
                return ['success' => (int) $response->json('data')['customer']['successful_delivery'], 'total' => (int) $response->json('data')['customer']['total_delivery'], 'error' => false];
            }
        }
        return ['success' => 0, 'total' => 0, 'error' => true];
    }

    private function getRedxData($phone)
    {
        $tokens = config('services.redx.tokens');
        for ($i = 0; $i < count($tokens); $i++) {
            $token = $tokens[$i];
            if (empty($token))
                continue;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36'
            ])->get(config('services.redx.url') . "/api/redx_se/admin/parcel/customer-success-return-rate", ['phoneNumber' => $phone]);

            if ($response->status() === 429)
                continue; // Rate limited, try next token

            if ($response->successful() && isset($response->json('data')['totalParcels'])) {
                return ['success' => (int) $response->json('data')['deliveredParcels'], 'total' => (int) $response->json('data')['totalParcels'], 'error' => false];
            }
        }
        Log::error('RedX API All Keys Failed.');
        return ['success' => 0, 'total' => 0, 'error' => true];
    }

    // getSteadfastData and aggregateResults remain unchanged
    private function getSteadfastData($phone)
    {
        $response = Http::get(config('services.steadfast.url') . "/api/v1/fraud_check/{$phone}");
        if ($response->successful() && isset($response->json()['total_parcels'])) {
            return ['success' => (int) $response->json()['total_delivered'], 'total' => (int) $response->json()['total_parcels'], 'error' => false];
        }
        return ['success' => 0, 'total' => 0, 'error' => true];
    }

    private function aggregateResults($pathao, $steadfast, $redx)
    {
        $total_deliveries = $pathao['total'] + $steadfast['total'] + $redx['total'];
        $total_success = $pathao['success'] + $steadfast['success'] + $redx['success'];
        $success_rate = ($total_deliveries > 0) ? round(($total_success / $total_deliveries) * 100) : 0;

        $level_name = 'N/A';
        $level_color = '#757575';
        if ($success_rate >= 95) {
            $level_name = 'Safe';
            $level_color = '#4CAF50';
        } elseif ($success_rate >= 85) {
            $level_name = 'Mid-safe';
            $level_color = '#FFC107';
        } elseif ($success_rate >= 70) {
            $level_name = 'Risk';
            $level_color = '#FF9800';
        } elseif ($total_deliveries > 0) {
            $level_name = 'High Risk';
            $level_color = '#F44336';
        }

        return [
            'pathao' => array_merge($pathao, ['cancelled' => $pathao['total'] - $pathao['success']]),
            'steadfast' => array_merge($steadfast, ['cancelled' => $steadfast['total'] - $steadfast['success']]),
            'redx' => array_merge($redx, ['cancelled' => $redx['total'] - $redx['success']]),
            'grand_total' => ['total' => $total_deliveries, 'success' => $total_success, 'cancelled' => $total_deliveries - $total_success, 'rate' => $success_rate],
            'level' => ['name' => $level_name, 'color' => $level_color],
        ];
    }
}