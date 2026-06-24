<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('usuarios');

        Schema::create('usuarios', function ($table) {
            $table->id();
            $table->string('nome');
            $table->string('login')->unique();
            $table->string('senha');
            $table->boolean('situacao')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
