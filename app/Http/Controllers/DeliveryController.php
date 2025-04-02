<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\COD;
use App\Models\CodAmount;
use App\Models\DlyBoy;
use App\Models\Order;
use App\Models\OrderCod;
use App\Models\PinCode;
use App\Models\Service;
use App\Models\Servicetype;
use App\Models\Wallet;
use App\Models\WebOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    public function deliveryLogin(Request $request)
    {
        $user = Branch::where('email', $request->email)->where('type', $request->type)->first();
        if ($user && $user->password != $request->pwd) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
            ]);
        } else {
            Session::put('dyid', $user->id);
            return response()->json([
                'success' => true,
                'message' => 'Welcome to your Dashboard!'
            ]);
        }
    }

    public function deliveryLogout(Request $request)
    {
        $request->session()->forget('dyid');
        return redirect('/DeliveryPanel')->with([
            'success' => true,
            'message' => 'You have successful LogOut!'
        ]);
    }

    public function setting()
    {
        $user = Session::get('dyid');
        $data = Branch::find($user);
        return view('delivery.setting', compact('data'));
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

    public function dashboard()
    {
        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        $pinCodes = explode(',', $delivery->pincode);
        // toDayOrder details
        $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');
        // $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')
        //     ->where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode]);

        // $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')
        //     ->where(function ($query) use ($pinCodes) {
        //         $query->whereIn('receiver_pincode', $pinCodes)
        //             ->orWhereIn('sender_pincode', $pinCodes);
        // });

        $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')
            ->where(function ($query) use ($pinCodes) {
                $query->whereIn('sender_pincode', $pinCodes);
            });

        $toDayOrder = $ordersQuery->count();

        $toDayPendingOrder = (clone $ordersQuery)->where('order_status', 'Booked')->count();
        $toDayOrderPicUp = (clone $ordersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch'])->count();
        $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
        $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();

        // totalOrder details
        // $totalOrder = Order::where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode])->count();
        // $totalPendingOrder = Order::where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode])->where('order_status', 'Booked')->count();
        // $totalOrderPicUp = Order::where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode])->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        // $totalCompleteOrder = Order::where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode])->where('order_status', 'Delivered')->count();
        // $totalCanceledOrder = Order::where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode])->where('order_status', 'Cancelled')->count();

        $pincodes = explode(',', $delivery->pincode);

        // $query = Order::where(function ($q) use ($pincodes) {
        //     $q->whereIn('receiver_pincode', $pincodes)
        //         ->orWhereIn('sender_pincode', $pincodes);
        // });
        $query = Order::where(function ($q) use ($pincodes) {
            $q->whereIn('sender_pincode', $pincodes);
        });

        $totalOrder         = (clone $query)->count();
        $totalPendingOrder  = (clone $query)->where('order_status', 'Booked')->count();
        $totalOrderPicUp    = (clone $query)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch'])->count();
        $totalCompleteOrder = (clone $query)->where('order_status', 'Delivered')->count();
        $totalCanceledOrder = (clone $query)->where('order_status', 'Cancelled')->count();


        $allOrderDetail = Order::whereNull('sender_order_status')
            ->whereIn('sender_pincode', $pinCodes)
            ->whereNotIn('receiver_pincode', $pinCodes)
            ->select('receiver_pincode')
            ->distinct()
            ->pluck('receiver_pincode')
            ->count();

        $delivery = Branch::find($id);
        $pinCodes = explode(',', $delivery->pincode);
        $myOrderDetail = Order::where(['sender_order_status' => 'Delivered'])->whereIn('sender_order_pin', $pinCodes)->count();

        return view('delivery.dashboard', compact('delivery', 'toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'totalOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCanceledOrder', 'allOrderDetail', 'myOrderDetail'));
    }

    public function orderDetails($action)
    {
        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        $pinCodes = explode(',', trim($delivery->pincode, ','));
        if ($action == 'toDayOrder' || $action == 'toDayPendingOrder' || $action == 'toDayOrderPicUp' || $action == 'toDayCompleteOrder' || $action == 'toDayCancelledOrder') {
            // $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');
            $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');
            // $ordersQuery = Order::where('datetime', 'like', $dateTiem . '%')
            //     ->where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode]);

            // $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')
            //     ->where(function ($query) use ($pinCodes) {
            //         $query->whereIn('receiver_pincode', $pinCodes)
            //             ->orWhereIn('sender_pincode', $pinCodes);
            //     });
            $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')
                ->where(function ($query) use ($pinCodes) {
                    $query->whereIn('sender_pincode', $pinCodes);
                });
            if ($action == 'toDayOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayPendingOrder') {
                $data = $ordersQuery->where('order_status', 'Booked')->get();
            } elseif ($action == 'toDayOrderPicUp') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch'])->get();
            } elseif ($action == 'toDayCompleteOrder') {
                $data = $ordersQuery->where('order_status', 'Delivered')->get();
            } elseif ($action == 'toDayCancelledOrder') {
                $data = $ordersQuery->where('order_status', 'Cancelled')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } elseif ($action == 'totalOrder' || $action == 'totalPendingOrder' || $action == 'totalOrderPicUp' || $action == 'totalCompleteOrder' || $action == 'totalCancelledOrder') {
            // $ordersQuery = Order::where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode]);
            // $ordersQuery = Order::where(function ($q) use ($pinCodes) {
            //     $q->whereIn('receiver_pincode', $pinCodes)
            //         ->orWhereIn('sender_pincode', $pinCodes);
            // });
            $ordersQuery = Order::where(function ($q) use ($pinCodes) {
                $q->whereIn('sender_pincode', $pinCodes);
            });
            if ($action == 'totalOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalPendingOrder') {
                $data = $ordersQuery->where('order_status', 'Booked')->get();
            } elseif ($action == 'totalOrderPicUp') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch'])->get();
            } elseif ($action == 'totalCompleteOrder') {
                $data = $ordersQuery->where('order_status', 'Delivered')->get();
            } elseif ($action == 'totalCancelledOrder') {
                $data = $ordersQuery->where('order_status', 'Cancelled')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } else {
            $data = Order::where(['receiver_pincode' => $delivery->pincode, 'sender_pincode' => $delivery->pincode])->get();
        }
        return view('delivery.orderDetails', compact('data'));
    }

    public function deliveryStatusGet(Request $request)
    {
        // $order = Order::find($request->id);
        // if ($request->ajax()) {
        //     return response()->json([
        //         'success' => true,
        //         'orderId' => $order->order_id
        //     ]);
        // }

        $order = Order::find($request->id);
        $receiverPinCode = $order->receiver_pincode;

        $id = Session::get('dyid');
        $delivery = DlyBoy::find($id);
        $pinCodes = explode(',', $delivery->pincode);

        // Check if receiver pin code is in delivery boy's pin codes
        if (in_array($receiverPinCode, $pinCodes)) {
            $orderStatus = "
        <option value='Booked'>Booked</option> 
        <option value='Item Picked Up'>Item Picked Up</option>
        <option value='Item Not Picked Up'>Item Not Picked Up</option> 
        <option value='Returned'>Returned</option> 
        <option value='In Transit'>In Transit</option> 
        <option value='Arrived at Destination'>Arrived at Destination</option> 
        <option value='Out for Delivery'>Out for Delivery</option> 
        <option value='Delivered'>Delivered</option> 
        <option value='Not Delivered'>Not Delivered</option> 
        <option value='Returning to Origin'>Returning to Origin</option> 
        <option value='Out for Delivery to Origin'>Out for Delivery to Origin</option>
    ";
        } else {
            $orderStatus = "
        <option value='Booked'>Booked</option> 
        <option value='Item Picked Up'>Item Picked Up</option> 
        <option value='Item Not Picked Up'>Item Not Picked Up</option> 
        <option value='Delivered to branch'>Delivered To Branch</option>
    ";
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'orderId' => $order->order_id,
                'status' => $orderStatus,
            ]);
        }
    }

    public function deliveryAssignGet(Request $request)
    {
        $id = Session::get('dyid');
        // $delivery = Branch::find($id);
        // $pinCodes = explode(',', $delivery->pincode);
        // $data = DlyBoy::where(function ($query) use ($pinCodes) {
        //     foreach ($pinCodes as $pincode) {
        //         $query->orWhere('pincode', 'LIKE', "%$pincode%");
        //     }
        // })->where('status', 'active')->get();

        // dd($data->toArray());


        $order = Order::find($request->id);
        $orderId = $order->order_id;
        $data = DlyBoy::where('status', 'active')->where('userid', $id)->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'orderId' => $orderId
            ]);
        }
    }

    public function deliveryBoyGet()
    {
        $id = Session::get('dyid');
        $data = DlyBoy::where('status', 'active')->where('userid', $id)->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function deliveryAssignAdd(Request $request)
    {
        $id = Session::get('dyid');
        if (!empty($request->type)) {
            $order = Order::where('order_id', $request->orderIdedit)->first();
            $order->order_status = $request->deliverBoy;
            $order->status_message = $request->status_message;
            $order->assign_by = $id;
            $order->save();
            $msg = 'Order status update!';
        } else {
            $order = Order::where('order_id', $request->orderIdedit)->first();
            $order->assign_to = $request->deliverBoy;
            $order->status_message = $request->status_message;
            $order->assign_by = $id;
            $order->save();
            if ($order->payment_mode == 'COD') {
                COD::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'delivery_boy_id' => $request->deliverBoy,
                        'datetime' => now('Asia/Kolkata')->format('d-m-Y | h:i:s A')
                    ]
                );
            }
            $msg = 'Order assign successfully!';
        }

        $data = Order::where('seller_id', $order->seller_id)->orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('delivery.inc.orderDetails', compact('data'))->render(),
            ]);
        }
    }

    public function deliveryAssignOrder(Request $request)
    {
        $id = Session::get('dyid');
        $orderId = explode(',', $request->orderId);
        sort($orderId);
        $deliverBoy = $request->deliverBoyData;
        $status = $request->status_message;

        foreach ($orderId as $idValue) {
            // dd($idValue);
            $order = Order::where('id', $idValue)->first();
            if ($order) {
                $order->assign_to = $deliverBoy;
                $order->status_message = $status;
                $order->assign_by = $id;
                $order->save();
                $msg = 'Order assign successfully!';
            } else {
                $msg = 'Error! Order not assign.';
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }
    }

    public function addDeliveryBoy($id = null)
    {
        $data = null;
        if ($id) {
            $data = DlyBoy::find($id);
            dd($data);
        }
        $id = Session::get('dyid');
        $delivery = Branch::find($id);

        return view('delivery.addDeliveryBoy', compact('delivery', 'data'));
    }

    public function addNewDeliveryBoy(Request $request)
    {
        // Check if pinCode already exists in the table
        $existingRecord = DlyBoy::where('pincode', $request->pinCode)->exists();

        if (!$existingRecord || empty($request->pinCode)) {
            $dboy = new DlyBoy();
            $dboy->name = $request->fullName;
            $dboy->email = $request->email;
            $dboy->phone = $request->phone;
            $dboy->address = $request->fullAddress;
            // $dboy->pincode = $request->pinCode;
            $dboy->pincode = implode(',', $request->pinCode);
            $dboy->password = $request->password;
            $dboy->orderRate = $request->orderRate;
            $dboy->userid = Session::get('dyid');
            $dboy->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Delivery boy added successfully!',
                ]);
            }
        } else {
            // If pinCode already exists, return an error response
            return response()->json([
                'success' => false,
                'message' => 'Pincode already exists!',
            ]);
        }
    }

    public function allDeliveryBoy()
    {
        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        $data = DlyBoy::where('userid', $id)->orWhere('pincode', $delivery->pincode)->orderBy('id', 'desc')->get();
        return view('delivery.allDeliveryBoy', compact('data'));
    }

    public function orderHistory(Request $request, $id)
    {
        $dateToday = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'); // Correct date format for LIKE query
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

        // dd($todayOrders, $monthOrders->count()); // Debugging output

        return view('delivery.order-history', compact('todayOrders', 'monthOrders'));
    }

    public function monthOrderHistory(Request $request)
    {
        $monthYear = $request->monthYear;
        $month = date('m', strtotime($monthYear));
        $year = date('Y', strtotime($monthYear));

        $searchDate = sprintf('%02d-%d', $month, $year); // Format as "MM-YYYY"

        $monthOrders = COD::where('datetime', 'LIKE', "%$searchDate%")
            ->where('delivery_boy_id', $request->id)
            ->orderBy('id', 'desc')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.inc.monthOrderHistory', compact('monthOrders'))->render(),
            ]);
        }
    }

    public function codHistory($id)
    {
        $dateToday = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'); // Correct date format
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

        return view('delivery.cod-history', compact('todayCod', 'todaySubmit', 'todayPending', 'totalCod', 'totalSubmit', 'totalPending', 'codHistory'));
    }

    public function addCodAmount(Request $request)
    {
        $user = Session::get('dyid');
        $cod = new CodAmount();
        $cod->amount = $request->amount;
        $cod->delivery_boy_id = $request->delivery_boy;
        $cod->user_id = $user;
        $cod->datetime = now('Asia/Kolkata')->format('d-m-Y H:i:s');
        $cod->save();

        $lastWalletEntry = OrderCod::where('userid', $user)
            ->orderBy('id', 'desc')
            ->first();
        $total = $lastWalletEntry ? ($lastWalletEntry->total + $request->amount) : $request->amount;

        $wlt = new OrderCod();
        $wlt->userid = $user;
        $wlt->c_amount = $request->amount;

        $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

        $wlt->total = $total;
        $wlt->msg = 'credit';
        $wlt->save();

        $codHistory = CodAmount::where('delivery_boy_id', $cod->delivery_boy_id)
            ->orderBy('id', 'desc')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'COD amount added successfully!',
                'html' => view('admin.inc.cod-history', compact('codHistory'))->render(),
            ]);
        }
    }

    public function allCodHistory()
    {
        $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');
        $id = Session::get('dyid');
        $branch = Branch::find($id);
        $orders = Order::where('assign_by', $branch->id)
            ->where('datetime', 'like', $dateTime . '%')
            ->get();

        $orderIds = $orders->pluck('id');

        $data = COD::whereIn('order_id', $orderIds)
            ->where('datetime', 'like', $dateTime . '%')
            ->orderBy('id', 'desc')
            ->get();

        return view('delivery.allCodHistory', compact('data'));
    }

    public function dateCodHistory(Request $request)
    {
        $id = Session::get('dyid');
        $branch = Branch::find($id);
        $orders = Order::where('assign_by', $branch->id)->get();
        $orderIds = $orders->pluck('id');
        $dateRange = $request->date;
        list($startDate, $endDate) = explode(' - ', $dateRange);
        $startDate = date('d-m-Y 00:00:00', strtotime($startDate));
        $endDate = date('d-m-Y 23:59:59', strtotime($endDate));
        $ordersQuery = COD::whereBetween('datetime', [$startDate, $endDate])->whereIn('order_id', $orderIds)->orderBy('id', 'desc');
        $data = $ordersQuery->get();

        // Return AJAX response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('delivery.inc.allCodHistoryData', compact('data'))->render(),
            ]);
        }
    }

    public function deliveryWallet()
    {
        $user = Session::get('dyid');
        $data = OrderCod::where('userid', $user)->orderBy('id', 'desc')->get();
        $amount = $data->first();

        return view('delivery.deliveryWallet', compact('data', 'amount'));
    }

    public function otherBranchOrder()
    {
        // $receiverPinCodes = Order::whereColumn('sender_pincode', '!=', 'receiver_pincode')
        //     ->whereNull('sender_order_status')
        //     ->where(function ($query) use ($pinCodes) {
        //         $query->whereIn('sender_pincode', $pinCodes)
        //             ->orWhereIn('receiver_pincode', $pinCodes);
        //     })
        //     ->select('receiver_pincode')
        //     ->distinct()
        //     ->pluck('receiver_pincode');

        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        $pinCodes = explode(',', $delivery->pincode);

        $receiverPinCodes = Order::whereNull('sender_order_status')
            ->whereIn('sender_pincode', $pinCodes)
            ->whereNotIn('receiver_pincode', $pinCodes)
            ->select('receiver_pincode')
            ->distinct()
            ->pluck('receiver_pincode');



        // dd($receiverPinCodes->toArray());

        return view('delivery.otherBranchOrderPinCode', compact('receiverPinCodes'));
    }

    public function otherBranchOrderDetails($id)
    {
        $data = Order::where(['receiver_pincode' => $id, 'sender_order_status' => null])->get();
        return view('delivery.otherBranchOrder', compact('data'));
    }

    public function deliveryTransferBoyGet()
    {
        $id = Session::get('dyid');
        // $delivery = Branch::find($id);
        // $pinCodes = explode(',', $delivery->pincode);
        // $data = DlyBoy::where(function ($query) use ($pinCodes) {
        //     foreach ($pinCodes as $pincode) {
        //         $query->orWhere('pincode', 'LIKE', "%$pincode%");
        //     }
        // })->where('status', 'active')->get();

        $data = DlyBoy::where('status', 'active')->where('userid', $id)->get();


        $pinCode = PinCode::where('status', 'active')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'pinCode' => $pinCode,
        ]);
    }

    public function deliveryTransferAssignOrder(Request $request)
    {
        $orderId = explode(',', $request->transferOrderId);
        sort($orderId);
        $deliverBoy = $request->transferDeliverBoyData;

        foreach ($orderId as $idValue) {
            $order = Order::where('id', $idValue)->first();
            if ($order) {
                $order->sender_order_pin = $request->transferOrderPinCode;
                $order->sender_order_pin_by = $deliverBoy;
                $order->sender_order_status = 'Pending';
                $order->save();
                $msg = 'Order Transfer successfully!';
            } else {
                $msg = 'Error! Order not Transfer.';
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }
    }

    public function otherBranchOrderStatus()
    {
        $id = Session::get('dyid');
        // dd($id);
        $data = Order::whereNot('sender_order_status', null)->get();
        return view('delivery.otherBranchOrderStatus', compact('data'));
    }

    public function otherTransferOrderDetails(Request $request)
    {
        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        $pinCodes = explode(',', $delivery->pincode);

        if ($request->filter) {
            $filterType = $request->filter;
            $data = Order::where(['sender_order_status' => 'Delivered', 'service_type' => $filterType])->whereIn('sender_order_pin', $pinCodes)->get();
            return response()->json([
                'success' => true,
                'html' => view('delivery.inc.otherTransferOrderDetails', compact('data'))->render(),
            ]);
        } else {
            $data = Order::where(['sender_order_status' => 'Delivered'])->whereIn('sender_order_pin', $pinCodes)->get();
            return view('delivery.otherTransferOrderDetails', compact('data'));
        }
    }

    public function webDirectOrders()
    {
        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        $pinCodes = explode(',', $delivery->pincode);

        $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');

        // $data = WebOrder::where('datetime', 'like', $dateTime . '%')->where('senderPinCode', $delivery->pincode)->orderBy('id', 'desc')->get();
        // $data = Order::where('datetime', 'like', $dateTime . '%')->where(['sender_pincode' => $delivery->pincode, 'parcel_type' => 'Direct'])->orderBy('id', 'desc')->get();

        $data = Order::where('datetime', 'like', $dateTime . '%')
            ->where(function ($query) use ($pinCodes) {
                $query->whereIn('sender_pincode', $pinCodes)
                    ->where('parcel_type', 'Direct');
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('delivery.webOrders', compact('data'));
    }

    public function deliverInvoice($id)
    {
        // $data = WebOrder::where('order_id', $id)->first();
        $data = Order::where('order_id', $id)->first();
        return view('delivery.invoice', compact('data'));
    }

    public function webDirectOrdersStatus(Request $request)
    {
        $order = WebOrder::find($request->id);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'orderId' => $order->order_id
            ]);
        }
    }

    public function webDirectOrdersAssign(Request $request)
    {
        $order = WebOrder::find($request->id);
        $data = DlyBoy::where('status', 'active')->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'orderId' => $order->order_id
            ]);
        }
    }

    public function webDirectOrdersAdd(Request $request)
    {
        if (!empty($request->type)) {
            $order = WebOrder::where('order_id', $request->orderIdedit)->first();
            $order->order_status = $request->deliverBoy;
            $order->status_message = $request->status_message;
            $order->save();
            $msg = 'Order status update!';
        } else {
            $order = WebOrder::where('order_id', $request->orderIdedit)->first();
            $order->assign_to = $request->deliverBoy;
            $order->status_message = $request->status_message;
            $order->save();
            $msg = 'Order assign successfully!';
        }

        $data = WebOrder::orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('delivery.inc.webOrderDetails', compact('data'))->render(),
            ]);
        }
    }

    public function webDirectOrdersFilter(Request $request)
    {
        $dateRange = $request->date;
        // Split start and end dates
        list($startDate, $endDate) = explode(' - ', $dateRange);
        // Convert date format from "m/d/Y" to "d-m-Y" to match database
        // $startDate = date('d-m-Y', strtotime($startDate));
        // $endDate = date('d-m-Y', strtotime($endDate));
        // Query matching the date format in the database
        // $ordersQuery = WebOrder::where('datetime', 'LIKE', "$startDate%")
        //     ->orWhere('datetime', 'LIKE', "$endDate%")
        //     ->orderBy('id', 'desc');

        $startDate = date('d-m-Y 00:00:00', strtotime($startDate));
        $endDate = date('d-m-Y 23:59:59', strtotime($endDate));
        $ordersQuery = WebOrder::whereBetween('datetime', [$startDate, $endDate])
            ->orderBy('id', 'desc');
        $data = $ordersQuery->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('delivery.inc.webOrderDetails', compact('data'))->render(),
            ]);
        }
    }

    public function deliveryLabel($id)
    {
        $data = Order::where('order_id', $id)->first();
        if ($data->payment_mode == 'COD') {
            return view('delivery.label', compact('data'));
        } else {
            return view('delivery.label1', compact('data'));
        }
    }
}
