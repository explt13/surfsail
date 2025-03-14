<?php
namespace app\models;

use nosmi\base\Model;

abstract class AppModel extends Model
{

    protected function setDefinedAttributes($data){
        foreach ($this->attributes as $k => $v) {
            if (isset($data[$k])) {
                $this->attributes[$k] = $data[$k];
            }
        }
    }
}