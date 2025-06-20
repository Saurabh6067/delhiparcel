<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\COD;
use App\Models\DlyBoy;
use App\Models\Order;
use App\Models\OrderCod;
use App\Models\PinCode;
use App\Models\Service;
use App\Models\Servicetype;
use App\Models\Wallet;
use App\Models\CodSellerAmount;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\SellerBookingConfirmation;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{

    protected $date;
    public function __construct()
    {
        $kolkataDateTime = Carbon::now('Asia/Kolkata');
        $this->date = $kolkataDateTime->format('Y-m-d H:i:s');
    }


    // Cod Sattlement CodBookingAmount Modal 
    public function CodBookingAmount()
    {
        $user = Session::get('bid');
        $data = CodSellerAmount::where('userid', $user)->orderBy('id', 'desc')->get();
        $amount = $data->first();
        return view('booking.CodBookingAmount', compact('data', 'amount'));
    }



    public function bookingLogin(Request $request)
    {
        $user = Branch::where('email', $request->email)->where('type', $request->type)->first();
        if ($user && $user->password != $request->pwd) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
            ]);
        } else {
            Session::put('bid', $user->id);
            return response()->json([
                'success' => true,
                'message' => 'Welcome to your Dashboard!'
            ]);
        }
    }


    public function bookingDashboard()
    {
        $id = Session::get('bid');
        $branch = Branch::find($id);
        // toDayOrder details
        $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = Order::where('datetime', 'like', $dateTiem . '%')
            ->where('seller_primary_id', $id);
        $toDayOrder = $ordersQuery->count();
        // Clone the base query to avoid modification issues
        $toDayPendingOrder = (clone $ordersQuery)->where('order_status', 'Booked')->count();
        $toDayOrderPicUp = (clone $ordersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
        $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();

        // totalOrder details
        $totalOrder = Order::where('seller_primary_id', $id)->count();
        $totalPendingOrder = Order::where('seller_primary_id', $id)->where('order_status', 'Booked')->count();
        $totalOrderPicUp = Order::where('seller_primary_id', $id)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        $totalCompleteOrder = Order::where('seller_primary_id', $id)->where('order_status', 'Delivered')->count();
        $totalCanceledOrder = Order::where('seller_primary_id', $id)->where('order_status', 'Cancelled')->count();

        $BranchtotalCod = Wallet::where('userid', $id)
            ->orderBy('id', 'desc')
            ->first();
        $branchcodamount = $BranchtotalCod->total ?? null;

        return view('booking.dashboard', compact('branch', 'toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'totalOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCanceledOrder', 'branchcodamount'));
    }

    public function bookingLogout(Request $request)
    {
        $request->session()->forget('bid');
        return redirect('/BookingPanel')->with([
            'success' => true,
            'message' => 'You have successful LogOut!'
        ]);
    }

    public function setting()
    {
        $user = Session::get('bid');
        $data = Branch::find($user);
        return view('booking.setting', compact('data'));
    }

    public function updateProfile(Request $request)
    {
        $user = Branch::find($request->adminId);

        $user->fullname = $request->adminName;
        $user->email = $request->adminEmail;
        $user->phoneno = $request->adminMobile;
        $user->save();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile Update Successfully!',
            ]);
        }
    }

    public function passwordChange(Request $request)
    {
        $user = Branch::find($request->id);
        if ($request->oldPassword == $user->password) {
            if ($request->newPassword != $request->conPassword) {
                $status = false;
                $msg = 'Confirm password not matched!';
            } else {
                $user->password = $request->newPassword;
                $user->save();
                $status = true;
                $msg = 'Password change successfully!';
            }
            return response()->json([
                'success' => $status,
                'message' => $msg,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Old Password'
            ]);
        }
    }

    public function addDeliveryBoy()
    {
        return view('booking.addDeliveryBoy');
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

    public function addNewDeliveryBoy(Request $request)
    {
        $dboy = new DlyBoy();
        $dboy->name = $request->fullName;
        $dboy->email = $request->email;
        $dboy->phone = $request->phone;
        $dboy->address = $request->fullAddress;
        $dboy->pincode = $request->pinCode;
        $dboy->password = $request->password;
        $dboy->userid = Session::get('bid');
        $dboy->save();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Delivery boy add successfully!',
            ]);
        }
    }

    public function allDeliveryBoy()
    {
        $user = Session::get('bid');
        $data = DlyBoy::where('userid', $user)
            ->orderBy('id', 'desc')
            ->get();
        return view('booking.allDeliveryBoy', compact('data'));
    }

    public function deleteDlyBoy(Request $request)
    {
        $dboy = DlyBoy::FindOrFail($request->id);
        $dboy->delete();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function bookingUpdateBoySt(Request $request)
    {
        $service = DlyBoy::find($request->id);
        $service->status = $request->status;
        $service->save();
        $message = $service->status == 'active' ? 'Service activated successfully!' : 'Service deactivated successfully!';
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function bookingWallet()
    {
        $user = Session::get('bid');
        $data = Wallet::where('userid', $user)->orderBy('id', 'desc')->get();
        $amount = $data->first();

        return view('booking.bookingWallet', compact('data', 'amount'));
    }

    public function addWalletAmount(Request $request)
    {
        $user = Session::get('bid');

        $data = Wallet::where('userid', $user)->orderBy('id', 'desc')->first();
        $total = $data ? $data->total + $request->amount : $request->amount;

        // ✅ Get mobile number of the branch user
        $branch = Branch::where('id', $user)->first();
        $mobile = $branch ? $branch->phoneno : '9999999999'; // fallback number

        $wlt = new Wallet();
        $wlt->userid = $user;
        $wlt->c_amount = $request->amount;
        $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
        $wlt->total = $total;
        $wlt->msg = 'credit';
        $wlt->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Amount added successfully!',
                'mobile' => $mobile, // ✅ Return mobile number for Razorpay use
                'html' => view('booking.inc.wallet', compact('data'))->render(),
            ]);
        }
    }


    public function addDeliveryOrder()
    {
        $userId = Session::get('bid');
        $serviceType = Servicetype::where('userId', $userId)->get();

        if (!$serviceType->isEmpty()) {
            $stdOrder = $serviceType;
            $expOrder = $serviceType;
            $seOrder = $serviceType;
        } else {
            $stdOrder = Service::where('type', 'ss')->where('status', 'active')->get();
            $expOrder = Service::where('type', 'ex')->where('status', 'active')->get();
            $seOrder = Service::where('type', 'se')->where('status', 'active')->get();
        }
        return view('booking.addDeliveryOrder', compact('stdOrder', 'expOrder', 'seOrder'));
    }

    public function addPickupOrder()
    {
        $userId = Session::get('bid');
        $serviceType = Servicetype::where('userId', $userId)->get();
        if (!$serviceType->isEmpty()) {
            $stdOrder = $serviceType;
            $expOrder = $serviceType;
            $seOrder = $serviceType;
        } else {
            $stdOrder = Service::where('type', 'ss')->where('status', 'active')->get();
            $expOrder = Service::where('type', 'ex')->where('status', 'active')->get();
            $seOrder = Service::where('type', 'se')->where('status', 'active')->get();
        }
        return view('booking.addPickupOrder', compact('stdOrder', 'expOrder', 'seOrder'));
    }



    //   public function addOrderParcel(Request $request)
// {
//     // Validate request inputs
//     $request->validate([
//         'parcel_type' => 'required|in:delivery,Pickup,Direct',
//         'receiverPincode' => 'required',
//         'receiver_name' => 'required',
//         'receiver_number' => 'required',
//         'price' => 'required|numeric|min:0',
//     ]);

    //     $userId = Session::get('bid');
//     $branchDetails = Branch::where('id', $userId)->first();

    //     // Check if branch details exist
