<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_task_user', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('task_id')
                ->references('id')
                ->on('t_tasks')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('t_task_user', static function (Blueprint $table) {
            $table->dropForeign('t_task_user_task_id_foreign');
            $table->dropForeign('t_task_user_user_id_foreign');
        });

        Schema::dropIfExists('t_task_user');
    }
};