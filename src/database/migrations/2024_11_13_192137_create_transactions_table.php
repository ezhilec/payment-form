<?php

use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('transaction_id');
            $table->string('payment_method');
            $table->decimal('amount', 10, 2);
            $table->string('country', 3);
            $table->string('currency', 3);
            $table->string('description')->nullable();
            $table->string('success_redirect_url')->nullable();
            $table->string('fail_redirect_url')->nullable();
            $table->string('type_of_calculation');
            $table->string('transaction_type');
            $table->string('status')->default(TransactionStatusEnum::PENDING);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
