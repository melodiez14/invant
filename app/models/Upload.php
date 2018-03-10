<?php

use Morph\Database\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Upload extends Eloquent {

    protected $table    = 'uploads';
    protected $fillable = [
      'id',
	    'file_name',
        'client_file_name',
        'extension',
        'size',
        'mime',
        'upload_by'
    ];

    /*protected $rules    = [
        'id'        => 'required',
        'file_name' => 'required|max:255|unique:uploads,file_name',
        'client_file_name'  => 'required|max:255',
        'extension' => 'required|max:5',
        'size'      => 'required|min:1',
        'mime'      => 'required|max:255',
        'uploaded_by' => 'required'
    ];*/

    public function uploader()
    {
        return $this->belongsTo(User::class, 'upload_by');
    }

    public static function copy($file, $dir = null)
    {
        $extension= $file->getClientOriginalExtension();
        $filename = uniqid(date('YmdHis') . "_", true) . "." . $extension;
        $directory= Config::get('idms.upload_path');

        if ( !is_null($dir) )
            $directory = rtrim($directory, "/") . "/" . $dir;

        $instance = new static (
            [
                'file_name' => $filename,
                'client_file_name'  => $file->getClientOriginalName(),
                'extension' => $extension,
                'size'      => $file->getSize(),
                'mime'      => $file->getMimeType(),
                'upload_by' => Auth::user()->id
            ]
        );

        $file->move($directory, $filename);
        $instance->save();

        return $instance->id;
    }
}
