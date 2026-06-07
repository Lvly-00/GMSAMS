<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_record_student_id')->unique()->constrained('class_record_students')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedSmallInteger('total_assessments')->default(0);
            $table->unsignedSmallInteger('completed_assessments')->default(0);
            $table->unsignedSmallInteger('missing_assessments')->default(0);
            $table->decimal('completion_rate', 5, 2)->default(0);
            $table->enum('performance_level', ['Excellent', 'Satisfactory', 'At Risk', 'Failing'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_progress');
    }
};
