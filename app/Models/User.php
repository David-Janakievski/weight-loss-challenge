<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'is_admin',
        'must_change_password',
        'onboarding_completed',
        'starting_weight',
        'starting_photo',
        'goal_note',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'must_change_password' => 'boolean',
        'onboarding_completed' => 'boolean',
        'starting_weight' => 'decimal:2',
    ];

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class)->orderBy('week_number');
    }

    public function latestCheckin()
    {
        return $this->checkins()->latest('week_number')->first();
    }

    public function currentWeight(): float
    {
        $latest = $this->latestCheckin();
        return $latest ? (float) $latest->weight : (float) $this->starting_weight;
    }

    public function weightLost(): float
    {
        if (!$this->starting_weight) {
            return 0;
        }
        return round((float) $this->starting_weight - $this->currentWeight(), 2);
    }

    public function percentLost(): float
    {
        if (!$this->starting_weight || (float) $this->starting_weight <= 0) {
            return 0;
        }
        return round(($this->weightLost() / (float) $this->starting_weight) * 100, 2);
    }

    public function hasCheckedInForWeek(int $weekNumber): bool
    {
        return $this->checkins()->where('week_number', $weekNumber)->exists();
    }
}
