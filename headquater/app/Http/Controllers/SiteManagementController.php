<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Traits\ManagesSubscriptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class SiteManagementController extends Controller
{
    use ManagesSubscriptions;

    /**
     * Display the management page for a specific site.
     */
    public function show(Site $site)
    {
        // Ensure the user owns this site
        if ($site->user_id !== auth()->id()) {
            abort(403);
        }

        $site->load('plan.prices.billingPeriod', 'price.billingPeriod', 'settings', 'nextPlan.prices.billingPeriod', 'nextPrice.billingPeriod', 'customBillingPeriod');

        return view('sites.manage', compact('site'));
    }

    /**
     * Show the page for changing a site's plan.
     */
    public function changePlan(Site $site)
    {
        if ($site->user_id !== auth()->id()) {
            abort(403);
        }

        $site->load('plan', 'price.billingPeriod');

        // Fetch all public plans that are not the site's current plan and not the special 'custom' plan
        $availablePlans = Plan::with('prices.billingPeriod')
            ->where('is_public', true)
            ->where('slug', '!=', 'custom') // Exclude the placeholder 'Custom' plan from options
            ->where(function ($query) use ($site) {
                if ($site->plan_id) {
                    $query->where('id', '!=', $site->plan_id);
                }
            })
            ->get();

        // Get the current price, which could be null for Free or Custom plans.
        $currentPrice = $site->price;

        // Determine if each available price option is an upgrade or downgrade
        foreach ($availablePlans as $plan) {
            foreach ($plan->prices as $newPrice) {
                // THIS IS THE CORRECTED LOGIC
                // If there's no current standard price, any change to a paid plan is an upgrade.
                if (is_null($currentPrice)) {
                    $newPrice->is_upgrade = true;
                } else {
                    // Otherwise, use the existing logic to compare the two prices.
                    $newPrice->is_upgrade = $this->isUpgrade($currentPrice, $newPrice);
                }
            }
        }

        return view('sites.change-plan', compact('site', 'availablePlans'));
    }


    /**
     * Update the settings for a specific site.
     */
    public function updateSettings(Request $request, Site $site)
    {
        if ($site->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'checkout_tracking_enabled' => 'required|boolean',
            'fraud_blocker_enabled' => 'required|boolean',
            'courier_service_enabled' => 'required|boolean',
            'data_retention_days' => 'required|integer|min:1|max:365',
        ]);

        try {
            // Use updateOrCreate to handle cases where settings might not exist yet
            $site->settings()->updateOrCreate(
                ['site_id' => $site->id],
                $validated
            );

            return back()->with('success', __('messages.settings_updated_success'));
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating site settings for site ' . $site->id . ': ' . $e->getMessage());
            return back()->with('error', __('messages.settings_updated_error'));
        }
    }

    public function api(Site $site)
    {
        // Ensure the authenticated user owns this site
        if ($site->user_id !== auth()->id()) {
            abort(404);
        }

        // Fetch the global API key from your config to display on the page
        $apiKey = config('app.plugin_api_key');

        // Return the new API documentation view, passing the site and API key
        return view('sites.api', compact('site', 'apiKey'));
    }

    public function downloadPlugin()
    {
        // Use the config helper to get the path
        $pluginPath = config('filesystems.plugin_path');

        if (!$pluginPath) {
            abort(404, 'Plugin path is not configured.');
        }

        $filePath = public_path($pluginPath);

        // Check if the file exists
        if (!File::exists($filePath)) {
            abort(404, 'File not found at the configured path.');
        }

        // Return the file as a download
        return Response::download($filePath);
    }
    
}

