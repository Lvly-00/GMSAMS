<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('academic_requests')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('changed_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30);
            $table->text('note')->nullable();
            $table->timestamp('changed_at');

            $table->index('changed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_status_history');
    }
};
