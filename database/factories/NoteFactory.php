<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Company;
use App\Models\Contact;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition()
    {
        $noteable = $this->noteableType() === 'contact' 
            ? \App\Models\Contact::factory()
            : \App\Models\Company::factory();
            
        return [
            'note' => $this->faker->paragraph,
            'noteable_type' => $this->noteableType(),
            'noteable_id' => $noteable
        ];
    }

    public function forContact()
    {
        return $this->state(function (array $attributes) {
            return [
                'noteable_type' => 'contact',
                'noteable_id' => Contact::factory()
            ];
        });
    }

    public function forCompany()
    {
        return $this->state(function (array $attributes) {
            return [
                'noteable_type' => 'company',
                'noteable_id' => Company::factory()
            ];
        });
    }

    protected function noteableType(): string
    {
        return $this->faker->randomElement([Contact::class, Company::class]);
    }
}