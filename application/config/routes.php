<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['admin'] = 'user';
$route['acl'] = 'acl/Acl_roles';
// Live controller
$route['(:num)'] = 'home/index/$1';
$route['livecontroller'] = 'live_auction_controller/livecontroller/index';
$route['main-screen'] = 'screens/main_screen'; 
$route['bid-screen'] = 'screens/screen_two';
// $route['screen-three/(:any)/(:any)'] = 'live_auction_controller/livecontroller/screen_three/$1/$2';
$route['screen-three'] = 'screens/screen_three';
$route['image-screen'] = 'screens/screen_three_default';

$route['livecontroller/getAuctionItems'] = 'live_auction_controller/livecontroller/getAuctionItems';
$route['livecontroller/getAuctionItemDetail'] = 'live_auction_controller/livecontroller/getAuctionItemDetail';
$route['livecontroller/updateLiveAuctionStatus'] = 'live_auction_controller/livecontroller/updateLiveAuctionStatus';
$route['livecontroller/sold/items'] = 'live_auction_controller/livecontroller/sold_items';
$route['livecontroller/initialAuctionBid'] = 'live_auction_controller/livecontroller/initialAuctionBid';
$route['livecontroller/bidLogAPi'] = 'live_auction_controller/livecontroller/bidLogAPi';
$route['livecontroller/retractAuctionBid'] = 'live_auction_controller/livecontroller/retractAuctionBid';
$route['livecontroller/retractAllAuctionBid'] = 'live_auction_controller/livecontroller/retractAllAuctionBid';
$route['livecontroller/rollBackAuctionBid'] = 'live_auction_controller/livecontroller/rollBackAuctionBid';
$route['livecontroller/soldAuctionBid'] = 'live_auction_controller/livecontroller/soldAuctionBid';
$route['livecontroller/approvalSoldAuctionBid'] = 'live_auction_controller/livecontroller/approvalSoldAuctionBid';
$route['livecontroller/provisionalSoldAuctionBid'] = 'live_auction_controller/livecontroller/provisionalSoldAuctionBid';
$route['livecontroller/notSoldAuctionBid'] = 'live_auction_controller/livecontroller/notSoldAuctionBid';
$route['livecontroller/getSoldItemCount'] = 'live_auction_controller/livecontroller/getSoldItemCount';
$route['livecontroller/getAuctionUsersList'] = 'live_auction_controller/livecontroller/getAuctionUsersList';
$route['livecontroller/getAuctionSoldItems'] = 'live_auction_controller/livecontroller/getAuctionSoldItems';
$route['livecontroller/updateSoldstatus'] = 'live_auction_controller/livecontroller/updateSoldstatus';
$route['livecontroller/updateitembuyer'] = 'live_auction_controller/livecontroller/updateitembuyer';
// end live auction controller

$route['jobcard'] = 'jobcard/index';
$route['customers'] = 'users/users_sellers_buyers';
$route['page/error'] = 'template/templates/page_403';
$route['page/forbidden'] = 'template/templates/forbidden';
$route['user/forgot'] = 'user/user/forgot_password_form';
$route['login/jobcard/forgot'] = 'user/user/jobcard_forgot_password_form';
$route['auction/items/(:any)'] = 'auction/view_auction_items/$1';
$route['auction/liveitems/(:any)'] = 'auction/view_live_auction_items/$1';
$route['auction/update_bulk_lotting'] = 'auction/update_bulk_lotting';
$route['items/details/(:any)'] = 'items/item_detail/$1';
$route['auction/details/(:any)/(:any)'] = 'auction/item_detail/$1/$2';
$route['auction/livedetails/(:any)/(:any)'] = 'auction/live_item_detail/$1/$2';
$route['auction/online-auction/(:any)'] = 'auction/OnlineAuction/index/$1';
$route['auction/online-auction/details/(:any)/(:any)'] = 'auction/OnlineAuction/item_detail/$1/$2';

$route['auction/live-auction'] = 'auction/LiveAuction/index';
$route['auction/live-auction-items/(:any)'] = 'auction/LiveAuction/items/$1';
$route['auction/live-auction/details/(:any)/(:any)'] = 'auction/LiveAuction/item_detail/$1/$2';

//User Deposits Item wise
$route['user-deposits/(:any)'] = 'users/user_deposits/$1';
$route['user-remove-deposits'] = 'users/remove_auction_item_deposit';


