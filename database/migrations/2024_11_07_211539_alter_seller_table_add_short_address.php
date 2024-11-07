<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', static function (Blueprint $table) {
            $table->string('short_address', 1024)->after('address')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sellers', static function (Blueprint $table) {
            $table->dropColumn('short_address');
        });
    }
};
