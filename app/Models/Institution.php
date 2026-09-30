<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    use HasFactory;

    protected $table = 'institutions';

    protected $fillable = [
        'institution_name',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    /**
     * Relasi ke Event.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'institution_id');
    }
}
