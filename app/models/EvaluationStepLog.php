<?php

class EvaluationStepLog extends \Eloquent {
	protected $fillable = [
		'evaluation_step_id',
		'user_id',
		'cur_vendor',
		'status'
	];
	protected $table = 'evaluation_step_logs';

	public function step()
	{
		return $this->belongsTo(EvaluationStep::class, 'evaluation_step_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function answers()
	{
		return $this->belongsToMany(Question::class, 'question_answers', 'evaluation_step_log_id')->withTimestamps()->withPivot('score', 'comment', 'date_answer', 'vendor');
	}
}
