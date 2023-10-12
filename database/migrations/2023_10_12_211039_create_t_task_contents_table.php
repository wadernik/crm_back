<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_task_contents', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedTinyInteger('type');
            $table->string('name', 128)->nullable();
            $table->json('content');

            $table->foreign('task_id')
                ->references('id')
                ->on('t_tasks')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('t_task_contents', static function (Blueprint $table) {
            $table->dropForeign('t_task_contents_task_id_foreign');
        });

        Schema::dropIfExists('t_task_contents');
    }
};