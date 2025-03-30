<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\DlyBoy;
use App\Models\Enquiry;
use App\Models\FeedBack;
use App\Models\Order;
use App\Models\PinCode;
use App\Models\Service;
use App\Models\User;
use App\Models\WebOrder;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        $se = Service::where('type', 'se')->where('status', 'active')->orderBy('id', 'desc')->get();
        $ex = Service::where('type', 'ex')->where('status', 'active')->orderBy('id', 'desc')->get();
        $ss = Service::where('type', 'ss')->where('status', 'active')->orderBy('id', 'desc')->get();
        return view('web.welcome', compact('ex', 'ss', 'se'));
    }

    public function bookParcel()
    {
        $se = Service::where('type', 'se')->where('status', 'active')->orderBy('id', 'desc')->get();
        $ex = Service::where('type', 'ex')->where('status', 'active')->orderBy('id', 'desc')->get();
        $ss = Service::where('type', 'ss')->where('status', 'active')->orderBy('id', 'desc')->get();
        return view('web.bookparcel', compact('ex', 'ss', 'se'));
    }

    public function service()
    {
        return view('web.service');
    }

    public function webEnquiry()
    {
        $data = Category::where('status', 'active')->get();
        return view('web.enquiry', compact('data'));
    }

    public function addEnquiry(Request $request)
    {
        $eq = new Enquiry();
        $eq->fullname = $request->fullName;
        $eq->itemno = $request->itemsCount;
        $eq->email = $request->email;
        $eq->gst_panno = $request->panNo;
        $eq->phoneno = $request->phone;
        $eq->category = $request->category;
        $eq->fulladdress = $request->fullAddress;
        $eq->message = $request->contactMessage;
        $eq->pinCode = $request->pinCode;
        $eq->status = 'inactive';
        // Handle Pan Image
        if ($request->hasFile('panImage')) {
            $eq->gst_panno_img = 'admin/upload/branch/' . $request->file('panImage')->move(public_path('admin/upload/branch'), 'EID_' . time() . '.' . $request->file('panImage')->extension())->getFilename();
        }
        $eq->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Enquiry send successfully!',
            ]);
        }
    }

    public function about()
    {
        return view('web.about');
    }

    public function privacy()
    {
        return view('web.privacy');
    }

    public function termsConditions()
    {
        return view('web.termsConditions');
    }

    public function refundpolicy()
    {
        return view('web.refundpolicy');
    }

    public function trackOrder($id = null)
    {
        if ($id == null) {
            return view('web.trackOrder', ['order' => null]);
        }
        $order = WebOrder::where('order_id', $id)->first()
            ?? Order::where('order_id', $id)->first();

        return view('web.trackOrder', compact('order'));
    }

    public function trackOrderDetails(Request $request)
    {
        $order = WebOrder::where('order_id', $request->orderId)->first()
            ?? Order::where('order_id', $request->orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found!',
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order found successfully!',
            'data' => $order,
        ]);
    }


    public function blog()
    {
        return view('web.blog');
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

    public function parcelDetails(Request $request)
    {
        // dd($request->all());
        $data['service_type'] = $request->service_type ?? 'NA';
        $data['service_id'] = $request->service_id ?? 'NA';
        $data['service_price'] = $request->service_price ?? '0';
        $data['pickupPincode'] = $request->pickupPincode ?? 'NA';
        $data['deliveryPincode'] = $request->deliveryPincode ?? 'NA';
        $data['pickupAddress'] = $request->pickupAddress ?? 'NA';
        $data['deliveryAddress'] = $request->deliveryAddress ?? 'NA';
        $data['price'] = $request->price ?? 'NA';
        return view('web.parcelDetails', compact('data'));
    }

    public function storeParcelDetails(Request $request)
    {
        // dd($request->all());
        $request->service_id;
        $service = Service::where('id', $request->service_id)->first();
        $branch = Branch::where('pincode', 'LIKE', "%{$request->senderPinCode}%")->where('type', 'Delivery')->first();

        $DlyBoy = DlyBoy::where('pincode', 'LIKE', "%{$request->senderPinCode}%")->where(['status' => 'active'])->first();

        $order = new Order();
        $order->pickupAddress = $request->pickupAddress;
        $order->deliveryAddress = $request->deliveryAddress;

        $order->receiver_name = $request->receiver_name;
        $order->receiver_cnumber = $request->receiver_number;
        $order->receiver_email = $request->receiver_email;
        $order->receiver_add = $request->receiver_address;
        $order->receiver_pincode = $request->receiverPinCode;

        $order->sender_name = $request->sender_name;
        $order->sender_number = $request->sender_number;
        $order->sender_email = $request->sender_email;
        $order->sender_address = $request->sender_address;
        $order->sender_pincode = $request->senderPinCode;

        $order->service_type = $request->service_type;
        $order->service_title = $service->title;
        $order->service_price = $request->price;
        $order->order_id = 'DL' . $this->generateRandomCode();
        $order->seller_id = $branch->id ?? null;
        $order->price = $request->price;
        $order->payment_mode = $request->payment_methods;
        $order->codAmount = $request->codAmount;
        $order->insurance = $request->insurance;
        $order->order_status = 'Booked';
        $order->assign_to = $DlyBoy->id ?? null;
        $order->parcel_type = 'Direct';
        $order->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
        $order->save();
        $orderId = $order->order_id;



        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'msg' => 'Order Booked Successfully!',
                'data' => $orderId,
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

    public function review($action)
    {
        if ($action == 'FeedBack') {
            return view('web.reviews');
        }
    }

    public function storeReview(Request $request)
    {
        $dateTime = now('Asia/Kolkata')->format('d-m-Y');
        $review = new FeedBack();
        $review->name = $request->fullName;
        $review->email = $request->email;
        $review->phone = $request->phone;
        $review->message = $request->message;
        $review->datetime = $dateTime;
        $review->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review send successfully!',
            ]);
        }
    }

    public function orderLabel($id)
    {
        $data = Order::where('order_id', $id)->first() ?? WebOrder::where('order_id', $id)->first();
        if ($data->payment_mode == 'COD' || $data->payment_methods == 'COD') {
            return view('web.label', compact('data'));
        } else {
            return view('web.label1', compact('data'));
        }
    }
}
