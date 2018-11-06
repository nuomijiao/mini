<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/5 0005
 * Time: 23:55
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
use think\Loader;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder'],
    ];

    public function getPreOrder($id = '') {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    public function receiveNotify() {
        //通知频率为15/15/30/180/1800/1800/1800/1800/3600，单位：秒
        // 1、检查库存量，超卖
        // 2、更新这个订单的status状态
        // 3、减库存
        // 如果成功处理，我们返回微信成功处理的信息，否则，我们需要返回没有成功处理。
        // 特点：post; xml格式； 回调url不会携带参数（问号）

        $notify = new WxNotify();
        $config = new \WxPayConfig();
        $notify->Handle($config);
    }
}