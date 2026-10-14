<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('umum');
            $table->string('excerpt', 300)->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('target')->default(0);
            $table->unsignedBigInteger('collected')->default(0);
            $table->date('deadline')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('bank'); // bank, qris, ewallet
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('qr_image')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('donasi'); // zakat, infak, sedekah, wakaf, donasi
            $table->string('donor_name');
            $table->string('donor_phone')->nullable();
            $table->string('donor_email')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedBigInteger('amount');
            $table->unsignedSmallInteger('unique_code')->default(0);
            $table->text('message')->nullable();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('proof')->nullable();
            $table->string('status')->default('pending'); // pending, review, paid, rejected
            $table->text('admin_note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'paid_at']);
        });

        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->date('distributed_at');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('kegiatan');
            $table->string('excerpt', 300)->nullable();
            $table->longText('body');
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('photo')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('body');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['messages', 'team_members', 'galleries', 'posts', 'distributions', 'donations', 'payment_methods', 'programs', 'settings'] as $t) {
            Schema::dropIfExists($t);
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
