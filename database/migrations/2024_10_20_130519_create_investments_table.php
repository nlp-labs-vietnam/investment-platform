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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('property_id')->nullable();
            $table->decimal('amount', 11, 2)->nullable();
            $table->decimal('total_installment_late_fee', 11, 2)->nullable();
            $table->decimal('profit', 11, 2)->default(0.00);
            $table->integer('profit_type')->nullable();
            $table->decimal('net_profit', 11, 2)->nullable();
            $table->decimal('loss', 8, 2)->default(0.00);
            $table->integer('loss_type')->nullable();
            $table->decimal('net_loss', 11, 2)->nullable();
            $table->integer('is_return_type')->nullable();
            $table->integer('return_time')->nullable();
            $table->string('return_time_type', 60)->nullable();
            $table->integer('how_many_times')->nullable();
            $table->timestamp('return_date')->useCurrent();
            $table->timestamp('last_return_date')->useCurrent();
            $table->boolean('is_installment')->nullable();
            $table->integer('total_installments')->nullable();
            $table->integer('due_installments')->nullable();
            $table->timestamp('next_installment_date_start')->useCurrent();
            $table->timestamp('next_installment_date_end')->useCurrent();
            $table->integer('invest_status')->nullable()->comment('0=due, 1=complete');
            $table->integer('payment_status')->nullable()->comment('1=complete, 2=pending, 3=rejected');
            $table->boolean('status')->default(0)->comment('0 => Running, 1=> completed');
            $table->boolean('is_active')->default(1)->comment('1= active, 0 = deactive');
            $table->longText('deactive_reason')->nullable();
            $table->boolean('capital_back')->default(0)->comment('1 = YES & 0 = NO');
            $table->string('trx')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('maturity')->nullable();
            $table->integer('recurring_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
