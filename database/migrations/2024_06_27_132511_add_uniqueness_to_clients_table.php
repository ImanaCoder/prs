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
        Schema::table('clients', function (Blueprint $table) {
            // Make email column unique
            $table->string('email')->unique()->change();
            
            // Make contact column unique
            $table->string('contact')->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Drop unique constraint from email column
            $table->dropUnique(['email']);
            
            // Drop unique constraint from contact column
            $table->dropUnique(['contact']);
        });
    }
};
