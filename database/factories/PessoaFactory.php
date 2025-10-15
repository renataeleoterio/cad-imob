<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pessoa>
 */
class PessoaFactory extends Factory
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
            'nome' => fake()->name(),
            'cpf' => fake()->unique()->numerify('###########'),
            'data_nascimento' => fake()->date('Y-m-d', '2005-12-31'),
            'sexo' => fake()->randomElement(['Masculino', 'Feminino', 'Outro']),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => fake()->phoneNumber(),
        ];
    }
}
