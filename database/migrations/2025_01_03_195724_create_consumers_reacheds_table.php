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
        Schema::create('consumers_reacheds', function (Blueprint $table) {
            $table->id();
            $table->string('resposer')->nullable(); // Responder's identifier
            $table->string('quotgene')->nullable(); // QuotGene
            $table->string('quotedad')->nullable(); // QuotEdad
            $table->string('quosegur')->nullable(); // QuoSegur

            // Add columns for all variables
            $table->integer('ver_tv_senal_nacional')->nullable();
            $table->integer('ver_tv_cable')->nullable();
            $table->integer('ver_tv_internet')->nullable();
            $table->integer('escuchar_radio')->nullable();
            $table->integer('escuchar_radio_internet')->nullable();
            $table->integer('leer_revista_impresa')->nullable();
            $table->integer('leer_revista_digital')->nullable();
            $table->integer('leer_periodico_impreso')->nullable();
            $table->integer('leer_periodico_digital')->nullable();
            $table->integer('leer_periodico_email')->nullable();
            $table->integer('vallas_publicitarias')->nullable();
            $table->integer('centros_comerciales')->nullable();
            $table->integer('transitar_metrobuses')->nullable();
            $table->integer('ver_cine')->nullable();
            $table->integer('abrir_correos_companias')->nullable();
            $table->integer('entrar_sitios_web')->nullable();
            $table->integer('entrar_facebook')->nullable();
            $table->integer('entrar_twitter')->nullable();
            $table->integer('entrar_instagram')->nullable();
            $table->integer('entrar_youtube')->nullable();
            $table->integer('entrar_linkedin')->nullable();
            $table->integer('entrar_whatsapp')->nullable();
            $table->integer('escuchar_spotify')->nullable();
            $table->integer('ver_netflix')->nullable();
            $table->integer('utilizar_mailing_list')->nullable();
            $table->integer('videojuegos_celular')->nullable();
            $table->integer('utilizar_we_transfer')->nullable();
            $table->integer('utilizar_waze')->nullable();
            $table->integer('utilizar_uber')->nullable();
            $table->integer('utilizar_pedidos_ya')->nullable();
            $table->integer('utilizar_meet')->nullable();
            $table->integer('utilizar_zoom')->nullable();
            $table->integer('utilizar_airbnb')->nullable();
            $table->integer('entrar_google')->nullable();
            $table->integer('entrar_encuentra24')->nullable();
            // $table->integer('reach')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers_reacheds');
    }
};