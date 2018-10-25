<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/10/25 0025
 * Time: 1:08
 */

namespace app\lib\exception;

use think\Exception;
use think\exception\Handle;
use think\Request;

class ExceptionHandler extends  Handle
{
    private $code;
    private $msg;
    private $errorCode;
    //需要返回客户端当前请求的URL路径

    public function render(Exception $e) {
        if ($e instanceof BaseException) {
            //如果是自定义异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            $this->code = 500;
            $this->msg = '服务器内部错误，不想告诉你';
            $this->errorCode = 999;
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->code,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result, $this->code);
    }
}