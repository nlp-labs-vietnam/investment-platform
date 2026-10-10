<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;


class InvestmentFactory extends Factory
{

    public function definition(): array
    {
        $amount = $this->faker->numberBetween(1, 10) * 1000; // Between $1,000 and $10,000
        $loss = 0;
        $returnDate = Carbon::now()->subDays($this->faker->numberBetween(30, 365)); // Between 1 to 12 months
        $now = Carbon::now();

        $pId = $this->faker->numberBetween(2,6);
        $property = Property::find($pId);
        $profit = $property->profit;

        $returnTimeType = strtolower($property->return_time_type);
        $returnTime = $property->return_time;
        $func = $returnTimeType === 'days' ? 'addDays' : ($returnTimeType === 'months' ? 'addMonths' : 'addYears');
        $nextReturnDate = $now->$func($returnTime);

        return [
            'user_id' => $this->faker->numberBetween(3,3),
            'property_id' => $property->id,
            'amount' => $amount,
            'total_installment_late_fee' => null,
            'profit' => $profit,
            'profit_type' => $property->profit_type,
            'net_profit' => $profit - $loss,
            'loss' => $property->loss,
            'loss_type' => $property->loss_type,
            'net_loss' => $loss,
            'is_return_type' => $isReturnType = $property->is_return_type,
            'return_time' => $returnTime,
            'return_time_type' => $returnTimeType,
            'how_many_times' => $isReturnType ? null :$this->faker->numberBetween(100, 1500) ,
            'return_date' => $returnDate,
            'last_return_date' => $now,
            'is_installment' => $this->faker->boolean(50), // 50% chance
            'total_installments' => null ,
            'due_installments' => null,
            'next_installment_date_start' => $now->addDays($this->faker->numberBetween(1, 30)),
            'next_installment_date_end' => $now->addDays($this->faker->numberBetween(31, 60)),
            'invest_status' => 1, // 0=due, 1=complete
            'payment_status' => 1, // 1=complete, 2=pending, 3=rejected
            'status' => 0,
            'is_active' => 1,
            'deactive_reason' => null,
            'capital_back' => $property->is_capital_back,
            'trx' => strRandom(),
            'maturity' => null,
            'recurring_time' => null,
            'created_at' => $this->faker->dateTimeBetween('2024-06-01', '2025-06-30')->format('Y-m-d H:i:s'),
            'updated_at' => now(),
        ];

    }
}
