<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $department
 * @property string|null $manager_id
 * @property \Illuminate\Support\Carbon|null $admitted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Evidence[] $evidence
 * @property-read \App\Models\Person|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person[] $subordinates
 */
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
    ];

    protected $casts = [
        'admitted_at' => 'date',
    ];

    public function evidence()
    {
        return $this->hasMany(Evidence::class);
    }

    public function manager()
    {
        return $this->belongsTo(Person::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Person::class, 'manager_id');
    }
}
