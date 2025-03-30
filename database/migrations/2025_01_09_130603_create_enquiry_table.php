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
        Schema::create('enquirys', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('itemno');
            $table->string('email')->unique();
            $table->string('gst_panno');
            $table->string('phoneno');
            $table->string('category');
            $table->text('fulladdress');
            $table->longText('message');
            $table->string('gst_panno_img')->nullable();
            $table->string('pinCode', 6)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquirys');
    }
};
