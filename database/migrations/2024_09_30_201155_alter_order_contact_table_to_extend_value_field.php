<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_contacts', static function (Blueprint $table) {
            $table->string('value', 2056)->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_contacts', static function (Blueprint $table) {
            $table->string('value', 128)->change();
        });
    }
};