<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;


class PropertyFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'is_invest_type' => $isInvestType = $this->faker->boolean(),
            'is_return_type' => $isReturnType = $this->faker->boolean(),
            'is_installment' => $isInstallment = ($isInvestType ? $this->faker->boolean() : 0),
            'is_capital_back' => $this->faker->boolean(),
            'is_investor' => $this->faker->boolean(),
            'fixed_amount' =>  $isInstallment ? $this->faker->numberBetween(1, 10) * 1000 : null,
            'minimum_amount' => $isInstallment ? null : ($minimum = $this->faker->numberBetween(1, 3) * 500),
            'maximum_amount' => $isInstallment ? null : $this->faker->numberBetween(4, 10) * 500,
            'total_investment_amount' => $this->faker->numberBetween(5, 20) * 500,
            'available_funding' => $this->faker->numberBetween(1, 10) * 1000,
            'how_many_days' => $this->faker->numberBetween(10, 13),
            'how_many_times' => $isReturnType? null : $this->faker->randomElement([100, 500, 1000, 1500]),
            'profit' => $this->faker->randomFloat(2, 0.5, 5),
            'profit_type' => 1,
            'loss' => 0,
            'loss_type' => null,
            'total_installments' => $isInstallment ? $this->faker->numberBetween(1,10) : null,
            'installment_amount' => $isInstallment ? $this->faker->randomElement([500, 1000, 2000]) : null,
            'installment_duration' => $isInstallment ? $this->faker->randomElement([7, 15, 10]) : null,
            'installment_duration_type' => $isInstallment ? 'Days' : null,
            'installment_late_fee' => $isInstallment ? $this->faker->randomElement([10, 20, 30, 40, 50]) : null,

            'details' => $this->faker->paragraphs(5, true),
            'faq' => null,
            'video' => 'https://www.youtube.com/embed/VZey7-VAo7Q?si=zscmGnNTbXvaS1Vb',
            'amenity_id' => $this->faker->randomElements(['1', '2', '3', '4', '5', '6', '7'], 3),
            'address_id' => $this->faker->numberBetween(1, 8),
            'location' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13695570.034005085!2d126.54757346407052!3d33.06581103725837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x34674e0fd77f192f%3A0xf54275d47c665244!2sJapan!5e0!3m2!1sen!2sbd!4v1732020617420!5m2!1sen!2sbd',
            'thumbnail' => $this->faker->randomElement([
                                'property/thumbnail/fziMEd0BWR9dolnl5TRWntd6Ukqcsy.webp',
                                'property/thumbnail/nzV0tbtqZEKOyx4aHTNDmxEuwiYHdu.webp',
                                'property/thumbnail/SehTx8KPsE9KXzQtIDkLbTAxCEI3rP.webp',
                                'property/thumbnail/t6mjvKZ6HDMltbWQ8sySIdhk8PSqoy.webp',
                            ]),
            'driver' => "local",
            'start_date' => Carbon::now()->subDays($this->faker->numberBetween(1, 30))->toDateString(),
            'expire_date' => Carbon::now()->addDays($this->faker->numberBetween(30, 365))->toDateString(),
            'is_payment' => 1,
            'is_featured' => $this->faker->boolean(),
            'is_available_funding' => $this->faker->boolean(),
            'status' => 1,
            'get_favourite_count' => $this->faker->numberBetween(0, 100),
            'reviews_count' => $this->faker->numberBetween(0, 50),
            'avg_rating' => $this->faker->randomFloat(1, 1, 5),
            'deleted_at' => null, // or $this->faker->optional()->dateTime()
            'created_at' => $this->faker->dateTimeBetween('2024-06-01', '2025-06-30')->format('Y-m-d H:i:s'),
            'updated_at' => now(),
        ];
    }

}
