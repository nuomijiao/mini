<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/10/25 0025
 * Time: 0:36
 */

namespace app\api\model;


class Banner extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time'];

    public function items() {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }


//    protected $table = 'category';
    public static function getBannerById($id) {
        $banner = self::with(['items.img'])->find($id);
//        $result = Db::query("SELECT * FROM `banner_item` WHERE `banner_id` =?",[$id]);
//        $result = Db::table('banner_item')->where('banner_id','=',$id)->select();
        return $banner;
    }
}