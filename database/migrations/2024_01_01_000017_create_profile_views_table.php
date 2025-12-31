<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('viewed_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['viewed_id', 'created_at']);
            $table->index(['viewer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_views');
    }
};

















