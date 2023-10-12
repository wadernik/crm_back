<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_task_attachments', static function (Blueprint $table) {
            $table->id();
            $table->string('title', 128);
            $table->text('link')->nullable();

            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('file_id')->nullable();

            $table->foreign('task_id')
                ->references('id')
                ->on('t_tasks')
                ->onDelete('cascade');

            $table->foreign('file_id')
                ->references('id')
                ->on('files');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('t_task_attachments', static function (Blueprint $table) {
            $table->dropForeign('t_task_attachments_task_id_foreign');
            $table->dropForeign('t_task_attachments_file_id_foreign');
        });

        Schema::dropIfExists('t_task_attachments');
    }
};