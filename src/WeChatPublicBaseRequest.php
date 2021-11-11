<?php
declare(strict_types = 1);

namespace BusyPHP\wechat\publics;

use BusyPHP\Cache;
use BusyPHP\helper\HttpHelper;
use Throwable;

/**
 * 微信公众号接口基本类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:01 WeChatPublicBaseRequest.php $
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
            $http = new HttpHelper();
            $http->setTimeout($this->timeout);
            
            switch (strtolower($this->method)) {
                case 'post':
                    if ($files) {
                        foreach ($files as $key => $file) {
                            $http->addFile($key, $file);
                        }
                    }
                    $result = HttpHelper::post($url, $params, $http);
                break;
                case 'get':
                    $result = HttpHelper::get($url, $params, $http);
                break;
                default:
                    $result = HttpHelper::postJSON($url, json_encode($params, JSON_UNESCAPED_UNICODE), $http);
            }
        } catch (Throwable $e) {
            throw new WeChatPublicException("HTTP请求失败: {$e->getMessage()} [{$e->getCode()}]");
        }
        
        return self::parseResult($result);
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
                $http = new HttpHelper();
                $http->setTimeout($this->timeout);
                $result = HttpHelper::get("https://{$this->host}/cgi-bin/token", $params, $http);
            } catch (Throwable $e) {
                throw new WeChatPublicException("HTTP请求失败: {$e->getMessage()} [{$e->getCode()}]");
            }
            
            $result = self::parseResult($result);
            if (empty($result['access_token'])) {
                throw new WeChatPublicException('获取accessToken失败');
            }
            
            $accessToken = $result['access_token'];
            Cache::set(__CLASS__, $key, $accessToken, 7200 - 1000);
        }
        
        return $accessToken;
    }
    
    
    /**
     * 解析数据
     * @param $result
     * @return array
     */
    public static function parseResult($result) : array
    {
        $result = json_decode((string) $result, true) ?: [];
        if (!$result) {
            throw new WeChatPublicException("请求数据异常");
        }
        
        if (isset($result['errcode']) && $result['errcode'] != 0) {
            throw new WeChatPublicException($result['errmsg'] ?? '', intval($result['errcode'] ?? 0));
        }
        
        return $result;
    }
}