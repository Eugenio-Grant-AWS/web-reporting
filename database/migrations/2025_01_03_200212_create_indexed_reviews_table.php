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
        Schema::create('indexed_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('RespoSer');
            $table->string('QuotGene');
            $table->string('QuotEdad');
            $table->string('QuoSegur');
            $table->integer('Index');              // Store INDEX (e.g., 1, 2, 3, ...)
            $table->string('Influence');           // Store Influence type (e.g., 1. Awareness, 2. Understanding, ...)
            $table->string('tpi');
            $table->string('MediaType');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indexed_reviews');
    }
};