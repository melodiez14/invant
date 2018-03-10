<?php
namespace Morph\Validator;

use Illuminate\Validation\Validator as MainValidator;
use DB;

class Validator extends MainValidator
{
    public function __construct($translator, $data, $rules, $messages = array(), $attributes = array())
    {
        parent::__construct($translator, $data, $rules, $messages = array(), $attributes = array());
    }


    /**
     * Validation for checking the value is using JSON format
     * @param string $attribute
     * @param mixed $value
     * @return boolean
     */
    protected function validateJson($attribute, $value)
    {
        $isJson = function ($string) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        };

        return $isJson($value);
    }

    /**
     * Validation for checking unique value if it's not empty
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return boolean
     */
    protected function validateUniqueIfNotEmpty($attribute, $value, $params)
    {
        if(empty(trim($value)))
            return true;

        list($table, $column) = $params;
        $num = DB::table($table)->where($column, $value)->count();

        return ($num < 1);
    }

    protected function validateMimes($attribute, $value, $parameters)
	{
        $mimes = array(
            'pdf' => array('application/pdf'),
            'doc' => array('application/msword', 'application/vnd.ms-office'),
            'docx'=> array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-office'),
            'ppt' => array('application/vnd.ms-powerpoint', 'application/vnd.ms-office'),
            'pptx'=> array('application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-office'),
            'xls' => array('application/vnd.ms-excel', 'application/vnd.ms-office'),
            'xlsx'=> array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-office'),
            'jpeg'=> array('image/jpeg', 'image/pjpeg'),
            'png' => array('image/png'),
            'mp4' => array('video/mpeg'),
            'mpg' => array('video/mpeg'),
            'mp3' => array('audio/mpeg'),
            'zip' => array('application/zip', 'application/x-compressed', 'application/x-zip-compressed', 'multipart/x-zip'),
            'rar' => array('application/x-rar')
        );

		if ( ! $this->isAValidFileInstance($value))
		{
			return false;
		}
        $stack = array();
        foreach($parameters as $param)
        {
            if(isset($mimes[$param])) {

                foreach($mimes[$param] as $validMime)
                    $stack[] = $validMime;

            }

        }
        
		return $value->getPath() != '' && in_array($value->getMimeType(), array_unique($stack));
	}

    /*protected function validateMimeXls($attribute, $value){
        $FILE = $value->getMimeType();
        dd($FILE);
        $EXCEL_MIMES = array('application/vnd.ms-excel',
            'application/vnd.ms-office',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        return in_array($FILE, $EXCEL_MIMES);
    }*/


}
