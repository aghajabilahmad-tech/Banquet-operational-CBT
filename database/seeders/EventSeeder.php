<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Institution;
use App\Models\RoomLayout;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::first() ?? User::create([
            'username'   => '198501012010011001',
            'full_name'  => 'Dra. Nur Indah, M.Pd.',
            'password'   => bcrypt('password123'),
            'role'       => 'guru',
            'class_name' => 'Koordinator Lab Banquet',
            'phone'      => '081234567890',
            'is_active'  => true,
        ]);

        // 1. Data Institusi
        $instDinas = Institution::firstOrCreate(
            ['institution_name' => 'Dinas Pendidikan Provinsi'],
            [
                'contact_person' => 'Bpk. Bambang Sutrisno',
                'phone' => '081298765432',
                'email' => 'sekretariat@disdik.prov.go.id',
                'address' => 'Jl. Jenderal Sudirman No. 45',
            ]
        );

        $instYayasan = Institution::firstOrCreate(
            ['institution_name' => 'Yayasan Taruna Bakti'],
            [
                'contact_person' => 'Ibu Ratna Dewi',
                'phone' => '081345678901',
                'email' => 'admin@tarunabakti.org',
                'address' => 'Jl. Diponegoro No. 12',
            ]
        );

        $instSmk = Institution::firstOrCreate(
            ['institution_name' => 'SMK Negeri 1 Perhotelan'],
            [
                'contact_person' => 'Dra. Nur Indah',
                'phone' => '081234567890',
                'email' => 'info@smkn1perhotelan.sch.id',
                'address' => 'Jl. Pendidikan No. 8',
            ]
        );

        $instDigital = Institution::firstOrCreate(
            ['institution_name' => 'Digital Creative Summit'],
            [
                'contact_person' => 'Kevin Sanjaya',
                'phone' => '085712349876',
                'email' => 'kevin@digitalsummit.id',
                'address' => 'Gedung Cyber 2 Lantai 15, Jakarta',
            ]
        );

        $instHotel = Institution::firstOrCreate(
            ['institution_name' => 'Asosiasi General Manager Hotel'],
            [
                'contact_person' => 'David Pratama',
                'phone' => '087812345678',
                'email' => 'contact@agmh-indonesia.org',
                'address' => 'Jl. Gatot Subroto Kav. 22',
            ]
        );

        // 2. Data Layout Ruangan
        $layoutRound = RoomLayout::firstOrCreate(
            ['layout_name' => 'Round Table'],
            [
                'description' => 'Meja bundar elegan dengan 8-10 kursi per meja, ideal untuk banquet dan gala dinner.',
                'max_capacity' => 150,
                'image_path' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ]
        );

        $layoutTheater = RoomLayout::firstOrCreate(
            ['layout_name' => 'Theater'],
            [
                'description' => 'Barisan kursi menghadap ke panggung/podium tanpa meja, untuk kapasitas audiens maksimal.',
                'max_capacity' => 250,
                'image_path' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ]
        );

        $layoutStanding = RoomLayout::firstOrCreate(
            ['layout_name' => 'Standing Party'],
            [
                'description' => 'Konsep pesta berdiri dengan cocktail tables dan stall makanan fleksibel.',
                'max_capacity' => 400,
                'image_path' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ]
        );

        $layoutClassroom = RoomLayout::firstOrCreate(
            ['layout_name' => 'Classroom'],
            [
                'description' => 'Meja dan kursi berbaris rapi dengan ruang untuk laptop/catatan materi workshop.',
                'max_capacity' => 120,
                'image_path' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ]
        );

        // 3. Data Event Dummy
        $events = [
            [
                'event_code' => 'BEO-2026-001',
                'event_name' => 'Dinas Pendidikan',
                'institution_id' => $instDinas->id,
                'room_layout_id' => $layoutRound->id,
                'event_date' => '2026-09-25',
                'start_time' => '08:30:00',
                'end_time' => '14:00:00',
                'estimated_guest_count' => 50,
                'actual_guest_count' => null,
                'event_status' => 'upcoming',
                'notes' => 'Persiapan ruangan dengan formasi round table. Kebutuhan proyektor dan sistem audio standar untuk presentasi panelis.',
                'created_by' => $adminUser->id,
            ],
            [
                'event_code' => 'BEO-2026-002',
                'event_name' => 'Rapat Yayasan',
                'institution_id' => $instYayasan->id,
                'room_layout_id' => $layoutTheater->id,
                'event_date' => '2026-09-28',
                'start_time' => '09:00:00',
                'end_time' => '16:00:00',
                'estimated_guest_count' => 120,
                'actual_guest_count' => 118,
                'event_status' => 'ongoing',
                'notes' => 'Sidang paripurna tahunan yayasan. Setup ruangan theater style dilengkapi dengan meja podium dan kursi VIP di barisan depan.',
                'created_by' => $adminUser->id,
            ],
            [
                'event_code' => 'BEO-2026-003',
                'event_name' => 'Ulang Tahun Sekolah',
                'institution_id' => $instSmk->id,
                'room_layout_id' => $layoutStanding->id,
                'event_date' => '2026-10-02',
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'estimated_guest_count' => 300,
                'actual_guest_count' => 312,
                'event_status' => 'completed',
                'notes' => 'Perayaan anniversary ke-50. Menggunakan layout standing party dengan beberapa stall makanan di sudut ruangan serta panggung hiburan.',
                'created_by' => $adminUser->id,
            ],
            [
                'event_code' => 'BEO-2026-004',
                'event_name' => 'Seminar Digital',
                'institution_id' => $instDigital->id,
                'room_layout_id' => $layoutClassroom->id,
                'event_date' => '2026-10-10',
                'start_time' => '09:00:00',
                'end_time' => '15:00:00',
                'estimated_guest_count' => 100,
                'actual_guest_count' => null,
                'event_status' => 'canceled',
                'cancel_reason' => 'Dibatalkan oleh pihak penyelenggara dikarenakan kendala teknis internal perusahaan.',
                'notes' => 'Workshop mengenai perkembangan AI dalam bisnis. Dibatalkan oleh pihak penyelenggara dikarenakan kendala teknis internal perusahaan.',
                'created_by' => $adminUser->id,
            ],
            [
                'event_code' => 'BEO-2026-005',
                'event_name' => 'Gala Dinner GM Hotel',
                'institution_id' => $instHotel->id,
                'room_layout_id' => $layoutRound->id,
                'event_date' => '2026-09-29',
                'start_time' => '19:00:00',
                'end_time' => '23:00:00',
                'estimated_guest_count' => 80,
                'actual_guest_count' => null,
                'event_status' => 'upcoming',
                'notes' => 'Malam penganugerahan dan silaturahmi pimpinan industri perhotelan tingkat nasional.',
                'created_by' => $adminUser->id,
            ],
            [
                'event_code' => 'BEO-2026-006',
                'event_name' => 'Workshop Table Manner',
                'institution_id' => $instSmk->id,
                'room_layout_id' => $layoutRound->id,
                'event_date' => '2026-09-23',
                'start_time' => '10:00:00',
                'end_time' => '13:00:00',
                'estimated_guest_count' => 45,
                'actual_guest_count' => 45,
                'event_status' => 'ongoing',
                'notes' => 'Pelatihan table manner dan etika jamuan resmi berskala internasional untuk siswa tingkat akhir.',
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($events as $eventData) {
            Event::updateOrCreate(
                ['event_code' => $eventData['event_code']],
                $eventData
            );
        }
    }
}
