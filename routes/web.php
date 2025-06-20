<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DeliveryBoyController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProxyController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
// use App\Models\Branch;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::any('/order-Label-Email/{id}', [SellerController::class, 'sellerConfirmLabel']);

//    Route::any('/seller-wallet', [SellerController::class, 'sellerWallet'])->name('seller.wallet');
//     Route::any('/seller-add-wallet', [SellerController::class, 'addWalletAmount'])->name('seller.addWalletAmount');
//     // addon this url for phonepay 2june
//     Route::match(['get', 'post'], '/wallet/payment/callback', [SellerController::class, 'walletPaymentCallback'])->name('wallet.payment.callback');


Route::fallback(function () {
    abort(404);
});


// WEB Start
Route::get('/', [WebController::class, 'index'])->name('web.index');
Route::any('/bookparcel', [WebController::class, 'bookParcel'])->name('web.bookparcel');
Route::any('/services', [WebController::class, 'service'])->name('web.services');

Route::any('/enquiry', [WebController::class, 'webEnquiry'])->name('web.enquiry');
Route::post('/add-Enquiry', [WebController::class, 'addEnquiry'])->name('admin.addEnquiry');

// delivery boy enquiry 16 june 
Route::get('/deliveryboy_enq', [WebController::class, 'DeliveryBoyEnq'])->name('web.deliveryboy_enq');
Route::post('/add-addDeliveryBoyEnq', [WebController::class, 'addDeliveryBoyEnq'])->name('web.addDeliveryBoyEnq');

// franchise enq 16 june 
Route::get('/franchise_enq', [WebController::class, 'FranchiseEnq'])->name('web.franchise_enq');
Route::post('/add-addFranchiseEnq', [WebController::class, 'addFranchiseEnq'])->name('web.addFranchiseEnq');



Route::any('/about', [WebController::class, 'about'])->name('web.about');
Route::any('/privacy', [WebController::class, 'privacy'])->name('web.privacy');
Route::any('/terms_conditions', [WebController::class, 'termsConditions'])->name('web.terms_conditions');
Route::any('/refundPolicy', [WebController::class, 'refundpolicy'])->name('web.refundPolicy');
Route::any('/trackOrders/{id?}', [WebController::class, 'trackOrder'])->name('web.trackOrder');
Route::any('/blogs', [WebController::class, 'blog'])->name('web.blog');

Route::any('/check-PinCode', [WebController::class, 'checkPinCode'])->name('web.checkPinCode');
Route::any('/parcel-details', [WebController::class, 'parcelDetails'])->name('web.parcelDetails');

Route::post('/store-Parcel-Details', [WebController::class, 'storeParcelDetails'])->name('web.storeParcelDetails');



Route::any('/reviews/{action}', [WebController::class, 'review'])->name('web.reviews');
Route::post('/addReviews', [WebController::class, 'storeReview'])->name('web.addReviews');

Route::post('/track-Order-Detail', [WebController::class, 'trackOrderDetails'])->name('web.trackOrderDetails');
Route::get('/order-Label/{id}', [WebController::class, 'orderLabel']);
Route::get('/show-details', [WebController::class, 'showDetails']);
// WEB End

// email label 
Route::get('/order-Label-Email-Cod/{id}', [WebController::class, 'orderLabelviaEmailCod']);
Route::get('/order-Label-Email-Online/{id}', [WebController::class, 'orderLabelviaEmailOnline']);

// Google Maps
Route::any('/proxy', [ProxyController::class, 'handle'])->name('proxy');

