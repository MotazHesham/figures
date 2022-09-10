<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/ 

Route::post('get_date',function(Request $request){
	$date = strtotime($request->date);
	return format_Date($date);
})->name('get_date');

Route::post('get_date_time',function(Request $request){
	$date = strtotime($request->date);
	return format_Date_time($date);
})->name('get_date_time');

Route::get('alert_history',function(){
	$alerts = \App\Models\UserAlert::where('type','history')->get();
	foreach($alerts as $alert){
		$array = explode(' ',$alert->alert_text);
		$alert->alert_link = end($array);
		$alert->save();
	}
	
	return 'success';
});

//Route::get('test_mail','CheckoutController@test_mail');

//paymob
Route::get('paymob/callback','PayMobController@processedCallback');

Route::post('/save-token', 'PushNotificationController@saveToken')->name('save-token');

Route::get('firebase-messaging-sw.js', function(){return response()->view('firebase-messaging-sw')
	->header('Content-Type', 'application/javascript');});

Route::get('/track_your_order', 'HomeController@trackOrder')->name('orders.track');

//login for all
Route::get('user/login','UserAuthController@login_form')->name('user.login.form'); 

//register
Route::get('customer/register','UserAuthController@register_form')->name('user.register.form');
Route::post('user/register','UserAuthController@register')->name('user.register');

//social - login
Route::get('/social-login/redirect/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.login');
Route::get('/social-login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');

//when b
Route::post('/users/login/cart', 'HomeController@cart_login')->name('cart.login.submit');

//forgetpassword
Route::group(['prefix'=> 'forgetpassword'],function(){

	Route::post('/create/token','ForgetPasswordController@create_token')->name('forgetpassword.create.token');
	Route::get('/{token}','ForgetPasswordController@find')->name('forgetpassword.find.token');
	Route::post('/update','ForgetPasswordController@reset')->name('forgetpassword.update');
}); 

Auth::routes();
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::post('/language', 'HomeController@changeLanguage')->name('language.change');
Route::post('/currency', 'CurrencyController@changeCurrency')->name('currency.change');

Route::post('/subcategories/get_subcategories_by_category', 'Admin\Products\SubCategoryController@get_subcategories_by_category')->name('subcategories.get_subcategories_by_category');
Route::post('/subsubcategories/get_subsubcategories_by_subcategory', 'Admin\Products\SubSubCategoryController@get_subsubcategories_by_subcategory')->name('subsubcategories.get_subsubcategories_by_subcategory');
Route::post('/subsubcategories/get_brands_by_subsubcategory', 'Admin\Products\SubSubCategoryController@get_brands_by_subsubcategory')->name('subsubcategories.get_brands_by_subsubcategory');
Route::post('/subsubcategories/get_attributes_by_subsubcategory', 'Admin\Products\SubSubCategoryController@get_attributes_by_subsubcategory')->name('subsubcategories.get_attributes_by_subsubcategory');

//Home Page
Route::get('/', 'HomeController@index')->name('home');

//landingpages
Route::get('calender','CalenderController@calenderhome')->name('calenderhome');
Route::get('/seller', 'HomeController@seller_landingpage')->name('seller_landingpage'); 
Route::get('/designer', 'HomeController@designer_landingpage')->name('designer_landingpage'); 

Route::post('/home/section/featured', 'HomeController@load_featured_section')->name('home.section.featured');
Route::post('/home/section/best_selling', 'HomeController@load_best_selling_section')->name('home.section.best_selling');
Route::post('/home/section/home_categories', 'HomeController@load_home_categories_section')->name('home.section.home_categories');
Route::post('/home/section/best_sellers', 'HomeController@load_best_sellers_section')->name('home.section.best_sellers');

//category dropdown menu ajax call
Route::post('/category/nav-element-list', 'HomeController@get_category_items')->name('category.elements');

//Flash Deal Details Page
Route::get('/flash-deal/{slug}', 'HomeController@flash_deal_details')->name('flash-deal-details'); 

//subscribe
Route::resource('subscribers','SubscriberController');

//policies
Route::get('/sellerpolicy', 'PolicyController@sellerpolicy')->name('sellerpolicy');
Route::get('/returnpolicy', 'PolicyController@returnpolicy')->name('returnpolicy');
Route::get('/supportpolicy', 'PolicyController@supportpolicy')->name('supportpolicy');
Route::get('/terms', 'PolicyController@terms')->name('terms');
Route::get('/privacypolicy', 'PolicyController@privacypolicy')->name('privacypolicy');

//product details images
Route::post('/listings/product/images', 'ListingController@product_images')->name('listings.product.images');

