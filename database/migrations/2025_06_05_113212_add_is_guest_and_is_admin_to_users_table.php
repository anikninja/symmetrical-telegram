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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_guest')->default(false)->after('remember_token');
            $table->boolean('is_admin')->default(false)->after('is_guest');
            $table->string('device_token')->unique()->after('is_admin');
            $table->string('auth_id')->unique()->nullable()->after('device_token');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_guest');
            $table->dropColumn('is_admin');
        });
    }
};
