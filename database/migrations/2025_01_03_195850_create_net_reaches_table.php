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
        Schema::create('net_reaches', function (Blueprint $table) {
            $table->id();
            $table->string('QuotGene');
            $table->string('QuotEdad');
            $table->string('QuoSegur');
            $table->string('MediaType');
            $table->text('Value');
            $table->text('Adjusted_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('net_reaches');
    }
};
