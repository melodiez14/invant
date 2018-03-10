<?php

use Morph\Database\Model;

class Project extends Model
{
    protected $guarded = array();
    protected $STATUS = [
        ['id'=>0,'title'=>'Suspended'],
        ['id'=>1,'title'=>'Active']
    ];
    protected static $rules = array(
        'title' => 'required|max:255',
        'startDate' => 'required|before:finishDate',
        'finishDate' => 'required|after:startDate',
        'realFinishDate' => 'after:startDate',
        'manager_id' => 'required|exists:users,id',
        'sofCodes' => 'required|max:255'
    );

    // protected $with = ['manager', 'districts', 'subthemes', 'users'];

    /**
     * Custom attributes
     * @var array
     */
    protected $appends = array('status', 'permissionname', 'admins');

    /**
     * Date Mutator, auto change to Carbon instance specified attribute
     * @return array
     */
    public function getDates()
    {
        return array('created_at', 'updated_at','startDate', 'finishDate', 'realFinishDate');
    }

    /**
     * Inverse One-to-Many relations with Staff describing project manager
     * with manager_id attribute.
     * @return Staff
     */
    public function manager()
    {
        return $this->belongsTo('User', 'manager_id');
    }

    /**
     * Many-to-many relations with District model
     * @return Collection of District
     */
    public function districts()
    {
        return $this->belongsToMany('District');
    }

    /**
     * One-to-many relations with Activity model
     * @return Collection of Activity
     */
    public function activities()
    {
        return $this->hasMany('Activity');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * One-to-many relations with Report model
     * @return Collection of Report
     */
    public function reports()
    {
        return $this->hasMany('Report');
    }

    /**
     * Many-to-many relations with Subtheme model
     * @return Collection of Subtheme
     */
    public function subthemes()
    {
        return $this->belongsToMany('Subtheme');
    }

    /**
     * Many-to-many relations with Subtheme model
     * @return Collection of SCOffice
     */
    public function scoffices()
    {
        return $this->belongsToMany('Scoffice');
    }

    /**
     * Many-to-many relations with Users model
     * @return Collection of Subtheme
     */
    public function users()
    {
        return $this->belongsToMany('User');
    }

    /**
     * Many-to-many relations with Libraries model
     * @return Collection of Subtheme
     */
    public function libraries()
    {
        return $this->belongsToMany('Library', 'project_library');
    }

    /**
     * Get all related project location district name
     * @return array name of district
     */
    public function getDistrictTitle()
    {
        $districts = $this->districts;
        $districtTitle = array();
        foreach ($districts as $district) {
            $id = $district->pivot->district_id;
            $district_object = District::find($id);
            array_push($districtTitle,  $district->province->title. ', '. $district_object->title);
        }

        return $districtTitle;
    }

    /**
     * Get all related project subtheme name
     * @return array name of subtheme
     */
    public function getSubthemeTitle()
    {
        $subthemes = $this->subthemes;
        $subthemeTitle = array();
        foreach ($subthemes as $subtheme) {
            $id = $subtheme->pivot->subtheme_id;
            $subtheme_object = Subtheme::find($id);
            array_push($subthemeTitle, $subtheme_object->theme->title .', '.$subtheme->title);
        }

        return $subthemeTitle;
    }

    /**
     * Get all related project subtheme name
     * @return array name of subtheme
     */
    public function getOfficesTitle()
    {
        $scoffices = $this->scoffices;
        $scofficesTitle = array();
        foreach ($scoffices as $office) {
            $id = $office->pivot->scoffice_id;
            $office_object = Scoffice::find($id);
            array_push($scofficesTitle, $office_object->name);
        }

        return $scofficesTitle;
    }

    /**
     * Get all related project subtheme name
     * @return array name of subtheme
     */
    public function getLocalAdminName()
    {
        $localAdmins = $this->users;
        $localAdminName = array();
        foreach ($localAdmins as $localAdmin) {
            array_push($localAdminName, $localAdmin->profile->name);
        }

        return $localAdminName;
    }

    /**
     * Get all current active project based on status_id = 1 and
     * finishDate > Today date
     * @return Project Current active project
     */
    public static function getActiveProjects($finished = false)
    {
        $query = self::where('status_id', '!=', 0)
            ->where('finishDate', '>', new DateTime('now'));

        if($finished)
            $query = $query->whereNotNull('realFinishDate');

        return $query->get();
    }

    /**
     * Return string describing status
     * @return String expired/suspended/active
     */
    public function getStatusAttribute()
    {
        foreach ($this->STATUS as $status) {
            if ($this->status_id == $status['id']) {
                if ($this->status_id === 1) {
                    if ($this->realFinishDate !== null) {
                        if (new DateTime() > new DateTime($this->realFinishDate)) {
                            return 'Done';
                        }
                    } elseif (new DateTime() > new DateTime($this->finishDate)) {
                        return 'Extended';
                    } else {
                        return $status['title'];
                    }
                } else {
                    return $status['title'];
                }
            }
        }
    }

    /**
     * Get permission name attribute `project.` + `project_id`
     * eg. project.1, project.2, project.3, etc
     * @return String
     */
    public function getPermissionnameAttribute()
    {
        return 'project.'.$this->id;
    }

    /**
     * Get all staff whose admin and have access to this project
     * @return Staff Staff with access
     */

    public function getAdminsAttribute()
    {
        // Find all users have access to this project and filter not banned
        $project = $this;
        $users = User::whereHas('managedprojects', function($query) use($project) {
            return $query->whereId($project->id);
        })->orWhereHas('projects', function($query) use($project) {
            return $query->whereId($project->id);
        })->get()->toArray();
        // $users = array_filter(Sentry::findAllUsersWithAccess($this->permissionname), function($user) {
        //     $throttle = Sentry::findThrottlerByUserId($user->id);
        //     if (!$throttle->isBanned()) {
        //         return $user;
        //     }
        // });

        return ($users ? $users : false);
    }

    /**
     * Get Minimum year of project based on Start Date
     * @return integer
     */
    public static function getMinYear()
    {
        return substr(Project::min('startDate'), 0, 4) ?: (new DateTime())->format('Y');
    }

    /**
     * Get Maximum year of project based on Finish Date
     * @return integer
     */
    public static function getMaxYear()
    {
        return substr(Project::max('finishDate'), 0, 4) ?: (new DateTime())->format('Y');
    }

    public function indicators()
    {
        return $this->belongsToMany(Indicator::class);
    }

    /**
     * Get all related project subtheme name
     * @return array name of subtheme
     */
    public function getIndicatorsTitle()
    {
        $indicators = $this->indicators;
        $indicatorsTitle = array();
        foreach ($indicators as $indicator) {
            $id = $indicator->pivot->indicator_id;
            $indicator_object = Indicator::find($id);
            array_push($indicatorsTitle, ['title' => $indicator_object->title,
                                            'id' => $indicator_object->id]);
        }

        return $indicatorsTitle;
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifable');
    }
}
