<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sms_attempts', function (Blueprint $table) {
            $table->unsignedInteger('response_code')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_attempts', function (Blueprint $table) {
            $table->unsignedTinyInteger('response_code')->change();
        });
    }
};
