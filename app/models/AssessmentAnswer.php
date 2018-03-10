<?php

use Morph\Database\Model;

class AssessmentAnswer extends Model {
    protected $table = 'proposal_assessment_answers';
	protected $fillable = [
        'proposal_assessment_id',
        'question_id',
        'user_id',
        'score',
        'comment',
    ];

    public $timestamps = true;

    public static $rules = array(
        // 'user_id' => 'required|max:255|exists:users,id',
        'proposal_assessment_id' => 'required|exists:proposal_assessments,id',
        'question_id' => 'required|exists:questions,id',
        'user_id' => 'required|exists:users,id',
        'score' => 'required|alpha_dash|max:255',
        'comment' => 'sometimes|max:255',
    );

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
