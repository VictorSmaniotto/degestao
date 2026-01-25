<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Evidence extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'person_id',
        'context_id',
        'cycle_id',
        'type',
        'dimension',
        'intensity',
        'description',
        'occurred_at',
        'recorded_by',
    ];

    protected $casts = [
        'intensity' => 'integer',
        'occurred_at' => 'date',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function context()
    {
        return $this->belongsTo(Context::class);
    }

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }
}
