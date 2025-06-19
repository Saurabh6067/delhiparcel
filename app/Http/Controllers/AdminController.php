<?php

namespace App\Http\Controllers;

use App\Models\AdminCodHistory;
use App\Models\AdminTotalCod;
use App\Models\Branch;
use App\Models\Category;
use App\Models\COD;
use App\Models\CodAmount;
use App\Models\DlyBoy;
use App\Models\Enquiry;
use App\Models\FeedBack;
use App\Models\Order;
use App\Models\EstimatedService;
use App\Models\BranchTotalCod; // add 
use App\Models\PinCode;
use App\Models\Service;
use App\Models\Servicetype;
use App\Models\User;
use App\Models\Wallet;
use App\Models\CodSellerAmount;
use App\Models\WebOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function adminLogin(Request $request)
    {
        $user = User::where('email', $request->email)->where('type', $request->type)->first();

        if ($user && Hash::check($request->pwd, $user->password)) {
            Session::put('uid', $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Welcome to your Dashboard!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
            ]);
        }
    }

    public function adminLogout(Request $request)
    {
        $request->session()->forget('uid');
        return redirect('/AdminPanel')->with([
            'success' => true,
            'message' => 'You have successful LogOut!'
        ]);
    }
    
    public function serviceEstimitedTime(Request $request)
    {
        return view('admin.deliveryEstimatedtime');
    }
    public function storeEstimatedService(Request $request)
    {
        // Validate the request data
        $request->validate([
            'service_type' => 'required|in:ex,SuperExpress,ss',
            'time' => 'required|date_format:H:i',
        ]);

        try {
            // Create a new estimated service
            EstimatedService::create([
                'service_type' => $request->service_type,
                'time' => $request->time,
                'status' => 1, // Default status (active)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Service added successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add service: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getEstimatedServices(Request $request)
    {
        $services = EstimatedService::all()->map(function ($service, $index) {
            return [
                'sr_no' => $index + 1,
                'service_type' => $service->service_type === 'ex' ? 'Express' : ($service->service_type === 'SuperExpress' ? 'Super Express' : 'Standard'),
                'time' => $service->time,
                'status' => $service->status ? 'Active' : 'Inactive',
                'action' => '<button class="btn btn-sm btn-warning edit-service" data-id="' . $service->id . '">Edit</button> ' .
                    '<button class="btn btn-sm btn-danger delete-service" data-id="' . $service->id . '">Delete</button>'
            ];
        });

        return response()->json(['data' => $services]);
    }
    public function updateEstimatedService(Request $request, $id)
    {
        $request->validate([
            'service_type' => 'required|in:ex,SuperExpress,ss',
            'time' => 'required|date_format:H:i',
            'status' => 'required|in:0,1',
        ]);

        try {
            $service = EstimatedService::findOrFail($id);
            $service->update([
                'service_type' => $request->service_type,
                'time' => $request->time,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update service: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deleteEstimatedService($id)
    {
        try {
            $service = EstimatedService::findOrFail($id);
            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getEstimatedService($id)
    {
        try {
            $service = EstimatedService::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $service
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found: ' . $e->getMessage()
            ], 404);
        }
    }

    
    // all cod history manage delivery panel  12 june 
    // public function adminupdateCodStatus(Request $request)
    // {
    //     try {
    //         return DB::transaction(function () use ($request) {
    //             $status = $request->status;

    //             $adminCod = AdminCodHistory::where('id', $request->record_id)->first();
               
    //             if (!$adminCod) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'admin_cod_history record not found.',
    //                 ], 404);
    //             }

    //             if ($status === 'Approve') {
    //                 $adminCod->status = 'Approve';
    //                 $adminCod->save();
    //             } elseif ($status === 'Reject') {
    //                 $branchId = $adminCod->branch_id;
    //                 $amount = $adminCod->amount;

    //                 // admin_total_cod table update
    //                 $adminTotalCod = DB::table('admin_total_cod')->where('branch_id', $branchId)->first();

    //                 if ($adminTotalCod) {
    //                     DB::table('admin_total_cod')
    //                         ->where('branch_id', $branchId)
    //                         ->update([
    //                             'amount' => $adminTotalCod->amount - $amount
    //                         ]);
    //                     }

    //                 // branch_total_cod table update
    //                 $branchTotalCod = DB::table('branch_total_cod')->where('branch_id', $branchId)->first();
    //                 if ($branchTotalCod) {
    //                     DB::table('branch_total_cod')
    //                         ->where('branch_id', $branchId)
    //                         ->update([
    //                             'amount' => $branchTotalCod->amount + $amount
    //                         ]);
    //                     }
                        
    //                 // update status in admin_cod_history
    //                 $adminCod->status = 'Reject';
    //                 $adminCod->save();
    //             }

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Status updated successfully.',
    //             ]);
    //         });
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    
    
    // public function adminupdateCodStatus(Request $request)
    // {
    //     try {
    //         return DB::transaction(function () use ($request) {
    //             $status = $request->status;
    //             $adminCod = AdminCodHistory::where('id', $request->record_id)->first();
    //             if (!$adminCod) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'admin_cod_history record not found.',
    //                 ], 404);
    //             }
    
    //             if ($status === 'Approve') {
    //                 $adminCod->status = 'Approve';
    //                 $adminCod->save();
    //             } elseif ($status === 'Reject') {
    //                 $branchId = $adminCod->branch_id;
    //                 $branch_cod_history_id = $adminCod->branch_cod_history_id; // addon 
    //                 $amount = $adminCod->amount;
    
    //                 // admin_total_cod table update
    //                 $adminTotalCod = DB::table('admin_total_cod')->where('branch_id', $branchId)->first();
    //                 if ($adminTotalCod) {
    //                     DB::table('admin_total_cod')
    //                         ->where('branch_id', $branchId)
    //                         ->update([
    //                             'amount' => $adminTotalCod->amount - $amount
    //                         ]);
    //                 }
    
    //                 // branch_total_cod table update
    //                 $branchTotalCod = DB::table('branch_total_cod')->where('branch_id', $branchId)->first();
    //                 if ($branchTotalCod) {
    //                     DB::table('branch_total_cod')
    //                         ->where('branch_id', $branchId)
    //                         ->update([
    //                             'amount' => $branchTotalCod->amount + $amount
    //                         ]);
    //                 } else {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'branch_total_cod record not found for branch ID: ' . $branchId,
    //                     ], 404);
    //                 }
                    
    //                 // update status in admin_cod_history
    //                 $adminCod->status = 'Reject';
    //                 $adminCod->save();
    //             }
    
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Status updated successfully.',
    //             ]);
    //         });
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    
    // 14 june 
//   public function adminupdateCodStatus(Request $request)
//   {
//     try {
//         return DB::transaction(function () use ($request) {
//             $status = $request->status;

//             // Fetch admin_cod_history with branch_cod_history_id + id
//             $adminCod = AdminCodHistory::where('id', $request->record_id)
//                 ->first();

//             if (!$adminCod) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'admin_cod_history record not found.',
//                 ], 404);
//             }

//             if ($status === 'Approve') {
//                 $adminCod->status = 'Approve';
//                 $adminCod->save();

//             } elseif ($status === 'Reject') {
//                 $branchId = $adminCod->branch_id;
//                 $amount = $adminCod->amount;

//                 // Update admin_total_cod
//                 $adminTotalCod = DB::table('admin_total_cod')
//                     ->where('branch_id', $branchId)
//                     ->first();

//                 if ($adminTotalCod) {
//                     DB::table('admin_total_cod')
//                         ->where('branch_id', $branchId)
//                         ->update([
//                             'amount' => $adminTotalCod->amount - $amount
//                         ]);
//                 }

//                 // Update branch_total_cod with branch_id + branch_cod_history_id
//                 $branchTotalCod = DB::table('branch_total_cod')
//                     ->where('branch_id', $branchId)
//                     ->first();

//                 if ($branchTotalCod) {
//                     DB::table('branch_total_cod')
//                         ->where('branch_id', $branchId)
//                         ->update([
//                             'amount' => $branchTotalCod->amount + $amount
//                         ]);
//                 } else {
//                     return response()->json([
//                         'success' => false,
//                         'message' => 'branch_total_cod record not found for branch ID: ' . $branchId,
//                     ], 404);
//                 }

//                 // Update admin_cod_history status
//                 $adminCod->status = 'Reject';
//                 $adminCod->save();
//             }

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Status updated successfully.',
//             ]);
//         });
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Error: ' . $e->getMessage(),
//         ], 500);
//     }
// }

    
    // 16 june latest code 
//     public function adminupdateCodStatus(Request $request)
//     {
//         try {
//             return DB::transaction(function () use ($request) {
//                 $status = $request->status;
    
//                 // Fetch admin_cod_history record
//                 $adminCod = AdminCodHistory::where('id', $request->record_id)
//                     ->first();
    
//                 if (!$adminCod) {
//                     return response()->json([
//                         'success' => false,
//                         'message' => 'admin_cod_history record not found.',
//                     ], 404);
//                 }
    
//                 $branchId = $adminCod->branch_id;
//                 $amount = $adminCod->amount;
    
//                 if ($status === 'Approve') {
//                     // Update admin_total_cod
//                     $adminTotalCod = DB::table('admin_total_cod')
//                         ->where('branch_id', $branchId)
//                         ->first();
    
//                     if ($adminTotalCod) {
//                         DB::table('admin_total_cod')
//                             ->where('branch_id', $branchId)
//                             ->update([
//                                 'amount' => $adminTotalCod->amount + $amount
//                             ]);
//                     } else {
//                         DB::table('admin_total_cod')->insert([
//                             'branch_id' => $branchId,
//                             'amount' => $amount
//                         ]);
//                     }
    
//                     // Update admin_cod_history status
//                     $adminCod->status = 'Approve';
//                     $adminCod->save();
    
//                 } elseif ($status === 'Reject') {
//                     // Update branch_total_cod (admin_total_cod me kuch nahi hoga)
//                     $branchTotalCod = DB::table('branch_total_cod')
//                         ->where('branch_id', $branchId)
//                         ->first();
    
//                     if ($branchTotalCod) {
//                         DB::table('branch_total_cod')
//                             ->where('branch_id', $branchId)
//                             ->update([
//                                 'amount' => $branchTotalCod->amount + $amount
//                             ]);
//                     } else {
//                         DB::table('branch_total_cod')->insert([
//                             'branch_id' => $branchId,
//                             'amount' => $amount
//                         ]);
//                     }
    
//                     // Update admin_cod_history status
//                     $adminCod->status = 'Reject';
//                     $adminCod->save();
//                 }
    
//                 return response()->json([
//                     'success' => true,
//                     'message' => 'Status updated successfully.',
//                 ]);
//             });
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Error: ' . $e->getMessage(),
//             ], 500);
//         }
// }

    
    // latest code 
    // public function adminupdateCodStatus(Request $request)
    // {
    //     try {
    //         return DB::transaction(function () use ($request) {
    //             $status = $request->status;
    
    //             // Fetch admin_cod_history record_id is primary id 
    //             $adminCod = AdminCodHistory::where('id', $request->record_id)   
    //                 ->first();
    
    //             if (!$adminCod) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'admin_cod_history record not found.',
    //                 ], 404);
    //             }
    
    //             $branchId = $adminCod->branch_id;
    //             $amount = $adminCod->amount;
    
    //             if ($status === 'Approve') {

    //                 // ✅ Decrease from branch_total_cod
    //                 $branchTotalCod = DB::table('branch_total_cod')
    //                     ->where('branch_id', $branchId)
    //                     ->first();
    //                 if ($branchTotalCod) {
    //                     $newBranchAmount = $branchTotalCod->amount - $amount;
    //                     if ($newBranchAmount < 0) $newBranchAmount = 0;
    //                     DB::table('branch_total_cod')
    //                         ->where('branch_id', $branchId)
    //                         ->update([
    //                             'amount' => $newBranchAmount
    //                         ]);
    //                 }

    //                 // Update admin_total_cod
    //                 $adminTotalCod = DB::table('admin_total_cod')
    //                     ->where('branch_id', $branchId)
    //                     ->first();
    
    //                 if ($adminTotalCod) {
    //                     DB::table('admin_total_cod')
    //                         ->where('branch_id', $branchId)
    //                         ->update([
    //                             'amount' => $adminTotalCod->amount + $amount
    //                         ]);
    //                 } else {
    //                     DB::table('admin_total_cod')->insert([
    //                         'branch_id' => $branchId,
    //                         'amount' => $amount
    //                     ]);
    //                 }
    
    //                 // ✅ Update branch_cod_history status to 'Approve'
    //                 DB::table('branch_cod_history')
    //                 ->where('id', $adminCod->branch_cod_history_id)
    //                 ->update(['status' => 'Approve']);

    //                 // Update admin_cod_history status
    //                 $adminCod->status = 'Approve';
    //                 $adminCod->save();
    
    //             } elseif ($status === 'Reject') {
    //                 // ✅ Update branch_cod_history status to 'Reject'
    //                 DB::table('branch_cod_history')
    //                     ->where('id', $adminCod->branch_cod_history_id)
    //                     ->update(['status' => 'Reject']);
        
    //                     // Update admin_cod_history status
    //                     $adminCod->status = 'Reject';
    //                     $adminCod->save();
    //                 }
    
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Status updated successfully.',
    //             ]);
    //         });
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }


        public function adminupdateCodStatus(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $status = $request->status;
    
                // Fetch admin_cod_history record_id is primary id 
                $adminCod = AdminCodHistory::where('id', $request->record_id)   
                    ->first();
    
                if (!$adminCod) {
                    return response()->json([
                        'success' => false,
                        'message' => 'admin_cod_history record not found.',
                    ], 404);
                }
    
                $branchId = $adminCod->branch_id;
                $amount = $adminCod->amount;
    
                if ($status === 'Approve') {

                    // ✅ Decrease from branch_total_cod
                    $branchTotalCod = DB::table('branch_total_cod')
                        ->where('branch_id', $branchId)
                        ->first();

                    if ($branchTotalCod) {
                        $newBranchAmount = $branchTotalCod->amount - $amount;   
                        DB::table('branch_total_cod')
                            ->where('branch_id', $branchId)
                            ->update([
                                'amount' => $newBranchAmount
                            ]);
                    }


                    // Update admin_total_cod
                    $adminTotalCod = DB::table('admin_total_cod')
                        ->where('branch_id', $branchId)
                        ->first();
    
                    if ($adminTotalCod) {
                        DB::table('admin_total_cod')
                            ->where('branch_id', $branchId)
                            ->update([
                                'amount' => $adminTotalCod->amount + $amount
                            ]);
                    } else {
                        DB::table('admin_total_cod')->insert([
                            'branch_id' => $branchId,
                            'amount' => $amount
                        ]);
                    }
    
                    // ✅ Update branch_cod_history status to 'Approve'
                    DB::table('branch_cod_history')
                    ->where('id', $adminCod->branch_cod_history_id)
                    ->update(['status' => 'Approve']);

                    // Update admin_cod_history status
                    $adminCod->status = 'Approve';
                    $adminCod->save();
    
                } elseif ($status === 'Reject') {
                    // ✅ Update branch_cod_history status to 'Reject'
                    DB::table('branch_cod_history')
                        ->where('id', $adminCod->branch_cod_history_id)
                        ->update(['status' => 'Reject']);
        
                        // Update admin_cod_history status
                        $adminCod->status = 'Reject';
                        $adminCod->save();
                    }
    
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully.',
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    
    
    
    
    
    
    
    // public function adminDashboard()
    // {
    //     // toDayOrder details
    //     $dateTime = now('Asia/Kolkata')->format('d-m-Y');
    //     $ordersQuery = Order::where('datetime', 'like', $dateTime . '%');
    //     $toDayOrder = $ordersQuery->count();
    //     // Clone the base query to avoid modification issues
    //     $toDayPendingOrder = (clone $ordersQuery)->where('order_status', 'Booked')->count();
    //     $toDayOrderPicUp = (clone $ordersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
    //     $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
    //     $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();


    //     $branch = Branch::whereIn('type', ['Booking', 'Delivery'])->count();
    //     $sellerbranch = Branch::where('type', 'Seller')->count();
    //     $cat = Category::count();
    //     $pin = PinCode::count();
    //     $eq = Enquiry::count();
    //     $dBoy = DlyBoy::count();

    //     $todayWallet = Wallet::where('datetime', 'like', $dateTime . '%')->where('msg', 'credit')->get();
    //     $ordersQuery = COD::where('datetime', 'like', $dateTime . '%');
    //     $data = $ordersQuery->orderBy('id', 'desc')->get();

    //     $directOrder = WebOrder::where('order_status', 'Booked')->where('datetime', 'like', $dateTime . '%')->count();

    //     return view('admin.dashboard', compact('branch', 'sellerbranch', 'cat', 'pin', 'eq', 'dBoy', 'data', 'toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'todayWallet', 'directOrder'));
    // }
    
    // public function by suarbh adminDashboard()
    // {
    //     $dateTime = now('Asia/Kolkata')->format('d-m-Y');
    //     $ordersQuery = Order::where('datetime', 'like', $dateTime . '%');
        
        
    //     // today revenue sum 
    //     $totalOrder = Order::all();
    //     $totalOrders = $totalOrder->count();
    //     $totalPendingOrder = (clone $totalOrder)->where('order_status', 'Booked')->orWhere('order_status', 'Item Not Picked Up')->count();
    //     $totalOrderPicUp = (clone $totalOrder)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled', 'Item Not Picked Up'])->count();
    //     $totalCompleteOrder = (clone $totalOrder)->where('order_status', 'Delivered')->count();
    //     $totalCancelledOrder = (clone $totalOrder)->where('order_status', 'Cancelled')->count();
        
    //     $ordersRevenueQuery = Order::where('datetime', 'like', $dateTime . '%');
    //     // $toDayRevenueOrder = $ordersRevenueQuery->count();
    //     $toDayRevenueOrder = $ordersRevenueQuery->sum('price');
        
    //     // Clone the base query to avoid modification issues
    //     $toDayOrder = $ordersQuery->count();
    //     $toDayPendingOrder = (clone $ordersQuery)->where('order_status', 'Booked')->orWhere('order_status', 'Item Not Picked Up')->count();
    //     $toDayOrderPicUp = (clone $ordersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up'])->count();
    //     $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
    //     $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();


    //     $branch = Branch::whereIn('type', ['Booking', 'Delivery'])->count();
    //     $sellerbranch = Branch::where('type', 'Seller')->count();
    //     $cat = Category::count();
    //     $pin = PinCode::count();
    //     $eq = Enquiry::count();
    //     $dBoy = DlyBoy::count();

    //     $todayWallet = Wallet::where('datetime', 'like', $dateTime . '%')->where('msg', 'credit')->get();
    //     $ordersQuery = COD::where('datetime', 'like', $dateTime . '%');
    //     $data = $ordersQuery->orderBy('id', 'desc')->get();

    //     $directOrder = WebOrder::where('order_status', 'Booked')->where('datetime', 'like', $dateTime . '%')->count();

    //     return view('admin.dashboard', compact('branch', 'sellerbranch', 'cat', 'pin', 'eq', 'dBoy', 'data', 'toDayOrder', 'toDayRevenueOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'todayWallet', 'directOrder','totalOrders','totalPendingOrder','totalOrderPicUp','totalCompleteOrder','totalCancelledOrder'));
    // }
    


    // 16 june 
    // public function adminDashboard()
    // {
    //     $dateTime = now('Asia/Kolkata')->format('d-m-Y');
    //     $ordersQuery = Order::where('datetime', 'like', $dateTime . '%');
    
    //     // Total orders and their statuses
    //     $totalOrderQuery = Order::query();
    //     $totalOrders = $totalOrderQuery->count();
    //     $totalPendingOrder = (clone $totalOrderQuery)->where(function ($query) {
    //         $query->where('order_status', 'Booked')
    //               ->orWhere('order_status', 'Item Not Picked Up');
    //     })->count();
        
    //      $totalPendingSuperExpress = (clone $totalOrderQuery)->where(function ($query) {
    //         $query->where('service_type', 'SuperExpress')
    //               ->whereNotIn('order_status', ['Delivered','Cancelled']);
    //     })->count();
        
    //     $totalOrderPicUp = (clone $totalOrderQuery)->where('order_status', 'Item Picked Up')->count();
    //     $totalCompleteOrder = (clone $totalOrderQuery)->where('order_status', 'Delivered')->count();
    //     $totalCancelledOrder = (clone $totalOrderQuery)->where('order_status', 'Cancelled')->count();
    
    //     // Today's orders and revenue
    //     $sellerrevineue = Wallet::where('datetime', 'like', $dateTime . '%')->where('msg','Order Cancelled');
    //     $sellerBookingsum = $sellerrevineue->sum('c_amount');
    //     // $ordersRevenueQuery = Order::where('datetime', 'like', $dateTime . '%');
    //     // $toDayRevenue = $ordersRevenueQuery->sum('price');
    //     // $toDayRevenueOrder = $toDayRevenue - $sellerBookingsum;
    
    //     $ordersRevenueQuery = Order::whereNot('order_status','Cancelled')->where('datetime', 'like', $dateTime . '%');
    //     $toDayRevenue = $ordersRevenueQuery->sum('price');
    //     $toDayRevenueOrder = $toDayRevenue;
    //     // $toDayRevenueOrder = $toDayRevenue - $sellerBookingsum;
        
    //     // Today's order statuses
    //     $toDayOrder = (clone $ordersQuery)->count();
    
    //     $toDayPendingOrder = (clone $ordersQuery)->where(function ($query) {
    //         $query->where('order_status', 'Booked')
    //               ->orWhere('order_status', 'Item Not Picked Up');
    //     })->count();
        
    //     $todayOtherBranchOrders = (clone $ordersQuery)->where(function ($query) {
    //         $query->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch','Not Delivered']);
                  
    //     })->count();
        
    //     $totalOtherBranchOrders = (clone $totalOrderQuery)->where(function ($query) {
    //         $query->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch']);
                  
    //     })->count();
        
    //     $toDayOrderPicUp = (clone $ordersQuery)->where('order_status', 'Item Picked Up')->count();
    //     $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
    //     $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();
    
    //     // Other counts
    //     $branch = Branch::whereIn('type', ['Delivery'])->count();
    //     $sellerbranch = Branch::where('type', 'Seller')->count();
    //     $bookingbranch = Branch::where('type', 'Booking')->count();
    //     $cat = Category::count();
    //     $pin = PinCode::count();
    //     $eq = Enquiry::count();
    //     $dBoy = DlyBoy::count();
    
    //     // Today's wallet and COD orders
    //     $todayWallet = Wallet::where('datetime', 'like', $dateTime . '%')->where('msg', 'credit')->get();
    //     // $codQuery = CodAmount::where('datetime', 'like', $dateTime . '%');
        
    //     // $data = $codQuery->whereHas('order', function ($query) {
    //     //     $query->where('order_status', 'Delivered');
    //     // })->orderBy('id', 'desc')->get();
        
    //     $todaydata = Order::where('datetime', 'like', $dateTime . '%')->where('order_status', 'Delivered')
    //         ->orderBy('id', 'desc')
    //         ->get();
            
    //     $totaldata = Order::where('order_status', 'Delivered')
    //         ->orderBy('id', 'desc')
    //         ->get();
            
    //     $data = Order::where('order_status', 'Delivered')
    //         ->orderBy('id', 'desc')
    //         ->get();
        
    //     // Calculate total codAmount using collection sum
    //     $todayAmount = $todaydata->sum(function ($order) {
    //         return $order->codAmount ?? 0; // Handle null codAmount
    //     });
        
    //     // dd($todayAmount);
        
    //     $totalCodAmount = $totaldata->sum(function ($order) {
    //         return $order->codAmount ?? 0; // Handle null codAmount
    //     });
        
    //     $directOrder = WebOrder::where('order_status', 'Booked')->where('datetime', 'like', $dateTime . '%')->count();
        
    //     return view('admin.dashboard', compact(
    //         'branch',
    //         'sellerbranch',
    //         'bookingbranch',
    //         'cat',
    //         'pin',
    //         'eq',
    //         'dBoy',
    //         'data',
    //         'todayAmount',
    //         'totalCodAmount',
    //         'toDayOrder',
    //         'toDayRevenueOrder',
    //         'toDayPendingOrder',
    //         'toDayOrderPicUp',
    //         'toDayCompleteOrder',
    //         'toDayCancelledOrder',
    //         'todayWallet',
    //         'directOrder',
    //         'totalOrders',
    //         'totalPendingOrder',
    //         'totalOrderPicUp',
    //         'totalCompleteOrder',
    //         'totalCancelledOrder',
    //         'todayOtherBranchOrders',
    //         'totalOtherBranchOrders',
    //         'totalPendingSuperExpress'
    //     ));
    // }


    public function adminDashboard()
    {
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = Order::where('datetime', 'like', $dateTime . '%');
    
        // Total orders and their statuses
        $totalOrderQuery = Order::query();
        $totalOrders = $totalOrderQuery->count();
        $totalPendingOrder = (clone $totalOrderQuery)->where(function ($query) {
            $query->where('order_status', 'Booked')
                  ->orWhere('order_status', 'Item Not Picked Up');
        })->count();
        
         $totalPendingSuperExpress = (clone $totalOrderQuery)->where(function ($query) {
            $query->where('service_type', 'SuperExpress')
                  ->whereNotIn('order_status', ['Delivered','Cancelled']);
        })->count();
        
        $totalOrderPicUp = (clone $totalOrderQuery)->where('order_status', 'Item Picked Up')->count();
        $totalCompleteOrder = (clone $totalOrderQuery)->where('order_status', 'Delivered')->count();
        $totalCancelledOrder = (clone $totalOrderQuery)->where('order_status', 'Cancelled')->count();
    
        // Today's orders and revenue
        $sellerrevineue = Wallet::where('datetime', 'like', $dateTime . '%')->where('msg','Order Cancelled');
        $sellerBookingsum = $sellerrevineue->sum('c_amount');
        // $ordersRevenueQuery = Order::where('datetime', 'like', $dateTime . '%');
        // $toDayRevenue = $ordersRevenueQuery->sum('price');
        // $toDayRevenueOrder = $toDayRevenue - $sellerBookingsum;
    
        $ordersRevenueQuery = Order::whereNot('order_status','Cancelled')->where('datetime', 'like', $dateTime . '%');
        $toDayRevenue = $ordersRevenueQuery->sum('price');
        $toDayRevenueOrder = $toDayRevenue;
        // $toDayRevenueOrder = $toDayRevenue - $sellerBookingsum;
        
        // Today's order statuses
        $toDayOrder = (clone $ordersQuery)->count();
    
        $toDayPendingOrder = (clone $ordersQuery)->where(function ($query) {
            $query->where('order_status', 'Booked')
                  ->orWhere('order_status', 'Item Not Picked Up');
        })->count();
        
        $todayOtherBranchOrders = (clone $ordersQuery)->where(function ($query) {
            $query->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch','Not Delivered']);
        })->count();
        
        $totalOtherBranchOrders = (clone $totalOrderQuery)->where(function ($query) {
            $query->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch','Not Delivered']);
        })->count();
        
        $toDayOrderPicUp = (clone $ordersQuery)->where('order_status', 'Item Picked Up')->count();
        $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
        $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();
    
        // Other counts
        $branch = Branch::whereIn('type', ['Delivery'])->count();
        $sellerbranch = Branch::where('type', 'Seller')->count();
        $bookingbranch = Branch::where('type', 'Booking')->count();
        $cat = Category::count();
        $pin = PinCode::count();
        $eq = Enquiry::count();
        $dBoy = DlyBoy::count();
    
        // Today's wallet and COD orders
        $todayWallet = Wallet::where('datetime', 'like', $dateTime . '%')->where('msg', 'credit')->get();
        // $codQuery = CodAmount::where('datetime', 'like', $dateTime . '%');
        
        // $data = $codQuery->whereHas('order', function ($query) {
        //     $query->where('order_status', 'Delivered');
        // })->orderBy('id', 'desc')->get();
        
        $todaydata = Order::where('datetime', 'like', $dateTime . '%')->where('order_status', 'Delivered')
            ->orderBy('id', 'desc')
            ->get();
            
        $totaldata = Order::where('order_status', 'Delivered')
            ->orderBy('id', 'desc')
            ->get();
            
        $data = Order::where('order_status', 'Delivered')
            ->orderBy('id', 'desc')
            ->get();
        
        // Calculate total codAmount using collection sum
        $todayAmount = $todaydata->sum(function ($order) {
            return $order->codAmount ?? 0; // Handle null codAmount
        });
        
        // dd($todayAmount);
        
        $totalCodAmount = $totaldata->sum(function ($order) {
            return $order->codAmount ?? 0; // Handle null codAmount
        });
        
        $directOrder = WebOrder::where('order_status', 'Booked')->where('datetime', 'like', $dateTime . '%')->count();
        
        return view('admin.dashboard', compact(
            'branch',
            'sellerbranch',
            'bookingbranch',
            'cat',
            'pin',
            'eq',
            'dBoy',
            'data',
            'todayAmount',
            'totalCodAmount',
            'toDayOrder',
            'toDayRevenueOrder',
            'toDayPendingOrder',
            'toDayOrderPicUp',
            'toDayCompleteOrder',
            'toDayCancelledOrder',
            'todayWallet',
            'directOrder',
            'totalOrders',
            'totalPendingOrder',
            'totalOrderPicUp',
            'totalCompleteOrder',
            'totalCancelledOrder',
            'todayOtherBranchOrders',
            'totalOtherBranchOrders',
            'totalPendingSuperExpress'
        ));
    }


     public function toDayRevenueOrder()
    {
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        $sellerRevenue = Wallet::where('datetime', 'like', $dateTime . '%')
                              ->where('msg', 'Order Cancelled')
                              ->sum('c_amount');
        $ordersRevenueQuery = Order::where('order_status','!=', 'Cancelled')->where('datetime', 'like', $dateTime . '%');
        $toDayRevenue = $ordersRevenueQuery->sum('price');
        $toDayRevenueOrder = $toDayRevenue; // Net revenue after subtracting cancelled amounts
        $orderCount = $ordersRevenueQuery->count(); // Count of orders
        $averageRevenue = $orderCount > 0 ? $toDayRevenueOrder / $orderCount : 0; 
        
        
       
        
        
        $todayrevenue = $ordersRevenueQuery->get();
        return view('admin.TodayRevenue', compact('todayrevenue','toDayRevenue', 'toDayRevenueOrder', 'orderCount', 'averageRevenue'));
    }

  
    // 14 june latest code 
    public function revenuedateHistory(Request $request)
    {
        $dateRange = $request->date;
        list($startDate, $endDate) = explode(' - ', $dateRange);
        
        // Convert from MM/DD/YYYY to DD-MM-YYYY format
        $startDateTime = \DateTime::createFromFormat('m/d/Y', trim($startDate));
        $endDateTime = \DateTime::createFromFormat('m/d/Y', trim($endDate));
        
        if (!$startDateTime || !$endDateTime) {
            // Fallback in case format is different
            $startDateTime = new \DateTime(trim($startDate));
            $endDateTime = new \DateTime(trim($endDate));
        }
        
        $formattedStartDate = $startDateTime->format('d-m-Y');
        $formattedEndDate = $endDateTime->format('d-m-Y');
        
        // Get sum of cancelled order amounts from Wallet table for the date range
        $sellerRevenue = Wallet::whereRaw("STR_TO_DATE(SUBSTRING_INDEX(datetime, ' | ', 1), '%d-%m-%Y') >= STR_TO_DATE(?, '%d-%m-%Y') 
                                          AND STR_TO_DATE(SUBSTRING_INDEX(datetime, ' | ', 1), '%d-%m-%Y') <= STR_TO_DATE(?, '%d-%m-%Y')", 
                                          [$formattedStartDate, $formattedEndDate])
                        ->where('msg', 'Order Cancelled')
                        ->sum('c_amount');
        
        // Get non-cancelled orders for the date range
        $ordersRevenueQuery = Order::whereNot('order_status', 'Cancelled')
                                  ->whereRaw("STR_TO_DATE(SUBSTRING_INDEX(datetime, ' | ', 1), '%d-%m-%Y') >= STR_TO_DATE(?, '%d-%m-%Y') 
                                              AND STR_TO_DATE(SUBSTRING_INDEX(datetime, ' | ', 1), '%d-%m-%Y') <= STR_TO_DATE(?, '%d-%m-%Y')", 
                                              [$formattedStartDate, $formattedEndDate])
                                  ->orderBy('id', 'desc');
        
        // Calculate order count (non-cancelled orders only)
        $orderCount = $ordersRevenueQuery->count();
        
        // Calculate total revenue
        $toDayRevenue = $ordersRevenueQuery->sum('price');
        $toDayRevenueOrder = $toDayRevenue - $sellerRevenue;
        $averageRevenue = $orderCount > 0 ? $toDayRevenueOrder / $orderCount : 0;
        $todayrevenue = $ordersRevenueQuery->get();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.inc.todayrevenueHistoryData', 
                              compact('todayrevenue', 'toDayRevenueOrder', 'orderCount', 'averageRevenue','toDayRevenue'))->render(),
            ]);
        }
}
    


    // latest code 16 june 
    public function adminOrderDetails($action)
    {
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')->whereIn('parcel_type', ['Direct', 'delivery', 'Pickup']);
        if ($action == 'toDayOrder') {
            $data = $ordersQuery->orderBy('id', 'desc')->get();
        }
        // total orders 
        elseif ($action == 'totalOrder') {
            $data = Order::orderBy('id','desc')->get();
        }
        // Total Pending Orders 
        elseif ($action == 'TotalPendingOrder') {
            $data = Order::orderBy('id','desc')->where('order_status','Booked')->orWhere('order_status','Item Not Picked Up')->get();
        }
        
         // Total Pending Orders 
        elseif ($action == 'totalOtherBranchOrders') {
            $data = Order::orderBy('id','desc')->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch','Not Delivered'])->get();
        }
        
          elseif ($action == 'totalPendingSuperExpress') {
            $data = Order::where('service_type', 'SuperExpress')->orderBy('id','desc')->whereNotIn('order_status' ,['Delivered', 'Cancelled'])->get();
        }
        // Total Pickedup Orders 
        elseif ($action == 'TotalPickedupOrder') {
            $data = Order::orderBy('id','desc')->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up','Delivered to branch','Delivered to near by branch','Out for Delivery to Origin','Not Delivered'])->get();
        }
        // Total Complete Orders 
        elseif ($action == 'TotalCompleteOrder') {
            $data = Order::where('order_status','Delivered')->orderBy('id','desc')->get();
        }
        // Total Complete Orders 
        elseif ($action == 'TotalCancelledOrder') {
            $data = Order::orderBy('id','desc')->where('order_status','Cancelled')->get();
        }
        elseif ($action == 'toDayPendingOrder') {
            $data = $ordersQuery->where('order_status', 'Booked')->orWhere('order_status','Item Not Picked Up')->get();
        }
        // todayOtherBranchOrders
        elseif ($action == 'todayOtherBranchOrders') {
            $data = $ordersQuery->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch','Not Delivered'])->get();
        } elseif ($action == 'toDayOrderPicUp') {
            // $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up','Delivered to branch','Delivered to near by branch',])->get();
            $data = $ordersQuery->where('order_status', 'Item Picked Up')->get();
        } elseif ($action == 'toDayCompleteOrder') {
            $data = $ordersQuery->where('order_status', 'Delivered')->get();
        } elseif ($action == 'toDayCancelledOrder') {
            $data = $ordersQuery->where('order_status', 'Cancelled')->get();
        } else {
            $data = $ordersQuery->orderBy('id', 'desc')->get();
        }
        return view('admin.adminBranch', compact('data'));
    }


    // todayrevenue
    // public function toDayRevenueOrder()
    // {
    //     $dateTime = now('Asia/Kolkata')->format('d-m-Y');
    //     // $todayrevenue = Order::where('datetime', 'like', $dateTime . '%')->where('order_status', 'Booked')->get();
    //     $todayrevenue = Order::where('datetime', 'like', $dateTime . '%')->get();
    //     return view('admin.TodayRevenue', compact('todayrevenue'));
    // }
    
   
    
    public function superExpress($id = null)
    {
        $singleService = !empty($id) ? Service::where('id', $id)->first() : null;
        $data = Service::where('type', 'se')
            ->orWhere('type', 'stse')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.superExpress', compact('data', 'singleService'));
    }

    public function addSuperExpressServices(Request $request)
    {
        // dd($request->all());
        if (!empty($request->id)) {
            $se = Service::find($request->id);
            $msg = 'Service updated successfully!';
        } else {
            $se = new Service();
            $msg = 'New service added successfully!';
        }

        $se->title = $request->servicesTitle;
        $se->price = $request->servicesPrice;
        $se->type = $request->servicesType;
        $se->save();

        $data = Service::where('type', 'se')->orWhere('type', 'stse')->orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('admin.inc.superExpressService', compact('data'))->render(),
            ]);
        }
        return redirect()->route('admin.superExpress')->with('success', $msg);
    }

    public function expressServices($id = null)
    {
        $singleService = !empty($id) ? Service::where('id', $id)->first() : null;
        $data = Service::where('type', 'ex')
            ->orWhere('type', 'stex')
            ->orderBy('id', 'desc')
            ->get();
        if (!$data) {
            return redirect()->back()->with('error', 'No express services found.');
        }
        return view('admin.expressServices', compact('data', 'singleService'));
    }

    public function addExpressServices(Request $request)
    {
        if (!empty($request->id)) {
            $ex = Service::find($request->id);
            $msg = 'Service updated successfully!';
        } else {
            $ex = new Service();
            $msg = 'New service added successfully!';
        }

        $ex->title = $request->servicesTitle;
        $ex->price = $request->servicesPrice;
        $ex->type = $request->type;
        $ex->save();

        $data = Service::where('type', 'ex')->orWhere('type', 'stex')->orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('admin.inc.expressServices', compact('data'))->render(),
            ]);
        }
        return redirect()->route('admin.expressServices')->with('success', $msg);
    }

    public function standardServices($id = null)
    {
        $singleService = !empty($id) ? Service::where('id', $id)->first() : null;
        $data = Service::where('type', 'ss')
            ->orWhere('type', 'stss')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.standardServices', compact('data', 'singleService'));
    }

    public function addStandardServices(Request $request)
    {
        if (!empty($request->id)) {
            $ss = Service::find($request->id);
            $msg = 'Service updated successfully!';
        } else {
            $ss = new Service();
            $msg = 'New service added successfully!';
        }

        $ss->title = $request->servicesTitle;
        $ss->price = $request->servicesPrice;
        $ss->type = $request->type;
        $ss->save();

        $data = Service::where('type', 'ss')->orWhere('type', 'stss')->orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('admin.inc.standardServices', compact('data'))->render(),
            ]);
        }
        return redirect()->route('admin.standardServices')->with('success', $msg);
    }

    public function updateExSs(Request $request)
    {
        $service = Service::find($request->id);
        $service->status = $request->status;
        $service->save();
        $message = $service->status == 'active' ? 'Service activated successfully!' : 'Service deactivated successfully!';
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function deleteServices($id)
    {
        if (!empty($id)) {
            $ex = Service::find($id);
            $ex->delete();
            $msg = 'Service delete successfully!';
        } else {
            $ex = Service::find($id);
            $ex->delete();
            $msg = 'Service delete successfully!';
        }
        return back()->with('success', $msg);
    }
    
    public function deleteOrder(Request $request)
    {
        $id = $request->input('id');
        $message = $request->input('delete_message');

        if (!empty($id)) {
            $order = Order::find($id);
            if ($order) {
                $order->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Order deleted successfully!' . ($message ? ' Reason: ' . $message : '')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid order ID.'
            ]);
        }
    }

    public function pinCodes($id = null)
    {
        $singleData = null;
        if (!empty($id)) {
            $singleData = PinCode::find($id);
        }

        $data = PinCode::orderBy('id', 'desc')->get();
        return view('admin.pinCodes', compact('data', 'singleData'));
    }


    public function addPinCode(Request $request)
    {
        // $msg = '';
        // if (!empty($request->id)) {
        //     // dd($request->all());
        //     $pin = PinCode::find($request->id);
        //     $pin->status = $request->status;
        //     $pin->save();
        //     $msg = $pin->status == 'active' ? 'Pincode activated successfully!' : 'Pincode deactivated successfully!';
        // } else {
        //     $pin = new PinCode();
        //     $pin->pincodes = $request->pin;
        //     $pin->save();
        //     $msg = 'Pincode add successfully!';
        // }
        // $data = PinCode::orderBy('id', 'desc')->get();
        // if ($request->ajax()) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => $msg,
        //         'html' => view('admin.inc.pinCodes', compact('data'))->render()
        //     ]);
        // }

        $msg = '';
        if (!empty($request->id)) {
            // Existing pincode update
            $pin = PinCode::find($request->id);
            if ($pin) {
                $pin->status = $request->status;
                $pin->save();
                $msg = $pin->status == 'active' ? 'Pincode activated successfully!' : 'Pincode deactivated successfully!';
            } else {
                $msg = 'Pincode not found!';
            }
        } else {
            // Check if pincode already exists
            $existingPin = PinCode::where('pincodes', $request->pin)->first();
            if (!$existingPin) {
                $pin = new PinCode();
                $pin->pincodes = $request->pin;
                $pin->save();
                $msg = 'Pincode added successfully!';
            } else {
                $msg = 'Pincode already exists!';
            }
        }

        $data = PinCode::orderBy('id', 'desc')->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('admin.inc.pinCodes', compact('data'))->render()
            ]);
        }
        return redirect()->back()->with('success', $msg);
    }

    public function deletePinCode($id)
    {
        $pin = PinCode::find($id);
        $pin->delete();
        $msg = 'PinCode delete successfully!';
        return back()->with('success', $msg);
    }
    
    // Delete With Service 
    public function deleteServiceType($id)
    {
        $pin = Servicetype::find($id);
        $pin->delete();
        $msg = 'Service Type delete successfully!';
        return back()->with('success', $msg);
    }


    public function adminCategory()
    {
        $data = Category::orderBy('id', 'desc')->get();
        return view('admin.category', compact('data'));
    }

    public function addCategory(Request $request)
    {
        $msg = '';
        if (!empty($request->id)) {
            $cat = Category::find($request->id);
            $cat->status = $request->status;
            $cat->save();
            $msg = $cat->status == 'active' ? 'Category activated successfully!' : 'Category deactivated successfully!';
        } else {
            $cat = new Category();
            $cat->cat_name = $request->cat;
            $cat->save();
            $msg = 'Category add successfully!';
        }

        $data = Category::all();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('admin.inc.category', compact('data'))->render()
            ]);
        }
        return redirect()->back()->with('success', $msg);
    }

    public function deleteCategory($id)
    {
        $pin = Category::find($id);
        $pin->delete();
        $msg = 'Category delete successfully!';
        return back()->with('success', $msg);
    }
    


    public function adminSellerBranch($id = null)
    {
        $singleData = !empty($id) ? Branch::where('id', $id)->first() : null;
        $cat = Category::where('status', 'active')->get();
        $pinCode = PinCode::where('status', 'active')->get();
        return view('admin.sellerBranch', compact('cat', 'singleData', 'pinCode'));
    }

    public function addBranch(Request $request)
    {
        // dd($request->all());
        if (!empty($request->id)) {
            $brn = Branch::find($request->id);
            $message = 'Branch update successfully!';
        } else {
            $brn = new Branch();
            $message = 'New branch add successfully!';
        }

        $brn->fullname = $request->fullName;
        $brn->email = $request->email;
        $brn->fulladdress = $request->fullAddress;
        $brn->itemcount = $request->itemsCount;
        $brn->phoneno = $request->phone;
        $brn->category = $request->category;
        $brn->gst_panno = $request->panNo;

        // Handle Pan Image
        if ($request->hasFile('panImage')) {
            $brn->gst_panno_img = 'admin/upload/branch/' . $request->file('panImage')->move(public_path('admin/upload/branch'), 'BID_' . time() . '.' . $request->file('panImage')->extension())->getFilename();
        } else {
            $brn->gst_panno_img = $brn->gst_panno_img;
        }

        $brn->pincode = is_array($request->pinCode) ? implode(',', $request->pinCode) : $request->pinCode;
        $brn->type = $request->branchType;

        // Handle Seller Logo
        if ($request->hasFile('sellerLogo')) {
            $brn->type_logo = 'admin/upload/branch/' . $request->file('sellerLogo')->move(public_path('admin/upload/branch'), 'BL_' . time() . '.' . $request->file('sellerLogo')->extension())->getFilename();
        } else {
            $brn->type_logo = $brn->type_logo;
        }

        // $brn->password = $request->fullName . '@' . $request->pinCode;
        $brn->password = $request->branchType . '@' . $request->phone;
        $brn->branch_cm = empty($request->branch_cm) ? null : $request->branch_cm;
        // dd($brn->toArray());
        $brn->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }
    }

    // All Branch Data Show Here 
    // public function allBranch()
    // {
    //     // $data = Branch::whereIn('type', ['Booking', 'Delivery'])->orderBy('id', 'desc')->get();
    //     $data = Branch::whereIn('type', ['Delivery'])->orderBy('id', 'desc')->get();
    //     return view('admin.allBranch', compact('data'));
    // }
    
    // Controller (e.g., BranchController.php)
    public function allBranch()
    {
        $data = Branch::whereIn('type', ['Delivery'])
            ->withSum('branch_total_cod as total_amount', 'amount') // Eloquent withSum
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.allBranch', compact('data'));
    }
    
    // All Booking Branch Data Show Here 
    public function allBookingBranch()
    {
        $data = Branch::whereIn('type', ['Booking'])->orderBy('id', 'desc')->get();
        return view('admin.allBookingBranch', compact('data'));
    }


    // branch show here 
    public function adminBranch($id = null)
    {
        $singleData = !empty($id) ? Branch::where('id', $id)->first() : null;
        $cat = Category::where('status', 'active')->get();
        $pinCode = PinCode::where('status', 'active')->get();
        return view('admin.branch', compact('cat', 'singleData', 'pinCode'));
    }


    
    
    
    // branch show here 
    public function adminBookingBranch($id = null)
    {
        $singleData = !empty($id) ? Branch::where('id', $id)->first() : null;
        $cat = Category::where('status', 'active')->get();
        $pinCode = PinCode::where('status', 'active')->get();
        return view('admin.adminBookingBranch', compact('cat', 'singleData', 'pinCode'));
    }

    

    public function allSellerBranch()
    {
        $data = Branch::where('type', 'Seller')->orderBy('id', 'desc')->get();
        return view('admin.allSellerBranch', compact('data'));
    }
    
     public function allBookingBranchData()
    {
        $data = Branch::where('type', 'Booking')->orderBy('id', 'desc')->get();
        return view('admin.allBookingBranchData', compact('data'));
    }

    public function branchStatus(Request $request)
    {
        $branch = Branch::find($request->id);
        $branch->status = $request->status;
        $branch->save();
        $message = $branch->status == 'active' ? 'Service activated successfully!' : 'Service deactivated successfully!';
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }
    }

    public function deleteBranch($id)
    {
        $brn = Branch::findOrFail($id);

        // Delete the file from the folder
        // if ($brn->gst_panno_img) {
        //     $fileName = basename($brn->gst_panno_img);
        //     $filePath = public_path('admin/upload/branch/' . $fileName);
        //     if (File::exists($filePath)) {
        //         File::delete($filePath);
        //     }
        // }
        // if ($brn->type_logo) {
        //     $fileName = basename($brn->type_logo);
        //     $filePath = public_path('admin/upload/branch/' . $fileName);
        //     if (File::exists($filePath)) {
        //         File::delete($filePath);
        //     }
        // }
        foreach (['gst_panno_img', 'type_logo'] as $field) {
            if ($brn->$field) {
                $filePath = public_path('admin/upload/branch/' . basename($brn->$field));
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }
        $brn->delete();
        return redirect()->back()->with('success', 'Branch deleted successfully.');
    }

    public function manageServicesType($id)
    {
        $data = Service::all();
        $serviceData = Servicetype::where('userId', $id)->get();
        return view('admin.manageServicesType', compact('data', 'serviceData'));
    }

    public function servicesType(Request $request)
    {
        $data = Service::where('type', $request->services)
            ->where('status', 'active')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $data,
            'html' => view('admin.inc.servicesType', compact('data'))->render()
        ]);
    }

    public function addServicesType(Request $request)
    {
        $st = new Servicetype();
        $st->userId = $request->userId;
        $st->services = $request->services;
        $st->servicesType = $request->servicesType;
        $st->servicesId = implode(',', $request->servicesId);
        $st->save();
        return response()->json([
            'success' => true,
            'message' => 'Service type add successfully!',
        ]);
    }

    public function addDeliveryBoy($id = null)
    {
        $data = null;
        if (!empty($id)) {
            $data = DlyBoy::find($id);
        }
        $id = Session::get('uid');
        $delivery = PinCode::where('status', 'active')->get();

        $branch = Branch::where('type', 'Delivery')->get();
        return view('admin.addDeliveryBoy', compact('delivery', 'data', 'branch'));
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
        if ($request->id) {
            $dboy = DlyBoy::find($request->id);
        } else {
            $dboy = new DlyBoy();
        }

        $dboy->name = $request->fullName;
        $dboy->email = $request->email;
        $dboy->phone = $request->phone;
        $dboy->address = $request->fullAddress;
        // $dboy->pincode = $request->pinCode;
        $dboy->pincode = implode(',', $request->pinCode);
        $dboy->password = $request->password;
        $dboy->orderRate = $request->orderRate;
        $dboy->userid = $request->userid;
        $dboy->save();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Delivery boy add successfully!',
                'data' => [
                    'password' => $dboy->password, // Only include if plain text and necessary
                ]
            ]);
        }
    }

    // all DeliveryBoy 
    public function allDeliveryBoy()
    {
        $data = DlyBoy::orderBy('id', 'desc')->get();
        return view('admin.allDeliveryBoy', compact('data'));
    }
    
    // perticular deliveryboy earning 31 may 
    // public function DeliveryboyEarning($id)
    // {
    //     $delivery = DlyBoy::find($id);
    //     $deliveryBoyName = $delivery->name ?? '';
    //     // Get per-order rate
    //     $orderRate = $delivery->orderRate;

    //     // Current date in Asia/Kolkata timezone
    //     $currentDate = Carbon::now('Asia/Kolkata');
    //     $dateTime = $currentDate->format('d-m-Y');

    //     // Initialize data arrays
    //     $earningsData = [
    //         'today' => ['orders' => [], 'total' => 0],
    //         'this_week' => ['orders' => [], 'total' => 0],
    //         'this_month' => ['orders' => [], 'total' => 0],
    //         'this_year' => ['orders' => [], 'total' => 0],
    //     ];

    //     // Today: Orders delivered today
    //     $todayOrders = Order::where('datetime', 'like', $dateTime . '%')
    //         ->where('order_status', 'Delivered')
    //         ->where('assign_to', $id)
    //         ->select('id', 'datetime', 'order_status')
    //         ->get();
    //     $earningsData['today']['orders'] = $todayOrders;
    //     $earningsData['today']['total'] = $todayOrders->count() * $orderRate;

    //     // This Week: Orders delivered this week (Monday to Sunday)
    //     $weekStart = $currentDate->startOfWeek()->format('d-m-Y');
    //     $weekEnd = $currentDate->endOfWeek()->format('d-m-Y');
    //     $thisWeekOrders = Order::whereBetween('datetime', [$weekStart . ' 00:00:00', $weekEnd . ' 23:59:59'])
    //         ->where('order_status', 'Delivered')
    //         ->where('assign_to', $id)
    //         ->select('id', 'datetime', 'order_status')
    //         ->get();
    //     $earningsData['this_week']['orders'] = $thisWeekOrders;
    //     $earningsData['this_week']['total'] = $thisWeekOrders->count() * $orderRate;

    //     // This Month: Orders delivered this month
    //     $monthStart = $currentDate->startOfMonth()->format('d-m-Y');
    //     $monthEnd = $currentDate->endOfMonth()->format('d-m-Y');
    //     $thisMonthOrders = Order::whereBetween('datetime', [$monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])
    //         ->where('order_status', 'Delivered')
    //         ->where('assign_to', $id)
    //         ->select('id', 'datetime', 'order_status')
    //         ->get();
    //     $earningsData['this_month']['orders'] = $thisMonthOrders;
    //     $earningsData['this_month']['total'] = $thisMonthOrders->count() * $orderRate;

    //     // This Year: Orders delivered this year
    //     $yearStart = $currentDate->startOfYear()->format('d-m-Y');
    //     $yearEnd = $currentDate->endOfYear()->format('d-m-Y');
    //     $thisYearOrders = Order::whereBetween('datetime', [$yearStart . ' 00:00:00', $yearEnd . ' 23:59:59'])
    //         ->where('order_status', 'Delivered')
    //         ->where('assign_to', $id)
    //         ->select('id', 'datetime', 'order_status')
    //         ->get();
    //     $earningsData['this_year']['orders'] = $thisYearOrders;
    //     $earningsData['this_year']['total'] = $thisYearOrders->count() * $orderRate;

    //     // Return view with earnings data
    //     return view('admin.myearning', compact('earningsData', 'orderRate','deliveryBoyName'));
    // }
    
    public function DeliveryboyEarning($id)
{
    $delivery = DlyBoy::find($id);
    $deliveryBoyName = $delivery->name ?? '';
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
        'last_month' => ['orders' => [], 'total' => 0],
    ];

    // Today: Orders delivered today
    // Adjust the LIKE clause to account for the pipe separator
    $todayOrders = Order::where('datetime', 'like', $dateTime . ' | %')
        ->where('order_status', 'Delivered')
        ->where('assign_to', $id)
        ->select('id', 'datetime', 'order_status')
        ->get();
    $earningsData['today']['orders'] = $todayOrders;
    $earningsData['today']['total'] = $todayOrders->count() * $orderRate;

    // This Week: Orders delivered this week (Monday to Sunday)
    $weekStart = Carbon::now('Asia/Kolkata')->startOfWeek();
    $weekEnd = Carbon::now('Asia/Kolkata')->endOfWeek();
    $weekStartFormatted = $weekStart->format('Y-m-d H:i:s');
    $weekEndFormatted = $weekEnd->format('Y-m-d H:i:s');
    $thisWeekOrders = Order::whereBetween(DB::raw('STR_TO_DATE(datetime, "%d-%m-%Y | %h:%i:%s %p")'), [$weekStartFormatted, $weekEndFormatted])
        ->where('order_status', 'Delivered')
        ->where('assign_to', $id)
        ->select('id', 'datetime', 'order_status')
        ->get();
    $earningsData['this_week']['orders'] = $thisWeekOrders;
    $earningsData['this_week']['total'] = $thisWeekOrders->count() * $orderRate;

    // This Month: Orders delivered this month (June 2025)
    $monthStart = Carbon::now('Asia/Kolkata')->startOfMonth();
    $monthEnd = Carbon::now('Asia/Kolkata')->endOfMonth();
    $monthStartFormatted = $monthStart->format('Y-m-d H:i:s');
    $monthEndFormatted = $monthEnd->format('Y-m-d H:i:s');
    $thisMonthOrders = Order::whereBetween(DB::raw('STR_TO_DATE(datetime, "%d-%m-%Y | %h:%i:%s %p")'), [$monthStartFormatted, $monthEndFormatted])
        ->where('order_status', 'Delivered')
        ->where('assign_to', $id)
        ->select('id', 'datetime', 'order_status')
        ->get();
    $earningsData['this_month']['orders'] = $thisMonthOrders;
    $earningsData['this_month']['total'] = $thisMonthOrders->count() * $orderRate;

    // Last Month: Orders delivered last month (May 2025)
    $lastMonthStart = Carbon::now('Asia/Kolkata')->subMonthNoOverflow()->startOfMonth();
    $lastMonthEnd = Carbon::now('Asia/Kolkata')->subMonthNoOverflow()->endOfMonth();
    $lastMonthStartFormatted = $lastMonthStart->format('Y-m-d H:i:s');
    $lastMonthEndFormatted = $lastMonthEnd->format('Y-m-d H:i:s');

    // Log the date range for debugging
    \Log::info("Last Month Earnings Query - Delivery Boy ID: {$id}, Date Range: {$lastMonthStartFormatted} to {$lastMonthEndFormatted}");

    $lastMonthOrders = Order::whereBetween(DB::raw('STR_TO_DATE(datetime, "%d-%m-%Y | %h:%i:%s %p")'), [$lastMonthStartFormatted, $lastMonthEndFormatted])
        ->where('order_status', 'Delivered')
        ->where('assign_to', $id)
        ->select('id', 'datetime', 'order_status')
        ->get();

    // Log the fetched orders for debugging
    \Log::info("Last Month Orders Count: " . $lastMonthOrders->count());
    \Log::info("Last Month Orders: " . $lastMonthOrders->toJson());

    $earningsData['last_month']['orders'] = $lastMonthOrders;
    $earningsData['last_month']['total'] = $lastMonthOrders->count() * $orderRate;

    // Return view with earnings data
    return view('admin.myearning', compact('earningsData', 'orderRate', 'deliveryBoyName'));
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

    public function allEnquiry()
    {
        $data = Enquiry::orderBy('id', 'desc')->get();
        return view('admin.allEnquiry', compact('data'));
    }
    
    // allDeliveryBoyEnq
    public function allDeliveryBoyEnq()
    {
        $data = DB::table("tbl_deliveryboy_enq")->orderBy('id', 'desc')->get();
        return view('admin.allDeliveryBoyEnq', compact('data'));
    }
    // allFranchiseEnq
    public function allFranchiseEnq()
    {
        $data = DB::table("tbl_field_franchise")->orderBy('id', 'desc')->get();
        return view('admin.allFranchiseEnq', compact('data'));
    }
    // deletedeliveryboysform
    public function deletedeliveryboysform(Request $request)
    {
        DB::table("tbl_deliveryboy_enq")->where('id', $request->id)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }
    }
    // deletefranchiseform
    public function deletefranchiseform(Request $request)
    {
        DB::table("tbl_field_franchise")->where('id', $request->id)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }
    }
    
    
    
    

    public function deleteEnquiry(Request $request)
    {
        $eq = Enquiry::findOrFail($request->id);
        $eq->delete();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function editEnquiry(Request $request)
    {
        $category = Category::where('status', 'active')->get();
        $enquiry = Enquiry::find($request->id);
        return view('admin.editEnquiry', compact('enquiry', 'category'));
    }

    public function updateEnquiry(Request $request)
    {
        if (empty($request->category)) {
            $status = false;
            $msg = 'Please select category!';
        } else {
            $eq = Enquiry::find($request->id);
            $eq->fullname = $request->fullName;
            $eq->email = $request->email;
            $eq->phoneno = $request->phone;
            $eq->itemno = $request->itemsCount;
            $eq->category = $request->category;
            $eq->pinCode = $request->pinCode;
            $eq->gst_panno = $request->panNo;
            $eq->fulladdress = $request->fullAddress;
            if ($request->hasFile('panImage')) {
                $eq->gst_panno_img = 'admin/upload/branch/' . $request->file('panImage')->move(public_path('admin/upload/branch'), 'EID_' . time() . '.' . $request->file('panImage')->extension())->getFilename();
            } else {
                $eq->gst_panno_img = $eq->gst_panno_img;
            }
            $eq->save();

            $status = true;
            $msg = 'Enquiry update successfully!';
        }
        if ($request->ajax()) {
            return response()->json([
                'success' => $status,
                'message' => $msg,
            ]);
        }
    }

    public function enquiryAssignBranch(Request $request)
    {
        $eq = Enquiry::find($request->enquiry_id);
        $eq->status = 'active';

        $branch = new Branch();
        $branch->fullname = $eq->fullname;
        $branch->email = $eq->email;
        $branch->fulladdress = $eq->fullAddress;
        $branch->itemcount = $eq->itemno;
        $branch->phoneno = $eq->phoneno;
        $branch->category = $eq->category;
        $branch->fulladdress = $eq->fulladdress;
        $branch->gst_panno = $eq->gst_panno;
        $branch->gst_panno_img = $eq->gst_panno_img;

        $branch->pincode = $eq->pinCode;
        $branch->type = $request->branch;
        $branch->type_logo = 'web/images/logo.png';

        $branch->password = $eq->fullname . '@' . $eq->pinCode;
        $branch->branch_cm = Null;

        $branch->save();
        $eq->save();

        $data = Enquiry::orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Branch assign successfully!',
                'html' => view('admin.inc.allEnquiry', compact('data'))->render()
            ]);
        }
    }

    public function webDirectOrders()
    {
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        // $data = WebOrder::where('datetime', 'like', $dateTime . '%')->orderBy('id', 'desc')->get();
        $data = Order::where('parcel_type', 'Direct')->orderBy('id', 'desc')->get();
        return view('admin.webOrders', compact('data'));
    }

    public function adminLabel($id)
    {
        $data = Order::where('order_id', $id)->first();
        if ($data->payment_mode == 'COD') {
            return view('admin.label', compact('data'));
        } else {
            return view('admin.label1', compact('data'));
        }
    }

    public function adminInvoice($id)
    {
        // $data = WebOrder::where('order_id', $id)->first();
        $data = Order::where('order_id', $id)->first();
        
        $branch = Branch::where('id', $data->seller_primary_id)->first();
        $data->gstno = $branch->gst_panno ?? null;
        return view('admin.invoice', compact('data'));
    }
    
    // public function MonthlyadminInvoice($id)
    // {
    // // Get parameters from GET request
    // $branch_type = request()->input('branch_type', 'Seller'); // Default to Seller
    // $branch_id = $id; // Use the id from the route
    // $year = request()->input('year', date('Y'));
    // $month = request()->input('month', date('F')); // e.g., January, February

    // // Fetch branches based on branch type
    // $branches = Branch::where('type', $branch_type)->get(['id', 'fullname']);
    
    // // If branch_id from route is not in the filtered branches, use the first branch ID
    // if (!$branches->pluck('id')->contains($branch_id) && $branches->isNotEmpty()) {
    //     $branch_id = $branches->first()->id;
    // }

    // // Fetch branch data for the selected branch
    // $branch = Branch::where('id', $branch_id)->first();

    // // Initialize data object
    // $data = new \stdClass();
    // $data->gstno = $branch->gst_panno ?? null;
    // $data->branch_fullname = $branch->fullname ?? null;
    // $data->branch_fulladdress = $branch->fulladdress ?? null;
    // $data->branch_phoneno = $branch->phoneno ?? null;
    // $data->branch_pincode = $branch->pincode ?? null;
    // $data->branches = $branches; // Add branches to data for dropdown
    // $data->selected_branch_type = $branch_type;
    // $data->selected_branch_id = $branch_id;

    // // Convert month name to month number (e.g., January -> 01)
    // $monthNumber = date('m', strtotime($year . '-' . $month . '-01'));

    // // Generate invoice number (e.g., DP202506-123)
    // $data->invoice_number = 'DP' . $year . $monthNumber . '-' . $branch_id;

    // // Query to sum the price of all delivered orders for the selected year, month, and branch
    // $totalPrice = Order::where('seller_primary_id', $branch_id)
    //                   ->where('order_status', 'Delivered')
    //                   ->whereRaw("SUBSTRING_INDEX(datetime, ' | ', 1) LIKE ?", ["%-$monthNumber-$year"])
    //                   ->sum('price');

    // // Add total price and selected year/month to $data
    // $data->total_price = $totalPrice;
    // $data->selected_year = $year;
    // $data->selected_month = $month;

    // return view('admin.monthlyinvoice', compact('data'));
    //}
    
   public function MonthlyAdminInvoice(Request $request)
    {
        // Get branch type and ID from request (default to Seller and first branch)
        $type = $request->input('type', 'Seller');
        $branchId = $request->input('branch_id');

        // Fetch branches of the selected type
        $branches = Branch::where('type', $type)->orderBy('fullname', 'asc')->get();

        // If no branch ID provided, use the first branch of the selected type
        if (!$branchId && $branches->isNotEmpty()) {
            $branchId = $branches->first()->id;
        }

        // Fetch branch data
        $branch = Branch::where('id', $branchId)->first();

        // Initialize data object
        $data = new \stdClass();
        $data->gstno = $branch->gst_panno ?? null;
        $data->branch_fullname = $branch->fullname ?? null;
        $data->branch_fulladdress = $branch->fulladdress ?? null;
        $data->branch_phoneno = $branch->phoneno ?? null;
        $data->branch_pincode = $branch->pincode ?? null;
        $data->branch_id = $branchId;

        // Get year and month from GET parameters (default to current year and month)
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('F')); // e.g., January, February

        // Convert month name to month number (e.g., January -> 01)
        $monthNumber = date('m', strtotime($year . '-' . $month . '-01'));

        // Generate invoice number (e.g., DP202506-123)
        $data->invoice_number = 'DP' . $year . $monthNumber . '-' . $branchId;

        // Query to sum the price of all delivered orders for the selected year, month, and branch
        $totalPrice = Order::where('seller_primary_id', $branchId)
                          ->where('order_status', 'Delivered')
                          ->whereRaw("SUBSTRING_INDEX(datetime, ' | ', 1) LIKE ?", ["%-$monthNumber-$year"])
                          ->sum('price');

        // Add total price and selected year/month to $data
        $data->total_price = $totalPrice;
        $data->selected_year = $year;
        $data->selected_month = $month;

        return view('admin.monthlyinvoice', compact('data', 'branches'));
    }

    public function getBranches(Request $request)
    {
        $type = $request->query('type', 'Seller');
        $branches = Branch::where('type', $type)->select('id', 'fullname')->orderBy('fullname', 'asc')->get();
        return response()->json($branches);
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
                'html' => view('admin.inc.webOrderDetails', compact('data'))->render(),
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
                'html' => view('admin.inc.webOrderDetails', compact('data'))->render(),
            ]);
        }
    }

    public function adminDlyBoyData(Request $request)
    {
        $data = Branch::find($request->id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function setting()
    {
        $user = Session::get('uid');
        $data = User::find($user);
        return view('admin.setting', compact('data'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::find($request->adminId);

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
        $user = User::find($request->id);
        if (Hash::check($request->oldPassword, $user->password)) {
            if ($request->newPassword != $request->conPassword) {
                $status = false;
                $msg = 'Confirm password not matched!';
            } else {
                $user->password = Hash::make($request->newPassword);
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

    public function branchAllDeliveryBoy($id)
    {
        $data = DlyBoy::where('userid', $id)
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.branch-AllDeliveryBoy', compact('data'));
    }

    public function branchManageBranch($id)
    {
        $branchdata = Branch::where('id', $id)->first();
        
        
        // toDayOrder details
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')
            ->where('seller_id', $id);
        $toDayOrder = $ordersQuery->count();
        $toDayPendingOrder = (clone $ordersQuery)->where('order_status', 'Booked')->count();
        $toDayOrderPicUp = (clone $ordersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
        $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();
        $amount = Wallet::where('userid', $id)->latest('id')->first();
        $totalDlyBoy = DlyBoy::where('userid', $id)->count();

        // totalOrder details
        $totalOrder = Order::where('seller_id', $id)->count();
        $totalPendingOrder = Order::where('seller_id', $id)->where('order_status', 'Booked')->count();
        $totalOrderPicUp = Order::where('seller_id', $id)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        $totalCompleteOrder = Order::where('seller_id', $id)->where('order_status', 'Delivered')->count();
        $totalCanceledOrder = Order::where('seller_id', $id)->where('order_status', 'Cancelled')->count();

        return view('admin.branchManageBranch', compact('branchdata','toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'amount', 'totalDlyBoy', 'totalOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCanceledOrder'));
    }



    // public function orderDetails($id, $action)
    // {
    //     if ($action == 'toDayOrder' || $action == 'toDayPendingOrder' ||  $action == 'todayOtherBranchOrders' || $action == 'toDayOrderPicUp' || $action == 'toDayCompleteOrder' || $action == 'toDayCancelledOrder' ) {
    //         $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
    //         $ordersQuery = Order::where('datetime', 'like', $dateTiem . '%')
    //             ->where('seller_id', $id);
    //         if ($action == 'toDayOrder') {
    //             $data = $ordersQuery->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'toDayPendingOrder') {
    //             $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Booked')->orWhere('order_status','Item Not Picked Up')->get();
    //         } elseif ($action == 'todayOtherBranchOrders') {
    //             $data = $ordersQuery->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch','Not Delivered'])->get();
    //         // } elseif ($action == 'todayOtherBranchOrders') {
    //         //     $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Delivered to branch')->orWhere('order_status','Out for Delivery to Origin')->get();
    //         } elseif ($action == 'toDayOrderPicUp') {
    //             $data = $ordersQuery->orderBy('id','desc')->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up','Delivered to branch','Delivered to near by branch'])->get();
    //         } elseif ($action == 'toDayCompleteOrder') {
    //             $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Delivered')->get();
    //         } elseif ($action == 'toDayCancelledOrder') {
    //             $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Cancelled')->get();
    //         } else {
    //             $data = $ordersQuery->orderBy('id', 'desc')->get();
    //         }
    //     } elseif ($action == 'totalOrder' || $action == 'TotalPendingOrder' || $action == 'totalOtherBranchOrders' || $action == 'totalOrderPicUp' || $action == 'TotalCompleteOrder' || $action == 'TotalCanceledOrder') {
    //         $ordersQuery = Order::where('seller_id', $id);
    //         if ($action == 'totalOrder') {
    //             $data = $ordersQuery->orderBy('id', 'desc')->get();
    //         } elseif ($action == 'TotalPendingOrder') {
    //             $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Booked')->orWhere('order_status','Item Not Picked Up')->get();
    //         } elseif ($action == 'totalOrderPicUp') {
    //             $data = $ordersQuery->orderBy('id','desc')->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up','Delivered to branch','Delivered to near by branch'])->get();
    //         } elseif ($action == 'TotalCompleteOrder') {
    //             $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Delivered')->get();
    //         } elseif ($action == 'TotalCanceledOrder') {
    //             $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Cancelled')->get();
    //         } elseif ($action == 'totalOtherBranchOrders') {
    //             $data = $ordersQuery->orderBy('id','desc')->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch'])->get();
    //         } else {
    //             $data = $ordersQuery->orderBy('id', 'desc')->get();
    //         }
    //     } else {
    //         $data = Order::where('seller_id', $id)->get();
    //     }
    //     return view('admin.orderDetails', compact('data'));
    // }
    
    
     public function orderDetails($id, $action)
    {
        
        
        if ($action == 'toDayOrder' || $action == 'toDayPendingOrder' ||  $action == 'todayOtherBranchOrders' || $action == 'toDayOrderPicUp' || $action == 'toDayCompleteOrder' || $action == 'toDayCancelledOrder' ) {
            $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
            $ordersQuery = Order::where('datetime', 'like', $dateTiem . '%')
                ->where('assign_by', $id);
            if ($action == 'toDayOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'toDayPendingOrder') {
                $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Booked')->orWhere('order_status','Item Not Picked Up')->get();
            } elseif ($action == 'todayOtherBranchOrders') {
                $data = $ordersQuery->whereIn('order_status' ,['Delivered to branch', 'Out for Delivery to Origin','Delivered to near by branch','Not Delivered'])->get();
            // } elseif ($action == 'todayOtherBranchOrders') {
            //     $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Delivered to branch')->orWhere('order_status','Out for Delivery to Origin')->get();
            } elseif ($action == 'toDayOrderPicUp') {
                // $data = $ordersQuery->orderBy('id','desc')->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up','Delivered to branch','Delivered to near by branch','Out for Delivery to Origin'])->get();
                $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Item Picked Up')->get();
            } elseif ($action == 'toDayCompleteOrder') {
                $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Delivered')->get();
            } elseif ($action == 'toDayCancelledOrder') {
                $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Cancelled')->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } elseif ($action == 'totalOrder' || $action == 'TotalPendingOrder' || $action == 'totalOtherBranchOrders' || $action == 'TotalPickedupOrder' || $action == 'TotalCompleteOrder' || $action == 'TotalCancelledOrder' || $action == 'totalPendingSuperExpress') {
            $ordersQuery = Order::where('assign_by', $id);
            if ($action == 'totalOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'TotalPendingOrder') {
                $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Booked')->orWhere('order_status','Item Not Picked Up')->get();
            } elseif ($action == 'totalPendingSuperExpress') {
                $data = $ordersQuery->orderBy('id','desc')->where('service_type','SuperExpress')->whereNotIn('order_status', ['Delivered','Cancelled'])->get();
            } elseif ($action == 'TotalPickedupOrder') {
                $data = $ordersQuery->orderBy('id','desc')->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled','Item Not Picked Up','Delivered to branch','Delivered to near by branch' ,'Out for Delivery to Origin','Not Delivered'])->get();
            } elseif ($action == 'TotalCompleteOrder') {
                $data = $ordersQuery->orderBy('id','desc')->where('order_status', 'Delivered')->get();
            } elseif ($action == 'TotalCancelledOrder') {
                $data = Order::where('order_status', 'Cancelled')->where('assign_by', $id)->orderBy('id','desc')->get();
            } elseif ($action == 'totalOtherBranchOrders') {
                $data = $ordersQuery->orderBy('id','desc')->whereIn('order_status' ,['Delivered to branch', 'Not Delivered', 'Out for Delivery to Origin','Delivered to near by branch'])->get();
            } else {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            }
        } else {
            $data = Order::where('seller_id', $id)->get();
        }
        return view('admin.orderDetails', compact('data'));
    }

    public function deliveryStatusGet(Request $request)
    {
        $order = Order::find($request->id);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'orderId' => $order->order_id
            ]);
        }
    }

    public function adminAssignGet(Request $request)
    {
        $order = Order::find($request->id);
        $data = DlyBoy::where('status', 'active')->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'orderId' => $order->order_id
            ]);
        }
    }

    public function adminStatusGet(Request $request)
    {
        $order = Order::find($request->id);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'orderId' => $order->order_id
            ]);
        }
    }

    public function adminAssignAdd(Request $request)
    {
        $id = Session::get('uid');
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
                        'pyment_method' => 'COD',
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
                'html' => view('admin.inc.orderDetails', compact('data'))->render(),
            ]);
        }
    }

    public function walletDetails($id)
    {
        $data = Wallet::where('userid', $id)->orderBy('id', 'desc')->get();
        $amount = $data->first();
        return view('admin.walletDetails', compact('data', 'amount'));
    }

    public function walletAmount(Request $request)
    {
        $wallet = Wallet::where('userid', $request->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($request->walletType == 'Credit') {
            $wlt = new Wallet();
            $wlt->userid = $request->id;
            $wlt->c_amount = $request->amount;
            $wlt->total = $wallet->total + $request->amount;
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->adminid = Session::get('uid');
            $wlt->msg = $request->remark;
            $wlt->save();
            $msg = 'Amount Credit successfully!';
        } elseif ($request->walletType == 'Debit') {
            $wlt = new Wallet();
            $wlt->userid = $request->id;
            $wlt->d_amount = $request->amount;
            $wlt->total = $wallet->total - $request->amount;
            $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
            $wlt->adminid = Session::get('uid');
            $wlt->msg = $request->remark;
            $wlt->save();
            $msg = 'Amount debit successfully!';
        }

        $data = Wallet::where('userid', $request->id)->orderBy('id', 'desc')->get();
        $amount = $data->first();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'html' => view('admin.inc.wallet', compact('data', 'amount'))->render(),
            ]);
        }
    }

    public function adminCodHistory(Request $request)
    {
        
        // If date range is provided via AJAX
        if ($request->has('date') && $request->date) {
            
            $dateRange = explode(' - ', $request->date);
            
            $startDate = Carbon::createFromFormat('m/d/Y', $dateRange[0])->format('d-m-Y');
            $endDate = Carbon::createFromFormat('m/d/Y', $dateRange[1])->format('d-m-Y');
            
            // Add time to make it a full day range
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';
            
            // Get AdminCodHistory data for the date range
            $data = AdminCodHistory::whereBetween('datetime', [$startDateTime, $endDateTime])
                ->orderBy('id', 'desc')
                ->get();
            
            $totalAmount = $data->sum('amount');
            
            
            // For AJAX response
            $view = view('admin.inc.adminCodHistoryData', compact('data', 'totalAmount'))->render();
            return response()->json([
                'html' => $view
            ]);
        } else {
            // Default: Show today's data
            $dateTime = now('Asia/Kolkata')->format('d-m-Y');

            // Get AdminCodHistory data for today
            $data = AdminCodHistory::where('datetime', 'like', $dateTime . '%')
                ->orderBy('id', 'desc')
                ->get();

            // Calculate total amount from today's transactions
            $totalAmount = $data->sum('amount');

            // Get total accumulated COD amount across all branches
            $totalCod = AdminTotalCod::sum('amount');

            // Get branch-wise totals for display
            $branchTotals = AdminTotalCod::select('branch_id', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('branch_id')
                ->get();

            // Transform to include branch names
            $branchTotals = $branchTotals->map(function ($item) {
                $branch = Branch::find($item->branch_id);
                $item->branch_name = $branch ? $branch->name : 'Unknown Branch';
                return $item;
            });

            return view('admin.allCodHistory', compact('data', 'totalAmount', 'totalCod', 'branchTotals'));
        }
    }
    

    
    // Today Cod History 
    public function TodayCodHistory(Request $request)
    {
        // If date range is provided via AJAX
        if ($request->has('date') && $request->date) {
            $dateRange = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', $dateRange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m/d/Y', $dateRange[1])->format('Y-m-d');

            // Add time to make it a full day range
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';

            // Get AdminCodHistory data for the date range
            $data = AdminCodHistory::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->orderBy('id', 'desc')
                ->get();

            $totalAmount = $data->sum('amount');

            // For AJAX response
            $view = view('admin.inc.TodayadminCodHistoryData', compact('data', 'totalAmount'))->render();
            return response()->json([
                'html' => $view
            ]);
        } else {
            // Default: Show today's data
            $dateTime = now('Asia/Kolkata')->format('d-m-Y');
            $dateTime1 = now('Asia/Kolkata')->format('Y-m-d');

            // Get AdminCodHistory data for today
            $data = AdminCodHistory::where('datetime', 'like', $dateTime . '%')
                ->orderBy('id', 'desc')
                ->get();

            // Calculate total amount from today's transactions
            $totalAmount = $data->sum('amount');

            // Get total accumulated COD amount across all branches
            // $totalCod = AdminTotalCod::sum('amount');
            $totalCod = AdminTotalCod::where('created_at', 'like', $dateTime1 . '%')->sum('amount');

            // Get branch-wise totals for display
            $branchTotals = AdminTotalCod::select('branch_id', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('branch_id')
                ->get();

            // Transform to include branch names
            $branchTotals = $branchTotals->map(function ($item) {
                $branch = Branch::find($item->branch_id);
                $item->branch_name = $branch ? $branch->name : 'Unknown Branch';
                return $item;
            });
            return view('admin.TodayCodHistory', compact('data', 'totalAmount', 'totalCod', 'branchTotals'));
        }
    }
    
    
    // All Cod History 
    public function allCodHistory(Request $request)
    {
        // If date range is provided via AJAX
        if ($request->has('date') && $request->date) {
            $dateRange = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', $dateRange[0])->format('d-m-Y');
            $endDate = Carbon::createFromFormat('m/d/Y', $dateRange[1])->format('d-m-Y');

            // Add time to make it a full day range
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';

            // Get AdminCodHistory data for the date range
            $data = AdminCodHistory::whereBetween('datetime', [$startDateTime, $endDateTime])
                ->orderBy('id', 'desc')
                ->get();

            $totalAmount = $data->sum('amount');

            // For AJAX response
            $view = view('admin.inc.adminCodHistoryData', compact('data', 'totalAmount'))->render();
            return response()->json([
                'html' => $view
            ]);
        } else {
            // Default: Show today's data
            $dateTime = now('Asia/Kolkata')->format('d-m-Y');

            // Get AdminCodHistory data for today
            $data = AdminCodHistory::
                orderBy('id', 'desc')
                ->get();

            // Calculate total amount from today's transactions
            $totalAmount = $data->sum('amount');

            // Get total accumulated COD amount across all branches
            $totalCod = AdminTotalCod::sum('amount');

            // Get branch-wise totals for display
            $branchTotals = AdminTotalCod::select('branch_id', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('branch_id')
                ->get();

            // Transform to include branch names
            $branchTotals = $branchTotals->map(function ($item) {
                $branch = Branch::find($item->branch_id);
                $item->branch_name = $branch ? $branch->name : 'Unknown Branch';
                return $item;
            });
            return view('admin.allCodHistory', compact('data', 'totalAmount', 'totalCod', 'branchTotals'));
        }
    }

    public function dateCodHistory(Request $request)
    {
        $dateRange = $request->date;
        list($startDate, $endDate) = explode(' - ', $dateRange);
        $startDate = date('d-m-Y 00:00:00', strtotime($startDate));
        $endDate = date('d-m-Y 23:59:59', strtotime($endDate));
        $ordersQuery = COD::whereBetween('datetime', [$startDate, $endDate])
            ->orderBy('id', 'desc');
        $data = $ordersQuery->get();

        // Return AJAX response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.inc.allCodHistoryData', compact('data'))->render(),
            ]);
        }
    }

    public function orderHistory(Request $request, $id)
    {
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

        // dd($todayOrders, $monthOrders->count()); // Debugging output

        return view('admin.order-history', compact('todayOrders', 'monthOrders'));
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
        $todaySubmit = (clone $codQuery)
            ->where('datetime', 'like', $dateToday . '%')
            ->sum('amount');
        $todayPending = $todayCod - $todaySubmit;

        // Total COD Orders
        $totalCodQuery = COD::where('delivery_boy_id', $id)->get();
        $orderId = $totalCodQuery->pluck('order_id')->toArray();
        $totalCod = Order::whereIn('id', $orderId)->sum('price');
        $totalSubmit = $codHistory->sum('amount');
        $totalPending = $totalCod - $totalSubmit;


        return view('admin.cod-history', compact('todayCod', 'todaySubmit', 'todayPending', 'totalCod', 'totalSubmit', 'totalPending', 'codHistory'));
    }

    public function addCodAmount(Request $request)
    {
        $cod = new CodAmount();
        $cod->amount = $request->amount;
        $cod->delivery_boy_id = $request->delivery_boy;
        $cod->user_id = Session::get('uid');
        $cod->datetime = now('Asia/Kolkata')->format('d-m-Y H:i:s');
        $cod->save();

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

    // BranchCodHistory
    public function branchCodHistory($id)
    {
        $userId = $id;
        $data = CodSellerAmount::where('userid', $userId)->orderBy('id', 'desc')->get();
        $amount = $data->first();
        $seller_id = $userId;
        $branchdata = Branch::where('id', $userId)->first();
        $branch_name = $branchdata->fullname ?? "";
        return view('admin.branchALLCodHistory', compact('data', 'amount','seller_id','branch_name'));
    }
    
        // BranchCodeHistory Date Filter  12 june new code
    public function branchCodHistorydatefilter(Request $request)
    {
        try {
            $sellerId = $request->seller_id;
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Check if seller ID is provided
            if (!$sellerId) {
                return response()->json(['error' => 'Seller ID is required'], 400);
            }

            // Build query
            $query = CodSellerAmount::where('userid', $sellerId)->orderByDesc('id');

            // Apply date filters
            if ($startDate && $endDate) {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('datetime', [$start, $end]);
            } elseif ($startDate) {
                $query->where('datetime', '>=', Carbon::parse($startDate)->startOfDay());
            } elseif ($endDate) {
                $query->where('datetime', '<=', Carbon::parse($endDate)->endOfDay());
            }

            // Get data
            $data = $query->get();
            $totalAmount = $data->sum('total');

            // Format response
            $response = [
                'data' => $data->map(fn($item) => [
                    'datetime' => $item->datetime,
                    'c_amount' => $item->c_amount,
                    'd_amount' => $item->d_amount,
                    'total' => $item->total,
                    'msg' => $item->adminid && $item->users ? ($item->users->type . '/' . $item->msg) : 
                              ($item->msg === 'credit' ? 'Credit' : ($item->msg === 'debit' ? 'Debit' : $item->msg)),
                    'status' => $item->status,
                    'refno' => $item->refno,
                ])->toArray(),
                'amount' => ['total' => number_format($totalAmount, 2, '.', '')]
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error occurred'], 500);
        }
    }
    
    
    
    
    
     // Cod Sattlement CodSellerAmount Modal 
    public function deductBranchCod(Request $request)
    {
        $user = $request->seller_id;
        $amount = (float) $request->amount;
        $refno = $request->refno;
    
        // Get latest record
        $lastData = CodSellerAmount::where('userid', $user)->orderBy('id', 'desc')->first();
        $prevTotal = is_numeric($lastData->total ?? null) ? (float)$lastData->total : 0;
        $newTotal = $prevTotal - $amount;
    
        // Insert new record
        $wallet = new CodSellerAmount();
        $wallet->userid   = $user;
        $wallet->c_amount = 0;
        $wallet->d_amount = $amount;
        $wallet->total    = $newTotal;
        $wallet->datetime = now('Asia/Kolkata')->format('Y-m-d H:i:s');
        $wallet->status   = 'success';
        $wallet->refno    = $refno;
        $wallet->msg      = 'debit';
        $wallet->save();
    
        if ($request->ajax()) {
            $data = CodSellerAmount::where('userid', $user)->orderBy('id', 'desc')->get();
    
            return response()->json([
                'success' => true,
                'message' => 'Amount debited successfully!'
            ]);
        }
    }
    
    
    

    public function branchDateCodHistory(Request $request)
    {
        $userId = $request->id;
        $dateRange = $request->date;
        $orders = Order::where('seller_id', $userId)->get();
        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Empty Data',
            ]);
        } else {
            list($startDate, $endDate) = explode(' - ', $dateRange);
            $startDate = date('d-m-Y', strtotime($startDate));
            $endDate = date('d-m-Y', strtotime($endDate));
            $ordersQuery = COD::where('datetime', 'LIKE', "$startDate%")
                ->orWhere('datetime', 'LIKE', "$endDate%")
                ->whereIn('order_id', $orders->pluck('id'))
                ->orderBy('id', 'desc');
            $data = $ordersQuery->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.inc.branchALLCodHistory', compact('data'))->render(),
                ]);
            }
        }
    }

    // public function todayWalletHistory()
    // {
    //     $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
    //     $ordersQuery = Wallet::where('datetime', 'like', $dateTiem . '%');
    //     $todayWallet = $ordersQuery->get();
    //     $data = $ordersQuery->where('msg', 'credit')->orderBy('id', 'desc')->get();
    //     return view('admin.todayWalletHistory', compact('data', 'todayWallet'));
    // }
    
    public function todayWalletHistory(Request $request)
    {
        $query = Wallet::query();

        // Apply single date filter if provided
        if ($request->has('date') && !empty($request->date)) {
            $selectedDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('d-m-Y');
            $query->where('datetime', 'like', $selectedDate . '%');
        } else {
            // Default to today's date if no filter is applied
            $dateTime = now('Asia/Kolkata')->format('d-m-Y');
            $query->where('datetime', 'like', $dateTime . '%');
        }

        $todayWallet = $query->where('msg','!=','Order Cancelled')->get();
        $data = $query->where('msg', 'credit')->orderBy('id', 'desc')->get();

        return view('admin.todayWalletHistory', compact('data', 'todayWallet'));
    }

    public function feedback()
    {
        $data = FeedBack::orderBy('id', 'desc')->get();
        return view('admin.feedbacks', compact('data'));
    }

    public function deleteFeedBack(Request $request)
    {
        $fb = FeedBack::findOrFail($request->id);
        $fb->delete();
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function feedBackStatus(Request $request)
    {
        $fb = FeedBack::find($request->id);
        $fb->status = $request->status;
        $fb->save();
        $message = $fb->status == 'active' ? 'Feedback activated successfully!' : 'Feedback deactivated successfully!';
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }
    }
}
