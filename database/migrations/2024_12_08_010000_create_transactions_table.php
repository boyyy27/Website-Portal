<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->foreignId('package_id')->nullable()->constrained()->onDelete('set null');
            $table->string('package_name')->nullable();
            $table->decimal('package_price', 10, 2)->nullable();
            $table->string('transaction_status')->default('pending');
            $table->string('payment_type')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->decimal('gross_amount', 10, 2);
            $table->string('currency')->default('IDR');
            $table->string('fraud_status')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->boolean('notification_received')->default(false);
            $table->integer('notification_count')->default(0);
            $table->timestamp('last_notification_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('package_id');
            $table->index('order_id');
            $table->index('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

