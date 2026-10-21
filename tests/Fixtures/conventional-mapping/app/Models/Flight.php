<?php

namespace App\Models;

use Database\Factories\AlternativeFlightFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(AlternativeFlightFactory::class)]
class Flight extends Model
{
    use HasFactory;
}
