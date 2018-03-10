<?php

class Village extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'villages';

    protected $visible = array('id', 'title');

    /**
     * Fillable field by mass assignment
     * see http://wiki.laravel.io/FAQ_(Laravel_4)#MassAssignmentException
     * @var array field name
      */
    protected $fillable = array('id', 'title', 'subdistrict_id');

    /**
     * Disable timestamp (updated_at and created_at)
     * @var boolean
     */
    public $timestamps = false;

    public $incrementing = false;

    /**
     * Inverse One-to-Many relations with Subdistrict
     * @return Subdistrict
     */
    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }

    /**
     * Many-to-Many relations with Beneficiaries
     * @return Collection of Beneficiaries
     */
    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'village_id');
    }

    /**
     * Many-to-Many relations with Activitiy
     * @return Collection of Activitiy
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'village_id');
    }

}
