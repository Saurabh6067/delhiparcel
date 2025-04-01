<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\COD;
use App\Models\CodAmount;
use App\Models\DlyBoy;
use App\Models\Order;
use App\Models\WebOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DeliveryBoyController extends Controller
{
    public function deliveryLogin(Request $request)
    {
        $user = DlyBoy::where('email', $request->email)->first();
        if ($user && $user->password != $request->pwd) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
            ]);
        } else {
            Session::put('dlyId', $user->id);
            return response()->json([
                'success' => true,
                'message' => 'Welcome to your Dashboard!'
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('dlyId');
        return redirect('/DeliveryBoy')->with([
            'success' => true,
            'message' => 'You have successful LogOut!'
        ]);
    }

    public function setting()
    {
        $user = Session::get('dlyId');
        $data = DlyBoy::find($user);
        return view('deliveryBoy.setting', compact('data'));
    }

    public function updateProfile(Request $request)
    {
        $user = DlyBoy::find($request->adminId);

        $user->name = $request->adminName;
        $user->email = $request->adminEmail;
        $user->phone = $request->adminMobile;
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
        $user = DlyBoy::find($request->id);
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

    public function dashboard()
    {
        $id = Session::get('dlyId');
        $delivery = DlyBoy::find($id);
        $pinCodes = explode(',', $delivery->pincode);

        // toDayOrder details
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        // $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')
        //     ->where(function ($query) use ($pinCodes) {
        //         $query->whereIn('receiver_pincode', $pinCodes)
        //             ->orWhere('sender_pincode', $pinCodes);
        //     });
        // $ordersQuery1 = Order::where('datetime', 'like', $dateTime . '%')
        //     ->where(function ($query) use ($pinCodes) {
        //         $query->whereIn('sender_pincode', $pinCodes);
        //     });
        // dd($ordersQuery1->get()->toArray());
        // $toDayOrder = $ordersQuery1->where(['assign_to' => $id])->orWhere('parcel_type', ['delivery', 'Pickup', 'Direct'])->count();
        // $toDayCompleteOrder = (clone $ordersQuery1)->where(['order_status' => 'Delivered', 'assign_to' => $id])->count();
        $toDayOrder = Order::where('datetime', 'like', $dateTime . '%')->where('assign_to', $id)->count();
        $toDayCompleteOrder = Order::where('datetime', 'like', $dateTime . '%')->where(['order_status' => 'Delivered', 'assign_to' => $id])->count();

        // totalOrder details
        $totalOrder = Order::count();
        $PendingOrder = Order::where(['order_status' => 'Booked', 'assign_to' => $id])->count();
        $totalCompleteOrder = Order::where(['order_status' => 'Delivered', 'assign_to' => $id])->count();
        $PendingDeliveryOrder = Order::whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch'])->where('assign_to', $id)->count();

        $PendingSuperExpressOrder = Order::where('service_type', 'SuperExpress')->count();
        $DirectOrders = WebOrder::where('assign_to', $id)->count();
        $PendingPinCodeOrders = Order::where('sender_order_pin_by', $id)->whereIn('sender_order_status', ['Pending', 'Processing'])->count();
        $CompleteOtherPinCodeOrders = Order::where(['sender_order_pin_by' => $id, 'sender_order_status' => 'Delivered'])->count();


        return view('deliveryBoy.dashboard', compact('delivery', 'toDayOrder', 'totalOrder', 'toDayCompleteOrder', 'PendingOrder', 'totalCompleteOrder', 'PendingDeliveryOrder', 'PendingSuperExpressOrder', 'DirectOrders', 'PendingPinCodeOrders', 'CompleteOtherPinCodeOrders'));
    }

    public function codHistory()
    {
        $id = Session::get('dlyId');
        $dateToday = now('Asia/Kolkata')->format('d-m-Y'); // Correct date format for LIKE query
        $monthStart = now('Asia/Kolkata')->startOfMonth()->format('d-m-Y'); // Start of the current month

        // Get today's data
        $todayOrdersQuery = COD::where('datetime', 'like', $dateToday . '%')
            ->where('delivery_boy_id', $id)
            ->orderBy('id', 'desc');

        $todayOrders = $todayOrdersQuery->get();

        // Get this month's data
        $monthOrders = COD::whereBetween('datetime', [$monthStart, $dateToday . ' 23:59:59'])
            ->where('delivery_boy_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('deliveryBoy.order-history', compact('todayOrders', 'monthOrders'));
    }

    public function monthOrderHistory(Request $request)
    {
        $id = Session::get('dlyId');
        $monthYear = $request->monthYear;
        $month = date('m', strtotime($monthYear));
        $year = date('Y', strtotime($monthYear));

        $searchDate = sprintf('%02d-%d', $month, $year); // Format as "MM-YYYY"

        $monthOrders = COD::where('datetime', 'LIKE', "%$searchDate%")
            ->where('delivery_boy_id', $id)
            ->orderBy('id', 'desc')
            ->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('deliveryBoy.inc.monthOrderHistory', compact('monthOrders'))->render(),
            ]);
        }
    }

    public function deliveryBoyWallet()
    {
        $id = Session::get('dlyId');
        $dateToday = now('Asia/Kolkata')->format('d-m-Y'); // Correct date format
        $monthStart = now('Asia/Kolkata')->startOfMonth()->format('d-m-Y'); // Start of the month

        $codQuery = CodAmount::where('delivery_boy_id', $id);
        $codHistory = $codQuery->orderBy('id', 'desc')->get();

        // Today's COD Orders
        $todayCodQuery = COD::where('datetime', 'like', $dateToday . '%')
            ->where('delivery_boy_id', $id)
            ->get();
        $orderId = $todayCodQuery->pluck('order_id')->toArray();
        $todayCod = Order::whereIn('id', $orderId)->sum('price');
        if ($todayCod !== 0) {
            $todaySubmit = (clone $codQuery)
                ->where('datetime', 'like', $dateToday . '%')
                ->sum('amount');
            $todayPending = $todayCod - $todaySubmit;
        } else {
            $todaySubmit = 0;
            $todayPending = 0;
        }

        // Total COD Orders
        $totalCodQuery = COD::where('delivery_boy_id', $id)->get();
        $orderId = $totalCodQuery->pluck('order_id')->toArray();
        $totalCod = Order::whereIn('id', $orderId)->sum('price');
        $totalSubmit = $codHistory->sum('amount');
        $totalPending = $totalCod - $totalSubmit;


        return view('deliveryBoy.cod-history', compact('todayCod', 'todaySubmit', 'todayPending', 'totalCod', 'totalSubmit', 'totalPending', 'codHistory'));
    }

    public function orderDetails($action)
    {
        $id = Session::get('dlyId');
        $delivery = DlyBoy::find($id);
        if ($action == 'toDayOrder' || $action == 'toDayCompleteOrder') {
            $dateTime = now('Asia/Kolkata')->format('d-m-Y');
            // $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')
            //     ->where('receiver_pincode', $delivery->pincode);
            $ordersQuery = Order::where('datetime', 'like', $dateTime . '%');
            if ($action == 'toDayOrder') {
                $data = $ordersQuery->where('assign_to', $id)->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayCompleteOrder') {
                $data = $ordersQuery->where(['order_status' => 'Delivered', 'assign_to' => $id])->orderBy('id', 'desc')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } elseif ($action == 'totalOrder' || $action == 'PendingOrder' || $action == 'PendingSuperExpressOrder' || $action == 'PendingDeliveryOrder' || $action == 'DirectOrders' || $action == 'totalCompleteOrder') {
            // $ordersQuery = Order::where('receiver_pincode', $delivery->pincode);
            $ordersQuery = new Order;
            if ($action == 'totalOrder') {
                $data = $ordersQuery->where('assign_to', $id)->orderBy('id', 'desc')->get();
            } elseif ($action == 'PendingOrder') {
                $data = $ordersQuery->where(['order_status' => 'Booked', 'assign_to' => $id])->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalCompleteOrder') {
                $data = $ordersQuery->where(['order_status' => 'Delivered', 'assign_to' => $id])->orderBy('id', 'desc')->get();
            } elseif ($action == 'PendingSuperExpressOrder') {
                $data = $ordersQuery->where('service_type', 'SuperExpress')->where('assign_to', $id)->orderBy('id', 'desc')->get();
            } elseif ($action == 'PendingDeliveryOrder') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch'])->where('assign_to', $id)->orderBy('id', 'desc')->get();
            } elseif ($action == 'DirectOrders') {
                $data = WebOrder::where('assign_to', $id)->orderBy('id', 'desc')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } else {
            $data = Order::where('receiver_pincode', $delivery->pincode)->get();
        }
        return view('deliveryBoy.orderDetails', compact('data'));
    }

    public function deliveryStatusGet(Request $request)
    {
        $order = Order::find($request->id);
        $receiverPinCode = $order->receiver_pincode;
        $id = Session::get('dlyId');
        $delivery = DlyBoy::find($id);
        dd($delivery->toArray());

        dd();
        if ($request->action == 'DirectOrders') {
            $order = WebOrder::where('id', $request->id)->first();
        } else {
            $order = Order::find($request->id);
            // dd($order->toArray());
            if ($order->receiver_pincode == $order->sender_pincode) {
                $orderStatus = "<option value='Booked'>Booked</option> <option value='Item Picked Up'>Item Picked Up</option><option value='Item Not Picked Up'>Item Not Picked Up</option> <option value='Returned'>Returned</option> <option value='In Transit'>In Transit</option> <option value='Arrived at Destination'>Arrived at Destination</option> <option value='Out for Delivery'>Out for Delivery</option> <option value='Delivered'>Delivered</option> <option value='Not Delivered'>Not Delivered</option> <option value='Returning to Origin'>Returning to Origin</option> <option value='Out for Delivery to Origin'>Out for Delivery to Origin</option>";
            } else {
                $orderStatus = "<option value='Booked'>Booked</option> <option value='Item Picked Up'>Item Picked Up</option> <option value='Item Not Picked Up'>Item Not Picked Up</option> <option value='Delivered to branch'>Delivered To Branch</option>";
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'orderId' => $order->order_id,
                'status' => $orderStatus,
            ]);
        }
    }

    public function deliveryAssignAdd(Request $request)
    {
        // dd($request->all());
        $id = Session::get('dlyId');
        if ($request->action == 'DirectOrders') {
            $order = WebOrder::where('order_id', $request->orderIdedit)->first();
            $order->order_status = $request->deliverBoy;
            $order->status_message = $request->status_message ?? $request->Reason_message;
            $order->save();
            $msg = 'Order status update!';
        } else {
            $order = Order::where('order_id', $request->orderIdedit)->first();
            $order->order_status = $request->deliverBoy;
            $order->status_message = $request->status_message ?? $request->Reason_message;
            $order->assign_by = $id;
            $order->save();
            $msg = 'Order status update!';
        }
        $data = Order::where('seller_id', $order->seller_id)->orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('deliveryBoy.inc.orderDetails', compact('data'))->render(),
            ]);
        }
    }

    public function deliveryBoyTotalOrders()
    {
        $id = Session::get('dlyId');
        $delivery = DlyBoy::find($id);
        $totalOrders = Order::where('receiver_pincode', 'link', $delivery->pincode . '%')->where(['assign_to' => $id])->orderBy('id', 'desc')->get();
        $directOrders = WebOrder::where(['receiverPinCode' => $delivery->pincode, 'assign_to' => $id])->orderBy('id', 'desc')->get();

        return view('deliveryBoy.totalOrders', compact('totalOrders', 'directOrders'));
    }

    public function transferOrderDetails($action)
    {
        $id = Session::get('dlyId');
        if ($action == 'DirectOrders') {
            // $data = Order::where(['sender_order_pin_by' => $id, 'sender_order_status' => 'Pending'])->get();
            $data = Order::where('sender_order_pin_by', $id)->whereIn('sender_order_status', ['Pending', 'Processing'])->get();
        } elseif ($action == 'CompleteOrders') {
            $data = Order::where(['sender_order_pin_by' => $id, 'sender_order_status' => 'Delivered'])->get();
        } else {
            $data = Order::where('sender_order_pin_by', $id)->get();
        }
        return view('deliveryBoy.transferOrderDetails', compact('data'));
    }

    public function transferOrderStatus(Request $request)
    {
        // dd($request->all());
        $orderId = explode(',', $request->orderId);
        sort($orderId);
        $order_status = $request->order_status;

        foreach ($orderId as $idValue) {
            $order = Order::where('id', $idValue)->first();
            if ($order) {
                $order->sender_order_status = $order_status;
                $order->assign_to = null;
                $order->assign_by = null;
                $order->save();
                $msg = 'Status update successfully!';
            } else {
                $msg = 'Error! Status not update.';
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }
    }
}
