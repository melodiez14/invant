<?php
use Morph\Database\Model;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table= 'users';
	protected $fillable = ['username', 'password', 'rolegroup_id'];
	protected $hidden = array('password', 'remember_token');

    public function rolegroup() {
        return $this->belongsTo(Rolegroup::class, 'rolegroup_id');
    }

    public function files() {
        return $this->hasMany(Upload::class, 'uploaded_by');
    }
}
