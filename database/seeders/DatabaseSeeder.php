<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\SystemReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@medisca.sch.id'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@medisca.sch.id'],
            [
                'name' => 'petugas',
                'password' => Hash::make('uks123'),
                'role' => 'guru_uks',
            ]
        );

        User::firstOrCreate(
            ['email' => 'sekolah@medisca.sch.id'],
            [
                'name' => 'sekolah',
                'password' => Hash::make('sekolah123'),
                'role' => 'pihak_sekolah',
            ]
        );

        $complaints = [
            ['nama_keluhan' => 'Demam timbang tinggi dan berat badan', 'deskripsi' => 'Pemeriksaan suhu dan pengukuran berat/tinggi badan'],
            ['nama_keluhan' => 'Demam, flu, batuk', 'deskripsi' => 'Gejala infeksi saluran pernapasan atas'],
            ['nama_keluhan' => 'Sakit perut', 'deskripsi' => 'Rasa nyeri atau kram pada perut'],
            ['nama_keluhan' => 'Sakit gigi', 'deskripsi' => 'Nyeri pada gigi atau gusi'],
            ['nama_keluhan' => 'Kecelakaan kecil / jatuh', 'deskripsi' => 'Luka lecet, memar akibat terjatuh atau terbentur'],
            ['nama_keluhan' => 'Alergi', 'deskripsi' => 'Reaksi gatal, bentol, atau bersin'],
            ['nama_keluhan' => 'Lambung, maag', 'deskripsi' => 'Nyeri ulu hati, mual, kembung'],
            ['nama_keluhan' => 'Diare', 'deskripsi' => 'BAB cair lebih dari tiga kali sehari'],
            ['nama_keluhan' => 'Kekurangan darah', 'deskripsi' => 'Anemia, pusing, lemas, pucat'],
        ];

        foreach ($complaints as $c) {
            ComplaintCategory::firstOrCreate(
                ['nama_keluhan' => $c['nama_keluhan']],
                ['deskripsi' => $c['deskripsi']]
            );
        }

        $medicines = [
            ['nama_obat' => 'Paracetamol', 'satuan' => 'strip', 'stok' => 24, 'status_stok' => 'aman'],
            ['nama_obat' => 'Tablet Tambah Darah', 'satuan' => 'strip', 'stok' => 40, 'status_stok' => 'aman'],
            ['nama_obat' => 'Entrostop', 'satuan' => 'strip', 'stok' => 8, 'status_stok' => 'menipis'],
            ['nama_obat' => 'Promag', 'satuan' => 'strip', 'stok' => 15, 'status_stok' => 'aman'],
            ['nama_obat' => 'Mefenamic Acid', 'satuan' => 'strip', 'stok' => 12, 'status_stok' => 'aman'],
            ['nama_obat' => 'Cetirizine', 'satuan' => 'strip', 'stok' => 10, 'status_stok' => 'aman'],
            ['nama_obat' => 'Antasida', 'satuan' => 'strip', 'stok' => 18, 'status_stok' => 'aman'],
            ['nama_obat' => 'Diatabs', 'satuan' => 'strip', 'stok' => 9, 'status_stok' => 'aman'],
            ['nama_obat' => 'Ibuprofen', 'satuan' => 'strip', 'stok' => 11, 'status_stok' => 'aman'],
            ['nama_obat' => 'Ambroxol', 'satuan' => 'strip', 'stok' => 7, 'status_stok' => 'menipis'],
            ['nama_obat' => 'Feminax', 'satuan' => 'strip', 'stok' => 14, 'status_stok' => 'aman'],
            ['nama_obat' => 'Geliga', 'satuan' => 'botol', 'stok' => 3, 'status_stok' => 'menipis'],
            ['nama_obat' => 'Hotin Cream', 'satuan' => 'tube', 'stok' => 4, 'status_stok' => 'menipis'],
            ['nama_obat' => 'Fresh Care', 'satuan' => 'botol', 'stok' => 5, 'status_stok' => 'aman'],
            ['nama_obat' => 'GPU', 'satuan' => 'botol', 'stok' => 4, 'status_stok' => 'menipis'],
            ['nama_obat' => 'Minyak Kayu Putih', 'satuan' => 'botol', 'stok' => 6, 'status_stok' => 'aman'],
            ['nama_obat' => 'Koyok Hansaplast', 'satuan' => 'sachet', 'stok' => 20, 'status_stok' => 'aman'],
            ['nama_obat' => 'Minyak Tawon', 'satuan' => 'botol', 'stok' => 3, 'status_stok' => 'menipis'],
            ['nama_obat' => 'Kasa Hydrophil', 'satuan' => 'pak', 'stok' => 9, 'status_stok' => 'aman'],
            ['nama_obat' => 'Bufacetine', 'satuan' => 'tube', 'stok' => 3, 'status_stok' => 'menipis'],
            ['nama_obat' => 'Betadine', 'satuan' => 'botol', 'stok' => 5, 'status_stok' => 'aman'],
            ['nama_obat' => 'Kapas', 'satuan' => 'pak', 'stok' => 6, 'status_stok' => 'aman'],
            ['nama_obat' => 'Hansaplast', 'satuan' => 'pcs', 'stok' => 60, 'status_stok' => 'aman'],
        ];

        foreach ($medicines as $m) {
            Medicine::firstOrCreate(
                ['nama_obat' => $m['nama_obat']],
                [
                    'satuan' => $m['satuan'],
                    'stok' => $m['stok'],
                    'status_stok' => $m['status_stok'],
                    'tgl_kedaluwarsa' => now()->addMonths(12)->toDateString(),
                ]
            );
        }

        $pct = Medicine::where('nama_obat', 'Paracetamol')->first();
        $btd = Medicine::where('nama_obat', 'Betadine')->first();
        $ttd = Medicine::where('nama_obat', 'Tablet Tambah Darah')->first();
        $ant = Medicine::where('nama_obat', 'Antasida')->first();
        $mkp = Medicine::where('nama_obat', 'Minyak Kayu Putih')->first();
        $amb = Medicine::where('nama_obat', 'Ambroxol')->first();

        $cDemam = ComplaintCategory::where('nama_keluhan', 'like', '%Demam%')->first();
        $cLuka = ComplaintCategory::where('nama_keluhan', 'like', '%Kecelakaan%')->first();
        $cAnemia = ComplaintCategory::where('nama_keluhan', 'like', '%Kekurangan darah%')->first();
        $cMaag = ComplaintCategory::where('nama_keluhan', 'like', '%Lambung%')->first();

        $records = [
            [
                'petugas_id' => $petugas->id,
                'complaint_category_id' => $cDemam?->id,
                'nama_pasien' => 'Aisyah Putri',
                'kelas_pasien' => 'X IPA 1',
                'keluhan' => 'Demam',
                'medicine_id' => $pct?->id,
                'jumlah_obat' => 1,
                'tindakan' => 'Kompres hangat dan pemberian paracetamol',
                'status' => 'sedang_dirawat',
            ],
            [
                'petugas_id' => $petugas->id,
                'complaint_category_id' => $cLuka?->id,
                'nama_pasien' => 'Rafi Pratama',
                'kelas_pasien' => 'XI IPS 2',
                'keluhan' => 'Kecelakaan kecil / jatuh',
                'medicine_id' => $btd?->id,
                'jumlah_obat' => 1,
                'tindakan' => 'Pembersihan luka dan perban',
                'status' => 'selesai',
            ],
            [
                'petugas_id' => $petugas->id,
                'complaint_category_id' => $cAnemia?->id,
                'nama_pasien' => 'Nadia Safitri',
                'kelas_pasien' => 'X IPS 1',
                'keluhan' => 'Kekurangan darah',
                'medicine_id' => $ttd?->id,
                'jumlah_obat' => 1,
                'tindakan' => 'Rujukan ke Puskesmas terdekat',
                'status' => 'dirujuk',
            ],
            [
                'petugas_id' => $petugas->id,
                'complaint_category_id' => $cMaag?->id,
                'nama_pasien' => 'Dimas Arya',
                'kelas_pasien' => 'XII IPA 2',
                'keluhan' => 'Lambung, maag',
                'medicine_id' => $ant?->id,
                'jumlah_obat' => 1,
                'tindakan' => 'Istirahat dan antasida',
                'status' => 'selesai',
            ],
            [
                'petugas_id' => $petugas->id,
                'complaint_category_id' => $cDemam?->id,
                'nama_pasien' => 'Salsa Amelia',
                'kelas_pasien' => 'X IPA 3',
                'keluhan' => 'Demam, flu, batuk',
                'medicine_id' => $mkp?->id,
                'jumlah_obat' => 1,
                'tindakan' => 'Balur minyak kayu putih dan istirahat',
                'status' => 'sedang_dirawat',
            ],
            [
                'petugas_id' => $petugas->id,
                'complaint_category_id' => $cDemam?->id,
                'nama_pasien' => 'Bima Saputra',
                'kelas_pasien' => 'XI IPA 1',
                'keluhan' => 'Demam, flu, batuk',
                'medicine_id' => $amb?->id,
                'jumlah_obat' => 1,
                'tindakan' => 'Minum air hangat dan ambroxol',
                'status' => 'selesai',
            ],
        ];

        foreach ($records as $rec) {
            MedicalRecord::firstOrCreate(
                [
                    'nama_pasien' => $rec['nama_pasien'],
                    'kelas_pasien' => $rec['kelas_pasien'],
                    'keluhan' => $rec['keluhan'],
                ],
                $rec
            );
        }

        $systemReports = [
            [
                'category' => 'mencurigakan',
                'category_label' => 'Mencurigakan',
                'title' => 'Percobaan login gagal 15x berturut-turut',
                'page' => '/login-petugas',
                'status' => 'pending',
                'status_label' => 'Perlu Ditinjau',
                'desc' => 'Ada pengguna atau robot asing dari IP luar negeri mencoba menebak kata sandi akun Guru UKS sebanyak 15 kali.',
                'solution' => 'Sistem otomatis mengunci akun sementara selama 15 menit. Anda dapat memblokir IP ini jika terus berlanjut.',
            ],
            [
                'category' => 'error',
                'category_label' => 'Error',
                'title' => 'Gagal kirim notifikasi WhatsApp ke wali murid',
                'page' => '/notifikasi-wali',
                'status' => 'pending',
                'status_label' => 'Perlu Ditinjau',
                'desc' => 'Laporan hasil skrining kesehatan siswa semester ini gagal terkirim ke WhatsApp wali murid karena nomor format tidak lengkap.',
                'solution' => 'Periksa kembali format nomor WhatsApp siswa (+62) pada data induk siswa.',
            ],
            [
                'category' => 'bug',
                'category_label' => 'Bug',
                'title' => 'Tombol \'Kirim Skrining\' tidak bisa diklik di HP',
                'page' => '/skrining-siswa',
                'status' => 'pending',
                'status_label' => 'Perlu Ditinjau',
                'desc' => 'Beberapa siswa melaporkan tombol \'Kirim\' macet saat menggunakan peramban Google Chrome di ponsel Android.',
                'solution' => 'Bug tampilan tombol tertutup elemen footer sudah dicatat untuk diperbaiki pengembang.',
            ],
            [
                'category' => 'mencurigakan',
                'category_label' => 'Mencurigakan',
                'title' => 'Percobaan input kode aneh (SQL Injection)',
                'page' => '/cari-artikel',
                'status' => 'done',
                'status_label' => 'Selesai (Diblokir)',
                'desc' => 'Seseorang memasukkan karakter simbol dan perintah database berbahaya pada kolom pencarian artikel kesehatan.',
                'solution' => 'Sistem keamanan Medisca berhasil memfilter karakter tersebut sehingga tidak membahayakan website.',
            ],
            [
                'category' => 'error',
                'category_label' => 'Error',
                'title' => 'Koneksi data Puskesmas lambat (Timeout 502)',
                'page' => '/rujukan-puskesmas',
                'status' => 'done',
                'status_label' => 'Selesai',
                'desc' => 'Server rujukan Puskesmas sempat tidak merespon saat jam sibuk pengiriman data pasien UKS.',
                'solution' => 'Koneksi server Puskesmas sudah pulih normal kembali.',
            ],
            [
                'category' => 'bug',
                'category_label' => 'Bug',
                'title' => 'Tulisan \'Jadwal Dokter Kecil\' terpotong di layar kecil',
                'page' => '/jadwal-dokter-kecil',
                'status' => 'done',
                'status_label' => 'Selesai',
                'desc' => 'Judul kegiatan dokter kecil terpotong di layar HP berukuran 5 inci.',
                'solution' => 'Ukuran font sudah disesuaikan agar pas di semua ukuran layar.',
            ],
        ];

        foreach ($systemReports as $sr) {
            SystemReport::firstOrCreate(
                ['title' => $sr['title']],
                $sr
            );
        }
    }
}
