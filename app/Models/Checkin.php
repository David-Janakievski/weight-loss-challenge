<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'week_number',
        'checkin_date',
        'weight',
        'photo',
        'note',
        'admin_override',
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'weight' => 'decimal:2',
        'admin_override' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
