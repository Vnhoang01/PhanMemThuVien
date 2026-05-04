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
        Schema::create('loan_slip_detail_error', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_slip_detail_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('error_id')
                ->constrained()
                ->cascadeOnDelete();

            // có thể override tiền phạt
            $table->integer('fine_amount')->default(0);

            $table->timestamps();

            // tránh trùng 1 lỗi gắn nhiều lần vào 1 sách
            $table->unique(['loan_slip_detail_id', 'error_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_slip_detail_error');
    }
};
