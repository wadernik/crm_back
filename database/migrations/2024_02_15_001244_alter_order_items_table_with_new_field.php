<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', static function (Blueprint $table) {
            $table
                ->unsignedSmallInteger('decoration_type')
                ->after('decoration')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', static function (Blueprint $table) {
            $table->dropColumn('decoration_type');
        });
    }
};