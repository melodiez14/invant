<?php


class RolesTableSeeder extends Seeder {

    public function run()
    {
        $table = DB::table('roles');
        $table->delete();

        $records = [
            /**
             *  General Administrator
             */
            [
                'rolegroup_id' => 1,
                'module_id'    => 1,
                'ability'       => 'XREAD',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 1,
                'ability'       => 'XCREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 1,
                'ability'       => 'XUPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 1,
                'ability'       => 'XDELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 2,
                'ability'       => 'XREAD',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 2,
                'ability'       => 'XCREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 2,
                'ability'       => 'XUPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 2,
                'ability'       => 'XDELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 3,
                'ability'       => 'XREAD',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 3,
                'ability'       => 'XCREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 3,
                'ability'       => 'XUPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 3,
                'ability'       => 'XDELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 4,
                'ability'       => 'XREAD',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 4,
                'ability'       => 'XCREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 4,
                'ability'       => 'XUPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 4,
                'ability'       => 'XDELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        ];

        $table->insert($records);
    }
}
