<?php

use Morph\Database\Model;

class Proposal extends Model
{
    protected $guarded = array();
    protected $table = 'proposal_assessments';
    protected $with = array(
        "opportunity"
    );

    public static $rules = array(
        'user_id' => 'required|max:255|exists:users,id',
        'project_idea' => 'required|max:255',
//        'award_info' => 'required|max:255',
        'opportunity_id' => 'required|exists:opportunities,id',
    );

    /**
     * Inverse One-to-Many relations with Activitytype
     * @return Activitytype
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evaluators()
    {
        return $this->belongsToMany(User::class, 'proposal_assessment_user', 'proposal_assessment_id')->withPivot('status', 'other_score', 'decission');
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'opportunity_id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'proposal_assessment_question', 'proposal_assessment_id', 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'proposal_assessment_id');
    }

    public function getTotalQuestions()
    {
        return $this->questions()->count();
    }

    public function getCompletition($user_id)
    {
        if ($this->questions()->count() > 0) {
            return number_format($this->answers()->where('user_id', $user_id)->count() / $this->questions()->count(), 2)*100;
        } else {
            return 0;
        }
    }

    public function getStatus($user_id)
    {
        return $this->evaluators()->where('user_id', $user_id)->first()->pivot['other_score'] != 0 && $this->getCompletition($user_id) == 100;
    }

    public function getFinalStatus()
    {
        if ($this->final_status == 'w') {
            return "<b class='badge bg-info'>Progress</b>";
        } elseif ($this->final_status == "G") {
            return "<b class='badge bg-success'>GO</b>";
        } elseif ($this->final_status == "N") {
            return "<b class='badge bg-danger'>NO GO</b>";
        }
    }

    public function getOpportunityNameAttribute()
    {
        return $this->opportunity ? $this->opportunity->name : '';
    }

    public static function getLikelyValueData(array $args = array())
    {

    }

    public function getQuestionCategoriesArray()
    {
        $questionCategory_arr = [];
        $this->questions->each(function($question) use (&$questionCategory_arr) {
            $questionCategory_arr[$question->category->id]['category'] = $question->category;
            $questionCategory_arr[$question->category->id]['questions'][] = $question;
        });
        return $questionCategory_arr;
    }

    public function getQuestionCategoriesCollection()
    {
        $questionsId_arr = $this->questions->lists('id');

        $questionCategories = QuestionCategory::whereHas('questions', function($query) use($questionsId_arr){
                return $query->whereIn('id', $questionsId_arr);
            })->with(['questions' => function($query) use($questionsId_arr){
                    return $query->whereIn('id', $questionsId_arr);;
            }])->get();

        return $questionCategories;
    }

    public function getQuestionAnswersArray($user_id = null)
    {
        $answers = [];
        foreach ($this->answers()->where('user_id', $user_id ?: Auth::id())->get() as $answer) {
            $answers[$answer['question_id']]['score'] = $answer['score'];
            $answers[$answer['question_id']]['comment'] = $answer['comment'];
        }
        return $answers;
    }

    public function getProposalDueDate(){
        $proposal_due_date = $this->opportunity->proposal_due_date;
        if(new DateTime($proposal_due_date) < new DateTime('now') && $this->final_status == 'w'){
            return "<b style='color: #fa3e29' >$proposal_due_date (Over Due Date)</b>";
        }else{
            return "$proposal_due_date";
        }
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifable');
    }

}