//     if (!$branchDetails || empty($branchDetails->pincode)) {
//         return $request->ajax()
//             ? response()->json(['success' => false, 'message' => 'Invalid branch or pincode data'], 400)
//             : redirect()->back()->with('message', 'Invalid branch or pincode data');
//     }

    //     $wallet = Wallet::where('userid', $userId)
//         ->orderBy('id', 'desc')
//         ->first();

    //     if ($wallet && $wallet->total >= $request->price) {
//         $wlt = new Wallet();
//         $wlt->userid = $userId;
//         $wlt->d_amount = $request->price;
//         $total = $wallet->total - $request->price;
//         $wlt->total = $total;
//         $wlt->msg = 'debit';
//         $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $wlt->save();

    //         $order = new Order();
//         $new_order_id = 'DL' . $this->generateRandomCode();

    //         // Set sender and receiver details based on parcel_type
//         if ($request->parcel_type === 'delivery') {
//             $order->sender_pincode = $branchDetails->pincode;
//             $order->sender_name = $branchDetails->fullname;
//             $order->sender_number = $branchDetails->phoneno;
//             $order->sender_email = $branchDetails->email;
//             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
//             $order->receiver_name = $request->receiver_name;
//             $order->receiver_cnumber = $request->receiver_number;
//             $order->receiver_email = $request->receiver_email ?? '';
//             $order->receiver_add = $request->receiver_address ?? '';
//         } elseif ($request->parcel_type === 'Pickup') {
//             $order->sender_pincode = $request->receiverPincode;
//             $order->sender_name = $request->receiver_name;
//             $order->sender_number = $request->receiver_number;
//             $order->sender_email = $request->receiver_email ?? '';
//             $order->sender_address = $request->receiver_address ?? '';

    //             $order->receiver_pincode = $branchDetails->pincode;
//             $order->receiver_name = $branchDetails->fullname;
//             $order->receiver_cnumber = $branchDetails->phoneno;
//             $order->receiver_email = $branchDetails->email;
//             $order->receiver_add = $branchDetails->fulladdress;
//         } else { // Direct
//             $order->sender_pincode = $branchDetails->pincode;
//             $order->sender_name = $branchDetails->fullname;
//             $order->sender_number = $branchDetails->phoneno;
//             $order->sender_email = $branchDetails->email;
//             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
//             $order->receiver_name = $request->receiver_name;
//             $order->receiver_cnumber = $request->receiver_number;
//             $order->receiver_email = $request->receiver_email ?? '';
//             $order->receiver_add = $request->receiver_address ?? '';
//         }

    //         // Find branch for seller_id based on sender_pincode
//         $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
//             ->where('type', 'Delivery')
//             ->first();

    //         $order->service_type = $request->service_type ?? null;
//         $order->service_title = $request->service_title ?? null;
//         $order->service_price = $request->service_price ?? null;
//         $order->order_id = $new_order_id;
//         $order->seller_id = $branch->id ?? null;
//         $order->assign_by = $branch->id ?? null;
//         $order->seller_primary_id = $userId; // Set seller primary id
//         $order->price = trim(str_replace('₹', '', $request->price));
//         $order->payment_mode = $request->payment_methods ?? null;
//         $order->codAmount = $request->codAmount ?? null;
//         $order->insurance = $request->insurance ?? null;
//         $order->order_status = 'Booked';
//         $order->parcel_type = $request->parcel_type;
//         $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $order->created_at = $this->date;
//         $order->updated_at = $this->date;

    //         // GET THE DELIVERY BOY based on parcel_type
//         $searchPincode = ($request->parcel_type === 'Pickup') ? $order->sender_pincode : $branchDetails->pincode;
//         $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
//             $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
//                   ->orWhere('pincode', 'LIKE', '%' . $searchPincode . '%');
//         })->first();

    //         // Ensure delivery boy is assigned for delivery
//         if ($request->parcel_type === 'delivery' && !$deliveryBoy) {
//             return $request->ajax()
//                 ? response()->json(['success' => false, 'message' => 'No delivery boy available for branch pincode'], 400)
//                 : redirect()->back()->with('message', 'No delivery boy available for branch pincode');
//         }

    //         // Assign delivery boy to order if found
//         if ($deliveryBoy) {
//             $order->assign_to = $deliveryBoy->id;
//             // // Set assign_by based on parcel_type
//             // if ($request->parcel_type === 'Pickup') {
//             //     // Find Branch with type = 'Delivery' and matching pincode
//             //     $deliveryBranch = Branch::where('type', 'Delivery')
//             //         ->whereRaw("FIND_IN_SET(?, pincode)", [$request->receiverPincode])
//             //         ->first();
//             //     $order->assign_by = $deliveryBranch ? $deliveryBranch->id : $userId;
//             // } else {
//             //     $order->assign_by = $userId;
//             // }
//         }

    //         $order->save();

    //         // Create order history entry
//         $order_history = new OrderHistory();
//         $order_history->tracking_id = $new_order_id;
//         $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $order_history->status = 'Booked';
//         $order_history->order_id = $order->id;
//         $order_history->save();

    //         $status = true;
//         $msg = 'Order created successfully!';
//     } else {
//         $status = false;
//         $msg = 'Insufficient Balance';
//     }

    //     if ($request->ajax()) {
//         return response()->json([
//             'success' => $status,
//             'message' => $msg,
//         ]);
//     }

    //     return redirect()->back()->with('message', $msg);
// }

    //     public function addOrderParcel(Request $request)
//     {
//     // Validate request inputs
//     $request->validate([
//         'parcel_type' => 'required|in:delivery,Pickup,Direct',
//         'receiverPincode' => 'required',
//         'receiver_name' => 'required',
//         'receiver_number' => 'required',
//         'price' => 'required|numeric|min:/mail0',
//     ]);

    //     $userId = Session::get('bid');
//     $branchDetails = Branch::where('id', $userId)->first();

    //     // Check if branch details exist
//     if (!$branchDetails || empty($branchDetails->pincode)) {
//         return $request->ajax()
//             ? response()->json(['success' => false, 'message' => 'Invalid branch or pincode data'], 400)
//             : redirect()->back()->with('message', 'Invalid branch or pincode data');
//     }

    //     $wallet = Wallet::where('userid', $userId)
//         ->orderBy('id', 'desc')
//         ->first();

    //     if ($wallet && $wallet->total >= $request->price) {
//         $wlt = new Wallet();
//         $wlt->userid = $userId;
//         $wlt->d_amount = $request->price;
//         $total = $wallet->total - $request->price;
//         $wlt->total = $total;
//         $wlt->msg = 'debit';
//         $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $wlt->save();

    //         $order = new Order();
//         $new_order_id = 'DL' . $this->generateRandomCode();

    //         // Set sender and receiver details based on parcel_type
//         if ($request->parcel_type === 'delivery') {
//             $order->sender_pincode = $branchDetails->pincode;
//             $order->sender_name = $branchDetails->fullname;
//             $order->sender_number = $branchDetails->phoneno;
//             $order->sender_email = $branchDetails->email;
//             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
//             $order->receiver_name = $request->receiver_name;
//             $order->receiver_cnumber = $request->receiver_number;
//             $order->receiver_email = $request->receiver_email ?? '';
//             $order->receiver_add = $request->receiver_address ?? '';
//         } elseif ($request->parcel_type === 'Pickup') {
//             $order->sender_pincode = $request->receiverPincode;
//             $order->sender_name = $request->receiver_name;
//             $order->sender_number = $request->receiver_number;
//             $order->sender_email = $request->receiver_email ?? '';
//             $order->sender_address = $request->receiver_address ?? '';

    //             $order->receiver_pincode = $branchDetails->pincode;
