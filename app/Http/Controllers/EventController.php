<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Institution;
use App\Models\RoomLayout;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event beserta filter, pencarian, dan statistik.
     */
    public function index(Request $request)
    {
        $query = Event::with(['institution', 'roomLayout', 'creator'])
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc');

        // Filter Pencarian (Nama Event atau Nama Instansi atau Keterangan)
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('institution', function ($instQ) use ($search) {
                      $instQ->where('institution_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Status
        if ($request->filled('status') && $request->query('status') !== 'all') {
            $status = strtolower($request->query('status'));
            if ($status === 'active' || $status === 'aktif') {
                $query->whereIn('event_status', ['upcoming', 'ongoing']);
            } elseif ($status === 'completed' || $status === 'selesai') {
                $query->where('event_status', 'completed');
            } elseif ($status === 'draft') {
                $query->where('event_status', 'canceled');
            } else {
                $query->where('event_status', $status);
            }
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->query('date'));
        }

        $events = $query->get();

        // Data Statistik Dinamis dari Database
        $allEvents = Event::all();
        $totalEvents = $allEvents->count();
        $activeEventsCount = $allEvents->whereIn('event_status', ['upcoming', 'ongoing'])->count();
        $completedEventsCount = $allEvents->where('event_status', 'completed')->count();
        $canceledEventsCount = $allEvents->where('event_status', 'canceled')->count();

        // Trend perubahan data
        $currentMonthCount = $allEvents->filter(function ($e) {
            return $e->event_date && Carbon::parse($e->event_date)->isCurrentMonth();
        })->count();
        $lastMonthCount = $allEvents->filter(function ($e) {
            return $e->event_date && Carbon::parse($e->event_date)->isLastMonth();
        })->count();
        $totalTrendDiff = $currentMonthCount - $lastMonthCount;

        // Tanggal unik yang memiliki event untuk penanda kalender
        $eventDates = $allEvents->pluck('event_date')->filter()->map(function ($d) {
            return Carbon::parse($d)->format('Y-m-d');
        })->unique()->values()->all();

        if ($request->wantsJson()) {
            return response()->json([
                'events' => $events,
                'total_events' => $totalEvents,
                'active_events' => $activeEventsCount,
                'event_dates' => $eventDates,
            ]);
        }

        return view('events.index', compact(
            'events',
            'totalEvents',
            'activeEventsCount',
            'completedEventsCount',
            'canceledEventsCount',
            'totalTrendDiff',
            'eventDates'
        ));
    }

    /**
     * Menampilkan formulir pembuatan event baru (BEO).
     */
    public function create()
    {
        $roomLayouts = RoomLayout::where('is_active', true)->orderBy('layout_name')->get();

        $rooms = [
            'Grand Ballroom',
            'Meeting Room A',
            'Meeting Room B',
            'VIP Dining Room',
            'Auditorium Lt. 3',
            'Sky Lounge',
            'Emerald Banquet Hall',
        ];

        return view('events.create', compact('roomLayouts', 'rooms'));
    }

    /**
     * Menyimpan data event baru ke dalam database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name'            => 'required|string|max:150',
            'event_type'            => 'nullable|string|max:100',
            'event_date'            => 'required|date',
            'start_time'            => 'required',
            'end_time'              => 'nullable',
            'room_name'             => 'nullable|string|max:100',
            'estimated_guest_count' => 'required|integer|min:1',
            'room_layout_id'        => 'nullable|exists:room_layouts,id',
            'notes'                 => 'nullable|string|max:2000',
        ], [
            'event_name.required'            => 'Nama instansi atau acara wajib diisi.',
            'event_date.required'            => 'Tanggal pelaksanaan wajib diisi.',
            'start_time.required'            => 'Waktu mulai acara wajib diisi.',
            'estimated_guest_count.required' => 'Target jumlah tamu (Pax) wajib diisi.',
            'estimated_guest_count.min'      => 'Target jumlah tamu minimal 1 orang.',
        ]);

        // Cari atau buat entri institusi agar relasi instansi tetap sinkron
        $institution = Institution::firstOrCreate(
            ['institution_name' => trim($validated['event_name'])],
            [
                'contact_person' => 'PIC ' . trim($validated['event_name']),
                'phone'          => null,
                'email'          => null,
            ]
        );

        // Format waktu mulai dan estimasi waktu selesai (+4 jam jika tidak diisi)
        $startTime = Carbon::parse($validated['start_time'])->format('H:i:s');
        $endTime = !empty($validated['end_time']) 
            ? Carbon::parse($validated['end_time'])->format('H:i:s') 
            : Carbon::parse($validated['start_time'])->addHours(4)->format('H:i:s');

        // Pengguna pembuat event
        $userId = auth()->id() ?? User::whereIn('role', ['guru', 'admin'])->first()?->id ?? 1;

        $event = Event::create([
            'event_name'            => $validated['event_name'],
            'event_type'            => $validated['event_type'] ?? null,
            'institution_id'        => $institution->id,
            'room_layout_id'        => $validated['room_layout_id'] ?? null,
            'room_name'             => $validated['room_name'] ?? 'Grand Ballroom',
            'event_date'            => $validated['event_date'],
            'start_time'            => $startTime,
            'end_time'              => $endTime,
            'estimated_guest_count' => $validated['estimated_guest_count'],
            'event_status'          => 'upcoming',
            'notes'                 => $validated['notes'] ?? null,
            'created_by'            => $userId,
        ]);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event BEO "' . $event->event_name . '" (' . $event->event_code . ') berhasil disimpan!');
    }

    /**
     * Menampilkan halaman detail BEO/Event.
     */
    public function show(Event $event)
    {
        $event->load(['institution', 'roomLayout', 'creator', 'canceledBy']);

        return view('events.show', compact('event'));
    }

    /**
     * Membatalkan event BEO (Khusus Admin / Guru).
     */
    public function cancel(Request $request, Event $event)
    {
        $request->validate([
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        $reason = $request->filled('cancel_reason')
            ? trim($request->cancel_reason)
            : 'Dibatalkan oleh ' . (auth()->user()->full_name ?? 'Administrator');

        $event->update([
            'event_status'  => 'canceled',
            'cancel_reason' => $reason,
            'canceled_by'   => auth()->id(),
            'canceled_at'   => now(),
        ]);

        return redirect()
            ->route('events.show', $event->id)
            ->with('success', 'Event BEO "' . $event->event_name . '" (' . $event->event_code . ') berhasil dibatalkan.');
    }
}

