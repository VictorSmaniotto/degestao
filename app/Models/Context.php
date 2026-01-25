<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Context extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'description',
        'complexity_level',
        'is_structured',
    ];

    protected $casts = [
        'complexity_level' => 'integer',
        'is_structured' => 'boolean',
    ];
}
