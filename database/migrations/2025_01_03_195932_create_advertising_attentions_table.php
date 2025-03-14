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
        Schema::create('advertising_attentions', function (Blueprint $table) {
            $table->id();
            $table->integer('RespoSer');
            $table->string('QuotGene');
            $table->string('QuotEdad');
            $table->string('QuoSegur');
            $table->string('MediaType');
            $table->text('Value');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertising_attentions');
    }
};