<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_reports', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['bug', 'error', 'mencurigakan']);
            $table->string('category_label');
            $table->string('title');
            $table->string('page');
            $table->enum('status', ['pending', 'done'])->default('pending');
            $table->string('status_label')->default('Perlu Ditinjau');
            $table->text('desc');
            $table->text('solution');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_reports');
    }
};
