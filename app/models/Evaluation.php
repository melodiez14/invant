<?php

class Evaluation extends Eloquent {

	protected $table = 'evaluations';

	protected $fillable = [
		'project_id',
		'theme_id',
		'status',
		'title',
		'type',
		'remark',
		'sofcode',
		'scoffice_id',
		'contact_name',
		'contact_email',
		'duedate',
		'created_by',
		'finished_at',
		'mobile_number'
	];

	public static function getCollection(array $params = array())
	{
		extract($params);

		$model = (new static)->with(['sc_office', 'users', 'report', 'report.evaluators']);

		if(!empty($user_id)) {
			$model = $model->whereHas('users', function($query) use($user_id) {
				return $query->where('id', $user_id);
			})->orWhereHas('report', function ($query) use ($user_id) {
				return $query->whereHas('evaluators', function ($evaluators) use ($user_id){
					return $evaluators->where('id', $user_id);
				});
			});
		}

		if(!empty($theme_id)) {
			$model = $model->whereHas('theme', function($query) use($theme_id) {
				return $query->where('id', $theme_id);
			});
		}

		if(!empty($subtheme_id)) {
			$model = $model->whereHas('subthemes', function($query) use($subtheme_id) {
				return $query->where('id', $subtheme_id);
			});
		}

		if(!empty($project_id)) {
			$model = $model->whereHas('project', function($query) use($project_id) {
				return $query->where('id', $project_id);
			});
		}

		if(!empty($scoffice_id)) {
			$model = $model->whereHas('sc_office', function($query) use($scoffice_id) {
				return $query->where('id', $scoffice_id);
			});
		}

		if(!empty($creator)) {
			$model = $model->whereHas('creator', function($query) use($creator) {
				return $query->where('id', $creator);
			});
		}

		if(!empty($no_draft)) {
			$model = $model->where('status', '<>', 0);
		}else if(!empty($status)) {
			$model = $model->where('status', $status);
		}

		return $model->get();//->with('users')->get();
	}

	public function project()
	{
		return $this->belongsTo(Project::class, 'project_id');
	}

	public function theme()
	{
		return $this->belongsTo(Theme::class, 'theme_id');
	}

	public function steps()
	{
		return $this->hasMany(EvaluationStep::class, 'evaluation_id');
	}

	public function report()
	{
		return $this->hasOne(EvaluationReport::class, 'evaluation_id');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'evaluation_user');
	}

	public function subthemes()
	{
		return $this->belongsToMany(Subtheme::class, 'evaluation_subtheme')->withTimestamps();
	}

	public function recommendations()
	{
		return $this->hasMany(Recommendation::class, 'evaluation_id');
	}

	public function sc_office()
	{
		return $this->belongsTo(Scoffice::class, 'scoffice_id');
	}

	public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function isLegalEvaluator(User $user)
    {
        if(empty($this->id))
            return false;
        $users = $this->users;

        return $users->contains($user->id);
    }

	public function sendAssignedNotification()
	{

	}

	public function getProgressStatusTitle()
    {
        $progress_status = $this->status;
        if ($progress_status == 0)
            return "Draft";
        else if(empty($this->finished_at)){
            if(new DateTime() > new DateTime($this->duedate)) {
              return "Delayed";
            }
            else{
              return "Active";
            }
        }
        else{
            return "Done";
        }
    }

	public function recommendation_key()
	{
		return $this->hasMany(RecommendationKey::class, 'evaluation_id');
	}

	public function libraries()
    {
        return $this->belongsToMany(Library::class, 'evaluation_library', 'evaluation_id', 'library_id');
    }

	public function notifications()
	{
		return $this->morphMany(Notification::class, 'notifable');
	}
}
