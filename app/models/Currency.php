<?php

use Morph\Database\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $primaryKey = 'code';
    public $incrementing = false;

    protected $fillable = ['name', 'code'];
    // Add your validation rules here
    public static $rules = [
        'code' => 'required|max:3',
        'name' => 'required'
    ];

    public function opportunity()
    {
        return $this->hasMany('Opportunity', 'code');
    }

}