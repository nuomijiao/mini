<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/3 0003
 * Time: 9:58
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}