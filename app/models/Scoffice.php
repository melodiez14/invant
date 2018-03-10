<?php

use Morph\Database\Model;

class Scoffice extends Model
{
    protected $table = 'scoffices';
    // Add your validation rules here
    protected $fillable = ['name', 'note'];

    public static $rules = [
        'name' => 'required|max:30|unique:scoffices,name,:id',
    ];

    public function opportunity()
    {
        return $this->hasMany('Opportunity');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'scoffice_id');
    }

    public static function getCombo()
    {
        $scoffices  = self::select('id', 'name')->get();
        $combo      = [];

        foreach($scoffices as $record)
            $combo[$record->id] = $record->name;

        return $combo;
    }

}
