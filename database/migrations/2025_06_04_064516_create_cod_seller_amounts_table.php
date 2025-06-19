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
        Schema::create('cod_seller_amounts', function (Blueprint $table) {
            $table->id();
            $table->string('userid')->nullable();
            $table->string('c_amount')->nullable(); // Credit amount
            $table->string('d_amount')->nullable(); // Debit amount
            $table->string('total')->nullable();
            $table->string('datetime')->nullable();
            $table->string('status')->nullable();
            $table->string('adminid')->nullable();
            $table->string('refno')->nullable();
            $table->text('msg')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cod_seller_amounts');
    }
};
