<?php

namespace BusyPHP\wechat\publics\request\account;

use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 长链接转短链接
 * 主要使用场景： 开发者用于生成二维码的原链接（商品、支付二维码等）太长导致扫码速度和成功率下降，将原长链接通过此接口转成短链接再生成二维码将大大提升扫码速度和成功率。
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:50 上午 WeChatShortUrl.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433600
 */
class WeChatShortUrl extends WeChatPublicBaseRequest
{
    protected $path = 'cgi-bin/shorturl?access_token=';
    
    
    /**
     * 设置需要转换的长链接，支持http://、https://、weixin://wxpay 格式的url
     * @param string $url
     */
    public function setLongUrl($url)
    {
        $this->params['long_url'] = $url;
    }
    
    
    /**
     * 执行请求
     * @return string
     * @throws WeChatPublicException
     */
    public function request()
    {
        $this->params['action'] = 'long2short';
        $result                 = parent::request();
        
        return $result['short_url'];
    }
}