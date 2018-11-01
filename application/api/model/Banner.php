<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/10/25 0025
 * Time: 0:36
 */

namespace app\api\model;


use think\Db;

class Banner extends BaseModel
{

    public function items() {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }


//    protected $table = 'category';
    public static function getBannerById($id) {
//        $result = Db::query("SELECT * FROM `banner_item` WHERE `banner_id` =?",[$id]);
        $result = Db::table('banner_item')->where('banner_id','=',$id)->select();
        return $result;
    }
}