<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_boards', static function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->unsignedBigInteger('file_id')->nullable();

            $table->foreign('file_id')
                ->references('id')
                ->on('files');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('t_boards', static function (Blueprint $table) {
            $table->dropForeign('t_boards_file_id_foreign');
        });

        Schema::dropIfExists('t_boards');
    }
};