<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', static function (Blueprint $table) {
            $table->index('order_date');
        });
    }

    public function down(): void
    {
        Schema::table('orders', static function (Blueprint $table) {
            $table->dropIndex('orders_order_date_index');
        });
    }
};