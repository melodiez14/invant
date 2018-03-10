<?php

class Role extends Eloquent {

    protected $table    = 'roles';
	protected $fillable = ['rolegroup_id', 'module_id','role_ability'];
    protected $with     = ['module'];

    public static function isAuthorized($roles, $routename)
    {
        list($module, $method) = explode(".", $routename);
        $abilities  = [];

        foreach($roles as $role)
        {
            if( !isset($abilities[$role->module->module_alias]) ) {
                $abilities[$role->module->module_alias] = [$role->role_ability];
            } else {
                array_push($abilities[$role->module->module_alias], $role->role_ability);
            }

        }

        if(isset($abilities[$module])) {

            if ( ($method === 'index') || ($method === 'show') ) {

                return true;

            } else if ( ($method === 'create') || ($method === 'store') ) {

                return (in_array("CREATE", $abilities[$module]) || in_array("XCREATE", $abilities[$module]));

            } else if ( ($method === 'edit') || ($method === 'update') ) {

                return (in_array("UPDATE", $abilities[$module]) || in_array("XUPDATE", $abilities[$module]));

            } else if ( ($method === 'destroy') || ($method === 'shred') || ($method === 'restore') ) {

                return (in_array("DELETE", $abilities[$module]) || in_array("XDELETE", $abilities[$module]));

            }

        }

        return false;

    }

    public static function isRoleDepthViolation($model)
    {
        $user       = Auth::user();
        $userRg     = $user->rolegroup;

        if($model instanceOf Rolegroup){
            $currentRg  = $model;
        } else {
            throw new \Exception("Model is not Rolegroup");
        }

        if($userRg->rolegroup_depth >= $currentRg->rolegroup_depth)
            throw new \Exception(trans('rolegroup.depth_violation'));


        return false;
    }

    /**
     * Get users in current ability
     * @return str $ability
     * @return Module $module
     * @return array
     */
    public static function getUsersByAbility($ability, Module $module)
    {
        $returned = [];
        $roles = self::where('role_ability', $ability)->where('module_id', $module->id)
            ->with(['rolegroup' => function($query) {
                $query->with('users');
            }])->get();

        foreach($roles as $role)
            $returned[] = $role->rolegroup->users->toArray();

        return $returned;
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rolegroup()
    {
        return $this->belongsTo(Rolegroup::class, 'rolegroup_id');
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
