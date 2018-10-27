<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/10/27 0027
 * Time: 21:09
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}