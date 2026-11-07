<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = MedicalRecord::with(['petugas:id,name,role', 'complaintCategory', 'medicine:id,nama_obat,satuan,stok,status_stok'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                    ->orWhere('kelas_pasien', 'like', "%{$search}%")
                    ->orWhere('keluhan', 'like', "%{$search}%");
            });
        }

        $records = $query->get();

        return response()->json([
            'success' => true,
            'data' => $records,
            'summary' => [
                'total' => $records->count(),
                'sedang_dirawat' => $records->where('status', 'sedang_dirawat')->count(),
                'dirujuk' => $records->where('status', 'dirujuk')->count(),
                'selesai' => $records->where('status', 'selesai')->count(),
                'pending' => $records->where('status', 'pending')->count(),
            ],
        ]);
    }

    public function store(StoreMedicalRecordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['petugas_id'] = $request->user()->id;

        $record = DB::transaction(function () use ($data) {
            $qty = (int) ($data['jumlah_obat'] ?? 0);
            if (! empty($data['medicine_id']) && $qty > 0) {
                $medicine = Medicine::lockForUpdate()->find($data['medicine_id']);
                if ($medicine) {
                    $medicine->stok = max(0, $medicine->stok - $qty);
                    $medicine->status_stok = Medicine::determineStatus($medicine->stok);
                    $medicine->save();
                }
            }

            return MedicalRecord::create($data);
        });

        $record->load(['petugas:id,name,role', 'complaintCategory', 'medicine:id,nama_obat,satuan,stok,status_stok']);

        return response()->json([
            'success' => true,
            'message' => 'Catatan rekam medis berhasil disimpan.',
            'data' => $record,
        ], 201);
    }

    public function show(MedicalRecord $medicalRecord): JsonResponse
    {
        $medicalRecord->load(['petugas:id,name,role', 'complaintCategory', 'medicine']);

        return response()->json([
            'success' => true,
            'data' => $medicalRecord,
        ]);
    }

    public function update(UpdateMedicalRecordRequest $request, MedicalRecord $medicalRecord): JsonResponse
    {
        $medicalRecord->update($request->validated());
        $medicalRecord->load(['petugas:id,name,role', 'complaintCategory', 'medicine']);

        return response()->json([
            'success' => true,
            'message' => 'Catatan rekam medis berhasil diperbarui.',
            'data' => $medicalRecord,
        ]);
    }

    public function destroy(MedicalRecord $medicalRecord): JsonResponse
    {
        $medicalRecord->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan rekam medis berhasil dihapus.',
        ]);
    }
}
