<?php

namespace App\Models;

use Database\Factories\InheritedFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(InheritedFactory::class)]
abstract class AttributeBase extends Model
{
    use HasFactory;
}
