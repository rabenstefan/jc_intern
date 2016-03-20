<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('birthday')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_street')->nullable();
            $table->unsignedInteger('address_zip')->nullable();
            $table->string('address_city')->nullable();
            $table->boolean('sheets_deposit_returned')->default(false);

            // Can only be in one voice
            $table->integer('voice')->unsigned()->default(0);
            $table->foreign('voice')->references('id')->on('voices')->onDelete('set default');

            // Letzte Semester-Rueckmeldung.
            $table->integer('last_echo')->unsigned()->nullable()->default('null');
            $table->foreign('last_echo')->references('id')->on('semesters')->onDelete('set null');

            $table->rememberToken();
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
        Schema::drop('users');
    }
}
