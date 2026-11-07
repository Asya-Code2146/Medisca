<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintCategoryRequest;
use App\Http\Requests\UpdateComplaintCategoryRequest;
use App\Models\ComplaintCategory;
use Illuminate\Http\JsonResponse;

class ComplaintCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = ComplaintCategory::withCount('medicalRecords')
            ->orderBy('nama_keluhan')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function store(StoreComplaintCategoryRequest $request): JsonResponse
    {
        $category = ComplaintCategory::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori keluhan berhasil ditambahkan.',
            'data' => $category,
        ], 201);
    }

    public function show(ComplaintCategory $complaintCategory): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $complaintCategory,
        ]);
    }

    public function update(UpdateComplaintCategoryRequest $request, ComplaintCategory $complaintCategory): JsonResponse
    {
        $complaintCategory->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori keluhan berhasil diperbarui.',
            'data' => $complaintCategory,
        ]);
    }

    public function destroy(ComplaintCategory $complaintCategory): JsonResponse
    {
        $complaintCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori keluhan berhasil dihapus.',
        ]);
    }
}
