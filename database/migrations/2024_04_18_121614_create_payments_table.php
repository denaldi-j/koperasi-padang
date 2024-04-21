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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Balance::class)->constrained()->cascadeOnDelete();
            $table->bigInteger('amount');
            $table->integer('discount');
            $table->bigInteger('final_amount')->nullable();
            $table->boolean('is_cash')->default(false);
            $table->bigInteger('cash')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
