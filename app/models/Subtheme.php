<?php

class Subtheme extends \Eloquent {
    protected $table = 'subthemes';
    protected $fillable = ['title', 'theme_id', 'description'];

    /**
         * Inverse of One-to-Many relations with Theme
         * @return Theme
         */
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }

    /**
     * One-to-Many relations with Project
     * @return Collection of Project
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * Many-to-Many relations with Activity
     * @return Collection of Activity
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

    /**
     * has Many relations with Opportunity
     * @return Collection of Opportunities
     */
    public function opportunities()
    {
        return $this->belongsToMany('Opportunity');
    }

    public function learnings(){
        return $this->belongsToMany('Learning');
    }

    public function approvals()
    {
        return $this->belongsToMany('Opportunity');
    }
}
