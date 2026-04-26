<?php

use App\Http\Controllers\Authorization\AdminUserManagementController;
use App\Http\Controllers\BookMeeting\BookMeetingController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Checkout\CheckoutController;
use App\Http\Controllers\ContactUs\ContactUsController;
use App\Http\Controllers\AdminPanel\AdminDashboardController;
use App\Http\Controllers\ControlPanel\CategoryCrudController;
use App\Http\Controllers\Faq\FaqController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Authorization\SignupEmailOtpController;
use App\Http\Controllers\Proforma\ProformaInvoiceController;
use App\Http\Controllers\Quotation\QuotationController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\AdminPanel\RolePermissionAdminCrudController;
use App\Http\Controllers\AdminPanel\PricingCrudController;
use App\Http\Controllers\AdminPanel\QuizeCrudController;
use App\Http\Controllers\Profile\CustomerAddressController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Product\ProductCrudController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Quize\QuizeController;
use App\Http\Controllers\SupportTicket\SupportTicketController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// sign In Signup routes
Route::view('/signup', 'auth.signup')->name('signup');
Route::view('/b2b-signup', 'auth.signup-b2b')->name('b2b.signup');
Route::post('/signup/email-otp/send', [SignupEmailOtpController::class, 'sendOtp'])->name('signup.email-otp.send');
Route::post('/signup/email-otp/verify', [SignupEmailOtpController::class, 'verifyOtp'])->name('signup.email-otp.verify');
Route::view('/forgot-password', 'auth.forgot-password')->name('forgot.password');
// (/login) is working through Laravel Fortify,inside fortifyServiceProvider => Fortify::loginView(fn () => view('auth.login'));

// product flow routes
Route::get('/', [HomeController::class, 'index'])->middleware('permission:home')->name('home');  // home page route
Route::get('/home', [HomeController::class, 'index'])->middleware('permission:home.page')->name('home.page');  // home page route
Route::get('/products', [ProductController::class, 'index'])->middleware('permission:products.index')->name('products.index'); // products page route
Route::get('/products/{productId}', [ProductController::class, 'productDetails'])->middleware(['decrypt.route', 'permission:products.productDetails'])->name('products.productDetails'); // product details page route
// route to download technical resources file in product details page.
Route::get('/products/{productId}/technical-resources/{resourceId}/download', [ProductController::class, 'downloadTechnicalResource'])->middleware(['decrypt.route', 'permission:products.technical-resources.download'])->name('products.technical-resources.download');

// Cart flow routes
Route::get('/cart/data', [CartController::class, 'showUserOrGuestCart'])->middleware('permission:cart.data')->name('cart.data');
Route::post('/cart/items', [CartController::class, 'addItemToUserOrGuestCart'])->middleware('permission:cart.items.store')->name('cart.items.store');
Route::patch('/cart/items/{cartItemId}', [CartController::class, 'updateUserOrGuestCartItem'])->middleware(['decrypt.route', 'permission:cart.items.update'])->name('cart.items.update');
Route::delete('/cart/items/{cartItemId}', [CartController::class, 'removeItemFromUserOrGuestCart'])->middleware(['decrypt.route', 'permission:cart.items.destroy'])->name('cart.items.destroy');
Route::post('/cart/checkout', [CheckoutController::class, 'submitUserCartCheckout'])->middleware(['auth', 'permission:cart.checkout.submit'])->name('cart.checkout.submit');

// Guest Cart routes.
Route::get('/guest-cart/data', [CartController::class, 'showUserOrGuestCart'])->middleware('permission:guest-cart.data')->name('guest-cart.data');
Route::post('/guest-cart/items', [CartController::class, 'addItemToUserOrGuestCart'])->middleware('permission:guest-cart.items.store')->name('guest-cart.items.store');
Route::patch('/guest-cart/items/{cartItemId}', [CartController::class, 'updateUserOrGuestCartItem'])->middleware(['decrypt.route', 'permission:guest-cart.items.update'])->name('guest-cart.items.update');
Route::delete('/guest-cart/items/{cartItemId}', [CartController::class, 'removeItemFromUserOrGuestCart'])->middleware(['decrypt.route', 'permission:guest-cart.items.destroy'])->name('guest-cart.items.destroy');


