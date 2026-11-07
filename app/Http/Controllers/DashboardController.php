<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Medicine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalRecords = MedicalRecord::count();
        $sedangDirawat = MedicalRecord::where('status', 'sedang_dirawat')->count();
        $dirujuk = MedicalRecord::where('status', 'dirujuk')->count();
        $selesai = MedicalRecord::where('status', 'selesai')->count();

        $criticalMeds = Medicine::where('status_stok', 'kritis')->get();
        $lowMeds = Medicine::where('status_stok', 'menipis')->get();
        $needRestock = Medicine::whereIn('status_stok', ['kritis', 'menipis'])->get();

        $topComplaints = MedicalRecord::selectRaw('keluhan, count(*) as total')
            ->groupBy('keluhan')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'summary' => [
                'total_pasien' => $totalRecords,
                'sedang_dirawat' => $sedangDirawat,
                'dirujuk' => $dirujuk,
                'selesai' => $selesai,
                'stok_kritis' => $criticalMeds->count(),
                'stok_menipis' => $lowMeds->count(),
            ],
            'top_complaints' => $topComplaints,
            'need_restock' => $needRestock,
        ]);
    }

    public function criticalAlerts(): JsonResponse
    {
        $criticalMeds = Medicine::where('status_stok', 'kritis')
            ->orWhere('status_stok', 'menipis')
            ->orderBy('stok')
            ->get();

        $referredPatients = MedicalRecord::where('status', 'dirujuk')
            ->latest()
            ->limit(15)
            ->get();

        return response()->json([
            'success' => true,
            'alerts' => [
                'medicines_critical' => $criticalMeds,
                'patients_referred' => $referredPatients,
            ],
        ]);
    }

    public function exportReport(Request $request): StreamedResponse
    {
        $records = MedicalRecord::with(['petugas', 'medicine'])
            ->latest()
            ->get();

        $filename = 'laporan-medisca-uks-'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['ID', 'Tanggal', 'Nama Siswa/Pasien', 'Kelas', 'Keluhan', 'Obat', 'Jumlah', 'Tindakan', 'Status', 'Petugas']);

            foreach ($records as $r) {
                fputcsv($handle, [
                    $r->id,
                    $r->created_at ? $r->created_at->format('Y-m-d H:i') : '-',
                    $r->nama_pasien,
                    $r->kelas_pasien ?? '-',
                    $r->keluhan,
                    $r->medicine ? $r->medicine->nama_obat : 'Tanpa obat',
                    $r->jumlah_obat ?? 0,
                    $r->tindakan ?? '-',
                    $r->status,
                    $r->petugas ? $r->petugas->name : '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
