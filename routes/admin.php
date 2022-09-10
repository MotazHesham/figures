<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//change theme
Route::get('theme/setcookie/{theme}','CookiesController@setcookie_theme')->name('change.theme');


//search logs
Route::post('receipts/logs', 'HomeController@logs')->name('receipts.logs');

//dashboard && profile
Route::get('/admin', 'HomeController@admin_dashboard')->name('admin.dashboard')->middleware(['auth', 'admin']);
Route::resource('profile','Admin\ProfileController');

Route::post('alert/seen','Admin\UserALertsController@notification_seen')->name('alert.seen');
Route::get('alert/all','Admin\UserALertsController@view_all')->name('alerts.all');
Route::get('alert/history','Admin\UserALertsController@view_history')->name('alerts.history');

//delivery orders
Route::group(['prefix' =>'deliveryman/orders','namespace' => 'Admin\DeliveryMan','middleware' => ['delivery_order_permission','auth']], function () {
	Route::get('/{type}','DeliveryManOrdersController@index')->name('deliveryman.orders.index');
	Route::get('/show/{id}','DeliveryManOrdersController@show')->name('deliveryman.orders.show');
	Route::get('/print/{id}','DeliveryManOrdersController@print')->name('deliveryman.orders.print');
});

//receipts company for delivery
Route::group(['prefix' => 'receipt/company', 'namespace' => 'Admin\Receipts'], function () {
	Route::post('/update_delivery_status','ReceiptCompanyController@update_delivery_status')->name('receipt.company.update_delivery_status');
	Route::post('/update_payment_status','ReceiptCompanyController@update_payment_status')->name('receipt.company.update_payment_status');
	Route::post('/cancel_order_reason', 'ReceiptCompanyController@cancel_order_reason')->name('receipt.company.cancel_order_reason');
	Route::post('/order_delay_reason', 'ReceiptCompanyController@order_delay_reason')->name('receipt.company.order_delay_reason');
	Route::post('/supplied','ReceiptCompanyController@updatesupplied')->name('receipt.company.supplied');
});

//social for delivery
Route::group(['prefix' => 'receipt/social', 'namespace' => 'Admin\Receipts'], function () {
	Route::post('/supplied','ReceiptSocialController@updatesupplied')->name('receipt.social.supplied');
	Route::post('/update_delivery_status','ReceiptSocialController@update_delivery_status')->name('receipt.social.update_delivery_status');
	Route::post('/update_payment_status','ReceiptSocialController@update_payment_status')->name('receipt.social.update_payment_status');
	Route::post('/cancel_order_reason', 'ReceiptSocialController@cancel_order_reason')->name('receipt.social.cancel_order_reason');
	Route::post('/order_delay_reason', 'ReceiptSocialController@order_delay_reason')->name('receipt.social.order_delay_reason');
});

