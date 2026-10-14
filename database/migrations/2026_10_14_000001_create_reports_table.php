<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('bulanan'); // bulanan, tahunan, keuangan
            $table->date('period_date');
            $table->string('url', 500)->nullable();     // tautan eksternal (flipbook, Google Drive, dll.)
            $table->string('file')->nullable();         // berkas PDF yang diunggah
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->index(['type', 'period_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
