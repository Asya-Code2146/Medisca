<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplaintCategory extends Model
{
    use HasFactory;

    protected $table = 'complaint_categories';

    protected $fillable = [
        'nama_keluhan',
        'deskripsi',
    ];

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'complaint_category_id');
    }
}