//             $order->receiver_name = $branchDetails->fullname;
//             $order->receiver_cnumber = $branchDetails->phoneno;
//             $order->receiver_email = $branchDetails->email;
//             $order->receiver_add = $branchDetails->fulladdress;
//         } else { // Direct
//             $order->sender_pincode = $branchDetails->pincode;
//             $order->sender_name = $branchDetails->fullname;
//             $order->sender_number = $branchDetails->phoneno;
//             $order->sender_email = $branchDetails->email;
//             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
//             $order->receiver_name = $request->receiver_name;
//             $order->receiver_cnumber = $request->receiver_number;
//             $order->receiver_email = $request->receiver_email ?? '';
//             $order->receiver_add = $request->receiver_address ?? '';
//         }

    //         // Find branch for seller_id based on sender_pincode
//         $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
//             ->where('type', 'Delivery')
//             ->first();

    //         $order->service_type = $request->service_type ?? null;
//         $order->service_title = $request->service_title ?? null;
//         $order->service_price = $request->service_price ?? null;
//         $order->order_id = $new_order_id;
//         $order->seller_id = $branch->id ?? null;
//         $order->assign_by = $branch->id ?? null;
//         $order->seller_primary_id = $userId;
//         $order->price = trim(str_replace('₹', '', $request->price));
//         $order->payment_mode = $request->payment_methods ?? null;
//         $order->codAmount = $request->codAmount ?? null;
//         $order->insurance = $request->insurance ?? null;
//         $order->order_status = 'Booked';
//         $order->parcel_type = $request->parcel_type;
//         $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $order->created_at = $this->date;
//         $order->updated_at = $this->date;

    //         // GET THE DELIVERY BOY based on parcel_type
//         $searchPincode = ($request->parcel_type === 'Pickup') ? $order->sender_pincode : $branchDetails->pincode;
//         $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
//             $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
//                   ->orWhere('pincode', 'LIKE', '%' . $searchPincode . '%');
//         })->first();

    //         // Ensure delivery boy is assigned for delivery
//         if ($request->parcel_type === 'delivery' && !$deliveryBoy) {
//             return $request->ajax()
//                 ? response()->json(['success' => false, 'message' => 'No delivery boy available for branch pincode'], 400)
//                 : redirect()->back()->with('message', 'No delivery boy available for branch pincode');
//         }

    //         // Assign delivery boy to order if found
//         if ($deliveryBoy) {
//             $order->assign_to = $deliveryBoy->id;
//         }

    //         $order->save();

    //         // Create order history entry
//         $order_history = new OrderHistory();
//         $order_history->tracking_id = $new_order_id;
//         $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $order_history->status = 'Booked';
//         $order_history->order_id = $order->id;
//         $order_history->save();

    //         // Prepare and send email
//         if ($order->sender_email || $order->receiver_email) {
//             $mailData = [
//                 'title' => 'Order Booking Confirmation',
//                 'order_id' => $order->order_id,
//                 'service_type' => $order->service_type,
//                 'price' => $order->price,
//                 'payment_mode' => $order->payment_mode,
//                 'sender_name' => $order->sender_name,
//                 'sender_number' => $order->sender_number,
//                 'sender_email' => $order->sender_email,
//                 'sender_address' => $order->sender_address,
//                 'sender_pincode' => $order->sender_pincode,
//                 'receiver_name' => $order->receiver_name,
//                 'receiver_cnumber' => $order->receiver_cnumber,
//                 'receiver_email' => $order->receiver_email,
//                 'receiver_add' => $order->receiver_add,
//                 'receiver_pincode' => $order->receiver_pincode,
//                 'datetime' => $order->datetime,
//             ];

    //             try {
//                 $recipients = array_filter([
//                     $order->sender_email,
//                     $order->receiver_email
//                 ], fn($email) => !empty($email));

    //                 if (!empty($recipients)) {
//                     // Use queue for better performance
//                     Mail::to($recipients)->queue(new SellerBookingConfirmation($mailData));
//                     \Log::info("Booking confirmation email queued for " . implode(', ', $recipients) . " for order ID: {$new_order_id}");
//                 } else {
//                     \Log::warning("No valid email addresses provided for order ID: {$new_order_id}");
//                 }
//             } catch (\Exception $e) {
//                 \Log::error("Failed to queue booking confirmation email for order ID: {$new_order_id}: " . $e->getMessage());
//                 // Optionally, you could add a fallback or notification here
//             }
//         }

    //         $status = true;
//         $msg = 'Order created successfully!';
//     } else {
//         $status = false;
//         $msg = 'Insufficient Balance';
//     }

    //     if ($request->ajax()) {
//         return response()->json([
//             'success' => $status,
//             'message' => $msg,
//         ]);
//     }

    //     return redirect()->back()->with('message', $msg);
// }

    //     public function addOrderParcel(Request $request)
//     {
//     // Validate request inputs
//     $request->validate([
//         'parcel_type' => 'required|in:delivery,Pickup,Direct',
//         'receiverPincode' => 'required',
//         'receiver_name' => 'required',
//         'receiver_number' => 'required',
//         'price' => 'required|numeric|min:0',
//     ]);

    //     $userId = Session::get('bid');
//     $branchDetails = Branch::where('id', $userId)->first();

    //     // Check if branch details exist
//     if (!$branchDetails || empty($branchDetails->pincode)) {
//         return $request->ajax()
//             ? response()->json(['success' => false, 'message' => 'Invalid branch or pincode data'], 400)
//             : redirect()->back()->with('message', 'Invalid branch or pincode data');
//     }

    //     $wallet = Wallet::where('userid', $userId)
//         ->orderBy('id', 'desc')
//         ->first();

    //     if ($wallet && $wallet->total >= $request->price) {
//         $wlt = new Wallet();
//         $wlt->userid = $userId;
//         $wlt->d_amount = $request->price;
//         $total = $wallet->total - $request->price;
//         $wlt->total = $total;
//         $wlt->msg = 'debit';
//         $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $wlt->save();

    //         $order = new Order();
//         $new_order_id = 'DL' . $this->generateRandomCode();

    //         // Set sender and receiver details based on parcel_type
//         if ($request->parcel_type === 'delivery') {
//             $order->sender_pincode = $branchDetails->pincode;
//             $order->sender_name = $branchDetails->fullname;
//             $order->sender_number = $branchDetails->phoneno;
//             $order->sender_email = $branchDetails->email;
//             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
//             $order->receiver_name = $request->receiver_name;
//             $order->receiver_cnumber = $request->receiver_number;
//             $order->receiver_email = $request->receiver_email ?? '';
//             $order->receiver_add = $request->receiver_address ?? '';
//         } elseif ($request->parcel_type === 'Pickup') {
//             $order->sender_pincode = $request->receiverPincode;
//             $order->sender_name = $request->receiver_name;
//             $order->sender_number = $request->receiver_number;
//             $order->sender_email = $request->receiver_email ?? '';
//             $order->sender_address = $request->receiver_address ?? '';

    //             $order->receiver_pincode = $branchDetails->pincode;
//             $order->receiver_name = $branchDetails->fullname;
//             $order->receiver_cnumber = $branchDetails->phoneno;
//             $order->receiver_email = $branchDetails->email;
//             $order->receiver_add = $branchDetails->fulladdress;
//         } else { // Direct
//             $order->sender_pincode = $branchDetails->pincode;
//             $order->sender_name = $branchDetails->fullname;
//             $order->sender_number = $branchDetails->phoneno;
//             $order->sender_email = $branchDetails->email;
//             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
//             $order->receiver_name = $request->receiver_name;
//             $order->receiver_cnumber = $request->receiver_number;
//             $order->receiver_email = $request->receiver_email ?? '';
//             $order->receiver_add = $request->receiver_address ?? '';
//         }

    //         // Find branch for seller_id based on sender_pincode
//         $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
//             ->where('type', 'Delivery')
//             ->first();

    //         $order->service_type = $request->service_type ?? null;
