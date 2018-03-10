<?php

class Rolegroup extends Eloquent {
    protected $table    = 'rolegroups';
	protected $fillable = ['rolegroup_name', 'rolegroup_depth', 'created_at', 'updated_at'];

    /**
     * Initial relations one to many with users table
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function users()
    {
        return $this->hasMany(User::class, 'rolegroup_id');
    }

    /**
     * Initial relations one to many with roles table
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function roles()
    {
        return $this->hasMany(Role::class, 'rolegroup_id');
    }

    public function getRolesStructure($rgId)
    {
        $roles      = Role::where('rolegroup_id', $rgId)->get();
        $roleCooked = [];
		$roleServe	= [];

        foreach($roles as $role)
        {
            if (!isset($roleCooked['mod_' . $role->module_id])) {
                $roleCooked['mod_' . $role->module_id] = [
                    'module_id' => $role->module_id,
                    'abilities' => [$role->ability]
                ];
            } else {
                array_push($roleCooked['mod_' . $role->module_id]['abilities'], $role->ability);
            }
        }

        foreach($roleCooked as $cooked)
            array_push($roleServe, $cooked);

        return $roleServe;
    }
}
