<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Site;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
    /**
     * Register a new WordPress site.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => ['required', 'string', 'regex:/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i'],
            'admin_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $existingSite = Site::where('domain', $request->domain)->first();
        $api_key = config('app.plugin_api_key', 'we-are-on-production');

        if ($existingSite) {
            return response()->json([
                'message' => 'The domain is already registered with us',
                'api_key' => $api_key
            ], 200);
        }

        // --- START OF MODIFIED LOGIC ---

        // Find the specific "Free Trial" plan.
        // I am assuming its slug is 'free-trial'. Change if needed.
        $freeTrialPlan = Plan::with('prices')->where('slug', 'free')->firstOrFail();

        // Get the first price associated with this plan (assuming it's the monthly one).
        $price = $freeTrialPlan->prices->first();

        // Ensure the plan is configured correctly with a price.
        if (!$price) {
            return response()->json(['error' => 'Free trial plan is not configured with a price.'], 500);
        }

        $site = new Site();
        $site->domain = $request->domain;
        $site->admin_email = $request->admin_email;
        $site->plan_id = $freeTrialPlan->id;
        $site->price_id = $price->id; // Assign the price ID
        $site->status = 'active';
        $site->plan_activated_at = now(); // Set the activation date
        $site->plan_expires_at = now()->addMonth(); // Set the plan to expire in one month

        // --- END OF MODIFIED LOGIC ---

        // Find and link a user who has registered with this exact website URL.
        $user = User::where('website_url', $request->domain)->first();
        if ($user) {
            $site->user_id = $user->id;
        }

        $site->save();

        $site->settings()->updateOrCreate(['site_id' => $site->id]);

        return response()->json([
            'message' => 'Site registered successfully.',
            'site_id' => $site->id,
            'api_key' => $api_key,
        ], 201);
    }

    // ... other methods in your controller are unchanged ...

    /**
     * Get the status, limits, and settings for a site.
     */
    public function getStatus(Request $request)
    {
        $site = Site::with(['plan', 'settings'])->where('domain', $request->header('X-Site-Domain'))->firstOrFail();

        $planToUse = $site->plan;

        if ($site->status === 'expired') {
            $freePlan = Plan::where('slug', 'free')->first();
            if ($freePlan) {
                $planToUse = $freePlan;
            }
        }

        return response()->json([
            'status' => $site->status,
            'plan' => $planToUse->name,
            'limits' => [
                'checkouts' => $site->getLimit('limit_checkouts'),
                'fraud_ips' => $site->getLimit('limit_fraud_ips'),
                'fraud_emails' => $site->getLimit('limit_fraud_emails'),
                'fraud_phones' => $site->getLimit('limit_fraud_phones'),
                'courier_checks' => $site->getLimit('limit_courier_checks'),
            ],
            'usage' => [
                'checkouts' => $site->usage_checkouts,
                'fraud_ips' => $site->usage_fraud_ips,
                'fraud_emails' => $site->usage_fraud_emails,
                'fraud_phones' => $site->usage_fraud_phones,
                'courier_checks' => $site->usage_courier_checks,
            ],
            'settings' => $site->settings,
        ]);
    }

    /**
     * Update a site's synchronized settings from the plugin.
     */
    public function updateSettings(Request $request)
    {
        $site = Site::where('domain', $request->header('X-Site-Domain'))->firstOrFail();

        $validator = Validator::make($request->all(), [
            'checkout_tracking_enabled' => 'sometimes|boolean',
            'fraud_blocker_enabled' => 'sometimes|boolean',
            'courier_service_enabled' => 'sometimes|boolean',
            'data_retention_days' => 'sometimes|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $site->settings->update($request->all());

        return response()->json([
            'message' => 'Settings updated successfully.',
            'settings' => $site->settings->fresh(),
        ]);
    }

    /**
     * Increment a usage counter for a specific action.
     */
    public function incrementUsage(Request $request)
    {
        $site = Site::where('domain', $request->header('X-Site-Domain'))->firstOrFail();

        $validator = Validator::make($request->all(), [
            'action_type' => 'required|in:checkouts,fraud_ips,fraud_emails,fraud_phones,courier_checks',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        $actionType = $request->action_type;
        $usageColumn = 'usage_' . $actionType;
        $limitColumn = 'limit_' . $actionType;

        $limit = $site->getLimit($limitColumn);
        $currentUsage = $site->{$usageColumn};

        if ($limit > 0 && $currentUsage >= $limit) {
            return response()->json([
                'error' => 'Limit reached for this action.',
                'action' => $actionType,
                'limit' => $limit,
            ], 429);
        }

        $site->increment($usageColumn);
        return response()->json(['message' => 'Usage updated.']);
    }

    /**
     * Decrement a usage counter for a specific action.
     */
    public function decrementUsage(Request $request)
    {
        $site = Site::where('domain', $request->header('X-Site-Domain'))->firstOrFail();

        $validator = Validator::make($request->all(), [
            'action_type' => 'required|in:checkouts,fraud_ips,fraud_emails,fraud_phones,courier_checks',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        $columnName = 'usage_' . $request->action_type;
        if ($site->{$columnName} > 0) {
            $site->decrement($columnName);
        }
        return response()->json(['message' => 'Usage updated.']);
    }
}