//         $order->service_title = $request->service_title ?? null;
//         $order->service_price = $request->service_price ?? null;
//         $order->order_id = $new_order_id;
//         $order->seller_id = $branch->id ?? null;
//         $order->assign_by = $branch->id ?? null;
//         $order->seller_primary_id = $userId;
//         $order->price = trim(str_replace('₹', '', $request->price));
//         $order->payment_mode = $request->payment_methods ?? null;
//         $order->codAmount = $request->codAmount ?? null;
//         $order->insurance = $request->insurance ?? null;
//         $order->order_status = 'Booked';
//         $order->parcel_type = $request->parcel_type;
//         $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $order->created_at = $this->date;
//         $order->updated_at = $this->date;

    //         // GET THE DELIVERY BOY based on parcel_type
//         $searchPincode = ($request->parcel_type === 'Pickup') ? $order->sender_pincode : $branchDetails->pincode;
//         $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
//             $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
//                   ->orWhere('pincode', 'LIKE', '%' . $searchPincode . '%');
//         })->first();

    //         // Ensure delivery boy is assigned for delivery
//         if ($request->parcel_type === 'delivery' && !$deliveryBoy) {
//             return $request->ajax()
//                 ? response()->json(['success' => false, 'message' => 'No delivery boy available for branch pincode'], 400)
//                 : redirect()->back()->with('message', 'No delivery boy available for branch pincode');
//         }

    //         // Assign delivery boy to order if found
//         if ($deliveryBoy) {
//             $order->assign_to = $deliveryBoy->id;
//         }

    //         $order->save();

    //         // Create order history entry
//         $order_history = new OrderHistory();
//         $order_history->tracking_id = $new_order_id;
//         $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $order_history->status = 'Booked';
//         $order_history->order_id = $order->id;
//         $order_history->save();

    //         // Prepare mail data
//         $mailData = null;
//         if ($order->sender_email || $order->receiver_email) {
//             $mailData = [
//                 'title' => 'Order Booking Confirmation',
//                 'order_id' => $order->order_id,
//                 'service_type' => $order->service_type,
//                 'price' => $order->price,
//                 'payment_mode' => $order->payment_mode,
//                 'sender_name' => $order->sender_name,
//                 'sender_number' => $order->sender_number,
//                 'sender_email' => $order->sender_email,
//                 'sender_address' => $order->sender_address,
//                 'sender_pincode' => $order->sender_pincode,
//                 'receiver_name' => $order->receiver_name,
//                 'receiver_cnumber' => $order->receiver_cnumber,
//                 'receiver_email' => $order->receiver_email,
//                 'receiver_add' => $order->receiver_add,
//                 'receiver_pincode' => $order->receiver_pincode,
//                 'datetime' => $order->datetime,
//             ];
//         }

    //         // Send email using shutdown function
//         if ($mailData) {
//             register_shutdown_function(function () use ($order, $mailData, $new_order_id) {
//                 try {
//                     $recipients = [];
//                     if ($order->sender_email) {
//                         $recipients[] = $order->sender_email;
//                     }
//                     if ($order->receiver_email) {
//                         $recipients[] = $order->receiver_email;
//                     }

    //                     if (!empty($recipients)) {
//                         Mail::to($recipients)->send(new SellerBookingConfirmation($mailData));
//                         \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$new_order_id}");
//                     } else {
//                         \Log::warning("No valid email addresses provided for order ID: {$new_order_id}");
//                     }
//                 } catch (\Exception $e) {
//                     \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
//                 }
//             });
//         }

    //         $status = true;
//         $msg = 'Order created successfully!';
//     } else {
//         $status = false;
//         $msg = 'Insufficient Balance';
//     }

    //     if ($request->ajax()) {
//         return response()->json([
//             'success' => $status,
//             'message' => $msg,
//             'data' => $status ? $new_order_id : null,
//         ]);
//     }

    //     return redirect()->back()->with('message', $msg);
// }

    // 16 june 
//     public function addOrderParcel(Request $request)
// {
//     // Validate request inputs
//     $request->validate([
//         'parcel_type' => 'required|in:delivery,Pickup,Direct',
//         'receiverPincode' => 'required',
//         'receiver_name' => 'required',
//         'receiver_number' => 'required',
//         'price' => 'required|numeric|min:0',
//     ]);

    //     $userId = Session::get('bid');
//     $branchDetails = Branch::where('id', $userId)->first();

    //     // Check if branch details exist
//     if (!$branchDetails || empty($branchDetails->pincode)) {
//         return $request->ajax()
//             ? response()->json(['success' => false, 'message' => 'Invalid branch or pincode data'], 400)
//             : redirect()->back()->with('message', 'Invalid branch or pincode data');
//     }

    //     $wallet = Wallet::where('userid', $userId)
//         ->orderBy('id', 'desc')
//         ->first();

    //     if (!$wallet || $wallet->total < $request->price) {
//         return $request->ajax()
//             ? response()->json(['success' => false, 'message' => 'Insufficient Balance'], 400)
//             : redirect()->back()->with('message', 'Insufficient Balance');
//     }

    //     // Start a database transaction
//     DB::beginTransaction();

    //     try {
//         $order = new Order();
//         $new_order_id = 'DL' . $this->generateRandomCode();

    //         // Set sender and receiver details based on parcel_type
//         if ($request->parcel_type === 'delivery') {
//             $order->sender_pincode = $branchDetails->pincode;
//             $order->sender_name = $branchDetails->fullname;
//             $order->sender_number = $branchDetails->phoneno;
//             $order->sender_email = $branchDetails->email;
//             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
//             $order->receiver_name = $request->receiver_name;
//             $order->receiver_cnumber = $request->receiver_number;
//             $order->receiver_email = $request->receiver_email ?? '';
//             $order->receiver_add = $request->receiver_address ?? '';
//         } elseif ($request->parcel_type === 'Pickup') {
//             $order->sender_pincode = $request->receiverPincode;
//             $order->sender_name = $request->receiver_name;
//             $order->sender_number = $request->receiver_number;
//             $order->sender_email = $request->receiver_email ?? '';
//             $order->sender_address = $request->receiver_address ?? '';

    //             $order->receiver_pincode = $branchDetails->pincode;
//             $order->receiver_name = $branchDetails->fullname;
//             $order->receiver_cnumber = $branchDetails->phoneno;
//             $order->receiver_email = $branchDetails->email;
//             $order->receiver_add = $branchDetails->fulladdress;
//         } else { // Direct
//             $order->sender_pincode = $branchDetails->pincode;
//             $order->sender_name = $branchDetails->fullname;
//             $order->sender_number = $branchDetails->phoneno;
//             $order->sender_email = $branchDetails->email;
//             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
//             $order->receiver_name = $request->receiver_name;
//             $order->receiver_cnumber = $request->receiver_number;
//             $order->receiver_email = $request->receiver_email ?? '';
//             $order->receiver_add = $request->receiver_address ?? '';
//         }

    //         // Find branch for seller_id based on sender_pincode
//         $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
//             ->where('type', 'Delivery')
//             ->first();

    //         $order->service_type = $request->service_type ?? null;
//         $order->service_title = $request->service_title ?? null;
//         $order->service_price = $request->service_price ?? null;
//         $order->order_id = $new_order_id;
//         $order->seller_id = $branch->id ?? null;
//         $order->assign_by = $branch->id ?? null;
//         $order->seller_primary_id = $userId;
//         $order->price = trim(str_replace('₹', '', $request->price));
//         $order->payment_mode = $request->payment_methods ?? null;
//         $order->codAmount = $request->codAmount ?? null;
//         $order->insurance = $request->insurance ?? null;
//         $order->order_status = 'Booked';
//         $order->parcel_type = $request->parcel_type;
//         $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $order->created_at = $this->date;
//         $order->updated_at = $this->date;

    //         // GET THE DELIVERY BOY based on parcel_type
//         $searchPincode = ($request->parcel_type === 'Pickup') ? $order->sender_pincode : $branchDetails->pincode;
//         $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
//             $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
//                   ->orWhere('pincode', 'LIKE', '%' . $searchPincode . '%');
//         })->first();

    //         // Ensure delivery boy is assigned for delivery
