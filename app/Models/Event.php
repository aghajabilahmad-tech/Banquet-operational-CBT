<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'event_code',
        'event_name',
        'event_type',
        'institution_id',
        'room_layout_id',
        'room_name',
        'event_date',
        'start_time',
        'end_time',
        'estimated_guest_count',
        'actual_guest_count',
        'event_status',
        'cancel_reason',
        'notes',
        'created_by',
        'started_by',
        'started_at',
        'completed_at',
        'canceled_by',
        'canceled_at',
    ];

    /**
     * Booted method untuk model Event.
     */
    protected static function booted(): void
    {
        static::creating(function ($event) {
            if (empty($event->event_code)) {
                $year = date('Y');
                $count = static::count() + 1;
                $code = sprintf('BEO-%s-%03d', $year, $count);
                while (static::where('event_code', $code)->exists()) {
                    $count++;
                    $code = sprintf('BEO-%s-%03d', $year, $count);
                }
                $event->event_code = $code;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'estimated_guest_count' => 'integer',
            'actual_guest_count' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke Institusi / Klien.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    /**
     * Relasi ke Layout Ruangan.
     */
    public function roomLayout(): BelongsTo
    {
        return $this->belongsTo(RoomLayout::class, 'room_layout_id');
    }

    /**
     * Relasi ke User pembuat event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User pembatal event.
     */
    public function canceledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }

    /**
     * Label status yang diformat.
     */
    public function getStatusLabelAttribute(): string
    {
        return match (strtolower($this->event_status)) {
            'ongoing' => 'Ongoing',
            'upcoming' => 'Upcoming',
            'completed' => 'Completed',
            'canceled' => 'Canceled',
            default => ucfirst($this->event_status),
        };
    }

    /**
     * Warna aksen dot/glow status.
     */
    public function getStatusColorAttribute(): string
    {
        return match (strtolower($this->event_status)) {
            'ongoing' => '#34D399',
            'upcoming' => '#60A5FA',
            'completed' => '#94A3B8',
            'canceled' => '#F87171',
            default => '#3B82F6',
        };
    }

    /**
     * Warna border badge.
     */
    public function getStatusBorderAttribute(): string
    {
        return match (strtolower($this->event_status)) {
            'ongoing' => '#A7F3D0',
            'upcoming' => '#BFDBFE',
            'completed' => '#E2E8F0',
            'canceled' => '#FECACA',
            default => '#BFDBFE',
        };
    }

    /**
     * Background badge.
     */
    public function getStatusBgAttribute(): string
    {
        return match (strtolower($this->event_status)) {
            'ongoing' => '#ECFDF5',
            'upcoming' => '#EFF6FF',
            'completed' => '#F8FAFC',
            'canceled' => '#FEF2F2',
            default => '#EFF6FF',
        };
    }

    /**
     * Teks color badge.
     */
    public function getStatusTextColorAttribute(): string
    {
        return match (strtolower($this->event_status)) {
            'ongoing' => '#059669',
            'upcoming' => '#2563EB',
            'completed' => '#475569',
            'canceled' => '#DC2626',
            default => '#1E40AF',
        };
    }

    /**
     * Tanggal terformat (misal: 25 Sep 2026).
     */
    public function getFormattedDateAttribute(): string
    {
        if (!$this->event_date) {
            return '-';
        }

        return Carbon::parse($this->event_date)->translatedFormat('d M Y');
    }

    /**
     * Format judul untuk hero display (kata pertama diapit strong jika multi-kata).
     */
    public function getHeroTitleFormattedAttribute(): string
    {
        $words = explode(' ', $this->event_name, 2);
        if (count($words) === 2) {
            return '<strong>' . e($words[0]) . '</strong><br>' . e($words[1]);
        }
        return '<strong>' . e($this->event_name) . '</strong>';
    }

    /**
     * URL Gambar Event / Hero.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->roomLayout && !empty($this->roomLayout->image_path)) {
            return $this->roomLayout->image_path;
        }

        // Default event images berdasarkan status / index
        $images = [
            'upcoming' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
            'ongoing' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
            'completed' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80',
            'canceled' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80',
        ];

        return $images[strtolower($this->event_status)] ?? 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80';
    }
}
