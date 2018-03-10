<?php

use Morph\Database\Model;

class RecommendationType extends Model {

    protected $table = 'recommendation_types';
    // Add your validation rules here
    public static $rules = [
		'title' => 'required|max:32|unique:recommendation_types,title,:id'
	];

	// Don't forget to fill this array
	protected $fillable = ['id', 'title'];

    public function recommendation()
    {
        return $this->hasMany(Recommendation::class, 'recommendation_type_id', 'id');
    }

}
