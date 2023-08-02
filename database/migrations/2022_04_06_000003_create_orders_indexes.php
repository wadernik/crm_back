<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', static function (Blueprint $table) {
            $table->index('manufacturer_id');
            $table->index('seller_id');
            $table->index('status');
            $table->index('number');
            $table->index('phone');
            $table->index(['manufacturer_id', 'seller_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', static function (Blueprint $table) {
            $table->dropIndex('orders_manufacturer_id_seller_id_status_index');
            $table->dropIndex('orders_manufacturer_id_index');
            $table->dropIndex('orders_seller_id_index');
            $table->dropIndex('orders_status_index');
            $table->dropIndex('orders_number_index');
            $table->dropIndex('orders_phone_index');
        });
    }
};