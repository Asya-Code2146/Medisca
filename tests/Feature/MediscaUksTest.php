<?php

namespace Tests\Feature;

use App\Models\ComplaintCategory;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MediscaUksTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $guruUks;
    protected User $pihakSekolah;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'admin',
            'email' => 'admin@medisca.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $this->guruUks = User::create([
            'name' => 'petugas',
            'email' => 'petugas@medisca.sch.id',
            'password' => Hash::make('uks123'),
            'role' => 'guru_uks',
        ]);

        $this->pihakSekolah = User::create([
            'name' => 'sekolah',
            'email' => 'sekolah@medisca.sch.id',
            'password' => Hash::make('sekolah123'),
            'role' => 'pihak_sekolah',
        ]);
    }

    public function test_landing_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Medisca');
    }

    public function test_user_can_login_with_username_and_password(): void
    {
        $response = $this->postJson('/login', [
            'login' => 'petugas',
            'password' => 'uks123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'user' => [
                'role' => 'guru_uks',
            ],
        ]);
        $this->assertAuthenticatedAs($this->guruUks);
    }

    public function test_pihak_sekolah_can_read_medicines(): void
    {
        Medicine::create([
            'nama_obat' => 'Paracetamol',
            'stok' => 20,
            'satuan' => 'strip',
            'status_stok' => 'aman',
        ]);

        $response = $this->actingAs($this->pihakSekolah)->getJson('/medicines');

        $response->assertStatus(200);
        $response->assertJsonFragment(['nama_obat' => 'Paracetamol']);
    }

    public function test_pihak_sekolah_cannot_modify_medicines_server_side_protection(): void
    {
        $medicine = Medicine::create([
            'nama_obat' => 'Paracetamol',
            'stok' => 20,
            'satuan' => 'strip',
            'status_stok' => 'aman',
        ]);

        $response = $this->actingAs($this->pihakSekolah)->patchJson("/medicines/{$medicine->id}/stock", [
            'action' => 'increase',
            'amount' => 5,
        ]);

        $response->assertStatus(403);

        $createResponse = $this->actingAs($this->pihakSekolah)->postJson('/medicines', [
            'nama_obat' => 'Obat Palsu',
            'stok' => 10,
            'satuan' => 'botol',
        ]);

        $createResponse->assertStatus(403);
    }

    public function test_guru_uks_can_record_medical_record_and_auto_decrement_stock(): void
    {
        $med = Medicine::create([
            'nama_obat' => 'Ambroxol',
            'stok' => 10,
            'satuan' => 'strip',
            'status_stok' => 'aman',
        ]);

        $cat = ComplaintCategory::create([
            'nama_keluhan' => 'Batuk Berdahak',
        ]);

        $response = $this->actingAs($this->guruUks)->postJson('/medical-records', [
            'nama_pasien' => 'Siti Nurhaliza',
            'kelas_pasien' => 'X IPA 2',
            'complaint_category_id' => $cat->id,
            'keluhan' => 'Batuk Berdahak',
            'medicine_id' => $med->id,
            'jumlah_obat' => 2,
            'tindakan' => 'Minum air hangat dan obat batuk',
            'status' => 'selesai',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('medical_records', [
            'nama_pasien' => 'Siti Nurhaliza',
            'petugas_id' => $this->guruUks->id,
        ]);

        $this->assertEquals(8, $med->fresh()->stok);
    }

    public function test_admin_has_exclusive_access_to_admin_panel(): void
    {
        $responseUser = $this->actingAs($this->guruUks)->get('/admin');
        $responseUser->assertStatus(403);

        $responseSchool = $this->actingAs($this->pihakSekolah)->get('/admin');
        $responseSchool->assertStatus(403);

        $responseAdmin = $this->actingAs($this->admin)->get('/admin');
        $responseAdmin->assertStatus(200);
    }

    public function test_google_redirect_route_is_configured(): void
    {
        $response = $this->get('/auth/google');
        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }
}
