<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Intervention extends Model
{
    protected $fillable = [
        'car_id', 'service_id', 'status', 'description', 'type',
        'estimated_hours', 'final_cost', 'km_at_intervention',
        'scheduled_at', 'completed_at',
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:1',
        'final_cost' => 'decimal:2',
        'km_at_intervention' => 'integer',
        'scheduled_at' => 'date',
        'completed_at' => 'date',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
