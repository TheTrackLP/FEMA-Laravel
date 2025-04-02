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
            $table->integer('loan_id');
            $table->integer('borrower_id');
            $table->integer('off_rec');
            $table->tinyInteger('plan_id');
            $table->float('paid')->nullable();
            $table->float('interest')->nullable();
            $table->integer('capital')->nullable();
            $table->float('penalty')->nullable();
            $table->tinyInteger('overdue')->nullable();
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
