<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dictionaries', static function (Blueprint $table) {
            $table->index('uuid');
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('dictionaries', static function (Blueprint $table) {
            $table->dropUnique('dictionaries_uuid_unique');
            $table->dropIndex('dictionaries_uuid_index');
        });
    }
};