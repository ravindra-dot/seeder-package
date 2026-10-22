<?php

namespace App\Models;

use Database\Factories\InheritedFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class ConfiguredBase extends Model
{
    use HasFactory;

    protected static string $factory = InheritedFactory::class;
}
