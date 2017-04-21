<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('application', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('key', 255)->unique();	
			$table->string('name', 255);		
			$table->integer('theme')->default(0);
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
		Schema::dropIfExists('application');
	}

}
