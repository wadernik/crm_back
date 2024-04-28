<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', static function (Blueprint $table) {
            $table->string('label', 1024)->nullable()->change();
            $table->string('decoration', 1024)->nullable()->change();
            $table->string('comment', 2056)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', static function (Blueprint $table) {
            $table->string('label', 255)->nullable()->change();
            $table->string('decoration', 255)->nullable()->change();
            $table->string('comment', 255)->nullable()->change();
        });
    }
};