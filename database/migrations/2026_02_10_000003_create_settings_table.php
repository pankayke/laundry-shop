<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name')->default('My Laundry Shop');
            $table->string('shop_address')->default('');
            $table->string('shop_phone', 20)->default('');
            $table->decimal('wash_price', 8, 2)->default(25.00);
            $table->decimal('dry_price', 8, 2)->default(15.00);
            $table->decimal('fold_price', 8, 2)->default(10.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
