<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBusquedaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_busqueda', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('busqueda_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->unique(['user_id', 'busqueda_id']);
            $table->foreign('user_id')->references('id')->on('usuario')->onDelete('cascade');
            $table->foreign('busqueda_id')->references('id')->on('busqueda')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_busqueda');
    }
}
