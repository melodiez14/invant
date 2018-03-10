<?php

class EvaluationReport extends \Eloquent {
	protected $fillable = [
		'evaluation_id',
		'evaluation_step_type_id',
		'title',
		'duedate'
	];

	protected $table = 'evaluation_reports';

	public function evaluation()
	{
		return $this->belongsTo(Evaluation::class, 'evaluation_id');
	}

	public function evaluators()
	{
		return $this->belongsToMany(User::class);
	}

	public function questions()
	{
		return $this->belongsToMany(Question::class);
	}

	public function answers()
	{
		return $this->belongsToMany(Question::class, 'report_question_answers');
	}

	public function logs()
	{
		return $this->hasMany(EvaluationReportLog::class, 'evaluation_report_id');
	}

	public function log()
	{
		return $this->hasOne(EvaluationReportLog::class, 'evaluation_report_id');
	}

	public static function getQuestionCategoriesCollection(Evaluation $evaluation)
	{
		// $evaluation = Evaluation::findOrFail($evaluation_id);
		$report = $evaluation->report;
		// dd($report->toJson());

		if(empty($report))
			return new \Illuminate\Support\Collection();

		return QuestionCategory::with(['questions' => function($query) use($report) {
			$query->whereHas('evaluation_reports', function($query) use($report) {
				$query->where('id', $report->id);
			});
		}])->whereHas('questions', function($query) use($report) {
			$query->whereHas('evaluation_reports', function($query) use($report) {
				$query->where('id', $report->id);
			});
		})->get();
	}

	public function questiontype()
	{
		return $this->belongsTo(EvaluationQuestionType::class, 'evaluation_step_type_id');
	}
}
