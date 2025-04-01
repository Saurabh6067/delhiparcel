<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\COD;
use App\Models\CodAmount;
use App\Models\DlyBoy;
use App\Models\Enquiry;
use App\Models\FeedBack;
use App\Models\Order;
use App\Models\PinCode;
use App\Models\Service;
use App\Models\Servicetype;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WebOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;


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

    public function adminDashboard()
    {
        // toDayOrder details
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = Order::where('datetime', 'like', $dateTime . '%');
        $toDayOrder = $ordersQuery->count();
        // Clone the base query to avoid modification issues
        $toDayPendingOrder = (clone $ordersQuery)->where('order_status', 'Booked')->count();
        $toDayOrderPicUp = (clone $ordersQuery)->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->count();
        $toDayCompleteOrder = (clone $ordersQuery)->where('order_status', 'Delivered')->count();
        $toDayCancelledOrder = (clone $ordersQuery)->where('order_status', 'Cancelled')->count();


        $branch = Branch::whereIn('type', ['Booking', 'Delivery'])->count();
        $sellerbranch = Branch::where('type', 'Seller')->count();
        $cat = Category::count();
        $pin = PinCode::count();
        $eq = Enquiry::count();
        $dBoy = DlyBoy::count();

        $todayWallet = Wallet::where('datetime', 'like', $dateTime . '%')->where('msg', 'credit')->get();
        $ordersQuery = COD::where('datetime', 'like', $dateTime . '%');
        $data = $ordersQuery->orderBy('id', 'desc')->get();

        $directOrder = WebOrder::where('order_status', 'Booked')->where('datetime', 'like', $dateTime . '%')->count();

        return view('admin.dashboard', compact('branch', 'sellerbranch', 'cat', 'pin', 'eq', 'dBoy', 'data', 'toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'todayWallet', 'directOrder'));
    }

    public function adminOrderDetails($action)
    {
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        // $data = Order::where('datetime', 'like', $dateTime . '%')->get();
        $ordersQuery = Order::where('datetime', 'like', $dateTime . '%')->whereIn('parcel_type', ['Direct', 'delivery', 'Pickup']);
        if ($action == 'toDayOrder') {
            $data = $ordersQuery->orderBy('id', 'desc')->get();
            // dd($data);
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
        return view('admin.adminBranch', compact('data'));
    }

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

    public function adminBranch($id = null)
    {
        $singleData = !empty($id) ? Branch::where('id', $id)->first() : null;
        $cat = Category::where('status', 'active')->get();
        $pinCode = PinCode::where('status', 'active')->get();
        // dd($pinCode->toArray());
        return view('admin.branch', compact('cat', 'singleData', 'pinCode'));
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
        dd($request->all());
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

        $brn->pincode = $request->pinCode;
        // $brn->pincode = implode(',', $request->pinCode);
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

    public function allBranch()
    {
        $data = Branch::whereIn('type', ['Booking', 'Delivery'])->orderBy('id', 'desc')->get();
        return view('admin.allBranch', compact('data'));
    }

    public function allSellerBranch()
    {
        $data = Branch::where('type', 'Seller')->orderBy('id', 'desc')->get();
        return view('admin.allSellerBranch', compact('data'));
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
            ]);
        }
    }

    public function allDeliveryBoy()
    {
        $data = DlyBoy::orderBy('id', 'desc')->get();
        return view('admin.allDeliveryBoy', compact('data'));
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
        $branch->type =  $request->branch;
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
        return view('admin.invoice', compact('data'));
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

        return view('admin.branchManageBranch', compact('toDayOrder', 'toDayPendingOrder', 'toDayOrderPicUp', 'toDayCompleteOrder', 'toDayCancelledOrder', 'amount', 'totalDlyBoy', 'totalOrder', 'totalPendingOrder', 'totalOrderPicUp', 'totalCompleteOrder', 'totalCanceledOrder'));
    }

    public function orderDetails($id, $action)
    {
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
        } elseif ($action == 'totalOrder' || $action == 'totalPendingOrder' || $action == 'totalOrderPicUp' || $action == 'totalCompleteOrder' || $action == 'totalCanceledOrder') {
            $ordersQuery = Order::where('seller_id', $id);
            if ($action == 'totalOrder') {
                $data = $ordersQuery->orderBy('id', 'desc')->get();
            } elseif ($action == 'totalPendingOrder') {
                $data = $ordersQuery->where('order_status', 'Booked')->get();
            } elseif ($action == 'totalOrderPicUp') {
                $data = $ordersQuery->whereNotIn('order_status', ['Booked', 'Delivered', 'Cancelled'])->get();
            } elseif ($action == 'totalCompleteOrder') {
                $data = $ordersQuery->where('order_status', 'Delivered')->get();
            } elseif ($action == 'totalCanceledOrder') {
                $data = $ordersQuery->where('order_status', 'Cancelled')->get();
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

    public function allCodHistory()
    {
        $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = COD::where('datetime', 'like', $dateTiem . '%');
        $data = $ordersQuery->orderBy('id', 'desc')->get();
        return view('admin.allCodHistory', compact('data'));
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

    public function branchCodHistory($id)
    {
        $userId = $id;
        $orders = Order::where('seller_id', $userId)->get();
        $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = COD::where('datetime', 'like', $dateTiem . '%')->whereIn('order_id', $orders->pluck('id'));
        $data = $ordersQuery->orderBy('id', 'desc')->get();
        return view('admin.branchALLCodHistory', compact('data'));
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

    public function todayWalletHistory()
    {
        $dateTiem = now('Asia/Kolkata')->format('d-m-Y');
        $ordersQuery = Wallet::where('datetime', 'like', $dateTiem . '%');
        $todayWallet = $ordersQuery->get();
        $data = $ordersQuery->where('msg', 'credit')->orderBy('id', 'desc')->get();
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
