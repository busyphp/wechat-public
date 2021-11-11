<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 微信认证事件名称认证服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:37 上午 EventNamingService.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1455785130
 */
class NamingEvent extends WeChatPublicBaseEvent
{
    /**
     * 是否命名成功
     * @var bool
     */
    public $isSuccess = false;
    
    /**
     * 过期时间
     * @var int
     */
    public $expireTime = 0;
    
    /**
     * 失败时间
     * @var int
     */
    public $failTime = 0;
    
    /**
     * 失败原因
     * @var string
     */
    public $failReason = '';
    
    
    /**
     * 构造器
     * @param array $data 微信数据
     * @param bool  $isSuccess 是否认证成功
     */
    public function __construct($data, $isSuccess)
    {
        parent::__construct($data);
        
        $this->isSuccess = $isSuccess;
        if ($this->isSuccess) {
            $this->expireTime = $data['ExpiredTime'];
        } else {
            $this->failReason = $data['FailReason'];
            $this->failTime   = $data['FailTime'];
        }
    }
}