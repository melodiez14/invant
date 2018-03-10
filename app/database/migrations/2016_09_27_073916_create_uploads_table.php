<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('uploads', function(Blueprint $table) {
			$table->increments('id');
			$table->string('file_name')->unique();
            $table->string('client_file_name');
			$table->string('extension', 5)->index();
			$table->integer('size')->unsigned();
			$table->string('mime');
			$table->integer('upload_by')->unsigned()->nullable();
			$table->foreign('upload_by')->references('id')->on('users')
				  ->onUpdate('cascade')->onDelete('set null');
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
		Schema::drop('uploads');
	}

}
