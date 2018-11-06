<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/5 0005
 * Time: 22:32
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];

    protected $autoWriteTimestamp = true;

    public function getSnapItemAttr($value) {
        if (empty($value)) {
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value) {
        if (empty($value)) {
            return null;
        }
        return json_decode($value);
    }

    public static function getSummaryByUser($uid, $page = 1, $size = 15) {
        $paginateData = self::where('user_id', '=', $uid)->order('create_time desc')->paginate($size, true, ['page' => $page]);
        return $paginateData;
    }
}