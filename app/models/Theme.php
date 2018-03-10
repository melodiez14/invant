<?php

use Morph\Database\Model;

class Theme extends Model
{

    protected $guarded = array();
    protected $with = ['subthemes'];
    protected static $rules = array(
        'title' => 'required',
        'description' => 'sometimes',
        'staff_id' => 'sometimes|numeric'
    );
    /**
     * One-to-Many relationsh with Subtheme
     * @return Subtheme
     */
    public function subthemes()
    {
        return $this->hasMany(Subtheme::class, 'theme_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

}
