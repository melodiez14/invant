<?php

class Module extends \Eloquent {
    protected $table    = 'modules';
	protected $fillable = ['module_alias', 'module_name', 'module_core'];

    /**
     * Initial relations one to many with roles table
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function roles()
    {
        return $this->hasMany(Role::class, 'rolegroup_id');
    }
}
