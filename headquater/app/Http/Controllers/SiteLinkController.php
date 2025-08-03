<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiteLinkController extends Controller
{
    public function create()
    {
        return view('sites.create');
    }
    public function store(Request $request)
    {
        // 1. Validate the 'domain' input from the form we created.
        $validated = $request->validate([
            'domain' => 'required|string',
        ]);

        // 2. Find the site that was already created by the plugin (where user_id is null).
        $site =Site::where('domain', $validated['domain'])
            ->whereNull('user_id')
            ->first();

        // 3. If we found a matching unlinked site, claim it for the current user.
        if ($site) {
            $site->user_id = auth()->id();
            $site->save();

            // 4. Redirect back to the list of sites with a success message.
            return redirect()->route('orders.plan')->with('success', 'Site linked successfully!');
        }

        // 5. If no matching site was found, return with an error.
        return back()->withInput()->with('error', 'Could not find a matching unlinked site. Please ensure the plugin has been activated on your WordPress site first.');
    }
}