<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Doar admin și service au acces la panoul Filament
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['admin', 'service']);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isService(): bool
    {
        return $this->role === 'service';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function service(): HasOne
    {
        return $this->hasOne(Service::class);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
