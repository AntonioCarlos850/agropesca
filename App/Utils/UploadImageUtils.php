<?php

namespace App\Utils;

class UploadImageUtils {
    public $extension;
    public $name;
    public $temp_name;
    public $filename;
    public $dir = '/uploads/';
    public $field;

    public function __construct(array $data)
    {
        $this->setName($data['name']);
        $this->temp_name = $data['tmp_name'];
        $this->setFilename();

        move_uploaded_file($this->temp_name, ($this->getAbsoluteDir().$this->filename));
    }

    public function getAbsoluteDir(){
        return __DIR__ . '/../..' . $this->dir;
    }

    public function setName($name) {
        $this->name = $name;
        $this->setExtension();
    }

    public function setFilename(){
        $this->filename = date("Y.m.d-H.i.s")."_".Helpers::randomString(4).".".$this->extension;
    }

    public function setExtension(){
        $splitedName = explode(".", $this->name);
        $this->extension = end($splitedName);
    }

    public static function getImageByField($field) :?UploadImageUtils{
        if(isset($_FILES[$field]) && $_FILES[$field]['size']){
            $uploadImageUtils = new UploadImageUtils($_FILES[$field]);

            return $uploadImageUtils;
        }

        return null;
    }
}