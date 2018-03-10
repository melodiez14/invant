<?php

use Morph\Database\Model;

class Indicator extends Model {
	protected $fillable = [
		'title', 'remark'
	];

    // Add your validation rules here
    public static $rules = [
        'title' => 'required|max:255'
    ];

	public function projects()
	{
		return $this->belongsToMany(Project::class);
	}

	public function activities()
	{
		return $this->belongsToMany(Activity::class);
	}
}