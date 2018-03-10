<?php

class Activitytype extends Eloquent
{
    protected $table = 'activity_types';
    protected $guarded = array();

    public static $rules = array();

    public $timestamps = false;

    /**
     * One-to-Many relations with Activity
     * @return Collection of Activity
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'activity_type_id');
    }
}
