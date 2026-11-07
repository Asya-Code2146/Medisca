<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Medicine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Medicine::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where('nama_obat', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status_stok', $request->input('status'));
        }

        $medicines = $query->orderBy('nama_obat')->get();

        return response()->json([
            'success' => true,
            'data' => $medicines,
            'summary' => [
                'total' => $medicines->count(),
                'kritis' => $medicines->where('status_stok', 'kritis')->count(),
                'menipis' => $medicines->where('status_stok', 'menipis')->count(),
                'aman' => $medicines->where('status_stok', 'aman')->count(),
            ],
        ]);
    }

    public function store(StoreMedicineRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['status_stok'])) {
            $data['status_stok'] = Medicine::determineStatus((int) $data['stok']);
        }

        $medicine = Medicine::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Obat berhasil ditambahkan.',
            'data' => $medicine,
        ], 201);
    }

    public function show(Medicine $medicine): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $medicine,
        ]);
    }

    public function update(UpdateMedicineRequest $request, Medicine $medicine): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['stok']) && ! isset($data['status_stok'])) {
            $data['status_stok'] = Medicine::determineStatus((int) $data['stok']);
        }

        $medicine->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data obat berhasil diperbarui.',
            'data' => $medicine,
        ]);
    }

    public function updateStock(UpdateStockRequest $request, Medicine $medicine): JsonResponse
    {
        $action = $request->validated('action');
        $amount = (int) ($request->validated('amount') ?? 1);

        if ($action === 'increase') {
            $medicine->stok += $amount;
        } elseif ($action === 'decrease') {
            $medicine->stok = max(0, $medicine->stok - $amount);
        }

        $medicine->status_stok = Medicine::determineStatus($medicine->stok);
        $medicine->save();

        return response()->json([
            'success' => true,
            'message' => 'Stok obat berhasil diperbarui.',
            'data' => $medicine,
        ]);
    }

    public function destroy(Medicine $medicine): JsonResponse
    {
        $medicine->delete();

        return response()->json([
            'success' => true,
            'message' => 'Obat berhasil dihapus.',
        ]);
    }
}
