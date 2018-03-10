<?php

class District extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'districts';

    protected $visible = array('id', 'title');

    /**
     * Fillable field by mass assignment
     * see http://wiki.laravel.io/FAQ_(Laravel_4)#MassAssignmentException
     * @var array field name
      */
    protected $fillable = array('id', 'title', 'province_id');

    /**
     * Disable timestamp (updated_at and created_at)
     * @var boolean
     */
    public $timestamps = false;

    public $incrementing = false;

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class, 'district_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

}
