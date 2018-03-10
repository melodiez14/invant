<?php

use Morph\Database\Model;
class Learning extends Model {
    protected $guarded = array();
    protected static $rules = array(
        'owner_id' => 'required',
        'description' => 'required',
        'title' => 'required',
        'learning_question' => 'required',
        'howto_answer' => 'required',
        'start_date' => 'required|date|before:end_date',
        'end_date' => 'required|date|after:start_date',
        'progress_status' => 'required',
        'complete_date' => 'after:start_date',
        'reason_postpone' => 'sometimes',
        'challenge' => 'sometimes',
        'summary' => 'sometimes',
        'admin_id' => 'required',
        'project_id' => 'sometimes'
    );

    public static $TYPE = array(
        ['id'=>'pro', 'title'=>'Project'],
        ['id'=>'com', 'title'=>'Communication'],
        ['id'=>'pdq', 'title'=>'PDQ'],
        ['id'=>'adv', 'title'=>'Advocacy and Campaign']
    );

    public static $AREA = array(
        ['id'=>'pq', 'title'=>'Program Quality'],
        ['id'=>'pi', 'title'=>'Program Implementation'],
        ['id'=>'ac', 'title'=>'Advocacy & Campaign'],
        ['id'=>'c', 'title'=>'Communication'],
        ['id'=>'lp', 'title'=>'Logistic/Procurement'],
        ['id'=>'f', 'title'=>'Financial'],
        ['id'=>'o', 'title'=>'Others']
    );

    public static $PROGRESS = array(
        ['id'=>'0', 'title'=>'On Going'],
        ['id'=>'1', 'title'=>'Canceled']
    );

    public static $PROGRESSEXPORT = [
        'On Going' => 'On Going',
        'Completed' => 'Completed',
        'Delayed' => 'Delayed',
        'Canceled' => 'Canceled',
        'Registered' => 'Registered'
    ];

    protected $dates = [
        'start_date', 'end_date', 'complete_date'
    ];

    public function libraries()
    {
        return $this->belongsToMany('Library');
    }

    /**
     * Date Mutator, auto change to Carbon instance specified attribute
     * @return array
     */
    public function getDates()
    {
        return array('created_at', 'updated_at');
    }
    public function subthemes()
    {
        return $this->belongsToMany('Subtheme');
    }
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function admin(){
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function getLearningTypeTitle()
    {
        $learning_type = $this->learning_type;
        foreach (Learning::$TYPE as $type) {
            if ($type["id"] == $learning_type) {
                return $type["title"];
            }
        }
    }
    public function getLearningAreaTitle()
    {
        $learning_area = $this->learning_area;
        foreach (Learning::$AREA as $area) {
            if ($area["id"] == $learning_area) {
                return $area["title"];
            }
        }
    }

    public function getProgressStatusTitle()
    {
        $progress_status = $this->progress_status;
        if ($progress_status == 1)
            return "Canceled";
        else if(empty($this->complete_date)){
            if(new DateTime() > new DateTime($this->end_date))
                return "Delayed";
            else if(new DateTime() >= new DateTime($this->start_date) && new DateTime() <= new DateTime($this->end_date))
                return "On Going";
            else if(new DateTime() < new DateTime($this->start_date))
                return "Registered";
        }
        else{
            return "Completed";
        }
    }

    public function getProgressStatusBg()
    {
        $progress_status = $this->progress_status;
        $bg = array();
        if ($progress_status == 1)
            $bg = ['color'=>'danger', 'title'=>'Canceled'];
        else if(empty($this->complete_date)){
            if(new DateTime() > new DateTime($this->end_date))
                $bg = ['color'=>'warning', 'title'=>'Delayed'];
            else if(new DateTime() >= new DateTime($this->start_date) && new DateTime() <= new DateTime($this->end_date))
                $bg = ['color'=>'info', 'title'=>'On Going'];
            else if(new DateTime() < new DateTime($this->start_date))
                $bg = ['color'=>'mute', 'title'=>'Registered'];
        }
        else{
            $bg = ['color'=>'success', 'title'=>'Completed'];
        }
        return $bg;
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifable');
    }
}