// Admin Start
Route::get('/AdminPanel', function () {
    return view('admin.login');
});
Route::post('/Admin-Login', [AdminController::class, 'adminLogin'])->name('admin.login');
Route::middleware(['checklogin'])->group(function () {

    // 16 june 
    Route::get('/service_estimited_time', [AdminController::class, 'serviceEstimitedTime']);
    Route::post('/service_estimated_time', [AdminController::class, 'storeEstimatedService'])->name('store_estimated_service');
    Route::get('/estimated-services-data', [AdminController::class, 'getEstimatedServices'])->name('estimated_services_data');
    Route::put('/admin/estimated-services/{id}', [AdminController::class, 'updateEstimatedService']);
    Route::delete('/admin/estimated-services/{id}', [AdminController::class, 'deleteEstimatedService']);
    Route::get('/admin/estimated-services/{id}', [AdminController::class, 'getEstimatedService']);



    // all cod history manage delivery panel 
    Route::post('/admin-update-cod-status', [AdminController::class, 'adminupdateCodStatus'])->name('admin.updateCodStatus');

    // admin panel delivery boy earning perticular 
    Route::get('/deliveryboy_earning/{id}', [AdminController::class, 'DeliveryboyEarning']);

    Route::get('/admin-logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
    Route::any('/admin-dashboard', [AdminController::class, 'adminDashboard']);
    Route::get('/admin-order-details/{action}', [AdminController::class, 'adminOrderDetails']);
    Route::get('/admin/toDayRevenueOrder', [AdminController::class, 'toDayRevenueOrder']);
    Route::any('/admin-revenueHistory', [AdminController::class, 'revenuedateHistory'])->name('admin.revenueHistory');


    Route::any('/super-express/{id?}', [AdminController::class, 'superExpress'])->name('admin.superExpress');
    Route::any('/addUpdate-Super-Express-Services', [AdminController::class, 'addSuperExpressServices'])->name('admin.addSuperExpressServices');

    Route::any('/express-services/{id?}', [AdminController::class, 'expressServices'])->name('admin.expressServices');
    Route::any('/addUpdate-Express-Services', [AdminController::class, 'addExpressServices'])->name('admin.addExpressServices');

    Route::any('/standard-services/{id?}', [AdminController::class, 'standardServices'])->name('admin.standardServices');
    Route::any('/addUpdate-Standard-Services', [AdminController::class, 'addStandardServices'])->name('admin.addStandardServices');

    Route::post('/update-ExSs', [AdminController::class, 'updateExSs'])->name('admin.update.ExSs');
    Route::get('/delete-services/{id}', [AdminController::class, 'deleteServices']);

    Route::any('/pin-codes', [AdminController::class, 'pinCodes'])->name('amdin.pinCodes');
    Route::any('/add-PinCode', [AdminController::class, 'addPinCode'])->name('admin.addPinCode');
    Route::get('/delete-pincode/{id}', [AdminController::class, 'deletePinCode']);

    Route::any('/category', [AdminController::class, 'adminCategory'])->name('admin.category');
    Route::any('/add-Category', [AdminController::class, 'addCategory'])->name('admin.addCategory');
    Route::get('/delete-Category/{id}', [AdminController::class, 'deleteCategory']);

    Route::any('/admin-seller-branch/{id?}', [AdminController::class, 'adminSellerBranch'])->name('admin.seller.branch');

    Route::any('/add-update-Branch/{id?}', [AdminController::class, 'addBranch'])->name('admin.addBranch');

    // All Delivery Branch 
    Route::any('/all-branch', [AdminController::class, 'allBranch'])->name('admin.allBranch');
    Route::any('/admin-branch/{id?}', [AdminController::class, 'adminBranch'])->name('admin.branch');

    // All Booking Branch 
    Route::any('/all-booking-branch', [AdminController::class, 'allBookingBranch'])->name('admin.allBookingBranch');
    Route::any('/admin-booking-branch/{id?}', [AdminController::class, 'adminBookingBranch'])->name('admin.bookingbranch');  // edit ke case me 


    Route::any('/all-seller-branch', [AdminController::class, 'allSellerBranch'])->name('admin.seller.allBranch');
    Route::any('/all-booking-branchs', [AdminController::class, 'allBookingBranchData'])->name('admin.booking.allBranch');

    Route::any('/branch-status', [AdminController::class, 'branchStatus'])->name('admin.branch.status');
    Route::get('/delete-branch/{id}', [AdminController::class, 'deleteBranch']);
    Route::get('/manage-services-type/{id}', [AdminController::class, 'manageServicesType']);
    Route::any('/get-services-type', [AdminController::class, 'servicesType'])->name('admin.servicesType');
    Route::any('/add-Services-Type', [AdminController::class, 'addServicesType'])->name('admin.addServicesType');

    Route::get('/delete-manage-services-type/{id}', [AdminController::class, 'deleteServiceType']);    // for delete 

    // Route::get('/branch-AllDeliveryBoy/{id}', [AdminController::class, 'branchAllDeliveryBoy']);
    Route::get('/branch-Manage-Branch/{id}', [AdminController::class, 'branchManageBranch']);
    Route::get('/admin-order-details/{id}/{action}', [AdminController::class, 'orderDetails']);
    Route::any('/admin-status-get', [AdminController::class, 'adminStatusGet'])->name('admin.status.get');
    Route::any('/admin-assign-add', [AdminController::class, 'adminAssignAdd'])->name('admin.assign.add');
    Route::any('/admin-assign-get', [AdminController::class, 'adminAssignGet'])->name('admin.assign.get');
    Route::any('/wallet-details/{id}', [AdminController::class, 'walletDetails']);
    Route::any('/wallet-Amount', [AdminController::class, 'walletAmount'])->name('admin.WalletAmount');

    Route::any('/admin-add-DeliveryBoy/{id?}', [AdminController::class, 'addDeliveryBoy'])->name('admin.addDeliveryBoy');
    Route::any('/admin-checkPinCode', [AdminController::class, 'checkPinCode'])->name('admin.checkPinCode');
    Route::any('/admin-add-Delivery-Boy', [AdminController::class, 'addNewDeliveryBoy'])->name('admin.addNewDeliveryBoy');
    Route::any('/all-Delivery-Boy', [AdminController::class, 'allDeliveryBoy'])->name('admin.allDeliveryBoy');
    Route::any('/admin-update-boySt', [AdminController::class, 'sellerUpdateBoySt'])->name('admin.update.boySt');
    Route::any('/admin-delete-dlyBoy', [AdminController::class, 'deleteDlyBoy'])->name('admin.delete.dlyBoy');
    // Route::any('/admin-dlyboy-data', [AdminController::class, 'adminDlyBoyData'])->name('admin.dlyboy.data');


    Route::any('/admin-cod-history', [AdminController::class, 'dateCodHistory'])->name('admin.codHistory');
    Route::any('/order-history/{id}', [AdminController::class, 'orderHistory']);
    Route::post('admin-monthOrderHistory', [AdminController::class, 'monthOrderHistory'])->name('admin.monthOrderHistory');
    Route::any('/cod-history/{id}', [AdminController::class, 'codHistory']);
    Route::any('/admin-cod-amount', [AdminController::class, 'addCodAmount']);

    // Route::any('/admin-branch-COD-History/{id}', [AdminController::class, 'branchCodHistory']);
    Route::any('/admin-branch-date-COD-History', [AdminController::class, 'branchDateCodHistory'])->name('admin.branch.date.COD.history');

    Route::any('/admin-branch-COD-History/{id}', [AdminController::class, 'branchCodHistory'])->name('admin-branch-COD-History');
    Route::any('/admin-deduct-branch-COD', [AdminController::class, 'deductBranchCod'])->name('admin.deductBranchCod'); // deduct wallet 

    // 12 june my code 
    Route::any('/admin-branch-COD-History-DateFilter', [AdminController::class, 'branchCodHistorydatefilter'])->name('admin-branch-COD-History-datefilter');
    // 16 june admin 
    Route::any('/all-deliveryboy_enq', [AdminController::class, 'allDeliveryBoyEnq'])->name('admin.deliveryboy_enq');
    Route::any('/all-franchise_enq', [AdminController::class, 'allFranchiseEnq'])->name('admin.franchise_enq');
    Route::any('/delete-deliveryboysform', [AdminController::class, 'deletedeliveryboysform'])->name('admin.delete.deliveryboysform');
    Route::any('/delete-franchiseform', [AdminController::class, 'deletefranchiseform'])->name('admin.delete.franchiseform');



    Route::any('/all-Enquiry', [AdminController::class, 'allEnquiry'])->name('admin.allEnquiry');
    Route::any('/delete-enquiry', [AdminController::class, 'deleteEnquiry'])->name('admin.delete.enquiry');
    Route::any('/edit-enquiry/{id}', [AdminController::class, 'editEnquiry']);
    Route::any('/edit-enquiry-update', [AdminController::class, 'updateEnquiry'])->name('admin.edit.enquiry');
    Route::post('/delete-enquiry-assign-branch', [AdminController::class, 'enquiryAssignBranch'])->name('admin.enquiry.assign.branch');

    Route::any('/feedbacks', [AdminController::class, 'feedback'])->name('admin.feedback');
    Route::any('/delete-feedback', [AdminController::class, 'deleteFeedBack'])->name('admin.delete.feedback');
    Route::any('/status-feedback', [AdminController::class, 'feedBackStatus'])->name('admin.feedback.status');

    Route::any('/admin-direct-orders', [AdminController::class, 'webDirectOrders'])->name('admin.DirectOrders');
    Route::any('/admin-invoice/{id}', [AdminController::class, 'adminInvoice']);
    // 14 june 
    Route::get('/monthly-admin-invoice', [AdminController::class, 'MonthlyAdminInvoice']);
    Route::get('/get-branches', [AdminController::class, 'getBranches']);

    // Route::get('/get-branches', function () {
    // $type = request()->input('type', 'Seller');
    // $branches = \App\Models\Branch::where('type', $type)->get(['id', 'fullname']);
    // return response()->json(['branches' => $branches]);
    // });

    Route::any('/admin-label/{id}', [AdminController::class, 'adminLabel']);
    Route::any('/admin-direct-orders-status', [AdminController::class, 'webDirectOrdersStatus'])->name('admin.DirectOrdersStatus.get');
    Route::any('/admin-direct-orders-assign', [AdminController::class, 'webDirectOrdersAssign'])->name('admin.DirectOrdersAssign.get');
    Route::any('/admin-direct-orders-add', [AdminController::class, 'webDirectOrdersAdd'])->name('admin.DirectOrdersAssign.add');
    Route::any('/admin-direct-orders-filter', [AdminController::class, 'webDirectOrdersFilter'])->name('admin.DirectOrdersFilter');

    Route::any('/admin-setting', [AdminController::class, 'setting'])->name('admin.setting');
    Route::any('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update.profile');
    Route::any('/admin-password-change', [AdminController::class, 'passwordChange'])->name('admin.passwordChange');
    Route::any('/admin-TodayWalletHistory', [AdminController::class, 'todayWalletHistory'])->name('admin.todayWallet');

    // today cod history 
    Route::any('/admin-Today-COD-History', [AdminController::class, 'TodayCodHistory'])->name('admin.TodayCodHistory');

    // all cod history 
    Route::any('/admin-All-COD-History', [AdminController::class, 'allCodHistory'])->name('admin.allCodHistory');
    Route::any('/delivery-Admin-COD-History', [AdminController::class, 'adminCodHistory'])->name('delivery.adminCodHistory');
    Route::any('/delete-order', [AdminController::class, 'deleteOrder'])->name('admin.delete.order');
});
// Admin End
// -------------------------------------- //

// Seller Start
Route::get('/SellerPanel', function () {
    return view('seller.login');
});
Route::post('/seller-login', [SellerController::class, 'sellerLogin'])->name('seller.login');
Route::middleware(['sellerlogin'])->group(function () {
    Route::any('/seller-dashboard', [SellerController::class, 'sellerDashboard']);
    Route::any('/seller-logout', [SellerController::class, 'sellerLogout'])->name('seller.logout');

    Route::any('/seller-setting', [SellerController::class, 'setting'])->name('seller.setting');
    Route::any('/seller-update-profile', [SellerController::class, 'updateProfile'])->name('seller.update.profile');
    Route::any('/seller-password-change', [SellerController::class, 'passwordChange'])->name('seller.passwordChange');


    // Route::any('/seller-add-DeliveryBoy', [SellerController::class, 'addDeliveryBoy'])->name('seller.addDeliveryBoy');
    Route::any('/seller-checkPinCode', [SellerController::class, 'checkPinCode'])->name('seller.checkPinCode');
    // Route::any('/seller-add-Delivery-Boy', [SellerController::class, 'addNewDeliveryBoy'])->name('seller.addNewDeliveryBoy');
    // Route::any('/seller-all-DeliveryBoy', [SellerController::class, 'allDeliveryBoy'])->name('seller.allDeliveryBoy');
    // Route::any('/seller-delete-dlyBoy', [SellerController::class, 'deleteDlyBoy'])->name('seller.delete.dlyBoy');
    // Route::any('/seller-update-boySt', [SellerController::class, 'sellerUpdateBoySt'])->name('seller.update.boySt');



    Route::any('/seller-wallet', [SellerController::class, 'sellerWallet'])->name('seller.wallet');
    Route::any('/seller-add-wallet', [SellerController::class, 'addWalletAmount'])->name('seller.addWalletAmount');
    // addon this url for phonepay 2june
    // Route::match(['get', 'post'], '/wallet/payment/callback', [SellerController::class, 'walletPaymentCallback'])->name('wallet.payment.callback');



    Route::any('/seller-cod-sattlement', [SellerController::class, 'CodSellerAmount']); // seller sattlement 



    Route::any('/seller-add-Delivery-Order', [SellerController::class, 'addDeliveryOrder'])->name('seller.addDeliveryOrder');
    Route::any('/seller-add-Pickup-Order', [SellerController::class, 'addPickupOrder'])->name('seller.addPickupOrder');

    Route::post('/seller-addOrderParcel', [SellerController::class, 'addOrderParcel'])->name('seller.addOrderParcel');
    Route::get('/seller-allOrders', [SellerController::class, 'allOrders'])->name('seller.allOrders');
    Route::get('/order-details/{action}', [SellerController::class, 'orderDetails']);

    Route::any('/seller-assign-get', [SellerController::class, 'sellerAssignGet'])->name('seller.assign.get');
    Route::any('/seller-assign-add', [SellerController::class, 'sellerAssignAdd'])->name('seller.assign.add');
    Route::any('/seller-status-get', [SellerController::class, 'sellerStatusGet'])->name('seller.status.get');

    Route::any('/seller-invoice/{id}', [SellerController::class, 'sellerInvoice']);
    Route::get('/monthly-seller-invoice', [SellerController::class, 'MonthlySellerInvoice']);
    Route::any('/seller-label/{id}', [SellerController::class, 'sellerLabel']);

    Route::any('/seller-delete-orders', [SellerController::class, 'deleteOrders'])->name('delete.orders');
    Route::any('/seller-edit-get', [SellerController::class, 'sellerEditGet'])->name('seller.edit.get');
    Route::any('/seller-edit-update', [SellerController::class, 'sellerEditUpdate'])->name('seller.edit.update');
    Route::get('/seller-cancelled-order/{id}', [SellerController::class, 'cancelledOrder']);

    Route::any('/seller-All-COD-History', [SellerController::class, 'allCodHistory'])->name('seller.allCodHistory');
    Route::any('/seller-COD-History', [SellerController::class, 'dateCodHistory'])->name('seller.codHistory');

    Route::any('/seller-order-cod-history', [SellerController::class, 'orderCodHistory'])->name('seller.orderCodHistory');
    Route::any('/seller-order-cod-Amount', [SellerController::class, 'orderCodAmount'])->name('seller.orderCodAmount');
});
// Seller End
// ------------------------------ //
// Booking Start
Route::get('/BookingPanel', function () {
    return view('booking.login');
});
Route::post('/booking-login', [BookingController::class, 'bookingLogin'])->name('booking.login');
Route::middleware(['bookinglogin'])->group(function () {
    Route::any('/booking-dashboard', [BookingController::class, 'bookingDashboard']);
    Route::any('/booking-logout', [BookingController::class, 'bookingLogout'])->name('booking.logout');

    Route::any('/booking-setting', [BookingController::class, 'setting'])->name('booking.setting');
    Route::any('/booking-update-profile', [BookingController::class, 'updateProfile'])->name('booking.update.profile');
    Route::any('/booking-password-change', [BookingController::class, 'passwordChange'])->name('booking.passwordChange');

    // Route::any('/booking-add-DeliveryBoy', [BookingController::class, 'addDeliveryBoy'])->name('booking.addDeliveryBoy');
    Route::any('/booking-checkPinCode', [BookingController::class, 'checkPinCode'])->name('booking.checkPinCode');
    // Route::any('/booking-add-Delivery-Boy', [BookingController::class, 'addNewDeliveryBoy'])->name('booking.addNewDeliveryBoy');
    // Route::any('/booking-all-DeliveryBoy', [BookingController::class, 'allDeliveryBoy'])->name('booking.allDeliveryBoy');
    // Route::any('/booking-delete-dlyBoy', [BookingController::class, 'deleteDlyBoy'])->name('booking.delete.dlyBoy');
    // Route::any('/booking-update-boySt', [BookingController::class, 'bookingUpdateBoySt'])->name('booking.update.boySt');

    Route::any('/booking-wallet', [BookingController::class, 'bookingWallet'])->name('booking.wallet');

    // booking razorpay 
    Route::any('/booking-add-wallet', [BookingController::class, 'addWalletAmount'])->name('booking.addWalletAmount');

    Route::any('/booking-add-Delivery-Order', [BookingController::class, 'addDeliveryOrder'])->name('booking.addDeliveryOrder');
    Route::any('/booking-add-Pickup-Order', [BookingController::class, 'addPickupOrder'])->name('booking.addPickupOrder');

    Route::post('/booking-addOrderParcel', [BookingController::class, 'addOrderParcel'])->name('booking.addOrderParcel');
    Route::get('/booking-allOrders', [BookingController::class, 'allOrders'])->name('booking.allOrders');
    Route::any('/booking-assign-get', [BookingController::class, 'bookingAssignGet'])->name('booking.assign.get');
    Route::any('/booking-assign-add', [BookingController::class, 'bookingAssignAdd'])->name('booking.assign.add');
    Route::any('/booking-status-get', [BookingController::class, 'bookingStatusGet'])->name('booking.status.get');

    Route::any('/booking-edit-get', [BookingController::class, 'bookingEditGet'])->name('booking.edit.get');
    Route::any('/booking-edit-update', [BookingController::class, 'bookingEditUpdate'])->name('booking.edit.update');
    Route::get('/booking-cancelled-order/{id}', [BookingController::class, 'cancelledOrder']);

    Route::any('/booking-invoice/{id}', [BookingController::class, 'bookingInvoice']);
    Route::any('/monthly-booking-invoice', [BookingController::class, 'MonthlyBookingInvoices'])->name('monthly.booking.invoice');
    Route::any('/booking-label/{id}', [BookingController::class, 'bookingLabel']);

    Route::any('/booking-delete-orders', [BookingController::class, 'deleteOrders'])->name('delete.orders.booking');

    Route::any('/booking-all-cod-history', [BookingController::class, 'allCodHistory'])->name('booking.allCodHistory');
    Route::any('/booking-cod-history', [BookingController::class, 'dateCodHistory'])->name('booking.codHistory');

    Route::get('/booking-order-details/{action}', [BookingController::class, 'orderDetails']);

    Route::any('/booking-order-cod-history', [BookingController::class, 'orderCodHistory'])->name('booking.orderCodHistory');
    Route::any('/booking-order-cod-Amount', [BookingController::class, 'orderCodAmount'])->name('booking.orderCodAmount');

    Route::any('/booking-cod-sattlement', [BookingController::class, 'CodBookingAmount']); // seller sattlement 

});
// Booking End

// Delivery Start
Route::get('/DeliveryPanel', function () {
    return view('delivery.login');
});
Route::post('/delivery-login', [DeliveryController::class, 'deliveryLogin'])->name('delivery.login');
Route::middleware(['deliverylogin'])->group(function () {
    // here 31 may for filter 
    Route::get('/delivery/branch/{branchId}/orders-by-delivery-boy', [DeliveryController::class, 'getOrdersByDeliveryBoyPincode'])->name('delivery.orders.by.delivery.boy');



    Route::any('/delivery-setting', [DeliveryController::class, 'setting'])->name('delivery.setting');
    Route::any('/delivery-update-profile', [DeliveryController::class, 'updateProfile'])->name('delivery.update.profile');
    Route::any('/delivery-password-change', [DeliveryController::class, 'passwordChange'])->name('delivery.passwordChange');
    Route::any('/delivery-logout', [DeliveryController::class, 'deliveryLogout'])->name('delivery.logout');

    Route::any('/delivery-dashboard', [DeliveryController::class, 'dashboard']);
    Route::get('/delivery-order-details/{action}', [DeliveryController::class, 'orderDetails']);
    Route::any('/delivery-status-get', [DeliveryController::class, 'deliveryStatusGet'])->name('delivery.status.get');
    Route::any('/delivery-assign-get', [DeliveryController::class, 'deliveryAssignGet'])->name('delivery.assign.get');
    Route::get('/delivery-boy-get', [DeliveryController::class, 'deliveryBoyGet'])->name('delivery.boy.get');
    Route::post('/delivery/orders-by-boy', [DeliveryController::class, 'getOrdersByDeliveryBoy'])->name('delivery.orders.by.boy');
    Route::any('/delivery-assign-add', [DeliveryController::class, 'deliveryAssignAdd'])->name('delivery.assign.add');
    Route::any('/delivery-assign-order', [DeliveryController::class, 'deliveryAssignOrder'])->name('delivery.assign.order');
    Route::any('/delivery-assign-order-New', [DeliveryController::class, 'deliveryAssignOrderNew'])->name('delivery.assign.order.new');

    Route::any('/delivery-add-DeliveryBoy/{id?}', [DeliveryController::class, 'addDeliveryBoy'])->name('delivery.addDeliveryBoy');
    Route::any('/delivery-add-Delivery-Boy', [DeliveryController::class, 'addNewDeliveryBoy'])->name('delivery.addNewDeliveryBoy');
    Route::any('/delivery-all-Delivery-Boy', [DeliveryController::class, 'allDeliveryBoy'])->name('delivery.allDeliveryBoy');

    Route::any('/delivery-order-history/{id}', [DeliveryController::class, 'orderHistory']);
    Route::post('delivery-monthOrderHistory', [DeliveryController::class, 'monthOrderHistory'])->name('delivery.monthOrderHistory');

    Route::any('/delivery-cod-history/{id}', [DeliveryController::class, 'codHistory']);
    Route::any('/delivery-cod-amount', [DeliveryController::class, 'addCodAmount']);

    Route::any('/delivery-Submit-COD-To-Branch', [DeliveryController::class, 'submitCodToAdmin'])->name('delivery.submitCodToAdmin');


    Route::any('/delivery-cod-history', [DeliveryController::class, 'dateCodHistory'])->name('delivery.codHistory');
    Route::any('/delivery-wallet', [DeliveryController::class, 'deliveryWallet'])->name('delivery.wallet');
    Route::any('/delivery-other-branch-order', [DeliveryController::class, 'otherBranchOrder'])->name('delivery.other.branch.order');
    Route::any('/delivery-other-branch-details/{id}', [DeliveryController::class, 'otherBranchOrderDetails']);
    Route::get('/delivery-transfer-boy-get', [DeliveryController::class, 'deliveryTransferBoyGet'])->name('delivery.transfer.boy.get');
    Route::any('/delivery-transfer-assign-order', [DeliveryController::class, 'deliveryTransferAssignOrder'])->name('delivery.transfer.assign.order');
    Route::any('/delivery-other-branch-order-status', [DeliveryController::class, 'otherBranchOrderStatus'])->name('delivery.other.branch.order.status');
    Route::any('/delivery-other-branch-order-details', [DeliveryController::class, 'otherTransferOrderDetails'])->name('delivery.order-pin-code-orders');

    Route::any('/delivery-direct-orders', [DeliveryController::class, 'webDirectOrders'])->name('delivery.DirectOrders');
    Route::any('/delivery-invoice/{id}', [DeliveryController::class, 'deliverInvoice']);
    Route::any('/delivery-direct-orders-status', [DeliveryController::class, 'webDirectOrdersStatus'])->name('delivery.DirectOrdersStatus.get');
    Route::any('/delivery-direct-orders-assign', [DeliveryController::class, 'webDirectOrdersAssign'])->name('delivery.DirectOrdersAssign.get');
    Route::any('/delivery-direct-orders-add', [DeliveryController::class, 'webDirectOrdersAdd'])->name('delivery.DirectOrdersAssign.add');
    Route::any('/delivery-direct-orders-filter', [DeliveryController::class, 'webDirectOrdersFilter'])->name('delivery.DirectOrdersFilter');
    Route::any('/delivery-label/{id}', [DeliveryController::class, 'deliveryLabel']);
    Route::any('/delivery-All-COD-History', [DeliveryController::class, 'allCodHistory'])->name('delivery.allCodHistory');
    Route::post('/update-cod-status', [DeliveryController::class, 'updateCodStatus'])->name('delivery.updateCodStatus');

});
// Delivery End

// Delivery boy Start
Route::get('/DeliveryBoy', function () {
    return view('deliveryBoy.login');
});
Route::post('/delivery-boy-login', [DeliveryBoyController::class, 'deliveryLogin'])->name('deliveryBoy.login');
Route::middleware(['deliveryboy'])->group(function () {

    Route::any('/delivery-boy-dashboard', [DeliveryBoyController::class, 'dashboard']);
    Route::any('/delivery-boy-logout', [DeliveryBoyController::class, 'logout'])->name('delivery.boy.logout');
    Route::any('/delivery-boy-setting', [DeliveryBoyController::class, 'setting'])->name('delivery.boy.setting');
    Route::any('/delivery-boy-update-profile', [DeliveryBoyController::class, 'updateProfile'])->name('delivery.boy.update.profile');
    Route::any('/delivery-boy-passwordChange', [DeliveryBoyController::class, 'passwordChange'])->name('delivery.boy.passwordChange');
    Route::any('/delivery-boy-codHistory', [DeliveryBoyController::class, 'codHistory'])->name('delivery.boy.codHistory');
    Route::post('delivery-boy-monthOrderHistory', [DeliveryBoyController::class, 'monthOrderHistory'])->name('delivery.boy.monthOrderHistory');
    Route::get('delivery-boy-wallet', [DeliveryBoyController::class, 'deliveryBoyWallet'])->name('delivery.boy.wallet');
    Route::get('delivery-boy-totalOrders', [DeliveryBoyController::class, 'deliveryBoyTotalOrders'])->name('delivery.boy.totalOrders');
    Route::post('delivery-boy-cod-submit', [DeliveryBoyController::class, 'submitCodToBranch'])->name('delivery.boy.cod.submit');

    // my earning 
    Route::get('delivery-boy-myearning', [DeliveryBoyController::class, 'deliveryBoyMyEarning'])->name('delivery.boy.myearning');

    Route::get('/delivery-boy-order-details/{action}', [DeliveryBoyController::class, 'orderDetails']);
    Route::any('/delivery-boy-status-get', [DeliveryBoyController::class, 'deliveryStatusGet'])->name('delivery.boy.status.get');
    Route::any('/delivery-boy-status-update', [DeliveryBoyController::class, 'deliveryAssignAdd'])->name('delivery.boy.status.update');


    Route::any('/delivery-boy-transfer-order-details/{action}', [DeliveryBoyController::class, 'transferOrderDetails']);
    Route::any('/delivery-boy-transfer-order-status', [DeliveryBoyController::class, 'transferOrderStatus'])->name('delivery.boy.transfer.order.status');

    // By Saurabh 
    Route::any('/delivery-boy-qrscanner', [DeliveryBoyController::class, 'qrscanner'])->name('delivery.boy.qrcode');
    // Add this route to your routes file
    Route::post('/delivery-boy-update-status', [DeliveryBoyController::class, 'updateOrderStatus'])->name('delivery.boy.update.status');

    // Get today's picked up orders
    Route::post('/delivery-boy-todays-pickups', [DeliveryBoyController::class, 'getTodaysPickedUpOrders'])->name('delivery.boy.todays.pickups');
    Route::post('/delivery-boy-todays-pickups-status', [DeliveryBoyController::class, 'getTodaysPickedUpOrdersDeliveredtonearbybranch'])->name('delivery.boy.todays.pickups.status');

    // Mark orders as delivered to branch (now generates and sends OTP)
    Route::post('/delivery-boy-mark-delivered-to-branch', [DeliveryBoyController::class, 'markDeliveredToBranch'])->name('delivery.boy.mark.delivered.to.branch');

    // New route for OTP verification - accepts branch_id and multiple order_ids
    Route::post('/delivery-boy-verify-otp', [DeliveryBoyController::class, 'verifyOtpAndCompleteDelivery'])->name('delivery.boy.verify.otp');

    Route::any('/get-booked-super-express-orders', [DeliveryBoyController::class, 'getBookedSuperExpressOrders'])->name('order.get.booked');
    Route::any('/check-super-express-order-status', [DeliveryBoyController::class, 'checkSuperExpressOrderStatus'])->name('order.check.status');
    Route::any('/save-delivery-time', [DeliveryBoyController::class, 'saveDeliveryTime'])->name('order.save.delivery.time');

});





