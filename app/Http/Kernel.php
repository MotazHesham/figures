<?php

namespace App\Http;

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsSeller;
use App\Http\Middleware\CheckoutMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Language::class,
            // \App\Http\Middleware\HttpsProtocol::class
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin' => IsAdmin::class,
        'seller' => IsSeller::class,
        'checkout' => CheckoutMiddleware::class,
        'CheckRole' => \App\Http\Middleware\CheckRole::class,
        'company_receipt_permission' => \App\Http\Middleware\CompanyReceiptPermission::class,
        'customer_list_permission' => \App\Http\Middleware\CustomerListPermission::class,
        'client_receipt_permission' => \App\Http\Middleware\ClientReceiptPermission::class,
        'social_receipt_permission' => \App\Http\Middleware\SocialReceiptPermission::class,
        'outgoings_receipt_permission' => \App\Http\Middleware\OutgoingsReceiptPermission::class,
        'price_view_receipt_permission' => \App\Http\Middleware\PriceViewReceiptPermission::class,
        'calender_permission' => \App\Http\Middleware\CalenderPermission::class,
        'mockup_permission' => \App\Http\Middleware\MockupPermission::class,
        'chatting_permission' => \App\Http\Middleware\ChattingPermission::class,
        'delivery_order_permission' => \App\Http\Middleware\DeliveryOrderPermission::class,
        'flash_deal_permission' => \App\Http\Middleware\FlashDealPermission::class,
        'order_permission' => \App\Http\Middleware\OrderPermission::class,
        'product_permission' => \App\Http\Middleware\ProductPermission::class,
        'seller_permission' => \App\Http\Middleware\SellerPermission::class,
        'seo_permission' => \App\Http\Middleware\SeoPermission::class,
        'settings_permission' => \App\Http\Middleware\SettingsPermission::class,
        'staff_permission' => \App\Http\Middleware\StaffPermission::class,
        'deliveryman_list_permission' => \App\Http\Middleware\DeliveryManListPermission::class,
        'playlist_permission' => \App\Http\Middleware\PlaylistPermission::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces the listed middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
