<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_performance_analysis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_record_student_id')->constrained('class_record_students')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('analysis_date');
            $table->enum('performance_level', ['Excellent', 'Satisfactory', 'At Risk', 'Failing']);
            $table->text('ai_feedback')->nullable();
            $table->text('action_plan')->nullable();
            $table->text('positive_reinforcement')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_performance_analysis');
    }
};
