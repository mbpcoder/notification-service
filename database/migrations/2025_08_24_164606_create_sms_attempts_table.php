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
        Schema::create('sms_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_id')->constrained('sms');
            $table->enum('provider_status', [
                'success',
                'unknown',
                'mobile_not_valid',
                'mobile_array_is_empty',
                'mobile_array_is_bigger_than_expected',
                'line_not_valid',
                'line_is_empty',
                'line_array_is_bigger_than_expected',
                'encoding_not_valid',
                'message_class_not_valid',
                'server_error',
                'balance_is_low',
                'account_is_disabled',
                'account_is_expired',
                'account_credential_not_valid',
                'server_not_responding',
                'requested_service_is_not_valid',
                'mobile_canceled_receiving_sms',
                'request_throttle_passed',
            ]);
            $table->text('response');
            $table->unsignedTinyInteger('response_code');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_attempts');
    }
};
