<?php

use Morph\Database\Model;
class Owner extends Model {
    protected $guarded = array();
    protected static $rules = array(
        'name' => 'required',
        'description' => 'sometimes'
    );
    /**
     * Date Mutator, auto change to Carbon instance specified attribute
     * @return array
     */
    public function getDates()
    {
        return array('created_at', 'updated_at');
    }
    public function learnings()
    {
        return $this->hasMany(Learning::class);
    }

}