<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('pickupAddress')->nullable();
            $table->text('deliveryAddress')->nullable();

            $table->string('receiver_name');
            $table->string('receiver_cnumber');
            $table->string('receiver_email');
            $table->text('receiver_add');
            $table->string('receiver_pincode', 10);

            $table->string('sender_name')->nullable();
            $table->string('sender_number')->nullable();
            $table->string('sender_email')->nullable();
            $table->text('sender_address')->nullable();
            $table->string('sender_pincode', 10)->nullable();

            $table->string('service_type');
            $table->string('service_title');
            $table->string('service_price');
            $table->string('order_id');

            $table->string('seller_id')->nullable();
            $table->string('price');
            $table->enum('payment_mode', ['online', 'COD']);
            $table->string('codAmount')->nullable();
            $table->string('insurance', 10)->nullable();

            $table->enum('order_status', [
                'Booked',
                'Item Picked Up',
                'Item Not Picked Up',
                'Delivered to branch',
                'Returned',
                'In Transit',
                'Arrived at Destination',
                'Out for Delivery',
                'Delivered',
                'Not Delivered',
                'Returning to Origin',
                'Out for Delivery to Origin',
                'Cancelled'
            ]);

            $table->text('status_message')->nullable();
            $table->string('parcel_type')->nullable();
            $table->string('assign_to')->nullable();
            $table->string('assign_by')->nullable();
            $table->string('sender_order_pin')->nullable();
            $table->string('sender_order_pin_by')->nullable();
            $table->enum('sender_order_status',['Pending','Processing','Delivered'])->nullable();
            $table->string('datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
