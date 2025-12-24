<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('success_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bride_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('groom_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('story');
            $table->string('photo_path')->nullable();
            $table->date('wedding_date')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('success_stories');
    }
};











