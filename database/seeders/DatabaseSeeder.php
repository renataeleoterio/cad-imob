<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Documento;
use App\Models\Imovel;
use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        
        User::factory(5)->create();

        
        Pessoa::factory(10)->create();

        
        Imovel::factory(5)->create();

        // 10 documentos (cada um associado a algum imóvel)
        //Documento::factory(10)->create();
    }
}