//User Payments
$route['user-payments/(:any)'] = 'users/user_payments/$1';
$route['user-payables/(:any)'] = 'users/user_payables/$1';
$route['security-adjust/(:any)/(:any)/(:any)/(:any)'] = 'users/adjust_security/$1/$2/$3/$4';
$route['deposit-adjust/(:any)/(:any)/(:any)'] = 'users/adjust_deposit/$1/$2/$3';
$route['user-payment'] = 'customer/payments';
$route['make-payment/(:any)/(:any)'] = 'users/make_payment/$1/$2';
$route['pay-payment'] = 'users/pay_payment';
$route['receipt/(:any)'] = 'users/payment_receipt/$1';
$route['buyer-invoice/(:any)'] = 'users/buyer_invoice/$1';
$route['buyer-statement/(:any)'] = 'users/buyer_statement/$1';
$route['buyer-payment-detail/(:any)'] = 'users/buyer_payment_detail/$1';
$route['seller-invoice/(:any)'] = 'users/seller_invoice/$1';
$route['view-seller-invoice/(:any)'] = 'users/view_seller_tex_invoice/$1';
$route['seller-statement/(:any)'] = 'users/seller_statement/$1';
$route['view-seller-statement/(:any)'] = 'users/view_seller_statement/$1';
$route['buyer-pay/(:any)/(:any)'] = 'users/pay_to_seller/$1/$2';


$route['view-return-invoice/(:any)'] = 'items/view_return_tex_invoice/$1';



//User Deposits General
$route['user-deposits-general/(:any)'] = 'users/user_deposits_general/$1';
$route['user-deposits-general-form/(:any)'] = 'users/user_deposits_general_form/$1';
$route['user-deposits-return-form/(:any)'] = 'users/user_deposits_return_form/$1';
$route['user-deposits-general-add'] = 'users/add_deposit';
$route['user-deposits-general-return'] = 'users/return_deposit';
$route['user-remove-general-deposits'] = 'users/remove_deposit';


/*
	
*/
$route['about/mission'] = 'home/about_mission';
$route['about/team'] = 'home/about_team';


//language handling
$route['language/(:any)'] = 'template/templates/language/$1';



//email routs
$route['test-email'] = 'email/Email_controller/index';
$route['my-test-email'] = 'email/Email_controller/my_test_email';
$route['sale-out-email/(:any)/(:any)'] = 'cronjob/Cronjob/sale_out_email/$1/$2';

// settings routes
$route['delete-bank-detail'] = 'settings/remove_bankDetail';



//customer dashboard routes
$route['customer'] = 'customer/dashboard';
$route['deposits'] = 'customer/deposits';
$route['sell-item'] = 'customer/sell_item';
$route['deposit'] = 'customer/deposit';
$route['profile'] = 'customer/profile';
$route['change-password'] = 'customer/change_password';
$route['logout'] = 'customer/logout';
$route['balance'] = 'customer/balance';
$route['user-bids'] = 'customer/bid';

//visitor controller routes 
$route['about-us'] = 'visitor/about_us';
$route['contact-us'] = 'visitor/contact_us';
$route['faqs'] = 'visitor/faqs';
$route['livestream'] = 'visitor/livestream';

//home controller routes 
$route['user-login'] = 'home/login';
$route['user-register'] = 'home/register';
$route['terms-conditions'] = 'home/terms_conditions';

//live online auction
$route['live-online/(:any)'] = 'auction/OnlineAuction/live_online_auction_view/$1';
$route['live-online-detail/(:any)/(:any)'] = 'auction/OnlineAuction/live_online_item_detail/$1/$2';


// test report url
$route['inspection_report/(:any)'] = 'auction/OnlineAuction/inspection_report/$1';

// search - listing page (user side)
$route['search'] = 'search/Search/index';

$route['search/modelsByMake'] = 'search/Search/modelsByMake/$1';
$route['search/getAucStatus'] = 'search/Search/getAuctionStatus/$1';
$route['search/(:any)'] = 'search/Search/index/$1';
$route['search/(:num)'] = 'search/Search/index/$1';
$route['search/catalog/(:num)'] = 'search/Search/printCatalog/$1';

// header search
$route['searchItems'] = 'search/Search/searchItems';
$route['searchItems/(:num)'] = 'search/Search/searchItems/$1';

//search end

