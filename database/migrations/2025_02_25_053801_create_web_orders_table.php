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
        Schema::create('web_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id'); // Order ID
            $table->string('service_type'); // Service Type
            $table->string('service_id');
            
            $table->string('pickupAddress')->nullable(); // Pickup Address
            $table->string('deliveryAddress')->nullable(); // Delivery Address
            
            $table->string('sender_name'); // Sender Name
            $table->string('sender_number'); // Sender Number
            $table->string('sender_email'); // Sender Email
            $table->text('sender_address');// Sender Address
            $table->string('senderPinCode');// Sender PinCode
            
            $table->string('receiver_name');// Receiver Name
            $table->string('receiver_number');// Receiver Number
            $table->string('receiver_email');// Receiver Email
            $table->text('receiver_address');// Receiver Address
            $table->string('receiverPinCode');// Receiver PinCode
            
            $table->string('payment_methods');// Payment Methods
            $table->string('codAmount')->nullable(); // COD Amount
            $table->string('insurance')->nullable();
            $table->string('price');
            
            $table->enum('order_status', [
                'Booked',
                'Item Picked Up',
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
            $table->string('datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_orders');
    }
};
