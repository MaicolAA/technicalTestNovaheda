<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->streetAddress,
            'website' => 'https://' . $this->faker->domainName,
            'description' => $this->faker->sentence
        ];
    }
}