//         if ($request->parcel_type === 'delivery' && !$deliveryBoy) {
//             throw new \Exception('No delivery boy available for branch pincode');
//         }

    //         // Assign delivery boy to order if found
//         if ($deliveryBoy) {
//             $order->assign_to = $deliveryBoy->id;
//         }

    //         // Deduct wallet amount
//         $wlt = new Wallet();
//         $wlt->userid = $userId;
//         $wlt->d_amount = $request->price;
//         $wlt->total = $wallet->total - $request->price;
//         $wlt->msg = 'debit';
//         $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $wlt->save();

    //         // Save the order
//         $order->save();

    //         // Create order history entry
//         $order_history = new OrderHistory();
//         $order_history->tracking_id = $new_order_id;
//         $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $order_history->status = 'Booked';
//         $order_history->order_id = $order->id;
//         $order_history->save();

    //         // Prepare mail data
//         $mailData = null;
//         if ($order->sender_email || $order->receiver_email) {
//             $mailData = [
//                 'title' => 'Order Booking Confirmation',
//                 'order_id' => $order->order_id,
//                 'service_type' => $order->service_type,
//                 'price' => $order->price,
//                 'payment_mode' => $order->payment_mode,
//                 'sender_name' => $order->sender_name,
//                 'sender_number' => $order->sender_number,
//                 'sender_email' => $order->sender_email,
//                 'sender_address' => $order->sender_address,
//                 'sender_pincode' => $order->sender_pincode,
//                 'receiver_name' => $order->receiver_name,
//                 'receiver_cnumber' => $order->receiver_cnumber,
//                 'receiver_email' => $order->receiver_email,
//                 'receiver_add' => $order->receiver_add,
//                 'receiver_pincode' => $order->receiver_pincode,
//                 'datetime' => $order->datetime,
//             ];
//         }

    //         // Commit the transaction
//         DB::commit();

    //         // Send email using shutdown function
//         if ($mailData) {
//             register_shutdown_function(function () use ($order, $mailData, $new_order_id) {
//                 try {
//                     $recipients = array_filter([$order->sender_email, $order->receiver_email]);
//                     if (!empty($recipients)) {
//                         Mail::to($recipients)->send(new SellerBookingConfirmation($mailData));
//                         \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$new_order_id}");
//                     } else {
//                         \Log::warning("No valid email addresses provided for order ID: {$new_order_id}");
//                     }
//                 } catch (\Exception $e) {
//                     \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
//                 }
//             });
//         }

    //         return $request->ajax()
//             ? response()->json(['success' => true, 'message' => 'Order created successfully!', 'data' => $new_order_id])
//             : redirect()->back()->with('message', 'Order created successfully!');
//     } catch (\Exception $e) {
//         // Rollback the transaction on any error
//         DB::rollBack();

    //         \Log::error("Order creation failed: " . $e->getMessage());

    //         return $request->ajax()
//             ? response()->json(['success' => false, 'message' => $e->getMessage()], 400)
//             : redirect()->back()->with('message', $e->getMessage());
//     }
// }


    // order id sequence change 
//     public function addOrderParcel(Request $request)
//     {
//         $request->validate([
//             'parcel_type' => 'required|in:delivery,Pickup,Direct',
//             'receiverPincode' => 'required',
//             'receiver_name' => 'required',
//             'receiver_number' => 'required',
//             'price' => 'required|numeric|min:0',
//         ]);

    //         $userId = Session::get('bid');
//         $branchDetails = Branch::where('id', $userId)->first();

    //         if (!$branchDetails || empty($branchDetails->pincode)) {
//             return $request->ajax()
//                 ? response()->json(['success' => false, 'message' => 'Invalid branch or pincode data'], 400)
//                 : redirect()->back()->with('message', 'Invalid branch or pincode data');
//         }

    //         $wallet = Wallet::where('userid', $userId)
//             ->orderBy('id', 'desc')
//             ->first();

    //         if (!$wallet || $wallet->total < $request->price) {
//             return $request->ajax()
//                 ? response()->json(['success' => false, 'message' => 'Insufficient Balance'], 400)
//                 : redirect()->back()->with('message', 'Insufficient Balance');
//         }

    //         DB::beginTransaction();

    //         try {
//             $order = new Order();

    //             if ($request->parcel_type === 'delivery') {
//                 $order->sender_pincode = $branchDetails->pincode;
//                 $order->sender_name = $branchDetails->fullname;
//                 $order->sender_number = $branchDetails->phoneno;
//                 $order->sender_email = $branchDetails->email;
//                 $order->sender_address = $branchDetails->fulladdress;

    //                 $order->receiver_pincode = $request->receiverPincode;
//                 $order->receiver_name = $request->receiver_name;
//                 $order->receiver_cnumber = $request->receiver_number;
//                 $order->receiver_email = $request->receiver_email ?? '';
//                 $order->receiver_add = $request->receiver_address ?? '';
//             } elseif ($request->parcel_type === 'Pickup') {
//                 $order->sender_pincode = $request->receiverPincode;
//                 $order->sender_name = $request->receiver_name;
//                 $order->sender_number = $request->receiver_number;
//                 $order->sender_email = $request->receiver_email ?? '';
//                 $order->sender_address = $request->receiver_address ?? '';

    //                 $order->receiver_pincode = $branchDetails->pincode;
//                 $order->receiver_name = $branchDetails->fullname;
//                 $order->receiver_cnumber = $branchDetails->phoneno;
//                 $order->receiver_email = $branchDetails->email;
//                 $order->receiver_add = $branchDetails->fulladdress;
//             } else {
//                 $order->sender_pincode = $branchDetails->pincode;
//                 $order->sender_name = $branchDetails->fullname;
//                 $order->sender_number = $branchDetails->phoneno;
//                 $order->sender_email = $branchDetails->email;
//                 $order->sender_address = $branchDetails->fulladdress;

    //                 $order->receiver_pincode = $request->receiverPincode;
//                 $order->receiver_name = $request->receiver_name;
//                 $order->receiver_cnumber = $request->receiver_number;
//                 $order->receiver_email = $request->receiver_email ?? '';
//                 $order->receiver_add = $request->receiver_address ?? '';
//             }

    //             $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
//                 ->where('type', 'Delivery')
//                 ->first();

    //             $order->service_type = $request->service_type ?? null;
//             $order->service_title = $request->service_title ?? null;
//             $order->service_price = $request->service_price ?? null;
//             $order->order_id = ''; // Placeholder
//             $order->seller_id = $branch->id ?? null;
//             $order->assign_by = $branch->id ?? null;
//             $order->seller_primary_id = $userId;
//             $order->price = trim(str_replace('₹', '', $request->price));
//             $order->payment_mode = $request->payment_methods ?? null;
//             $order->codAmount = $request->codAmount ?? null;
//             $order->insurance = $request->insurance ?? null;
//             $order->order_status = 'Booked';
//             $order->parcel_type = $request->parcel_type;
//             $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//             $order->created_at = $this->date;
//             $order->updated_at = $this->date;

    //             $searchPincode = ($request->parcel_type === 'Pickup') ? $order->sender_pincode : $branchDetails->pincode;
//             $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
//                 $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
//                       ->orWhere('pincode', 'LIKE', '%' . $searchPincode . '%');
//             })->first();

    //             if ($request->parcel_type === 'delivery' && !$deliveryBoy) {
//                 throw new \Exception('No delivery boy available for branch pincode');
//             }

    //             if ($deliveryBoy) {
//                 $order->assign_to = $deliveryBoy->id;
//             }

    //             // Debit wallet
//             $wlt = new Wallet();
//             $wlt->userid = $userId;
//             $wlt->d_amount = $request->price;
//             $wlt->total = $wallet->total - $request->price;
//             $wlt->msg = 'debit';
//             $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//             $wlt->save();

    //             // Save order first to get ID
//             $order->save();

    //             // Generate final order ID after saving
