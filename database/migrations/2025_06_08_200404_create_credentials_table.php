<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->enum('entity', ['provider', 'line'])->index();
            $table->unsignedBigInteger('entity_id');
            $table->string('username', 500)->nullable();
            $table->string('password', 500)->nullable();
            $table->string('token', 500)->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
