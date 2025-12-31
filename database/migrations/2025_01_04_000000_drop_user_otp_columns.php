<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'verification_code')) {
                $table->dropColumn('verification_code');
            }
            if (Schema::hasColumn('users', 'verification_expires_at')) {
                $table->dropColumn('verification_expires_at');
            }
            if (Schema::hasColumn('users', 'verification_attempts')) {
                $table->dropColumn('verification_attempts');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('verification_code')->nullable()->after('remember_token');
            $table->timestamp('verification_expires_at')->nullable()->after('verification_code');
            $table->unsignedSmallInteger('verification_attempts')->default(0)->after('verification_expires_at');
        });
    }
};

