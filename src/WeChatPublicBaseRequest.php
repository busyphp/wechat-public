<?php

namespace BusyPHP\wechat\publics;

use BusyPHP\Cache;
use BusyPHP\helper\net\Http;
use Throwable;

/**
 * 微信公众号接口基本类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:46 上午 BasePublic.php $
 */
class WeChatPublicBaseRequest extends WeChatPublic
{
    /**
     * 请求参数
     * @var array
     */
    protected $params = [];
    
    /**
     * 上传的附件
     * @var array
     */
    protected $files = [];
    
    /**
     * 设置超时秒
     * @var int
     */
    protected $timeout = 30;
    
    /**
     * 设置请求路径
     * @var string
     */
    protected $path = '';
    
    /**
     * 请求域名
     * @var string
     */
    protected $host = 'api.weixin.qq.com';
    
    /**
     * 请求方式，默认JSON请求
     * @var string
     */
    protected $method = 'json';
    
    /**
     * 是否HTTPS请求，默认是
     * @var string
     */
    protected $https = true;
    
    
    /**
     * 执行请求
     * @return mixed
     * @throws WeChatPublicException
     */
    protected function request()
    {
        $params       = $this->params;
        $files        = $this->files;
        $this->params = [];
        $this->files  = [];
        
        try {
            $url  = ($this->https ? 'https' : 'http') . "://{$this->host}/{$this->path}{$this->getAccessToken()}";
            $http = new Http();
            $http->setTimeout($this->timeout);
            
            switch (strtolower($this->method)) {
                case 'post':
                    if ($files) {
                        foreach ($files as $key => $file) {
                            $http->addFile($key, $file);
                        }
                    }
                    $result = Http::post($url, $params, $http);
                break;
                case 'get':
                    $result = Http::get($url, $params, $http);
                break;
                default:
                    $result = Http::postJSON($url, json_encode($params, JSON_UNESCAPED_UNICODE), $http);
            }
        } catch (Throwable $e) {
            throw new WeChatPublicException("HTTP请求失败: {$e->getMessage()} [{$e->getCode()}]");
        }
        
        $result = json_decode($result, true);
        if (!$result) {
            throw new WeChatPublicException("请求数据异常");
        }
        
        if (isset($result['errcode']) && $result['errcode'] != '0') {
            throw new WeChatPublicException($result['errmsg'], $result['errcode']);
        }
        
        return $result;
    }
    
    
    /**
     * 获取AccessToken
     * @return string
     * @throws WeChatPublicException
     */
    protected function getAccessToken()
    {
        $key         = 'accessToken';
        $accessToken = Cache::get(__CLASS__, $key);
        if (!$accessToken) {
            $params               = [];
            $params['grant_type'] = 'client_credential';
            $params['appid']      = $this->getAppId();
            $params['secret']     = $this->getAppSecret();
            
            try {
                $http = new Http();
                $http->setTimeout($this->timeout);
                $result = Http::get("https://{$this->host}/cgi-bin/token", $params, $http);
            } catch (Throwable $e) {
                throw new WeChatPublicException("HTTP请求失败: {$e->getMessage()} [{$e->getCode()}]");
            }
            
            $result = json_decode($result, true);
            if (!$result) {
                throw new WeChatPublicException("请求数据异常");
            }
            
            if (isset($result['errcode']) && $result['errcode'] != '0') {
                throw new WeChatPublicException($result['errmsg'], $result['errcode']);
            }
            
            if (!$result['access_token']) {
                throw new WeChatPublicException('获取accessToken失败');
            }
            
            $accessToken = $result['access_token'];
            Cache::set(__CLASS__, $key, $accessToken, 7200 - 1000);
        }
        
        return $accessToken;
    }
}