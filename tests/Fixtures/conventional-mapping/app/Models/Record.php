<?php

namespace App\Models;

use Database\Factories\AlternativeRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected static $factory = AlternativeRecordFactory::class;
}
