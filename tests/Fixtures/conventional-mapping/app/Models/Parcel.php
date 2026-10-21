<?php

namespace App\Models;

use Database\Factories\AlternativeParcelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    protected static function newFactory(): AlternativeParcelFactory
    {
        return AlternativeParcelFactory::new();
    }
}
