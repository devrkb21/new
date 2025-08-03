<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

// --- Public Page Controllers ---
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SiteManagementController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\CourierCheckController;
use App\Http\Controllers\LanguageController;


// --- Authenticated Client Controllers ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPlanController;
use App\Http\Controllers\SiteLinkController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BkashTokenizePaymentController;

// --- Admin Controllers ---
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\BillingPeriodController as AdminBillingPeriodController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;

// --- Auth Controllers ---
use App\Http\Controllers\Auth\PhoneVerificationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//======================================================================
// 1. PUBLIC ROUTES (Guests can access)
//======================================================================
Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/pricing', [PublicPageController::class, 'pricing'])->name('pricing');
Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicPageController::class, 'sendContactMessage'])->name('contact.send');
Route::get('/privacy-policy', [PublicPageController::class, 'privacy'])->name('privacy.policy');
Route::get('/terms-and-conditions', [PublicPageController::class, 'terms'])->name('terms.conditions');
Route::get('/about-us', [PublicPageController::class, 'about'])->name('about.us');


Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

//======================================================================
// 2. PHONE VERIFICATION ROUTES
//======================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/phone/verify', [PhoneVerificationController::class, 'show'])->name('phone.verification.notice');
    Route::post('/phone/verify', [PhoneVerificationController::class, 'verify'])->name('phone.verification.verify');
    Route::post('/phone/verify/resend', [PhoneVerificationController::class, 'resend'])->name('phone.verification.resend')->middleware('throttle:6,1');
});


//======================================================================
// 3. AUTHENTICATED CLIENT ROUTES
//======================================================================
Route::middleware(['auth', 'phone.verified'])->group(function () {

    // --- Client Dashboard ---
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $sites = auth()->user()->sites()->with('plan')->get();
        $active_tickets = $user->tickets()->whereIn('status', ['open', 'in-progress'])->count();
        return view('dashboard', [
            'user' => auth()->user(),
            'active_plans' => $sites->where('status', 'active')->count(),
            'expired_plans' => $sites->where('status', 'expired')->count(),
            'renewal_soon_plans' => $sites->where('plan_expires_at', '<=', Carbon::now()->addDays(15))
                ->where('plan_expires_at', '>', Carbon::now())
                ->where('status', 'active')
                ->count(),
            'active_tickets' => $active_tickets,
        ]);
    })->name('dashboard');
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [TicketController::class, 'storeReply'])->name('tickets.reply');
        Route::patch('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
        Route::get('/tickets/{ticket}/attachment', [TicketController::class, 'downloadAttachment'])->name('tickets.attachment');
    });

    // --- Courier Success Checker ---
    Route::get('/courier-checker', [CourierCheckController::class, 'index'])->name('courier.checker.index');
    Route::post('/courier-checker/check', [CourierCheckController::class, 'check'])->name('courier.checker.check');


    // --- Profile Management ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markasread');

    // --- Subscription & Site Management ---
    Route::get('/orders-plan', [UserPlanController::class, 'index'])->name('orders.plan');
    Route::get('/payment-history', [UserPlanController::class, 'paymentHistory'])->name('payment.history');
    Route::get('/subscribe', [UserPlanController::class, 'create'])->name('subscribe.create');
    Route::get('/sites/create', [SiteLinkController::class, 'create'])->name('sites.create');
    Route::post('/sites/link', [SiteLinkController::class, 'store'])->name('sites.link');
    Route::get('/sites/{site}/change-plan', [SiteManagementController::class, 'changePlan'])->name('sites.change_plan');
    Route::get('/sites/{site}/manage', [SiteManagementController::class, 'show'])->name('sites.manage');
    Route::put('/sites/{site}/settings', [SiteManagementController::class, 'updateSettings'])->name('sites.settings.update');
    Route::get('/sites/{site}/api', [SiteManagementController::class, 'api'])->name('sites.api');

    Route::get('/api-documentation', [PublicPageController::class, 'apiDocumentation'])->name('api.documentation');
    Route::get('/download/plugin', [SiteManagementController::class, 'downloadPlugin'])->name('plugin.download');




    // --- Invoicing & Checkout Flow ---
    Route::get('/checkout/{price}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::post('/invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');

    // --- Payment Initiation ---
    Route::get('/bkash/payment/{invoice}', [BkashTokenizePaymentController::class, 'createPayment'])->name('bkash.payment');

});




//======================================================================
// 4. ADMIN ROUTES
//======================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/payment-history', [AdminController::class, 'paymentHistory'])->name('payment.history');

    // --- Admin Invoice Management ---
    Route::get('/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('invoices.show');
    Route::post('invoices/{invoice}/refund', [AdminRefundController::class, 'refund'])->name('invoices.refund');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::post('/invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::post('/invoices/{invoice}/mark-as-paid', [AdminInvoiceController::class, 'markAsPaid'])->name('invoices.markaspaid');


    // --- Admin User Management Routes (Now Complete) ---
    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'userCreate'])->name('users.create');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'userDestroy'])->name('users.destroy');
    Route::post('/users/{user}/verify-email', [AdminController::class, 'manualVerifyEmail'])->name('users.verify-email');
    Route::post('/users/{user}/verify-phone', [AdminController::class, 'manualVerifyPhone'])->name('users.verify-phone');



    // --- Other Admin Resourceful Routes ---
    Route::resource('sites', AdminController::class)->except(['show', 'create', 'store', 'destroy']);
    Route::delete('/sites/{site}', [AdminController::class, 'destroy'])->name('sites.destroy');
    Route::get('/sites/create', [AdminController::class, 'create'])->name('sites.create');
    Route::post('/sites', [AdminController::class, 'store'])->name('sites.store');
    Route::resource('plans', AdminPlanController::class);


    Route::resource('billing-periods', AdminBillingPeriodController::class)->except(['show']);
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'storeReply'])->name('tickets.reply');
        Route::patch('/tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.status');
    });
});


//======================================================================
// 5. PUBLIC CALLBACKS & AUTHENTICATION
//======================================================================
Route::get('/bkash/callback', [BkashTokenizePaymentController::class, 'success'])->name('bkash.success');
require __DIR__ . '/auth.php';