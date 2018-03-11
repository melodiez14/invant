<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code');
			$table->string('name');
			$table->text('address_primary');
			$table->text('address_secondary')->nullable();
			$table->string('city');
			$table->char('zip', 5)->nullable();
			$table->string('phone', 13);
			$table->string('fax', 13)->nullable();
			$table->string('contact')->nullable();
			$table->integer('created_by')->unsigned()->nullable();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->index('id');
			$table->unique('code');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customers');
	}

}
