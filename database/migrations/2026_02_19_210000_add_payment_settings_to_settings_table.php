<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('gcash_number', 20)->default('09925247231')->after('fold_price');
            $table->string('qr_code_path')->nullable()->after('gcash_number');
            $table->string('payment_instructions', 500)
                ->default('Scan QR or send payment to the number above, then enter your reference number below.')
                ->after('qr_code_path');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['gcash_number', 'qr_code_path', 'payment_instructions']);
        });
    }
};
