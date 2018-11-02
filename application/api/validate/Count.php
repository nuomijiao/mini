<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/2 0002
 * Time: 11:03
 */

namespace app\api\validate;


use think\Validate;

class Count extends Validate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15',
    ];
}