//Auth Api
$route['api/v1'] = 'getapi/Getapi';
$route['api/v1/registerUser'] = 'getapi/Getapi/registerUser';
$route['api/v1/fb_login_register'] = 'getapi/Getapi/fb_login_register';
$route['api/v1/google_login_register'] = 'getapi/Getapi/google_login_register';
$route['api/v1/apple_login_register'] = 'getapi/Getapi/apple_login_register';
$route['api/v1/deauthorize'] = 'getapi/Getapi/deauthorize';
$route['api/v1/loginUser'] = 'getapi/Getapi/loginUser';
$route['api/v1/token_update'] = 'getapi/Getapi/token_update';

$route['api/v1/otp'] = 'getapi/Getapi/otp';
$route['api/v1/sendotp'] = 'getapi/Getapi/sendotp';
$route['api/v1/verifyOtp'] = 'getapi/Getapi/verifyOtp';
$route['api/v1/resendOtp'] = 'getapi/Getapi/resendOtp';
$route['api/v1/forgotPassword'] = 'getapi/Getapi/forgotPassword';
$route['api/v1/updatePassword'] = 'getapi/Getapi/updatePassword';
$route['api/v1/changePassword'] = 'getapi/Getapi/changePassword';
$route['api/v1/getUserProfile'] = 'getapi/Getapi/getUserProfile';

//Auth end

//Auction Api

$route['api/v1/categories_data/(:any)'] = 'getapi/Getapi/categories_data/$1';
$route['api/v1/upcoming_auction_data'] = 'getapi/Getapi/upcoming_auction_data';
$route['api/v1/AllFeaturedItems'] = 'getapi/Getapi/AllFeaturedItems';
$route['api/v1/AllHomeListing'] = 'getapi/Getapi/AllHomeListing';
$route['api/v1/getOnlineLiveAuctions/(:any)'] = 'getapi/Getapi/getOnlineLiveAuctions/$1';
$route['api/v1/all_categories'] = 'getapi/Getapi/all_categories';
$route['api/v1/get_all_users'] = 'getapi/Getapi/get_all_users';
$route['api/v1/AllHomeListing2'] = 'getapi/Getapi/AllHomeListing2';

$route['api/v1/delete_user_row/(:any)'] = 'getapi/Getapi/delete_user_row/$1';


//search

$route['api/v1/search'] = 'getapi/Getapi/search';
$route['api/v1/search/(:any)'] = 'getapi/Getapi/search/$1';
$route['api/v1/search/(:num)'] = 'getapi/Getapi/search/$1';
$route['api/v1/search/catalog/(:num)'] = 'getapi/Getapi/printCatalog/$1';
$route['api/v1/getAuctionItems'] = 'getapi/Getapi/getAuctionItems';
$route['api/v1/getAuctionItems3'] = 'getapi/Getapi/getAuctionItems3';

//Search end


$route['api/v1/live-online_2/(:any)/(:any)'] = 'getapi/Getapi/live_online_auction_view_2/$1/$2';//live
$route['api/v1/live-online/(:any)'] = 'getapi/Getapi/live_online_auction_view/$1';
$route['api/v1/live-online-detail/(:any)/(:any)'] = 'getapi/Getapi/live_online_item_detail/$1/$2';
$route['api/v1/place_bid_live'] = 'getapi/Getapi/place_bid_live';
$route['api/v1/broadcast_pusher/(:any)/(:any)'] = 'getapi/Getapi/broadcast_pusher/$1/$2';
$route['api/v1/get_bid_log'] = 'getapi/Getapi/get_bid_log';
$route['api/v1/mix_function'] = 'getapi/Getapi/mix_function';
$route['api/v1/mix_function2'] = 'getapi/Getapi/mix_function2';
$route['api/v1/get_all_lots'] = 'getapi/Getapi/get_all_lots';
$route['api/v1/get_winning_lots'] = 'getapi/Getapi/get_winning_lots';
$route['api/v1/get_hall_auto_bids'] = 'getapi/Getapi/get_hall_auto_bids';
$route['api/v1/get_item_fields_data'] = 'getapi/Getapi/get_item_fields_data';
$route['api/v1/get_current_lot'] = 'getapi/Getapi/get_current_lot';
$route['api/v1/get_upcoming_auctions'] = 'getapi/Getapi/get_upcoming_auctions';
$route['api/v1/get_winner_status_model'] = 'getapi/Getapi/get_winner_status_model';

//live end

//online

$route['api/v1/online-auction/(:any)'] = 'getapi/Getapi/online_auction/$1';
$route['api/v1/online-auction/details/(:any)/(:any)'] = 'getapi/Getapi/item_detail/$1/$2';
$route['api/v1/placebid'] = 'getapi/Getapi/placebid';

