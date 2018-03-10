<?php

use Morph\Database\Model;

class Notification extends Model {
	protected $fillable = [
        'user_id',
        'remark',
        'url',
        'read_at',
    ];

    public $timestamps = true;

    public function notifable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('read_at', '=', null);
    }

    public function scopeUnreadLimit($query, $limit = 4)
    {
        return $query->where('read_at', '=', null)->take($limit);
    }

    public function getDates()
    {
        return ['created_at', 'updated_at', 'read_at'];
    }

    public function withRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    public function withUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function deliver()
    {
        $this->save();

        return $this;
    }
}