//orders for delivery
Route::group(['prefix' => 'admin/orders', 'namespace' => 'Admin\Orders', 'as' => 'admin.orders.'], function () {
	Route::post('/supplied','OrderController@updatesupplied')->name('supplied');
	Route::post('/update_delivery_status', 'OrderController@update_delivery_status')->name('update_delivery_status');
	Route::post('/update_payment_status', 'OrderController@update_payment_status')->name('update_payment_status');
	Route::post('/cancel_order_reason', 'OrderController@cancel_order_reason')->name('cancel_order_reason');
	Route::post('/order_delay_reason', 'OrderController@order_delay_reason')->name('order_delay_reason');
});



Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){

	//wasla login
	Route::post('wasla/login','Admin\WaslaController@login')->name('wasla.login');
	Route::get('wasla/logout','Admin\WaslaController@logout')->name('wasla.logout');

	//products && category && sub category && sub sub category && brands && attribute && Reviews
	Route::group(['middleware' => 'product_permission','namespace' => 'Admin\Products' ], function () {

		//category
		Route::resource('categories','CategoryController');
		Route::get('/categories/destroy/{id}', 'CategoryController@destroy')->name('categories.destroy');
		Route::post('/categories/featured', 'CategoryController@updateFeatured')->name('categories.featured');

		//sub category
		Route::resource('subcategories','SubCategoryController');
		Route::get('/subcategories/destroy/{id}', 'SubCategoryController@destroy')->name('subcategories.destroy');

		//sub sub category
		Route::resource('subsubcategories','SubSubCategoryController');
		Route::get('/subsubcategories/destroy/{id}', 'SubSubCategoryController@destroy')->name('subsubcategories.destroy');

		//brands
		Route::resource('brands','BrandController');
		Route::get('/brands/destroy/{id}', 'BrandController@destroy')->name('brands.destroy');



		//products
		Route::group(['prefix' => 'products' ], function () {
			Route::resource('/products','ProductController');
			Route::get('/destroy/{id}', 'ProductController@destroy')->name('products.destroy');
			Route::get('/duplicate/{id}', 'ProductController@duplicate')->name('products.duplicate');
			Route::post('/sku_combination', 'ProductController@sku_combination')->name('products.sku_combination');
			Route::any('/sku_combination_edit', 'ProductController@sku_combination_edit')->name('products.sku_combination_edit');
			Route::post('/featured', 'ProductController@updateFeatured')->name('products.featured');
			Route::post('/published', 'ProductController@updatePublished')->name('products.published');
			Route::post('/update_stock','ProductController@update_stock')->name('products.update_stock');
			Route::post('/todays_deal', 'ProductController@updateTodaysDeal')->name('products.todays_deal');
			Route::post('/get_products_by_subsubcategory', 'ProductController@get_products_by_subsubcategory')->name('products.get_products_by_subsubcategory');

			//Product Export
			Route::get('/bulk-export', 'ProductBulkUploadController@export')->name('product_bulk_export.index');
		});

		//attribute
		Route::resource('attributes','AttributeController');
		Route::get('/attributes/destroy/{id}', 'AttributeController@destroy')->name('attributes.destroy');

		//Reviews
		Route::get('/reviews', 'ReviewController@index')->name('admin.reviews.index');
		Route::post('/reviews/published', 'ReviewController@updatePublished')->name('admin.reviews.published');

	});

	//mockup
	Route::group(['middleware' => 'mockup_permission' , 'namespace' => 'Admin\Mockup'], function () {
		Route::resource('mockups','MockupController');
		Route::get('/mockups/destroy/{id}', 'MockupController@destroy')->name('mockups.destroy');
		Route::post('mockups/sku_combination', 'MockupController@sku_combination')->name('mockups.sku_combination');
		Route::any('mockups/sku_combination/edit', 'MockupController@sku_combination_edit')->name('mockups.sku_combination_edit');
		Route::get('listings','ListingController@index')->name('admin.listings.index');
		Route::post('listings/preview_designs','ListingController@preview_designs')->name('admin.listings.preview_designs');
		Route::get('listings/accept/{id}','ListingController@accept')->name('admin.listings.accept');
		Route::get('listings/refuse/{id}','ListingController@refuse')->name('admin.listings.refuse');
		Route::get('listings/trash/{id}','ListingController@trash')->name('admin.listings.trash');


		//designers
		Route::resource('designers','DesignerController');
		Route::get('/designers/destroy/{id}', 'DesignerController@destroy')->name('designers.destroy');
	});

	//callender
	Route::group(['middleware' => 'calender_permission'], function () {
		Route::get('/calender','CalenderController@admin_index')->name('calender.admin');
		Route::get('/calender/delete/{id}','CalenderController@delete_calender')->name('calender.admin.delete');
		Route::get('/calender_user/delete/{id}','CalenderController@delete_calender_user')->name('calender_user.admin.delete');
		Route::get('/calender/by/date/{date}','CalenderController@calender_by_date')->name('calender.admin.by.date');
		Route::post('/calender/download','CalenderController@export')->name('download.calender');
	});

	Route::post('search_by_phone','Admin\Receipts\ReceiptCompanyController@searchByPhone')->name('search_by_phone');

	//Receipts
	Route::group(['prefix' => 'receipt', 'namespace' => 'Admin\Receipts'], function () {

		//Receipt Products
		Route::group(['prefix' => 'product' ,'middleware' => 'client_receipt_permission'], function () {
			Route::get('{type}','ReceiptProductController@index')->name('receipt.product');
			Route::post('/store','ReceiptProductController@store')->name('receipt.product.store');
			Route::post('/update','ReceiptProductController@update')->name('receipt.product.update');
			Route::get('/edit_photos/{id}','ReceiptProductController@edit_photos')->name('receipt.product.edit_photos');
			Route::post('/update_photos','ReceiptProductController@update_photos')->name('receipt.product.update_photos');
			Route::get('/destroy/{id}','ReceiptProductController@destroy')->name('receipt.product.destroy');
		});

		//Receipt company
		Route::group(['prefix' => 'company','middleware' => 'company_receipt_permission'], function () {
			Route::get('/','ReceiptCompanyController@index')->name('receipt.company');
			Route::get('/trashed','ReceiptCompanyController@trashed')->name('receipt.company.trashed');
			Route::get('/add','ReceiptCompanyController@add')->name('receipt.company.add');
			Route::post('/store','ReceiptCompanyController@store')->name('receipt.company.store');
			Route::get('/edit/{id}','ReceiptCompanyController@edit')->name('receipt.company.edit');
			Route::post('/update','ReceiptCompanyController@update')->name('receipt.company.update');
			Route::get('/destroy/{id}','ReceiptCompanyController@destroy')->name('receipt.company.destroy');
			Route::post('/done','ReceiptCompanyController@updateDone')->name('receipt.company.done');
			Route::post('/quickly','ReceiptCompanyController@updatequickly')->name('receipt.company.quickly');
			Route::post('/calling','ReceiptCompanyController@updatecalling')->name('receipt.company.calling');
			Route::post('/no_answer','ReceiptCompanyController@update_no_answer')->name('receipt.company.no_answer');
			Route::get('/print/{id}','ReceiptCompanyController@print')->name('receipt.company.print');
			Route::get('/pdf','ReceiptCompanyController@pdf')->name('receipt.company.pdf');
			Route::get('/duplicate/{id}','ReceiptCompanyController@duplicate')->name('receipt.company.duplicate');
			Route::post('/update_delivery_man','ReceiptCompanyController@update_delivery_man')->name('receipt.company.update_delivery_man');
			Route::post('/update_playlist_status2', 'ReceiptCompanyController@update_playlist_status2')->name('receipt.company.update_playlist_status2');
			Route::post('/playlist_users', 'ReceiptCompanyController@playlist_users')->name('receipt.company.playlist_users');
			Route::post('/send_to_wasla','ReceiptCompanyController@send_to_wasla')->name('receipt.company.send_to_wasla');
		});

		//Receipt client
		Route::group(['prefix' => 'client' ,'middleware' => 'client_receipt_permission'], function () {
			Route::get('/','ReceiptClientController@index')->name('receipt.client');
			Route::get('/add','ReceiptClientController@add')->name('receipt.client.add');
			Route::post('/store','ReceiptClientController@store')->name('receipt.client.store');
			Route::get('/edit/{id}','ReceiptClientController@edit')->name('receipt.client.edit');
			Route::post('/update','ReceiptClientController@update')->name('receipt.client.update');
			Route::get('/edit_product/{id}','ReceiptClientController@edit_product')->name('receipt.client.edit_product');
			Route::post('/update_product','ReceiptClientController@update_product')->name('receipt.client.update_product');
			Route::get('/destroy/{id}','ReceiptClientController@destroy')->name('receipt.client.destroy');
			Route::get('/product/destroy/{id}','ReceiptClientController@destroy_product')->name('receipt.client.product.destroy');
			Route::post('/store_product','ReceiptClientController@store_product')->name('receipt.client.store_product');
			Route::post('/done','ReceiptClientController@updateDone')->name('receipt.client.done');
			Route::get('/print/{id}','ReceiptClientController@print')->name('receipt.client.print');
			Route::get('/print/receive/money/{id}','ReceiptClientController@print_receive_money')->name('receipt.client.print_receive_money');
			Route::post('/quickly','ReceiptClientController@updatequickly')->name('receipt.client.quickly');
			Route::get('/duplicate/{id}','ReceiptClientController@duplicate')->name('receipt.client.duplicate');
		});

		//Receipt social
		Route::group(['prefix' => 'social' ,'middleware' => 'social_receipt_permission'], function () {
			Route::get('/add/{receipt_type}','ReceiptSocialController@add')->name('receipt.social.add');
			Route::get('/index/{receipt_type}/{confirm}','ReceiptSocialController@index')->name('receipt.social');
			Route::post('/store','ReceiptSocialController@store')->name('receipt.social.store');
			Route::get('/trashed/{receipt_type}','ReceiptSocialController@trashed')->name('receipt.social.trashed');
			Route::get('/edit/{id}','ReceiptSocialController@edit')->name('receipt.social.edit');
			Route::post('/update','ReceiptSocialController@update')->name('receipt.social.update');
			Route::get('/edit_product/{id}','ReceiptSocialController@edit_product')->name('receipt.social.edit_product');
			Route::post('/update_product','ReceiptSocialController@update_product')->name('receipt.social.update_product');
			Route::get('/destroy/{id}','ReceiptSocialController@destroy')->name('receipt.social.destroy');
			Route::get('/product/destroy/{id}','ReceiptSocialController@destroy_product')->name('receipt.social.product.destroy');
			Route::post('/store_product','ReceiptSocialController@store_product')->name('receipt.social.store_product');
			Route::post('/done','ReceiptSocialController@updateDone')->name('receipt.social.done');
			Route::post('/calling','ReceiptSocialController@updatecalling')->name('receipt.social.calling');
			Route::get('/print/{id}','ReceiptSocialController@print')->name('receipt.social.print');
			Route::get('/print_new/{id}','ReceiptSocialController@print_new')->name('receipt.social.print_new');
			Route::get('/print/receive/money/{id}','ReceiptSocialController@print_receive_money')->name('receipt.social.print_receive_money');
			Route::post('/quickly','ReceiptSocialController@updatequickly')->name('receipt.social.quickly');
			Route::post('/update_delivery_man','ReceiptSocialController@update_delivery_man')->name('receipt.social.update_delivery_man');
			Route::post('/update_playlist_status2', 'ReceiptSocialController@update_playlist_status2')->name('receipt.social.update_playlist_status2');
			Route::post('/playlist_users', 'ReceiptSocialController@playlist_users')->name('receipt.social.playlist_users');
			Route::post('/confirm','ReceiptSocialController@updateconfirm')->name('receipt.social.confirm');
			Route::post('/send_to_wasla','ReceiptSocialController@send_to_wasla')->name('receipt.social.send_to_wasla');
			Route::post('/view_products','ReceiptSocialController@view_products')->name('receipt.social.view_products');
		});


		//Receipt outgoings
		Route::group(['prefix' => 'outgoings' , 'middleware' => 'outgoings_receipt_permission'], function () {
			Route::get('/','ReceiptOutgoingsController@index')->name('receipt.outgoings');
			Route::get('/add','ReceiptOutgoingsController@add')->name('receipt.outgoings.add');
			Route::post('/store','ReceiptOutgoingsController@store')->name('receipt.outgoings.store');
			Route::get('/edit/{id}','ReceiptOutgoingsController@edit')->name('receipt.outgoings.edit');
			Route::post('/update','ReceiptOutgoingsController@update')->name('receipt.outgoings.update');
			Route::get('/edit_product/{id}','ReceiptOutgoingsController@edit_product')->name('receipt.outgoings.edit_product');
			Route::post('/update_product','ReceiptOutgoingsController@update_product')->name('receipt.outgoings.update_product');
			Route::get('/destroy/{id}','ReceiptOutgoingsController@destroy')->name('receipt.outgoings.destroy');
			Route::get('/product/destroy/{id}','ReceiptOutgoingsController@destroy_product')->name('receipt.outgoings.product.destroy');
			Route::post('/store_product','ReceiptOutgoingsController@store_product')->name('receipt.outgoings.store_product');
			Route::post('/done','ReceiptOutgoingsController@updateDone')->name('receipt.outgoings.done');
			Route::get('/print/{id}','ReceiptOutgoingsController@print')->name('receipt.outgoings.print');
		});

        //Receipt view price
		Route::group(['prefix' => 'price_view' , 'middleware' => 'price_view_receipt_permission'], function () {
			Route::get('/','ReceiptPriceViewController@index')->name('receipt.price_view');
			Route::get('/add','ReceiptPriceViewController@add')->name('receipt.price_view.add');
			Route::post('/store','ReceiptPriceViewController@store')->name('receipt.price_view.store');
			Route::get('/edit/{id}','ReceiptPriceViewController@edit')->name('receipt.price_view.edit');
			Route::post('/update','ReceiptPriceViewController@update')->name('receipt.price_view.update');
			Route::get('/destroy/{id}','ReceiptPriceViewController@destroy')->name('receipt.price_view.destroy');
			Route::get('/print/{id}','ReceiptPriceViewController@print')->name('receipt.price_view.print');
			Route::get('/edit_product/{id}','ReceiptPriceViewController@edit_product')->name('receipt.price_view.edit_product');
			Route::post('/update_product','ReceiptPriceViewController@update_product')->name('receipt.price_view.update_product');
			Route::post('/store_product','ReceiptPriceViewController@store_product')->name('receipt.price_view.store_product');
			Route::get('/product/destroy/{id}','ReceiptPriceViewController@destroy_product')->name('receipt.price_view.product.destroy');
		});
	});

	//Delivery Man list
	Route::group(['namespace' => 'Admin\PlayList' , 'middleware' => 'playlist_permission'], function () {
		Route::get('playlist/{type}','PlaylistController@index')->name('playlist.index');
		Route::post('playlist/print/receipt','PlaylistController@print')->name('playlist.print');
		Route::post('playlist/show','PlaylistController@show')->name('playlist.show');
		Route::post('playlist/update_playlist_status', 'PlaylistController@update_playlist_status')->name('playlist.update_playlist_status');
	});

	//flash deal
	Route::group(['middleware' => 'flash_deal_permission', 'namespace' => 'Admin\FlashDeal'], function () {
		//flash deal
		Route::resource('flash_deals','FlashDealController');
		Route::get('/flash_deals/destroy/{id}', 'FlashDealController@destroy')->name('flash_deals.destroy');
		Route::post('/flash_deals/update_status', 'FlashDealController@update_status')->name('flash_deals.update_status');
		Route::post('/flash_deals/update_featured', 'FlashDealController@update_featured')->name('flash_deals.update_featured');
		Route::post('/flash_deals/product_discount', 'FlashDealController@product_discount')->name('flash_deals.product_discount');
		Route::post('/flash_deals/product_discount_edit', 'FlashDealController@product_discount_edit')->name('flash_deals.product_discount_edit');
	});

	//orders
	Route::group(['middleware' => 'order_permission','namespace' => 'Admin\Orders', 'as' => 'admin.orders.','prefix' => 'orders'], function () {
		Route::get('/{type}', 'OrderController@index')->name('index');
		Route::get('/{id}/show', 'OrderController@show')->name('show');
		Route::post('/calling', 'OrderController@updatecalling')->name('calling');
		Route::get('/edit/{id}', 'OrderController@edit')->name('edit');
		Route::get('/destroy/{id}', 'OrderController@destroy')->name('destroy');
		Route::post('/show_details', 'OrderController@show_details')->name('show_details');
		Route::get('/print/{id}','OrderController@print')->name('print');
		Route::post('/update_delivery_man', 'OrderController@update_delivery_man')->name('update_delivery_man');
		Route::post('/update_order_note', 'OrderController@update_order_note')->name('update_order_note');
		Route::post('/update_playlist_status2', 'OrderController@update_playlist_status2')->name('update_playlist_status2');
		Route::post('/playlist_users', 'OrderController@playlist_users')->name('playlist_users');
		Route::post('/update_extra_commission', 'OrderController@update_extra_commission')->name('update_extra_commission');
		Route::get('/delete_product_of_order/{id}', 'OrderController@delete_product_of_order')->name('delete_product_of_order');
		Route::get('/edit_product_of_order/{id}', 'OrderController@edit_product_of_order')->name('edit_product_of_order');
		Route::post('/send_to_wasla','OrderController@send_to_wasla')->name('send_to_wasla');
	});

	//Delivery Man list
	Route::group(['middleware' => 'deliveryman_list_permission' , 'namespace' => 'Admin\DeliveryMan'], function () {
		Route::get('deliveryman/delete/{id}','DeliveryManController@delete')->name('deliveryman.delete');
		Route::resource('deliveryman','DeliveryManController');
	});

	//Customers list
	Route::group(['middleware' => 'customer_list_permission' , 'namespace' => 'Admin\Customer'], function () {
		Route::get('customer/delete/{id}','CustomerController@delete')->name('customer.delete');
		Route::resource('customer','CustomerController');
	});

	//sellers
	Route::group(['middleware' => 'seller_permission', 'namespace' => 'Admin\Sellers'], function () {

		//sellers list
		Route::resource('sellers','SellerController');
		Route::get('/sellers/destroy/{id}', 'SellerController@destroy')->name('sellers.destroy');
		Route::post('/sellers/approved', 'SellerController@updateApproved')->name('sellers.approved');

		//send message to sellers
		Route::post('send_offers','ConversationController@send_offers')->name('seller.send_offers');

		//common_questions
		Route::resource('common_questions','CommonQuestionsController');
		Route::get('/common_questions/destroy/{id}', 'CommonQuestionsController@destroy')->name('common_questions.destroy');

	});

	//conversations
	Route::group(['middleware' => 'chatting_permission', 'namespace' => 'Admin\Conversations'], function () {

		//conversations
		Route::get('conversation/{my_conversation?}','ConversationController@index')->name('admin.conversation.index');
		Route::post('conversation/refresh','ConversationController@refresh')->name('admin.conversation.refresh');
		Route::any('conversation/show/{id}','ConversationController@show')->name('admin.conversation.show');
		Route::post('conversation/new_contact','ConversationController@new_contact')->name('admin.conversation.new_contact');

	});

	// ------------------------------------------  Settings ----------------------------------------- //
	Route::group(['middleware' => 'settings_permission', 'namespace' => 'Admin\Settings'], function () {
		//links
		Route::resource('links','LinkController');
		Route::get('/links/destroy/{id}', 'LinkController@destroy')->name('links.destroy');

		//countries
		Route::resource('countries','CountryController');
		Route::post('/countries/status', 'CountryController@updateStatus')->name('countries.status');
		Route::get('/countries/destroy/{id}', 'CountryController@destroy')->name('countries.destroy');

		//Policy Controller
		Route::post('/policies/store', 'PolicyController@store')->name('policies.store');

		Route::resource('/languages', 'LanguageController');
		Route::post('/languages/update_rtl_status', 'LanguageController@update_rtl_status')->name('languages.update_rtl_status');
		Route::get('/languages/destroy/{id}', 'LanguageController@destroy')->name('languages.destroy');
		Route::get('/languages/{id}/edit', 'LanguageController@edit')->name('languages.edit');
		Route::post('/languages/{id}/update', 'LanguageController@update')->name('languages.update');
		Route::get('/add_key/{id}', 'LanguageController@add_key')->name('languages.add.key');
		Route::post('/store_key', 'LanguageController@store_key')->name('languages.store.key');
		Route::post('/languages/key_value_store', 'LanguageController@key_value_store')->name('languages.key_value_store');

		Route::get('/frontend_settings/home', 'HomeController@home_settings')->name('home_settings.index');
		Route::post('/frontend_settings/home/top_10', 'HomeController@top_10_settings')->name('top_10_settings.store');
		Route::get('/sellerpolicy/{type}', 'PolicyController@index')->name('sellerpolicy.index');
		Route::get('/returnpolicy/{type}', 'PolicyController@index')->name('returnpolicy.index');
		Route::get('/supportpolicy/{type}', 'PolicyController@index')->name('supportpolicy.index');
		Route::get('/terms/{type}', 'PolicyController@index')->name('terms.index');
		Route::get('/privacypolicy/{type}', 'PolicyController@index')->name('privacypolicy.index');

		Route::patch('generalsettings_2','GeneralSettingController@receipt_colors')->name('generalsettings_2');
		Route::resource('generalsettings','GeneralSettingController');
		Route::get('/logo','GeneralSettingController@logo')->name('generalsettings.logo');
		Route::post('/logo','GeneralSettingController@storeLogo')->name('generalsettings.logo.store');
		Route::get('/color','GeneralSettingController@color')->name('generalsettings.color');
		Route::post('/color','GeneralSettingController@storeColor')->name('generalsettings.color.store');


		//social
		Route::resource('social','SocialsNetworkController');
		Route::get('/social/destroy/{id}', 'SocialsNetworkController@destroy')->name('social.destroy');

		Route::group(['prefix' => 'frontend_settings'], function(){
			Route::resource('sliders','SliderController');
			Route::get('/sliders/destroy/{id}', 'SliderController@destroy')->name('sliders.destroy');

			Route::resource('home_banners','BannerController');
			Route::get('/home_banners/create/{position}', 'BannerController@create')->name('home_banners.create');
			Route::post('/home_banners/update_status', 'BannerController@update_status')->name('home_banners.update_status');
			Route::get('/home_banners/destroy/{id}', 'BannerController@destroy')->name('home_banners.destroy');

			Route::resource('home_categories','HomeCategoryController');
			Route::get('/home_categories/destroy/{id}', 'HomeCategoryController@destroy')->name('home_categories.destroy');
			Route::post('/home_categories/update_status', 'HomeCategoryController@update_status')->name('home_categories.update_status');
			Route::post('/home_categories/get_subsubcategories_by_category', 'HomeCategoryController@getSubSubCategories')->name('home_categories.get_subsubcategories_by_category');
		});


		//banned_phones
		Route::resource('banned_phones','BannedPhonesController');
		Route::get('/banned_phones/destroy/{id}', 'BannedPhonesController@destroy')->name('banned_phones.destroy');

	});
	// ------------------------------------------  Settings ----------------------------------------- //

	//seo settings
	Route::group(['middleware' => 'seo_permission', 'namespace' => 'Admin\Seo' ], function () {
		Route::resource('seosetting','SEOController');
	});

	//staffs
	Route::group(['middleware' => 'staff_permission', 'namespace' => 'Admin\Staffs'], function () {
		//roles
		Route::resource('roles','RoleController');
		Route::get('/roles/destroy/{id}', 'RoleController@destroy')->name('roles.destroy');

		//staffs
		Route::resource('staffs','StaffController');
		Route::get('/staffs/destroy/{id}', 'StaffController@destroy')->name('staffs.destroy');
		Route::post('/staffs/update_show_notification', 'StaffController@update_show_notification')->name('staffs.update_show_notification');


		//admins
		Route::resource('admins','AdminController');
		Route::get('/admins/destroy/{id}', 'StaffController@destroy')->name('admins.destroy');

		//borrow
		Route::any('borrow/all','BorrowController@all')->name('borrow.all');
		Route::resource('borrow','BorrowController');
		Route::post('/borrow/status_all', 'BorrowController@status_all')->name('borrow.status_all');
		Route::get('/borrow/status/{id}', 'BorrowController@status')->name('borrow.status');
		Route::get('/borrow/destroy/{id}', 'BorrowController@destroy')->name('borrow.destroy');

		//subtract
		Route::resource('subtract','SubtractController');
		Route::get('/subtract/destroy/{id}', 'SubtractController@destroy')->name('subtract.destroy');

		//borrow user
		Route::resource('borrow_user','BorrowUserController');
		Route::get('/borrow_user/destroy/{id}', 'BorrowUserController@destroy')->name('borrow_user.destroy');

		//tasks
		Route::resource('tasks','TaskController');
		Route::get('/tasks/destroy/{id}', 'TaskController@destroy')->name('tasks.destroy');

		//audit logs
		Route::get('auditlogs','AuditLogsController@index')->name('auditlogs');
		Route::get('auditlogs/show/{id}','AuditLogsController@show')->name('auditlogs.show');

		//quality_responsible
		Route::get('/quality_responsible/show','QualityResponsibleController@show')->name('quality_responsible.show');
		Route::patch('/quality_responsible/update/{id}','QualityResponsibleController@update')->name('quality_responsible.update');
	});

	//mytasks
	Route::group(['namespace' => 'Admin\Staffs'], function () {
		Route::get('in_progress','TaskController@in_progress')->name('tasks.in_progress');
		Route::get('done','TaskController@done')->name('tasks.done');
		Route::get('out_date','TaskController@out_date')->name('tasks.out_date');
		Route::post('/tasks/update_done', 'TaskController@update_done')->name('tasks.update_done');
	});


});
