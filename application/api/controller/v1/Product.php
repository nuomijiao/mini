<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/2 0002
 * Time: 10:59
 */

namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\lib\exception\ProductException;

class Product
{
    public function getRecent($count = 15) {
        (new Count())->gocheck();
        $products = ProductModel::getMostRecent($count);
        if (!$products) {
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

}