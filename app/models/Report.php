<?php

use Morph\Database\Model;

class Report extends Model
{
    protected $guarded = array();

    protected static $rules = array(
        'title' => 'required|max:255',
        // check constant in ReportsController
        'type_id' => 'required|in:1,2',
        // when update, set it with old upload_id before validating
        // maximum 10 Mb
        'upload_id' => 'required|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240',
        'project_id' => 'exists:projects,id',
        'submitDate' => 'required',
        'description' => 'max:255',
        'reporterName' => 'max:255'
    );

    protected static $messages = array(
        'upload_id.required' => 'Please choose file to upload.',
        'upload_id.mimes' => 'The upload file must be a file of type: :values',
        'upload_id.max' => 'Maximum upload file size is :max kb'
    );

    public $timestamps = false;

    /**
     * Date Mutator, auto change to Carbon instance specified attribute
     * @return array
     */
    public function getDates()
    {
        return array('submitDate');
    }

    /**
     * One-to-Many relation with Upload
     * @return Upload
     */
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }

    /**
     * One-to-Many relation with Project
     * @return Upload
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if inputted year+month report for a project already exist
     * @param  dateIso $date yyyy-mm-dd
     * @return boolean
     */
    public static function isMonthlyReportSubmitted($project_id, $date)
    {
        $date = Carbon\Carbon::createFromFormat('Y-m-d', $date);
        // type_id 1 == monthly-report
        $reports = Report::where('type_id', '=', 1)->where('project_id','=',$project_id)->get();

        $YearMonthSubmited = [];
        foreach ($reports as $report) {
            array_push($YearMonthSubmited, $report->submitDate->year.$report->submitDate->month);
        }

        // if month+date exist in array, return true, else return false
        return ( (in_array($date->year.$date->month, $YearMonthSubmited)) ? true : false );

    }

}
