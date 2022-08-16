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
        Schema::create('manufacturer_date_limits', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacturer_id');
            $table->date('date');
            $table->unsignedTinyInteger('limit_type');

            $table->foreign('manufacturer_id')
                ->references('id')
                ->on('manufacturers')
                ->onDelete('cascade');

            $table->unique(['manufacturer_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacturer_date_limits', static function (Blueprint $table) {
            $table->dropForeign('manufacturer_date_limits_manufacturer_id_foreign');
            $table->dropUnique('manufacturer_date_limits_manufacturer_id_date_unique');
        });
        Schema::dropIfExists('manufacturer_date_limits');
    }
};