//             $finalOrderId = 'DP1516800' . $order->id;
//             $order->order_id = $finalOrderId;
//             $order->save();

    //             // Order history
//             $order_history = new OrderHistory();
//             $order_history->tracking_id = $finalOrderId;
//             $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//             $order_history->status = 'Booked';
//             $order_history->order_id = $order->id;
//             $order_history->save();

    //             $mailData = null;
//             if ($order->sender_email || $order->receiver_email) {
//                 $mailData = [
//                     'title' => 'Order Booking Confirmation',
//                     'order_id' => $order->order_id,
//                     'service_type' => $order->service_type,
//                     'price' => $order->price,
//                     'payment_mode' => $order->payment_mode,
//                     'sender_name' => $order->sender_name,
//                     'sender_number' => $order->sender_number,
//                     'sender_email' => $order->sender_email,
//                     'sender_address' => $order->sender_address,
//                     'sender_pincode' => $order->sender_pincode,
//                     'receiver_name' => $order->receiver_name,
//                     'receiver_cnumber' => $order->receiver_cnumber,
//                     'receiver_email' => $order->receiver_email,
//                     'receiver_add' => $order->receiver_add,
//                     'receiver_pincode' => $order->receiver_pincode,
//                     'datetime' => $order->datetime,
//                 ];
//             }

    //             DB::commit();

    //             if ($mailData) {
//                 register_shutdown_function(function () use ($order, $mailData, $finalOrderId) {
//                     try {
//                         $recipients = array_filter([$order->sender_email, $order->receiver_email]);
//                         if (!empty($recipients)) {
//                             Mail::to($recipients)->queue(new SellerBookingConfirmation($mailData));
//                             \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$finalOrderId}");
//                         } else {
//                             \Log::warning("No valid email addresses provided for order ID: {$finalOrderId}");
//                         }
//                     } catch (\Exception $e) {
//                         \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
//                     }
//                 });
//             }

    //             return $request->ajax()
//                 ? response()->json(['success' => true, 'message' => 'Order created successfully!', 'data' => $finalOrderId])
//                 : redirect()->back()->with('message', 'Order created successfully!');
//         } catch (\Exception $e) {
//             DB::rollBack();
//             \Log::error("Order creation failed: " . $e->getMessage());

    //             return $request->ajax()
