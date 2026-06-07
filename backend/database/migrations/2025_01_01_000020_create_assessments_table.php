<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('class_record_id')->constrained('class_records')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('assessment_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('title', 200);
            $table->unsignedSmallInteger('assessment_number');
            $table->decimal('highest_score', 8, 2);
            $table->date('date_given')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
