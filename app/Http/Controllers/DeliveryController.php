<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\AdminCodHistory;
use App\Models\AdminTotalCod;
use App\Models\Branch;
use App\Models\BranchCodHistory;
use App\Models\BranchtotalCod;
use App\Models\COD;
use App\Models\CodAmount;
use App\Models\CodWallet;
use Illuminate\Support\Facades\Log;
use App\Models\DlyBoy;
use App\Models\Order;
use App\Models\OrderCod;
use App\Models\PinCode;
use App\Models\Service;
use App\Models\Servicetype;
use App\Models\Wallet;
use App\Models\OrderHistory;
use App\Models\WebOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    protected $date;
    public function __construct()
    {
        $kolkataDateTime  = Carbon::now('Asia/Kolkata');
        $this->date       = $kolkataDateTime->format('Y-m-d H:i:s');
    }
    
   
    
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

  



  
    
//     public function dashboard()
//     {
//     $id = Session::get('dyid');
//     $delivery = Branch::find($id);
//     $pinCodes = explode(',', trim($delivery->pincode, ','));

//     // Store branch pincodes in session for use in blade template
//     Session::put('branch_pincodes', $delivery->pincode);

//     $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');

//     // Sender branch orders (where transfer_other_branch = false)
//     $senderOrdersQuery = Order::where('datetime', 'like', $dateTime . '%')
//         ->where('transfer_other_branch', 'false')
//         ->whereIn('sender_pincode', $pinCodes);
        
//     $superexp = Order::where('datetime', 'like', $dateTime . '%')
//         ->where('transfer_other_branch', 'false')
//         ->whereIn('sender_pincode', $pinCodes);

//     // Receiver branch orders (where transfer_other_branch = true)
//     $receiverOrdersQuery = Order::where('datetime', 'like', $dateTime . '%')
//         ->where('transfer_other_branch', 'true')
//         ->where('order_status', '!=', 'Delivered to near by branch')
//         ->whereIn('receiver_pincode', $pinCodes);

//     $receiverTransferOtherBranchOrdersQuery = Order::where('datetime', 'like', $dateTime . '%')
//         ->where('transfer_other_branch', 'true')
//         ->whereIn('receiver_pincode', $pinCodes);

//     // Orders transferred OUT from this branch (to different branches)
//     $outgoingTransfersQuery = Order::where('datetime', 'like', $dateTime . '%')
//         ->where('transfer_other_branch', 'true')
//         ->whereIn('sender_pincode', $pinCodes)
//         ->whereNotIn('receiver_pincode', $pinCodes); // Exclude transfers within same branch

//     // Other branch orders query (sender is this branch, receiver is different branch)
//     $otherBranchOrdersQuery = Order::where('datetime', 'like', $dateTime . '%')
//         ->where('transfer_other_branch', 'false')
//         ->whereIn('sender_pincode', $pinCodes)
//         ->whereNotIn('receiver_pincode', $pinCodes) // Exclude orders within same branch
//         ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered','Cancelled']); // Exclude Booked and Item Picked Up

//     // Total other branch orders query (all-time, sender is this branch, receiver is different branch)
//     $totalOtherBranchOrdersQuery = Order::where('transfer_other_branch', 'false')
//         ->whereIn('sender_pincode', $pinCodes)
//         ->whereNotIn('receiver_pincode', $pinCodes) // Exclude orders within same branch
//         ->whereNotIn('order_status', ['Booked', 'Item Picked Up','Delivered','Item Not Picked Up','Cancelled']); // Exclude Booked and Item Picked Up

//     // Same branch order show 
//     $myBranchOrdersQuery = Order::where('datetime', 'like', $dateTime . '%')
//         ->whereIn('sender_pincode', $pinCodes)
//         ->whereIn('receiver_pincode', $pinCodes) // Include orders within same branch
//         ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered','Cancelled']); // Exclude Booked and Item Picked Up

//     // Today's counts for sender branch (transfer_other_branch = false)
//     $todaySenderOrders = $senderOrdersQuery->count();
//     $todaySenderPendingOrders = (clone $senderOrdersQuery)->where(function ($query) {
//         $query->where('order_status', 'Booked')
//               ->orWhere('order_status', 'Item Not Picked Up');
//     })->count();
//     $todaySenderProcessingOrders = (clone $senderOrdersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch', 'Item Not Picked Up'])->count();
//     $todaySenderCompletedOrders = (clone $senderOrdersQuery)->where('order_status', 'Delivered')->count();
//     $todaySenderCancelledOrders = (clone $senderOrdersQuery)->where('order_status', 'Cancelled')->count();

//     // Today's counts for receiver branch (transfer_other_branch = true)
//     $todayReceiverOrders = $receiverOrdersQuery->count();
//     $todayReceiverPendingOrders = (clone $receiverOrdersQuery)->where(function ($query) {
//         $query->where('order_status', 'Booked')
//               ->orWhere('order_status', 'Item Not Picked Up');
//     })->count();
//     $todayReceiverProcessingOrders = (clone $receiverOrdersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch', 'Item Not Picked Up'])->count();
//     $todayReceiverCompletedOrders = (clone $receiverOrdersQuery)->where('order_status', 'Delivered')->count();
//     $todayReceiverCancelledOrders = (clone $receiverOrdersQuery)->where('order_status', 'Cancelled')->count();

//     // Delivered to nearby branch
//     $todayDeliveredToNearbyBranchOrders = (clone $receiverTransferOtherBranchOrdersQuery)->where('order_status', 'Delivered to near by branch')->orWhere('order_status', 'Out for Delivery to Origin')->count();
//     $todayPendingToReceiveOtherBranchOrders = (clone $receiverTransferOtherBranchOrdersQuery)->whereNotIn('order_status', ['Delivered to near by branch', 'Delivered','Out for Delivery to Origin'])->count();

//     // Count of outgoing transfers (orders transferred OUT from this branch to different branches)
//     $totaltodaytransferOrder = $outgoingTransfersQuery->count();

//     // Count of orders where sender is this branch, receiver is different branch
//     $toDayOtherBranchOrder = $otherBranchOrdersQuery->count();
//     $toDayMyBranchOrder = $myBranchOrdersQuery->count();
//     $totalOtherBranchOrder = $totalOtherBranchOrdersQuery->count(); // New variable for total other branch orders

//     // All-time sender orders (transfer_other_branch = false)
//     $senderAllTimeQuery = Order::where('transfer_other_branch', 'false')
//         ->whereIn('sender_pincode', $pinCodes);

//     $totalSenderOrders = $senderAllTimeQuery->count();
//     $totalSenderPendingOrders = (clone $senderAllTimeQuery)->where(function ($query) {
//         $query->where('order_status', 'Booked')
//               ->orWhere('order_status', 'Item Not Picked Up');
//     })->count();
    
//     $todaySendersuperexp = (clone $senderAllTimeQuery)->where(function ($query) {
//         $query->whereNotIn('order_status', ['Delivered'])->where('service_type', 'SuperExpress');
//     })->count();
    
//     $totalSenderProcessingOrders = (clone $senderAllTimeQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch', 'Item Not Picked Up'])->count();
//     $totalSenderCompletedOrders = (clone $senderAllTimeQuery)->where('order_status', 'Delivered')->count();
//     $totalSenderCancelledOrders = (clone $senderAllTimeQuery)->where('order_status', 'Cancelled')->count();

//     // All-time receiver orders (transfer_other_branch = true)
//     $receiverAllTimeQuery = Order::where('transfer_other_branch', 'true')
//         ->whereIn('receiver_pincode', $pinCodes);

//     $totalReceiverOrders = $receiverAllTimeQuery->count();
//     $totalReceiverPendingOrders = (clone $receiverAllTimeQuery)->where(function ($query) {
//         $query->where('order_status', 'Booked')
//               ->orWhere('order_status', 'Item Not Picked Up');
//     })->count();
    
//     $totalsupertoday = (clone $receiverAllTimeQuery)->where(function ($query) {
//         $query->whereNotIn('order_status', ['Delivered'])->where('service_type', 'SuperExpress');
//     })->count();
    
//     $totalReceiverProcessingOrders = (clone $receiverAllTimeQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch', 'Item Not Picked Up'])->count();
//     $totalReceiverCompletedOrders = (clone $receiverAllTimeQuery)->where('order_status', 'Delivered')->count();
//     $totalReceiverCancelledOrders = (clone $receiverAllTimeQuery)->where('order_status', 'Cancelled')->count();

//     $myOrderDetail = Order::where(['sender_order_status' => 'Delivered'])
//         ->whereIn('sender_order_pin', $pinCodes)
//         ->count();

//     // For historical compatibility
//     // $toDayOrder = $todaySenderOrders + $todayReceiverOrders + $totaltodaytransferOrder;
//     $toDayOrder = Order::where('datetime', 'like', $dateTime . '%')
//             ->where(function ($query) use ($pinCodes) {
//                 $query->where(function ($q) use ($pinCodes) {
//                     // Sender branch orders
//                     $q->whereIn('sender_pincode', $pinCodes)
//                         ->where('transfer_other_branch', 'false');
//                 })->orWhere(function ($q) use ($pinCodes) {
//                     // Receiver branch orders
//                     $q->whereIn('receiver_pincode', $pinCodes)
//                         ->where('transfer_other_branch', 'true');
//                 });
//             })->count();
    
    
//     // $toDayOrder = $todaySenderOrders + $todayReceiverOrders + $totaltodaytransferOrder;
//     // $toDayOrder = $todaySenderOrders + $todayReceiverOrders + $totaltodaytransferOrder;
//     $toDayPendingOrder = $todaySenderPendingOrders + $todayReceiverPendingOrders;
//     $todaysuper = $todaySendersuperexp + $totalsupertoday;
    
//     $toDayOrderPicUp = $todaySenderProcessingOrders + $todayReceiverProcessingOrders;
//     $toDayCompleteOrder = $todaySenderCompletedOrders + $todayReceiverCompletedOrders;
//     $toDayCancelledOrder = $todaySenderCancelledOrders + $todayReceiverCancelledOrders;

//     $totalOrder = $totalSenderOrders + $totalReceiverOrders;
//     $totalPendingOrder = $totalSenderPendingOrders + $totalReceiverPendingOrders;
//     $totalOrderPicUp = $totalSenderProcessingOrders + $totalReceiverProcessingOrders;
//     $totalCompleteOrder = $totalSenderCompletedOrders + $totalReceiverCompletedOrders;
//     $totalCanceledOrder = $totalSenderCancelledOrders + $totalReceiverCancelledOrders;

//     // Count distinct receiver pincodes for orders from this branch that go to other branches
//     $allOrderDetail = Order::whereNull('sender_order_status')
//         ->whereIn('sender_pincode', $pinCodes)
//         ->whereNotIn('receiver_pincode', $pinCodes) // Exclude orders within same branch
//         ->select('receiver_pincode')
//         ->distinct()
//         ->pluck('receiver_pincode')
//         ->count();

