<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', static function (Blueprint $table) {
            $table->unsignedInteger('number')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', static function (Blueprint $table) {
            $table->string('number')->nullable()->change(); // Номер заказа внутренний
        });
    }
};