<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MedicamentFactory extends Factory
{
    public function definition()
    {
        return [
            'nom' => $this->faker->word() . ' ' . $this->faker->randomElement(['500mg', '250mg', '100mg']),
            'forme' => $this->faker->randomElement(['Comprimé', 'Sirop', 'Injectable']),
            'dosage' => $this->faker->randomElement(['500mg', '250mg', '100mg']),
            'categorie' => $this->faker->randomElement(['Antibiotique', 'Antalgique', 'Anti-inflammatoire']),
            'stock' => $this->faker->numberBetween(10, 200),
            'stock_min' => 10,
            'prix_unitaire' => $this->faker->randomElement([500, 1000, 2500, 5000]),
        ];
    }
}
