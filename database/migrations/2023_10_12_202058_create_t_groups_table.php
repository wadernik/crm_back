<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_groups', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id');
            $table->string('name', 128);
            $table->unsignedInteger('sort');

            $table->foreign('board_id')
                ->references('id')
                ->on('t_boards')
                ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('t_groups', static function (Blueprint $table) {
            $table->dropForeign('t_groups_board_id_foreign');
        });

        Schema::dropIfExists('t_groups');
    }
};