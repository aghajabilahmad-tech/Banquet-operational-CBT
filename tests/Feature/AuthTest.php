<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_login_and_register_buttons_for_guest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Sistem');
        $response->assertSee('Registrasi Baru');
    }

    public function test_home_page_hides_login_and_register_buttons_when_authenticated(): void
    {
        $user = User::create([
            'username'   => '212210045',
            'full_name'  => 'Ahmad Fauzi',
            'role'       => 'siswa',
            'password'   => Hash::make('password123'),
            'is_active'  => true,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Masuk ke Sistem');
        $response->assertDontSee('Registrasi Baru');
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Tata Kelola Event yang Presisi');
        $response->assertSee('Selamat Datang Kembali');
        $response->assertSee('Email atau NIS / NIP');
    }

    public function test_registration_page_can_be_rendered_with_simplified_form(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Nama Lengkap');
        // Tidak lagi memuat NIS/NIP/Username, Telepon, Kelas, atau Role
        $response->assertDontSee('NIS / NIP / Username');
        $response->assertDontSee('Peran Akun (Role)');
        $response->assertDontSee('Kelas / Jurusan');
        $response->assertDontSee('Nomor Telepon / WhatsApp');
    }

    public function test_guru_can_login_and_redirected_to_home(): void
    {
        $guru = User::create([
            'username'   => '198501012010011001',
            'full_name'  => 'Guru Pembina',
            'role'       => 'guru',
            'password'   => Hash::make('password123'),
            'is_active'  => true,
        ]);

        $response = $this->post('/login', [
            'login'    => '198501012010011001',
            'password' => 'password123',
            'remember' => '1',
        ]);

        $this->assertAuthenticatedAs($guru);
        $response->assertRedirect('/');
    }

    public function test_siswa_can_login_and_redirected_to_home(): void
    {
        $siswa = User::create([
            'username'   => '212210045',
            'full_name'  => 'Siswa Operasional',
            'role'       => 'siswa',
            'password'   => Hash::make('password123'),
            'is_active'  => true,
        ]);

        $response = $this->post('/login', [
            'login'    => '212210045',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($siswa);
        $response->assertRedirect('/');
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::create([
            'username'   => 'inactive_user',
            'full_name'  => 'User Nonaktif',
            'role'       => 'siswa',
            'password'   => Hash::make('password123'),
            'is_active'  => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'login'    => 'inactive_user',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['login']);
    }

    public function test_user_can_register_new_account_with_only_name_and_password(): void
    {
        $response = $this->post('/register', [
            'full_name'             => 'Rifki Pratama',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'full_name'  => 'Rifki Pratama',
            'role'       => 'siswa',
            'is_active'  => true,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'username'   => 'user_logout',
            'full_name'  => 'User Logout',
            'role'       => 'siswa',
            'password'   => Hash::make('password123'),
            'is_active'  => true,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
