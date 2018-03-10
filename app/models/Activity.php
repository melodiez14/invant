<?php

use Morph\Database\Model;

class Activity extends Model
{
    protected $guarded = array();
    protected $with = array(
      'project',
      'beneficiaries_number'
    );
    protected $fillable = array(
        'title',
        'description',
        'project_id',
        'activityDate',
        'activityFinishDate',
        'activity_type_id',
        'othertype',
        'output',
        'challenge',
        'learning',
        'plans',
    );
    public static $rules = array(
        'title'             => 'required|max:255',
        'activity_type_id'  => 'required|exists:activity_types,id',
        'activityDate'      => 'required',
        'activityFinishDate'=> 'date',
        'othertype'         => 'max:255',
        'description'       => 'max:255'
    );

    public function beneficiaries_number()
    {
        return $this->hasMany(ActivityBeneficiariesNumber::class, 'activity_id');
    }

    /**
     * Date Mutator, auto change to Carbon instance specified attribute
     * @return array
     */
    public function getDates()
    {
        return array('created_at', 'updated_at','activityDate');
    }

    /**
     * Inverse One-to-Many relations with Activitytype
     * @return Activitytype
     */
    public function activitytype()
    {
        return $this->belongsTo(Activitytype::class, 'activity_type_id');
    }

    /**
     * Inverse One-to-Many relations with Project
     * @return Project
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Many-to-Many relations with Beneficiary
     * @return Collection of Beneficiary
     */
    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class);
    }

    /**
     * Many-to-Many relations with Subdistrict
     * @return Collection of Subdistrict
     */
    public function subdistricts()
    {
        return $this->belongsToMany(Subdistrict::class);
    }

    /**
     * Many-to-Many relations with Village
     * @return Collection of Village
     */
    public function villages()
    {
        return $this->belongsToMany(Village::class);
    }

    /**
     * Many-to-Many relations with Subtheme
     * @return Collection of Subtheme
     */
    public function subthemes()
    {
        return $this->belongsToMany(Subtheme::class);
    }

    /**
     * Get title of activity, if its type is other (1)
     * Then, get from othertype attribute
     * @return String
     */
    public function getTypeTitle()
    {
        // Type = Other
        if ($this->activity_type_id == 1) {
            return $this->othertype;
        } else {
            return $this->activitytype->title;
        }
    }

    /**
     * Query Scope: Get Activity filtered by activity date in given year
     * @return Activity
     */
    public function scopeOfYear($query, $year)
    {
        return $query->whereBetween('activityDate', array(
                Carbon\Carbon::createFromDate($year-1, 12, 31)->toDateTimeString(),
                Carbon\Carbon::createFromDate($year+1, 1, 1)->toDateTimeString()
            ))->get();
    }

    /**
     * Check if activity date is within given year
     * @param  int $year
     * @return boolean
     */
    public function inYear($year)
    {
        if ($this->activityDate >= Carbon\Carbon::createFromDate($year, 1, 1)->toDateTimeString()
            && $this->activityDate <= Carbon\Carbon::createFromDate($year, 12, 31)->toDateTimeString()) {
            return true;
        }
    }

    public function indicators()
    {
        return $this->belongsToMany(Indicator::class);
    }

    public function notifications()
	{
		return $this->morphMany(Notification::class, 'notifable');
	}
}
