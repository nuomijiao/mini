<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/2 0002
 * Time: 14:56
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category extends BaseController
{
    public function getAllCategories() {
//        $categories = CategoryModel::with('img')->select();
        $categories = CategoryModel::all([], 'img');
        if ($categories->isEmpty()) {
            throw new CategoryException();
        }
        return $categories;
    }
}