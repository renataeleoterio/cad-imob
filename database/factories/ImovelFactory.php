<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Imovel>
 */
class ImovelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'tipo' => fake()->randomElement(['Terreno', 'Casa', 'Apartamento']),
            'area_terreno' => fake()->randomFloat(2, 50, 1000),
            'area_edificacao' => fake()->randomFloat(2, 30, 500),
            'logradouro' => fake()->streetName(),
            'numero' => fake()->buildingNumber(),
            'bairro' => fake()->citySuffix(),
            'complemento' => fake()->optional()->secondaryAddress(),
            'contribuinte_id' => Pessoa::factory(),
            'situacao' => fake()->randomElement(['Ativo', 'Inativo']),
        ];
    }
}
