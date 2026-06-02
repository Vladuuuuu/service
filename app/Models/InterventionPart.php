<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterventionPart extends Model
{
    protected $fillable = [
        'intervention_id', 'name', 'quantity', 'unit_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }

    public function subtotal(): float
    {
        return (float) $this->quantity * (float) $this->unit_price;
    }
}
