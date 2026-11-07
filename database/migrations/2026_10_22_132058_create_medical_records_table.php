<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('complaint_category_id')->nullable()->constrained('complaint_categories')->nullOnDelete();
            $table->string('nama_pasien');
            $table->string('kelas_pasien')->nullable();
            $table->text('keluhan');
            $table->foreignId('medicine_id')->nullable()->constrained('medicines')->nullOnDelete();
            $table->integer('jumlah_obat')->nullable();
            $table->text('tindakan')->nullable();
            $table->enum('status', ['pending', 'sedang_dirawat', 'selesai', 'dirujuk'])->default('selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
