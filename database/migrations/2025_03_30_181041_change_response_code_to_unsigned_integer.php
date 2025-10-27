<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeResponseCodeToUnsignedInteger extends Migration
{
    public function up()
    {
        Schema::table('sms', function (Blueprint $table) {
            $table->unsignedInteger('response_code')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('sms', function (Blueprint $table) {
            $table->unsignedTinyInteger('response_code')->nullable()->change();
        });
    }
}
