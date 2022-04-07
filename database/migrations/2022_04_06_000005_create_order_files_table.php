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
        Schema::create('order_files', function (Blueprint $table) {
            $table->primary(['order_id', 'file_id']);
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('file_id');

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
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
        Schema::table('order_files', function (Blueprint $table) {
            $table->dropForeign('order_files_order_id_foreign');
            $table->dropForeign('order_files_file_id_foreign');
        });

        Schema::dropIfExists('order_files');
    }
};
