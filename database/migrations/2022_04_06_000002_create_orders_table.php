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
        Schema::create('orders', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->unsignedBigInteger('source_id')->nullable(); // Источник заказа / где он был сделан; Model: Seller
            $table->unsignedBigInteger('seller_id')->nullable(); // Где будет отдан/получен заказ; Model: Seller
            $table->unsignedBigInteger('user_id')->nullable(); // Кто принял заказ
            $table->string('type'); // Тип заказа: Черновик заказа, Полноценный Заказ
            $table->string('number')->nullable(); // Номер заказа внутренний
            $table->string('number_external')->nullable(); // Номер заказа внешний
            $table->unsignedInteger('price')->nullable(); // Окончательная стоимость заказа
            $table->unsignedSmallInteger('status')->nullable(); // Статус заказа
            $table->string('product_code')->nullable(); // Код товара
            $table->date('accepted_date')->nullable(); // Дата принятия заказа
            $table->date('order_date')->nullable(); // Дата получения заказа
            $table->time('order_time')->nullable(); // Время получения заказа

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
