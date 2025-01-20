<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsumersReached extends Model
{
    use HasFactory;
    // protected $table = 'consumers_reached_old';
    protected $fillable = [
        'resposer',
        'quotgene',
        'quotedad',
        'quosegur',
        'ver_tv_senal_nacional',
        'ver_tv_cable',
        'ver_tv_internet',
        'escuchar_radio',
        'escuchar_radio_internet',
        'leer_revista_impresa',
        'leer_revista_digital',
        'leer_periodico_impreso',
        'leer_periodico_digital',
        'leer_periodico_email',
        'vallas_publicitarias',
        'centros_comerciales',
        'transitar_metrobuses',
        'ver_cine',
        'abrir_correos_companias',
        'entrar_sitios_web',
        'entrar_facebook',
        'entrar_twitter',
        'entrar_instagram',
        'entrar_youtube',
        'entrar_linkedin',
        'entrar_whatsapp',
        'escuchar_spotify',
        'ver_netflix',
        'utilizar_mailing_list',
        'videojuegos_celular',
        'utilizar_we_transfer',
        'utilizar_waze',
        'utilizar_uber',
        'utilizar_pedidos_ya',
        'utilizar_meet',
        'utilizar_zoom',
        'utilizar_airbnb',
        'entrar_google',
        'entrar_encuentra24',
    ];
}