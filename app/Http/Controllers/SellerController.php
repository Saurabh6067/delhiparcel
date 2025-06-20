<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\COD;
use App\Models\DlyBoy;
use App\Models\Order;
use App\Models\OrderCod;
use App\Models\OrderHistory;
use App\Models\PinCode;
use App\Models\Service;
use App\Models\Servicetype;
use App\Models\Wallet;
use App\Models\CodSellerAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\SellerBookingConfirmation;
use Illuminate\Support\Facades\Http;  // for phone pay

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;



class SellerController extends Controller
{

    protected $date;
    public function __construct()
    {
        $kolkataDateTime = Carbon::now('Asia/Kolkata');
        $this->date = $kolkataDateTime->format('Y-m-d H:i:s');
    }


    public function sellerConfirmLabel($id)
    {
        $data = Order::where('order_id', $id)->first();
        if ($data->payment_mode == 'COD') {
            return view('seller.email.label', compact('data'));
        } else {
            return view('seller.email.label1', compact('data'));
        }
    }



    public function sellerWallet()
    {
        $user = Session::get('sid');
        $data = Wallet::where('userid', $user)->orderBy('id', 'desc')->get();
        $amount = $data->first();
        return view('seller.sellerWallet', compact('data', 'amount'));
    }


    public function CodSellerAmount()
    {
        $user = Session::get('sid');
        $data = CodSellerAmount::where('userid', $user)->orderBy('id', 'desc')->get();
        $amount = $data->first();
        return view('seller.CodSellerAmount', compact('data', 'amount'));
    }

