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
            $table->string('ofrec');
            $table->integer('loan_id');
            $table->integer('borrower_id');
            $table->integer('type_id');
            $table->decimal('total_paid')->nullable();
            $table->decimal('balance_after')->nullable();
            $table->decimal('principal')->nullable();
            $table->decimal('interest')->nullable();
            $table->decimal('capital')->nullable();
            $table->decimal('penalty')->nullable();
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
