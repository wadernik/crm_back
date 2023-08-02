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
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->string('phone', 16)->nullable();
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedTinyInteger('sex')->nullable();
            $table->timestamp('last_seen')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        Schema::table('users', static function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
        });

        Schema::dropIfExists('users');
    }
};