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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{
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
            ->where('seller_id', $id);
        $toDayOrder = $ordersQuery->count();
        // Clone the base query to avoid modification issues
        $toDayPendingOrder = (clone $ordersQuery)->where('order_status', 'Booked')->count();
        $toDayOrderPicUp = (clone $ordersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
        $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();


        // totalOrder details
        $totalOrder = Order::where('seller_id', $id)->count();
        $totalPendingOrder = Order::where('seller_id', $id)->where('order_status', 'Booked')->count();
        $totalOrderPicUp = Order::where('seller_id', $id)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        $totalCompleteOrder = Order::where('seller_id', $id)->where('order_status', 'Delivered')->count();
        $totalCanceledOrder = Order::where('seller_id', $id)->where('order_status', 'Cancelled')->count();

        return view('booking.dashboard', compact('branch', 'toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'totalOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCanceledOrder'));
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
        $data = Wallet::where('userid', $user)
            ->orderBy('id', 'desc')
            ->first();
        if ($data) {
            $total = $data->total + $request->amount;
        } else {
            $total = $request->amount;
        }

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

    public function addOrderParcel(Request $request)
    {
        $userId = Session::get('bid');
        $branchDetails = Branch::where('id', $userId)->first();
        $wallet = Wallet::where('userid', $userId)
            ->orderBy('id', 'desc')
            ->first();
        if ($wallet && $wallet->total >= $request->price) {
            $wlt = new Wallet();
            $wlt->userid = $userId;
            $wlt->d_amount = $request->price;
            $total = $wallet->total - $request->price;
            $wlt->total = $total;
            $wlt->msg = 'debit';
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->save();

            $order = new Order();
            $order->receiver_name = $request->receiver_name ?? null;
            $order->receiver_cnumber = $request->receiver_number ?? null;
            $order->receiver_email = $request->receiver_email ?? null;
            $order->receiver_add = $request->receiver_address ?? null;
            $order->sender_pincode = $branchDetails->pincode ?? null;
            $order->receiver_pincode = $request->receiverPincode ?? null;
            $order->service_type = $request->service_type ?? null;
            $order->service_title = $request->service_title ?? null;
            $order->service_price = $request->service_price ?? null;
            $order->order_id = 'DL' . $this->generateRandomCode();

            $order->seller_id = $userId;

            $order->price = $request->price;
            $order->payment_mode = $request->payment_methods;
            $order->insurance = $request->insurance;
            $order->order_status = 'Booked';
            $order->parcel_type = $request->parcel_type;
            $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $order->save();

            $status = true;
            $msg = 'Order create successfully!';
        } else {
            $status = false;
            $msg = 'Insufficient Balance';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => $status,
                'message' => $msg,
            ]);
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

    public function allCodHistory()
    {
        $userId = Session::get('bid');
        $orders = Order::where('seller_id', $userId)->get();
        $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = COD::where('datetime', 'like', $dateTiem . '%')->whereIn('order_id', $orders->pluck('id'));
        $data = $ordersQuery->orderBy('id', 'desc')->get();
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
                ->where('seller_id', $id);
            if ($action == 'toDayOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayPendingOrder') {
                $data = $ordersQuery->where('order_status', 'Booked')->get();
            } elseif ($action == 'toDayOrderPicUp') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->get();
            } elseif ($action == 'toDayCompleteOrder') {
                $data = $ordersQuery->where('order_status', 'Delivered')->get();
            } elseif ($action == 'toDayCancelledOrder') {
                $data = $ordersQuery->where('order_status', 'Cancelled')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } elseif ($action == 'totalOrder' || $action == 'totalPendingOrder' || $action == 'totalOrderPicUp' || $action == 'totalCompleteOrder' || $action == 'totalCancelledOrder') {
            $ordersQuery = Order::where('seller_id', $id);
            if ($action == 'totalOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalPendingOrder') {
                $data = $ordersQuery->where('order_status', 'Booked')->get();
            } elseif ($action == 'totalOrderPicUp') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->get();
            } elseif ($action == 'totalCompleteOrder') {
                $data = $ordersQuery->where('order_status', 'Delivered')->get();
            } elseif ($action == 'totalCancelledOrder') {
                $data = $ordersQuery->where('order_status', 'Cancelled')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } else {
            $data = Order::where('seller_id', $id)->get();
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

    public function bookingEditUpdate(Request $request)
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

    public function cancelledOrder($id)
    {
        $order = Order::where('id', $id)->first();
        $order->order_status = 'Cancelled';
        if ($order->save()) {
            $amount = $order->price;
            $userId = $order->seller_id;

            $wallet = Wallet::where('userid', $userId)
                ->orderBy('id', 'desc')
                ->first();
            $total = $wallet->total + $amount;

            $wlt = new Wallet();
            $wlt->userid = $userId;
            $wlt->c_amount = $amount;
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->total = $total;
            $wlt->msg = 'Order Cancelled';
            $wlt->save();
        }
        return redirect()->back();
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
        $branchPinCode =  $branchDetails->pincode;
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
        $branchPinCode =  $branchDetails->pincode;
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
