<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_field', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('form')->references('id')->on('form');
			$table->string('key', 255);
			$table->string('connection', 255);
			$table->string('table', 255);
			$table->string('field', 255);
			//$table->string('caption', 255);
			//$table->string('tooltip', 1023);
			//$table->string('placeholder', 255);
			//$table->string('default_value', 255); ??
			//$table->integer('type')->default(0);
			//$table->integer('order')->default(0);
			//$table->boolean('required')->default(0);
			$table->integer('row')->nullable();
			$table->string('properties', 2047)->nullable();


			//set timestamps
			$table->timestamps();
			
			
			//set constraints
			$table->unique(array('form', 'connection', 'table', 'field', 'row'));
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('form_field');
	}

}
