<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusquedaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busqueda', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('municipio_id')->unsigned();
            $table->integer('usuario_id')->unsigned()->nullable();
            $table->longtext('tripadvisor_info');
            $table->longtext('twitter_info');
            $table->longtext('tiempo_info');
            $table->longtext('wiki_info');
            $table->foreign('municipio_id')->references('id')->on('municipios')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
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
        Schema::dropIfExists('busqueda');
    }
}