//     return view('delivery.dashboard', compact(
//         'delivery',
//         // Combined totals (for backward compatibility)
//         'toDayOrder',
//         'toDayPendingOrder',
//         'toDayOrderPicUp',
//         'toDayCompleteOrder',
//         'toDayCancelledOrder',
//         'totalOrder',
//         'totalPendingOrder',
//         'totalOrderPicUp',
//         'totalCompleteOrder',
//         'totalCanceledOrder',
//         'todaysuper',
//         // Sender branch counts (transfer_other_branch = false)
//         'todaySenderOrders',
//         'todaySenderPendingOrders',
//         'todaySenderProcessingOrders',
//         'todaySenderCompletedOrders',
//         'todaySenderCancelledOrders',
//         'totalSenderOrders',
//         'totalSenderPendingOrders',
//         'totalSenderProcessingOrders',
//         'totalSenderCompletedOrders',
//         'totalSenderCancelledOrders',
//         // Receiver branch counts (transfer_other_branch = true)
//         'todayReceiverOrders',
//         'todayReceiverPendingOrders',
//         'todayReceiverProcessingOrders',
//         'todayReceiverCompletedOrders',
//         'todayReceiverCancelledOrders',
//         'totalReceiverOrders',
//         'totalReceiverPendingOrders',
//         'totalReceiverProcessingOrders',
//         'totalReceiverCompletedOrders',
//         'totalReceiverCancelledOrders',
//         // Other existing variables
//         'myOrderDetail',
//         'totaltodaytransferOrder',
//         'toDayOtherBranchOrder',
//         'allOrderDetail',
//         'todayPendingToReceiveOtherBranchOrders',
//         'toDayMyBranchOrder',
//         'totalOtherBranchOrder',
//         // New variable
//         'todayDeliveredToNearbyBranchOrders'
//     ));
// }


    // public function updateCodStatus(Request $request)
    // {
        
    //     $id = Session::get('dyid');
    //     try {
    //         // Run database operations in a transaction for atomicity
    //         return DB::transaction(function () use ($request, $id) {
    //             // Fetch the existing CodAmount record
    //             $codAmount = CodAmount::where('id', $request->record_id)
    //                 ->where('delivery_boy_id', $id)
    //                 ->first();
                   

    //             // Fetch the corresponding BranchCodHistory record
    //             $branchCodHistory = BranchCodHistory::where('delivery_boy_id', $id)
    //                 ->where('amount', $codAmount->amount)
    //                 ->where('datetime', $codAmount->datetime)
    //                 ->first();

    //             if (!$branchCodHistory) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Corresponding branch COD history record not found for this delivery boy.',
    //                 ], 404);
    //             }

    //             // If status is changing to Reject and was not already Reject, reverse the transaction
    //             if ($request->status === 'Reject' && $codAmount->status !== 'Reject') {
    //                 // Fetch the delivery boy's wallet
    //                 $codWallet = CodWallet::where('delivery_boy_id', $id)->first();
    //                 if (!$codWallet) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'No wallet found for this delivery boy.',
    //                     ], 400);
    //                 }

    //                 // Add the amount back to CodWallet
    //                 $codWallet->amount += $codAmount->amount;
    //                 $codWallet->save();

    //                 // Deduct the amount from BranchtotalCod
    //                 $branchTotalCod = BranchtotalCod::where('delivery_boy_id', $id)
    //                     ->where('branch_id', $branchCodHistory->branch_id)
    //                     ->first();

    //                 if ($branchTotalCod) {
    //                     if ($branchTotalCod->amount < $codAmount->amount) {
    //                         return response()->json([
    //                             'success' => false,
    //                             'message' => 'Insufficient branch COD balance to reject.',
    //                         ], 400);
    //                     }
    //                     $branchTotalCod->amount -= $codAmount->amount;
    //                     $branchTotalCod->save();
    //                 } else {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'No branch total COD record found for this delivery boy.',
    //                     ], 400);
    //                 }
    //             }

    //             // Update status in existing CodAmount and BranchCodHistory records
    //             $codAmount->status = $request->status;
    //             $codAmount->save();

    //             $branchCodHistory->status = $request->status;
    //             $branchCodHistory->save();

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'COD status updated successfully.',
    //             ]);
    //         });
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error updating COD status: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    //  public function updateCodStatus(Request $request)
    // {
        
    //     $id = Session::get('dyid');
    //     try {
    //         // Run database operations in a transaction for atomicity
    //         return DB::transaction(function () use ($request, $id) {
    //             // Fetch the existing CodAmount record
    //             $codAmount = CodAmount::where('id', $request->record_id)
    //                 ->where('delivery_boy_id', $id)
    //                 ->first();
                   

    //             // Fetch the corresponding BranchCodHistory record
    //             $branchCodHistory = BranchCodHistory::where('delivery_boy_id', $id)
    //                 ->where('amount', $codAmount->amount)
    //                 ->where('datetime', $codAmount->datetime)
    //                 ->first();

    //             if (!$branchCodHistory) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Corresponding branch COD history record not found for this delivery boy.',
    //                 ], 404);
    //             }

    //             // If status is changing to Reject and was not already Reject, reverse the transaction
    //             if ($request->status === 'Reject' && $codAmount->status !== 'Reject') {
    //                 // Fetch the delivery boy's wallet
    //                 $codWallet = CodWallet::where('delivery_boy_id', $id)->first();
    //                 if (!$codWallet) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'No wallet found for this delivery boy.',
    //                     ], 400);
    //                 }

    //                 // Add the amount back to CodWallet
    //                 $codWallet->amount += $codAmount->amount;
    //                 $codWallet->save();

    //                 // Deduct the amount from BranchtotalCod
    //                 $branchTotalCod = BranchtotalCod::where('delivery_boy_id', $id)
    //                     ->where('branch_id', $branchCodHistory->branch_id)
    //                     ->first();

    //                 if ($branchTotalCod) {
    //                     if ($branchTotalCod->amount < $codAmount->amount) {
    //                         return response()->json([
    //                             'success' => false,
    //                             'message' => 'Insufficient branch COD balance to reject.',
    //                         ], 400);
    //                     }
    //                     $branchTotalCod->amount -= $codAmount->amount;
    //                     $branchTotalCod->save();
    //                 } else {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'No branch total COD record found for this delivery boy.',
    //                     ], 400);
    //                 }
    //             }

    //             // Update status in existing CodAmount and BranchCodHistory records
    //             $codAmount->status = $request->status;
    //             $codAmount->save();

    //             $branchCodHistory->status = $request->status;
    //             $branchCodHistory->save();

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'COD status updated successfully.',
    //             ]);
    //         });
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error updating COD status: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    
    // 14 june 
    // public function updateCodStatus(Request $request)
    // {
    
    //     $id = Session::get('dyid');
    //     try {
    //         // Run database operations in a transaction for atomicity
    //         return DB::transaction(function () use ($request, $id) {
    //             // Fetch the existing CodAmount record
    //             $codAmount = CodAmount::where('record_id', $request->record_id)
    //                 ->first();
                
    //             // Fetch the corresponding BranchCodHistory record
    //             $branchCodHistory = BranchCodHistory::where('branch_id', $id)
    //                 ->where('id', $request->record_id)
    //                 ->first();
                

    //             if (!$branchCodHistory) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Corresponding branch COD history record not found for this delivery boy.',
    //                 ], 404);
    //             }

    //             // If status is changing to Reject and was not already Reject, reverse the transaction
    //             if ($request->status === 'Reject' && $codAmount->status !== 'Reject') {
    //                 // Fetch the delivery boy's wallet
    //                 $codWallet = CodWallet::where('delivery_boy_id', $codAmount->delivery_boy_id)->first();
    //                 if (!$codWallet) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'No wallet found for this delivery boy.',
    //                     ], 400);
    //                 }

    //                 // Add the amount back to CodWallet (ensure amount is valid)
    //                 if ($codAmount->amount && $codAmount->amount > 0) {
    //                     $codWallet->amount += $codAmount->amount;
    //                     $codWallet->save();
    //                 }

    //                 // Update status in BranchtotalCod
    //                 $branchTotalCod = BranchtotalCod::where('branch_id', $id)->first();
                    
    //                 if ($branchTotalCod) {
    //                     if ($request->status === 'Reject') {
    //                         $branchTotalCod->amount -= $codAmount->amount; // ← यहीं सुधार किया गया
    //                     }
    //                     $branchTotalCod->save();
    //                 }
    //                 else {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'No branch total COD record found for this delivery boy.',
    //                     ], 400);
    //                 }
    //             }

    //             // Update status in existing CodAmount and BranchCodHistory records
    //             $codAmount->status = $request->status;
    //             $codAmount->save();
                
    //             $branchCodHistory->status = $request->status;
    //             $branchCodHistory->save();

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'COD status updated successfully.',
    //             ]);
    //         });
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error updating COD status: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    
//   public function updateCodStatus(Request $request)
//   {
//         $id = Session::get('dyid'); // Branch ID
    
//         try {
//             return DB::transaction(function () use ($request, $id) {
//                 // Fetch CodAmount record
//                 $codAmount = CodAmount::where('record_id', $request->record_id)->first();
    
//                 // Fetch BranchCodHistory record
//                 $branchCodHistory = BranchCodHistory::where('branch_id', $id)
//                     ->where('id', $request->record_id)
//                     ->first();
    
//                 if (!$codAmount || !$branchCodHistory) {
//                     return response()->json([
//                         'success' => false,
//                         'message' => 'Record not found.',
//                     ], 404);
//                 }
    
//                 if ($request->status === 'Approve') {
//                     // Increment BranchtotalCod amount
//                     $branchTotalCod = BranchtotalCod::where('branch_id', $id)->first();
    
//                     if ($branchTotalCod) {
//                         $branchTotalCod->amount += $branchCodHistory->amount;
//                         $branchTotalCod->save();
//                     } else {
//                         // Create if not exists with delivery_boy_id also
//                         BranchtotalCod::create([
//                             'branch_id' => $id,
//                             'delivery_boy_id' => $codAmount->delivery_boy_id,
//                             'amount' => $branchCodHistory->amount,
//                         ]);
//                     }
    
//                 } 
//                 elseif ($request->status === 'Reject' && $codAmount->status !== 'Reject') 
//                 {
//                     // Add back to CodWallet
//                     $codWallet = CodWallet::where('delivery_boy_id', $codAmount->delivery_boy_id)->first();
    
//                     if (!$codWallet) {
//                         return response()->json([
//                             'success' => false,
//                             'message' => 'No wallet found for this delivery boy.',
//                         ], 400);
//                     }
    
//                     $codWallet->amount += $codAmount->amount;
//                     $codWallet->save();
//                 }
    
//                 // Update statuses
//                 $codAmount->status = $request->status;
//                 $codAmount->save();
    
//                 $branchCodHistory->status = $request->status;
//                 $branchCodHistory->save();
    
//                 return response()->json([
//                     'success' => true,
//                     'message' => 'COD status updated successfully.',
//                 ]);
//             });
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Error updating COD status: ' . $e->getMessage(),
//             ], 500);
//         }
//     }

