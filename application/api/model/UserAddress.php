<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/3 0003
 * Time: 10:28
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = ['id', 'delete_time', 'user_id'];
}