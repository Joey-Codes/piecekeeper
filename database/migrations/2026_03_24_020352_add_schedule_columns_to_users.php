<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('pieces_per_session')->default(3)->after('password');
            $table->string('practice_frequency')->default('every_day')->after('pieces_per_session');
            $table->unsignedInteger('rotation_index')->default(0)->after('practice_frequency');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pieces_per_session', 'practice_frequency', 'rotation_index']);
        });
    }
};
