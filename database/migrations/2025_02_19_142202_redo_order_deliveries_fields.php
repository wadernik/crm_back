<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_deliveries', static function (Blueprint $table) {
            $table->unsignedBigInteger('courier_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_deliveries', static function (Blueprint $table) {
            $table->unsignedBigInteger('courier_id')->change();
        });
    }
};