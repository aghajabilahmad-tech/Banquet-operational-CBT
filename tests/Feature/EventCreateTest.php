<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\RoomLayout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCreateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Tamu / Guest yang belum login diarahkan ke login.
     */
    public function test_guest_is_redirected_to_login_when_accessing_create_event(): void
    {
        $response = $this->get(route('events.create'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Pengguna dengan role siswa (bukan admin) mendapat 403 Forbidden.
     */
    public function test_siswa_cannot_access_create_event_page(): void
    {
        $siswa = User::firstOrCreate(
            ['username' => 'test_siswa_01'],
            [
                'full_name' => 'Siswa Test',
                'password'  => bcrypt('password123'),
                'role'      => 'siswa',
            ]
        );

        $response = $this->actingAs($siswa)->get(route('events.create'));
        $response->assertStatus(403);
    }

    /**
     * Pengguna dengan role guru atau admin dapat mengakses halaman create event.
     */
    public function test_admin_or_guru_can_access_create_event_page(): void
    {
        $guru = User::firstOrCreate(
            ['username' => 'test_guru_01'],
            [
                'full_name' => 'Guru Test',
                'password'  => bcrypt('password123'),
                'role'      => 'guru',
            ]
        );

        $response = $this->actingAs($guru)->get(route('events.create'));
        $response->assertStatus(200);
        $response->assertSee('Buat BEO');
        $response->assertSee('Informasi Dasar Acara');
        $response->assertSee('Detail Lokasi & Layout', false);
        $response->assertSee('Simpan Event (Draft)');
    }

    /**
     * Admin/Guru berhasil menyimpan event baru dan di-redirect ke Daftar Event dengan flash message.
     */
    public function test_admin_can_store_event_and_redirected_to_index_with_flash_message(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'test_admin_01'],
            [
                'full_name' => 'Admin Test',
                'password'  => bcrypt('password123'),
                'role'      => 'admin',
            ]
        );

        $layout = RoomLayout::first() ?? RoomLayout::create([
            'layout_name' => 'Round Table Test',
            'max_capacity' => 100,
        ]);

        $postData = [
            'event_name'            => 'Universitas Indonesia Tech Summit',
            'event_type'            => 'Konferensi Nasional',
            'event_date'            => '2026-11-15',
            'start_time'            => '09:00',
            'room_name'             => 'Grand Ballroom',
            'estimated_guest_count' => 150,
            'room_layout_id'        => $layout->id,
            'notes'                 => 'Kebutuhan proyektor ganda dan mic wireless.',
        ];

        $response = $this->actingAs($admin)->post(route('events.store'), $postData);

        // Harus redirect ke events.index
        $response->assertRedirect(route('events.index'));
        $response->assertSessionHas('success');

        // Pastikan tersimpan di database
        $this->assertDatabaseHas('events', [
            'event_name'            => 'Universitas Indonesia Tech Summit',
            'event_type'            => 'Konferensi Nasional',
            'estimated_guest_count' => 150,
            'room_name'             => 'Grand Ballroom',
        ]);

        // Cek bahwa event_code terisi otomatis (e.g. BEO-YYYY-XXX)
        $event = Event::where('event_name', 'Universitas Indonesia Tech Summit')->first();
        $this->assertNotNull($event);
        $this->assertNotNull($event->event_code);
        $this->assertStringStartsWith('BEO-', $event->event_code);
    }
}
