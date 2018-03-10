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
            $table->integer('rolegroup_id')->unsigned();
            $table->string('email')->unique();
            $table->string('password', 64);
            $table->boolean('is_active');
            $table->string('activation_code')->nullable();
            $table->rememberToken();
			$table->timestamps();

            $table->index('rolegroup_id', 'users_indexes');
            $table->foreign('rolegroup_id')->references('id')->on('rolegroups')
                ->onUpdate('CASCADE');
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
