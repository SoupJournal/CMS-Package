<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityGroupPermissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (!Schema::hasTable('security_group_permission')) {
            Schema::create('security_group_permission', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('security_group')->references('id')->on('security_group');
                $table->integer('user')->references('id')->on('user');
                $table->integer('permission')->default(0);

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
		Schema::dropIfExists('security_group_permission');
	}

}
