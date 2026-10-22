<?php

namespace App\Models;

use Database\Factories\InheritedFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class MethodBase extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return InheritedFactory::new();
    }
}
