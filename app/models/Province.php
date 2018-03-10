<?php

class Province extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'provinces';

    /**
     * Fillable field by mass assignment
     * see http://wiki.laravel.io/FAQ_(Laravel_4)#MassAssignmentException
     * @var array field name
      */
    protected $fillable = array('id', 'title');

    /**
     * Disable timestamp (updated_at and created_at)
     * @var boolean
     */
    public $timestamps = false;
    public $incrementing = false;

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id');
    }

}
