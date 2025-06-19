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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userid');
            // rec
            $table->string('c_amount')->nullable();
            // send 
            $table->string('d_amount')->nullable();
            $table->string('total')->nullable();
            $table->string('datetime')->nullable();
            $table->enum('status', ['success', 'cancelled', 'pending']);
            $table->string('adminid')->nullable();
            $table->string('refno')->nullable();
            $table->text('msg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
