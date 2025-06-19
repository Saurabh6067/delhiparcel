<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCodHistory;
use App\Models\BranchtotalCod;
use App\Models\COD;
use App\Models\CodWallet;
use App\Models\OrderHistory;
use App\Models\CodAmount;
use App\Models\DlyBoy;
use App\Models\Order;
use App\Models\CodSellerAmount;
use App\Models\WebOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderDeliveredConfirmation;


class DeliveryBoyController extends Controller
{
    
    protected $date;
    public function __construct()
    {
        $kolkataDateTime  = Carbon::now('Asia/Kolkata');
        $this->date       = $kolkataDateTime->format('Y-m-d H:i:s');
    }
    
    
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
        
        $toDayOrder = Order::where('assign_to', $id)->whereNotIn('order_status', ['Cancelled','Delivered'])->count();
        
        // dd($toDayOrder);
        
        $toDayCompleteOrder = Order::where('datetime', 'like', $dateTime . '%')->where(['order_status' => 'Delivered', 'assign_to' => $id])->count();
        // $toDayCompleteOrder = Order::where(['order_status' => 'Delivered', 'assign_to' => $id])->count();

        // totalOrder details
        $totalOrder = Order::count();
        // $PendingOrder = Order::where('assign_to', $id)->where('order_status', 'Booked')->orWhere('order_status','Item Not Picked Up')->count();
        $PendingOrder = Order::where('assign_to', $id)
        ->where(function($query) {
            $query->where('order_status', 'Booked')
                  ->orWhere('order_status', 'Item Not Picked Up');
        })
        ->count();
        $totalCompleteOrder = Order::where(['order_status' => 'Delivered', 'assign_to' => $id])->count();
        
        $PendingDeliveryOrder = Order::whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up','Item Picked Up','Delivered to near by branch'])->where('assign_to', $id)->count();
        $transfertoMyBranchOrder = Order::whereIn('order_status', ['Item Picked Up'])->where('assign_to', $id)->count();
        $transfertoOtherBranchOrder = Order::whereIn('order_status', ['Delivered to near by branch'])->where('assign_to', $id)->count();
        
        // Super Express order details
        $PendingSuperExpressOrder = Order::where(['service_type' => 'SuperExpress', 'assign_to' => $id])
            ->whereNotIn('order_status', ['Delivered','Cancelled'])
            ->count();

        // Get IDs of pending super express orders for the timer
        $pendingSuperExpressOrderIds = Order::where(['service_type' => 'SuperExpress', 'assign_to' => $id])
            ->where('order_status', '!=', 'Delivered')
            ->pluck('id')
            ->toArray();

        $DirectOrders = WebOrder::where('assign_to', $id)->count();
        $PendingPinCodeOrders = Order::where('sender_order_pin_by', $id)->whereIn('sender_order_status', ['Pending', 'Processing'])->count();
        $CompleteOtherPinCodeOrders = Order::where(['sender_order_pin_by' => $id, 'sender_order_status' => 'Delivered'])->count();

        // Get recent deliveries (last 5 delivered orders)
        $recentDeliveries = Order::where('assign_to', $id)
            ->where('order_status', 'Delivered')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Calculate average and fastest delivery times
        // $averageDeliveryTime = $this->calculateAverageDeliveryTime($id);
        // $fastestDeliveryTime = $this->getFastestDeliveryTime($id);

