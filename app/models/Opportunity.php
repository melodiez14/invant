<?php

use Morph\Database\Model;

class Opportunity extends Model
{
    protected $guarded = array();
	// Add your validation rules here
	public static $rules = [
		'opp_no' => 'required|max:30|unique:opportunities,opp_no,:id',
        'name' => 'required',
        'donor' => 'required',
        'donor_value' => 'required',
        'donor_rate' => 'required',
        'sc_office' => 'required',
        'proposal_due_date' => 'date',
        'implementation_length' => 'required|numeric'
	];

    /**
     * Many-to-Many relations with Subtheme
     * @return Collection of Subtheme
     */
    public function subthemes()
    {
        return $this->belongsToMany(Subtheme::class);
    }

    /**
     * Inverse One-to-Many relations with Staff describing lead
     * with lead_person attribute.
     * @return Staff
     */
    public function lead()
    {
        return $this->belongsTo('Staff', 'lead_person');
    }

    public function approvedBy()
    {
        return $this->belongsTo('User', 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo('User', 'created_by');
    }

    public function scOffice()
    {
        return $this->belongsTo('Scoffice', 'sc_office');
    }

    public function currency()
    {
        return $this->belongsTo('Currency', 'donor_currency');
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
            array_push($subthemeTitle, $subtheme_object->theme->title . ', ' . $subtheme->title);
        }

        return $subthemeTitle;
    }

    public function getApprovalStatus($requestFrom){
        $status_id = $this->approval_status;
        if ($status_id == 'D' || $status_id == null) {
            if ($this->proposal !== null){
                if ($requestFrom == 'approval'){
                    return "<b class='badge bg-info'>-</b>";
                }
                return "<b class='badge bg-info'>Process</b>";
            }
            return "<b class='badge bg-warning'>Draft</b>";
        } elseif ($status_id == 'G') {
            return "<b class='badge bg-success'>Go</b>";
        } elseif ($status_id == 'N') {
            return "<b class='badge bg-danger'>No Go</b>";
        }
    }

    public function getFinalStatus(){
        $final_status_id = $this->proposal->final_status;
        if ($final_status_id == 'w') {
            return "<b class='badge bg-info'>Process</b>";
        } elseif ($final_status_id == 'G') {
            return "<b class='badge bg-success'>Go</b>";
        } elseif ($final_status_id == 'N') {
            return "<b class='badge bg-danger'>No Go</b>";
        }
    }

    public function getApprovalStatusXls(){
        // Get Status Opportunity
        $status_id = $this->approval_status;
        if ($status_id == 'D' || $status_id == null) {
            $status = "Draft";
        } elseif ($status_id == 'G') {
            $status = "Go";
        } elseif ($status_id == 'N') {
            $status = "No Go";
        }
        return $status;

    }
    public function currencyConvert($currency){
        if($currency != 'idr'){
            $donor_value = $this->donor_value;
            $donor_rate = $this->donor_rate;
            return $donor_usd = $donor_value * $donor_rate;
        }

    }

    public function proposal()
    {
        return $this->hasOne(Proposal::class);
    }

    /**
     * Get Minimum year of project based on Start Date
     * @return integer
     */
    public static function getMinYear()
    {
        return substr(Opportunity::min('created_at'), 0, 4);
    }

    /**
     * Get Maximum year of project based on Finish Date
     * @return integer
     */
    public static function getMaxYear()
    {
        return substr(Opportunity::max('created_at'), 0, 4);
    }

    /**
     * Get api for chart
     * @param array $args
     * @return Illuminate\Support\Collection
     */
    public static function getLikelyValueData(array $filter = [])
    {
        extract($filter);

        if(empty($year))
            $year = date('Y');

        if(empty($status))
            $status = 'W';

        return DB::table('opportunities')
            ->select(
                DB::raw('SUM(donor_value * donor_rate) AS donor_value'),
                DB::raw('COUNT(1) AS opps_number'),
                DB::raw('date_part(\'month\', created_at) AS month')
            )
            ->where(DB::raw('date_part(\'year\', created_at)', $year))
            ->where('approval_status', strtoupper($status))
            ->groupBy(DB::raw('date_part(\'month\', created_at)'))
            ->orderBy(DB::raw('date_part(\'month\', created_at)'), 'ASC')
            ->get();

    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifable');
    }
}
