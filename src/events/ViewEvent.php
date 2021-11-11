<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 点击菜单跳转链接时的事件推送服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:42 上午 ViewEvent.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141016
 */
class ViewEvent extends WeChatPublicBaseEvent
{
    /**
     * 设置的跳转URL
     * @var string
     */
    public $url;
    
    /**
     * 指菜单ID，如果是个性化菜单，则可以通过这个字段，知道是哪个规则的菜单被点击了
     * @var string
     */
    public $menuID;
    
    
    /**
     * 构造器
     * @param array $data 微信数据
     */
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->url    = $data['EventKey'];
        $this->menuID = isset($data['MenuID']) ? $data['MenuID'] : '';
    }
}