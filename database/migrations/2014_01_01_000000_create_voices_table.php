<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->integer('super_group')->unsigned()->nullable();

            $table->boolean('child_group')->default(true);
            $table->timestamps();
        });

        Schema::table('voices', function (Blueprint $table) {
            $table->foreign('super_group')->references('id')->on('voices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('voices');
    }
}
