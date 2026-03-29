<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'intervention_id', 'number', 'total', 'issued_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'issued_at' => 'date',
    ];

    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
