<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/11/2 0002
 * Time: 21:16
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WechatException;
use think\Exception;
use app\api\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code) {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprinf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    public function get($code) {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        } else {
            $loginFail = array_key_exists('error_code', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                $this->grantToken();
            }
        }
    }

    private function processLoginError($wxResult) {
        throw new WechatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }

    private function grantToken($wxResult) {
        //拿到openid
        //数据库里看一下，这个openid是不是已经存在
        //如果存在，则不处理，如果不存在那么新增一条user记录
        //生成令牌，准备缓存数据，写入缓存
        //把令牌返回到客户端去
        //key: 令牌
        //value: wxResult
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user) {
            $uid = $user->id;
        } else {
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachevalue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue) {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');
        $request = cache($key, $value, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    private function prepareCachevalue($wxResult, $uid) {
        $cachedvalue = $wxResult;
        $cachedvalue['uid'] = $uid;
        // scope=16 代表APP用户的权限数值
        $cachedvalue['scope'] = ScopeEnum::User;
        // scope = 32代表CMS(管理员)用户的权限数值
        return $cachedvalue;
    }

    private function newUser($openid) {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }
}