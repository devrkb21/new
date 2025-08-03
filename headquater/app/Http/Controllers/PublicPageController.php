<?php
namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;


class PublicPageController extends Controller
{
    // Show the public homepage
    public function home()
    {
        // THE FIX: Fetch plans for the homepage, just like the pricing page.
        $plans = Plan::where('is_public', true)->with('prices.billingPeriod')->get();
        return view('welcome', ['plans' => $plans]);
    }
    // Show the public pricing page
    public function pricing()
    {
        // Fetches only plans that have at least one price defined.
        // Eager-loads the prices and their billing periods for efficiency.
        $plans = Plan::where('is_public', true)->with('prices.billingPeriod')->get();
        return view('pricing', ['plans' => $plans]);
    }
    public function contact()
    {
        return view('contact');
    }

    /**
     * ADD THIS METHOD
     * Handle the contact form submission.
     */
    public function sendContactMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Note: Ensure your .env file is configured to send emails.
        // The recipient is the MAIL_FROM_ADDRESS in your .env file.
        Mail::to(config('mail.from.address'))->send(new ContactFormMail($validated));

        return back()->with('success', 'Thank you for your message! We will get back to you shortly.');
    }
    public function apiDocumentation()
    {
        return view('api-documentation');
    }
    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        return view('privacy-policy');
    }

    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        return view('terms-and-conditions');
    }
    /**
     * Display the about us page.
     */
    public function about()
    {
        return view('about-us');
    }
}