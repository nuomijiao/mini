<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/2 0002
 * Time: 14:57
 */

namespace app\api\model;


class Category extends BaseModel
{
    public function img() {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }
}