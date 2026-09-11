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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('refno')->nullable();
            $table->integer('borrower_id');
            $table->integer('loantype_id');
            $table->text('purpose');
            $table->decimal('currbalance');
            $table->decimal('amountborrowed');
            $table->tinyInteger('status')->default(0);
            $table->dateTime('date_approved')->nullable();
            $table->dateTime('date_released')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
