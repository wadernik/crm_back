<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dictionaries', static function (Blueprint $table) {
            $table->boolean('to_delete')->default(false)->after('parent_uuid');
        });
    }

    public function down(): void
    {
        Schema::table('dictionaries', static function (Blueprint $table) {
            $table->dropColumn('to_delete');
        });
    }
};