//                 ? response()->json(['success' => false, 'message' => $e->getMessage()], 400)
//                 : redirect()->back()->with('message', $e->getMessage());
//         }
// }


    public function addOrderParcel(Request $request)
    {
        $request->validate([
            'parcel_type' => 'required|in:delivery,Pickup,Direct',
            'receiverPincode' => 'required',
            'receiver_name' => 'required',
            'receiver_number' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $userId = Session::get('bid');
        $branchDetails = Branch::where('id', $userId)->first();

        if (!$branchDetails || empty($branchDetails->pincode)) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Invalid branch or pincode data'], 400)
                : redirect()->back()->with('message', 'Invalid branch or pincode data');
        }

        $wallet = Wallet::where('userid', $userId)
            ->orderBy('id', 'desc')
            ->first();

        if (!$wallet || $wallet->total < $request->price) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Insufficient Balance'], 400)
                : redirect()->back()->with('message', 'Insufficient Balance');
        }

        DB::beginTransaction();

        try {
            $order = new Order();

            if ($request->parcel_type === 'delivery') {
                $order->sender_pincode = $branchDetails->pincode;
                $order->sender_name = $branchDetails->fullname;
                $order->sender_number = $branchDetails->phoneno;
                $order->sender_email = $branchDetails->email;
                $order->sender_address = $branchDetails->fulladdress;

                $order->receiver_pincode = $request->receiverPincode;
                $order->receiver_name = $request->receiver_name;
                $order->receiver_cnumber = $request->receiver_number;
                $order->receiver_email = $request->receiver_email ?? '';
                $order->receiver_add = $request->receiver_address ?? '';
            } elseif ($request->parcel_type === 'Pickup') {
                $order->sender_pincode = $request->receiverPincode;
                $order->sender_name = $request->receiver_name;
                $order->sender_number = $request->receiver_number;
                $order->sender_email = $request->receiver_email ?? '';
                $order->sender_address = $request->receiver_address ?? '';

                $order->receiver_pincode = $branchDetails->pincode;
                $order->receiver_name = $branchDetails->fullname;
                $order->receiver_cnumber = $branchDetails->phoneno;
                $order->receiver_email = $branchDetails->email;
                $order->receiver_add = $branchDetails->fulladdress;
            } else {
                $order->sender_pincode = $branchDetails->pincode;
                $order->sender_name = $branchDetails->fullname;
                $order->sender_number = $branchDetails->phoneno;
                $order->sender_email = $branchDetails->email;
                $order->sender_address = $branchDetails->fulladdress;

                $order->receiver_pincode = $request->receiverPincode;
                $order->receiver_name = $request->receiver_name;
                $order->receiver_cnumber = $request->receiver_number;
                $order->receiver_email = $request->receiver_email ?? '';
                $order->receiver_add = $request->receiver_address ?? '';
            }

            $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
                ->where('type', 'Delivery')
                ->first();

            $order->service_type = $request->service_type ?? null;
            $order->service_title = $request->service_title ?? null;
            $order->service_price = $request->service_price ?? null;
            $order->order_id = ''; // Placeholder
            $order->seller_id = $branch->id ?? null;
            $order->assign_by = $branch->id ?? null;
            $order->seller_primary_id = $userId;
            $order->price = trim(str_replace('₹', '', $request->price));
            $order->payment_mode = $request->payment_methods ?? null;
            $order->codAmount = $request->codAmount ?? null;
            $order->insurance = $request->insurance ?? null;
            $order->order_status = 'Booked';
            $order->parcel_type = $request->parcel_type;
            $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $order->created_at = $this->date;
            $order->updated_at = $this->date;

            $searchPincode = ($request->parcel_type === 'Pickup') ? $order->sender_pincode : $branchDetails->pincode;
            $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
                $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
                    ->orWhere('pincode', 'LIKE', '%' . $searchPincode . '%');
            })->first();

            if ($request->parcel_type === 'delivery' && !$deliveryBoy) {
                throw new \Exception('No delivery boy available for branch pincode');
            }

            if ($deliveryBoy) {
                $order->assign_to = $deliveryBoy->id;
            }

            // Debit wallet
            $wlt = new Wallet();
            $wlt->userid = $userId;
            $wlt->d_amount = $request->price;
            $wlt->total = $wallet->total - $request->price;
            $wlt->msg = 'debit';
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->save();

            // Save order first to get ID
            $order->save();

            // Generate final order ID after saving
            $finalOrderId = 'DP1516800' . $order->id;
            $order->order_id = $finalOrderId;
            $order->save();

            // Order history
            $order_history = new OrderHistory();
            $order_history->tracking_id = $finalOrderId;
            $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $order_history->status = 'Booked';
            $order_history->order_id = $order->id;
            $order_history->save();

            $mailData = null;
            if ($order->sender_email || $order->receiver_email) {
                $mailData = [
                    'title' => 'Order Booking Confirmation',
                    'order_id' => $order->order_id,
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

            // DB::commit();
            // if ($mailData) {
            //     register_shutdown_function(function () use ($order, $mailData, $finalOrderId) {
            //         try {
            //             $recipients = array_filter([$order->sender_email, $order->receiver_email]);
            //             if (!empty($recipients)) {
            //                 Mail::to($recipients)->queue(new SellerBookingConfirmation($mailData));
            //                 \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$finalOrderId}");
            //             } else {
            //                 \Log::warning("No valid email addresses provided for order ID: {$finalOrderId}");
            //             }
            //         } catch (\Exception $e) {
            //             \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
            //         }
            //     });
            // }

            if ($mailData) {
                $orderId = $finalOrderId;

                register_shutdown_function(function () use ($order, $mailData, $orderId) {
                    try {
                        $recipients = [];

                        if (!empty($order->sender_email)) {
                            $recipients[] = $order->sender_email;
                        }

                        if (!empty($order->receiver_email)) {
                            $recipients[] = $order->receiver_email;
                        }

                        if (!empty($recipients)) {
                            Mail::to($recipients)->queue(new SellerBookingConfirmation($mailData));
                            \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$orderId}");
                        } else {
                            \Log::warning("No valid email addresses provided for order ID: {$orderId}");
                        }
                    } catch (\Exception $e) {
                        \Log::error("Failed to send booking confirmation email for order ID {$orderId}: " . $e->getMessage());
                    }
                });
            }

            DB::commit();


            return $request->ajax()
                ? response()->json(['success' => true, 'message' => 'Order created successfully!', 'data' => $finalOrderId])
                : redirect()->back()->with('message', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Order creation failed: " . $e->getMessage());

            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $e->getMessage()], 400)
                : redirect()->back()->with('message', $e->getMessage());
        }
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

    public function allOrders()
    {
        $user = Session::get('bid');
        $data = Order::where('seller_id', $user)->orderBy('id', 'desc')->get();

        return view('booking.allOrders', compact('data'));
    }

    public function bookingAssignGet(Request $request)
    {
        $order = Order::find($request->id);
        $data = DlyBoy::where('userid', $order->seller_id)->where('status', 'active')->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'orderId' => $order->order_id
            ]);
        }
    }

    public function bookingAssignAdd(Request $request)
    {
        if (!empty($request->type)) {
            $order = Order::where('order_id', $request->orderIdedit)->first();
            $order->order_status = $request->deliverBoy;
            $order->status_message = $request->status_message;
            $order->save();
            $msg = 'Order status update!';
        } else {
            $order = Order::where('order_id', $request->orderIdedit)->first();
            $order->assign_to = $request->deliverBoy;
            $order->status_message = $request->status_message;
            $order->save();
            $msg = 'Order assign successfully!';
        }

        $data = Order::where('seller_id', $order->seller_id)->orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('seller.inc.deliveryBoy', compact('data'))->render(),
            ]);
        }
    }

    public function bookingStatusGet(Request $request)
    {
        $order = Order::find($request->id);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'orderId' => $order->order_id
            ]);
        }
    }

    public function bookingInvoice($id)
    {
        $data = Order::where('order_id', $id)->first();
        return view('booking.invoice', compact('data'));
    }

    // public function MonthlyBookingInvoices()
    // {
    //     // Get the logged-in user's branch ID from session
    //     $userId = Session::get('bid');

    //     // Fetch branch data
    //     $branch = Branch::where('id', $userId)->first();

    //     // Initialize data object
    //     $data = new \stdClass();
    //     $data->gstno = $branch->gst_panno ?? null;
    //     $data->branch_fullname = $branch->fullname ?? null;
    //     $data->branch_fulladdress = $branch->fulladdress ?? null;
    //     $data->branch_phoneno = $branch->phoneno ?? null;
    //     $data->branch_pincode = $branch->pincode ?? null;

    //     // Get year and month from GET parameters (default to current year and month)
    //     $year = request()->input('year', date('Y'));
    //     $month = request()->input('month', date('F')); // e.g., January, February

    //     // Convert month name to month number (e.g., January -> 01)
    //     $monthNumber = date('m', strtotime($year . '-' . $month . '-01'));

    //     // Query to sum the price of all delivered orders for the selected year, month, and branch
    //     $totalPrice = Order::where('seller_primary_id', $userId)
    //                       ->where('order_status', 'Delivered')
    //                       ->whereRaw("SUBSTRING_INDEX(datetime, ' | ', 1) LIKE ?", ["%-$monthNumber-$year"])
    //                       ->sum('price');

    //     // Add total price and selected year/month to $data
    //     $data->total_price = $totalPrice;
    //     $data->selected_year = $year;
    //     $data->selected_month = $month;

    //     // Render the view
    //     return view('booking.montlyinvoice', compact('data'));
    // }

    public function MonthlyBookingInvoices()
    {
        // Get the logged-in user's branch ID from session
        $userId = Session::get('bid');

        // Fetch branch data
        $branch = Branch::where('id', $userId)->first();

        // Initialize data object
        $data = new \stdClass();
        $data->gstno = $branch->gst_panno ?? null;
        $data->branch_fullname = $branch->fullname ?? null;
        $data->branch_fulladdress = $branch->fulladdress ?? null;
        $data->branch_phoneno = $branch->phoneno ?? null;
        $data->branch_pincode = $branch->pincode ?? null;

        // Get year and month from GET parameters (default to current year and month)
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('F')); // e.g., January, February

        // Convert month name to month number (e.g., January -> 01)
        $monthNumber = date('m', strtotime($year . '-' . $month . '-01'));

        // Generate invoice number (e.g., DP202506-123)
        $data->invoice_number = 'DP' . $year . $monthNumber . '-' . $userId;

        // Query to sum the price of all delivered orders for the selected year, month, and branch
        $totalPrice = Order::where('seller_primary_id', $userId)
            ->where('order_status', 'Delivered')
            ->whereRaw("SUBSTRING_INDEX(datetime, ' | ', 1) LIKE ?", ["%-$monthNumber-$year"])
            ->sum('price');

        // Add total price and selected year/month to $data
        $data->total_price = $totalPrice;
        $data->selected_year = $year;
        $data->selected_month = $month;

        // Render the view
        return view('booking.montlyinvoice', compact('data'));
    }

    public function bookingLabel($id)
    {
        $data = Order::where('order_id', $id)->first();
        if ($data->payment_mode == 'COD') {
            return view('booking.label', compact('data'));
        } else {
            return view('booking.label1', compact('data'));
        }
    }

    public function deleteOrders(Request $request)
    {
        $order = Order::FindOrFail($request->id);
        $order->delete();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }
    }


    // public function allCodHistory()
    // {
    // $userId = Session::get('bid');
    // $data = Order::where('seller_primary_id', $userId)->where('order_status' , 'Delivered')->where('payment_mode','COD')->orderBy('id', 'desc')->get();
    // return view('booking.allCodHistory', compact('data'));
    // }

    public function allCodHistory(Request $request)
    {
        $userId = Session::get('bid');
        $query = Order::where('seller_primary_id', $userId)
            ->where('order_status', 'Delivered')
            ->where('payment_mode', 'COD');

        // Apply date filters if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $data = $query->orderBy('id', 'desc')->get();

        // Log the query and results for debugging
        Log::info('allCodHistory Query', [
            'user_id' => $userId,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'results_count' => $data->count(),
        ]);

        // Return JSON for AJAX requests
        if ($request->ajax()) {
            return response()->json(['data' => $data]);
        }

        // Render Blade template for initial page load
        return view('booking.allCodHistory', compact('data'));
    }

    public function dateCodHistory(Request $request)
    {
        $userId = Session::get('bid');
        $orders = Order::where('seller_id', $userId)->get();
        // dd($orders->pluck('id'));
        $dateRange = $request->date;
        list($startDate, $endDate) = explode(' - ', $dateRange);
        $startDate = date('d-m-Y 00:00:00', strtotime($startDate));
        $endDate = date('d-m-Y 23:59:59', strtotime($endDate));
        $ordersQuery = COD::whereBetween('datetime', [$startDate, $endDate])
            ->whereIn('order_id', $orders->pluck('id'))
            ->orderBy('id', 'desc');

        // $startDate = date('d-m-Y', strtotime($startDate));
        // $endDate = date('d-m-Y', strtotime($endDate));
        // $ordersQuery = COD::where('datetime', 'LIKE', "$startDate%")
        //     ->orWhere('datetime', 'LIKE', "$endDate%")
        //     ->whereIn('order_id', $orders->pluck('id'))
        //     ->orderBy('id', 'desc');
        $data = $ordersQuery->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('booking.inc.allCodHistoryData', compact('data'))->render(),
            ]);
        }
    }

    public function orderDetails($action)
    {
        $id = Session::get('bid');
        if ($action == 'toDayOrder' || $action == 'toDayPendingOrder' || $action == 'toDayOrderPicUp' || $action == 'toDayCompleteOrder' || $action == 'toDayCancelledOrder') {
            $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
            $ordersQuery = Order::where('datetime', 'like', $dateTiem . '%')
                ->where('seller_primary_id', $id);
            if ($action == 'toDayOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayPendingOrder') {
                $data = $ordersQuery->where('order_status', 'Booked')->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayOrderPicUp') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayCompleteOrder') {
                $data = $ordersQuery->where('order_status', 'Delivered')->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayCancelledOrder') {
                $data = $ordersQuery->where('order_status', 'Cancelled')->orderBy('id', 'desc')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->orderBy('id', 'desc')->get();
            }
        } elseif ($action == 'totalOrder' || $action == 'totalPendingOrder' || $action == 'totalOrderPicUp' || $action == 'totalCompleteOrder' || $action == 'totalCancelledOrder') {
            $ordersQuery = Order::where('seller_primary_id', $id);
            if ($action == 'totalOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalPendingOrder') {
                $data = $ordersQuery->where('order_status', 'Booked')->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalOrderPicUp') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalCompleteOrder') {
                $data = $ordersQuery->where('order_status', 'Delivered')->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalCancelledOrder') {
                $data = $ordersQuery->where('order_status', 'Cancelled')->orderBy('id', 'desc')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->orderBy('id', 'desc')->get();
            }
        } else {
            $data = Order::where('seller_primary_id', $id)->get();
        }
        return view('booking.orderDetails', compact('data'));
    }

    public function bookingEditGet(Request $request)
    {
        $order = Order::find($request->id);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        }
    }

    // public function bookingEditUpdate(Request $request)
    // {
    //     $order = Order::find($request->orderId);
    //     $order->receiver_name = $request->receiver_name;
    //     $order->receiver_cnumber = $request->receiver_cnumber;
    //     $order->receiver_email = $request->receiver_email ?? '';
    //     $order->receiver_add = $request->receiver_add ?? '';
    //     $order->save();
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Order Details update successfully!',
    //         ]);
    //     }
    // }

    public function bookingEditUpdate(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'orderId' => 'required|exists:orders,id',
            'sender_name' => 'nullable|string|max:255',
            'sender_number' => 'nullable|regex:/^[0-9]{10}$/', // Assuming 10-digit phone number
            'sender_email' => 'nullable|email|max:255',
            'sender_address' => 'nullable|string|max:500',
            'receiver_name' => 'nullable|string|max:255',
            'receiver_cnumber' => 'nullable|regex:/^[0-9]{10}$/', // Assuming 10-digit phone number
            'receiver_email' => 'nullable|email|max:255',
            'receiver_add' => 'nullable|string|max:500',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Find the order
            $order = Order::findOrFail($request->orderId);

            // Update sender details if provided
            if ($request->filled('sender_name')) {
                $order->sender_name = $request->sender_name;
                $order->sender_number = $request->sender_number ?? $order->sender_number;
                $order->sender_email = $request->sender_email ?? $order->sender_email;
                $order->sender_address = $request->sender_address ?? $order->sender_address;
            }

            // Update receiver details if provided
            if ($request->filled('receiver_name')) {
                $order->receiver_name = $request->receiver_name;
                $order->receiver_cnumber = $request->receiver_cnumber ?? $order->receiver_cnumber;
                $order->receiver_email = $request->receiver_email ?? $order->receiver_email;
                $order->receiver_add = $request->receiver_add ?? $order->receiver_add;
            }

            // Save the order
            $order->save();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Order details updated successfully!',
            ]);

        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //   public function cancelledOrder($id)
