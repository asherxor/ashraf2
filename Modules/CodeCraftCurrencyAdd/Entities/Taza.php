<?php

namespace Modules\CodeCraftCurrencyAdd\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Taza extends Model
{
    protected $table = 'tazas';
    protected $fillable = [
        'business_id',
        'currency_id',
        'value',
        'alias',
        // Agrega otros campos si es necesario
    ];
}
