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
			$table->increments('id')->unsigned();
			$table->string('name', 100);
            $table->string('email')->unique();
            $table->string('password', 64);
            $table->rememberToken();
			$table->timestamps();
            $table->integer('rolegroup_id')->unsigned();

			$table->foreign('rolegroup_id')->references('id')->on('rolegroups');
			
			$table->index('id', 'idx_users_id');
            $table->index('rolegroup_id', 'idx_users_rolegroup_id');
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
