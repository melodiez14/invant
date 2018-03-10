<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolegroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rolegroups', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
            $table->string('name');
			$table->timestamps();

			$table->index('id', 'idx_rolegroup_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rolegroups');
	}

}
