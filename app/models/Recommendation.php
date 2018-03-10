<?php

class Recommendation extends Eloquent {
    protected $table = 'evaluation_recommendations';
    protected $with = array(
      'recommendation_type',
      'recommendation_key'
    );
    protected $fillable = [
      'evaluation_id',
      'recommendation_key_id',
      'recommendation_type_id',
      'action',
      'start_date',
      'end_date',
      'progress_status',
      'complete_date',
      'reason_postpone',
      'challenge',
      'summary',
      'created_by'
    ];

    public static function getCollection(array $params = array())
    {
      extract($params);

      $model = new static;

      if(!empty($evaluation_id)) {
        $model = $model->whereHas('evaluation', function($query) use($evaluation_id) {
          return $query->where('id', $evaluation_id);
        });
      }

      if (!empty($user_id)) {
        $model = $model->whereHas('evaluation', function($query) use($user_id) {
          return $query->whereHas('users', function($query) use($user_id) {
            return $query->where('id', $user_id);
          });
        });
      }

      return $model->with(['recommendation_type', 'libraries', 'recommendation_key'])->get();
    }

    public static $PROGRESS = array(
        ['id'=>'0', 'title'=>'On Going'],
        ['id'=>'1', 'title'=>'Canceled']
    );

    public static $PROGRESSEXPORT = [
        'On Going' => 'On Going',
        'Completed' => 'Completed',
        'Delayed' => 'Delayed',
        'Canceled' => 'Canceled',
        'Registered' => 'Registered'
    ];

    protected $dates = [
        'start_date', 'end_date', 'complete_date'
    ];

    /**
     * Date Mutator, auto change to Carbon instance specified attribute
     * @return array
     */
    public function getDates()
    {
        return array('created_at', 'updated_at');
    }

    public function getProgressStatusTitle()
    {
        $progress_status = $this->progress_status;
        if ($progress_status == 1)
            return "Canceled";
        else if(empty($this->complete_date)){
            if(new DateTime() > new DateTime($this->end_date)) {
              return "Delayed";
            }
            else if(new DateTime() >= new DateTime($this->start_date) && new DateTime() <= new DateTime($this->end_date)){
              return "On Going";
            }
            else{
              return "Registered";
            }
        }
        else{
            return "Completed";
        }
    }

    public function getProgressStatusBg()
    {
        $progress_status = $this->progress_status;
        $bg = array();
        if ($progress_status == 1)
            $bg = ['color'=>'danger', 'title'=>'Canceled'];
        else if(empty($this->complete_date)){
            if(new DateTime() > new DateTime($this->end_date)){
                $bg = ['color'=>'warning', 'title'=>'Delayed'];
            }
            else if(new DateTime() >= new DateTime($this->start_date) && new DateTime() <= new DateTime($this->end_date)) {
                $bg = ['color'=>'info', 'title'=>'On Going'];
            }
            else{
                $bg = ['color'=>'mute', 'title'=>'Registered'];
            }
        }
        else{
            $bg = ['color'=>'success', 'title'=>'Completed'];
        }
        return $bg;
    }

    public function evaluation(){
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function recommendation_type(){
        return $this->belongsTo(RecommendationType::class, 'recommendation_type_id');
    }

    public function recommendation_key(){
        return $this->belongsTo(RecommendationKey::class, 'recommendation_key_id');
    }

    public function libraries()
    {
        return $this->belongsToMany(Library::class, 'library_recommendation', 'evaluation_recommendation_id', 'library_id');
    }
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifable');
    }
}
