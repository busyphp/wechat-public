<?php

namespace BusyPHP\wechat\publics\request\account;

/**
 * 微信生成带参数的二维码Ticket 返回结构
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/8 下午3:38 下午 WeChatQrCodeResult.php $
 */
class WeChatQrCodeResult
{
    /**
     * 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
     * @var string
     */
    public $url;
    
    /**
     * 该二维码有效时间，以秒为单位。 最大不超过2592000（即30天）。
     * @var string
     */
    public $ticket;
    
    /**
     * 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片z
     * @var string
     */
    public $expire;
    
    /**
     * 微信在线二维码
     * @var string
     */
    public $onlineUrl;
}