<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', static function (Blueprint $table) {
            $table->index('name');
            $table->index('uuid');
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('sellers', static function (Blueprint $table) {
            $table->dropUnique('sellers_uuid_unique');
            $table->dropIndex('sellers_name_index');
            $table->dropIndex('sellers_uuid_index');
        });
    }
};