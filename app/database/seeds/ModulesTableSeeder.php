<?php


class ModulesTableSeeder extends Seeder {

    public function run()
    {
        $table = DB::table('modules');
        $table->delete();

        $records = [
            [
                'alias'         => 'dashboard',
                'name'          => 'Dashboard',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'alias'         => 'users',
                'name'          => 'Users Management',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'alias'         => 'modules',
                'name'          => 'Modules Management',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'alias'         => 'rolegroups',
                'name'          => 'Roles Groups',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        ];

        $table->insert($records);
    }

}
