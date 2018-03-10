<?php

use Morph\Database\Model;

class Beneficiary extends Model
{
    protected $SEX = array(
        ['id'=>1, 'title'=>'Man', 'prefix'=>'Mr.'],
        ['id'=>2, 'title'=>'Woman', 'prefix'=>'Mrs.']
    );

    protected $STATUS = array(
      ['id'=>1, 'title'=>'Single'],
      ['id'=>2, 'title'=>'Maried']
    );

    protected $guarded = array();

    // Custome attribute (not exist in db)
    // Check $this->getSexAttribute , getStatusAttribute
    // http://laravel.com/docs/eloquent#converting-to-arrays-or-json (last paragraph)
    protected $appends = array('sex', 'status', 'prefix', 'age', 'addressCombined','title');

    protected static $rules = array(
        'name' => 'required|max:255',
        'sex_id' => 'required|in:1,2|numeric',
        'status_id' => 'in:1,2|numeric',
        'dateOfBirth' => 'required|date|before:now',
        'placeOfBirth' => 'max:45',
        'job' => 'max:45',
        'address' => 'max:45',
        'nik' => ('nik' == null ? ' ' : 'sometimes|numeric|unique:beneficiaries,nik,:id'),
        'nisn' => ('nisn' == null ? ' ' : 'sometimes|numeric|unique:beneficiaries,nisn,:id'),
        'rt' => 'max:999|numeric',
        'rw' => 'max:999|numeric',
        'village_id' => 'required|exists:villages,id|numeric',
        'code' => 'required|max:255|unique:beneficiaries,code,:id'
    );

    protected $with = ['village', 'children', 'spouse', 'activities'];
    /**
     * Date Mutator, auto change to Carbon instance specified attribute
     * @return array
     */
    public function getDates()
    {
        return array('created_at', 'updated_at','dateOfBirth');
    }

    /**
     * Inverse One-To-Many relations to Village
     * @return Village
     */
    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    /**
     * Get beneficiary sex
     * @return String
     */
    public function getSexAttribute()
    {
        foreach ($this->SEX as $sex) {
            if ($this->sex_id == $sex['id']) {
                return $sex['title'];
            }
        }
    }

    /**
     * Get beneficiary age
     * @return int
     */
    public function getAgeAttribute()
    {
        // Tampilkan age hanya jika dateOfBirth diquery atau ada isinya
        if (isset($this->dateOfBirth)) {
            return $this->dateOfBirth->age;
        }
    }

    /**
     * Get beneficiary status
     * @return String
     */
    public function getStatusAttribute()
    {
        foreach ($this->STATUS as $status) {
            if ($this->status_id == $status['id']) {
                return $status['title'];
            }
        }
    }

    /**
     * Get parent name
     * @return String
     */
    public function getParentAttribute()
    {
        //get parent Name
        $parents = [];
        foreach (Child::where("child_id", $this->id)->get() as $parent)
            array_push($parents, $parent->parent->name);
        return $parents;

    }

    /**
     * Get spouse name
     * @return String
     */
    public function getSpousesAttribute()
    {
        $spouses = [];
        foreach ($this->spouse as $spouse) {
            array_push($spouses, $spouse->spouse->name);
        }
        return $spouses;
    }

    /**
     * Get beneficiary prefix (Mr/Mrs) based on sex
     * @return String
     */
    public function getPrefixAttribute()
    {
        foreach ($this->SEX as $sex) {
            if ($this->sex_id == $sex['id']) {
                return $sex['prefix'];
            }
        }
    }

    public function getAddresscombinedAttribute()
    {
        $attribute = null;

        if(!empty($this->getAttribute('address')))
            $attribute = $this->address;
        if(!empty($this->getAttribute('rt')))
            $attribute .= " RT " . $this->rt;
        if(!empty($this->getAttribute('rw')))
            $attribute .= " RW " . $this->rw;

        if(empty($this->village)) {
            return null;
        }

        $attribute .= " " . $this->village->title;
        $attribute .= ", " . $this->village->subdistrict->title;
        $attribute .= ", " . $this->village->subdistrict->district->title;
        $attribute .= ", " . $this->village->subdistrict->district->province->title;

        return $attribute;
    }

    /**
     * Get name attribute as title (used for select2)
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->name;
    }

    /**
     * Check if beneficiary is boys (man with age under 18)
     * @return boolean
     */
    public function isBoy()
    {
        if ($this->age < 18 && $this->sex == 'Man') {
            return true;
        }
    }

    /**
     * Check if beneficiary is girls (woman with age under 18)
     * @return boolean
     */
    public function isGirl()
    {
        if ($this->age < 18 && $this->sex == 'Woman') {
            return true;
        }
    }

    /**
     * Check if beneficiary is adult woman (age >= 18)
     * @return boolean
     */
    public function isWoman()
    {
        if ($this->age >= 18 && $this->sex == 'Woman') {
            return true;
        }
    }

    /**
     * Check if beneficiary is adult man (age >= 18)
     * @return boolean
     */
    public function isMan()
    {
        if ($this->age >= 18 && $this->sex == 'Man') {
            return true;
        }
    }

    /**
     * One-to-Many (singular) relations with Children (beneficiary)
     * @return Collection pivot
     */
    public function children()
    {
        // has many `Child`
        return $this->hasMany(Child::class, 'beneficiary_id');
    }

    /**
     * One-to-Many (singular) relations with Spouse (beneficiary)
     * @return Collection pivot
     */
    public function spouse()
    {
        // has many `Spouse`
        return $this->hasMany(Spouse::class, 'beneficiary_id');
    }

    /**
     * Many-to-Many relations with Activity
     * @return Collection pivot
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

    /**
     * Generate unique code for beneficiary
     * @param $beneficiary
     * @return string code
     */
    public function getCodeBeneficiary($beneficiary){
        // generate code beneficiary
        $village_id = $beneficiary->village_id;
        if(empty($beneficiary->village->subdistrict_id))
            dd($village_id);
        $village = $beneficiary->village->subdistrict_id;
        $date_birth = (new DateTime($beneficiary->dateOfBirth))->format('dmY');
        $subdistrict_id = $village;
        $sex_id = $beneficiary->sex_id;
        $sequence_id = $this->getBeneficiarySequence($subdistrict_id);
        // $code = $village_id.'-'.$date_birth.'-'.$sex_id.'-'.$sequence_id;
        $code = $village_id.'-'.$date_birth.'-'.$sex_id.'-'.$sequence_id;

        return $code;
    }
    /**
     * Get next sequential number of beneficiary by subdistrict
     * @param int $subdistrict_id
     * @return int
     */
    public function getBeneficiarySequence($subdistrict_id)
    {

        $collection = $this->whereHas('village', function($query) use($subdistrict_id) {
            $query->where('subdistrict_id', $subdistrict_id);
        })->whereNotNull('code')->get();

        $listOfCodes = array();

        foreach($collection as $data)
        {
            list($village, $date, $sex, $sequence) = explode("-", $data->code);
            array_push($listOfCodes, $sequence);
        }

        return (empty($listOfCodes)) ? 1 : (max($listOfCodes) + 1);
    }

}
