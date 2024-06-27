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
        Schema::table('deals', function (Blueprint $table) {
            $table->integer('status')->default(1);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->integer('status')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
