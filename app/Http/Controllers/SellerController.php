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

class SellerController extends Controller
{

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

    public function sellerDashboard()
    {
        $id = Session::get('sid');
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
        // dd($toDayOrder, $toDayPendingOrder, $toDayCompleteOrder, $toDayCancelledOrder);


        // totalOrder details
        $totalOrder = Order::where('seller_id', $id)->count();
        $totalPendingOrder = Order::where('seller_id', $id)->where('order_status', 'Booked')->count();
        $totalOrderPicUp = Order::where('seller_id', $id)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        $totalCompleteOrder = Order::where('seller_id', $id)->where('order_status', 'Delivered')->count();
        $totalCanceledOrder = Order::where('seller_id', $id)->where('order_status', 'Cancelled')->count();

        return view('seller.dashboard', compact('branch', 'toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'totalOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCanceledOrder'));
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

    public function sellerWallet()
    {
        $user = Session::get('sid');
        $data = Wallet::where('userid', $user)->orderBy('id', 'desc')->get();
        $amount = $data->first();

        return view('seller.sellerWallet', compact('data', 'amount'));
    }

    public function addWalletAmount(Request $request)
    {
        $user = Session::get('sid');
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
                'html' => view('seller.inc.wallet', compact('data'))->render(),
            ]);
        }
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

    public function addOrderParcel(Request $request)
    {
        $userId = Session::get('sid');
        $branchDetails = Branch::where('id', $userId)->first();
        // dd($branchDetails);
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
            $order->receiver_name = $request->receiver_name;
            $order->receiver_cnumber = $request->receiver_number;
            $order->receiver_email = $request->receiver_email;
            $order->receiver_add = $request->receiver_address;
            $order->receiver_pincode = $request->receiverPincode;
            $order->sender_pincode = $branchDetails->pincode;
            $order->service_type = $request->service_type;
            $order->service_title = $request->service_title;
            $order->service_price = $request->service_price;
            $order->order_id = 'DL' . $this->generateRandomCode();

            $order->seller_id = $userId;

            $order->price = $request->price;
            $order->payment_mode = $request->payment_methods;
            $order->codAmount = $request->codAmount;
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
        $user = Session::get('sid');
        $data = Order::where('seller_id', $user)->orderBy('id', 'desc')->get();

        return view('seller.allOrders', compact('data'));
    }

    public function orderDetails($action)
    {
        $id = Session::get('sid');
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
        return view('seller.orderDetails', compact('data'));
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

    public function sellerInvoice($id)
    {
        $data = Order::where('order_id', $id)->first();
        return view('seller.invoice', compact('data'));
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
        return back();
    }

    public function allCodHistory()
    {
        $userId = Session::get('sid');
        $orders = Order::where('seller_id', $userId)->get();
        $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = COD::where('datetime', 'like', $dateTiem . '%')->whereIn('order_id', $orders->pluck('id'));
        $data = $ordersQuery->orderBy('id', 'desc')->get();
        return view('seller.allCodHistory', compact('data'));
    }

    public function dateCodHistory(Request $request)
    {
        $userId = Session::get('sid');
        $orders = Order::where('seller_id', $userId)->get();
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
                'html' => view('seller.inc.allCodHistoryData', compact('data'))->render(),
            ]);
        }
    }

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
        $branchPinCode =  $branchDetails->pincode;
        $branch = Branch::where(['pincode' => $branchPinCode, 'type' => 'Delivery'])->first();
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

        return view('seller.orderCodHistory', compact('total_price', 'today_price', 'OrderCod', 'OrderCodTotal', 'OrderCodToDay'));
    }

    public function orderCodAmount(Request $request)
    {
        $user = Session::get('sid');
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
