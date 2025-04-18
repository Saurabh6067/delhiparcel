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
        Schema::create('branchs', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->text('fulladdress');
            $table->string('itemcount');
            $table->string('phoneno');
            $table->string('category');
            $table->string('gst_panno');
            $table->string('gst_panno_img')->nullable();
            $table->string('pincode');
            $table->string('type');
            $table->string('type_logo')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('branch_cm')->nullable();
            $table->string('branch_otp', 6)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branchs');
    }
};
