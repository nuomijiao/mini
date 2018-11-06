<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/6 0006
 * Time: 14:38
 */

namespace app\api\service;

use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\model\Product as ProductModel;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class WxNotify  extends \WxPayNotify
{
    public function NotifyProcess($data, $config, &$msg) {
        if ($data['result_code'] == 'SUCCESS') {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try {
                $order = OrderModel::where('order_no', '=', $orderNo)->lock(true)->find();
                if ($order->status == 1) {
                    $service = new OrderService();
                    $stockStatus = $service->checkOrderStock($order->id);
                    if ($stockStatus['pass']) {
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($stockStatus);
                    } else {
                        $this->updateOrderStatus($order->id, false);
                    }
                }
                return true;
                Db::commit();
            } catch(Exception $ex) {
                Db::rollback();
                Log::error($ex);
                return false;
            }
        } else {
            return true;
        }
    }

    private function reduceStock($stockStatus) {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
            ProductModel::where('id', '=', $singlePStatus['id'])->setDec('stock', $singlePStatus['count']);
        }
    }

    private function updateOrderStatus($orderID, $success) {
        $status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id', '=', $orderID)->update(['status'=>$status]);
    }
}