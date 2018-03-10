<?php

class Meta extends Eloquent {

    /**
     * Class atribute to define sex and prefix
     * @var array
     */
    protected $SEX = array(
        ['id'=>1, 'title'=>'Man', 'prefix'=>'Mr.'],
        ['id'=>2, 'title'=>'Woman', 'prefix'=>'Mrs.']
    );

    /**
     * Mass Assignment
     * @var array
     */
    protected $fillable = array('user_id', 'sex_id', 'address', 'phone',
        'photo_id');

    /**
     * Validation
     * @var array
     */
    public static $rules = array(
        'user_id'=>'required',
        'sex_id' => 'required|in:1,2',
        'photo_id' => 'image|max:2048',
        'address'   => 'max:255',
        'phone' => 'max:255'
    );

    /**
     * Custom attributes
     * @var array
     */
    protected $appends = array('email', 'first_name', 'last_name', 'full_name',
        'sex', 'prefix');

    /**
     * Inverse One-to-One relations with User
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * One-to-One relations to Upload
     * @return Upload
     */
    public function photo()
    {
        return $this->belongsTo(Upload::class, 'photo_id');
    }

    /**
     * Accessor for sex attribute
     * @return string
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
     * Accessor for prefix attribute
     * @return string
     */
    public function getPrefixAttribute()
    {
        foreach ($this->SEX as $sex) {
            if ($this->sex_id == $sex['id']) {
                return $sex['prefix'];
            }
        }
    }

    /**
     * Accessor for first name
     * @return string
     */
    public function getFirstNameAttribute()
    {
        return $this->user->first_name;
    }

    /**
     * Accessor for last name
     * @return string
     */
    public function getLastNameAttribute()
    {
        return $this->user->last_name;
    }

    /**
     * Accessor for full name
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Accessor for email
     * @return string
     */
    public function getEmailAttribute()
    {
        return $this->user->email;
    }

}
