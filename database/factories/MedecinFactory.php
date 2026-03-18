<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MedecinFactory extends Factory
{
    public function definition()
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'specialite' => $this->faker->randomElement(['Médecine générale', 'Cardiologie', 'Pédiatrie', 'Chirurgie']),
            'telephone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'bureau' => 'A-' . $this->faker->numberBetween(100, 400),
            'statut' => 'disponible',
            'tarif_consultation' => $this->faker->randomElement([15000, 20000, 25000, 30000]),
        ];
    }
}
