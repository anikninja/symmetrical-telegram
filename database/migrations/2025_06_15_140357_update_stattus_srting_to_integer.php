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
        Schema::table('note_types', function (Blueprint $table) {
            $table->integer('status')->default(1)->change();
        });

        Schema::table('folders', function (Blueprint $table) {
            $table->integer('status')->default(1)->change();
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->integer('status')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('note_types', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });

        Schema::table('folders', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });
    }
};
