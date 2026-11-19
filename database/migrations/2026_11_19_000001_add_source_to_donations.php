<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Asal donasi: formulir website, laporan WhatsApp, transfer langsung, atau tunai.
 * Donasi lama otomatis bernilai "website".
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('donations') && ! Schema::hasColumn('donations', 'source')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->string('source', 20)->default('website')->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('donations', 'source')) {
            Schema::table('donations', fn (Blueprint $table) => $table->dropColumn('source'));
        }
    }
};
