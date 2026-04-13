<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru setelah kolom 'email'
            $table->string('phone')->nullable()->after('email');
            $table->string('company')->nullable()->after('phone');
            $table->string('position')->nullable()->after('company');
            $table->text('address')->nullable()->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn(['phone', 'company', 'position', 'address']);
        });
    }
};