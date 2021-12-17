<?php

namespace BusyPHP\wechat\publics;

use BusyPHP\wechat\WeChatConfig;
use think\App;

/**
 * 微信公众号基本类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:46 WeChatPublic.php $
 */
abstract class WeChatPublic
{
    use WeChatConfig;
    
    /**
     * 公众号appId
     * @var string
     */
    private $appId;
    
    /**
     * 公众号密钥
     * @var string
     */
    private $appSecret;
    
    /**
     * 服务器token 长度为3-32字符
     * @var string
     */
    private $token;
    
    /**
     * 服务器加密密钥 长度为3-32字符
     * @var string
     */
    private $encodingAESKey;
    
    /**
     * @var App
     */
    protected $app;
    
    
    /**
     * WeChatPublic constructor.
     * @param string $accountId 多账户标识
     */
    public function __construct(string $accountId = '')
    {
        $this->app = App::getInstance();
        
        if (!$accountId) {
            $this->appId          = $this->getWeChatConfig('public.app_id');
            $this->appSecret      = $this->getWeChatConfig('public.app_secret');
            $this->token          = $this->getWeChatConfig('public.token');
            $this->encodingAESKey = $this->getWeChatConfig('public.encoding_aes_key');
        } else {
            $this->appId          = $this->getWeChatConfig("public.multi.{$accountId}.app_id");
            $this->appSecret      = $this->getWeChatConfig('public.multi.{$accountId}.app_secret');
            $this->token          = $this->getWeChatConfig('public.multi.{$accountId}.token');
            $this->encodingAESKey = $this->getWeChatConfig('public.multi.{$accountId}.encoding_aes_key');
        }
    }
    
    
    /**
     * 获取 AppId
     * @return string
     */
    protected function getAppId() : string
    {
        return $this->appId;
    }
    
    
    /**
     * 获取 AppSecret
     * @return string
     */
    protected function getAppSecret() : string
    {
        return $this->appSecret;
    }
    
    
    /**
     * 获取服务器token
     * @return string
     */
    protected function getToken() : string
    {
        return $this->token;
    }
    
    
    /**
     * 获取服务器加密密钥
     * @return string
     */
    protected function getEncodingAESKey() : string
    {
        return $this->encodingAESKey;
    }
}