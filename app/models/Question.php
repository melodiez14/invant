<?php

use Morph\Database\Model;

class Question extends Model
{
	protected $guarded = array();
	public static $rules = array(
		'title'=>'required|max:255',
		'question_category_id' => 'required|exists:question_categories,id',
	);
	protected $table = 'questions';

	public function evaluation_answers()
	{
		return $this->belongsToMany(EvaluationStepLog::class, 'question_answers', 'question_id');
	}

	public function evaluation_report_answers()
	{
		return $this->belongsToMany(EvaluationReportLog::class, 'report_question_answers', 'question_id')->withTimestamps()->withPivot('score', 'comment');
	}

	public function evaluation_steps()
	{
		return $this->belongsToMany(EvaluationStep::class, 'evaluation_step_question', 'question_id');
	}

	public function evaluation_reports()
	{
		return $this->belongsToMany(EvaluationReport::class, 'evaluation_report_question', 'question_id');
	}

	public function category()
	{
		return $this->belongsTo(QuestionCategory::class, 'question_category_id');
	}

	public function proposals()
	{
		return $this->belongsToMany(Proposal::class, 'proposal_assessment_question', 'question_id', 'proposal_assessment_id');
	}

	public function assesment_answer()
	{
		return $this->hasMany(AssessmentAnswer::class);
	}
}
