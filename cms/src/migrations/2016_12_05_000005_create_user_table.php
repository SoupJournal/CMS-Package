<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 255);
			$table->string('password', 255);
			$table->string('email', 255)->nullable()->unique();
			$table->string('first_name', 255);
			$table->string('last_name', 255);
			$table->string('facebook_id', 255)->nullable();
			$table->string('gender', 100)->nullable();
			$table->string('country', 255)->nullable();
			$table->string('ip_address', 15)->nullable();
			
			$table->boolean('email_verified');

			$table->integer('default_application')->reference('id')->on('application')->nullable();
			$table->integer('role')->default(0);			
			$table->integer('status')->default(0);

			$table->string('remember_token')->nullable();

			$table->timestamp('last_login')->default(DB::raw('CURRENT_TIMESTAMP'));

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
		Schema::dropIfExists('user');
	}

}
