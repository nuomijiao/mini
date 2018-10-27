<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/10/24 0024
 * Time: 0:27
 */

namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePositiveInt;

class Banner
{
    /**
     * 获取指定id的banner信息
     * @url  /banner/:id
     * @http GET
     * @id   banner的id号
     */
    public function getBanner($id){
        (new IDMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerByID($id);
        if (!$banner) {

        }
    }
}