// checkout routes
Route::get('/checkout', [CheckoutController::class, 'showCustomerCheckoutPage'])->middleware('permission:checkout.page')->name('checkout.page');
Route::post('/checkout', [CheckoutController::class, 'submitUserCheckoutOrder'])->middleware(['auth', 'permission:checkout.submit'])->name('checkout.submit');
Route::post('/checkout/coupon/validate', [CheckoutController::class, 'validateCheckoutCoupon'])->middleware(['auth', 'permission:checkout.coupon.validate'])->name('checkout.coupon.validate');
Route::post('/checkout/reorder/pricing', [CheckoutController::class, 'previewReOrderPricing'])->middleware(['auth', 'permission:checkout.reorder.pricing'])->name('checkout.reorder.pricing');
Route::post('/checkout/buy-now', [CheckoutController::class, 'startCheckoutFromBuyNow'])->middleware('permission:checkout.buy-now')->name('checkout.buy-now');
Route::post('/guest-checkout/buy-now', [CheckoutController::class, 'startCheckoutFromBuyNow'])->middleware('permission:guest.checkout.buy-now')->name('guest.checkout.buy-now');


// Orders routes
Route::get('/orders', [OrderController::class, 'showOrderCrud'])->middleware(['auth', 'permission:orders.index'])->name('orders.index');
Route::post('/orders', [OrderController::class, 'createOrder'])->middleware(['auth', 'permission:orders.store'])->name('orders.store');
Route::get('/orders/reorder/checkout', [OrderController::class, 'showReOrderCheckoutPage'])->middleware(['auth', 'permission:orders.reorder.checkout'])->name('orders.reorder.checkout');
Route::post('/orders/reorder/checkout', [OrderController::class, 'submitReOrderCheckout'])->middleware(['auth', 'permission:orders.reorder.checkout.submit'])->name('orders.reorder.checkout.submit');
Route::post('/orders/{orderId}/reorder', [OrderController::class, 'ReOrder'])->middleware(['auth', 'decrypt.route', 'permission:orders.reorder'])->name('orders.reorder');
Route::get('/orders/{orderId}', [OrderController::class, 'getOrderById'])->middleware(['auth', 'decrypt.route', 'permission:orders.show'])->name('orders.show');
Route::put('/orders/{orderId}', [OrderController::class, 'editOrderById'])->middleware(['auth', 'decrypt.route', 'permission:orders.update'])->name('orders.update');
Route::delete('/orders/{orderId}', [OrderController::class, 'softDeleteOrderById'])->middleware(['auth', 'decrypt.route', 'permission:orders.destroy'])->name('orders.destroy');

// profroma invoice routes
Route::get('/pi-quotation', [ProformaInvoiceController::class, 'showRequestPage'])->middleware(['auth', 'permission:pi-quotation.generate'])->name('pi-quotation.generate');
Route::post('/pi-quotation', [ProformaInvoiceController::class, 'submitRequest'])->middleware(['auth', 'permission:pi-quotation.store'])->name('pi-quotation.store');
// TODO : download Proforma Invoices flow after approval of request.

// quotation routes.
Route::get('/generate-quote', [QuotationController::class, 'showCreatePage'])->middleware('permission:quotation.create')->name('quotation.create');
Route::post('/generate-quote', [QuotationController::class, 'generate'])->middleware('permission:quotation.store')->name('quotation.store');

// Quize section route.
Route::get('/diagnostic-quiz', [QuizeController::class, 'index'])->middleware('permission:diagnostic-quiz')->name('diagnostic-quiz');
Route::post('/diagnostic-quiz', [QuizeController::class, 'store'])->middleware('permission:diagnostic-quiz.store')->name('diagnostic-quiz.store');

// Customer support ticket routes.

