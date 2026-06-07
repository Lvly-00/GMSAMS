<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_record_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('class_record_id')->constrained('class_records')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('submitted_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('action', ['submitted', 'resubmitted']);
            $table->enum('review_action', ['approved', 'rejected'])->nullable();
            $table->text('review_note')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_record_submissions');
    }
};
