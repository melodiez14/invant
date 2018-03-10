<?php

class EvaluationReportLog extends \Eloquent {
	protected $fillable = [
		'evaluation_report_id',
		'user_id',
		'status'
	];

	protected $table = 'evaluation_report_logs';

	public function report()
	{
		return $this->belongsTo(EvaluationReport::class, 'evaluation_report_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function answers()
	{
		return $this->belongsToMany(Question::class, 'report_question_answers', 'evaluation_report_log_id')->withTimestamps()->withPivot('score', 'comment');
	}
}