// Customer support routes
Route::get('/book-meeting', [BookMeetingController::class, 'index'])->middleware(['auth', 'permission:book-meeting'])->name('book-meeting');
Route::post('/book-meeting', [BookMeetingController::class, 'store'])->middleware(['auth', 'permission:book-meeting.store'])->name('book-meeting.store');
Route::view('/about', 'information.about')->middleware('permission:about')->name('about');
Route::view('/meet-team', 'information.meet-team')->name('meet-team');
Route::get('/meet-team/{id}', function ($id) {
    if (!is_numeric($id)) abort(404);
    $idx = (int)$id;
    $teamData = config('team.members');
    if (!isset($teamData[$idx])) {
        abort(404);
    }
    return view('information.team-member', [
        'member' => $teamData[$idx],
        'idx' => $idx,
        'teamData' => $teamData
    ]);
})->name('meet-team.show');
Route::view('/portfolio', 'information.portfolio')->name('portfolio');
Route::get('/solutions/{solutionSlug}', function (string $solutionSlug) {
    $displayName = str_replace('-', ' ', $solutionSlug);
    $displayName = ucwords($displayName);

    return view('information.solution-dummy', [
        'solutionSlug' => $solutionSlug,
        'solutionName' => $displayName,
    ]);
})->name('solutions.detail');
Route::get('/product-categories/{categorySlug}', function (string $categorySlug) {
    $displayName = str_replace('-', ' ', $categorySlug);
    $displayName = ucwords($displayName);

    return view('product.product-category-dummy', [
        'categorySlug' => $categorySlug,
        'categoryName' => $displayName,
    ]);
})->name('products.category.detail');
Route::get('/contact', [ContactUsController::class, 'index'])->middleware('permission:contact')->name('contact');
Route::post('/contact', [ContactUsController::class, 'store'])->middleware('permission:contact.store')->name('contact.store');
Route::view('/privacy', 'information.privacy')->middleware('permission:privacy')->name('privacy');
Route::view('/terms', 'information.terms')->middleware('permission:terms')->name('terms');
Route::view('/refund-policy', 'information.refund')->middleware('permission:refund-policy')->name('refund-policy');
Route::get('/faq', [FaqController::class, 'index'])->middleware('permission:faq')->name('faq');
Route::view('/maintenance', 'errors.503')->middleware('permission:maintenance')->name('maintenance');

// Customer Profile Section
Route::get('/customer/profile', [ProfileController::class, 'showMyProfilePage'])->middleware(['auth', 'permission:customer.profile.preview'])->name('customer.profile.preview');
Route::post('/customer/profile', [ProfileController::class, 'updateMyProfileSection'])->middleware(['auth', 'permission:customer.profile.update'])->name('customer.profile.update');
Route::post('/customer/profile/password', [ProfileController::class, 'updateMyPassword'])->middleware(['auth', 'permission:customer.profile.password.update'])->name('customer.profile.password.update');
Route::get('/customer/addresses', [CustomerAddressController::class, 'index'])->middleware(['auth', 'permission:customer.addresses.preview'])->name('customer.addresses.preview');
Route::get('/customer/orders', [OrderController::class, 'showCustomerOrdersPage'])->middleware(['auth', 'permission:customer.orders.preview'])->name('customer.orders.preview');
Route::post('/customer/addresses', [CustomerAddressController::class, 'store'])->middleware(['auth', 'permission:customer.addresses.store'])->name('customer.addresses.store');
Route::put('/customer/addresses/{addressId}', [CustomerAddressController::class, 'update'])->middleware(['auth', 'decrypt.route', 'permission:customer.addresses.update'])->name('customer.addresses.update');
Route::get('/support-tickets', [SupportTicketController::class, 'index'])->middleware(['auth', 'permission:support-tickets.index'])->name('support-tickets.index');
Route::post('/support-tickets', [SupportTicketController::class, 'store'])->middleware(['auth', 'permission:support-tickets.store'])->name('support-tickets.store');
Route::get('/support-tickets/{ticketId}/attachments/{attachmentId}/download', [SupportTicketController::class, 'downloadAttachment'])->middleware(['auth', 'decrypt.route', 'permission:support-tickets.attachments.download'])->name('support-tickets.attachments.download');
Route::get('/support-tickets/{ticketId}', [SupportTicketController::class, 'show'])->middleware(['auth', 'decrypt.route', 'permission:support-tickets.show'])->name('support-tickets.show');



Route::view('/order-confirmation', 'order-confirmation')->middleware(['auth', 'permission:order.confirmation'])->name('order.confirmation');

