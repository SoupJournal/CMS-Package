<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('application')->references('id')->on('application');
			$table->integer('parent')->reference('id')->on('form')->nullable();
			$table->string('key', 255)->unique();
			$table->string('name', 255);
			$table->string('properties')->nullable();
			$table->string('permissions')->nullable();
			$table->integer('type')->default(0);
			$table->integer('status')->default(0);

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
		Schema::dropIfExists('form');
	}

}
