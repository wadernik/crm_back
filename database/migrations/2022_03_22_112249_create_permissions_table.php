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
        Schema::create('permissions', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();

            $table->foreign('section_id')
                ->references('id')
                ->on('permission_sections')
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
        Schema::table('permissions', static function (Blueprint $table) {
            $table->dropForeign('permissions_section_id_foreign');
        });

        Schema::dropIfExists('permissions');
    }
};