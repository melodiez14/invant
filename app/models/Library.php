<?php

use Morph\Database\Model;

class Library extends Model
{
    protected $guarded = array();

    protected static $messages = array(
        'upload_id.required' => 'Please choose file to upload.',
        'upload_id.mimes' => 'The upload file must be a file of type: :values',
        'upload_id.max' => 'Maximum upload file size is :max kb'
    );

    protected static $rules = array(
        'title' => 'required|max:255|unique:libraries,title,:id',
        // check constant in LibrariesController
        // 5 for evaluation, 4 for recommendation, 3 for learning agenda = unasigned to communication material, 2 for Project
        'type_id' => 'required|in:1,2,3,4,5',
        'upload_id' => 'required',
        'description' => 'max:255',
        'libraryTags' => 'max:255'
    );

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    /**
     * Many-to-many relations with Libraries model
     * @return Collection of Subtheme
     */
    public function projects()
    {
        return $this->belongsToMany('Project', 'project_library');
    }
    public function learnings()
    {
        return $this->belongsToMany('Learning');
    }

    public function recommendations()
    {
        return $this->belongsToMany(Recommendation::class, 'library_recommendation', 'recommendation_id', 'evaluation_recommendation_id');
    }
    public function evaluations()
    {
        return $this->belongsToMany(Evaluation::class);
    }
}