$route['api/v1/three_d/(:any)'] = 'getapi/Getapi/three_d/$1';


//online end


//profile
$route['api/v1/customer/delete_doc_type/(:any)'] = 'getapi/Getapi/delete_doc_type/$1';
$route['api/v1/customer/insert_doc_type'] = 'getapi/Getapi/insert_doc_type';
$route['api/v1/customer/update_doc_type'] = 'getapi/Getapi/update_doc_type';
$route['api/v1/customer/select_doc_type'] = 'getapi/Getapi/select_doc_type';
$route['api/v1/customer/profile'] = 'getapi/Getapi/profile';
$route['api/v1/customer/update_profile'] = 'getapi/Getapi/update_profile';
$route['api/v1/customer/save_profile_image'] = 'getapi/Getapi/save_profile_image';

$route['api/v1/userBids'] = 'getapi/Getapi/userBids';
$route['api/v1/userBids2'] = 'getapi/Getapi/userBids2';
$route['api/v1/userBids3'] = 'getapi/Getapi/userBids3';


$route['api/v1/wishList'] = 'getapi/Getapi/wishList';
$route['api/v1/getWishList'] = 'getapi/Getapi/getWishList';
$route['api/v1/getWishList2'] = 'getapi/Getapi/getWishList2';


$route['api/v1/docs'] = 'getapi/Getapi/docs';
$route['api/v1/docsLoad'] = 'getapi/Getapi/docsLoad';
$route['api/v1/save_user_documents'] = 'getapi/Getapi/save_user_documents';
$route['api/v1/delete_customerDocs'] = 'getapi/Getapi/delete_customerDocs';

//Payment
$route['api/v1/customer/deposit'] = 'getapi/Getapi/deposit';
$route['api/v1/customer/cradit_card'] = 'getapi/Getapi/cradit_card';
$route['api/v1/customer/paytabsReturnURL'] = 'getapi/Getapi/paytabsReturnURL';
$route['api/v1/customer/add_bank_slip'] = 'getapi/Getapi/add_bank_slip';
$route['api/v1/customer/item_deposit'] = 'getapi/Getapi/item_deposit';
$route['api/v1/customer/get_ai_list'] = 'getapi/Getapi/get_ai_list';
$route['api/v1/customer/get_ai_deposit'] = 'getapi/Getapi/get_ai_deposit';
$route['api/v1/customer/bankDetail'] = 'getapi/Getapi/bankDetail';

//inventory
$route['api/v1/inventory'] = 'getapi/Getapi/inventory';
$route['api/v1/sell_item'] = 'getapi/Getapi/sell_item';
$route['api/v1/get_item_fields'] = 'getapi/Getapi/get_item_fields';
$route['api/v1/get_makes_options'] = 'getapi/Getapi/get_makes_options';
$route['api/v1/get_subcategories'] = 'getapi/Getapi/get_subcategories';
$route['api/v1/get_model_options'] = 'getapi/Getapi/get_model_options';
$route['api/v1/save_item'] = 'getapi/Getapi/save_item';
$route['api/v1/save_item2'] = 'getapi/Getapi/save_item2';
$route['api/v1/save_item_file_images'] = 'getapi/Getapi/save_item_file_images';

//term condition
$route['api/v1/terms_conditions'] = 'getapi/Getapi/terms_conditions';
$route['api/v1/contact_us'] = 'getapi/Getapi/contact_us';
$route['api/v1/our_team'] = 'getapi/Getapi/our_team';
$route['api/v1/about_us'] = 'getapi/Getapi/about_us';
$route['api/v1/faqs'] = 'getapi/Getapi/faqs';
$route['api/v1/liveStreaming'] = 'getapi/Getapi/liveStreaming';
$route['api/v1/qualityPolicy'] = 'getapi/Getapi/qualityPolicy';

//Language

$route['api/v1/lang_arabic'] = 'getapi/Getapi/lang_arabic';
$route['api/v1/lang_english'] = 'getapi/Getapi/lang_english';

//Fcm
$route['api/v1/fcm'] = 'getapi/Fcm/home';
$route['api/v1/push'] = 'getapi/Fcm/sendPushNotification';
$route['api/v1/update_fcm'] = 'getapi/Getapi/update_fcm';
$route['api/v1/fcm_to_email'] = 'getapi/Getapi/fcm_to_email';

