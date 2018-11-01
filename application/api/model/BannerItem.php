<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/1 0001
 * Time: 14:04
 */

namespace app\api\model;


class BannerItem extends BaseModel
{

    protected $hidden = ['id', 'img_id', 'delete_time', 'update_time', 'banner_id'];

    public function img() {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}