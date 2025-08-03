<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(Price $price)
    {
        $sites = auth()->user()->sites()->get();

        if ($sites->isEmpty()) {
            return redirect()->route('sites.create')->with('info', 'You must add a site before you can purchase a plan.');
        }

        return view('checkout', [
            'price' => $price,
            'sites' => $sites,
        ]);
    }
}