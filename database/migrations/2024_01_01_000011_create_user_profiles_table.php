<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Physical attributes
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->string('body_type', 50)->nullable();
            $table->string('complexion', 50)->nullable();
            $table->string('physical_status', 50)->nullable(); // Normal, Physically Challenged
            
            // Personal info
            $table->enum('marital_status', ['never_married', 'divorced', 'widowed', 'awaiting_divorce'])->nullable();
            $table->integer('num_children')->default(0);
            $table->string('about_me', 1000)->nullable();
            
            // Religion & Caste
            $table->foreignId('religion_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('caste_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subcaste_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mother_tongue_id')->nullable()->constrained()->nullOnDelete();
            $table->string('gothra', 100)->nullable();
            $table->boolean('manglik')->nullable();
            $table->string('horoscope', 100)->nullable();
            $table->string('star', 100)->nullable();
            $table->string('raasi', 100)->nullable();
            
            // Location
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('state_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('citizenship', 100)->nullable();
            $table->string('residing_country', 100)->nullable();
            
            // Education & Career
            $table->foreignId('education_id')->nullable()->constrained()->nullOnDelete();
            $table->string('education_detail', 255)->nullable();
            $table->foreignId('occupation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('occupation_detail', 255)->nullable();
            $table->string('employer_name', 255)->nullable();
            $table->string('annual_income', 100)->nullable();
            
            // Lifestyle
            $table->enum('diet', ['vegetarian', 'non_vegetarian', 'eggetarian', 'vegan'])->nullable();
            $table->enum('smoke', ['no', 'occasionally', 'yes'])->nullable();
            $table->enum('drink', ['no', 'occasionally', 'yes'])->nullable();
            
            // Family details
            $table->string('family_type', 50)->nullable(); // Joint, Nuclear
            $table->string('family_status', 50)->nullable(); // Middle Class, Upper Middle, Rich
            $table->string('family_values', 50)->nullable(); // Traditional, Moderate, Liberal
            $table->string('father_occupation', 255)->nullable();
            $table->string('mother_occupation', 255)->nullable();
            $table->integer('num_brothers')->default(0);
            $table->integer('num_sisters')->default(0);
            $table->integer('brothers_married')->default(0);
            $table->integer('sisters_married')->default(0);
            $table->string('family_location', 255)->nullable();
            $table->string('about_family', 1000)->nullable();
            
            // Partner preferences
            $table->integer('partner_age_min')->nullable();
            $table->integer('partner_age_max')->nullable();
            $table->decimal('partner_height_min', 5, 2)->nullable();
            $table->decimal('partner_height_max', 5, 2)->nullable();
            $table->string('partner_marital_status', 255)->nullable();
            $table->string('partner_religion', 255)->nullable();
            $table->string('partner_caste', 255)->nullable();
            $table->string('partner_mother_tongue', 255)->nullable();
            $table->string('partner_education', 255)->nullable();
            $table->string('partner_occupation', 255)->nullable();
            $table->string('partner_country', 255)->nullable();
            $table->string('partner_state', 255)->nullable();
            $table->string('partner_expectations', 1000)->nullable();
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};

















