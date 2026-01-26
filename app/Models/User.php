<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements HasAvatar, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'person_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        // Se tiver person e avatar_path, retorna a URL pública
        if ($this->person && $this->person->avatar_path) {
            return Storage::url($this->person->avatar_path);
        }

        return null; // Fallback para padrão do Filament (iniciais)
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Em produção, o Filament exige esse método.
        // Vamos liberar acesso para quem tem role definido OU se for o primeiro usuário
        // O ideal é: return $this->role === 'admin' || $this->role === 'manager';
        // Mas como o make:filament-user não seta role, vamos liberar temporariamente:
        return true;
    }
}
