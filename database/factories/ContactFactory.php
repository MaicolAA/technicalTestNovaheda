<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->numerify('##########'), 
            'address' => $this->faker->streetAddress,
            'company_id' => Company::factory()
        ];
    }
}