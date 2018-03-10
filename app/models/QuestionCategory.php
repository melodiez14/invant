<?php

class QuestionCategory extends Eloquent {
    protected $table = 'question_categories';
    protected $fillable = [
        'title', 'weight', 'used_for'
    ];
	public function questions()
	{
		return $this->hasMany(Question::class, 'question_category_id');
	}
}