        return view('deliveryBoy.dashboard', compact(
            'delivery',
            'toDayOrder',
            'totalOrder',
            'toDayCompleteOrder',
            'PendingOrder',
            'totalCompleteOrder',
            'PendingDeliveryOrder',
            'PendingSuperExpressOrder',
            'pendingSuperExpressOrderIds',
            'DirectOrders',
            'PendingPinCodeOrders',
            'CompleteOtherPinCodeOrders',
            'recentDeliveries',
            'transfertoMyBranchOrder',
            'transfertoOtherBranchOrder'
        ));
    }

    /**
     * Calculate average delivery time
     * 
     * @param int $deliveryBoyId
     * @return string
     */
  

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
        $dateToday = now('Asia/Kolkata')->format('d-m-Y'); // Today's date in d-m-Y format

        // Get today's total COD from Order table
        $todayCod = Order::where('assign_to', $id)
            ->where('datetime', 'like', $dateToday . '%')
            ->where('payment_mode', 'COD')
            ->sum('codAmount');

        // Get all COD history from CodAmount for the table
        $codAmounts = CodAmount::where('delivery_boy_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        // Get all-time COD amount from CodWallet for second card
        $codWallet = CodWallet::where('delivery_boy_id', $id)->first();
        $totalCod = $codWallet ? $codWallet->amount : 0.0;

        return view('deliveryBoy.cod-history', compact(
            'todayCod',
            'totalCod',
            'codAmounts'
        ));
    }



    // Delivery Boy myearning 
    public function deliveryBoyMyEarning()
    {
        // Get delivery boy ID from session
        $id = Session::get('dlyId');
        $delivery = DlyBoy::find($id);

        // Get per-order rate
        $orderRate = $delivery->orderRate;

        // Current date in Asia/Kolkata timezone
        $currentDate = Carbon::now('Asia/Kolkata');
        $dateTime = $currentDate->format('d-m-Y');

        // Initialize data arrays
        $earningsData = [
            'today' => ['orders' => [], 'total' => 0],
            'this_week' => ['orders' => [], 'total' => 0],
            'this_month' => ['orders' => [], 'total' => 0],
            'this_year' => ['orders' => [], 'total' => 0],
        ];

        // Today: Orders delivered today
        $todayOrders = Order::where('datetime', 'like', $dateTime . '%')
            ->where('order_status', 'Delivered')
            ->where('assign_to', $id)
            ->select('id', 'datetime', 'order_status')
            ->get();
        $earningsData['today']['orders'] = $todayOrders;
        $earningsData['today']['total'] = $todayOrders->count() * $orderRate;

        // This Week: Orders delivered this week (Monday to Sunday)
        $weekStart = $currentDate->startOfWeek()->format('d-m-Y');
        $weekEnd = $currentDate->endOfWeek()->format('d-m-Y');
        $thisWeekOrders = Order::whereBetween('datetime', [$weekStart . ' 00:00:00', $weekEnd . ' 23:59:59'])
            ->where('order_status', 'Delivered')
            ->where('assign_to', $id)
            ->select('id', 'datetime', 'order_status')
            ->get();
        $earningsData['this_week']['orders'] = $thisWeekOrders;
        $earningsData['this_week']['total'] = $thisWeekOrders->count() * $orderRate;

        // This Month: Orders delivered this month
        $monthStart = $currentDate->startOfMonth()->format('d-m-Y');
        $monthEnd = $currentDate->endOfMonth()->format('d-m-Y');
        $thisMonthOrders = Order::whereBetween('datetime', [$monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])
            ->where('order_status', 'Delivered')
            ->where('assign_to', $id)
            ->select('id', 'datetime', 'order_status')
            ->get();
        $earningsData['this_month']['orders'] = $thisMonthOrders;
        $earningsData['this_month']['total'] = $thisMonthOrders->count() * $orderRate;

        // This Year: Orders delivered this year
        $yearStart = $currentDate->startOfYear()->format('d-m-Y');
        $yearEnd = $currentDate->endOfYear()->format('d-m-Y');
        $thisYearOrders = Order::whereBetween('datetime', [$yearStart . ' 00:00:00', $yearEnd . ' 23:59:59'])
            ->where('order_status', 'Delivered')
            ->where('assign_to', $id)
            ->select('id', 'datetime', 'order_status')
            ->get();
        $earningsData['this_year']['orders'] = $thisYearOrders;
        $earningsData['this_year']['total'] = $thisYearOrders->count() * $orderRate;

        // Return view with earnings data
        return view('deliveryBoy.myearning', compact('earningsData', 'orderRate'));
    }
    
    // 4 june 
    // public function submitCodToBranch(Request $request)
    // {
    //     $id = Session::get('dlyId');
    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

    //     // Validate input
    //     $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string|max:255',
    //     ]);

    //     // Get delivery boy details including pincode
    //     $deliveryBoy = Dlyboy::where('id', $id)->first();

    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found.',
    //         ], status: 404);
    //     }

    //     $deliveryBoyPincodes = explode(',', $deliveryBoy->pincode);

    //     // Find matching branch based on pincode
    //     $matchingBranchId = null;
    //     $branches = Branch::all();

    //     foreach ($branches as $branch) {
    //         $branchPincodes = explode(',', $branch->pincode);

    //         // Check for any matching pincode between delivery boy and branch
    //         $matchingPincodes = array_intersect($deliveryBoyPincodes, $branchPincodes);

    //         if (!empty($matchingPincodes)) {
    //             $matchingBranchId = $branch->id;
    //             break; // Found a match, no need to continue checking
    //         }
    //     }

    //     if (!$matchingBranchId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No matching branch found for delivery boy pincode.',
    //         ], 400);
    //     }

    //     try {
    //         $codWallet = CodWallet::where('delivery_boy_id', $id)->first();

    //         if (!$codWallet) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No wallet found for this delivery boy.',
    //             ], 400);
    //         }

    //         if ($codWallet->amount < $request->amount) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Insufficient wallet balance. Available: ' . $codWallet->amount,
    //             ], 400);
    //         }

    //         // Update wallet balance
    //         $codWallet->amount -= $request->amount;
    //         $codWallet->save();
    //         $debit = 'Debited';

    //         $pending = "Pending";
    //         // Create history entry in CodAmount with type Debited
    //         CodAmount::create([
    //             'delivery_boy_id' => $id,
    //             'amount' => $request->amount,
    //             'datetime' => $currentDateTime,
    //             'type' => $debit,
    //             'remarks' => $request->remarks,
    //             'user_id' => $id,
    //             'status' => $pending
    //         ]);

    //         // Create entry in BranchCodHistory
    //         BranchCodHistory::create([
    //             'delivery_boy_id' => $id,
    //             'amount' => $request->amount,
    //             'type' => 'Received',  // Indicating branch received the COD
    //             'branch_id' => $matchingBranchId,
    //             'datetime' => $currentDateTime,
    //             'status' => $pending
    //         ]);

    //         // Check if there's an existing record in BranchtotalCod for this branch and delivery boy
    //         $branchTotalCod = BranchtotalCod::where('delivery_boy_id', $id)
    //             ->where('branch_id', $matchingBranchId)
    //             ->first();

    //         if ($branchTotalCod) {
    //             // Update existing record
    //             $branchTotalCod->amount += $request->amount;
    //             $branchTotalCod->save();
    //         } else {
    //             // Create new record
    //             BranchtotalCod::create([
    //                 'delivery_boy_id' => $id,
    //                 'amount' => $request->amount,
    //                 'branch_id' => $matchingBranchId,
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'COD amount submitted successfully.',
    //             'branch_id' => $matchingBranchId,
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error submitting COD amount: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    // 14 june 
    // public function submitCodToBranch(Request $request)
    // {
    //     $id = Session::get('dlyId');
    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

    //     // Validate input
    //     $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string|max:255',
    //     ]);

    //     // Get delivery boy details including pincode
    //     $deliveryBoy = Dlyboy::where('id', $id)->first();

    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found.',
    //         ], status: 404);
    //     }

    //     $deliveryBoyPincodes = explode(',', $deliveryBoy->pincode);

    //     // Find matching branch based on pincode
    //     $matchingBranchId = null;
    //     $branches = Branch::all();

    //     foreach ($branches as $branch) {
    //         $branchPincodes = explode(',', $branch->pincode);

    //         // Check for any matching pincode between delivery boy and branch
    //         $matchingPincodes = array_intersect($deliveryBoyPincodes, $branchPincodes);

    //         if (!empty($matchingPincodes)) {
    //             $matchingBranchId = $branch->id;
    //             break; // Found a match, no need to continue checking
    //         }
    //     }

    //     if (!$matchingBranchId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No matching branch found for delivery boy pincode.',
    //         ], 400);
    //     }

    //     try {
    //         $codWallet = CodWallet::where('delivery_boy_id', $id)->first();

    //         if (!$codWallet) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No wallet found for this delivery boy.',
    //             ], 400);
    //         }

    //         if ($codWallet->amount < $request->amount) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Insufficient wallet balance. Available: ' . $codWallet->amount,
    //             ], 400);
    //         }

    //         // Update wallet balance
    //         $codWallet->amount -= $request->amount;
    //         $codWallet->save();
    //         $debit = 'Debited';

    //         $pending = "Pending";

    //         // Create entry in BranchCodHistory
    //         $branchdata = BranchCodHistory::create([
    //             'delivery_boy_id' => $id,
    //             'amount' => $request->amount,
    //             'type' => 'Received',  // Indicating branch received the COD
    //             'branch_id' => $matchingBranchId,
    //             'remarks' => $request->remarks,
    //             'datetime' => $currentDateTime,
    //             'status' => $pending
    //         ]);

    //         // Create history entry in CodAmount with type Debited
    //         CodAmount::create([
    //             'record_id' => $branchdata->id,
    //             'delivery_boy_id' => $id,
    //             'amount' => $request->amount,
    //             'datetime' => $currentDateTime,
    //             'type' => $debit,
    //             'remarks' => $request->remarks,
    //             'user_id' => $id,
    //             'status' => $pending
    //         ]);


    //         // Check if there's an existing record in BranchtotalCod for this branch and delivery boy
    //         $branchTotalCod = BranchtotalCod::where('delivery_boy_id', $id)
    //             ->where('branch_id', $matchingBranchId)
    //             ->first();

    //         if ($branchTotalCod) {
    //             // Update existing record
    //             $branchTotalCod->amount += $request->amount;
    //             $branchTotalCod->save();
    //         } else {
    //             // Create new record
    //             BranchtotalCod::create([
    //                 'delivery_boy_id' => $id,
    //                 'amount' => $request->amount,
    //                 'branch_id' => $matchingBranchId,
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'COD amount submitted successfully.',
    //             'branch_id' => $matchingBranchId,
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error submitting COD amount: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    
    // 16 june latest code 
    // public function submitCodToBranch(Request $request)
    // {
    //     $id = Session::get('dlyId');
    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

    //     // Validate input
    //     $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string|max:255',
    //     ]);

    //     // Get delivery boy details including pincode
    //     $deliveryBoy = Dlyboy::where('id', $id)->first();

    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found.',
    //         ], status: 404);
    //     }

    //     $deliveryBoyPincodes = explode(',', $deliveryBoy->pincode);

    //     // Find matching branch based on pincode
    //     $matchingBranchId = null;
    //     $branches = Branch::all();

    //     foreach ($branches as $branch) {
    //         $branchPincodes = explode(',', $branch->pincode);

    //         // Check for any matching pincode between delivery boy and branch
    //         $matchingPincodes = array_intersect($deliveryBoyPincodes, $branchPincodes);

    //         if (!empty($matchingPincodes)) {
    //             $matchingBranchId = $branch->id;
    //             break; // Found a match, no need to continue checking
    //         }
    //     }

    //     if (!$matchingBranchId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No matching branch found for delivery boy pincode.',
    //         ], 400);
    //     }

    //     try {
    //         $codWallet = CodWallet::where('delivery_boy_id', $id)->first();

    //         if (!$codWallet) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No wallet found for this delivery boy.',
    //             ], 400);
    //         }

    //         if ($codWallet->amount < $request->amount) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Insufficient wallet balance. Available: ' . $codWallet->amount,
    //             ], 400);
    //         }

    //         // Update wallet balance
    //         $codWallet->amount -= $request->amount;
    //         $codWallet->save();
    //         $debit = 'Debited';

    //         $pending = "Pending";

    //         // Create entry in BranchCodHistory
    //         $branchdata = BranchCodHistory::create([
    //             'delivery_boy_id' => $id,
    //             'amount' => $request->amount,
    //             'type' => 'Received',  // Indicating branch received the COD
    //             'branch_id' => $matchingBranchId,
    //             'remarks' => $request->remarks,
    //             'datetime' => $currentDateTime,
    //             'status' => $pending
    //         ]);

    //         // Create history entry in CodAmount with type Debited
    //         CodAmount::create([
    //             'record_id' => $branchdata->id,
    //             'delivery_boy_id' => $id,
    //             'amount' => $request->amount,
    //             'datetime' => $currentDateTime,
    //             'type' => $debit,
    //             'remarks' => $request->remarks,
    //             'user_id' => $id,
    //             'status' => $pending
    //         ]);


    //         // Check if there's an existing record in BranchtotalCod for this branch and delivery boy
    //         // $branchTotalCod = BranchtotalCod::where('delivery_boy_id', $id)
    //         //     ->where('branch_id', $matchingBranchId)
    //         //     ->first();

    //         // if ($branchTotalCod) {
    //         //     // Update existing record
    //         //     $branchTotalCod->amount += $request->amount;
    //         //     $branchTotalCod->save();
    //         // } else {
    //         //     // Create new record
    //         //     BranchtotalCod::create([
    //         //         'delivery_boy_id' => $id,
    //         //         'amount' => $request->amount,
    //         //         'branch_id' => $matchingBranchId,
    //         //     ]);
    //         // }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'COD amount submitted successfully.',
    //             'branch_id' => $matchingBranchId,
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error submitting COD amount: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    
    // latest code 
    public function submitCodToBranch(Request $request)
    {
        $id = Session::get('dlyId');
        $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

        // Validate input
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        // Get delivery boy details including pincode
        $deliveryBoy = Dlyboy::where('id', $id)->first();

        if (!$deliveryBoy) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery boy not found.',
            ], status: 404);
        }

        $deliveryBoyPincodes = explode(',', $deliveryBoy->pincode);

        // Find matching branch based on pincode
        $matchingBranchId = null;
        $branches = Branch::all();

        foreach ($branches as $branch) {
            $branchPincodes = explode(',', $branch->pincode);

            // Check for any matching pincode between delivery boy and branch
            $matchingPincodes = array_intersect($deliveryBoyPincodes, $branchPincodes);

            if (!empty($matchingPincodes)) {
                $matchingBranchId = $branch->id;
                break; // Found a match, no need to continue checking
            }
        }

        if (!$matchingBranchId) {
            return response()->json([
                'success' => false,
                'message' => 'No matching branch found for delivery boy pincode.',
            ], 400);
        }

        try {
            $codWallet = CodWallet::where('delivery_boy_id', $id)->first();

            if (!$codWallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'No wallet found for this delivery boy.',
                ], 400);
            }

            if ($codWallet->amount < $request->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance. Available: ' . $codWallet->amount,
                ], 400);
            }

            // Update wallet balance
            // $codWallet->amount -= $request->amount;
            $codWallet->save();
            $debit = 'Debited';

            $pending = "Pending";

            // Create entry in BranchCodHistory
            $branchdata = BranchCodHistory::create([
                'delivery_boy_id' => $id,
                'amount' => $request->amount,
                'type' => 'Received',  // Indicating branch received the COD
                'branch_id' => $matchingBranchId,
                'remarks' => $request->remarks,
                'datetime' => $currentDateTime,
                'status' => $pending
            ]);

            // Create history entry in CodAmount with type Debited
            CodAmount::create([
                'record_id' => $branchdata->id,
                'delivery_boy_id' => $id,
                'amount' => $request->amount,
                'datetime' => $currentDateTime,
                'type' => $debit,
                'remarks' => $request->remarks,
                'user_id' => $id,
                'status' => $pending
            ]);

            return response()->json([
                'success' => true,
                'message' => 'COD amount submitted successfully.',
                'branch_id' => $matchingBranchId,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting COD amount: ' . $e->getMessage(),
            ], 500);
        }
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
                $data = Order::where('assign_to', $id)->whereNotIn('order_status', ['Cancelled','Delivered'])->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayCompleteOrder') {
                $data =  $ordersQuery->where(['order_status' => 'Delivered', 'assign_to' => $id])->orderBy('id', 'desc')->get();
            } else {
                $data = Order::orderBy('id', 'desc')->get();
            }
        } elseif ($action == 'totalOrder' || $action == 'PendingOrder' || $action == 'PendingSuperExpressOrder' || $action == 'PendingDeliveryOrder' || $action == 'DirectOrders' || $action == 'totalCompleteOrder' || $action == 'transfertoMyBranchOrder' || $action == 'transfertoOtherBranchOrder') {
            // $ordersQuery = Order::where('receiver_pincode', $delivery->pincode);
            $ordersQuery = new Order;
            if ($action == 'totalOrder') {
                $data = $ordersQuery->where('assign_to', $id)->orderBy('id', 'desc')->get();
            } elseif ($action == 'PendingOrder') {
               $data = $ordersQuery->where('assign_to', $id)
                ->where(function($query) {
                    $query->where('order_status', 'Booked')
                          ->orWhere('order_status', 'Item Not Picked Up');
                })
                ->orderBy('id', 'desc')
                ->get();
            } elseif ($action == 'totalCompleteOrder') {
                $data = $ordersQuery->where(['order_status' => 'Delivered', 'assign_to' => $id])->orderBy('id', 'desc')->get();
            } elseif ($action == 'transfertoMyBranchOrder'){
                $data = Order::whereIn('order_status', ['Item Picked Up'])->where('assign_to', $id)->get();
            
            } elseif ($action == 'transfertoOtherBranchOrder'){
                $data = Order::whereIn('order_status', ['Delivered to near by branch'])->where('assign_to', $id)->get();
            
            } elseif ($action == 'PendingSuperExpressOrder') {
                $data = $ordersQuery->whereNotIn('order_status', ['Cancelled','Delivered'])->where('service_type', 'SuperExpress')->where('assign_to', $id)->orderBy('id', 'desc')->get();
            } elseif ($action == 'PendingDeliveryOrder') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up','Item Picked Up','Delivered to near by branch'])->where('assign_to', $id)->orderBy('id', 'desc')->get();
            } elseif ($action == 'DirectOrders') {
                $data = WebOrder::where('assign_to', $id)->orderBy('id', 'desc')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } else {
            // $pincodeArray = explode(',', );
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

 
    public function qrscanner(Request $request)
    {
    $dlyId = Session::get('dlyId'); // Get delivery boy ID from session

    if ($request->isMethod('post')) {
        $trackingId = $request->input('tracking_id');
        $deliveryBoyId = $request->input('deliveryBoyId');

        // Validate required inputs
        if (!$trackingId || !$deliveryBoyId) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking ID or Delivery Boy ID not provided'
            ]);
        }

        // Validate delivery boy ID matches session
        if ($deliveryBoyId != $dlyId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Delivery Boy ID does not match session'
            ]);
        }

        // Find the order
        $order = Order::where('order_id', $trackingId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        // Check if order is assigned to the delivery boy
        $isScanned = ($order->assign_to == $dlyId);

        if (!$isScanned) {
            return response()->json([
                'success' => false,
                'message' => 'Order not assigned to this delivery boy',
                'is_scanned' => false,
                'order_id' => $trackingId
            ]);
        }

        // Get the order's status and sender pincode
        $sender_pincode = $order->sender_pincode;
        $order_status = $order->order_status;

        // Return the result for successful scan
        return response()->json([
            'success' => true,
            'message' => 'Order scanned successfully',
            'is_scanned' => true,
            'order' => $sender_pincode,
            'order_status' => $order_status,
            'order_id' => $trackingId
        ]);
    }

    return view('deliveryBoy.qrcode');
}
    
    // Saurabh
    public function updateOrderStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        $status = $request->input('status');
        $statusMessage = $request->input('status_message');
        $deliveryBoyId = $request->input('deliveryBoyId');

        // Validate required inputs
        if (!$orderId || !$status || !$deliveryBoyId) {
            return response()->json([
                'success' => false,
                'message' => 'Required fields missing'
            ]);
        }

        // Find the order
        $order = Order::where('order_id', $orderId)->first();
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        // Verify this order has "Booked" status
        if ($order->order_status !== "Booked") {
            return response()->json([
                'success' => false,
                'message' => 'Only orders with "Booked" status can be updated'
            ]);
        }

        // Verify this delivery boy is authorized (by checking pincode match)
        $deliveryBoy = DlyBoy::where('id', $deliveryBoyId)->first();
        if (!$deliveryBoy) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery boy not found'
            ]);
        }

        $boyPincodes = explode(',', $deliveryBoy->pincode);
        $senderPincode = $order->sender_pincode;
        $receiverPincode = $order->receiver_pincode;

        if (!in_array($senderPincode, $boyPincodes) && !in_array($receiverPincode, $boyPincodes)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this order'
            ]);
        }

        // Update order status
        $order->order_status = $status;
        $order->updated_at = $this->date;  // updated_at 

        // Update status message if "Not Picked Up" is selected
        if ($status === "item Not Picked Up") {
            if (empty($statusMessage)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reason is required for Not Picked Up status'
                ]);
            }
            $order->status_message = $statusMessage;
        }

        // Save the changes
        $order->save();

        // Get current date and time in India timezone
        $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

        // Create order history
        $orderHistory = new OrderHistory();
        $orderHistory->datetime = $currentDateTime;
        $orderHistory->status = $status;
        $orderHistory->tracking_id = $orderId;
        $orderHistory->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    }
    
    
    // my new code 
    // public function getTodaysPickedUpOrders(Request $request)
    // {
    //     $deliveryBoyId = $request->input('deliveryBoyId');

       
    //     if (!$deliveryBoyId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery Boy ID not provided'
    //         ]);
    //     }

    //     // Get the delivery boy
    //     $deliveryBoy = DlyBoy::where('id', $deliveryBoyId)->first();
    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found'
    //         ]);
    //     }
        
    //     $today = now('Asia/Kolkata')->format('Y-m-d');
    //     // $orders = Order::where('assign_to', $deliveryBoyId)
    //     //         ->where('order_status', 'item Picked Up')
    //     //         ->orWhere('order_status', 'Delivered to near by branch')
    //     //         ->whereDate('updated_at', $today)
    //     //         ->get();
        
    //     $orders = Order::where('assign_to', $deliveryBoyId)
    //     ->whereDate('updated_at', $today)
    //     ->where(function ($query) {
    //         $query->where('order_status', 'item Picked Up');
    //     })
    //     ->get();
    //     // Format the pickup time
    //     $orders->transform(function ($order) {
    //         $order->pickup_time = Carbon::parse($order->updated_at)->format('d-m-Y h:i A');
    //         return $order;
    //     });
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Orders retrieved successfully',
    //         'orders' => $orders
    //     ]);
    // }
    
    
    // public function getTodaysPickedUpOrdersDeliveredtonearbybranch(Request $request)
    // {
    //     $deliveryBoyId = $request->input('deliveryBoyId');

       
    //     if (!$deliveryBoyId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery Boy ID not provided'
    //         ]);
    //     }

    //     // Get the delivery boy
    //     $deliveryBoy = DlyBoy::where('id', $deliveryBoyId)->first();
    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found'
    //         ]);
    //     }
        
    //     $today = now('Asia/Kolkata')->format('Y-m-d');
       
        
    //     $orders = Order::where('assign_to', $deliveryBoyId)
    //     ->whereDate('updated_at', $today)
    //     ->where(function ($query) {
    //         $query->where('order_status', 'Delivered to near by branch');
    //     })
    //     ->get();
    //     // Format the pickup time
    //     $orders->transform(function ($order) {
    //         $order->pickup_time = Carbon::parse($order->updated_at)->format('d-m-Y h:i A');
    //         return $order;
    //     });
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Orders retrieved successfully',
    //         'orders' => $orders
    //     ]);
    // }
    
    
    
   

    /**
     * Get today's picked up orders for a delivery boy.
     */
    // public function getTodaysPickedUpOrders(Request $request)
    // {
    //     $deliveryBoyId = $request->input('deliveryBoyId');

    //     if (!$deliveryBoyId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery Boy ID not provided'
    //         ], 400);
    //     }

    //     $deliveryBoy = DlyBoy::where('id', $deliveryBoyId)->first();
    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found'
    //         ], 404);
    //     }

    //     $today = now('Asia/Kolkata')->format('Y-m-d');
    //     $orders = Order::where('assign_to', $deliveryBoyId)
    //         ->where('order_status', 'item Picked Up')
    //         ->whereDate('updated_at', $today)
    //         ->get(['order_id', 'updated_at']);

    //     $orders->transform(function ($order) {
    //         $order->pickup_time = Carbon::parse($order->updated_at)->format('d-m-Y h:i A');
    //         return $order;
    //     });

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Picked up orders retrieved successfully',
    //         'orders' => $orders
    //     ]);
    // }
    
    public function getTodaysPickedUpOrders(Request $request)
    {
    $deliveryBoyId = $request->input('deliveryBoyId');

    if (!$deliveryBoyId) {
        return response()->json([
            'success' => false,
            'message' => 'Delivery Boy ID not provided'
        ], 400);
    }

    $deliveryBoy = DlyBoy::where('id', $deliveryBoyId)->first();
    if (!$deliveryBoy) {
        return response()->json([
            'success' => false,
            'message' => 'Delivery boy not found'
        ], 404);
    }

    $orders = Order::where('assign_to', $deliveryBoyId)
        ->where('order_status', 'item Picked Up')
        ->get(['order_id', 'updated_at']);

    $orders->transform(function ($order) {
        $order->pickup_time = Carbon::parse($order->updated_at)->format('d-m-Y h:i A');
        return $order;
    });

    return response()->json([
        'success' => true,
        'message' => 'Picked up orders retrieved successfully',
        'orders' => $orders
    ]);
}

    /**
     * Get today's orders delivered to nearby branch for a delivery boy.
     */
    // public function getTodaysPickedUpOrdersDeliveredtonearbybranch(Request $request)
    // {
    //     $deliveryBoyId = $request->input('deliveryBoyId');

    //     if (!$deliveryBoyId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery Boy ID not provided'
    //         ], 400);
    //     }

    //     $deliveryBoy = DlyBoy::where('id', $deliveryBoyId)->first();
    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found'
    //         ], 404);
    //     }

    //     $today = now('Asia/Kolkata')->format('Y-m-d');
    //     $orders = Order::where('assign_to', $deliveryBoyId)
    //         ->where('order_status', 'Delivered to near by branch')
    //         ->whereDate('updated_at', $today)
    //         ->get(['order_id', 'updated_at']);

    //     $orders->transform(function ($order) {
    //         $order->pickup_time = Carbon::parse($order->updated_at)->format('d-m-Y h:i A');
    //         return $order;
    //     });

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Delivered to nearby branch orders retrieved successfully',
    //         'orders' => $orders
    //     ]);
    // }
    public function getTodaysPickedUpOrdersDeliveredtonearbybranch(Request $request)
    {
    $deliveryBoyId = $request->input('deliveryBoyId');

    if (!$deliveryBoyId) {
        return response()->json([
            'success' => false,
            'message' => 'Delivery Boy ID not provided'
        ], 400);
    }

    $deliveryBoy = DlyBoy::where('id', $deliveryBoyId)->first();
    if (!$deliveryBoy) {
        return response()->json([
            'success' => false,
            'message' => 'Delivery boy not found'
        ], 404);
    }

    $orders = Order::where('assign_to', $deliveryBoyId)
        ->where('order_status', 'Delivered to near by branch')
        ->get(['order_id', 'updated_at']);

    $orders->transform(function ($order) {
        $order->pickup_time = Carbon::parse($order->updated_at)->format('d-m-Y h:i A');
        return $order;
    });

    return response()->json([
        'success' => true,
        'message' => 'Delivered to nearby branch orders retrieved successfully',
        'orders' => $orders
    ]);
}

    


    
    
    // new code for mail otp sent 
    // public function markDeliveredToBranch(Request $request)
    // {
    //     $orderIds = $request->input('order_ids');
    //     $deliveryBoyId = $request->input('deliveryBoyId');

    //     // Validate required inputs
    //     if (!$orderIds || !$deliveryBoyId || !is_array($orderIds)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Required fields missing or invalid'
    //         ]);
    //     }

    //     // Get the delivery boy
    //     $deliveryBoy = DlyBoy::find($deliveryBoyId);
    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found'
    //         ]);
    //     }

    //     // Get all delivery branches
    //     $deliveryBranches = Branch::where('type', 'Delivery')->get();

    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $ordersWithBranches = [];
    //     $branchEmails = [];
    //     $branchOtps = [];

    //     foreach ($orderIds as $orderId) {
    //         $order = Order::where('order_id', $orderId)
    //             ->where('assign_to', $deliveryBoyId)
    //             ->where(function ($query) {
    //                 $query->whereRaw('LOWER(order_status) = ?', ['item picked up'])
    //                     ->orWhereRaw('LOWER(order_status) = ?', ['delivered to near by branch']);
    //             })
    //             ->first();

    //         if (!$order) {
    //             \Log::warning("No matching order found for ID: {$orderId} assigned to delivery boy: {$deliveryBoyId}");
    //             continue;
    //         }

    //         $matchingBranch = null;

    //         foreach ($deliveryBranches as $branch) {
    //             $branchPincodes = array_map('trim', explode(',', $branch->pincode));

    //             if (
    //                 (strtolower($order->order_status) === 'item picked up' && in_array($order->sender_pincode, $branchPincodes)) ||
    //                 (strtolower($order->order_status) === 'delivered to near by branch' && in_array($order->receiver_pincode, $branchPincodes))
    //             ) {
    //                 $matchingBranch = $branch;
    //                 break;
    //             }
    //         }
    //         // dd($matchingBranch);
            
    //         if ($matchingBranch) {
    //             if (!isset($branchOtps[$matchingBranch->id])) {
    //                 $branchOtps[$matchingBranch->id] = [
    //                     'otp' => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
    //                     'branch' => $matchingBranch,
    //                     'orders' => [],
    //                     'order_ids_numeric' => []
    //                 ];
    //             }

    //             $branchOtps[$matchingBranch->id]['orders'][] = $order;

    //             $numericOrderId = preg_replace('/[^0-9]/', '', $order->order_id);
    //             if (is_numeric($numericOrderId)) {
    //                 $branchOtps[$matchingBranch->id]['order_ids_numeric'][] = (int) $numericOrderId;
    //             }
    //         }
    //     }

    //     foreach ($branchOtps as $branchId => $data) {
    //         $branch = $data['branch'];
    //         $otp = $data['otp'];
    //         $orders = $data['orders'];
    //         $numericOrderIds = $data['order_ids_numeric'];

    //         $orderIdsSum = count($numericOrderIds) > 0 ? array_sum($numericOrderIds) : 0;

    //         foreach ($orders as $order) {
    //             $order->otp = $otp;
    //             $order->save();

    //             $ordersWithBranches[] = [
    //                 'order_id' => $order->order_id,
    //                 'branch_id' => $branch->id,
    //                 'branch_name' => $branch->fullname ?? 'Unknown Branch',
    //                 'branch_email' => $branch->email
    //             ];
    //         }

    //         if ($branch->email) {
    //             $branchEmails[] = $branch->email;
    //             $orderIdsStr = implode(', ', array_map(fn($o) => $o->order_id, $orders));

    //             $mailData = [
    //                 'title' => 'Order Delivery OTP',
    //                 'body' => "OTP for order confirmation: $otp\nOrder IDs: $orderIdsStr",
    //                 'otp' => "$otp - Your OTP for Order Delivery Verification",
    //                 'orders' => $orderIdsStr,
    //                 'order_ids_sum' => $orderIdsSum,
    //                 'order_count' => count($orders)
    //             ];

    //             try {
    //                 Mail::to($branch->email)->send(new SendOtp($mailData));
    //                 \Log::info("Email sent to {$branch->email} with OTP {$otp} for orders: {$orderIdsStr}, Count: " . count($orders) . ", Sum: {$orderIdsSum}");
    //             } catch (\Exception $e) {
    //                 \Log::error("Failed to send email to {$branch->email}: " . $e->getMessage());
    //             }
    //         } else {
    //             \Log::warning("Branch ID {$branch->id} has no email. OTP saved but not emailed.");
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'OTP generated for branch(es). Please verify OTP to complete the process.',
    //         'requires_otp' => true,
    //         'orders' => $ordersWithBranches,
    //         'branch_emails' => array_unique($branchEmails)
    //     ]);
    // }
    
    public function markDeliveredToBranch(Request $request)
{
    $orderIds = $request->input('order_ids');
    $deliveryBoyId = $request->input('deliveryBoyId');

    // Validate required inputs
    if (!$orderIds || !$deliveryBoyId || !is_array($orderIds)) {
        return response()->json([
            'success' => false,
            'message' => 'Required fields missing or invalid'
        ]);
    }

    // Get the delivery boy
    $deliveryBoy = DlyBoy::find($deliveryBoyId);
    if (!$deliveryBoy) {
        return response()->json([
            'success' => false,
            'message' => 'Delivery boy not found'
        ]);
    }

    // Get all delivery branches
    $deliveryBranches = Branch::where('type', 'Delivery')->get();

    $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    $ordersWithBranches = [];
    $branchEmails = [];
    $branchOtps = [];

    foreach ($orderIds as $orderId) {
        $order = Order::where('order_id', $orderId)
            ->where('assign_to', $deliveryBoyId)
            ->where(function ($query) {
                $query->whereRaw('LOWER(order_status) = ?', ['item picked up'])
                    ->orWhereRaw('LOWER(order_status) = ?', ['delivered to near by branch']);
            })
            ->first();

        if (!$order) {
            \Log::warning("No matching order found for ID: {$orderId} assigned to delivery boy: {$deliveryBoyId}");
            continue;
        }

        $matchingBranch = null;

        foreach ($deliveryBranches as $branch) {
            $branchPincodes = array_map('trim', explode(',', $branch->pincode));

            if (
                (strtolower($order->order_status) === 'item picked up' && in_array($order->sender_pincode, $branchPincodes)) ||
                (strtolower($order->order_status) === 'delivered to near by branch' && in_array($order->receiver_pincode, $branchPincodes))
            ) {
                $matchingBranch = $branch;
                break;
            }
        }
        
        if ($matchingBranch) {
            if (!isset($branchOtps[$matchingBranch->id])) {
                $branchOtps[$matchingBranch->id] = [
                    'otp' => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
                    'branch' => $matchingBranch,
                    'orders' => [],
                    'order_ids_numeric' => []
                ];
            }

            $branchOtps[$matchingBranch->id]['orders'][] = $order;

            $numericOrderId = preg_replace('/[^0-9]/', '', $order->order_id);
            if (is_numeric($numericOrderId)) {
                $branchOtps[$matchingBranch->id]['order_ids_numeric'][] = (int) $numericOrderId;
            }
        }
    }

    foreach ($branchOtps as $branchId => $data) {
        $branch = $data['branch'];
        $otp = $data['otp'];
        $orders = $data['orders'];
        $numericOrderIds = $data['order_ids_numeric'];

        $orderIdsSum = count($numericOrderIds) > 0 ? array_sum($numericOrderIds) : 0;

        foreach ($orders as $order) {
            $order->otp = $otp;
            // Update assign_by with branch ID when order status is 'delivered to near by branch'
            $order->assign_by = strtolower($order->order_status) === 'delivered to near by branch' ? $branch->id : $order->assign_by;
            $order->save();

            $ordersWithBranches[] = [
                'order_id' => $order->order_id,
                'branch_id' => $branch->id,
                'branch_name' => $branch->fullname ?? 'Unknown Branch',
                'branch_email' => $branch->email
            ];
        }

        if ($branch->email) {
            $branchEmails[] = $branch->email;
            $orderIdsStr = implode(', ', array_map(fn($o) => $o->order_id, $orders));

            $mailData = [
                'title' => 'Order Delivery OTP',
                'body' => "OTP for order confirmation: $otp\nOrder IDs: $orderIdsStr",
                'otp' => "$otp - Your OTP for Order Delivery Verification",
                'orders' => $orderIdsStr,
                'order_ids_sum' => $orderIdsSum,
                'order_count' => count($orders)
            ];

            try {
                Mail::to($branch->email)->send(new SendOtp($mailData));
                \Log::info("Email sent to {$branch->email} with OTP {$otp} for orders: {$orderIdsStr}, Count: " . count($orders) . ", Sum: {$orderIdsSum}");
            } catch (\Exception $e) {
                \Log::error("Failed to send email to {$branch->email}: " . $e->getMessage());
            }
        } else {
            \Log::warning("Branch ID {$branch->id} has no email. OTP saved but not emailed.");
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'OTP generated for branch(es). Please verify OTP to complete the process.',
        'requires_otp' => true,
        'orders' => $ordersWithBranches,
        'branch_emails' => array_unique($branchEmails)
    ]);
}


    // New method to verify OTP and complete the delivery process
    public function verifyOtpAndCompleteDelivery(Request $request)
    {
        $branchId = $request->input('branch_id');
        $enteredOtp = $request->input('otp');
        $deliveryBoyId = $request->input('deliveryBoyId');
        $orderIds = $request->input('order_ids', []);

        if (!$branchId || !$enteredOtp || !$deliveryBoyId || empty($orderIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Required fields missing'
            ]);
        }

        $branch = Branch::find($branchId);
        if (!$branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found'
            ]);
        }

        $firstOrder = Order::where('order_id', $orderIds[0])
            ->where('assign_to', $deliveryBoyId)
            ->first();

        if (!$firstOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        if ($firstOrder->otp != $enteredOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }

        $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
        $updatedCount = 0;

        foreach ($orderIds as $orderId) {
            $order = Order::where('order_id', $orderId)
                ->where('assign_to', $deliveryBoyId)
                ->where('otp', $enteredOtp)
                ->first();

            $orderHistory = new OrderHistory();
            if ($order) {
                $statusBefore = strtolower($order->order_status);
                $newStatus = '';

                if ($statusBefore === 'item picked up') {
                    $newStatus = 'Delivered to branch';
                    $orderHistory->status = $newStatus;
                } elseif ($statusBefore === 'delivered to near by branch') {
                    $newStatus = 'Out for Delivery to Origin';
                    $orderHistory->status = "Order Reached in Near By Hub";
                }

                if ($newStatus !== '') {
                    $order->order_status = $newStatus;
                    $order->assign_to = '';
                    $order->save();


                    $orderHistory->datetime = $currentDateTime;
                    $orderHistory->tracking_id = $orderId;
                    $orderHistory->save();

                    $updatedCount++;
                }
            }
        }

        if ($updatedCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No orders were updated. Please check order statuses.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $updatedCount . ' order(s) status updated successfully',
            'count' => $updatedCount
        ]);
    }


    // public function deliveryAssignAdd(Request $request)
    // {
    //     // dd($request->all());
    //     $id = Session::get('dlyId');
    //     $currentTime = now(); 

    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     if ($request->action == 'DirectOrders' || $request->action == 'delivery' || $request->action == 'Pickup') {
    //         $order = WebOrder::where('order_id', $request->orderIdedit)->first();
    //         $order->order_status = $request->deliverBoy;
    //         $order->status_message = $request->status_message ?? $request->Reason_message;

    //         // Calculate delivery time in minutes
    //         $createdTime = Carbon::parse($order->created_at);
    //         $deliveryTimeInMinutes = $createdTime->diffInMinutes($currentTime);
    //         $order->delivery_time = $deliveryTimeInMinutes;
    //         $order->save();

    //         $orderHistory = new OrderHistory();
    //         $orderHistory->datetime = $currentDateTime;
    //         $orderHistory->status = $request->deliverBoy;
    //         $orderHistory->tracking_id = $order->order_id;
    //         $orderHistory->save();

    //         // Create COD amount record if order is marked as Delivered
    //         if ($request->deliverBoy == 'Delivered' && $order->payment_mode == 'COD') {
    //             $codamount = new CodAmount();
    //             $codamount->amount = $order->codAmount;
    //             $codamount->delivery_boy_id = $order->assign_to;
    //             $codamount->user_id = $order->id;
    //             $codamount->type = 'Credited';
    //             $codamount->datetime = $currentDateTime;
    //             $codamount->save();

    //             // Add to COD wallet
    //             $this->updateCodWallet($order->assign_to, $order->codAmount);
    //         }

    //         $msg = 'Order status update!';
    //     } else {
    //         $order = Order::where('order_id', $request->orderIdedit)->first();
    //         $order->order_status = $request->deliverBoy;
    //         $order->status_message = $request->status_message ?? $request->Reason_message;
    //         $order->assign_by = $id;
    //         $order->updated_at = $this->date;  // updated at 2025-05-13 

    //         $orderHistory = new OrderHistory();
    //         $orderHistory->datetime = $currentDateTime;
    //         $orderHistory->status = $request->deliverBoy;
    //         $orderHistory->tracking_id = $order->order_id;
    //         $orderHistory->save();

    //         // Calculate delivery time in minutes
    //         $createdTime = Carbon::parse($order->created_at);
    //         $deliveryTimeInMinutes = $createdTime->diffInMinutes($currentTime);
    //         $order->delivery_time = $deliveryTimeInMinutes;

    //         $order->save();

    //         // Create COD amount record if order is marked as Delivered
    //         if ($request->deliverBoy == 'Delivered' && $order->payment_mode == 'COD') {
    //             $codamount = new CodAmount();
    //             $codamount->amount = $order->codAmount;
    //             $codamount->delivery_boy_id = $order->assign_to;
    //             $codamount->user_id = $order->id;
    //             $codamount->type = 'Credited';
    //             $codamount->datetime = $currentDateTime;
    //             $codamount->save();

    //             // Add to COD wallet
    //             $this->updateCodWallet($order->assign_to, $order->codAmount);
    //         }

    //         $msg = 'Order status update!';
    //     }
    //     $data = Order::where('seller_id', $order->seller_id)->orderBy('id', 'desc')->get();
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => $msg,
    //             'html' => view('deliveryBoy.inc.orderDetails', compact('data'))->render(),
    //         ]);
    //     }
    // }
    
    // public function deliveryAssignAdd(Request $request)
    // {
    //     // Get delivery boy ID from session
    //     $deliveryBoyId = Session::get('dlyId');
    //     if (!$deliveryBoyId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Session expired. Please log in again.',
    //         ], 401);
    //     }

    //     // Current date and time
    //     $currentTime = now();
    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

    //     // Fetch delivery boy and their pincode
    //     $deliveryBoy = DlyBoy::find($deliveryBoyId);
    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found.',
    //         ], 404);
    //     }

    //     $deliveryBoyPincodes = array_map('trim', explode(',', trim($deliveryBoy->pincode, ',')));
    //     $matchingBranchId = null;

    //     // Find branch with matching pincode
    //     if (!empty($deliveryBoyPincodes)) {
    //         $matchingBranch = Branch::where(function ($query) use ($deliveryBoyPincodes) {
    //             foreach ($deliveryBoyPincodes as $pincode) {
    //                 $query->orWhere('pincode', 'like', "%$pincode%");
    //             }
    //         })->where('type', 'Delivery')->first();

    //         $matchingBranchId = $matchingBranch ? $matchingBranch->id : null;
    //     }

    //     if ($request->action == 'DirectOrders' || $request->action == 'delivery' || $request->action == 'Pickup') {
    //         // Handle WebOrder
    //         $order = WebOrder::where('order_id', $request->orderIdedit)->first();
    //         if (!$order) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Order not found.',
    //             ], 404);
    //         }

    //         // Update order details
    //         $order->order_status = $request->deliverBoy;
    //         $order->status_message = $request->status_message ?? $request->Reason_message;
    //         $order->assign_by = $matchingBranchId ?? $deliveryBoyId; // Use branch ID if found, else delivery boy ID

    //         // Calculate delivery time in minutes
    //         $createdTime = Carbon::parse($order->created_at);
    //         $deliveryTimeInMinutes = $createdTime->diffInMinutes($currentTime);
    //         $order->delivery_time = $deliveryTimeInMinutes;

    //         $order->save();

    //         // Create order history
    //         $orderHistory = new OrderHistory();
    //         $orderHistory->datetime = $currentDateTime;
    //         $orderHistory->status = $request->deliverBoy;
    //         $orderHistory->tracking_id = $order->order_id;
    //         $orderHistory->save();

    //         // Handle COD for Delivered orders
    //         if ($request->deliverBoy == 'Delivered' && $order->payment_mode == 'COD') {
    //             // Create COD amount record
    //             $codamount = new CodAmount();
    //             $codamount->amount = $order->codAmount;
    //             $codamount->delivery_boy_id = $order->assign_to;
    //             $codamount->user_id = $order->id;
    //             $codamount->type = 'Credited';
    //             $codamount->datetime = $currentDateTime;
    //             $codamount->save();

    //             // Inline COD wallet update
    //             $wallet = CodWallet::firstOrCreate(
    //                 ['delivery_boy_id' => $order->assign_to],
    //                 ['balance' => 0]
    //             );
    //             $wallet->balance += $order->codAmount;
    //             $wallet->save();
    //         }

    //         $msg = 'Order status updated!';
    //     } else {
    //         // Handle Order
    //         $order = Order::where('order_id', $request->orderIdedit)->first();
    //         if (!$order) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Order not found.',
    //             ], 404);
    //         }

    //         // Update order details
    //         $order->order_status = $request->deliverBoy;
    //         $order->status_message = $request->status_message ?? $request->Reason_message;
    //         $order->assign_by = $matchingBranchId ?? $deliveryBoyId; // Use branch ID if found, else delivery boy ID
    //         $order->updated_at = $currentTime;

    //         // Calculate delivery time in minutes
    //         $createdTime = Carbon::parse($order->created_at);
    //         $deliveryTimeInMinutes = $createdTime->diffInMinutes($currentTime);
    //         $order->delivery_time = $deliveryTimeInMinutes;

    //         $order->save();

    //         // Create order history
    //         $orderHistory = new OrderHistory();
    //         $orderHistory->datetime = $currentDateTime;
    //         $orderHistory->status = $request->deliverBoy;
    //         $orderHistory->tracking_id = $order->order_id;
    //         $orderHistory->save();

    //         // Handle COD for Delivered orders
    //         if ($request->deliverBoy == 'Delivered' && $order->payment_mode == 'COD') {
    //             // Create COD amount record
    //             $codamount = new CodAmount();
    //             $codamount->amount = $order->codAmount;
    //             $codamount->delivery_boy_id = $order->assign_to;
    //             $codamount->user_id = $order->id;
    //             $codamount->type = 'Credited';
    //             $codamount->datetime = $currentDateTime;
    //             $codamount->save();

    //             // Inline COD wallet update
    //             $wallet = CodWallet::firstOrCreate(
    //                 ['delivery_boy_id' => $order->assign_to],
    //                 ['balance' => 0]
    //             );
    //             $wallet->balance += $order->codAmount;
    //             $wallet->save();
    //         }

    //         $msg = 'Order status updated!';
    //     }

    //     // Fetch updated orders for the seller
    //     $data = Order::where('seller_id', $order->seller_id)->orderBy('id', 'desc')->get();

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => $msg,
    //             'html' => view('deliveryBoy.inc.orderDetails', compact('data'))->render(),
    //         ]);
    //     }
    // }
    
    
    
    // 3 june
    // public function deliveryAssignAdd(Request $request)
    // {
    //     // Get delivery boy ID from session
    //     $deliveryBoyId = Session::get('dlyId');
    //     if (!$deliveryBoyId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Session expired. Please log in again.',
    //         ], 401);
    //     }

    //     // Current date and time
    //     $currentTime = now();
    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

    //     // Fetch delivery boy and their pincode
    //     $deliveryBoy = DlyBoy::find($deliveryBoyId);
    //     if (!$deliveryBoy) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery boy not found.',
    //         ], 404);
    //     }

    //     $deliveryBoyPincodes = array_map('trim', explode(',', trim($deliveryBoy->pincode, ',')));
    //     $matchingBranchId = null;

    //     // Find branch with matching pincode
    //     if (!empty($deliveryBoyPincodes)) {
    //         $matchingBranch = Branch::where(function ($query) use ($deliveryBoyPincodes) {
    //             foreach ($deliveryBoyPincodes as $pincode) {
    //                 $query->orWhere('pincode', 'like', "%$pincode%");
    //             }
    //         })->where('type', 'Delivery')->first();

    //         $matchingBranchId = $matchingBranch ? $matchingBranch->id : null;
    //     }

    //     try {
    //         // Start database transaction
    //         DB::beginTransaction();

    //         if (in_array($request->action, ['DirectOrders', 'delivery', 'Pickup'])) {
    //             // Handle WebOrder
    //             $order = WebOrder::where('order_id', $request->orderIdedit)->first();
    //             if (!$order) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Order not found.',
    //                 ], 404);
    //             }

    //             // Validate COD data early if Delivered
    //             if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
    //                 if (!$order->assign_to || !is_numeric($order->codAmount) || $order->codAmount <= 0) {
    //                     Log::error('Invalid COD data for WebOrder', [
    //                         'order_id' => $order->order_id,
    //                         'assign_to' => $order->assign_to,
    //                         'codAmount' => $order->codAmount,
    //                     ]);
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Invalid COD data for order.',
    //                     ], 422);
    //                 }
    //             }

    //             // Update order details
    //             $order->order_status = $request->deliverBoy;
    //             $order->status_message = $request->status_message ?? $request->Reason_message ?? '';
    //             $order->assign_by = $matchingBranchId ?? $deliveryBoyId;

    //             // Calculate delivery time in minutes
    //             $createdTime = Carbon::parse($order->created_at);
    //             $deliveryTimeInMinutes = $createdTime->diffInMinutes($currentTime);
    //             $order->delivery_time = $deliveryTimeInMinutes;
    //             $order->updated_at = $this->date;  // addon this 
    //             // Save order
    //             $order->save();

    //             // Create order history
    //             $orderHistory = new OrderHistory();
    //             $orderHistory->datetime = $currentDateTime;
    //             $orderHistory->status = $request->deliverBoy;
    //             $orderHistory->tracking_id = $order->order_id;
    //             $orderHistory->save();

    //             // Handle COD for Delivered orders
    //             if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
    //                 // Create COD amount record
    //                 $codamount = new CodAmount();
    //                 $codamount->amount = $order->codAmount;
    //                 $codamount->delivery_boy_id = $order->assign_to;
    //                 $codamount->user_id = $order->id;
    //                 $codamount->type = 'Credited';
    //                 $codamount->datetime = $currentDateTime;
    //                 $codamount->save();

    //                 // Update COD wallet
    //                 $wallet = CodWallet::firstOrCreate(
    //                     ['delivery_boy_id' => $order->assign_to],
    //                     ['amount' => 0]
    //                 );
    //                 $wallet->balance += $order->codAmount;
    //                 $wallet->save();
    //             }

    //             $msg = 'Order status updated!';
    //         } else {
    //             // Handle Order (includes QR code updates)
    //             $order = Order::where('order_id', $request->orderIdedit)->first();
    //             if (!$order) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Order not found.',
    //                 ], 404);
    //             }

    //             // Validate status transition for QR code updates
    //             if ($request->action === 'qrcode') {
    //                 $allowedTransitions = [
    //                     'Item Picked Up' => ['Delivered to branch'],
    //                     'Delivered to branch' => ['Out for Delivery to Origin'],
    //                     'Out for Delivery to Origin' => ['Delivered', 'Not Delivered'],
    //                     'Item Not Picked Up' => ['Item Picked Up'],
    //                     'Not Delivered' => ['Delivered'],
    //                     'Booked' => ['Item Picked Up', 'Item Not Picked Up'],
    //                 ];

    //                 if (!isset($allowedTransitions[$order->order_status]) || !in_array($request->deliverBoy, $allowedTransitions[$order->order_status])) {
    //                     Log::error('Invalid status transition for QR code update', [
    //                         'order_id' => $order->order_id,
    //                         'current_status' => $order->order_status,
    //                         'requested_status' => $request->deliverBoy,
    //                     ]);
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Invalid status transition for this order.',
    //                     ], 422);
    //                 }
    //             }

    //             // Validate COD data early if Delivered
    //             if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
    //                 if (!$order->assign_to || !is_numeric($order->codAmount) || $order->codAmount <= 0) {
    //                     Log::error('Invalid COD data for Order', [
    //                         'order_id' => $order->order_id,
    //                         'assign_to' => $order->assign_to,
    //                         'codAmount' => $order->codAmount,
    //                     ]);
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Invalid COD data for order.',
    //                     ], 422);
    //                 }
    //             }

    //             // Update order details
    //             $order->order_status = $request->deliverBoy;
    //             $order->status_message = $request->status_message ?? $request->Reason_message ?? '';
    //             $order->assign_by = $matchingBranchId ?? $deliveryBoyId;
    //             // $order->updated_at = $currentTime;
    //             $order->updated_at = $this->date;

    //             // Calculate delivery time in minutes
    //             $createdTime = Carbon::parse($order->created_at);
    //             $deliveryTimeInMinutes = $createdTime->diffInMinutes($currentTime);
    //             $order->delivery_time = $deliveryTimeInMinutes;

    //             // Save order
    //             $order->save();

    //             // Create order history
    //             $orderHistory = new OrderHistory();
    //             $orderHistory->datetime = $currentDateTime;
    //             $orderHistory->status = $request->deliverBoy;
    //             $orderHistory->tracking_id = $order->order_id;
    //             $orderHistory->save();

    //             // Handle COD for Delivered orders
    //             if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
    //                 // Create COD amount record
    //                 $codamount = new CodAmount();
    //                 $codamount->amount = $order->codAmount;
    //                 $codamount->delivery_boy_id = $order->assign_to;
    //                 $codamount->user_id = $order->id;
    //                 $codamount->type = 'Credited';
    //                 $codamount->datetime = $currentDateTime;
    //                 $codamount->save();

    //                 // Update COD wallet
    //                 $wallet = CodWallet::firstOrCreate(
    //                     ['delivery_boy_id' => $order->assign_to],
    //                     ['amount' => 0]
    //                 );
    //                 $wallet->amount += $order->codAmount;
    //                 $wallet->save();
    //             }

    //             $msg = 'Order status updated!';
    //         }

    //         // Fetch updated orders for the seller
    //         $data = Order::where('seller_id', $order->seller_id)->orderBy('id', 'desc')->get();

    //         // Commit transaction
    //         DB::commit();

    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => $msg,
    //                 'html' => view('deliveryBoy.inc.orderDetails', compact('data'))->render(),
    //             ]);
    //         }
    //     } catch (\Exception $e) {
    //         // Roll back transaction
    //         DB::rollBack();

    //         Log::error('Error in deliveryAssignAdd', [
    //             'error' => $e->getMessage(),
    //             'order_id' => $request->orderIdedit,
    //             'action' => $request->action,
    //             'deliverBoy' => $request->deliverBoy,
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while updating the order status: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    // 19 june 
//     public function deliveryAssignAdd(Request $request)
//     {
//         $deliveryBoyId = Session::get('dlyId');
//         if (!$deliveryBoyId) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Session expired. Please log in again.',
//             ], 401);
//         }
    
//         $currentTime = now();
//         $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    
//         $deliveryBoy = DlyBoy::find($deliveryBoyId);
//         if (!$deliveryBoy) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Delivery boy not found.',
//             ], 404);
//         }
    
//         $deliveryBoyPincodes = array_map('trim', explode(',', trim($deliveryBoy->pincode, ',')));
//         $matchingBranchId = null;
    
//         if (!empty($deliveryBoyPincodes)) {
//             $matchingBranch = Branch::where(function ($query) use ($deliveryBoyPincodes) {
//                 foreach ($deliveryBoyPincodes as $pincode) {
//                     $query->orWhere('pincode', 'like', "%$pincode%");
//                 }
//             })->where('type', 'Delivery')->first();
    
//             $matchingBranchId = $matchingBranch ? $matchingBranch->id : null;
//         }
    
//         try {
//             DB::beginTransaction();
    
//             if (in_array($request->action, ['DirectOrders', 'delivery', 'Pickup'])) {
//                 $order = WebOrder::where('order_id', $request->orderIdedit)->first();
//                 if (!$order) {
//                     return response()->json([
//                         'success' => false,
//                         'message' => 'Order not found.',
//                     ], 404);
//                 }
    
//                 if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
//                     if (!$order->assign_to || !is_numeric($order->codAmount) || $order->codAmount <= 0) {
//                         Log::error('Invalid COD data for WebOrder', [
//                             'order_id' => $order->order_id,
//                             'assign_to' => $order->assign_to,
//                             'codAmount' => $order->codAmount,
//                         ]);
//                         return response()->json([
//                             'success' => false,
//                             'message' => 'Invalid COD data for order.',
//                         ], 422);
//                     }
//                 }
    
//                 $order->order_status = $request->deliverBoy;
//                 $order->status_message = $request->status_message ?? $request->Reason_message ?? '';
//                 $order->assign_by = $matchingBranchId ?? $deliveryBoyId;
    
//                 $createdTime = Carbon::parse($order->created_at);
//                 $deliveryTimeInMinutes = $createdTime->diffInMinutes($currentTime);
//                 $order->delivery_time = $deliveryTimeInMinutes;
//                 $order->updated_at = $this->date;
    
//                 $order->save();
    
//                 $orderHistory = new OrderHistory();
//                 $orderHistory->datetime = $currentDateTime;
//                 $orderHistory->status = $request->deliverBoy;
//                 $orderHistory->tracking_id = $order->order_id;
//                 $orderHistory->save();
    
//                 if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
//                     // COD amount credit entry
//                     $codamount = new CodAmount();
//                     $codamount->amount = $order->codAmount;
//                     $codamount->delivery_boy_id = $order->assign_to;
//                     $codamount->user_id = $order->id;
//                     $codamount->type = 'Credited';
//                     $codamount->datetime = $currentDateTime;
//                     $codamount->save();
    
//                     // Update CodWallet amount
//                     $wallet = CodWallet::firstOrCreate(
//                         ['delivery_boy_id' => $order->assign_to],
//                         ['amount' => 0]
//                     );
//                     $wallet->amount += $order->codAmount;
//                     $wallet->save();
    
//                     // Update COD Seller Amount total logic
//                     $userId = $order->seller_primary_id;
    
//                     if (!empty($userId)) {
//                         $codAmount = is_numeric($order->codAmount) ? (float)$order->codAmount : 0;
    
//                         // Get existing total for this user
//                         $existingWallet = CodSellerAmount::where('userid', $userId)
//                             ->orderByDesc('id')
//                             ->first();
    
//                         $totalPrev = is_numeric($existingWallet->total ?? null) ? (float)$existingWallet->total : 0;
//                         $newTotal = $totalPrev + $codAmount;
    
//                         CodSellerAmount::create([
//                             'userid'   => $userId,
//                             'c_amount' => $codAmount,
//                             'd_amount' => 0,
//                             'total'    => $newTotal,
//                             'datetime' => now('Asia/Kolkata')->format('Y-m-d H:i:s'),
//                             'status'   => 'success',
//                             'adminid'  => null,
//                             'refno'    => null,
//                             'msg'      => 'Cod amount after delivery',
//                         ]);
//                     } else {
//                         Log::error('seller_primary_id missing for order', ['order_id' => $order->order_id]);
//                     }
//                 }
    
//                 $msg = 'Order status updated!';
//             } else {
//                 $order = Order::where('order_id', $request->orderIdedit)->first();
//                 if (!$order) {
//                     return response()->json([
//                         'success' => false,
//                         'message' => 'Order not found.',
//                     ], 404);
//                 }
    
//                 if ($request->action === 'qrcode') {
//                     $allowedTransitions = [
//                         'Item Picked Up' => ['Delivered to branch'],
//                         'Delivered to branch' => ['Out for Delivery to Origin'],
//                         'Out for Delivery to Origin' => ['Delivered', 'Not Delivered'],
//                         'Item Not Picked Up' => ['Item Picked Up'],
//                         'Not Delivered' => ['Delivered'],
//                         'Booked' => ['Item Picked Up', 'Item Not Picked Up'],
//                     ];
    
//                     if (!isset($allowedTransitions[$order->order_status]) || !in_array($request->deliverBoy, $allowedTransitions[$order->order_status])) {
//                         Log::error('Invalid status transition for QR code update', [
//                             'order_id' => $order->order_id,
//                             'current_status' => $order->order_status,
//                             'requested_status' => $request->deliverBoy,
//                         ]);
//                         return response()->json([
//                             'success' => false,
//                             'message' => 'Invalid status transition for this order.',
//                         ], 422);
//                     }
//                 }
    
//                 if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
//                     if (!$order->assign_to || !is_numeric($order->codAmount) || $order->codAmount <= 0) {
//                         Log::error('Invalid COD data for Order', [
//                             'order_id' => $order->order_id,
//                             'assign_to' => $order->assign_to,
//                             'codAmount' => $order->codAmount,
//                         ]);
//                         return response()->json([
//                             'success' => false,
//                             'message' => 'Invalid COD data for order.',
//                         ], 422);
//                     }
//                 }
    
//                 $order->order_status = $request->deliverBoy;
//                 $order->status_message = $request->status_message ?? $request->Reason_message ?? '';
//                 $order->assign_by = $matchingBranchId ?? $deliveryBoyId;
//                 $order->updated_at = now();
    
//                 $createdTime = Carbon::parse($order->created_at);
//                 $deliveryTimeInMinutes = $createdTime->diffInMinutes($currentTime);
//                 $order->delivery_time = $deliveryTimeInMinutes;
    
//                 $order->save();
    
//                 $orderHistory = new OrderHistory();
//                 $orderHistory->datetime = $currentDateTime;
//                 $orderHistory->status = $request->deliverBoy;
//                 $orderHistory->tracking_id = $order->order_id;
//                 $orderHistory->save();
    
//                 if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
//                     $codamount = new CodAmount();
//                     $codamount->amount = $order->codAmount;
//                     $codamount->delivery_boy_id = $order->assign_to;
//                     $codamount->user_id = $order->id;
//                     $codamount->type = 'Credited';
//                     $codamount->datetime = $currentDateTime;
//                     $codamount->save();
    
//                     $wallet = CodWallet::firstOrCreate(
//                         ['delivery_boy_id' => $order->assign_to],
//                         ['amount' => 0]
//                     );
//                     $wallet->amount += $order->codAmount;
//                     $wallet->save();
    
//                     $userId = $order->seller_primary_id;
    
//                     if (!empty($userId)) {
//                         $codAmount = is_numeric($order->codAmount) ? (float)$order->codAmount : 0;
    
//                         $existingWallet = CodSellerAmount::where('userid', $userId)
//                             ->orderByDesc('id')
//                             ->first();
    
//                         $totalPrev = is_numeric($existingWallet->total ?? null) ? (float)$existingWallet->total : 0;
//                         $newTotal = $totalPrev + $codAmount;
    
//                         CodSellerAmount::create([
//                             'userid'   => $userId,
//                             'c_amount' => $codAmount,
//                             'd_amount' => 0,
//                             'total'    => $newTotal,
//                             'datetime' => now('Asia/Kolkata')->format('Y-m-d H:i:s'),
//                             'status'   => 'success',
//                             'adminid'  => null,
//                             'refno'    => null,
//                             'msg'      => 'Cod amount after delivery',
//                         ]);
//                     } else {
//                         Log::error('seller_primary_id missing for order', ['order_id' => $order->order_id]);
//                     }
//                 }
    
//                 $msg = 'Order status updated!';
//             }
    
//             $data = Order::where('seller_id', $order->seller_id)->orderBy('id', 'desc')->get();
    
//             DB::commit();
    
//             if ($request->ajax()) {
//                 return response()->json([
//                     'success' => true,
//                     'message' => $msg,
//                     'html' => view('deliveryBoy.inc.orderDetails', compact('data'))->render(),
//                 ]);
//             }
//         } catch (\Exception $e) {
//             DB::rollBack();
    
//             Log::error('Error in deliveryAssignAdd', [
//                 'error' => $e->getMessage(),
//                 'order_id' => $request->orderIdedit,
//                 'action' => $request->action,
//                 'deliverBoy' => $request->deliverBoy,
//                 'trace' => $e->getTraceAsString(),
//             ]);
    
//             return response()->json([
//                 'success' => false,
//                 'message' => 'An error occurred while updating the order status: ' . $e->getMessage(),
//             ], 500);
//         }
// }




public function deliveryAssignAdd(Request $request)
{
    $deliveryBoyId = Session::get('dlyId');
    if (!$deliveryBoyId) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please log in again.',
        ], 401);
    }

    $currentTime = now();
    $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

    $deliveryBoy = DlyBoy::find($deliveryBoyId);
    if (!$deliveryBoy) {
        return response()->json([
            'success' => false,
            'message' => 'Delivery boy not found.',
        ], 404);
    }

    $deliveryBoyPincodes = array_map('trim', explode(',', trim($deliveryBoy->pincode, ',')));
    $matchingBranchId = null;

    if (!empty($deliveryBoyPincodes)) {
        $matchingBranch = Branch::where(function ($query) use ($deliveryBoyPincodes) {
            foreach ($deliveryBoyPincodes as $pincode) {
                $query->orWhere('pincode', 'like', "%$pincode%");
            }
        })->where('type', 'Delivery')->first();

        $matchingBranchId = $matchingBranch ? $matchingBranch->id : null;
    }

    try {
        DB::beginTransaction();

        $model = in_array($request->action, ['DirectOrders', 'delivery', 'Pickup']) ? WebOrder::class : Order::class;
        $order = $model::where('order_id', $request->orderIdedit)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if ($request->action === 'qrcode') {
            $allowedTransitions = [
                'Item Picked Up' => ['Delivered to branch'],
                'Delivered to branch' => ['Out for Delivery to Origin'],
                'Out for Delivery to Origin' => ['Delivered', 'Not Delivered'],
                'Item Not Picked Up' => ['Item Picked Up'],
                'Not Delivered' => ['Delivered'],
                'Booked' => ['Item Picked Up', 'Item Not Picked Up'],
            ];
            if (!isset($allowedTransitions[$order->order_status]) || !in_array($request->deliverBoy, $allowedTransitions[$order->order_status])) {
                Log::error('Invalid status transition for QR code update', [
                    'order_id' => $order->order_id,
                    'current_status' => $order->order_status,
                    'requested_status' => $request->deliverBoy,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition for this order.',
                ], 422);
            }
        }

        if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
            if (!$order->assign_to || !is_numeric($order->codAmount) || $order->codAmount <= 0) {
                Log::error('Invalid COD data', [
                    'order_id' => $order->order_id,
                    'assign_to' => $order->assign_to,
                    'codAmount' => $order->codAmount,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid COD data for order.',
                ], 422);
            }
        }

        $order->order_status = $request->deliverBoy;
        $order->status_message = $request->status_message ?? $request->Reason_message ?? '';
        $order->assign_by = $matchingBranchId ?? $deliveryBoyId;
        $order->updated_at = now();

        $createdTime = Carbon::parse($order->created_at);
        $order->delivery_time = $createdTime->diffInMinutes($currentTime);

        $order->save();

        OrderHistory::create([
            'datetime' => $currentDateTime,
            'status' => $request->deliverBoy,
            'tracking_id' => $order->order_id,
        ]);

        if ($request->deliverBoy === 'Delivered' && $order->payment_mode === 'COD') {
            CodAmount::create([
                'amount' => $order->codAmount,
                'delivery_boy_id' => $order->assign_to,
                'user_id' => $order->id,
                'type' => 'Credited',
                'datetime' => $currentDateTime,
            ]);

            $wallet = CodWallet::firstOrCreate(
                ['delivery_boy_id' => $order->assign_to],
                ['amount' => 0]
            );
            $wallet->amount += $order->codAmount;
            $wallet->save();

            $userId = $order->seller_primary_id;
            if ($userId) {
                $codAmount = (float) $order->codAmount;
                $existingWallet = CodSellerAmount::where('userid', $userId)->orderByDesc('id')->first();
                $newTotal = ($existingWallet->total ?? 0) + $codAmount;

                CodSellerAmount::create([
                    'userid' => $userId,
                    'c_amount' => $codAmount,
                    'd_amount' => 0,
                    'total' => $newTotal,
                    'datetime' => now('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'status' => 'success',
                    'adminid' => null,
                    'refno' => null,
                    'msg' => 'Cod amount after delivery',
                ]);
            }
        }

        // ✅ Email Sending with Safe Log
        if ($request->deliverBoy === 'Delivered') {
            $mailData = [
                'title' => 'Order Delivered Successfully',
                'order_id' => $order->order_id,
                'price' => $order->price ?? $order->codAmount ?? 0,
                'payment_mode' => $order->payment_mode,
                'sender_name' => $order->sender_name ?? '',
                'sender_number' => $order->sender_number ?? '',
                'sender_email' => $order->sender_email ?? '',
                'sender_address' => $order->sender_address ?? '',
                'sender_pincode' => $order->sender_pincode ?? '',
                'receiver_name' => $order->receiver_name ?? '',
                'receiver_cnumber' => $order->receiver_cnumber ?? '',
                'receiver_email' => $order->receiver_email ?? '',
                'receiver_add' => $order->receiver_add ?? '',
                'receiver_pincode' => $order->receiver_pincode ?? '',
                'datetime' => now('Asia/Kolkata')->format('d-m-Y | h:i:s A'),
            ];

            register_shutdown_function(function () use ($order, $mailData) {
                try {
                    $recipients = array_filter([$order->sender_email, $order->receiver_email]);
                    if (!empty($recipients)) {
                        Mail::to($recipients)->send(new \App\Mail\OrderDeliveredConfirmation($mailData));
                        \Log::info("Booking confirmation email sent to " . implode(', ', $recipients) . " for order ID: {$order->order_id}");
                    } else {
                        \Log::warning("No valid email addresses provided for order ID: {$order->order_id}");
                    }
                } catch (\Exception $e) {
                    \Log::error("Failed to send booking confirmation email: " . $e->getMessage());
                }
            });
        }

        $data = Order::where('seller_id', $order->seller_id)->orderBy('id', 'desc')->get();

        DB::commit();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated!',
                'html' => view('deliveryBoy.inc.orderDetails', compact('data'))->render(),
            ]);
        }
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in deliveryAssignAdd', [
            'error' => $e->getMessage(),
            'order_id' => $request->orderIdedit,
            'action' => $request->action,
            'deliverBoy' => $request->deliverBoy,
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the order status: ' . $e->getMessage(),
        ], 500);
    }
}

    



   

  
  
  
  
  
    

    /**
     * Update or create COD wallet entry for a delivery boy
     * 
     * @param int $deliveryBoyId
     * @param float $amount
     * @return void
     */
    private function updateCodWallet($deliveryBoyId, $amount)
    {
        // Check if delivery boy already has a wallet entry
        $codWallet = CodWallet::where('delivery_boy_id', $deliveryBoyId)->first();

        if ($codWallet) {
            $codWallet->amount = $codWallet->amount + $amount;
            $codWallet->save();
        } else {
            // Create new wallet entry
            $codWallet = new CodWallet();
            $codWallet->delivery_boy_id = $deliveryBoyId;
            $codWallet->amount = $amount;
            $codWallet->save();
        }
    }


    public function getBookedSuperExpressOrders()
    {
        $id = Session::get('dlyId');

        $orders = Order::where('service_type', 'SuperExpress')
            ->where('order_status', 'Booked')
            ->where('assign_to', $id)
            ->select('id', 'created_at as booked_at')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    /**
     * Check status of super express orders
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSuperExpressOrderStatus()
    {
        $id = Session::get('dlyId');

        // Get all delivered super express orders from today
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');

        $deliveredOrders = Order::where('service_type', 'SuperExpress')
            ->where('datetime', 'like', $dateTime . '%')
            ->where('order_status', 'Delivered')
            ->where('assign_to', $id)
            ->select('id')
            ->get();

        // Count pending super express orders
        $pendingSuperExpressCount = Order::where('service_type', 'SuperExpress')
            ->where('order_status', '!=', 'Delivered')
            ->where('assign_to', $id)
            ->count();

        return response()->json([
            'success' => true,
            'deliveredOrders' => $deliveredOrders,
            'pendingSuperExpressCount' => $pendingSuperExpressCount
        ]);
    }


    public function saveDeliveryTime(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_time' => 'required|string',
            'delivery_time_seconds' => 'required|integer'
        ]);

        try {
            // Find the order
            $order = Order::findOrFail($request->order_id);

            // Update the delivery time
            $order->delivery_time = $request->delivery_time;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Delivery time saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save delivery time: ' . $e->getMessage()
            ], 500);
        }
    }



}

