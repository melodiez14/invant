<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('rolegroup_id')->unsigned();
            $table->integer('module_id')->unsigned();
			$table->enum('ability', ['CREATE', 'READ', 'UPDATE', 'DELETE']);
			$table->timestamps();

            $table->foreign('rolegroup_id')->references('id')->on('rolegroups');
			$table->foreign('module_id')->references('id')->on('modules');
			
            $table->index('id');
            $table->unique(['rolegroup_id', 'module_id', 'ability']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles');
	}

}
