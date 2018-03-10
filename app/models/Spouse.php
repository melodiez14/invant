<?php

use Morph\Database\Model;

class Spouse extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'beneficiary_spouse';

    public $incrementing = false;

    public $timestamps = false;

    public function beneficiary()
    {
        // has many 'Beneficiary' model on table `beneficiaries` at field `id`
        return $this->belongsTo(Beneficiary::class);
    }
    public function spouse()
    {
        // has many 'Beneficiary' model on table `beneficiaries` at field `id`
        return $this->belongsTo(Beneficiary::class);
    }
}
