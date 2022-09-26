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
        Schema::create('user_token', static function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('token');

            $table->unique('user_id');

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
        Schema::table('user_token', static function (Blueprint $table) {
            $table->dropUnique('user_token_user_id_unique');
        });
        Schema::dropIfExists('user_token');
    }
};
