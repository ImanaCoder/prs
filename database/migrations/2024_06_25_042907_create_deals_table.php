<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->foreignUuid('client_id')->constrained()->onDelete('cascade');
            $table->string('work_type');
            $table->foreignId('source_type_id')->constrained()->onDelete('cascade');
            $table->double('deal_value');
            $table->timestamp('deal_date')->nullable(); // Allow null for editing
            $table->timestamp('due_date')->nullable(); // Allow null for editing
            $table->string('remarks',500);
            $table->string('deal_version');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
