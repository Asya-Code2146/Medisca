<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemReport extends Model
{
    use HasFactory;

    protected $table = 'system_reports';

    protected $fillable = [
        'category',
        'category_label',
        'title',
        'page',
        'status',
        'status_label',
        'desc',
        'solution',
    ];
}
