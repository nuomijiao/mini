<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/3 0003
 * Time: 13:16
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    protected function checkPrimaryScope() {
        TokenService::needPrimaryScope();
    }

    protected function checkExclusivescope() {
        TokenService::needExclusiveScope();
    }
}