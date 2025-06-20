<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Api;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Category;
use App\Models\OrderHistory;
use App\Models\DlyBoy;
use App\Models\Enquiry;
use App\Models\FeedBack;
use App\Models\Order;
use App\Models\EstimatedService;
use App\Models\PinCode;
use App\Models\Service;
use App\Models\User;
use App\Models\WebOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingOtp;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Http;


class WebController extends Controller
{

    protected $date;
    public function __construct()
    {
        $kolkataDateTime = Carbon::now('Asia/Kolkata');
        $this->date = $kolkataDateTime->format('Y-m-d H:i:s');
    }


    public function index()
    {
        $se = Service::where('type', 'se')->where('status', 'active')->orderBy('id', 'asc')->get();
        $ex = Service::where('type', 'ex')->where('status', 'active')->orderBy('id', 'asc')->get();
        $ss = Service::where('type', 'ss')->where('status', 'active')->orderBy('id', 'asc')->get();
        $service_time = EstimatedService::orderBy('id', 'Desc')->get();
        return view('web.welcome', compact('ex', 'ss', 'se', 'service_time'));
    }

    // public function index()
    // {
    //     // Fetch and sort 'ex' services
    //     $ex = Service::where('type', 'ex')
    //         ->where('status', 'active')
    //         ->get()
    //         ->sortBy(function ($item) {
    //             if (preg_match('/(\d+(\.\d+)?)/', $item->title, $matches)) {
    //                 return floatval($matches[1]);
    //             }
    //             return 0;
    //         })->values();

    //     // Fetch and sort 'ss' services
    //     $ss = Service::where('type', 'ss')
    //         ->where('status', 'active')
    //         ->get()
    //         ->sortBy(function ($item) {
    //             if (preg_match('/(\d+(\.\d+)?)/', $item->title, $matches)) {
    //                 return floatval($matches[1]);
    //             }
    //             return 0;
    //         })->values();

    //     // Fetch and sort 'se' services
    //     $se = Service::where('type', 'se')
    //         ->where('status', 'active')
    //         ->get()
    //         ->sortBy(function ($item) {
    //             if (preg_match('/(\d+(\.\d+)?)/', $item->title, $matches)) {
    //                 return floatval($matches[1]);
    //             }
    //             return 0;
    //         })->values();

    //     return view('web.welcome', compact('ex', 'ss', 'se'));
    // }




    public function bookParcel()
    {
        $se = Service::where('type', 'se')->where('status', 'active')->orderBy('id', 'asc')->get();
        $ex = Service::where('type', 'ex')->where('status', 'active')->orderBy('id', 'asc')->get();
        $ss = Service::where('type', 'ss')->where('status', 'active')->orderBy('id', 'asc')->get();
        $service_time = EstimatedService::orderBy('id', 'Desc')->get();
        return view('web.bookparcel', compact('ex', 'ss', 'se', 'service_time'));
    }

    public function service()
    {
        return view('web.service');
    }

    public function webEnquiry()
    {
        $data = Category::where('status', 'active')->get();
        return view('web.enquiry', compact('data'));
    }

