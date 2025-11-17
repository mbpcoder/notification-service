# Notification Service

A lightweight, flexible Laravel-based service to manage and send notifications across multiple channels.

## SMS Endpoints

### 1. Send Single SMS

**POST** `/sms/send`\
Send a single SMS message.

### 2. Send Bulk SMS

**POST** `/sms/send-bulk`\
Send SMS messages to multiple recipients at once.

### 3. Send OTP

**POST** `/sms/send-otp`\
Send a one-time password (OTP) message.

## Installation

Clone the repository and install dependencies:

    composer install

## Usage

Use the defined API endpoints to send SMS, bulk SMS, or OTP messages.

## License

MIT
