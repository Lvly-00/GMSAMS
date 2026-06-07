<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('assignment_id')->constrained('subject_teacher_assignments')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('head_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('title', 200);
            $table->string('ocr_image_path')->nullable();
            $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_records');
    }
};
