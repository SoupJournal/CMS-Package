<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormPermissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (!Schema::hasTable('form_permission')) {
            Schema::create('form_permission', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('form')->references('id')->on('form');
                $table->integer('security_group')->references('id')->on('security_group');

                $table->timestamps();
            });
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('form_permission');
	}

}
