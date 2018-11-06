<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/5 0005
 * Time: 15:06
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 80000;
}