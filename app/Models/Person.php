<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'email',
        'role',
        'department',
        'manager_id',
        'admitted_at',
        'phone',
        'bio',
        'avatar_path',
    ];

    protected $casts = [
        'admitted_at' => 'date',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Person::class, 'manager_id');
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
