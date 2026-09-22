<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminSearchController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductReplacementController;
use App\Http\Controllers\Admin\ProductSaleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\WarrantyController;
use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerWarrantyController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/search', [PublicController::class, 'search'])->name('public.search');
Route::post('/search/ajax', [PublicController::class, 'searchAjax'])->name('public.search.ajax');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact/send', [PublicController::class, 'sendContact'])->name('public.contact.send');

/*
|--------------------------------------------------------------------------
| One-Click Live Production Deployment Sync
|--------------------------------------------------------------------------
*/
Route::get('/deploy-sync', function (\Illuminate\Http\Request $request) {
    if ($request->query('key') !== 'fuzura2026') {
        abort(403, 'Unauthorized deployment key.');
    }
    $outputs = [];
    $commands = [
        'git fetch origin main 2>&1',
        'git reset --hard origin/main 2>&1',
        'php artisan view:clear 2>&1',
        'php artisan config:clear 2>&1',
        'php artisan route:clear 2>&1',
    ];
    foreach ($commands as $cmd) {
        $outputs[] = "$ " . $cmd . "\n" . shell_exec($cmd);
    }
    return response('<pre style="background:#0f172a;color:#38bdf8;padding:25px;border-radius:12px;font-family:monospace;font-size:14px;line-height:1.6;">' . implode("\n", $outputs) . '</pre>');
});

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [CustomerAuthController::class, 'showForgotPassword'])->name('forgot_password');
    Route::post('/forgot-password', [CustomerAuthController::class, 'sendResetLink'])->name('forgot_password.post');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

    /*
    | Customer Protected Portal
    */
    Route::middleware('customer.auth')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        
        // Products & Warranty Registration
        Route::get('/products', [CustomerWarrantyController::class, 'index'])->name('products.index');
        Route::get('/products/register', [CustomerWarrantyController::class, 'create'])->name('products.register');
        Route::post('/products/check-serial', [CustomerWarrantyController::class, 'checkSerial'])->name('products.check_serial');
        Route::post('/products/store', [CustomerWarrantyController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/certificate', [CustomerWarrantyController::class, 'certificate'])->name('products.certificate');
        Route::get('/products/{id}/bill', [CustomerWarrantyController::class, 'viewBill'])->name('products.view_bill');

        // Profile & Security
        Route::get('/profile', [CustomerAuthController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [CustomerAuthController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password/update', [CustomerAuthController::class, 'updatePassword'])->name('password.update');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Authentication & Console Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    /*
    | Admin Protected Console
    */
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Product Master
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/products/available/list', [ProductController::class, 'getAvailableSerials'])->name('products.available');
        Route::get('/products/by-serial/{serial}', [ProductController::class, 'getProductBySerial'])->name('products.by_serial');

        // Product Sales
        Route::get('/sales', [ProductSaleController::class, 'index'])->name('sales.index');
        Route::post('/sales', [ProductSaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/{id}', [ProductSaleController::class, 'show'])->name('sales.show');
        Route::delete('/sales/{id}', [ProductSaleController::class, 'destroy'])->name('sales.destroy');

        // Product Replacements
        Route::get('/replacements', [ProductReplacementController::class, 'index'])->name('replacements.index');
        Route::post('/replacements', [ProductReplacementController::class, 'store'])->name('replacements.store');
        Route::get('/replacements/timeline/{serial}', [ProductReplacementController::class, 'showTimeline'])->name('replacements.timeline');
        Route::get('/replacements/eligible/list', [ProductReplacementController::class, 'getEligibleNewProducts'])->name('replacements.eligible');

        // Warranty Management
        Route::get('/warranties', [WarrantyController::class, 'index'])->name('warranties.index');
        Route::get('/warranties/{id}', [WarrantyController::class, 'show'])->name('warranties.show');
        Route::put('/warranties/{id}/status', [WarrantyController::class, 'updateStatus'])->name('warranties.status');
        Route::get('/warranties/{id}/download-bill', [WarrantyController::class, 'downloadBill'])->name('warranties.download_bill');

        // Customer Management
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
        Route::post('/customers/{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle_status');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export_csv');

        // Deep Serial Search
        Route::get('/search', [AdminSearchController::class, 'index'])->name('search.index');

        // Admin Profile & Security
        Route::get('/profile', [AdminAuthController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [AdminAuthController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password/update', [AdminAuthController::class, 'updatePassword'])->name('password.update');
    });
});
