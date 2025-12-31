<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('email_verifications', 'phone')) {
                $table->dropUnique(['phone']);
                $table->dropColumn(['phone']);
            }
            foreach (['name', 'gender', 'dob', 'password'] as $column) {
                if (Schema::hasColumn('email_verifications', $column)) {
                    $table->dropColumn($column);
                }
            }
            if (!Schema::hasColumn('email_verifications', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('otp_attempts');
            }
        });
    }

    public function down(): void
    {
        Schema::table('email_verifications', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('phone', 20)->nullable()->unique();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('dob')->nullable();
            $table->string('password')->nullable();
            $table->dropColumn('verified_at');
        });
    }
};

