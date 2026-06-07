<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sf5_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('grade_level_id')->constrained('grade_levels')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('strand_id')->constrained('strands')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('generated_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('gwa_threshold', 5, 2);
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sf5_records');
    }
};
