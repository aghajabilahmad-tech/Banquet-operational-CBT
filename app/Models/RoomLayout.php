<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomLayout extends Model
{
    use HasFactory;

    protected $table = 'room_layouts';

    public $timestamps = false;

    protected $fillable = [
        'layout_name',
        'description',
        'max_capacity',
        'image_path',
        'is_active',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'max_capacity' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke Event.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'room_layout_id');
    }
}
