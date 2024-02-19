<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_contacts', static function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedSmallInteger('type_id');
            $table->string('value', 128);

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_contacts');
    }
};