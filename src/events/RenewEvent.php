<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 年审通知服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:39 上午 RenewEvent.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1455785130
 */
class RenewEvent extends WeChatPublicBaseEvent
{
    /**
     * 过期时间
     * @var int
     */
    public $expireTime = 0;
    
    
    /**
     * 构造器
     * @param array $data 微信数据
     */
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->expireTime = $data['ExpiredTime'];
    }
}