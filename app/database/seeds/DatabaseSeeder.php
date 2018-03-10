<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        DB::beginTransaction();
		try {

            $this->call(ModulesTableSeeder::class);
            $this->call(RolegroupsTableSeeder::class);
            $this->call(RolesTableSeeder::class);
			$this->call(UsersTableSeeder::class);

		    DB::commit();

        } catch (\PDOException $exception) {

            echo $exception->getMessage();
            DB::rollback();

        }
	}

}
