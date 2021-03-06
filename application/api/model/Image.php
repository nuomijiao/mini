<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/1 0001
 * Time: 14:26
 */

namespace app\api\model;


class Image extends BaseModel
{
    protected $hidden = ['id', 'delete_time', 'update_time', 'from'];

    public function getUrlAttr($value, $data) {
        return $this->prefixImgUrl($value, $data);
    }
}