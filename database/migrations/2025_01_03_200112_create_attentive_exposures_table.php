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
        Schema::create('attentive_exposures', function (Blueprint $table) {
            $table->id();
            $table->string('RespoSer');
            $table->string('QuotGene');
            $table->string('QuotEdad');
            $table->string('QuoSegur');
            $table->string('MediaType');
            $table->integer('Value');
            $table->integer('Reach');
            $table->float('attentive_exposure', 4, 4);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attentive_exposures');
    }
};