//TODO: Under development - Admin panel routes pointing to view directly for now, will connect to controllers after the views are ready.
Route::get('/adminPanel/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/adminPanel/categories', [CategoryCrudController::class, 'index'])->name('admin.categories');
Route::post('/adminPanel/categories', [CategoryCrudController::class, 'store'])->name('admin.categories.store');
Route::post('/adminPanel/categories/update', [CategoryCrudController::class, 'update'])->name('admin.categories.update');
Route::get('/adminPanel/products', [App\Http\Controllers\AdminPanel\ProductCrudController::class, 'index'])->name('admin.products');
Route::get('/adminPanel/products/create', [App\Http\Controllers\AdminPanel\ProductCrudController::class, 'create'])->name('admin.products.create');
Route::post('/adminPanel/products', [App\Http\Controllers\AdminPanel\ProductCrudController::class, 'store'])->name('admin.products.store');
Route::get('/adminPanel/products/{productId}', [App\Http\Controllers\AdminPanel\ProductCrudController::class, 'edit'])->name('admin.products.edit');
Route::put('/adminPanel/products/{productId}', [App\Http\Controllers\AdminPanel\ProductCrudController::class, 'update'])->name('admin.products.update');
Route::delete('/adminPanel/products/{productId}', [App\Http\Controllers\AdminPanel\ProductCrudController::class, 'destroy'])->name('admin.products.destroy');
Route::get('/adminPanel/pricing', [PricingCrudController::class, 'index'])->name('admin.pricing.index');
Route::get('/adminPanel/pricing/map-price', [PricingCrudController::class, 'showMapPricingForm'])->name('admin.pricing.map-price.form');
Route::post('/adminPanel/pricing/map-price', [PricingCrudController::class, 'saveMappedPricing'])->name('admin.pricing.map-price.save');
Route::post('/adminPanel/pricing/edit-price', [PricingCrudController::class, 'updatePricing'])->name('admin.pricing.edit-price.update');
Route::post('/adminPanel/pricing/company-price', [PricingCrudController::class, 'saveCompanyPricing'])->name('admin.pricing.company-price.save');
Route::post('/adminPanel/pricing/bulk-price', [PricingCrudController::class, 'saveBulkPricingSlabs'])->name('admin.pricing.bulk-price.save');
Route::get('/adminPanel/pi-quotation', [App\Http\Controllers\AdminPanel\Proforma\ProformaCrudController::class, 'index'])->name('admin.pi-quotation.index');
Route::get('/adminPanel/pi-quotation/create', [App\Http\Controllers\AdminPanel\Proforma\ProformaCrudController::class, 'create'])->name('admin.pi-quotation.create');
Route::post('/adminPanel/pi-quotation', [App\Http\Controllers\AdminPanel\Proforma\ProformaCrudController::class, 'store'])->name('admin.pi-quotation.store');
Route::get('/adminPanel/pi-quotation/{proformaId}', [App\Http\Controllers\AdminPanel\Proforma\ProformaCrudController::class, 'show'])->name('admin.pi-quotation.edit');
Route::put('/adminPanel/pi-quotation/{proformaId}', [App\Http\Controllers\AdminPanel\Proforma\ProformaCrudController::class, 'update'])->name('admin.pi-quotation.update');
Route::get('/adminPanel/orders', [App\Http\Controllers\AdminPanel\Order\OrderCrudController::class, 'index'])->name('admin.orders');
Route::get('/adminPanel/orders/{orderId}', [App\Http\Controllers\AdminPanel\Order\OrderCrudController::class, 'show'])->name('admin.orders.view');
Route::put('/adminPanel/orders/{orderId}', [App\Http\Controllers\AdminPanel\Order\OrderCrudController::class, 'update'])->name('admin.orders.update');
    Route::get('/adminPanel/customers', [\App\Http\Controllers\AdminPanel\UserManagementCrudController::class, 'index'])->name('admin.customers');
    Route::get('/adminPanel/customers/details/{customerId}', [\App\Http\Controllers\AdminPanel\UserManagementCrudController::class, 'details'])->name('admin.customers.details');
    Route::put('/adminPanel/customers/details/{customerId}', [\App\Http\Controllers\AdminPanel\UserManagementCrudController::class, 'updateDetails'])->name('admin.customers.details.update');
    Route::get('/adminPanel/customer-directory', [\App\Http\Controllers\AdminPanel\UserManagementCrudController::class, 'directory'])->name('admin.customer-directory');
    Route::post('/adminPanel/customers/pending/approve', [\App\Http\Controllers\AdminPanel\UserManagementCrudController::class, 'approvePending'])->name('admin.customers.pending.approve');
    Route::post('/adminPanel/customers/pending/reject', [\App\Http\Controllers\AdminPanel\UserManagementCrudController::class, 'rejectPending'])->name('admin.customers.pending.reject');
    Route::get('/adminPanel/support-tickets', [\App\Http\Controllers\AdminPanel\SupportTicketCrudController::class, 'index'])->name('admin.support-tickets');
    Route::get('/adminPanel/support-tickets/{ticketId}/details', [\App\Http\Controllers\AdminPanel\SupportTicketCrudController::class, 'getDetails'])->name('admin.support-tickets.details');
    Route::post('/adminPanel/support-tickets/{ticketId}/comments', [\App\Http\Controllers\AdminPanel\SupportTicketCrudController::class, 'addComment'])->name('admin.support-tickets.comment');
    Route::put('/adminPanel/support-tickets/{ticketId}/priority', [\App\Http\Controllers\AdminPanel\SupportTicketCrudController::class, 'updatePriority'])->name('admin.support-tickets.priority');
    Route::put('/adminPanel/support-tickets/{ticketId}/status', [\App\Http\Controllers\AdminPanel\SupportTicketCrudController::class, 'updateStatus'])->name('admin.support-tickets.status');
