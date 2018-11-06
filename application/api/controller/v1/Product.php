<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/2 0002
 * Time: 10:59
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product extends BaseController
{

    /*
     * @url recent?count=num
     */
    public function getRecent($count = 15) {
        (new Count())->gocheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    /*
     * @url  by_category?id=num
     */
    public function getAllInCategory($id) {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public function getOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if (!$product) {
            throw new ProductException();
        }
        return $product;
    }

    public function deleteOne($id) {

    }

}