<?php

use Morph\Database\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Gd\Imagine;

class Staff extends Model {

    protected $table    = 'staffs';
	protected $fillable = ['name', 'sex_id', 'address', 'email', 'phone', 'photo_id', 'user_id'];

    protected static $rules    = [
        'name'  => 'required|max:255',
        'sex_id'=> 'required|in:1,2',
        'email' => 'required|email|max:255',
        'address'   => 'max:255'
    ];

    /**
     * Listen for save event
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            return $model->validate();
        });
    }
    // protected $with     = ['photo'];

    public function photo()
    {
        return $this->hasOne(Upload::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function learnings()
    {
        return $this->hasMany(Learning::class, 'staff_id');
    }

    public function createThumb(Upload $file, $width, $height, $x, $y)
    {
        $directory  = Config::get('idms.upload_path') . '/usr/avatar';
        $filepath   = $directory . '/' . $file->file_name;

        Imagine::open($filepath)->crop(new Point($x, $y), new Box($width, $height))
            ->save($filepath);

    }

    public static function uploadPhoto(UploadedFile $file, array $options = [])
    {
        $extension  = $file->getClientOriginalExtension();
        $curPath    = $file->getRealPath();
        $filename   = uniqid(date('YmdHis') . "_", true) . "." . $extension;
        $directory  = Config::get('idms.upload_path') . "/staff";

        $imagine    = new Imagine();

        $image      = $imagine->open($curPath);
        $imagesize  = $image->getSize();
        $height     = $imagesize->getHeight();
        $width      = $imagesize->getWidth();

        if ( $height >= $width ) {

            $cropHeight     = $height;

            if (isset($options['y0']) && isset($options['y1'])){

                if( floatval($options['y1']) > $height )
                    throw new \Exception(trans('image.exceeded_endpoint'));

                $cropHeight = floatval($options['y1']) - floatval($options['y0']);
            }


            $cropWidth      = ($cropHeight <= 0) ? $height : $cropHeight;
            $difference     = $cropHeight - $cropWidth;

        } else {

            $cropWidth      = $width;

            if (isset($options['x0']) && isset($options['x1'])) {

                if( floatval($options['x1']) > $width )
                    throw new \Exception(trans('image.exceeded_endpoint'));

                $cropWidth = floatval($options['x1']) - floatval($options['x0']);

            }

            $cropHeight     = ($cropWidth <= 0) ? $width : $cropWidth;
            $difference     = $cropWidth - $cropHeight;

        }

        $xcrop  = (isset($options['x0'])) ? floatval($options['x0']) : ($difference / 2);
        $ycrop  = (isset($options['y0'])) ? floatval($options['y0']) : 0;

        $image->crop(new Point($xcrop, $ycrop), new Box($cropWidth, $cropHeight));

        if ($cropWidth > 320)
            $image->resize(new Box(320,320));

        if (!is_dir($directory))
            mkdir($directory, 0755, true);

        $image->save($directory . "/" . $filename);

        return Upload::create(
            [
                'file_name' => 'staff/' . $filename,
                'client_file_name'  => $file->getClientOriginalName(),
                'extension' => $extension,
                'size'      => $file->getSize(),
                'mime'      => $file->getMimeType(),
                'uploaded_by' => Auth::user()->id
            ]
        );
    }

    public function themes()
    {
        return $this->hasMany(Theme::class, 'staff_id');
    }
}
