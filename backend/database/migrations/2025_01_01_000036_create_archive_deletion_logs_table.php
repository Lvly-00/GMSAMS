<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archive_deletion_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('deleted_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('record_type', 100);
            $table->string('record_id', 36);
            $table->json('snapshot');
            $table->timestamp('deleted_at');

            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archive_deletion_logs');
    }
};
