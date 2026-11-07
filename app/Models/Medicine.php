<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medicines';

    protected $fillable = [
        'nama_obat',
        'stok',
        'satuan',
        'tgl_kedaluwarsa',
        'status_stok',
    ];

    protected function casts(): array
    {
        return [
            'stok' => 'integer',
            'tgl_kedaluwarsa' => 'date',
        ];
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'medicine_id');
    }

    public static function determineStatus(int $stock): string
    {
        if ($stock <= 2) {
            return 'kritis';
        }
        if ($stock <= 8) {
            return 'menipis';
        }
        return 'aman';
    }
}