    public function addWalletAmount(Request $request)
    {
        $user = Session::get('sid');
        $status = $request->input('status', 'success');

        // Don't credit wallet unless success
        if ($status === 'success') {

            dd($status);

            $data = Wallet::where('userid', $user)->orderBy('id', 'desc')->first();
            $total = $data ? $data->total + $request->amount : $request->amount;

            $branch = Branch::where('id', $user)->first();
            $mobile = $branch ? $branch->phoneno : '0000000000';

            $wlt = new Wallet();
            $wlt->userid = $user;
            $wlt->c_amount = $request->amount;
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->total = $total;
            $wlt->msg = 'credit';
            $wlt->txn_id = $request->razorpay_payment_id ?? null;
            $wlt->status = 'success'; // Add this column if not present
            $wlt->save();

            return response()->json([
                'success' => true,
                'message' => 'Amount added successfully!',
            ]);
        } else {
            $wlt = new Wallet();
            $wlt->userid = $user;
            $wlt->c_amount = $request->amount;
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->total = Wallet::where('userid', $user)->orderBy('id', 'desc')->value('total') ?? 0;
            $wlt->msg = 'credit';
            $wlt->txn_id = $request->razorpay_payment_id ?? null;
            $wlt->status = $status; // 'failed' or 'cancelled'
            $wlt->save();

            return response()->json([
                'success' => false,
                'message' => "Payment $status"
            ]);
        }
    }
    public function sellerLogin(Request $request)
    {
        $user = Branch::where('email', $request->email)->where('type', $request->type)->first();
        if ($user && $user->password != $request->pwd) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
            ]);
        } else {
            Session::put('sid', $user->id);
            return response()->json([
                'success' => true,
                'message' => 'Welcome to your Dashboard!'
            ]);
        }
    }

    // by saurabh 29 may 
    // public function sellerDashboard()
    // {
    //     $id = Session::get('sid');
    //     $branch = Branch::find($id);
    //     // toDayOrder details
    //     $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
    //     $ordersQuery = Order::where('datetime', 'like', $dateTiem . '%')
    //         ->where('seller_id', $id);
    //     $toDayOrder = $ordersQuery->count();
    //     // Clone the base query to avoid modification issues
    //     $toDayPendingOrder = (clone $ordersQuery)->where('order_status', 'Booked')->count();
    //     $toDayOrderPicUp = (clone $ordersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
    //     $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
    //     $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();
    //     // dd($toDayOrder, $toDayPendingOrder, $toDayCompleteOrder, $toDayCancelledOrder);


    //     // totalOrder details
    //     $totalOrder = Order::where('seller_id', $id)->count();
    //     $totalPendingOrder = Order::where('seller_id', $id)->where('order_status', 'Booked')->count();
    //     $totalOrderPicUp = Order::where('seller_id', $id)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
    //     $totalCompleteOrder = Order::where('seller_id', $id)->where('order_status', 'Delivered')->count();
    //     $totalCanceledOrder = Order::where('seller_id', $id)->where('order_status', 'Cancelled')->count();

    //     return view('seller.dashboard', compact('branch', 'toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'totalOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCanceledOrder'));
    // }

    // by saurabh 29 may 
    // public function orderDetails($action)
    // {
    //     $id = Session::get('sid');
    //     if ($action == 'toDayOrder' || $action == 'toDayPendingOrder' || $action == 'toDayOrderPicUp' || $action == 'toDayCompleteOrder' || $action == 'toDayCancelledOrder') {
    //         $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
    //         $ordersQuery = Order::where('datetime', 'like', $dateTiem . '%')
    //             ->where('seller_id', $id);
    //         if ($action == 'toDayOrder') {
    //             $data = $ordersQuery->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'toDayPendingOrder') {
    //             $data = $ordersQuery->where('order_status', 'Booked')->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'toDayOrderPicUp') {
    //             $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'toDayCompleteOrder') {
    //             $data = $ordersQuery->where('order_status', 'Delivered')->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'toDayCancelledOrder') {
    //             $data = $ordersQuery->where('order_status', 'Cancelled')->orderBy('id', 'desc')->get();
    //         } else {
    //             $data = $ordersQuery->orderBy('id', 'desc')->get();
    //         }
    //     } elseif ($action == 'totalOrder' || $action == 'totalPendingOrder' || $action == 'totalOrderPicUp' || $action == 'totalCompleteOrder' || $action == 'totalCancelledOrder') {
    //         $ordersQuery = Order::where('seller_id', $id);
    //         if ($action == 'totalOrder') {
    //             $data = $ordersQuery->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'totalPendingOrder') {
    //             $data = $ordersQuery->where('order_status', 'Booked')->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'totalOrderPicUp') {
    //             $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'totalCompleteOrder') {
    //             $data = $ordersQuery->where('order_status', 'Delivered')->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'totalCancelledOrder') {
    //             $data = $ordersQuery->where('order_status', 'Cancelled')->orderBy('id', 'desc')->get();
    //         } else {
    //             $data = $ordersQuery->orderBy('id', 'desc')->orderBy('id', 'desc')->get();
    //         }
    //     } else {
    //         $data = Order::where('seller_id', $id)->get();
    //     }
    //     return view('seller.orderDetails', compact('data'));
    // }


    // by Khushnasib
    public function sellerDashboard()
    {
        $id = Session::get('sid');
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
        // dd($toDayOrder, $toDayPendingOrder, $toDayCompleteOrder, $toDayCancelledOrder);


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


        return view('seller.dashboard', compact('branch', 'toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'totalOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCanceledOrder', 'branchcodamount'));
    }


    // by Khushnasib
    public function orderDetails($action)
    {
        $id = Session::get('sid');
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
                $data = $ordersQuery->orderBy('id', 'desc')->get();
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
        return view('seller.orderDetails', compact('data'));
    }





    public function sellerLogout(Request $request)
    {
        $request->session()->forget('sid');
        return redirect('/SellerPanel')->with([
            'success' => true,
            'message' => 'You have successful LogOut!'
        ]);
    }

    public function setting()
    {
        $user = Session::get('sid');
        $data = Branch::find($user);
        return view('seller.setting', compact('data'));
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
        return view('seller.addDeliveryBoy');
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
        $dboy->userid = Session::get('sid');
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
        $user = Session::get('sid');
        $data = DlyBoy::where('userid', $user)
            ->orderBy('id', 'desc')
            ->get();
        return view('seller.allDeliveryBoy', compact('data'));
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

    public function sellerUpdateBoySt(Request $request)
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



    public function addDeliveryOrder()
    {
        $userId = Session::get('sid');
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
        return view('seller.addDeliveryOrder', compact('stdOrder', 'expOrder', 'seOrder'));
    }

    public function addPickupOrder()
    {
        $userId = Session::get('sid');
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
        return view('seller.addPickupOrder', compact('stdOrder', 'expOrder', 'seOrder'));
    }

    // public function addOrderParcel(Request $request)
    // {
    //     $userId = Session::get('sid');
    //     $branchDetails = Branch::where('id', $userId)->first();
    //     // dd($branchDetails);
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
    //         $order->receiver_name = $request->receiver_name;
    //         $order->receiver_cnumber = $request->receiver_number;
    //         $order->receiver_email = $request->receiver_email;
    //         $order->receiver_add = $request->receiver_address;
    //         $order->receiver_pincode = $request->receiverPincode;
    //         $order->sender_pincode = $branchDetails->pincode;
    //         $order->service_type = $request->service_type;
    //         $order->service_title = $request->service_title;
    //         $order->service_price = $request->service_price;
    //         $order->order_id = $new_order_id;

    //         $order->seller_id = $userId;

    //         // $order->price = $request->price;
    //         $order->price = trim(str_replace('₹', '', $request->price));
    //         $order->payment_mode = $request->payment_methods;
    //         $order->codAmount = $request->codAmount;
    //         $order->insurance = $request->insurance;
    //         $order->order_status = 'Booked';
    //         $order->parcel_type = $request->parcel_type;
    //         $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //         $order->created_at = $this->date;
    //         $order->updated_at = $this->date;

    //         // GET THE DELIVERY BOY
    //         $searchPincode = $request->receiverPincode ?? $branchDetails->pincode;
    //         $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
    //             // Check if the pincode is found in the comma-separated list
    //             $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
    //                 ->orWhere('pincode', 'LIKE', '%' . $searchPincode . '%');
    //         })->first();

    //         // Assign delivery boy to order if found
    //         if ($deliveryBoy) {
    //             $order->assign_to = $deliveryBoy->id;
    //             $order->assign_by = $userId;
    //         }

    //         $order->save();

    //         // Create order history entry
    //         $order_history = new OrderHistory();
    //         $order_history->tracking_id = $new_order_id;
    //         $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //         $order_history->status = 'Booked';
    //         $order_history->order_id = $order->id; // Link to the order
    //         $order_history->save();

    //         $status = true;
    //         $msg = 'Order create successfully!';
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

    //     // Return for non-AJAX requests
    //     return redirect()->back()->with('message', $msg);
    // }

    // public function addOrderParcel(Request $request)
    // {
    //     // Validate request inputs
    //     $request->validate([
    //         'parcel_type' => 'required|in:delivery,Pickup,Direct',
    //         'receiverPincode' => 'required',
    //         'receiver_name' => 'required',
    //         'receiver_number' => 'required',
    //         'receiver_email' => 'required|email',
    //         'receiver_address' => 'required',
    //         'price' => 'required|numeric|min:0',
    //     ]);

    //     $userId = Session::get('sid');
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

    //         // Set sender_pincode and receiver_pincode based on parcel_type
    //         if ($request->parcel_type === 'delivery') {
    //             $order->sender_pincode = $branchDetails->pincode;
    //             $order->sender_name = $branchDetails->fullname;
    //             $order->sender_number = $branchDetails->phoneno;
    //             $order->sender_email = $branchDetails->email;
    //             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
    //             $order->receiver_name = $request->receiver_name;
    //             $order->receiver_cnumber = $request->receiver_number;
    //             $order->receiver_email = $request->receiver_email;
    //             $order->receiver_add = $request->receiver_address;
    //         } elseif ($request->parcel_type === 'Pickup') {
    //             $order->sender_pincode = $request->receiverPincode;
    //             $order->sender_name = $request->receiver_name;
    //             $order->sender_number = $request->receiver_number;
    //             $order->sender_email = $request->receiver_email;
    //             $order->sender_address = $request->receiver_address;

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
    //             $order->receiver_email = $request->receiver_email;
    //             $order->receiver_add = $request->receiver_address;
    //         }

    //         $order->service_type = $request->service_type;
    //         $order->service_title = $request->service_title;
    //         $order->service_price = $request->service_price;
    //         $order->order_id = $new_order_id;
    //         $order->seller_id = $userId;
    //         $order->price = trim(str_replace('₹', '', $request->price));
    //         $order->payment_mode = $request->payment_methods;
    //         $order->codAmount = $request->codAmount;
    //         $order->insurance = $request->insurance;
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
    //             // Set assign_by based on parcel_type
    //             if ($request->parcel_type === 'Pickup') {
    //                 // Find Branch with type = 'Delivery' and matching pincode
    //                 $deliveryBranch = Branch::where('type', 'Delivery')
    //                     ->whereRaw("FIND_IN_SET(?, pincode)", [$request->receiverPincode])
    //                     ->first();
    //                 if ($deliveryBranch) {
    //                     $order->assign_by = $deliveryBranch->id;
    //                 } else {
    //                     $order->assign_by = $userId; // Fallback to userId if no matching branch found
    //                 }
    //             } else {
    //                 $order->assign_by = $userId;
    //             }
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

    // public function addOrderParcel(Request $request)
    // {
    //     // Validate request inputs
    //     $request->validate([
    //         'parcel_type' => 'required|in:delivery,Pickup,Direct',
    //         'receiverPincode' => 'required',
    //         'receiver_name' => 'required',
    //         'receiver_number' => 'required',
    //         'receiver_email' => 'required|email',
    //         'receiver_address' => 'required',
    //         'price' => 'required|numeric|min:0',
    //     ]);

    //     $userId = Session::get('sid');
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


    //         // Set sender_pincode and receiver_pincode based on parcel_type
    //         if ($request->parcel_type === 'delivery') {
    //             $order->sender_pincode = $branchDetails->pincode;
    //             $order->sender_name = $branchDetails->fullname;
    //             $order->sender_number = $branchDetails->phoneno;
    //             $order->sender_email = $branchDetails->email;
    //             $order->sender_address = $branchDetails->fulladdress;

    //             $order->receiver_pincode = $request->receiverPincode;
    //             $order->receiver_name = $request->receiver_name;
    //             $order->receiver_cnumber = $request->receiver_number;
    //             $order->receiver_email = $request->receiver_email;
    //             $order->receiver_add = $request->receiver_address;
    //         } elseif ($request->parcel_type === 'Pickup') {
    //             $order->sender_pincode = $request->receiverPincode;
    //             $order->sender_name = $request->receiver_name;
    //             $order->sender_number = $request->receiver_number;
    //             $order->sender_email = $request->receiver_email;
    //             $order->sender_address = $request->receiver_address;

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
    //             $order->receiver_email = $request->receiver_email;
    //             $order->receiver_add = $request->receiver_address;
    //         }

    //         // Find branch for seller_id based on sender_pincode
    //         $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
    //             ->where('type', 'Delivery')
    //             ->first();

    //         $order->service_type = $request->service_type;
    //         $order->service_title = $request->service_title;
    //         $order->service_price = $request->service_price;
    //         $order->order_id = $new_order_id;
    //         $order->seller_id = $branch->id ?? null; 
    //         $order->assign_by = $branch->id ?? null; 
    //         $order->seller_primary_id = $userId; // seller primary id 
    //         $order->price = trim(str_replace('₹', '', $request->price));
    //         $order->payment_mode = $request->payment_methods;
    //         $order->codAmount = $request->codAmount;
    //         $order->insurance = $request->insurance;
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
    //             // Set assign_by based on parcel_type
    //             // if ($request->parcel_type === 'Pickup') {
    //             //     // Find Branch with type = 'Delivery' and matching pincode
    //             //     $deliveryBranch = Branch::where('type', 'Delivery')
    //             //         ->whereRaw("FIND_IN_SET(?, pincode)", [$request->receiverPincode])
    //             //         ->first();
    //             //     // if ($deliveryBranch) {
    //             //     //     $order->assign_by = $deliveryBranch->id;
    //             //     // } else {
    //             //     //     $order->assign_by = $userId; // Fallback to userId if no matching branch found
    //             //     // }
    //             // } else {
    //             //     // $order->assign_by = $userId;
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


    // with order id sequence change 18 june 
//     public function addOrderParcel(Request $request)
//     {
//         $request->validate([
//             'parcel_type' => 'required|in:delivery,Pickup,Direct',
//             'receiverPincode' => 'required',
//             'receiver_name' => 'required',
//             'receiver_number' => 'required',
//             'receiver_email' => 'required|email',
//             'receiver_address' => 'required',
//             'price' => 'required|numeric|min:0',
//         ]);

    //         $userId = Session::get('sid');
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

    //             // Order ID will be assigned after save
//             $order->order_id = null;

    //             if ($request->parcel_type === 'delivery') {
//                 $order->sender_pincode = $branchDetails->pincode;
//                 $order->sender_name = $branchDetails->fullname;
//                 $order->sender_number = $branchDetails->phoneno;
//                 $order->sender_email = $branchDetails->email;
//                 $order->sender_address = $branchDetails->fulladdress;

    //                 $order->receiver_pincode = $request->receiverPincode;
//                 $order->receiver_name = $request->receiver_name;
//                 $order->receiver_cnumber = $request->receiver_number;
//                 $order->receiver_email = $request->receiver_email;
//                 $order->receiver_add = $request->receiver_address;
//             } elseif ($request->parcel_type === 'Pickup') {
//                 $order->sender_pincode = $request->receiverPincode;
//                 $order->sender_name = $request->receiver_name;
//                 $order->sender_number = $request->receiver_number;
//                 $order->sender_email = $request->receiver_email;
//                 $order->sender_address = $request->receiver_address;

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
//                 $order->receiver_email = $request->receiver_email;
//                 $order->receiver_add = $request->receiver_address;
//             }

    //             $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
//                 ->where('type', 'Delivery')
//                 ->first();

    //             $order->service_type = $request->service_type;
//             $order->service_title = $request->service_title;
//             $order->service_price = $request->service_price;
//             // order_id will be set after save
//             $order->seller_id = $branch->id ?? null;
//             $order->assign_by = $branch->id ?? null;
//             $order->seller_primary_id = $userId;
//             $order->price = trim(str_replace('₹', '', $request->price));
//             $order->payment_mode = $request->payment_methods;
//             $order->codAmount = $request->codAmount;
//             $order->insurance = $request->insurance;
//             $order->order_status = 'Booked';
//             $order->parcel_type = $request->parcel_type;
//             $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//             $order->created_at = $this->date;
//             $order->updated_at = $this->date;

    //             $searchPincode = ($request->parcel_type === 'Pickup') ? $order->sender_pincode : $branchDetails->pincode;
//             $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
//                 $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
//                     ->orWhere('pincode', 'LIKE', '%' . $searchPincode . '%');
//             })->first();

    //             if ($request->parcel_type === 'delivery' && !$deliveryBoy) {
//                 throw new \Exception('No delivery boy available for branch pincode');
//             }

    //             if ($deliveryBoy) {
//                 $order->assign_to = $deliveryBoy->id;
//             }

    //             $wlt = new Wallet();
//             $wlt->userid = $userId;
//             $wlt->d_amount = $request->price;
//             $wlt->total = $wallet->total - $request->price;
//             $wlt->msg = 'debit';
//             $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//             $wlt->save();

    //             $order->save(); // Save order to get ID

    //             // 👇 Add fixed prefix with last insert ID
//             $fixedPrefix = 'DP1516800';
//             $finalOrderId = $fixedPrefix . $order->id;

    //             $order->order_id = $finalOrderId;
//             $order->save(); // Update order_id

    //             // ✅ Create order history with updated order_id as tracking_id
//             $order_history = new OrderHistory();
//             $order_history->tracking_id = $finalOrderId;
//             $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
//             $order_history->status = 'Booked';
//             $order_history->order_id = $order->id;
//             $order_history->save();

    //             $mailData = [
//                 'title' => 'Order Booking Confirmation',
//                 'order_id' => $finalOrderId,
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

    //             DB::commit();

    //             register_shutdown_function(function () use ($order, $mailData, $finalOrderId) {
//                 try {
//                     $recipients = array_filter([$order->sender_email, $order->receiver_email]);
//                     if (!empty($recipients)) {
//                         Mail::to($recipients)->queue(new SellerBookingConfirmation($mailData));
//                         \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$finalOrderId}");
//                     } else {
//                         \Log::warning("No valid email addresses provided for order ID: {$finalOrderId}");
//                     }
//                 } catch (\Exception $e) {
//                     \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
//                 }
//             });

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
            'receiver_email' => 'required|email',
            'receiver_address' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $userId = Session::get('sid');
        $branchDetails = Branch::find($userId);

        if (!$branchDetails || empty($branchDetails->pincode)) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Invalid branch or pincode data'], 400)
                : redirect()->back()->with('message', 'Invalid branch or pincode data');
        }

        $wallet = Wallet::where('userid', $userId)->latest()->first();

        if (!$wallet || $wallet->total < $request->price) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Insufficient Balance'], 400)
                : redirect()->back()->with('message', 'Insufficient Balance');
        }

        DB::beginTransaction();

        try {
            $order = new Order();
            $order->order_id = null;

            if ($request->parcel_type === 'delivery') {
                $order->sender_pincode = $branchDetails->pincode;
                $order->sender_name = $branchDetails->fullname;
                $order->sender_number = $branchDetails->phoneno;
                $order->sender_email = $branchDetails->email;
                $order->sender_address = $branchDetails->fulladdress;

                $order->receiver_pincode = $request->receiverPincode;
                $order->receiver_name = $request->receiver_name;
                $order->receiver_cnumber = $request->receiver_number;
                $order->receiver_email = $request->receiver_email;
                $order->receiver_add = $request->receiver_address;
            } elseif ($request->parcel_type === 'Pickup') {
                $order->sender_pincode = $request->receiverPincode;
                $order->sender_name = $request->receiver_name;
                $order->sender_number = $request->receiver_number;
                $order->sender_email = $request->receiver_email;
                $order->sender_address = $request->receiver_address;

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
                $order->receiver_email = $request->receiver_email;
                $order->receiver_add = $request->receiver_address;
            }

            $branch = Branch::where('pincode', 'LIKE', "%{$order->sender_pincode}%")
                ->where('type', 'Delivery')
                ->first();

            $order->service_type = $request->service_type;
            $order->service_title = $request->service_title;
            $order->service_price = $request->service_price;
            $order->seller_id = $branch->id ?? null;
            $order->assign_by = $branch->id ?? null;
            $order->seller_primary_id = $userId;
            $order->price = trim(str_replace('₹', '', $request->price));
            $order->payment_mode = $request->payment_methods;
            $order->codAmount = $request->codAmount;
            $order->insurance = $request->insurance;
            $order->order_status = 'Booked';
            $order->parcel_type = $request->parcel_type;
            $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $order->created_at = now();
            $order->updated_at = now();

            $searchPincode = ($request->parcel_type === 'Pickup') ? $order->sender_pincode : $branchDetails->pincode;
            $deliveryBoy = DlyBoy::where(function ($query) use ($searchPincode) {
                $query->whereRaw("FIND_IN_SET(?, pincode)", [$searchPincode])
                    ->orWhere('pincode', 'LIKE', "%{$searchPincode}%");
            })->first();

            if ($request->parcel_type === 'delivery' && !$deliveryBoy) {
                throw new \Exception('No delivery boy available for branch pincode');
            }

            if ($deliveryBoy) {
                $order->assign_to = $deliveryBoy->id;
            }

            // Wallet update
            $wlt = new Wallet();
            $wlt->userid = $userId;
            $wlt->d_amount = $request->price;
            $wlt->total = $wallet->total - $request->price;
            $wlt->msg = 'debit';
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->save();

            $order->save();

            $fixedPrefix = 'DP1516800';
            $finalOrderId = $fixedPrefix . $order->id;
            $order->order_id = $finalOrderId;
            $order->save();

            $order_history = new OrderHistory();
            $order_history->tracking_id = $finalOrderId;
            $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $order_history->status = 'Booked';
            $order_history->order_id = $order->id;
            $order_history->save();

            $mailData = [
                'title' => 'Order Booking Confirmation',
                'order_id' => $finalOrderId,
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

            // ✅ Register email send logic on shutdown
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
                        \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
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
        $user = Session::get('sid');
        $data = Order::where('seller_id', $user)->orderBy('id', 'desc')->get();

        return view('seller.allOrders', compact('data'));
    }



    public function sellerAssignGet(Request $request)
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

    public function sellerAssignAdd(Request $request)
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

    public function sellerStatusGet(Request $request)
    {
        $order = Order::find($request->id);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'orderId' => $order->order_id
            ]);
        }
    }

    // public function sellerInvoice($id)
    // {
    //     $data = Order::where('order_id', $id)->first();
    //     return view('seller.invoice', compact('data'));
    // }

    public function sellerInvoice($id)
    {
        // Fetch the order data
        $data = Order::where('order_id', $id)->firstOrFail();
        $branch = Branch::where('id', $data->seller_primary_id)->first();
        $data->gstno = $branch->gst_panno ?? null;



        return view('seller.invoice', compact('data'));
    }

    public function MonthlySellerInvoice()
    {
        // Get the logged-in user's branch ID from session
        $userId = Session::get('sid');

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


        return view('seller.montlyinvoice', compact('data'));
    }


    public function sellerLabel($id)
    {
        $data = Order::where('order_id', $id)->first();
        if ($data->payment_mode == 'COD') {
            return view('seller.label', compact('data'));
        } else {
            return view('seller.label1', compact('data'));
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

    public function sellerEditGet(Request $request)
    {
        $order = Order::find($request->id);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        }
    }

    public function sellerEditUpdate(Request $request)
    {
        $order = Order::find($request->orderId);
        $order->receiver_name = $request->receiver_name;
        $order->receiver_cnumber = $request->receiver_cnumber;
        $order->receiver_email = $request->receiver_email;
        $order->receiver_add = $request->receiver_add;
        $order->save();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order Details update successfully!',
            ]);
        }
    }

    // public function cancelledOrder($id)
    // {
    //     $order = Order::where('id', $id)->first();
    //     $order->order_status = 'Cancelled';
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
        if ($order->save()) {
            $amount = $order->price;
            $userId = $order->seller_primary_id;

            $wallet = Wallet::where('userid', $userId)
                ->orderBy('id', 'desc')
                ->first();
            $total = $wallet ? ($wallet->total + $amount) : $amount;

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


    //  public function allCodHistory()
    // {
    // $userId = Session::get('sid');
    // $data = Order::where('seller_primary_id', $userId)->where('order_status' , 'Delivered')->where('payment_mode','COD')->orderBy('id', 'desc')->get();
    // return view('seller.allCodHistory', compact('data'));
    // }


    public function allCodHistory(Request $request)
    {
        $userId = Session::get('sid');
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
        return view('seller.allCodHistory', compact('data'));
    }




    // public function dateCodHistory(Request $request)
    // {
    //     $userId = Session::get('sid');
    //     $orders = Order::where('seller_id', $userId)->get();
    //     $dateRange = $request->date;
    //     list($startDate, $endDate) = explode(' - ', $dateRange);
    //     $startDate = date('d-m-Y 00:00:00', strtotime($startDate));
    //     $endDate = date('d-m-Y 23:59:59', strtotime($endDate));
    //     $ordersQuery = COD::whereBetween('datetime', [$startDate, $endDate])
    //         ->whereIn('order_id', $orders->pluck('id'))
    //         ->orderBy('id', 'desc');

    //     $data = $ordersQuery->get();
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'html' => view('seller.inc.allCodHistoryData', compact('data'))->render(),
    //         ]);
    //     }
    // }

    public function dateCodHistory(Request $request)
    {
        $userId = Session::get('sid');

        // Get and parse the date range from the request
        $dateRange = $request->date;
        list($startDate, $endDate) = explode(' - ', $dateRange);

        // Convert dates to d-m-Y format for string comparison
        $startDate = date('d-m-Y', strtotime($startDate)); // e.g., 29-05-2025
        $endDate = date('d-m-Y', strtotime($endDate));     // e.g., 31-05-2025

        // Fetch orders for the seller within the date range
        $data = Order::where('seller_id', $userId)
            ->whereNotNull('codAmount') // Filter COD orders
            ->whereRaw("STR_TO_DATE(datetime, '%d-%m-%Y | %h:%i:%s %p') BETWEEN ? AND ?", [
                "$startDate 00:00:00",
                "$endDate 23:59:59"
            ])
            ->orderBy('id', 'desc')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('seller.inc.allCodHistoryData', compact('data'))->render(),
            ]);
        }

        // Return view for non-AJAX requests
        return view('seller.allCodHistory', compact('data'));
    }

    // Khushnasib
    // public function orderCodHistory()
    // {
    //     $user = Session::get('sid');
    //     $orders = Order::where('seller_id', $user)->get();
    //     $dateTime = now('Asia/Kolkata')->format('d-m-Y');
    //     $todayOrdersQuery = COD::where('datetime', 'like', $dateTime . '%')->whereIn('order_id', $orders->pluck('id'));
    //     $totalOrdersQuery = COD::whereIn('order_id', $orders->pluck('id'))->get();

    //     $todayOrder = Order::whereIn('id', $todayOrdersQuery->pluck('order_id'))->get();
    //     $totalOrder = Order::whereIn('id', $totalOrdersQuery->pluck('order_id'))->get();

    //     $orders = $totalOrder->toArray();
    //     $total_price = $totalOrder->sum('price');
    //     $today_price = $todayOrder->sum('price');

    //     $branchDetails = Branch::find($user);
    //     $branchPinCode = $branchDetails->pincode;
    //     $branch = Branch::where(['pincode' => $branchPinCode, 'type' => 'Delivery'])->first();
    //     $OrderCod = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])->where('msg', 'like', 'debit%')->get();
    //     // Get total Order COD amount
    //     $OrderCodTotal = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])
    //         ->where('msg', 'like', 'debit%')
    //         ->sum('d_amount');
    //     // Get today's Order COD amount
    //     $OrderCodToDay = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])
    //         ->where('msg', 'like', 'debit%')
    //         ->where('datetime', 'like', $dateTime . '%')
    //         ->sum('d_amount');

    //     return view('seller.orderCodHistory', compact('total_price', 'today_price', 'OrderCod', 'OrderCodTotal', 'OrderCodToDay'));
    // }


    public function orderCodHistory()
    {
        $user = Session::get('sid');
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
        $branch = Branch::where(['pincode' => $branchPinCode, 'type' => 'Delivery'])->first();

        // Initialize default values
        $OrderCod = collect(); // Empty collection if no branch is found
        $OrderCodTotal = 0;
        $OrderCodToDay = 0;

        // Check if $branch exists before proceeding
        if ($branch) {
            $OrderCod = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])
                ->where('msg', 'like', 'debit%')
                ->get();

            $OrderCodTotal = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])
                ->where('msg', 'like', 'debit%')
                ->sum('d_amount');

            $OrderCodToDay = OrderCod::where(['userid' => $branch->id, 'debit_by' => $user])
                ->where('msg', 'like', 'debit%')
                ->where('datetime', 'like', $dateTime . '%')
                ->sum('d_amount');
        }

        return view('seller.orderCodHistory', compact('total_price', 'today_price', 'OrderCod', 'OrderCodTotal', 'OrderCodToDay'));
    }




    public function orderCodAmount(Request $request)
    {
        $user = Session::get('sid');
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
            $wlt->msg = 'debit/sb/' . $branchDetails->fullname;
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
