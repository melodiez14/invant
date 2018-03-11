<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 32);
            $table->string('password', 64);
            $table->rememberToken();
			$table->timestamps();
			$table->integer('rolegroup_id')->unsigned();
			$table->softDeletes();

			$table->foreign('rolegroup_id')->references('id')->on('rolegroups');
			
			$table->index('id');
			$table->unique('username');
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
