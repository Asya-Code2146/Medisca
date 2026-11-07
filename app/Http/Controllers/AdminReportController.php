<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSystemReportRequest;
use App\Http\Requests\UpdateSystemReportStatusRequest;
use App\Models\SystemReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SystemReport::query()->latest();

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('page', 'like', "%{$search}%")
                    ->orWhere('category_label', 'like', "%{$search}%");
            });
        }

        $reports = $query->get();

        return response()->json([
            'success' => true,
            'data' => $reports,
            'counts' => [
                'total' => SystemReport::count(),
                'bug' => SystemReport::where('category', 'bug')->where('status', 'pending')->count(),
                'error' => SystemReport::where('category', 'error')->where('status', 'pending')->count(),
                'suspicious' => SystemReport::where('category', 'mencurigakan')->where('status', 'pending')->count(),
            ],
        ]);
    }

    public function store(StoreSystemReportRequest $request): JsonResponse
    {
        $report = SystemReport::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dicatat.',
            'data' => $report,
        ], 201);
    }

    public function toggleStatus(UpdateSystemReportStatusRequest $request, SystemReport $systemReport): JsonResponse
    {
        $newStatus = $request->validated('status');
        $systemReport->status = $newStatus;
        $systemReport->status_label = $newStatus === 'done' ? 'Selesai' : 'Perlu Ditinjau';
        $systemReport->save();

        return response()->json([
            'success' => true,
            'message' => 'Status laporan berhasil diubah.',
            'data' => $systemReport,
        ]);
    }

    public function destroy(SystemReport $systemReport): JsonResponse
    {
        $systemReport->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus.',
        ]);
    }
}
