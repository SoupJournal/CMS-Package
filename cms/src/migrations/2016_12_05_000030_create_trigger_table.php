<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (!Schema::hasTable('trigger')) {
            Schema::create('trigger', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255)->nullable();
                $table->string('stub', 255)->nullable();
                $table->integer('type')->default(0);
                $table->integer('anchor')->default(0);
                $table->integer('trigger_form')->references('id')->on('form')->nullable();
                $table->integer('data_form')->references('id')->on('form')->nullable();
                //$table->string('url', 1023)->nullable();
                $table->string('properties', 2047)->nullable();
                $table->string('display_properties', 2047)->nullable();
                //$table->string('identifier', 2047)->nullable();
                //$table->string('code', 2047)->nullable();

                $table->integer('status')->default(0);

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
		Schema::dropIfExists('trigger');
	}

}
