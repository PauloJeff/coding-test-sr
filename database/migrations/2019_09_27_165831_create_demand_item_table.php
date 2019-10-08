<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemandItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_item', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('demand_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('demand_id')->references('id')->on('demand');
            $table->foreign('product_id')->references('id')->on('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demand_item');
    }
}
