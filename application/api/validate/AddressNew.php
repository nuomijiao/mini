<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/3 0003
 * Time: 9:04
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' =>'require|isMobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];
}