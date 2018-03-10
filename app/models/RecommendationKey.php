<?php

class RecommendationKey extends \Eloquent {
	protected $primaryKey = 'id';
	protected $fillable = [
		'evaluation_id', 'key'
	];

	protected $table = 'recommendation_keys';

	public function evaluation()
	{
		return $this->belongsTo(Evaluation::class, 'evaluation_id');
	}

	public function recommendation()
    {
        return $this->hasOne(Recommendation::class, 'recommendation_key_id', 'id');
    }
}