Route::group(['middleware' => 'auth'], function(){
	
	//chatting
	Route::resource('messages','MessageController');  
	Route::resource('conversations','ConversationController');
	Route::post('conversations/refresh','ConversationController@refresh')->name('conversations.refresh');

	//calender
	Route::get('user/calender','CalenderController@index')->name('calender');
	Route::post('/addevent','CalenderController@addevent')->name('calender.addevent');
	Route::get('/delete/{id}','CalenderController@delete')->name('calender.delete');

	//reviews
	Route::resource('/reviews', 'ReviewController'); 

	//profile
	Route::get('/user/profile', 'UserAuthController@show_profile')->name('profile');
	Route::post('/update/profile', 'UserAuthController@update_profile')->name('user.profile.update');
	Route::post('/update_password/profile', 'UserAuthController@update_password')->name('user.profile.update_password');

	//commissions requests
	Route::get('request_commission','CommissionRequestController@index')->name('orders.request_commission.index');
	Route::get('request_commission/edit/{id}','CommissionRequestController@edit')->name('orders.request_commission.edit');
	Route::post('request_commission/update','CommissionRequestController@update')->name('orders.request_commission.update');
	Route::post('request_commission','CommissionRequestController@store')->name('orders.request_commission.store');
	Route::get('request_commission/destroy/{id}','CommissionRequestController@destroy')->name('orders.request_commission.destroy');
	Route::get('request_commission/pay/{id}','CommissionRequestController@pay')->name('orders.request_commission.pay');

	Route::get('request_commission/seller/edit/{id}','CommissionRequestController@seller_edit')->name('orders.request_commission.seller_edit');
	Route::get('request_commission/seller','CommissionRequestController@seller')->name('orders.request_commission.seller');

	//design 
	Route::resource('design','DesignController');
	Route::get('/design/destroy/{id}', 'DesignController@destroy')->name('design.destroy');

	//collections
	Route::get('collections','CollectionController@index')->name('collections.index');
	Route::get('collections/mockups/{id}','CollectionController@mockups')->name('collections.mockups'); 
	Route::get('collections/start/{id}','CollectionController@start')->name('collections.start'); 

	//listings
	Route::resource('listings','ListingController');
	Route::get('/listings/destroy/{id}', 'ListingController@destroy')->name('listings.destroy');
	
	Route::resource('wishlists','WishlistController');
	Route::post('/wishlists/remove', 'WishlistController@remove')->name('wishlists.remove');
});

//my_store
Route::get('store/{store_name}','MystoreController@index')->name('my_store.index');
Route::get('store/{store_name}?category={category_slug}', 'MystoreController@index')->name('my_store.category');  
Route::get('store/{store_name}?subcategory={subcategory_slug}', 'MystoreController@index')->name('my_store.subcategory');
Route::get('store/{store_name}?subsubcategory={subsubcategory_slug}', 'MystoreController@index')->name('my_store.subsubcategory');

//cart
Route::get('/cart', 'CartController@index')->name('cart');
Route::post('/edit', 'CartController@edit')->name('cart.edit');
Route::patch('/update/{cart}', 'CartController@update')->name('cart.update');
Route::post('/cart/nav-cart-items', 'CartController@updateNavCart')->name('cart.nav_cart');
Route::post('/cart/show-cart-modal', 'CartController@showCartModal')->name('cart.showCartModal');
Route::post('/cart/addtocart', 'CartController@addToCart')->name('cart.addToCart');
Route::post('/cart/removeFromCart', 'CartController@removeFromCart')->name('cart.removeFromCart');
Route::post('/cart/updateQuantity', 'CartController@updateQuantity')->name('cart.updateQuantity');

//Checkout Routes
Route::group(['middleware' => ['checkout']], function(){
	Route::get('/checkout/shipping_info', 'CheckoutController@get_shipping_info')->name('checkout.shipping_info');
	Route::post('/checkout/payment', 'CheckoutController@checkout')->name('payment.checkout');
	Route::post('/checkout/payment_select', 'CheckoutController@payment_select')->name('checkout.payment_select')->middleware('prevent-back-history');
	Route::get('/checkout/order-confirmed/{id}', 'CheckoutController@order_confirmed')->name('order_confirmed');
});

//seller
Route::get('/dashboard', 'User\OrderController@index')->name('dashboard')->middleware('auth');
Route::get('/common_questions','User\CommonQuestionsController@index')->name('seller.common_questions');

//orders
Route::group(['as' => 'user.','prefix' =>'user', 'middleware' => 'auth','namespace' => 'User'], function(){ 

	//orders
	Route::resource('orders','OrderController');
	Route::group(['prefix' =>'orders', 'as' => 'orders.'], function(){ 
		Route::get('/destroy/{id}', 'OrderController@destroy')->name('destroy');
		Route::get('/print/{id}','OrderController@print')->name('print');
		Route::get('/phone/{phone}', 'OrderController@orders_by_phone')->name('by_phone');
		Route::post('/check/phone_number','OrderController@check_phone_number')->name('check_phone_number');
		Route::post('/details','OrderController@details')->name('details');

		//orders products
		Route::resource('products','OrderProductController');
		Route::post('/product_details_of_order', 'OrderProductController@product_details_of_order')->name('products.product_details_of_order');
		Route::get('/products/destroy/{id}', 'OrderProductController@destroy')->name('products.destroy');

	});

});


//categories && brands
Route::get('/brands', 'HomeController@all_brands')->name('brands.all');
Route::get('/categories', 'HomeController@all_categories')->name('categories.all');
Route::get('/search', 'HomeController@search')->name('search');
Route::get('/search?q={search}', 'HomeController@search')->name('suggestion.search');
Route::post('/ajax-search', 'HomeController@ajax_search')->name('search.ajax');
Route::post('/config_content', 'HomeController@product_content')->name('configs.update_status');

//products
Route::get('/product/{slug}', 'HomeController@product')->name('product');
Route::get('/products', 'HomeController@listing')->name('products');
Route::get('/search?category={category_slug}', 'HomeController@search')->name('products.category');
Route::get('/search?subcategory={subcategory_slug}', 'HomeController@search')->name('products.subcategory');
Route::get('/search?subsubcategory={subsubcategory_slug}', 'HomeController@search')->name('products.subsubcategory');
Route::get('/search?brand={brand_slug}', 'HomeController@search')->name('products.brand');
Route::post('/product/variant_price', 'HomeController@variant_price')->name('products.variant_price');
Route::get('/shops/visit/{slug}', 'HomeController@shop')->name('shop.visit');
Route::get('/shops/visit/{slug}/{type}', 'HomeController@filter_shop')->name('shop.visit.type');  



