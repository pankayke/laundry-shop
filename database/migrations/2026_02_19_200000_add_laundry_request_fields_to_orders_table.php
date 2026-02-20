<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN for enums, so we add columns only.
        // The status enum expansion is handled at the model/application level.
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('estimated_weight', 5, 2)->nullable()->after('notes');
            $table->string('payment_reference', 50)->nullable()->after('change_amount');
            $table->text('special_instructions')->nullable()->after('notes');
            $table->json('requested_services')->nullable()->after('estimated_weight');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['estimated_weight', 'payment_reference', 'special_instructions', 'requested_services']);
        });
    }
};
