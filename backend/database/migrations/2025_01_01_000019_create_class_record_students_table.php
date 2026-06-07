<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_record_students', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('class_record_id')->constrained('class_records')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('initial_grade', 5, 2)->nullable();
            $table->unsignedTinyInteger('quarterly_grade')->nullable();
            $table->enum('remarks', ['Passed', 'Failed', 'Dropped', 'Incomplete'])->nullable();
            $table->timestamps();

            $table->unique(['class_record_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_record_students');
    }
};
