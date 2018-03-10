<?php

class StaticStepLog extends \Eloquent {
	protected $fillable = ['evaluation_step_id', 'user_id', 'action', 'status'];
	protected $table = 'static_step_logs';

	public function step()
    {
        return $this->belongsTo(EvaluationStep::class, 'evaluation_step_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}