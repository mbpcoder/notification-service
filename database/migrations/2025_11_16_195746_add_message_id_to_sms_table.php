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
        Schema::table('sms', function (Blueprint $table) {
            $table->foreignId('message_id')->nullable()->after('line_id')->constrained();
        });

        \Illuminate\Support\Facades\DB::table('sms')->chunkById(1000, function ($items) {
            foreach ($items as $item) {
                if ($item->message_id !== null) {
                    continue;
                }
                $contentMd5 = md5($item->message);
                $message = \Illuminate\Support\Facades\DB::table('messages')->where('content_md5', $contentMd5)->first();
                if ($message === null) {
                    $messageId = \Illuminate\Support\Facades\DB::table('messages')->insertGetId([
                        'content' => $item->message,
                        'content_md5' => $contentMd5,
                    ]);
                } else {
                    $messageId = $message->id;
                }

                \Illuminate\Support\Facades\DB::table('sms')->where('id', $item->id)->update(['message_id' => $messageId]);
            }
        });

        Schema::table('sms', function (Blueprint $table) {
            $table->dropColumn('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms', function (Blueprint $table) {
            $table->dropForeign(['message_id']);
            $table->dropColumn('message_id');

            $table->string('message', 500);
        });
    }
};