Route::view('/adminPanel/ui-fields-modification', 'admin.support-tickets.ui-fields-modification')->name('admin.ui-fields-modification');
Route::view('/adminPanel/sync-monitor', 'admin.sync-monitor.index')->name('admin.sync-monitor');
Route::view('/adminPanel/global-settings', 'admin.global-settings.index')->name('admin.global-settings');
Route::view('/adminPanel/delivery-logistics', 'admin.delivery-logistics')->name('admin.delivery-logistics');
Route::get('/adminPanel/quiz', [QuizeCrudController::class, 'index'])->name('admin.quiz.index');
Route::get('/adminPanel/quiz/create', [QuizeCrudController::class, 'create'])->name('admin.quiz.create');
Route::post('/adminPanel/quiz/questions', [QuizeCrudController::class, 'storeQuestion'])->name('admin.quiz.questions.store');
Route::post('/adminPanel/quiz/questions/{questionId}/toggle', [QuizeCrudController::class, 'toggleQuestionStatus'])->name('admin.quiz.questions.toggle');
Route::delete('/adminPanel/quiz/questions/{questionId}', [QuizeCrudController::class, 'destroyQuestion'])->name('admin.quiz.questions.destroy');

Route::group(['prefix' => 'adminPanel', 'as' => 'admin.'], function () {
    Route::get('/role-permission', [RolePermissionAdminCrudController::class, 'index'])->name('role-permission');
    Route::post('/role-permission/roles', [RolePermissionAdminCrudController::class, 'storeRole'])->name('role-permission.roles.store');
    Route::post('/role-permission/users', [RolePermissionAdminCrudController::class, 'storeUser'])->name('role-permission.users.store');
    Route::post('/role-permission/matrix', [RolePermissionAdminCrudController::class, 'saveRolePermissions'])->name('role-permission.matrix.save');
    Route::post('/role-permission/overrides', [RolePermissionAdminCrudController::class, 'storeUserOverride'])->name('role-permission.overrides.store');
    Route::post('/role-permission/delegations', [RolePermissionAdminCrudController::class, 'storeDelegatedAccess'])->name('role-permission.delegations.store');
    Route::post('/role-permission/impersonations', [RolePermissionAdminCrudController::class, 'storeImpersonationSession'])->name('role-permission.impersonations.store');
    Route::post('/role-permission/impersonations/{sessionId}/stop', [RolePermissionAdminCrudController::class, 'stopImpersonationSession'])->name('role-permission.impersonations.stop');
    Route::get('/role-permission/users/search', [RolePermissionAdminCrudController::class, 'searchUsers'])->name('role-permission.users.search');
    Route::view('/role-permission/add-role', 'admin.RolePermissions.add-role')->name('role-permission.add-role');
    Route::view('/role-permission/add-permission', 'admin.RolePermissions.add-permission')->name('role-permission.add-permission');
    Route::view('/role-permission/assign-dept-role', 'admin.RolePermissions.assign-dept-role')->name('role-permission.assign-dept-role');
    Route::view('/role-permission/add-override', 'admin.RolePermissions.add-override')->name('role-permission.add-override');
    Route::view('/role-permission/add-delegation', 'admin.RolePermissions.add-delegation')->name('role-permission.add-delegation');
    Route::view('/role-permission/grant-impersonation', 'admin.RolePermissions.grant-impersonation')->name('role-permission.grant-impersonation');
});
