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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Member::class)->constrained()->cascadeOnDelete();
            $table->bigInteger('amount')->default(0);
            $table->bigInteger('total_transaction')->default(0);
            $table->bigInteger('final_balance')->default(0);
            $table->bigInteger('monthly_deposit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
