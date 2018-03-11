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
                'rolegroup_id'  => 1,
                'module_id'     => 1,
                'ability'       => 'READ',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 1,
                'ability'       => 'CREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 1,
                'ability'       => 'UPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 1,
                'ability'       => 'DELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 2,
                'ability'       => 'READ',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 2,
                'ability'       => 'CREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 2,
                'ability'       => 'UPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 2,
                'ability'       => 'DELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 3,
                'ability'       => 'READ',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 3,
                'ability'       => 'CREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 3,
                'ability'       => 'UPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 3,
                'ability'       => 'DELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 4,
                'ability'       => 'READ',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id' => 1,
                'module_id'    => 4,
                'ability'       => 'CREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 4,
                'ability'       => 'UPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 4,
                'ability'       => 'DELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 5,
                'ability'       => 'READ',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 5,
                'ability'       => 'CREATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 5,
                'ability'       => 'UPDATE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'rolegroup_id'  => 1,
                'module_id'     => 5,
                'ability'       => 'DELETE',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        ];

        $table->insert($records);
    }
}
