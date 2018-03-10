<?php

class EvaluationQuestionType extends \Eloquent {
	protected $primaryKey = 'id';
	protected $table	= 'evaluation_step_types';
	protected $fillable = ['title', 'scoring_config', 'max_scores'];

	public function steps()
	{
		return $this->hasMany(EvaluationStep::class, 'evaluation_step_type_id');
	}

	public function reports()
	{
		return $this->hasMany(EvaluationReport::class, 'evaluation_step_type_id');
	}

}
