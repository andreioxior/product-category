<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bikes', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer', 255);
            $table->string('model', 255);
            $table->integer('year');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['manufacturer', 'model', 'year']);
            $table->index(['manufacturer', 'model', 'year']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bikes');
    }
};
