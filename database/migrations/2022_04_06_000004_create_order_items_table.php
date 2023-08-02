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
        Schema::create('order_items', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('product_code')->nullable(); // Код товара
            $table->string('name', 255)->nullable(); // Наименование заказа
            $table->string('amount', 255)->nullable(); // Количество / вес в граммах
            $table->string('label', 255)->nullable(); // Надпись
            $table->string('decoration', 255)->nullable(); // Оформление
            $table->string('comment', 255)->nullable(); // Комментарий от покупателя

            $table->softDeletes();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', static function (Blueprint $table) {
            $table->dropForeign('order_items_order_id_foreign');
        });
        Schema::dropIfExists('order_items');
    }
};