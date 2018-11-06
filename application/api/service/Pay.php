<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/6 0006
 * Time: 0:02
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\service\Token as TokenService;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay
{
    private $orderID;
    private $orderNo;

    function __construct($orderID) {
        if (!$orderID) {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    public function pay() {
        //订单号可能根本就不存在
        //订单号确实是存在的，但是，订单号和当前用户是不匹配的
        //订单有可能已经被支付过
        //进行库存量检测
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']) {
            return $status;
        }

        return $this->makeWxPreOrder($status['orderPrice']);
    }

    public function makeWxPreOrder($totalPrice) {
        $openid = TokenService::getCurrentTokenVar('openid');
        if (!$openid) {
            throw new TokenException();
        }

        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('mini');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        $config = new \WxPayConfig();
        return $this->getPaySignature($config, $wxOrderData);
    }

    private function getPaySignature($config,$wxOrderData) {
        $wxOrder = \WxPayApi::unifiedOrder($config,$wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($config, $wxOrder);
        return $signature;
    }

    private function sign($config, $wxOrder) {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign($config);
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    private function recordPreOrder($wxOrder) {
        OrderModel::where('id', '=', $this->orderID)->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    public function checkOrderValid() {
        $order = OrderModel::where('id', '=', $this->orderID)->find();
        if (!$order) {
            throw new OrderException();
        }
        if (!TokenService::isValidOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003,
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => '80003',
                'code' => 400
            ]);
        }
        $this->orderNo = $order->order_no;
        return true;
    }
}