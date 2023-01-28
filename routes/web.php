<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerLoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerRegisterController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubcategoryController;
use App\Models\Cart;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\StripePaymentController;
use App\Models\Customerlogin;

//Frontend
Route::get('/', [FrontendController::class, 'home'])->name('index');
Route::get('/product/details/{slug}', [FrontendController::class, 'details'])->name('product.details');
Route::post('/getSize', [FrontendController::class, 'getSize']);
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/category/product/{category_id}', [FrontendController::class, 'category_product'])->name('category.product');


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

//users
Route::get('/users', [UserController::class, 'users'])->name('user');
Route::get('/user/delete/{user_id}', [UserController::class, 'delete'])->name('delete');
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::post('/name/update', [UserController::class, 'name_update'])->name('name.update');
Route::post('/password/update', [UserController::class, 'pass_update'])->name('pass.update');
Route::post('/photo/update', [UserController::class, 'photo_update'])->name('photo.update');
Route::post('/add/user', [UserController::class, 'user_register'])->name('user.register');

//Category
Route::get('/category', [CategoryController::class, 'category'])->name('category');
Route::post('/category/store', [CategoryController::class, 'category_store'])->name('category.store');
Route::get('/category/delete/{category_id}', [CategoryController::class, 'category_delete'])->name('category.delete');
Route::get('/category/restore/{category_id}', [CategoryController::class, 'category_restore'])->name('category.restore');
Route::get('/category/force/delete/{category_id}', [CategoryController::class, 'category_force_delete'])->name('category.force.delete');
Route::get('/category/edit/{category_id}', [CategoryController::class, 'category_edit'])->name('category.edit');
Route::post('/category/update', [CategoryController::class, 'category_update'])->name('category.update');

//Subcategory
Route::get('/subcategory', [SubcategoryController::class, 'subcategory'])->name('subcategory');
Route::POST('/subcategory/store', [SubcategoryController::class, 'subcategory_store'])->name('subcategory.store');
Route::POST('/getsubcategory', [SubcategoryController::class, 'getsubcategory']);

//Porduct
Route::get('/add/product', [ProductController::class, 'add_product'])->name('add.product');
Route::post('/getSubcategory', [ProductController::class, 'getSubcategory']);
Route::post('/product/store', [ProductController::class, 'product_store'])->name('product.store');
Route::get('/product/list', [ProductController::class, 'product_list'])->name('product.list');
Route::get('/product/inventory/{product_id}', [ProductController::class, 'add_inventory'])->name('add.inventory');
Route::post('/product/inventory/store', [ProductController::class, 'inventory_store'])->name('inventory.store');
Route::get('/product/delete/{product_id}', [ProductController::class, 'product_delete'])->name('product.delete');

//Variation
Route::get('/product/variation', [ProductController::class, 'add_variation'])->name('add.variation');
Route::post('/add/color', [ProductController::class, 'add_color'])->name('add.color');
Route::post('/add/size', [ProductController::class, 'add_size'])->name('add.size');

//brand
Route::get('/product/brand', [ProductController::class, 'brand'])->name('brand');
Route::post('/brand/store', [ProductController::class, 'add_brand'])->name('add.brand');



//Customer Login/Register
Route::get('/customer/register/login', [FrontendController::class, 'customer_register_login'])->name('customer.register.login');
Route::post('/customer/store', [CustomerRegisterController::class, 'customer_store'])->name('customer.store');
Route::post('/customer/login', [CustomerLoginController::class, 'customer_login'])->name('customer.login');
Route::get('/customer/logout', [CustomerLoginController::class, 'customer_logout'])->name('customer.logout');
Route::get('/customer/profile', [CustomerController::class, 'customer_profile'])->name('customer.profile')->middleware('customerlogin');
Route::post('/customer/profile/update', [CustomerController::class, 'customer_profile_update'])->name('profile.update');
Route::get('/customer/order', [CustomerController::class, 'customer_order'])->name('customer.order');
Route::get('customer/email/verify/{token}', [CustomerRegisterController::class, 'customer_email_verify']);


//wishlist
Route::get('/wishlist', [CartController::class, 'wishlist'])->name('wishlist');
Route::get('/wishlist/remove/{wishlist_id}', [CartController::class, 'wishlist_remove'])->name('wishlist.remove');

//Cart
Route::post('/cart/store', [CartController::class, 'cart_store'])->name('cart.store');
Route::get('/cart/remove/{cart_id}', [CartController::class, 'remove_cart'])->name('remove.cart');
Route::get('/clear/cart', [CartController::class, 'clear_cart'])->name('clear.cart');
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::post('/cart/update', [CartController::class, 'update_cart'])->name('update.cart');

//Coupon
Route::get('/coupon', [CouponController::class, 'coupon'])->name('coupon');
Route::post('/coupon/store', [CouponController::class, 'coupon_store'])->name('coupon.store');

//CheckOut
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/getCity', [CheckoutController::class, 'getCity']);
Route::post('/order/store', [CheckoutController::class, 'order_store'])->name('order.store');
Route::get('/order/success', [CheckoutController::class, 'order_success'])->name('order.success');

//Orders
Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
Route::post('/order/status', [OrderController::class, 'order_status'])->name('order.status');
Route::get('/invoice/download/{order_id}', [OrderController::class, 'invoice_download'])->name('download.invoice');

// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::get('/pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END


//Stripe
Route::controller(StripePaymentController::class)->group(function () {
    Route::get('stripe', 'stripe')->name('stripe');
    Route::post('stripe', 'stripePost')->name('stripe.post');
});


//Role manager
Route::get('/role', [RoleController::class, 'role'])->name('role');
Route::post('/add/permission', [RoleController::class, 'add_permission'])->name('add.permission');
Route::post('/add/role', [RoleController::class, 'add_role'])->name('add.role');
Route::post('/assign/role', [RoleController::class, 'assign_role'])->name('assign.role');
Route::get('/remove/user/role/{user_id}', [RoleController::class, 'remove_user_role'])->name('remove.user.role');
Route::get('/edit/role/{role_id}', [RoleController::class, 'edit_role'])->name('role.edit');
Route::post('/update/role', [RoleController::class, 'update_role'])->name('update.role');

//Review
Route::post('/review/store', [CustomerController::class, 'review_store'])->name('review.store');


//Reset Password
Route::get('/customer/pass/reset/req', [CustomerController::class, 'customer_pass_reset_req'])->name('customer.pass.reset.req');
Route::post('/customer/pass/reset/send', [CustomerController::class, 'customer_pass_reset_req_send'])->name('customer.pass.reset.req.send');
Route::get('/customer/pass/reset/form/{token}', [CustomerController::class, 'customer_pass_reset_form'])->name('customer.pass.reset.form');
Route::post('/customer/pass/reset', [CustomerController::class, 'customer_pass_reset'])->name('customer.pass.reset');


//Social Login
Route::get('/github/redirect', [GithubController::class, 'github_redirect'])->name('github.redirect');
Route::get('/github/callback', [GithubController::class, 'github_callback'])->name('github.callback');

Route::get('/google/redirect', [GoogleController::class, 'google_redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleController::class, 'google_callback'])->name('google.callback');

Route::get('/facebook/redirect', [FacebookController::class, 'facebook_redirect'])->name('facebook.redirect');
Route::get('/facebook/callback', [FacebookController::class, 'facebook_callback'])->name('facebook.callback');
