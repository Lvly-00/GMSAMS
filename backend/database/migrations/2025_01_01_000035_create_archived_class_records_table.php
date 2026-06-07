<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archived_class_records', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('class_record_id')->unique()->constrained('class_records')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('archived_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('archive_reason', ['semester_end', 'manual']);
            $table->timestamp('archived_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archived_class_records');
    }
};
