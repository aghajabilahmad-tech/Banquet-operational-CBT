<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Institution;
use App\Models\RoomLayout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCancelTest extends TestCase
{
    use RefreshDatabase;

    private function createSampleEvent(string $status = 'upcoming'): Event
    {
        $creator = User::create([
            'username'  => 'creator_test',
            'full_name' => 'Creator Test',
            'role'      => 'guru',
            'password'  => bcrypt('password123'),
        ]);

        $inst = Institution::create([
            'institution_name' => 'Instansi Contoh',
        ]);

        $layout = RoomLayout::create([
            'layout_name' => 'Round Table',
        ]);

        return Event::create([
            'event_code'            => 'BEO-2026-999',
            'event_name'            => 'Acara Uji Coba Pembatalan',
            'institution_id'        => $inst->id,
            'room_layout_id'        => $layout->id,
            'event_date'            => '2026-10-10',
            'start_time'            => '10:00:00',
            'end_time'              => '14:00:00',
            'estimated_guest_count' => 80,
            'event_status'          => $status,
            'created_by'            => $creator->id,
        ]);
    }

    /**
     * Guest tidak bisa membatalkan event dan diarahkan ke login.
     */
    public function test_guest_cannot_cancel_event(): void
    {
        $event = $this->createSampleEvent();

        $response = $this->post(route('events.cancel', $event->id), [
            'cancel_reason' => 'Batal oleh guest',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertEquals('upcoming', $event->fresh()->event_status);
    }

    /**
     * Siswa (non-admin) tidak bisa membatalkan event (403 Forbidden) dan tidak melihat tombol batalkan.
     */
    public function test_siswa_cannot_cancel_event_and_cannot_see_button(): void
    {
        $event = $this->createSampleEvent();

        $siswa = User::create([
            'username'  => 'siswa_user',
            'full_name' => 'Siswa Pelajar',
            'role'      => 'siswa',
            'password'  => bcrypt('password123'),
        ]);

        // 1. Cek halaman detail tidak memuat tombol Batalkan Event
        $showResponse = $this->actingAs($siswa)->get(route('events.show', $event->id));
        $showResponse->assertStatus(200);
        $showResponse->assertDontSee('Batalkan Event');

        // 2. Request POST pembatalan ditolak 403
        $cancelResponse = $this->actingAs($siswa)->post(route('events.cancel', $event->id), [
            'cancel_reason' => 'Mencoba batalkan',
        ]);
        $cancelResponse->assertStatus(403);
        $this->assertEquals('upcoming', $event->fresh()->event_status);
    }

    /**
     * Admin/Guru melihat tombol Batalkan Event dan berhasil membatalkan event.
     */
    public function test_admin_can_see_button_and_cancel_event(): void
    {
        $event = $this->createSampleEvent();

        $admin = User::create([
            'username'  => 'admin_user',
            'full_name' => 'Bapak Admin',
            'role'      => 'admin',
            'password'  => bcrypt('password123'),
        ]);

        // 1. Cek halaman detail memuat tombol Batalkan Event
        $showResponse = $this->actingAs($admin)->get(route('events.show', $event->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Batalkan Event');

        // 2. Kirim aksi pembatalan
        $cancelResponse = $this->actingAs($admin)->post(route('events.cancel', $event->id), [
            'cancel_reason' => 'Perubahan agenda internal pihak penyelenggara',
        ]);

        $cancelResponse->assertRedirect(route('events.show', $event->id));
        $cancelResponse->assertSessionHas('success');

        // 3. Verifikasi data di database
        $freshEvent = $event->fresh();
        $this->assertEquals('canceled', $freshEvent->event_status);
        $this->assertEquals('Perubahan agenda internal pihak penyelenggara', $freshEvent->cancel_reason);
        $this->assertEquals($admin->id, $freshEvent->canceled_by);
        $this->assertNotNull($freshEvent->canceled_at);

        // 4. Buka kembali halaman detail setelah dibatalkan
        $afterResponse = $this->actingAs($admin)->get(route('events.show', $event->id));
        $afterResponse->assertStatus(200);
        $afterResponse->assertSee('Acara BEO Ini Telah Dibatalkan');
        $afterResponse->assertSee('Perubahan agenda internal pihak penyelenggara');
        // Tombol Batalkan Event tidak lagi ditampilkan karena sudah canceled
        $afterResponse->assertDontSee('openCancelModal');
    }
}
