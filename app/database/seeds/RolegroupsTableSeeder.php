<?php


class RolegroupsTableSeeder extends Seeder {

	public function run()
	{
        $table = DB::table('rolegroups');
        $table->delete();

        $records = [
            [
                'name'          => 'Super Administrator',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],[
                'name'          => 'Data Entry',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        ];

        $table->insert($records);
	}

}
