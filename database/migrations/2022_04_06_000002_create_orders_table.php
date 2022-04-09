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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('source_id'); // Где был сделан заказ; Model: Seller
            $table->unsignedBigInteger('seller_id'); // Где будет получен заказ; Model: Seller
            $table->string('number'); // Номер заказа внутренний
            $table->string('number_external'); // Номер заказа внешний
            $table->unsignedSmallInteger('status'); // Статус заказа
            $table->string('product_code')->nullable(); // Код товара
            $table->date('accepted_date'); // Дата принятия заказа
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
