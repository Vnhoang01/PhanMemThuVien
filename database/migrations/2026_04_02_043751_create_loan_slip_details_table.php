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
        Schema::create('loan_slip_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_slip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_detail_id')->constrained()->cascadeOnDelete();
            $table->enum('status', [
                'borrowing',
                'returned',
                'problem'
            ])->default('borrowing');
            $table->unique(['loan_slip_id', 'book_detail_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_slip_details');
    }
};
