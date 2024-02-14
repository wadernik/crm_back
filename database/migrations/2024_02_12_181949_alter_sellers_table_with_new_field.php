<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', static function (Blueprint $table) {
            $table
                ->boolean('as_pickup_point')
                ->after('menu_id')
                ->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('sellers', static function (Blueprint $table) {
            $table->dropColumn('as_pickup_point');
        });
    }
};