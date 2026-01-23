<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Person extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'email',
        'role',
        'admitted_at',
    ];

    protected $casts = [
        'admitted_at' => 'date',
    ];

    public function evidence()
    {
        return $this->hasMany(Evidence::class);
    }
}
