<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
	{
		$table = DB::table('users');
        $table->delete();

        $records = [
            [
				'rolegroup_id'  => 1,
				'name' 			=> 'Risal Falah',
                'email'         => 'risal.falah@gmail.com',
                'password'      => Hash::make('qwerty123'),
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],[
				'rolegroup_id'  => 2,
				'name'			=> 'Asep Nur Muhammad',
                'email'         => 'asepnur.isk@gmail.com',
                'password'      => Hash::make('qwerty123'),
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
			]
        ];

        $table->insert($records);
	}

}