<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Sesuai dengan skema tabel users pada skema SQL Banquet Operational System.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'full_name',
        'role',
        'class_name',
        'phone',
        'is_active',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
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
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Cek apakah pengguna memiliki hak akses Administrator (admin atau guru/pembina).
     */
    public function isAdmin(): bool
    {
        return in_array(strtolower($this->role ?? ''), ['admin', 'guru']);
    }

    /**
     * Cek apakah pengguna memiliki peran 'guru'.
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Cek apakah pengguna memiliki peran 'siswa'.
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Scope untuk menyaring pengguna yang statusnya aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
