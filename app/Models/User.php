<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable, LogsActivity;

    protected $fillable = ['name', 'username', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['admin', 'petugas', 'peminjam']);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}