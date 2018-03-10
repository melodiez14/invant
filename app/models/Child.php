<?php

use Morph\Database\Model;

class Child extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'beneficiary_child';

    public $incrementing = false;

    public $timestamps = false;

    public function beneficiary()
    {
        // has many 'Beneficiary' model on table `beneficiaries` at field `id`
        return $this->belongsTo(Beneficiary::class);
    }
    public function child()
    {
        // has many 'Beneficiary' model on table `beneficiaries` at field `id`
        return $this->belongsTo(Beneficiary::class);
    }
    public function parent()
    {
        // return parrent beneficiary model on table 'beneficiaries'
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
}
