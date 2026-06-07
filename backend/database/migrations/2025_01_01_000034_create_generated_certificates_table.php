<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('honors_list_id')->constrained('honors_lists')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('honors_category', ['With Honors', 'With High Honors', 'With Highest Honors']);
            $table->string('file_path');
            $table->foreignUuid('generated_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_certificates');
    }
};
