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
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('provider_id')->constrained();
            $table->foreignId('line_id')->constrained();
            $table->string('mobile', 32)->index();
            $table->string('template_name', 64)->nullable();
            $table->string('template_parameter1', 64)->nullable();
            $table->string('template_parameter2', 64)->nullable();
            $table->string('template_parameter3', 64)->nullable();
            $table->string('template_parameter4', 64)->nullable();
            $table->string('message', 500);
            $table->unsignedTinyInteger('retry_count')->default(0)->index();
            $table->enum('status', ['pending', 'sending', 'success', 'error'])->index();
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
            ])->index()->nullable();
            $table->text('response')->nullable();
            $table->unsignedTinyInteger('response_code')->nullable()->index();
            $table->timestamp('due_at')->nullable()->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('expired_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
