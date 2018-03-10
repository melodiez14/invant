<?php

class EvaluationStep extends Eloquent {
	protected $table = 'evaluation_steps';
	protected $fillable = [
		'evaluation_id',
		'evaluation_step_type_id',
		'order_index',
		'title',
		'question_commentable',
		'question_date_fill',
		'vendor_assessment',
		'vendors_number'
	];

	public static function getQuestionCategoriesCollection($id)
	{
		// NOT IMPLEMENTED YET
		return QuestionCategory::with(['questions' => function($query) use($id) {
			$query->whereHas('evaluation_steps', function($query2) use($id) {
				$query2->where('evaluation_step_id', $id);
			});
		}])->whereHas('questions', function($query) use($id) {
			$query->whereHas('evaluation_steps', function($query2) use($id) {
				$query2->where('evaluation_step_id', $id);
			});
		})->get();
	}

	public function questiontype()
	{
		return $this->belongsTo(EvaluationQuestionType::class, 'evaluation_step_type_id');
	}

	public function evaluation()
	{
		return $this->belongsTo(Evaluation::class, 'evaluation_id');
	}

	public function questions()
	{
		return $this->belongsToMany(Question::class, 'evaluation_step_question', 'evaluation_step_id', 'question_id');
	}

	public function logs()
    {
        return $this->hasMany(EvaluationStepLog::class, 'evaluation_step_id');
    }

	public function log()
    {
        return $this->hasOne(EvaluationStepLog::class, 'evaluation_step_id');
    }

    public function getLogIdAttribute()
    {
    	return !empty($this->log) ? $this->log->id : null;
    }

}
