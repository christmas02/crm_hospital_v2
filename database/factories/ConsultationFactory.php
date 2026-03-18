<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Medecin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'medecin_id' => Medecin::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'heure' => $this->faker->time('H:i'),
            'motif' => $this->faker->sentence(),
            'statut' => 'en_attente',
        ];
    }
}
