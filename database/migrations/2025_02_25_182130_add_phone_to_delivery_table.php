<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_deliveries', static function (Blueprint $table) {
            $table->string('client_phone', 16)->nullable()->after('delivery_date');
        });
    }

    public function down(): void
    {
        Schema::table('order_deliveries', static function (Blueprint $table) {
            $table->dropColumn('client_phone');
        });
    }
};