<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->boolean('can_plan_rehearsal');
            $table->boolean('can_plan_gig');
            $table->boolean('can_send_mail'); // Maybe sometimes implemented...
            $table->boolean('can_configure_system'); // Tweak system parameters and such.
            $table->boolean('only_own_voice')->default(false); // Only relevant for Stimmfuehrer.
            $table->timestamps();
        });

        Schema::create('user_role', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('role_id')->unsigned()->default(0);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

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
        Schema::drop('user_role');
        Schema::drop('roles');
    }
}