    public function addEnquiry(Request $request)
    {
        $eq = new Enquiry();
        $eq->fullname = $request->fullName;
        $eq->itemno = $request->itemsCount;
        $eq->email = $request->email;
        $eq->gst_panno = $request->panNo;
        $eq->phoneno = $request->phone;
        $eq->category = $request->category;
        $eq->fulladdress = $request->fullAddress;
        $eq->message = $request->contactMessage;
        $eq->pinCode = $request->pinCode;
        $eq->status = 'inactive';
        // Handle Pan Image
        if ($request->hasFile('panImage')) {
            $eq->gst_panno_img = 'admin/upload/branch/' . $request->file('panImage')->move(public_path('admin/upload/branch'), 'EID_' . time() . '.' . $request->file('panImage')->extension())->getFilename();
        }
        $eq->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Enquiry send successfully!',
            ]);
        }
    }



    // Delivery Boy Enq
    public function DeliveryBoyEnq()
    {
        return view('web.DeliveryBoyEnq');
    }
    // add addDeliveryBoyEnq
    public function addDeliveryBoyEnq(Request $request)
    {

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'pincode' => $request->pincode,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'reference' => $request->reference ?? '',
            'date' => $this->date,
        ];
        DB::table('tbl_deliveryboy_enq')->insert($data);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Enquiry sent successfully!',
            ]);
        } else {
            return redirect()->back()->with('success', 'Enquiry sent successfully!');
        }
    }
    // Delivery Boy Enq
    public function FranchiseEnq()
    {
        return view('web.FranchiseEnq');
    }
    // add addFranchiseEnq
    public function addFranchiseEnq(Request $request)
    {

        $data = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'pincode' => $request->pincode,
            'premises' => $request->premises,
            'address' => $request->address,
            'no_of_delivery_boys' => $request->no_of_delivery_boys,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'reference' => $request->reference ?? '',
            'date' => $this->date,
        ];
        DB::table('tbl_field_franchise')->insert($data);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Franchise Enquiry sent successfully!',
            ]);
        } else {
            return redirect()->back()->with('success', 'Franchise Enquiry sent successfully!');
        }
    }






    public function about()
    {
        return view('web.about');
    }

    public function privacy()
    {
        return view('web.privacy');
    }

    public function termsConditions()
    {
        return view('web.termsConditions');
    }

    public function refundpolicy()
    {
        return view('web.refundpolicy');
    }

    public function trackOrder($id = null)
    {
        if ($id == null) {
            return view('web.trackOrder', ['order' => null, 'orderDetails' => null]);
        }

        $order = WebOrder::where('order_id', $id)->first()
            ?? Order::where('order_id', $id)->first();

        $orderDetails = OrderHistory::where('tracking_id', $id)->get();

        // it is for Not Delivered Case 
        $orders = Order::where('orders.order_id', $id)
            ->leftJoin('order_histories', function ($join) {
                $join->on('orders.order_id', '=', 'order_histories.tracking_id')
                    ->where('order_histories.status', '=', 'Not Delivered');
            })->first();

        return view('web.trackOrder', compact('order', 'orderDetails', 'orders'));
    }

    public function trackOrderDetails(Request $request)
    {
        $order = WebOrder::where('order_id', $request->orderId)->first()
            ?? Order::where('order_id', $request->orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found!',
                'data' => null,
            ]);
        }

        $orderDetails = OrderHistory::where('tracking_id', $request->orderId)->get();

        return response()->json([
            'success' => true,
            'message' => 'Order found successfully!',
            'data' => $order,
            'orderDetails' => $orderDetails
        ]);
    }


    public function blog()
    {
        return view('web.blog');
    }

    public function checkPinCode(Request $request)
    {
        $pin = PinCode::where('pincodes', $request->pin)
            ->where('status', 'active')
            ->first();
        if ($pin) {
            $status = true;
            $msg = 'Available';
        } else {
            $status = false;
            $msg = 'Not available';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => $status,
                'message' => $msg
            ]);
        }
    }

    public function parcelDetails(Request $request)
    {
        // dd($request->all());
        $data['service_type'] = $request->service_type ?? 'NA';
        $data['service_id'] = $request->service_id ?? 'NA';
        $data['service_price'] = $request->service_price ?? '0';
        $data['pickupPincode'] = $request->pickupPincode ?? 'NA';
        $data['deliveryPincode'] = $request->deliveryPincode ?? 'NA';
        $data['pickupAddress'] = $request->pickupAddress ?? 'NA';
        $data['deliveryAddress'] = $request->deliveryAddress ?? 'NA';
        $data['price'] = $request->price ?? 'NA';
        return view('web.parcelDetails', compact('data'));
    }

    // public function storeParcelDetails(Request $request)
    // {
    //     // dd($request->all());
    //     $service = Service::where('id', $request->service_id)->first();
    //     $branch = Branch::where('pincode', 'LIKE', "%{$request->senderPinCode}%")->where('type', 'Delivery')->first();

    //     $DlyBoy = DlyBoy::where('pincode', 'LIKE', "%{$request->senderPinCode}%")->where(['status' => 'active'])->first();

    //     $order = new Order();
    //     $order_history = new OrderHistory();
    //     $order->pickupAddress = $request->pickupAddress;
    //     $order->deliveryAddress = $request->deliveryAddress;

    //     $order->receiver_name = $request->receiver_name;
    //     $order->receiver_cnumber = $request->receiver_number;
    //     $order->receiver_email = $request->receiver_email;
    //     $order->receiver_add = $request->receiver_address;  
    //     $order->receiver_pincode = $request->receiverPinCode;

    //     $order->sender_name = $request->sender_name;
    //     $order->sender_number = $request->sender_number;
    //     $order->sender_email = $request->sender_email;
    //     $order->sender_address = $request->sender_address;
    //     $order->sender_pincode = $request->senderPinCode;

    //     $new_order_id = 'DL' . $this->generateRandomCode();

    //     $order->service_type = $request->service_type;
    //     $order->service_title = $service->title ?? $request->service_id;
    //     $order->service_price = trim(str_replace('₹', '', $request->price));
    //     $order->order_id = $new_order_id;
    //     $order->seller_id = $branch->id ?? null;
    //     $order->price = trim(str_replace('₹', '', $request->price));
    //     $order->payment_mode = $request->payment_methods;
    //     $order->codAmount = $request->codAmount;
    //     $order->insurance = $request->insurance;
    //     $order->order_status = 'Booked';
    //     $order->assign_to = $DlyBoy->id ?? null;
    //     $order->parcel_type = 'Direct';
    //     $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order->created_at = $this->date;
    //     $order->updated_at = $this->date;


    //     $order_history->tracking_id = $new_order_id;
    //     $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order_history->status = 'Booked';
    //     $order_history->save();

    //     $order->save();
    //     $orderId = $order->order_id;

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'msg' => 'Order Booked Successfully!',
    //             'data' => $orderId,
    //         ]);
    //     }
    // }

    // 31 may delay max 5 second delay order booking time 
    // public function storeParcelDetails(Request $request)
    // {
    //     $service = Service::where('id', $request->service_id)->first();
    //     $branch = Branch::where('pincode', 'LIKE', "%{$request->senderPinCode}%")->where('type', 'Delivery')->first();
    //     $DlyBoy = DlyBoy::where('pincode', 'LIKE', "%{$request->senderPinCode}%")->where(['status' => 'active'])->first();

    //     $order = new Order();
    //     $order_history = new OrderHistory();
    //     $order->pickupAddress = $request->pickupAddress;
    //     $order->deliveryAddress = $request->deliveryAddress;

    //     $order->receiver_name = $request->receiver_name;
    //     $order->receiver_cnumber = $request->receiver_number;
    //     $order->receiver_email = $request->receiver_email;
    //     $order->receiver_add = $request->receiver_address;
    //     $order->receiver_pincode = $request->receiverPinCode;

    //     $order->sender_name = $request->sender_name;
    //     $order->sender_number = $request->sender_number;
    //     $order->sender_email = $request->sender_email;
    //     $order->sender_address = $request->sender_address;
    //     $order->sender_pincode = $request->senderPinCode;

    //     $new_order_id = 'DL' . $this->generateRandomCode();

    //     $order->service_type = $request->service_type;
    //     $order->service_title = $service->title ?? $request->service_id;
    //     $order->service_price = trim(str_replace('₹', '', $request->price));
    //     $order->order_id = $new_order_id;
    //     $order->seller_id = $branch->id ?? null;
    //     $order->price = trim(str_replace('₹', '', $request->price));
    //     $order->payment_mode = $request->payment_methods;
    //     $order->codAmount = $request->codAmount;
    //     $order->insurance = $request->insurance;
    //     $order->order_status = 'Booked';
    //     $order->assign_to = $DlyBoy->id ?? null;
    //     $order->parcel_type = 'Direct';
    //     $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order->created_at = $this->date;
    //     $order->updated_at = $this->date;

    //     $order_history->tracking_id = $new_order_id;
    //     $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order_history->status = 'Booked';
    //     $order_history->save();

    //     $order->save();
    //     $orderId = $order->order_id;

    //     // Send booking confirmation email
    //     if ($order->sender_email) {
    //         $mailData = [
    //             'title' => 'Order Booking Confirmation',
    //             'order_id' => $order->order_id,
    //             'service_type' => $order->service_type,
    //             'price' => $order->price,
    //             'payment_mode' => $order->payment_mode,
    //             'sender_name' => $order->sender_name,
    //             'sender_number' => $order->sender_number,
    //             'sender_email' => $order->sender_email,
    //             'sender_address' => $order->sender_address,
    //             'sender_pincode' => $order->sender_pincode,
    //             'receiver_name' => $order->receiver_name,
    //             'receiver_cnumber' => $order->receiver_cnumber,
    //             'receiver_email' => $order->receiver_email,
    //             'receiver_add' => $order->receiver_add,
    //             'receiver_pincode' => $order->receiver_pincode,
    //             'datetime' => $order->datetime,
    //         ];
    //         try {
    //             Mail::to($order->sender_email)->send(new BookingOtp($mailData));
    //             \Log::info("Booking confirmation email sent to {$order->sender_email} for order ID: {$orderId}");
    //         } catch (\Exception $e) {
    //             \Log::error("Failed to send booking confirmation email to {$order->sender_email}: " . $e->getMessage());
    //         }
    //     } else {
    //         \Log::warning("No sender email provided for order ID: {$orderId}. Booking confirmation email not sent.");
    //     }

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'msg' => 'Order Booked Successfully!',
    //             'data' => $orderId,
    //         ]);
    //     }
    // }


    // storeParcelDetails it is for quick order booking with mail 31 may latest code 
    // public function storeParcelDetails(Request $request)
    // {
    //     $service = Service::where('id', $request->service_id)->first();
    //     $branch = Branch::where('pincode', 'LIKE', "%{$request->senderPinCode}%")
    //                     ->where('type', 'Delivery')->first();
    //     $DlyBoy = DlyBoy::where('pincode', 'LIKE', "%{$request->senderPinCode}%")
    //                     ->where(['status' => 'active'])->first();

    //     $order = new Order();
    //     $order_history = new OrderHistory();
    //     $order->pickupAddress = $request->pickupAddress;
    //     $order->deliveryAddress = $request->deliveryAddress;

    //     $order->receiver_name = $request->receiver_name;
    //     $order->receiver_cnumber = $request->receiver_number;
    //     $order->receiver_email = $request->receiver_email;
    //     $order->receiver_add = $request->receiver_address;
    //     $order->receiver_pincode = $request->receiverPinCode;

    //     $order->sender_name = $request->sender_name;
    //     $order->sender_number = $request->sender_number;
    //     $order->sender_email = $request->sender_email;
    //     $order->sender_address = $request->sender_address;
    //     $order->sender_pincode = $request->senderPinCode;

    //     $new_order_id = 'DL' . $this->generateRandomCode();

    //     $order->service_type = $request->service_type;
    //     $order->service_title = $service->title ?? $request->service_id;
    //     $order->service_price = trim(str_replace('₹', '', $request->price));
    //     $order->order_id = $new_order_id;
    //     $order->seller_id = $branch->id ?? null;
    //     $order->price = trim(str_replace('₹', '', $request->price));
    //     $order->payment_mode = $request->payment_methods;
    //     $order->codAmount = $request->codAmount;
    //     $order->insurance = $request->insurance;
    //     $order->order_status = 'Booked';
    //     $order->assign_to = $DlyBoy->id ?? null;
    //     $order->assign_by = $branch->id ?? null;
    //     $order->parcel_type = 'Direct';
    //     $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order->created_at = $this->date;
    //     $order->updated_at = $this->date;

    //     $order_history->tracking_id = $new_order_id;
    //     $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order_history->status = 'Booked';
    //     $order_history->save();

    //     $order->save();
    //     $orderId = $order->order_id;

    //     // Mail data preparation (before response)
    //     $mailData = null;
    //     if ($order->sender_email) {
    //         $mailData = [
    //             'title' => 'Order Booking Confirmation',
    //             'order_id' => $order->order_id,
    //             'service_type' => $order->service_type,
    //             'price' => $order->price,
    //             'payment_mode' => $order->payment_mode,
    //             'sender_name' => $order->sender_name,
    //             'sender_number' => $order->sender_number,
    //             'sender_email' => $order->sender_email,
    //             'sender_address' => $order->sender_address,
    //             'sender_pincode' => $order->sender_pincode,
    //             'receiver_name' => $order->receiver_name,
    //             'receiver_cnumber' => $order->receiver_cnumber,
    //             'receiver_email' => $order->receiver_email,
    //             'receiver_add' => $order->receiver_add,
    //             'receiver_pincode' => $order->receiver_pincode,
    //             'datetime' => $order->datetime,
    //         ];
    //     }

    //     if ($request->ajax()) {
    //         if ($order->sender_email && $mailData) {
    //             register_shutdown_function(function () use ($order, $mailData, $orderId) {
    //                 try {
    //                     Mail::to($order->sender_email)->send(new BookingOtp($mailData));
    //                     \Log::info("Booking confirmation email sent to {$order->sender_email} for order ID: {$orderId}");
    //                 } catch (\Exception $e) {
    //                     \Log::error("Failed to send booking confirmation email to {$order->sender_email}: " . $e->getMessage());
    //                 }
    //             });
    //         }
    //         return response()->json([
    //             'success' => true,
    //             'msg' => 'Order Booked Successfully!',
    //             'data' => $orderId,
    //         ]);
    //     }
    // }

    // 16 june 
    // public function storeParcelDetails(Request $request)
    // {
    //     $service = Service::where('id', $request->service_id)->first();
    //     $branch = Branch::where('pincode', 'LIKE', "%{$request->senderPinCode}%")
    //                     ->where('type', 'Delivery')->first();
    //     $DlyBoy = DlyBoy::where('pincode', 'LIKE', "%{$request->senderPinCode}%")
    //                     ->where(['status' => 'active'])->first();

    //     $order = new Order();
    //     $order_history = new OrderHistory();
    //     $order->pickupAddress = $request->pickupAddress;
    //     $order->deliveryAddress = $request->deliveryAddress;

    //     $order->receiver_name = $request->receiver_name;
    //     $order->receiver_cnumber = $request->receiver_number;
    //     $order->receiver_email = $request->receiver_email;
    //     $order->receiver_add = $request->receiver_address;
    //     $order->receiver_pincode = $request->receiverPinCode;

    //     $order->sender_name = $request->sender_name;
    //     $order->sender_number = $request->sender_number;
    //     $order->sender_email = $request->sender_email;
    //     $order->sender_address = $request->sender_address;
    //     $order->sender_pincode = $request->senderPinCode;

    //     $new_order_id = 'DL' . $this->generateRandomCode();

    //     $order->service_type = $request->service_type;
    //     $order->service_title = $service->title ?? $request->service_id;
    //     $order->service_price = trim(str_replace('₹', '', $request->price));
    //     $order->order_id = $new_order_id;
    //     $order->seller_id = $branch->id ?? null;
    //     $order->price = trim(str_replace('₹', '', $request->price));
    //     $order->payment_mode = $request->payment_methods;
    //     $order->codAmount = $request->codAmount;
    //     $order->insurance = $request->insurance;
    //     $order->order_status = 'Booked';
    //     $order->assign_to = $DlyBoy->id ?? null;
    //     $order->assign_by = $branch->id ?? null;
    //     $order->parcel_type = 'Direct';
    //     $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order->created_at = $this->date;
    //     $order->updated_at = $this->date;

    //     $order_history->tracking_id = $new_order_id;
    //     $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order_history->status = 'Booked';
    //     $order_history->save();

    //     $order->save();
    //     $orderId = $order->order_id;

    //     // Mail data preparation
    //     $mailData = null;
    //     if ($order->sender_email || $order->receiver_email) {
    //         $mailData = [
    //             'title' => 'Order Booking Confirmation',
    //             'order_id' => $order->order_id,
    //             'service_type' => $order->service_type,
    //             'price' => $order->price,
    //             'payment_mode' => $order->payment_mode,
    //             'sender_name' => $order->sender_name,
    //             'sender_number' => $order->sender_number,
    //             'sender_email' => $order->sender_email,
    //             'sender_address' => $order->sender_address,
    //             'sender_pincode' => $order->sender_pincode,
    //             'receiver_name' => $order->receiver_name,
    //             'receiver_cnumber' => $order->receiver_cnumber,
    //             'receiver_email' => $order->receiver_email,
    //             'receiver_add' => $order->receiver_add,
    //             'receiver_pincode' => $order->receiver_pincode,
    //             'datetime' => $order->datetime,
    //         ];
    //     }

    //     if ($request->ajax()) {
    //         if ($mailData) {
    //             register_shutdown_function(function () use ($order, $mailData, $orderId) {
    //                 try {
    //                     $recipients = [];
    //                     if ($order->sender_email) {
    //                         $recipients[] = $order->sender_email;
    //                     }
    //                     if ($order->receiver_email) {
    //                         $recipients[] = $order->receiver_email;
    //                     }

    //                     if (!empty($recipients)) {
    //                         Mail::to($recipients)->send(new BookingOtp($mailData));
    //                         \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$orderId}");
    //                     } else {
    //                         \Log::warning("No valid email addresses provided for order ID: {$orderId}");
    //                     }
    //                 } catch (\Exception $e) {
    //                     \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
    //                 }
    //             });
    //         }
    //         return response()->json([
    //             'success' => true,
    //             'msg' => 'Order Booked Successfully!',
    //             'data' => $orderId,
    //         ]);
    //     }
    // }


    // new code with order id sequence change 
    // public function storeParcelDetails(Request $request)
    // {
    //     $service = Service::where('id', $request->service_id)->first();
    //     $branch = Branch::where('pincode', 'LIKE', "%{$request->senderPinCode}%")
    //         ->where('type', 'Delivery')->first();
    //     $DlyBoy = DlyBoy::where('pincode', 'LIKE', "%{$request->senderPinCode}%")
    //         ->where(['status' => 'active'])->first();

    //     $order = new Order();
    //     $order_history = new OrderHistory();

    //     $order->pickupAddress = $request->pickupAddress;
    //     $order->deliveryAddress = $request->deliveryAddress;

    //     $order->receiver_name = $request->receiver_name;
    //     $order->receiver_cnumber = $request->receiver_number;
    //     $order->receiver_email = $request->receiver_email;
    //     $order->receiver_add = $request->receiver_address;
    //     $order->receiver_pincode = $request->receiverPinCode;

    //     $order->sender_name = $request->sender_name;
    //     $order->sender_number = $request->sender_number;
    //     $order->sender_email = $request->sender_email;
    //     $order->sender_address = $request->sender_address;
    //     $order->sender_pincode = $request->senderPinCode;

    //     $fixedPrefix = 'DP1516800'; // Fixed order ID prefix

    //     $order->service_type = $request->service_type;
    //     $order->service_title = $service->title ?? $request->service_id;
    //     $order->service_price = trim(str_replace('₹', '', $request->price));
    //     $order->order_id = ''; // Temporary, will be updated after save
    //     $order->seller_id = $branch->id ?? null;
    //     $order->price = trim(str_replace('₹', '', $request->price));
    //     $order->payment_mode = $request->payment_methods;
    //     $order->codAmount = $request->codAmount;
    //     $order->insurance = $request->insurance;
    //     $order->order_status = 'Booked';
    //     $order->assign_to = $DlyBoy->id ?? null;
    //     $order->assign_by = $branch->id ?? null;
    //     $order->parcel_type = 'Direct';
    //     $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order->created_at = $this->date;
    //     $order->updated_at = $this->date;

    //     $order->save(); // Save to get auto-increment ID
    //     $lastInsertId = $order->id;

    //     $generatedOrderId = $fixedPrefix . '' . $lastInsertId;
    //     $order->order_id = $generatedOrderId;
    //     $order->save(); // Update order_id

    //     $orderId = $generatedOrderId;

    //     // Order history
    //     $order_history->tracking_id = $orderId;
    //     $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $order_history->status = 'Booked';
    //     $order_history->save();

    //     // Mail data preparation
    //     $mailData = null;
    //     if ($order->sender_email || $order->receiver_email) {
    //         $mailData = [
    //             'title' => 'Order Booking Confirmation',
    //             'order_id' => $orderId,
    //             'service_type' => $order->service_type,
    //             'price' => $order->price,
    //             'payment_mode' => $order->payment_mode,
    //             'sender_name' => $order->sender_name,
    //             'sender_number' => $order->sender_number,
    //             'sender_email' => $order->sender_email,
    //             'sender_address' => $order->sender_address,
    //             'sender_pincode' => $order->sender_pincode,
    //             'receiver_name' => $order->receiver_name,
    //             'receiver_cnumber' => $order->receiver_cnumber,
    //             'receiver_email' => $order->receiver_email,
    //             'receiver_add' => $order->receiver_add,
    //             'receiver_pincode' => $order->receiver_pincode,
    //             'datetime' => $order->datetime,
    //         ];
    //     }

    //     if ($request->ajax()) {
    //         if ($mailData) {
    //             register_shutdown_function(function () use ($order, $mailData, $orderId) {
    //                 try {
    //                     $recipients = [];
    //                     if ($order->sender_email) {
    //                         $recipients[] = $order->sender_email;
    //                     }
    //                     if ($order->receiver_email) {
    //                         $recipients[] = $order->receiver_email;
    //                     }

    //                     if (!empty($recipients)) {
    //                         Mail::to($recipients)->queue(new BookingOtp($mailData));
    //                         \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$orderId}");
    //                     } else {
    //                         \Log::warning("No valid email addresses provided for order ID: {$orderId}");
    //                     }
    //                 } catch (\Exception $e) {
    //                     \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
    //                 }
    //             });
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'msg' => 'Order Booked Successfully!',
    //             'data' => $orderId,
    //         ]);
    //     }
    // }

  

    public function storeParcelDetails(Request $request)
{
    // Validate request data
    $request->validate([
        'service_id' => 'required',
        'sender_name' => 'required',
        'number' => 'required|regex:/^[6789][0-9]{9}$/', // Sender number
        'email' => 'required|email', // Sender email
        'sender_address' => 'required',
        'senderPinCode' => 'required',
        'receiver_name' => 'required',
        'receiver_number' => 'required|regex:/^[6789][0-9]{9}$/',
        'receiver_email' => 'required|email',
        'receiver_address' => 'required',
        'receiverPinCode' => 'required',
        'payment_methods' => 'required|in:COD,online',
        'codAmount' => 'required_if:payment_methods,COD|numeric|min:0',
        'price' => 'required',
        'status' => 'required|in:success,failed,cancelled',
        'razorpay_payment_id' => 'nullable|string',
        'razorpay_order_id' => 'nullable|string',
        'reason' => 'nullable|string',
    ]);

    $response = [
        'success' => false,
        'message' => 'Order processing failed.',
    ];

    // Skip Razorpay verification for COD or non-online payments
    if ($request->payment_methods === 'online' && $request->status === 'success' && $request->razorpay_payment_id) {
        try {
            $paymentResponse = Http::withBasicAuth(
                env('RAZORPAY_KEY', 'rzp_test_BCqQIjZcNVZHVw'),
                env('RAZORPAY_SECRET')
            )->get("https://api.razorpay.com/v1/payments/{$request->razorpay_payment_id}");

            if ($paymentResponse->successful()) {
                $payment = $paymentResponse->json();
                if ($payment['status'] !== 'captured') {
                    throw new \Exception('Payment not captured');
                }
            } else {
                throw new \Exception('Failed to fetch payment details: ' . $paymentResponse->body());
            }
        } catch (\Exception $e) {
            Log::error("Razorpay payment verification failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 400);
        }
    }

    if ($request->status === 'success') {
        // Book parcel for successful payments (online or COD)
        $service = Service::where('id', $request->service_id)->first();
        $branch = Branch::where('pincode', 'LIKE', "%{$request->senderPinCode}%")
            ->where('type', 'Delivery')->first();
        $DlyBoy = DlyBoy::where('pincode', 'LIKE', "%{$request->senderPinCode}%")
            ->where('status', 'active')->first();

        $order = new Order();
        $order_history = new OrderHistory();

        $order->pickupAddress = $request->pickupAddress;
        $order->deliveryAddress = $request->deliveryAddress;
        $order->sender_name = $request->sender_name;
        $order->sender_number = $request->number; // Sender number
        $order->sender_email = $request->email; // Sender email
        $order->sender_address = $request->sender_address;
        $order->sender_pincode = $request->senderPinCode;
        $order->receiver_name = $request->receiver_name;
        $order->receiver_cnumber = $request->receiver_number;
        $order->receiver_email = $request->receiver_email;
        $order->receiver_add = $request->receiver_address;
        $order->receiver_pincode = $request->receiverPinCode;

        $fixedPrefix = 'DP1516800';

        $order->service_type = $request->service_type;
        $order->service_title = $service->title ?? $request->service_id;
        $order->service_price = trim(str_replace('₹', '', $request->price));
        $order->order_id = ''; // Temporary
        $order->seller_id = $branch->id ?? null;
        $order->price = trim(str_replace('₹', '', $request->price));
        $order->payment_mode = $request->payment_methods;
        $order->codAmount = $request->codAmount ?? 0;
        $order->insurance = $request->insurance ? 'insurance' : null;
        $order->order_status = 'Booked';
        $order->assign_to = $DlyBoy->id ?? null;
        $order->assign_by = $branch->id ?? null;
        $order->parcel_type = 'Direct';
        $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
        $order->created_at = $this->date;
        $order->updated_at = $this->date;

        // Save Razorpay details only if provided
        if ($request->payment_methods === 'online' && $request->razorpay_payment_id) {
            $order->razorpay_payment_id = $request->razorpay_payment_id;
            $order->razorpay_order_id = $request->razorpay_order_id;
        }

        $order->save();
        $lastInsertId = $order->id;

        $generatedOrderId = $fixedPrefix . $lastInsertId;
        $order->order_id = $generatedOrderId;
        $order->save();

        $orderId = $generatedOrderId;

        // Order history
        $order_history->tracking_id = $orderId;
        $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
        $order_history->status = 'Booked';
        $order_history->save();

        // Mail data preparation
        $mailData = null;
        if ($order->sender_email || $order->receiver_email) {
            $mailData = [
                'title' => 'Order Booking Confirmation',
                'order_id' => $orderId,
                'service_type' => $order->service_type,
                'price' => $order->price,
                'payment_mode' => $order->payment_mode,
                'sender_name' => $order->sender_name,
                'sender_number' => $order->sender_number,
                'sender_email' => $order->sender_email,
                'sender_address' => $order->sender_address,
                'sender_pincode' => $order->sender_pincode,
                'receiver_name' => $order->receiver_name,
                'receiver_cnumber' => $order->receiver_cnumber,
                'receiver_email' => $order->receiver_email,
                'receiver_add' => $order->receiver_add,
                'receiver_pincode' => $order->receiver_pincode,
                'datetime' => $order->datetime,
            ];
        }

        if ($mailData) {
            register_shutdown_function(function () use ($order, $mailData, $orderId) {
                try {
                    $recipients = [];
                    if ($order->sender_email) {
                        $recipients[] = $order->sender_email;
                    }
                    if ($order->receiver_email) {
                        $recipients[] = $order->receiver_email;
                    }

                    if (!empty($recipients)) {
                        Mail::to($recipients)->queue(new BookingOtp($mailData));
                        Log::info("Order confirmation email sent to: " . implode(', ', $recipients) . ' for order ID: ' . $orderId);
                    } else {
                        Log::warning("No valid email addresses provided for order ID: " . $orderId);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send order confirmation email: " . $e->getMessage());
                }
            });
        }

        $response = [
            'success' => true,
            'msg' => 'Order Booked Successfully!',
            'data' => $orderId,
        ];
    } elseif ($request->status === 'failed') {
        Log::info("Failed payment attempt: Amount: {$request->amount}, Reason: " . ($request->reason ?? ''));
        $response = [
            'success' => false,
            'message' => 'Payment failed: ' . ($request->reason ?? 'Unknown error'),
        ];
    } elseif ($request->status === 'cancelled') {
        Log::info("Cancelled payment attempt: Amount: {$request->amount}");
        $response = [
            'success' => false,
            'message' => 'Payment was cancelled',
        ];
    }

    if ($request->ajax()) {
        return response()->json($response);
    }

    return redirect()->back()->with('error', $response['message']);
}







    /**
     * Generate a random alphanumeric code.
     *
     * @param int $length
     * @return string
     */
    private function generateRandomCode($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomCode = '';
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, strlen($characters) - 1);
            $randomCode .= $characters[$randomIndex];
        }
        return $randomCode;
    }

    public function review($action)
    {
        if ($action == 'FeedBack') {
            return view('web.reviews');
        }
    }

    public function storeReview(Request $request)
    {
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        $review = new FeedBack();
        $review->name = $request->fullName;
        $review->email = $request->email;
        $review->phone = $request->phone;
        $review->message = $request->message;
        $review->datetime = $dateTime;
        $review->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review send successfully!',
            ]);
        }
    }

    public function orderLabel($id)
    {
        $data = Order::where('order_id', $id)->first() ?? WebOrder::where('order_id', $id)->first();
        if ($data->payment_mode == 'COD' || $data->payment_methods == 'COD') {
            return view('web.label', compact('data'));
        } else {
            return view('web.label1', compact('data'));
        }
    }

    public function orderLabelviaEmailCod($id)
    {
        $data = Order::where('order_id', $id)->first() ?? WebOrder::where('order_id', $id)->first();
        return view('web.label', compact('data'));

    }
    public function orderLabelviaEmailOnline($id)
    {
        $data = Order::where('order_id', $id)->first() ?? WebOrder::where('order_id', $id)->first();
        return view('web.label1', compact('data'));

    }
}
