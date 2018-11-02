<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/2 0002
 * Time: 21:12
 */

namespace app\api\model;


class User extends BaseMode
{
    public static function getByOpenID($openid) {
        $user = self::where('openid', '=', $openid)->find();
        return $user;
    }
}