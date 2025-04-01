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
        Schema::create('loan_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_refno');
            $table->integer('borrower_id');
            $table->longText('purpose');
            $table->float('shared_capital');
            $table->float('amount');
            $table->float('amount_borrow');
            $table->integer('plan_id');
            $table->tinyInteger('status');
            $table->dateTime('date_released')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_lists');
    }
};
