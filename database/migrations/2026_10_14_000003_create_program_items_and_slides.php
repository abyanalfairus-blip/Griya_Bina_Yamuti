<?php

use Database\Seeders\ProgramItemSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_items', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('utama');      // utama, gerakan, wakaf, penguatan
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('focus')->nullable();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('list_title')->nullable();
            $table->text('list_items')->nullable();         // satu butir per baris
            $table->string('icon')->default('apps');
            $table->string('image')->nullable();
            $table->string('image_pos')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('donation_type')->default('donasi');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('image_pos')->nullable();
            $table->string('caption');
            $table->string('alt')->nullable();
            $table->string('program_slug')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Isi awal agar beranda langsung berisi setelah migrasi.
        (new ProgramItemSeeder())->run();
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
        Schema::dropIfExists('program_items');
    }
};
