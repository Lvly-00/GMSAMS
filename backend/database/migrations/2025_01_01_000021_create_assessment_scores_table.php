<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('assessment_id')->constrained('assessments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('class_record_student_id')->constrained('class_record_students')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('raw_score', 8, 2)->nullable();
            $table->boolean('is_missing')->default(false);
            $table->timestamps();

            $table->unique(['assessment_id', 'class_record_student_id'], 'assessment_score_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_scores');
    }
};
