<?php

class Subdistrict extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subdistricts';

    protected $visible = array('id', 'title');

    /**
     * Fillable field by mass assignment
     * see http://wiki.laravel.io/FAQ_(Laravel_4)#MassAssignmentException
     * @var array field name
     */
    protected $fillable = array('id', 'title', 'district_id');

    /**
     * Disable timestamp (updated_at and created_at)
     * @var boolean
     */
    public $timestamps = false;

    public $incrementing = false;

    /**
     * Inverse One-to-Many relations to District
     * @return District
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * One-to-Many relations to Village
     * @return Collection Village
     */
    public function villages()
    {
        return $this->hasMany(Village::class, 'subdistrict_id');
    }

    /**
     * Many-to-Many relations with Activity
     * @return Collection pivot
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

}
