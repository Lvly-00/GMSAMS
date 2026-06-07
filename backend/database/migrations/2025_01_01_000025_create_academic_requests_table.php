<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('class_record_id')->constrained('class_records')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('assessment_id')->nullable()->constrained('assessments')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('assessment_categories')->nullOnDelete();
            $table->foreignUuid('assigned_teacher_id')->constrained('teachers')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('date_of_assessment')->nullable();
            $table->date('preferred_retake_date')->nullable();
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->enum('source', ['manual', 'notification'])->default('manual');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_requests');
    }
};
