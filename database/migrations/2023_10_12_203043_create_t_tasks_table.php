<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_tasks', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedInteger('sort');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->time('time_to')->nullable();

            $table->foreign('group_id')
                ->references('id')
                ->on('t_groups')
                ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('t_tasks', static function (Blueprint $table) {
            $table->dropForeign('t_group_id_foreign');
        });

        Schema::dropIfExists('t_tasks');
    }
};