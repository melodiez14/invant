<?php
use Morph\Database\Model;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table= 'users';
	// protected $with	= ['rolegroup', 'profile', 'projects'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = ['rolegroup_id', 'email', 'password', 'is_active', 'activation_code'];
	protected $hidden = array('password', 'remember_token');

    public function getProfileNameAttribute()
    {
        if ($this->profile) {
            return $this->profile->name;
        } else {
            return 'Admin';
        }
    }

    public function profile()
    {
        return $this->hasOne(Staff::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(Upload::class, 'uploaded_by');
    }

	public function managedprojects()
	{
		return $this->hasMany(Project::class, 'manager_id');
	}

    public function projects()
    {
        return $this->belongsToMany('Project');
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rolegroup()
    {
        return $this->belongsTo(Rolegroup::class, 'rolegroup_id');
    }

	public function assessmentAnswers()
	{
		return $this->hasMany(AssessmentAnswer::class);
	}

	public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'created_by');
    }

    public function proposals()
    {
        return $this->belongsToMany(Proposal::class, 'proposal_assessment_user', 'user_id', 'proposal_assessment_id')->withPivot('status', 'other_score', 'decission');
    }

    public function approvals()
    {
        return $this->hasMany(Opportunity::class, 'approved_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function newNotification()
    {
        $notification = new Notification();
        $notification->user()->associate($this);

        return $notification;
    }

	/* ALL NEW EVALUATION */
	public function evaluation_reports()
	{
		return $this->belongsToMany(EvaluationReport::class, 'evaluation_report_user', 'user_id');
	}

	public function evaluation_report_logs()
	{
		return $this->hasMany(EvaluationReportLog::class, 'user_id');
	}

	public function evaluation_step_logs()
	{
		return $this->hasMany(EvaluationStepLog::class, 'user_id');
	}

	public function evaluations()
	{
		return $this->belongsToMany(Evaluation::class, 'evaluation_user', 'user_id');
	}

	public function evaluations_created()
	{
		return $this->hasMany(Evaluation::class, 'created_by');
	}
}
