<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_days'); // Validity in days
            $table->integer('contact_views_limit')->default(0); // 0 = unlimited
            $table->integer('chat_limit')->default(0); // 0 = unlimited
            $table->integer('interest_limit')->default(0); // 0 = unlimited
            $table->boolean('can_see_contact')->default(false);
            $table->boolean('can_chat')->default(false);
            $table->boolean('profile_highlighter')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};















