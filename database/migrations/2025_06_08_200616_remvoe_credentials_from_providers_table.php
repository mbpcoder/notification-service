<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn(['username', 'password', 'token']);
        });
    }

    public function down(): void
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->string('username', 500)->nullable();
            $table->string('password', 500)->nullable();
            $table->string('token', 500)->nullable();
        });
    }
};
