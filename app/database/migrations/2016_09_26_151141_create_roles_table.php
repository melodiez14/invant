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
            $table->string('role_ability', 7);
			$table->timestamps();

            $table->index(['rolegroup_id', 'module_id'], 'role_indexes');

            $table->foreign('rolegroup_id')->references('id')->on('rolegroups')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('module_id')->references('id')->on('modules')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
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
