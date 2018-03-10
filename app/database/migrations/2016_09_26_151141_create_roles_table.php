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
            $table->string('ability', 7);
			$table->timestamps();

            $table->index(['rolegroup_id', 'module_id'], 'idx_roles_rolegroup_id_module_id');
            $table->index('id', 'idx_roles_id');

            $table->foreign('rolegroup_id')->references('id')->on('rolegroups');
            $table->foreign('module_id')->references('id')->on('modules');
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
