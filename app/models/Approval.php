<?php

class Approval extends \Eloquent {

    protected $table = 'opportunities';
    protected $fillable = ['approval_status'];

    // Add your validation rules here
	public static $rules = [
		 'approval_status' => 'required'
	];

    /**
     * Many-to-Many relations with Subtheme
     * @return Collection of Subtheme
     */
    public function lead()
    {
        return $this->belongsTo('User', 'lio_person');
    }

    public function staffs()
    {
        return $this->belongsTo(Staff::class);
    }

    public function subthemes()
    {
        return $this->belongsToMany('Subtheme', 'opportunity_subtheme', 'opportunity_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatus(){
        $status_id = $this->approval_status;
        if ($status_id == 'D') {
            if ($this->proposal !== null){
                return "<b class='badge bg-info'>Process</b>";
            }
            return "<b class='badge bg-warning'>Draft</b>";
        } elseif ($status_id == 'G') {
            return "<b class='badge bg-success'>Go</b>";
        } elseif ($status_id == 'N') {
            return "<b class='badge bg-danger'>No Go</b>";
        }
    }

    public function proposal()
    {
        return $this->hasOne('Proposal', 'opportunity_id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifable');
    }

}