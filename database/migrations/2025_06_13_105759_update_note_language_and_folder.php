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
        Schema::table('notes', function (Blueprint $table) {
            $table->unsignedBigInteger('language_id')->nullable()->after('summary');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('set null');

            $table->unsignedBigInteger('folder_id')->nullable()->after('library_id');
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->dropColumn('language_id');

            $table->dropForeign(['folder_id']);
            $table->dropColumn('folder_id');
        });
    }
};
