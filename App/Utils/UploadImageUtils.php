<?php

namespace App\Utils;

class UploadImageUtils {
    public $ext;
    public $name;
    public $temp_name;
    public $filename;
    public $dir;
    public $field;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->temp_name = $data['tmp_name'];

        $this->ext = strtolower(substr($this->name,-4));
        $this->filename = date("Y.m.d-H.i.s")."_".Helpers::randomString(4).$this->ext;
        $this->dir = __DIR__. '/../../uploads/';
        move_uploaded_file($this->temp_name, ($this->dir.$this->filename));
    }

    public static function getImageByField($field) :?UploadImageUtils{
        if(isset($_FILES[$field])){
            $uploadImageUtils = new UploadImageUtils($_FILES[$field]);

            return $uploadImageUtils;
        }

        return null;
    }
}