<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\WebOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Api extends Controller
{
    /*
     * Track order details by order ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function trackOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the order
        $order = WebOrder::where('order_id', $request->order_id)->first()
            ?? Order::where('order_id', $request->order_id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found!',
                'data' => null,
            ], 404);
        }

        $orderDetails = OrderHistory::where('tracking_id', $request->order_id)->get();

        $responseData = [
            'order' => [
                'id' => $order->id,
                'order_id' => $order->order_id,
                // 'pickupAddress' => $order->pickupAddress ?? null,
                // 'deliveryAddress' => $order->deliveryAddress ?? null,
                'receiver_name' => $order->receiver_name ?? null,
                'receiver_cnumber' => $order->receiver_cnumber ?? null,
                'receiver_email' => $order->receiver_email ?? null,
                'receiver_add' => $order->receiver_add ?? null,
                'receiver_pincode' => $order->receiver_pincode ?? null,
                'sender_name' => $order->sender_name ?? null,
                'sender_number' => $order->sender_number ?? null,
                'sender_email' => $order->sender_email ?? null,
                'sender_address' => $order->sender_address ?? null,
                'sender_pincode' => $order->sender_pincode ?? null,
                'service_type' => $order->service_type ?? null,
                'service_title' => $order->service_title ?? null,
                'service_price' => $order->service_price ?? null,
                'seller_id' => $order->seller_id ?? null,
                'price' => $order->price ?? null,
                'payment_mode' => $order->payment_mode ?? null,
                'codAmount' => $order->codAmount ?? null,
                'insurance' => $order->insurance ?? null,
                'order_status' => $order->order_status ?? null,
                'status_message' => $order->status_message ?? null,
                'parcel_type' => $order->parcel_type ?? null,
                'datetime' => $order->datetime ?? null,
                'delivery_time' => $order->delivery_time ?? null,
            ],
            'history' => $orderDetails->map(function ($item) {
                return [
                    'id' => $item->id ?? null,
                    'tracking_id' => $item->tracking_id ?? null,
                    'status' => $item->status ?? null,
                    'datetime' => $item->datetime ?? null,
                ];
            }),
        ];
        return response()->json([
            'success' => true,
            'message' => 'Order found successfully!',
            'data' => $responseData,
        ]);
    }
    
    
    
    // end here 
}