//   {
//     $order = Order::where('id', $id)->first();
//     $order->order_status = 'Cancelled';
//     $order->updated_at = $this->date;
//     if ($order->save()) {
//         $amount = $order->price;
//         $userId = $order->seller_primary_id;

    //         $wallet = Wallet::where('userid', $userId)
//             ->orderBy('id', 'desc')
//             ->first();
//         $total = $wallet->total + $amount;

    //         $wlt = new Wallet();
//         $wlt->userid = $userId;
//         $wlt->c_amount = $amount;
//         $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//         $wlt->total = $total;
//         $wlt->msg = 'Order Cancelled';
//         $wlt->save();
//     }
//     return back();
// }

    public function cancelledOrder($id)
    {
        $order = Order::where('id', $id)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        $order->order_status = 'Cancelled';
        $order->updated_at = $this->date ?? now('Asia/Kolkata'); // Fallback to now() if $this->date is not set
        if ($order->save()) {
            $amount = $order->price;
            $userId = $order->seller_primary_id;

            $wallet = Wallet::where('userid', $userId)
                ->orderBy('id', 'desc')
                ->first();
            $total = $wallet ? ($wallet->total + $amount) : $amount; // Fallback to $amount if no wallet exists

            $wlt = new Wallet();
            $wlt->userid = $userId;
            $wlt->c_amount = $amount;
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->total = $total;
            $wlt->msg = 'Order Cancelled';
            $wlt->save();

            return response()->json(['success' => true, 'message' => 'Order cancelled successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to cancel the order.']);
    }



    public function orderCodHistory()
    {
        $user = Session::get('bid');
        $orders = Order::where('seller_id', $user)->get();
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        $todayOrdersQuery = COD::where('datetime', 'like', $dateTime . '%')->whereIn('order_id', $orders->pluck('id'));
        $totalOrdersQuery = COD::whereIn('order_id', $orders->pluck('id'))->get();

        $todayOrder = Order::whereIn('id', $todayOrdersQuery->pluck('order_id'))->get();
        $totalOrder = Order::whereIn('id', $totalOrdersQuery->pluck('order_id'))->get();

        $orders = $totalOrder->toArray();
        $total_price = $totalOrder->sum('price');
        $today_price = $todayOrder->sum('price');

        $branchDetails = Branch::find($user);
        $branchPinCode = $branchDetails->pincode;
        // $branch = Branch::where(['pincode' => $branchPinCode, 'type' => 'Delivery'])->first();
        $branch = Branch::where('pincode', 'like', $branchPinCode . '%')->where('type', 'Delivery')->first();
        $OrderCod = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])->where('msg', 'like', 'debit%')->get();
        // Get total Order COD amount
        $OrderCodTotal = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])
            ->where('msg', 'like', 'debit%')
            ->sum('d_amount');
        // Get today's Order COD amount
        $OrderCodToDay = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])
            ->where('msg', 'like', 'debit%')
            ->where('datetime', 'like', $dateTime . '%')
            ->sum('d_amount');

        return view('booking.orderCodHistory', compact('total_price', 'today_price', 'OrderCod', 'OrderCodTotal', 'OrderCodToDay'));
    }

    public function orderCodAmount(Request $request)
    {
        $user = Session::get('bid');
        $branchDetails = Branch::find($user);
        $branchPinCode = $branchDetails->pincode;
        $branch = Branch::where(['pincode' => $branchPinCode, 'type' => 'Delivery'])->first();

        $wallet = OrderCod::where('userid', $branch->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($wallet && $wallet->total >= $request->amount) {
            $wlt = new OrderCod();
            $wlt->userid = $branch->id;
            $wlt->debit_by = $user;
            $wlt->d_amount = $request->amount;
            $total = $wallet->total - $request->amount;
            $wlt->total = $total;
            $wlt->refno = $request->refNo;
            $wlt->msg = 'debit/bb/' . $branchDetails->fullname;
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->save();
            $status = true;
            $msg = 'Amount debit successfully!';
        } else {
            $status = false;
            $msg = 'Amount not debit.';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => $status,
                'message' => $msg,
            ]);
        }
    }
}