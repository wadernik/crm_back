<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_settings', static function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('type_id');
            $table->string('value', 128);

            $table->unique('type_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_settings');
    }
};