public function updateCodStatus(Request $request)
    {
        $id = Session::get('dyid'); // Branch ID
        try {
            return DB::transaction(function () use ($request, $id) {
                // Fetch CodAmount record
                $codAmount = CodAmount::where('record_id', $request->record_id)->first();
    
                // Fetch BranchCodHistory record
                $branchCodHistory = BranchCodHistory::where('branch_id', $id)
                    ->where('id', $request->record_id)
                    ->first();
    
                if (!$codAmount || !$branchCodHistory) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Record not found.',
                    ], 404);
                }
    
                if ($request->status === 'Approve') {
                     // Find all matching CodAmount records
                    $codAmounts = CodAmount::where('delivery_boy_id', $codAmount->delivery_boy_id)
                        ->where('record_id', $request->record_id)
                        ->first();
                    $totalAmount = $codAmounts->amount;

                    // Decrease amount from CodWallet
                    $codWallet = CodWallet::where('delivery_boy_id', $codAmount->delivery_boy_id)->first();
                    if ($codWallet) {
                        $codWallet->amount -= $totalAmount;
                        $codWallet->save();
                    }

                    // Increment BranchtotalCod amount
                    $branchTotalCod = BranchtotalCod::where('branch_id', $id)->first();
                    if ($branchTotalCod) {
                        $branchTotalCod->amount += $branchCodHistory->amount;
                        $branchTotalCod->save();
                    } else {
                        // Create if not exists with delivery_boy_id also
                        BranchtotalCod::create([
                            'branch_id' => $id,
                            'delivery_boy_id' => $codAmount->delivery_boy_id,
                            'amount' => $branchCodHistory->amount,
                        ]);
                    }
    
                } 
                elseif ($request->status === 'Reject' && $codAmount->status !== 'Reject') 
                {
                    // Add back to CodWallet
                    $codWallet = CodWallet::where('delivery_boy_id', $codAmount->delivery_boy_id)->first();
    
                    if (!$codWallet) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No wallet found for this delivery boy.',
                        ], 400);
                    }
                    // $codWallet->amount += $codAmount->amount;
                    $codWallet->save();
                }
    
                // Update statuses
                $codAmount->status = $request->status;
                $codAmount->save();
    
                $branchCodHistory->status = $request->status;
                $branchCodHistory->save();
    
                return response()->json([
                    'success' => true,
                    'message' => 'COD status updated successfully.',
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating COD status: ' . $e->getMessage(),
            ], 500);
        }
    }

    
    
    


    public function dashboard()
    {
        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        $pinCodes = explode(',', trim($delivery->pincode, ','));

        // Store branch pincodes in session for use in blade template
        Session::put('branch_pincodes', $delivery->pincode);

        // Get current date in Y-m-d format for comparison with DATE(updated_at)
        $date = Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d');

        // Sender branch orders (where transfer_other_branch = false)
        $senderOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
            ->where('transfer_other_branch', 'false')
            ->whereIn('sender_pincode', $pinCodes);

        $superexp = Order::whereRaw('DATE(updated_at) = ?', [$date])
            ->where('transfer_other_branch', 'false')
            ->whereIn('sender_pincode', $pinCodes);

        // Receiver branch orders (where transfer_other_branch = true)
        $receiverOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
            ->where('transfer_other_branch', 'true')
            ->where('order_status', '!=', 'Delivered to near by branch')
            ->whereIn('receiver_pincode', $pinCodes);

        $receiverTransferOtherBranchOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
            ->where('transfer_other_branch', 'true')
            ->whereIn('receiver_pincode', $pinCodes);

        // Orders transferred OUT from this branch (to different branches)
        $outgoingTransfersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
            ->where('transfer_other_branch', 'true')
            ->whereIn('sender_pincode', $pinCodes)
            ->whereNotIn('receiver_pincode', $pinCodes); // Exclude transfers within same branch

        // Other branch orders query (sender is this branch, receiver is different branch)
        $otherBranchOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
            ->where('transfer_other_branch', 'false')
            ->whereIn('sender_pincode', $pinCodes)
            ->whereNotIn('receiver_pincode', $pinCodes) // Exclude orders within same branch
            ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered', 'Cancelled']); // Exclude Booked and Item Picked Up

        // Total other branch orders query (all-time, sender is this branch, receiver is different branch)
        $totalOtherBranchOrdersQuery = Order::where('transfer_other_branch', 'false')
            ->whereIn('sender_pincode', $pinCodes)
            ->whereNotIn('receiver_pincode', $pinCodes) // Exclude orders within same branch
            ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Delivered', 'Item Not Picked Up', 'Cancelled']); // Exclude Booked and Item Picked Up

        // Same branch order show 
        $myBranchOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
            ->whereIn('sender_pincode', $pinCodes)
            ->whereIn('receiver_pincode', $pinCodes) // Include orders within same branch
            ->where('order_status', 'Delivered to branch');

        $myBranchOrdersQueryforTotalorder = Order::whereIn('sender_pincode', $pinCodes)
            ->whereIn('receiver_pincode', $pinCodes) // Include orders within same branch
            ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered', 'Cancelled']);

        // Today's counts for sender branch (transfer_other_branch = false)
        $todaySenderOrders = $senderOrdersQuery->count();
        $todaySenderPendingOrders = (clone $senderOrdersQuery)->where(function ($query) {
            $query->where('order_status', 'Booked')
                ->orWhere('order_status', 'Item Not Picked Up');
        })->count();
        $todaySenderProcessingOrders = (clone $senderOrdersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch', 'Item Not Picked Up', 'Out for Delivery to Origin', 'Not Delivered'])->count();
        $todaySenderCompletedOrders = (clone $senderOrdersQuery)->where('order_status', 'Delivered')->count();
        $todaySenderCancelledOrders = (clone $senderOrdersQuery)->where('order_status', 'Cancelled')->count();

        // Today's counts for receiver branch (transfer_other_branch = true)
        $todayReceiverOrders = $receiverOrdersQuery->count();
        $todayReceiverPendingOrders = (clone $receiverOrdersQuery)->where(function ($query) {
            $query->where('order_status', 'Booked')
                ->orWhere('order_status', 'Item Not Picked Up');
        })->count();
        $todayReceiverProcessingOrders = (clone $receiverOrdersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Delivered to branch', 'Item Not Picked Up', 'Out for Delivery to Origin', 'Not Delivered'])->count();
        $todayReceiverCompletedOrders = (clone $receiverOrdersQuery)->where('order_status', 'Delivered')->count();
        $todayReceiverCancelledOrders = (clone $receiverOrdersQuery)->where('order_status', 'Cancelled')->count();

        // Delivered to nearby branch
        $todayDeliveredToNearbyBranchOrders = (clone $receiverTransferOtherBranchOrdersQuery)->whereIn('order_status', ['Out for Delivery to Origin', 'Not Delivered'])->count();
        $todayPendingToReceiveOtherBranchOrders = (clone $receiverTransferOtherBranchOrdersQuery)->whereNotIn('order_status', ['Delivered', 'Delivered to branch', 'Out for Delivery to Origin', 'Not Delivered'])->count();

        // Count of outgoing transfers (orders transferred OUT from this branch to different branches)
        $totaltodaytransferOrder = $outgoingTransfersQuery->count();

        // Count of orders where sender is this branch, receiver is different branch
        $toDayOtherBranchOrder = $otherBranchOrdersQuery->count();
        $toDayMyBranchOrder = $myBranchOrdersQuery->count();
        $totalOtherBranchOrder = $totalOtherBranchOrdersQuery->count(); // New variable for total other branch orders

        // All-time sender orders (transfer_other_branch = false)
        $senderAllTimeQuery = Order::where('transfer_other_branch', 'false')
            ->whereIn('sender_pincode', $pinCodes);

        $totalSenderOrders = $senderAllTimeQuery->count();
        $totalSenderPendingOrders = (clone $senderAllTimeQuery)->where(function ($query) {
            $query->where('order_status', 'Booked')
                ->orWhere('order_status', 'Item Not Picked Up');
        })->count();

        // Updated to match orderDetails: only specific statuses
        $totalSenderProcessingOrders = (clone $senderAllTimeQuery)->whereIn('order_status', ['Item Picked Up', 'Out for Delivery to Origin'])->count();
        $totalSenderCompletedOrders = (clone $senderAllTimeQuery)->where('order_status', 'Delivered')->count();
        $totalSenderCancelledOrders = (clone $senderAllTimeQuery)->where('order_status', 'Cancelled')->count();

        $totalMyBranchOrder = $myBranchOrdersQueryforTotalorder->count();

        // All-time receiver orders (transfer_other_branch = true)
        $receiverAllTimeQuery = Order::where('transfer_other_branch', 'true')
            ->whereIn('receiver_pincode', $pinCodes);

        $totalReceiverOrders = $receiverAllTimeQuery->count();
        $totalReceiverPendingOrders = (clone $receiverAllTimeQuery)->where(function ($query) {
            $query->where('order_status', 'Booked')
                ->orWhere('order_status', 'Item Not Picked Up');
        })->count();

        $todaySendersuperexp = (clone $senderAllTimeQuery)->where(function ($query) {
            $query->whereNotIn('order_status', ['Delivered', 'Cancelled'])->where('service_type', 'SuperExpress');
        })->count();

        $totalsupertoday = (clone $receiverAllTimeQuery)->where(function ($query) {
            $query->whereNotIn('order_status', ['Delivered'])->where('service_type', 'SuperExpress');
        })->count();

        // Updated to match orderDetails: only specific statuses
        $totalReceiverProcessingOrders = (clone $receiverAllTimeQuery)->whereIn('order_status', ['Item Picked Up', 'Out for Delivery to Origin'])->count();
        $totalReceiverCompletedOrders = (clone $receiverAllTimeQuery)->where('order_status', 'Delivered')->count();
        $totalReceiverCancelledOrders = (clone $receiverAllTimeQuery)->where('order_status', 'Cancelled')->count();

        $myOrderDetail = Order::where(['sender_order_status' => 'Delivered'])
            ->whereIn('sender_order_pin', $pinCodes)
            ->count();

        // For historical compatibility
        $toDayOrder = Order::whereRaw('DATE(updated_at) = ?', [$date])
            ->where(function ($query) use ($pinCodes) {
                $query->where(function ($q) use ($pinCodes) {
                    // Sender branch orders
                    $q->whereIn('sender_pincode', $pinCodes)
                        ->where('transfer_other_branch', 'false');
                })->orWhere(function ($q) use ($pinCodes) {
                    // Receiver branch orders
                    $q->whereIn('receiver_pincode', $pinCodes)
                        ->where('transfer_other_branch', 'true');
                });
            })->count();

        $toDayPendingOrder = $todaySenderPendingOrders + $todayReceiverPendingOrders;
        $todaysuper = $todaySendersuperexp;

        $toDayOrderPicUp = $todaySenderProcessingOrders + $todayReceiverProcessingOrders;
        $toDayCompleteOrder = $todaySenderCompletedOrders + $todayReceiverCompletedOrders;
        $toDayCancelledOrder = $todaySenderCancelledOrders + $todayReceiverCancelledOrders;

        $totalOrder = $totalSenderOrders + $totalReceiverOrders;
        $totalPendingOrder = $totalSenderPendingOrders + $totalReceiverPendingOrders;
        $totalOrderPicUp = $totalSenderProcessingOrders + $totalReceiverProcessingOrders;
        $totalCompleteOrder = $totalSenderCompletedOrders + $totalReceiverCompletedOrders;
        $totalCanceledOrder = $totalSenderCancelledOrders + $totalReceiverCancelledOrders;

        // Count distinct receiver pincodes for orders from this branch that go to other branches
        $allOrderDetail = Order::whereNull('sender_order_status')
            ->whereIn('sender_pincode', $pinCodes)
            ->whereNotIn('receiver_pincode', $pinCodes) // Exclude orders within same branch
            ->select('receiver_pincode')
            ->distinct()
            ->pluck('receiver_pincode')
            ->count();

        return view('delivery.dashboard', compact(
            'delivery',
            // Combined totals (for backward compatibility)
            'toDayOrder',
            'toDayPendingOrder',
            'toDayOrderPicUp',
            'toDayCompleteOrder',
            'toDayCancelledOrder',
            'totalOrder',
            'totalPendingOrder',
            'totalOrderPicUp',
            'totalCompleteOrder',
            'totalCanceledOrder',
            'todaysuper',
            // Sender branch counts (transfer_other_branch = false)
            'todaySenderOrders',
            'todaySenderPendingOrders',
            'todaySenderProcessingOrders',
            'todaySenderCompletedOrders',
            'todaySenderCancelledOrders',
            'totalSenderOrders',
            'totalSenderPendingOrders',
            'totalSenderProcessingOrders',
            'totalSenderCompletedOrders',
            'totalSenderCancelledOrders',
            // Receiver branch counts (transfer_other_branch = true)
            'todayReceiverOrders',
            'todayReceiverPendingOrders',
            'todayReceiverProcessingOrders',
            'todayReceiverCompletedOrders',
            'todayReceiverCancelledOrders',
            'totalReceiverOrders',
            'totalReceiverPendingOrders',
            'totalReceiverProcessingOrders',
            'totalReceiverCompletedOrders',
            'totalReceiverCancelledOrders',
            // Other existing variables
            'myOrderDetail',
            'totaltodaytransferOrder',
            'toDayOtherBranchOrder',
            'allOrderDetail',
            'todayPendingToReceiveOtherBranchOrders',
            'toDayMyBranchOrder',
            'totalOtherBranchOrder',
            'totalMyBranchOrder',
            // New variable
            'todayDeliveredToNearbyBranchOrders'
        ));
    }
    




  




    
    
  


     public function orderDetails($action)
    {
        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        $pinCodes = explode(',', trim($delivery->pincode, ','));

        // Store branch pincodes in session for use in blade template
        Session::put('branch_pincodes', $delivery->pincode);

        // Get current date in Y-m-d format for comparison with DATE(updated_at)
        $date = Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d');

        // Today's queries
        if (in_array($action, ['toDayMyBranchOrder', 'toDayOrder', 'todayPendingToReceiveOtherBranchOrders', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'toDayOtherBranchOrder', 'totaltodaytransferOrder', 'todayDeliveredToNearbyBranchOrders', 'todaysuper'])) {
            // Sender branch orders (transfer_other_branch = false)
            $senderOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
                ->where('transfer_other_branch', 'false')
                ->whereIn('sender_pincode', $pinCodes);

            // today condition remove because this is SuperExpress
            $senderOrdersQuerytwo = Order::where('transfer_other_branch', 'false')
                ->whereIn('sender_pincode', $pinCodes);

            // Receiver branch orders (transfer_other_branch = true)
            $receiverOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
                ->where('transfer_other_branch', 'true')
                ->whereIn('receiver_pincode', $pinCodes);

            $myBranchOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
                ->whereIn('sender_pincode', $pinCodes)
                ->whereIn('receiver_pincode', $pinCodes)
                ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered', 'Cancelled']);

            // Orders transferred OUT from this branch (to different branches)
            $outgoingTransfersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
                ->where('transfer_other_branch', 'true')
                ->whereIn('sender_pincode', $pinCodes)
                ->whereNotIn('receiver_pincode', $pinCodes); // Exclude transfers within same branch

            // Combined orders (for backward compatibility)
            $combinedOrdersQuery = Order::whereRaw('DATE(updated_at) = ?', [$date])
                ->where(function ($query) use ($pinCodes) {
                    $query->where(function ($q) use ($pinCodes) {
                        // Sender branch orders
                        $q->whereIn('sender_pincode', $pinCodes)
                            ->where('transfer_other_branch', 'false');
                    })->orWhere(function ($q) use ($pinCodes) {
                        // Receiver branch orders
                        $q->whereIn('receiver_pincode', $pinCodes)
                            ->where('transfer_other_branch', 'true');
                    });
                });

            // Handle specific actions
            if ($action == 'toDayOrder') {
                $data = $combinedOrdersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'todayPendingToReceiveOtherBranchOrders') {
                $data = $receiverOrdersQuery->whereNotIn('order_status', ['Delivered', 'Delivered to branch', 'Not Delivered', 'Booked', 'Out for Delivery to Origin'])->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayPendingOrder') {
                $data = $combinedOrdersQuery->where(function ($query) {
                    $query->where('order_status', 'Booked')
                        ->orWhere('order_status', 'Item Not Picked Up');
                })->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayOrderPicUp') {
                $data = $combinedOrdersQuery->where(function ($query) { 
                    $query->where('order_status', 'Item Picked Up');
                    //   ->orWhere('order_status', 'Not Delivered')
                })->orderBy('id', 'desc')->get();
            } elseif ($action == 'todaysuper') {
                $data = $senderOrdersQuery->where('service_type', 'SuperExpress')
                    ->whereNotIn('order_status', ['Delivered', 'Cancelled'])
                    ->orderBy('id', 'desc')
                    ->get();

                // today condition remove because this is SuperExpress
                $data = $senderOrdersQuerytwo->where('service_type', 'SuperExpress')
                    ->whereNotIn('order_status', ['Delivered', 'Cancelled'])
                    ->orderBy('id', 'desc')
                    ->get();
            } elseif ($action == 'toDayCompleteOrder') {
                $data = $combinedOrdersQuery->where('order_status', 'Delivered')->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayCancelledOrder') {
                $data = $combinedOrdersQuery->where('order_status', 'Cancelled')->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayOtherBranchOrder') {
                $data = Order::whereRaw('DATE(updated_at) = ?', [$date])
                    ->where('transfer_other_branch', 'false')
                    ->whereIn('sender_pincode', $pinCodes)
                    ->whereNotIn('receiver_pincode', $pinCodes)
                    ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered', 'Cancelled'])
                    ->orderBy('id', 'asc')->get();
            } elseif ($action == 'toDayMyBranchOrder') {
                $data = Order::whereRaw('DATE(updated_at) = ?', [$date])
                    ->whereIn('sender_pincode', $pinCodes)
                    ->whereIn('receiver_pincode', $pinCodes)
                    // ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered', 'Cancelled'])
                    ->where('order_status', 'Delivered to branch')
                    ->orderBy('id', 'asc')->get();
            } elseif ($action == 'totaltodaytransferOrder') {
                $data = $outgoingTransfersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'todayDeliveredToNearbyBranchOrders') {
                $data = $receiverOrdersQuery->whereIn('order_status', ['Out for Delivery to Origin', 'Not Delivered'])->orderBy('id', 'desc')->get();
            } else {
                $data = $combinedOrdersQuery->orderBy('id', 'desc')->get();
            }
        }
        // All-time queries
        elseif (in_array($action, ['totalOrder', 'totalMyBranchOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCancelledOrder', 'totalOtherBranchOrder', 'allOrdersAsc'])) {
            // All-time sender orders (transfer_other_branch = false)
            $senderAllTimeQuery = Order::where('transfer_other_branch', 'false')
                ->whereIn('sender_pincode', $pinCodes);

            // All-time receiver orders (transfer_other_branch = true)
            $receiverAllTimeQuery = Order::where('transfer_other_branch', 'true')
                ->whereIn('receiver_pincode', $pinCodes);

            // Combined orders (for backward compatibility)
            $combinedAllTimeQuery = Order::where(function ($query) use ($pinCodes) {
                $query->where(function ($q) use ($pinCodes) {
                    // Sender branch orders
                    $q->whereIn('sender_pincode', $pinCodes)
                        ->where('transfer_other_branch', 'false');
                })->orWhere(function ($q) use ($pinCodes) {
                    // Receiver branch orders
                    $q->whereIn('receiver_pincode', $pinCodes)
                        ->where('transfer_other_branch', 'true');
                });
            });

            if ($action == 'totalOrder') {
                $data = $combinedAllTimeQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalMyBranchOrder') {
                $data = Order::whereIn('sender_pincode', $pinCodes)
                    ->whereIn('receiver_pincode', $pinCodes)
                    ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered', 'Cancelled'])
                    ->orderBy('id', 'asc')->get();
            } elseif ($action == 'totalPendingOrder') {
                $data = $combinedAllTimeQuery->where(function ($query) {
                    $query->where('order_status', 'Booked')
                        ->orWhere('order_status', 'Item Not Picked Up');
                })->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalOrderPicUp') {
                $data = $combinedAllTimeQuery->where(function ($query) {
                    $query->whereIn('order_status', ['Item Picked Up', 'Out for Delivery to Origin']);
                })->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalCompleteOrder') {
                $data = $combinedAllTimeQuery->where('order_status', 'Delivered')->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalCancelledOrder') {
                $data = $combinedAllTimeQuery->where('order_status', 'Cancelled')->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalOtherBranchOrder') {
                $data = Order::where('transfer_other_branch', 'false')
                    ->whereIn('sender_pincode', $pinCodes)
                    ->whereNotIn('receiver_pincode', $pinCodes)
                    ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Delivered', 'Item Not Picked Up', 'Cancelled'])
                    ->get();
            } elseif ($action == 'allOrdersAsc') {
                $data = $combinedAllTimeQuery->orderBy('id', 'asc')->get();
            } else {
                $data = $combinedAllTimeQuery->orderBy('id', 'desc')->get();
            }
        }
        // Default case, show all orders in ascending order
        else {
            $data = Order::where(function ($query) use ($pinCodes) {
                $query->where(function ($q) use ($pinCodes) {
                    // Sender branch orders
                    $q->whereIn('sender_pincode', $pinCodes)
                        ->where('transfer_other_branch', 'false');
                })->orWhere(function ($q) use ($pinCodes) {
                    // Receiver branch orders
                    $q->whereIn('receiver_pincode', $pinCodes)
                        ->where('transfer_other_branch', 'true');
                });
            })->orderBy('id', 'asc')->get();
        }

        return view('delivery.orderDetails', compact('data', 'delivery'));
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

    // public function deliveryAssignGet(Request $request)
    // {
    //     $id = Session::get('dyid');
    //     // $delivery = Branch::find($id);
    //     // $pinCodes = explode(',', $delivery->pincode);
    //     // $data = DlyBoy::where(function ($query) use ($pinCodes) {
    //     //     foreach ($pinCodes as $pincode) {
    //     //         $query->orWhere('pincode', 'LIKE', "%$pincode%");
    //     //     }
    //     // })->where('status', 'active')->get();

    //     // dd($data->toArray());


    //     $order = Order::find($request->id);
    //     $orderId = $order->order_id;
    //     $data = DlyBoy::where('status', 'active')->where('userid', $id)->get();

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'data' => $data,
    //             'orderId' => $orderId
    //         ]);
    //     }
    // }

    // public function deliveryBoyGet()
    // {
    //     $id = Session::get('dyid');
    //     $delivery_branch = Branch::where('id', $id)->where('type', 'Delivery')->first();

    //     if (!$delivery_branch) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery branch not found',
    //         ]);
    //     }

    //     $branch_pincodes = explode(',', $delivery_branch->pinocde);

    //     // Get delivery boys whose pincodes match with branch pincodes
    //     $data = DlyBoy::where('status', 'active')
    //         ->where(function ($query) use ($branch_pincodes) {
    //             foreach ($branch_pincodes as $pincode) {
    //                 // Using FIND_IN_SET or LIKE based on how DlyBoy pincode is stored
    //                 $query->orWhereRaw("FIND_IN_SET(?, pincode)", [$pincode])
    //                     ->orWhere('pincode', $pincode)
    //                     ->orWhere('pincode', 'LIKE', '%' . $pincode . ',%')
    //                     ->orWhere('pincode', 'LIKE', '%,' . $pincode . '%');
    //             }
    //         })
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $data,
    //     ]);
    // }



    //  Today My Task 
    public function deliveryAssignGet(Request $request)
    {
        // Get delivery branch ID from session
        $id = Session::get('dyid');
        $branch = Branch::where('id', $id)->where('type', 'Delivery')->first();

        if (!$branch) {
            return response()->json(['success' => false, 'message' => 'Branch not found']);
        }

        // Get the order from request
        $order = Order::find($request->id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }
        $orderId = $order->order_id;
        // Get branch pincodes
        $branchPincodes = explode(',', $branch->pincode);
        // Get active delivery boys
        $deliveryBoys = DlyBoy::where('status', 'active')->get();
        // Filter matching delivery boys
        $matchingBoys = [];
        foreach ($deliveryBoys as $boy) {
            // Skip if pincode is empty
            if (empty($boy->pincode))
                continue;

            $boyPincodes = explode(',', $boy->pincode);

            // Check for any matching pincode
            foreach ($branchPincodes as $branchPin) {
                if (in_array(trim($branchPin), array_map('trim', $boyPincodes))) {
                    $matchingBoys[] = $boy;
                    break; // Once we find a match, no need to check other pincodes
                }
            }
        }

        // Return the JSON response with orderId included
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $matchingBoys,
                'orderId' => $orderId
            ]);
        }
    }
    
  

    public function deliveryBoyGet()
    {
        // Get delivery branch
        $id = Session::get('dyid');
        $branch = Branch::where('id', $id)->where('type', 'Delivery')->first();

        if (!$branch) {
            return response()->json(['success' => false, 'message' => 'Branch not found']);
        }
        // Get branch pincodes
        $branchPincodes = explode(',', $branch->pincode);
        $deliveryBoys = DlyBoy::where('status', 'active')->get();
        // Filter matching delivery boys
        $matchingBoys = [];
        foreach ($deliveryBoys as $boy) {
            $boyPincodes = explode(',', $boy->pincode);
            // Check for any matching pincode
            foreach ($branchPincodes as $branchPin) {
                if (in_array(trim($branchPin), array_map('trim', $boyPincodes))) {
                    $matchingBoys[] = $boy;
                    break; // Once we find a match, no need to check other pincodes
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $matchingBoys
        ]);
    }
    
 
     public function getOrdersByDeliveryBoy(Request $request)
    {
        try {
            $deliveryBoyId = $request->input('deliveryBoyId');
            $segment = $request->input('segment');

            // Validate segment
            if (!in_array($segment, ['todayDeliveredToNearbyBranchOrders', 'toDayMyBranchOrder'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid segment provided. Allowed segments: todayDeliveredToNearbyBranchOrders, toDayMyBranchOrder.'
                ], 400);
            }

            $id = Session::get('dyid');
            $branch = Branch::where('id', $id)->where('type', 'Delivery')->first();

            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found.'
                ], 404);
            }

            $branchPinCodes = explode(',', trim($branch->pincode, ','));

            // Log session data for debugging
            Log::info('Session Data', [
                'dyid' => $id,
                'branch_pincodes' => $branchPinCodes,
                'segment' => $segment,
                'deliveryBoyId' => $deliveryBoyId
            ]);

            // Get orders based on segment and delivery boy
            $data = $this->getOrdersByAction($branchPinCodes, $deliveryBoyId, $segment);






            $html = view('delivery.orderDetails', compact('data'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'data_count' => $data->count()
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in getOrdersByDeliveryBoy', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getOrdersByAction($branchPinCodes, $deliveryBoyId = null, $segment)
    {
        $date = Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d');
        $query = Order::query();

        // Apply segment-specific filters
        if ($segment == 'todayDeliveredToNearbyBranchOrders') {
            $query->whereRaw('DATE(updated_at) = ?', [$date])
                ->where('transfer_other_branch', 'true')
                ->whereIn('receiver_pincode', $branchPinCodes)
                ->whereIn('order_status', ['Out for Delivery to Origin', 'Not Delivered'])
                ->orderBy('id', 'desc');
        } elseif ($segment == 'toDayMyBranchOrder') {
            $query->whereRaw('DATE(updated_at) = ?', [$date])
                ->whereIn('sender_pincode', $branchPinCodes)
                ->whereIn('receiver_pincode', $branchPinCodes)
                ->where('order_status', 'Delivered to branch')
                ->orderBy('id', 'asc');
        }

        if ($deliveryBoyId) {
            $deliveryBoy = DlyBoy::find($deliveryBoyId);
            if (!$deliveryBoy) {
                return Order::whereRaw('1 = 0');
            }

            // Get delivery boy pincodes
            $deliveryBoyPinCodes = explode(',', trim($deliveryBoy->pincode, ','));

            // Filter orders by delivery boy ID and matching receiver_pincode
            $query->whereIn('receiver_pincode', $deliveryBoyPinCodes);
        }



        return $query->with('dlyBoy')->get();
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

    // old code 
    // public function deliveryAssignOrder(Request $request)
    // {
    //     $id = Session::get('dyid');
    //     $orderIds = explode(',', $request->order_ids);
    //     sort($orderIds);
    //     $deliveryBoyId = $request->delivery_boy_id;

    //     // Get delivery boy name for the response message
    //     $deliveryBoy = DlyBoy::find($deliveryBoyId);
    //     $deliveryBoyName = $deliveryBoy ? $deliveryBoy->name : 'Unknown';

    //     $successCount = 0;
    //     $errorCount = 0;

    //     foreach ($orderIds as $orderId) {
    //         $order = Order::where('id', $orderId)->first();

    //         $order_history = new OrderHistory();
    //         if ($order) {
    //             $order->assign_to = $deliveryBoyId;
    //             $order->status_message = $request->status_message ?? 'Assigned to delivery';
    //             $order->order_status = 'Delivered to near by branch';
    //             $order->assign_by = $id;
    //             $order->transfer_other_branch = 'true';
    //             $order->save();
    //             $order_history->tracking_id = $order->order_id;
    //             $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //             $order_history->status = 'Transit';
    //             $order_history->save();
    //             $successCount++;
    //         } else {
    //             $errorCount++;
    //         }
    //     }

    //     if ($errorCount > 0) {
    //         $msg = "Warning: {$successCount} orders assigned to {$deliveryBoyName}, {$errorCount} orders not found.";
    //     } else {
    //         $msg = "Success: All {$successCount} orders assigned to {$deliveryBoyName}!";
    //     }

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => ($successCount > 0),
    //             'message' => $msg,
    //             'delivery_boy_name' => $deliveryBoyName
    //         ]);
    //     }

    //     return redirect()->back()->with('message', $msg);
    // }
    
    // new code for timing 
    public function deliveryAssignOrder(Request $request)
    {
        $id = Session::get('dyid');
        // $orderIds = explode(',', $request->order_ids);
        $orderIds = explode(',', $request->orderId);
        sort($orderIds);
        // $deliveryBoyId = $request->delivery_boy_id;
        $deliveryBoyId = $request->deliverBoyData;
        // $deliveryBoyId = 1;
        // dd($deliveryBoyId);
        
        // Get delivery boy name for the response message
        $deliveryBoy = DlyBoy::find($deliveryBoyId);
        $deliveryBoyName = $deliveryBoy ? $deliveryBoy->name : 'Unknown';
        
        $successCount = 0;
        $errorCount = 0;

        foreach ($orderIds as $orderId) {
            $order = Order::where('id', $orderId)->first();

            $order_history = new OrderHistory();
            if ($order) {
                $order->assign_to = $deliveryBoyId;
                $order->status_message = $request->status_message ?? 'Assigned to delivery';
                $order->order_status = 'Delivered to near by branch';
                $order->assign_by = $id;
                $order->transfer_other_branch = 'true';
                $order->updated_at = $this->date;
                $order->save();
                $order_history->tracking_id = $order->order_id;
                $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
                $order_history->status = 'Transit';
                $order_history->save();
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount > 0) {
            $msg = "Warning: {$successCount} orders assigned to {$deliveryBoyName}, {$errorCount} orders not found.";
        } else {
            $msg = "Success: All {$successCount} orders assigned to {$deliveryBoyName}!";
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => ($successCount > 0),
                'message' => $msg,
                'delivery_boy_name' => $deliveryBoyName
            ]);
        }

        return redirect()->back()->with('message', $msg);
    }
    
    
     public function deliveryAssignOrderNew(Request $request)
    {
        $id = Session::get('dyid');
        // $orderIds = explode(',', $request->order_ids);
        $orderIds = explode(',', $request->orderId);
        sort($orderIds);
        // $deliveryBoyId = $request->delivery_boy_id;
        $deliveryBoyId = $request->deliverBoyData;
        // $deliveryBoyId = 1;
        // dd($deliveryBoyId);
        
        // Get delivery boy name for the response message
        $deliveryBoy = DlyBoy::find($deliveryBoyId);
        $deliveryBoyName = $deliveryBoy ? $deliveryBoy->name : 'Unknown';
        
        $successCount = 0;
        $errorCount = 0;

        foreach ($orderIds as $orderId) {
            $order = Order::where('id', $orderId)->first();

            $order_history = new OrderHistory();
            if ($order) {
                $order->assign_to = $deliveryBoyId;
                $order->status_message = $request->status_message ?? 'Assigned to delivery';
                // $order->order_status = 'Delivered to near by branch';
                $order->assign_by = $id;
                $order->transfer_other_branch = 'true';
                $order->updated_at = $this->date;
                $order->save();
                $order_history->tracking_id = $order->order_id;
                $order_history->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
                $order_history->status = 'Transit';
                $order_history->save();
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount > 0) {
            $msg = "Warning: {$successCount} orders assigned to {$deliveryBoyName}, {$errorCount} orders not found.";
        } else {
            $msg = "Success: All {$successCount} orders assigned to {$deliveryBoyName}!";
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => ($successCount > 0),
                'message' => $msg,
                'delivery_boy_name' => $deliveryBoyName
            ]);
        }

        return redirect()->back()->with('message', $msg);
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

    // public function allDeliveryBoy()
    // {
    //     $id = Session::get('dyid');
    //     $delivery = Branch::find($id);
    //     $data = DlyBoy::where('userid', $id)->orWhere('pincode', $delivery->pincode)->orderBy('id', 'desc')->get();
    //     return view('delivery.allDeliveryBoy', compact('data'));
    // }
    
    // 14 june latest code 
    public function allDeliveryBoy()
    {
        $id = Session::get('dyid');
        $delivery = Branch::find($id);
        
        // Get delivery boys with count of orders where order_status is Booked or Out for Delivery to Origin
        $data = DlyBoy::where('userid', $id)
            ->orWhere('pincode', $delivery->pincode)
            ->withCount(['orders' => function ($query) {
                $query->whereIn('order_status', ['Booked', 'Out for Delivery to Origin']);
            }])
            ->orderBy('id', 'desc')
            ->get();
        
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

    // public function allCodHistory(Request $request)
    // {
    //     $id = Session::get('dyid');
    //     $branch = Branch::find($id);

    //     if ($request->has('date') && $request->date) {
    //         // If date range is provided via ajax
    //         $dateRange = explode(' - ', $request->date);
    //         $startDate = Carbon::createFromFormat('m/d/Y', $dateRange[0])->format('d-m-Y');
    //         $endDate = Carbon::createFromFormat('m/d/Y', $dateRange[1])->format('d-m-Y');

    //         // Add time to make it a full day range
    //         $startDateTime = $startDate . ' 00:00:00';
    //         $endDateTime = $endDate . ' 23:59:59';

    //         // Get BranchCodHistory data for the date range
    //         $data = BranchCodHistory::where('branch_id', $branch->id)
    //             ->whereBetween('datetime', [$startDateTime, $endDateTime])
    //             ->orderBy('id', 'desc')
    //             ->get();

    //         $totalAmount = $data->sum('amount');

    //         // For AJAX response
    //         $view = view('delivery.inc.allCodHistoryData', compact('data', 'totalAmount'))->render();
    //         return response()->json([
    //             'html' => $view
    //         ]);
    //     } else {
            
    //         // Default: Show today's data
    //         $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');

    //         // Get BranchCodHistory data for today
    //         // $data = BranchCodHistory::where('branch_id', $branch->id)
    //         //     ->where('datetime', 'like', $dateTime . '%')
    //         //     ->orderBy('id', 'desc')
    //         //     ->get();
            
    //         $data = BranchCodHistory::where('branch_id', $id)
    //                         ->orderBy('id', 'desc')
    //                         ->get();

    //         // Calculate total amount from today's transactions
    //         $totalAmount = $data->sum('amount');

    //         // Get total accumulated COD amount for this branch from BranchtotalCod
    //         $totalCod = BranchtotalCod::where('branch_id', $branch->id)
    //             ->sum('amount');

    //         return view('delivery.allCodHistory', compact('data', 'totalAmount', 'totalCod'));
    //     }
    // }
    
    // 14 june new 
    // public function allCodHistory(Request $request)
    // {
    //     $id = Session::get('dyid');
    //     $branch = Branch::find($id);

    //     if ($request->has('date') && $request->date) {
    //         // If date range is provided via ajax
    //         $dateRange = explode(' - ', $request->date);
    //         $startDate = Carbon::createFromFormat('m/d/Y', $dateRange[0])->format('d-m-Y');
    //         $endDate = Carbon::createFromFormat('m/d/Y', $dateRange[1])->format('d-m-Y');

    //         // Add time to make it a full day range
    //         $startDateTime = $startDate . ' 00:00:00';
    //         $endDateTime = $endDate . ' 23:59:59';

    //         // Get BranchCodHistory data for the date range
    //         $data = BranchCodHistory::where('branch_id', $branch->id)
    //             ->whereBetween('datetime', [$startDateTime, $endDateTime])
    //             ->orderBy('id', 'desc')
    //             ->get();

    //         $totalAmount = $data->sum('amount');

    //         // For AJAX response
    //         $view = view('delivery.inc.allCodHistoryData', compact('data', 'totalAmount'))->render();
    //         return response()->json([
    //             'html' => $view
    //         ]);
    //     } else {
            
    //         // Default: Show today's data
    //         $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');

    //         $data = BranchCodHistory::where('branch_id', $id)
    //                         ->orderBy('id', 'desc')
    //                         ->get();

    //         // Calculate total amount from today's transactions
    //         $totalAmount = $data->sum('amount');

    //         // Get total accumulated COD amount for this branch from BranchtotalCod
    //         $totalCod = BranchtotalCod::where('branch_id', $branch->id)
    //             ->sum('amount');

    //         return view('delivery.allCodHistory', compact('data', 'totalAmount', 'totalCod'));
    //     }
    // }
    
    public function allCodHistory(Request $request)
    {
        $id = Session::get('dyid');
        $branch = Branch::find($id);

        if ($request->has('date') && $request->date) {
            // If date range is provided via ajax
            $dateRange = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', $dateRange[0])->format('d-m-Y');
            $endDate = Carbon::createFromFormat('m/d/Y', $dateRange[1])->format('d-m-Y');

            // Add time to make it a full day range
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';

            // Get BranchCodHistory data for the date range
            $data = BranchCodHistory::where('branch_id', $branch->id)
                ->whereBetween('datetime', [$startDateTime, $endDateTime])
                ->orderBy('id', 'desc')
                ->get();

            $totalAmount = $data->sum('amount');

            // For AJAX response
            $view = view('delivery.inc.allCodHistoryData', compact('data', 'totalAmount'))->render();
            return response()->json([
                'html' => $view
            ]);
        } else {
            
            // Default: Show today's data
            $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');

            $data = BranchCodHistory::where('branch_id', $id)
                            ->orderBy('id', 'desc')
                            ->get();

            // Calculate total amount from today's transactions
            $totalAmount = $data->sum('amount');

            // Get total accumulated COD amount for this branch from BranchtotalCod
            $totalCod = BranchtotalCod::where('branch_id', $branch->id)
                ->sum('amount');

            return view('delivery.allCodHistory', compact('data', 'totalAmount', 'totalCod'));
        }
    }
    
    


    // public function submitCodToAdmin(Request $request)
    // {
    //     $branchId = Session::get('dyid'); // Branch ID from session
    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

    //     // Validate input
    //     $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string|max:255',
    //     ]);

    //     // Get branch details
    //     $branch = Branch::find($branchId);

    //     if (!$branch) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Branch not found.',
    //         ], 404);
    //     }

    //     // Check if branch has enough total COD to submit
    //     $totalBranchCodAmount = BranchtotalCod::where('branch_id', $branchId)->sum('amount');

    //     if ($totalBranchCodAmount < $request->amount) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Insufficient COD balance. Available: ₹' . $totalBranchCodAmount,
    //         ], 400);
    //     }

    //     try {
    //         DB::beginTransaction();

    //         // This will just show "Transfer to Admin" instead of delivery boy details
    //         BranchCodHistory::create([
    //             'branch_id' => $branchId,
    //             'amount' => $request->amount,
    //             'type' => 'Debited',
    //             'status' => 'Pending',   // addon this 
    //             'datetime' => $currentDateTime,
    //             'remarks' => 'Transfer to Admin',
    //         ]);

    //         // Deduct amount from BranchtotalCod records
    //         $branchTotalCods = BranchtotalCod::where('branch_id', $branchId)
    //             ->orderBy('amount', 'desc')
    //             ->get();

    //         $remainingAmountToDeduct = $request->amount;

    //         // Deduct amount from BranchtotalCod records
    //         foreach ($branchTotalCods as $branchTotalCod) {
    //             if ($remainingAmountToDeduct <= 0) {
    //                 break; // No more amount to deduct
    //             }

    //             if ($branchTotalCod->amount <= $remainingAmountToDeduct) {
    //                 // If record amount is less than or equal to what we need to deduct
    //                 $remainingAmountToDeduct -= $branchTotalCod->amount;

    //                 // Delete this record as its amount is fully used
    //                 $branchTotalCod->delete();
    //             } else {
    //                 // If record amount is more than what we need to deduct
    //                 $branchTotalCod->amount -= $remainingAmountToDeduct;
    //                 $branchTotalCod->save();

    //                 $remainingAmountToDeduct = 0;
    //                 break;
    //             }
    //         }

    //         $admincodhistorydata = [
    //             'branch_id' => $branchId,
    //             'amount' => $request->amount,
    //             'status' => 'Pending',
    //             'type' => 'Received',
    //             'datetime' => $currentDateTime,
    //             'remarks' => $request->remarks ?? 'Received from Branch',
    //             ];
                
    //         DB::table('admin_cod_history')->insert($admincodhistorydata);

    //         // Check if there's an existing record in AdminTotalCod for this branch
    //         $adminTotalCod = AdminTotalCod::where('branch_id', $branchId)->first();

    //         if ($adminTotalCod) {
    //             // Update existing record
    //             $adminTotalCod->amount += $request->amount;
    //             $adminTotalCod->save();
    //         } else {
    //             // Create new record
    //             AdminTotalCod::create([
    //                 'branch_id' => $branchId,
    //                 'amount' => $request->amount,
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'COD amount submitted to admin successfully.',
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error submitting COD amount: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    // 14 june 
    // public function submitCodToAdmin(Request $request)
    // {
    //     $branchId = Session::get('dyid');
    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    
    //     $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string|max:255',
    //     ]);
    
    //     $branch = Branch::find($branchId);
    //     if (!$branch) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Branch not found.',
    //         ], 404);
    //     }
    
    //     $totalBranchCodAmount = BranchtotalCod::where('branch_id', $branchId)->sum('amount');
    //     if ($totalBranchCodAmount < $request->amount) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Insufficient COD balance. Available: ₹' . $totalBranchCodAmount,
    //         ], 400);
    //     }
    
    //     try {
    //         DB::beginTransaction();
    
    //         BranchCodHistory::create([
    //             'branch_id' => $branchId,
    //             'amount' => $request->amount,
    //             'type' => 'Debited',
    //             'status' => 'Pending',
    //             'datetime' => $currentDateTime,
    //             'remarks' => 'Transfer to Admin',
    //         ]);
    
    //         $branchTotalCods = BranchtotalCod::where('branch_id', $branchId)
    //             ->orderBy('amount', 'desc')
    //             ->get();
    
    //         $remainingAmount = $request->amount;
    
    //         foreach ($branchTotalCods as $branchTotalCod) {
    //             if ($remainingAmount <= 0) {
    //                 break;
    //             }
            
    //             if ($branchTotalCod->amount > $remainingAmount) {
    //                 // Subtract part amount
    //                 $branchTotalCod->amount -= $remainingAmount;
    //                 $branchTotalCod->save();
    //                 $remainingAmount = 0;
    //                 break;
    //             } else {
    //                 // Use up this entire record
    //                 $remainingAmount -= $branchTotalCod->amount;
    //                 $branchTotalCod->amount = 0;
    //                 $branchTotalCod->save();
    //             }
    //         }
    
    
    //         DB::table('admin_cod_history')->insert([
    //             'branch_id' => $branchId,
    //             'amount' => $request->amount,
    //             'status' => 'Pending',
    //             'type' => 'Received',
    //             'datetime' => $currentDateTime,
    //             'remarks' => $request->remarks ?? 'Received from Branch',
    //         ]);
    
    //         // $adminTotalCod = AdminTotalCod::where('branch_id', $branchId)->first();
    
    //         // if ($adminTotalCod) {
    //         //     $adminTotalCod->amount += $request->amount;
    //         //     $adminTotalCod->save();
    //         // } else {
    //         //     AdminTotalCod::create([
    //         //         'branch_id' => $branchId,
    //         //         'amount' => $request->amount,
    //         //     ]);
    //         // }
    
    //         DB::commit();
    
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'COD amount submitted to admin successfully.',
    //         ]);
    
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error submitting COD amount: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // 14 june 
    // public function submitCodToAdmin(Request $request)
    // {
    //     $branchId = Session::get('dyid');
    //     $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    
    //     $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string|max:255',
    //     ]);
    
    //     $branch = Branch::find($branchId);
    //     if (!$branch) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Branch not found.',
    //         ], 404);
    //     }
    
    //     $totalBranchCodAmount = BranchtotalCod::where('branch_id', $branchId)->sum('amount');
    //     if ($totalBranchCodAmount < $request->amount) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Insufficient COD balance. Available: ₹' . $totalBranchCodAmount,
    //         ], 400);
    //     }
    
    //     try {
    //         DB::beginTransaction();
    
    //         BranchCodHistory::create([
    //             'branch_id' => $branchId,
    //             'amount' => $request->amount,
    //             'type' => 'Debited',
    //             'status' => 'Pending',
    //             'datetime' => $currentDateTime,
    //             'remarks' => 'Transfer to Admin',
    //         ]);
    
    //         $branchTotalCods = BranchtotalCod::where('branch_id', $branchId)
    //             ->orderBy('amount', 'desc')
    //             ->get();
    
    //         $remainingAmount = $request->amount;
    
    //         foreach ($branchTotalCods as $branchTotalCod) {
    //             if ($remainingAmount <= 0) {
    //                 break;
    //             }
            
    //             if ($branchTotalCod->amount > $remainingAmount) {
    //                 // Subtract part amount
    //                 $branchTotalCod->amount -= $remainingAmount;
    //                 $branchTotalCod->save();
    //                 $remainingAmount = 0;
    //                 break;
    //             } else {
    //                 // Use up this entire record
    //                 $remainingAmount -= $branchTotalCod->amount;
    //                 $branchTotalCod->amount = 0;
    //                 $branchTotalCod->save();
    //             }
    //         }
    
    
    //         DB::table('admin_cod_history')->insert([
    //             'branch_id' => $branchId,
    //             'amount' => $request->amount,
    //             'status' => 'Pending',
    //             'type' => 'Received',
    //             'datetime' => $currentDateTime,
    //             'remarks' => $request->remarks ?? 'Received from Branch',
    //         ]);
    
    //         // $adminTotalCod = AdminTotalCod::where('branch_id', $branchId)->first();
    
    //         // if ($adminTotalCod) {
    //         //     $adminTotalCod->amount += $request->amount;
    //         //     $adminTotalCod->save();
    //         // } else {
    //         //     AdminTotalCod::create([
    //         //         'branch_id' => $branchId,
    //         //         'amount' => $request->amount,
    //         //     ]);
    //         // }
    
    //         DB::commit();
    
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'COD amount submitted to admin successfully.',
    //         ]);
    
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error submitting COD amount: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    
    // 16 june latest code 
//     public function submitCodToAdmin(Request $request)
//     {
//         $branchId = Session::get('dyid');
//         $currentDateTime = now('Asia/Kolkata');
    
//         $request->validate([
//             'amount' => 'required|numeric|min:0',
//             'remarks' => 'nullable|string|max:255',
//         ]);
    
//         $branch = Branch::find($branchId);
//         if (!$branch) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Branch not found.',
//             ], 404);
//         }
    
//         $totalBranchCodAmount = BranchtotalCod::where('branch_id', $branchId)->sum('amount');
//         if ($totalBranchCodAmount < $request->amount) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Insufficient COD balance. Available: ₹' . $totalBranchCodAmount,
//             ], 400);
//         }
    
//         try {
//             DB::beginTransaction();
    
//             // ✅ Insert BranchCodHistory + get ID
//             $branchCod = BranchCodHistory::create([
//                 'branch_id' => $branchId,
//                 'amount' => $request->amount,
//                 'type' => 'Debited',
//                 'status' => 'Pending',
//                 'datetime' => $currentDateTime,
//                 'remarks' => 'Transfer to Admin',
//             ]);
    
//             $branchCodHistoryId = $branchCod->id;
    
//             // ✅ Deduct amount from branchtotal_cod
//             $branchTotalCods = BranchtotalCod::where('branch_id', $branchId)
//                 ->orderBy('amount', 'desc')
//                 ->get();
    
//             $remainingAmount = $request->amount;
    
//             foreach ($branchTotalCods as $branchTotalCod) {
//                 if ($remainingAmount <= 0) {
//                     break;
//                 }
    
//                 if ($branchTotalCod->amount > $remainingAmount) {
//                     $branchTotalCod->amount -= $remainingAmount;
//                     $branchTotalCod->save();
//                     $remainingAmount = 0;
//                     break;
//                 } else {
//                     $remainingAmount -= $branchTotalCod->amount;
//                     $branchTotalCod->amount = 0;
//                     $branchTotalCod->save();
//                 }
//             }
    
//             // ✅ Insert AdminCodHistory with branch_cod_history_id
//             AdminCodHistory::create([
//                 'branch_id' => $branchId,
//                 'branch_cod_history_id' => $branchCodHistoryId,
//                 'amount' => $request->amount,
//                 'status' => 'Pending',
//                 'type' => 'Received',
//                 'datetime' => $currentDateTime->format('d-m-Y | h:i:s A'),
//                 'remarks' => $request->remarks ?? 'Received from Branch',
//             ]);
    
//             DB::commit();
    
//             return response()->json([
//                 'success' => true,
//                 'message' => 'COD amount submitted to admin successfully.',
//             ]);
    
//         } catch (\Exception $e) {
//             DB::rollBack();
    
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Error submitting COD amount: ' . $e->getMessage(),
//             ], 500);
//         }
// }

    // latest code 
    // public function submitCodToAdmin(Request $request)
    // {
    //     $branchId = Session::get('dyid');
    //     $currentDateTime = now('Asia/Kolkata');

    //     $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string|max:255',
    //     ]);

    //     $branch = Branch::find($branchId);
    //     if (!$branch) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Branch not found.',
    //         ], 404);
    //     }

    //     $totalBranchCodAmount = BranchtotalCod::where('branch_id', $branchId)->sum('amount');
    //     if ($totalBranchCodAmount < $request->amount) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Insufficient COD balance. Available: ₹' . $totalBranchCodAmount,
    //         ], 400);
    //     }

    //     try {
    //         DB::beginTransaction();

    //         // ✅ Insert BranchCodHistory + get ID
    //         $branchCod = BranchCodHistory::create([
    //             'branch_id' => $branchId,
    //             'amount' => $request->amount,
    //             'type' => 'Debited',
    //             'status' => 'Pending',
    //             'datetime' => $currentDateTime,
    //             'remarks' => 'Transfer to Admin',
    //         ]);

    //         $branchCodHistoryId = $branchCod->id;

    //         // ✅ Insert AdminCodHistory with branch_cod_history_id
    //         AdminCodHistory::create([
    //             'branch_id' => $branchId,
    //             'branch_cod_history_id' => $branchCodHistoryId,
    //             'amount' => $request->amount,
    //             'status' => 'Pending',
    //             'type' => 'Received',
    //             'datetime' => $currentDateTime->format('d-m-Y | h:i:s A'),
    //             'remarks' => $request->remarks ?? 'Received from Branch',
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'COD amount submitted to admin successfully.',
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error submitting COD amount: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    
    
        public function submitCodToAdmin(Request $request)
{
    $branchId = Session::get('dyid');

    // ✅ Format datetime as "19-06-2025 | 12:21:50 PM"
    $currentDateTime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');

    $request->validate([
        'amount' => 'required|numeric|min:0',
        'remarks' => 'nullable|string|max:255',
    ]);

    $branch = Branch::find($branchId);
    if (!$branch) {
        return response()->json([
            'success' => false,
            'message' => 'Branch not found.',
        ], 404);
    }

    $totalBranchCodAmount = BranchtotalCod::where('branch_id', $branchId)->sum('amount');
    if ($totalBranchCodAmount < $request->amount) {
        return response()->json([
            'success' => false,
            'message' => 'Insufficient COD balance. Available: ₹' . $totalBranchCodAmount,
        ], 400);
    }

    try {
        DB::beginTransaction();

        // ✅ Insert BranchCodHistory + get ID
        $branchCod = BranchCodHistory::create([
            'branch_id' => $branchId,
            'amount' => $request->amount,
            'type' => 'Debited',
            'status' => 'Pending',
            'datetime' => $currentDateTime,
            'remarks' => 'Transfer to Admin',
        ]);

        $branchCodHistoryId = $branchCod->id;

        // ✅ Insert AdminCodHistory with branch_cod_history_id
        AdminCodHistory::create([
            'branch_id' => $branchId,
            'branch_cod_history_id' => $branchCodHistoryId,
            'amount' => $request->amount,
            'status' => 'Pending',
            'type' => 'Received',
            'datetime' => $currentDateTime,
            'remarks' => $request->remarks ?? 'Received from Branch',
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'COD amount submitted to admin successfully.',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Error submitting COD amount: ' . $e->getMessage(),
        ], 500);
    }
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

    // public function otherBranchOrder()
    // {
    //     $id = Session::get('dyid');
    //     $delivery = Branch::find($id);
    //     $pinCodes = explode(',', $delivery->pincode);

    //     $receiverPinCodes = Order::whereNull('sender_order_status')
    //         ->whereIn('sender_pincode', $pinCodes)
    //         ->whereNotIn('receiver_pincode', $pinCodes)
    //         ->select('*')
    //         ->distinct()
    //         ->pluck('receiver_pincode');

    //     return view('delivery.otherBranchOrderPinCode', compact('receiverPinCodes'));
    // }


    // public function otherBranchOrder()
    // {
    //     $id = Session::get('dyid');
    //     $delivery = Branch::find($id);
    //     $pinCodes = explode(',', trim($delivery->pincode, ','));

    //     $receiverPinCodes = Order::whereNull('sender_order_status')->where('order_status', 'Delivered to branch')
    //         ->whereIn('sender_pincode', $pinCodes)
    //         ->whereNotIn('receiver_pincode', $pinCodes)
    //         ->select('receiver_pincode')
    //         ->distinct()
    //         ->pluck('receiver_pincode')
    //         ->toArray();

    //     // Create array for each branch with matching pincodes
    //     $branchMap = [];

    //     foreach ($receiverPinCodes as $pin) {
    //         $matchedBranches = Branch::where('pincode', 'LIKE', "%$pin%")->where('type','Delivery')->get();

    //         foreach ($matchedBranches as $branch) {
    //             // Use branch ID as key to avoid duplicates
    //             if (!isset($branchMap[$branch->id])) {
    //                 $branchMap[$branch->id] = [
    //                     'branchName' => $branch->fullname,
    //                     'receiverPinCodes' => [$pin],
    //                 ];
    //             } else {
    //                 // Add this pincode to the existing branch's pincode list
    //                 if (!in_array($pin, $branchMap[$branch->id]['receiverPinCodes'])) {
    //                     $branchMap[$branch->id]['receiverPinCodes'][] = $pin;
    //                 }
    //             }
    //         }

    //         // If no branch matches this pincode, add it as unassigned
    //         if (!$matchedBranches->count()) {
    //             $key = 'unassigned_' . $pin;
    //             $branchMap[$key] = [
    //                 'branchName' => 'Unassigned',
    //                 'receiverPinCodes' => [$pin],
    //             ];
    //         }
    //     }

    //     // Convert to array format expected by the view
    //     $results = [];
    //     foreach ($branchMap as $branchInfo) {
    //         $results[] = [
    //             'receiverPinCode' => implode(',', $branchInfo['receiverPinCodes']),
    //             'matchedBranches' => $branchInfo['branchName'],
    //             'branchId' => $branchInfo['id'],
    //         ];
    //     }
        
        

    //     return view('delivery.otherBranchOrderPinCode', ['results' => $results]);
    // }
    
    
    public function otherBranchOrder()
    {
    $id = Session::get('dyid');
    $delivery = Branch::find($id);
    $pinCodes = explode(',', trim($delivery->pincode, ','));

    $receiverPinCodes = Order::whereNull('sender_order_status')
        ->where('order_status', 'Delivered to branch')
        ->whereIn('sender_pincode', $pinCodes)
        ->whereNotIn('receiver_pincode', $pinCodes)
        ->select('receiver_pincode')
        ->distinct()
        ->pluck('receiver_pincode')
        ->toArray();

    // Create array for each branch with matching pincodes
    $branchMap = [];

    foreach ($receiverPinCodes as $pin) {
        $matchedBranches = Branch::where('pincode', 'LIKE', "%$pin%")
            ->where('type', 'Delivery')
            ->get();

        foreach ($matchedBranches as $branch) {
            // Use branch ID as key to avoid duplicates
            if (!isset($branchMap[$branch->id])) {
                $branchMap[$branch->id] = [
                    'branchName' => $branch->fullname,
                    'receiverPinCodes' => [$pin],
                    'id' => $branch->id, // Explicitly include id
                ];
            } else {
                // Add this pincode to the existing branch's pincode list
                if (!in_array($pin, $branchMap[$branch->id]['receiverPinCodes'])) {
                    $branchMap[$branch->id]['receiverPinCodes'][] = $pin;
                }
            }
        }

        // If no branch matches this pincode, add it as unassigned
        if (!$matchedBranches->count()) {
            $key = 'unassigned_' . $pin;
            $branchMap[$key] = [
                'branchName' => 'Unassigned',
                'receiverPinCodes' => [$pin],
                'id' => null, // Add null id for unassigned
            ];
        }
    }

    // Convert to array format expected by the view
    $results = [];
    foreach ($branchMap as $branchInfo) {
        $results[] = [
            'receiverPinCode' => implode(',', $branchInfo['receiverPinCodes']),
            'matchedBranches' => $branchInfo['branchName'],
            'branchId' => $branchInfo['id'], // Now safe to access
        ];
    }
    return view('delivery.otherBranchOrderPinCode', ['results' => $results]);
}


    // in Delivery Panel After Filter Assign DeliveryBoy 2 june latest working code 
    public function getOrdersByDeliveryBoyPincode(Request $request)
    {
        // Get current date in the required format
        $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');

        // Build the query for orders
        $ordersQuery = Order::with(['deliveryBoy'])
            ->where('datetime', 'like', $dateTime . '%')
            ->where('transfer_other_branch', 'false')
            ->where('order_status', 'Delivered to branch')
            ->orderBy('id', 'desc');
            
        // Filter by delivery boy pincode if provided
        if (!empty($request->deliveryBoyId)) {
            $request->validate(['deliveryBoyId' => 'exists:dlyboy,id']);
            $deliveryBoy = DlyBoy::findOrFail($request->deliveryBoyId);
            $deliveryBoyPincodes = array_filter(array_map('trim', explode(',', $deliveryBoy->pincode)));
            $ordersQuery->whereIn('sender_pincode', $deliveryBoyPincodes);
        }
        // Fetch the orders
        $orders = $ordersQuery->get();
        
        // Map the orders to the desired format
        $ordersData = $orders->map(function ($orderData, $key) {
            return [
                'id' => $orderData->id,
                'index' => $key + 1,
                'receiver_pincode' => $orderData->receiver_pincode ?? 'N/A',
                'order_id' => $orderData->order_id ?? 'N/A',
                'receiver_name' => $orderData->receiver_name ?? $orderData->sender_name ?? 'N/A',
                'receiver_cnumber' => $orderData->receiver_cnumber ?? $orderData->sender_number ?? 'N/A',
                'receiver_email' => $orderData->receiver_email ?? $orderData->sender_email ?? 'N/A',
                'receiver_add' => $orderData->receiver_add ?? $orderData->sender_address ?? 'N/A',
                'sender_name' => $orderData->sender_name ?? 'N/A',
                'sender_number' => $orderData->sender_number ?? 'N/A',
                'sender_email' => $orderData->sender_email ?? 'N/A',
                'sender_address' => $orderData->sender_add ?? $orderData->sender_address ?? 'N/A',
                'payment_mode' => $orderData->payment_mode ?? 'N/A',
                'cod_amount' => $orderData->codAmount ?? '0',
                'insurance' => isset($orderData->insurance) ? ($orderData->insurance ? 'Yes' : 'No') : 'N/A',
                'created_at' => isset($orderData->created_at) ? Carbon::parse($orderData->created_at)->format('d-m-Y H:i:s') : 'N/A',
                'assign_to' => $orderData->assign_to,
                'delivery_boy_name' => $orderData->deliveryBoy ? $orderData->deliveryBoy->name : 'Unknown',
            ];
        })->toArray();
        $response = [
            'status' => 'success',
            'data' => $ordersData,
            'message' => $orders->count() > 0 ? 'Orders retrieved successfully' : 'No orders found',
        ];
        return response()->json($response, 200);
    }
    
    
    // public function otherBranchOrderDetails($branchId)
    // {
    // // Get the current branch
    // $id = Session::get('dyid'); 
    // $delivery = Branch::findOrFail($branchId); 
    
    // $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');

    // $branchPinCodes = explode(',', trim($delivery->pincode, ','));

    // $orders = Order::where('datetime', 'like', $dateTime . '%')->
    //     whereIn('receiver_pincode', $branchPinCodes)->where('transfer_other_branch', 'false')
    //     ->where('order_status', 'Delivered to branch')
    //     ->with('deliveryBoy')
    //     ->orderBy('id', 'desc')
    //     ->get();

    // return view('delivery.newshoworderDeatails', compact('orders', 'delivery'));
    // }
    
    
    public function otherBranchOrderDetails($branchId)
    {
        // Get the current branch ID from session
        $currentBranchId = Session::get('dyid');
        if (!$currentBranchId) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        // Get current branch details
        $currentBranch = Branch::findOrFail($currentBranchId);
        $currentBranchPinCodes = explode(',', trim($currentBranch->pincode, ','));

        // Get the specified branch details
        $delivery = Branch::findOrFail($branchId);
        $branchPinCodes = explode(',', trim($delivery->pincode, ','));

        // Store branch pincodes in session for use in Blade template
        Session::put('branch_pincodes', $delivery->pincode);

        // Get current date in d-m-Y format
        $dateTime = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y');

        // Query orders based on toDayOtherBranchOrder logic
        // $orders = Order::where('datetime', 'like', $dateTime . '%')
        //     ->where('transfer_other_branch', 'false') 
        //     ->whereIn('sender_pincode', $currentBranchPinCodes) 
        //     ->whereIn('receiver_pincode', $branchPinCodes) 
        //     ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered', 'Cancelled'])
        //     ->with('deliveryBoy')
        //     ->orderBy('id', 'asc') 
        //     ->get();
        
         $orders = Order::where('transfer_other_branch', 'false') 
            ->whereIn('sender_pincode', $currentBranchPinCodes) 
            ->whereIn('receiver_pincode', $branchPinCodes) 
            ->whereNotIn('order_status', ['Booked', 'Item Picked Up', 'Item Not Picked Up', 'Delivered', 'Cancelled'])
            ->with('deliveryBoy')
            ->orderBy('id', 'asc') 
            ->get();
            
   
         return view('delivery.newshoworderDeatails', compact('orders', 'delivery'));
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
            $data = Order::where(['service_type' => $filterType, 'sender_order_status' => 'Delivered'])->whereIn('sender_order_pin', $pinCodes)->get();

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

    public function assignOrders(Request $request)
    {
        try {
            $orderIds = explode(',', $request->order_ids);
            $deliveryBoyId = $request->delivery_boy_id;
            $id = Session::get('dyid');

            foreach ($orderIds as $orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    // Log previous assignment if exists
                    if ($order->assign_to) {
                        OrderAssignmentLog::create([
                            'order_id' => $order->id,
                            'previous_delivery_boy_id' => $order->assign_to,
                            'new_delivery_boy_id' => $deliveryBoyId,
                            'assigned_by' => $id,
                            'reason' => 'Reassignment'
                        ]);
                    }

                    $order->assign_to = $deliveryBoyId;
                    $order->assign_by = $id;
                    $order->status_message = $order->assign_to ? 'Reassigned to delivery boy' : 'Assigned to delivery boy';
                    $order->save();

                    // Handle COD orders
                    if ($order->payment_mode == 'COD') {
                        COD::updateOrCreate(
                            ['order_id' => $order->id],
                            [
                                'delivery_boy_id' => $deliveryBoyId,
                                'datetime' => now('Asia/Kolkata')->format('d-m-Y | h:i:s A')
                            ]
                        );
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($orderIds) > 1 ? 'Orders assigned successfully!' : 'Order assigned successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error assigning orders: ' . $e->getMessage()
            ]);
        }
    }
}





