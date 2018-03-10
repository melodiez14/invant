<?php

class Module extends Eloquent {
    
    protected $table    = 'modules';
	protected $fillable = ['alias', 'name'];

    /**
     * Initial relations one to many with roles table
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function roles()
    {
        return $this->hasMany(Role::class, 'rolegroup_id');
    }
}
