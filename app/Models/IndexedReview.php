<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndexedReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'QuotGene',
        'QuotEdad',
        'QuoSegur',
        'MediaType',
        'Value',
        'Adjusted_value',
    ];
}
