<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_board_user', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('board_id')
                ->references('id')
                ->on('t_boards')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('t_board_user', static function (Blueprint $table) {
            $table->dropForeign('t_board_user_board_id_foreign');
            $table->dropForeign('t_board_user_user_id_foreign');
        });

        Schema::dropIfExists('t_board_user');
    }
};