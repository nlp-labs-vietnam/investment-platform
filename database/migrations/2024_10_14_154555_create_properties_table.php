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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->boolean('is_invest_type')->default(0)->comment('fixed/range');
            $table->boolean('is_return_type')->default(0)->comment('Will you get the profit on the investment for a certain period of time or will you continue to get it for life?');
            $table->boolean('is_installment')->default(0)->comment('Is there an opportunity to repay the money invested in a particular property in stages or not?');
            $table->boolean('is_capital_back')->default(0)->comment('Will he ever get back the investment amount?');
            $table->boolean('is_investor')->default(0)->comment('Can the investor sell his shares to another investor or not?');
            $table->decimal('fixed_amount', 11,2)->nullable();
            $table->decimal('minimum_amount', 11, 2)->nullable();
            $table->decimal('maximum_amount', 11, 2)->nullable();
            $table->decimal('total_investment_amount', 11, 2)->nullable()->comment('Total investment amount is the total amount of investment required in a particular property.');
            $table->decimal('available_funding', 11, 2)->nullable()->comment('The amount left to invest in a particular property');
            $table->integer('how_many_days')->nullable()->comment('After how many days you will get the return');
            $table->integer('how_many_times')->nullable()->comment('How many times profit will be returned');
            $table->decimal('profit', 11, 2)->default(0)->comment('fixed/%');
            $table->integer('profit_type')->nullable()->comment('fixed/%');
            $table->decimal('loss', 11, 2)->default(0)->comment('fixed/%');
            $table->integer('loss_type')->nullable()->comment('fixed/%');
            $table->integer('total_installments')->nullable();
            $table->decimal('installment_amount', 11, 2)->nullable();
            $table->integer('installment_duration')->nullable();
            $table->string('installment_duration_type')->nullable();
            $table->decimal('installment_late_fee', 11, 2)->nullable();

            $table->text('details')->nullable();
            $table->text('faq')->nullable();
            $table->text('video')->nullable();
            $table->text('amenity_id')->nullable();
            $table->foreignId('address_id')->index();
            $table->text('location')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('driver')->nullable();
            $table->timestamp('start_date')->nullable()->comment('property investment start date');
            $table->timestamp('expire_date')->nullable()->comment('property investment expire date');
            $table->boolean('is_payment')->default(1)->comment('0=>manual profit return 1=>auto profit return');
            $table->boolean('is_featured')->default(0)->comment('Whether or not this property will show on the home page feature section');
            $table->boolean('is_available_funding')->default(0)->comment('How much more money is available for investment in the property now can the user or invest frontend see it or not?');
            $table->integer('status')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
