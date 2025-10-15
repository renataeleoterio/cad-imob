<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('averbacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imovel_id')->constrained('imoveis')->onDelete('cascade');
            $table->enum('evento', [
                'aumento_area_construida', 
                'reducao_area_construida', 
                'observacao', 
                'cancelamento', 
                'reativacao'
            ]);
            $table->decimal('medida', 10, 2)->nullable();
            $table->text('descricao');
            $table->date('data');
            $table->timestamps();


            $table->index(['imovel_id', 'data']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('averbacaos');
    }
};
