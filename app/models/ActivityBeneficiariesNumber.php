<?php

class ActivityBeneficiariesNumber extends \Eloquent {

	protected $table = 'activity_beneficiaries_number';
	protected $fillable = [
			'activity_id',
			'beneficiary_type',
			'beneficiary_age',
			'beneficiaries_number'
	];

	public static function getCollection(array $params = array())
	{
			extract($params);

			$model = new static;

			return $model->with('activity')->get();
	}


	public function activity()
	{
			return $this->belongsTo(Activity::class, 'activity_id');
	}

}
