<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 关注/取消关注事件服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午12:29 下午 EventFollowService.php $
 */
class FollowEvent extends WeChatPublicBaseEvent
{
    /**
     * 是否取消关注
     * @var bool
     */
    public $isUnFlow = false;
    
    
    /**
     * 构造器
     * @param array $data 微信数据
     * @param bool  $isUnFlow 是否取消关注
     */
    public function __construct($data, $isUnFlow = false)
    {
        parent::__construct($data);
        
        $this->isUnFlow = $isUnFlow;
    }
}