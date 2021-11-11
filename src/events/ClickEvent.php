<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 自定义菜单事件服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午12:29 下午 EventClickService.php $
 */
class ClickEvent extends WeChatPublicBaseEvent
{
    /**
     * @var string 事件KEY值，与自定义菜单接口中KEY值对应
     */
    public $key;
    
    
    /**
     * 构造器
     * @param array $data 微信数据
     */
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->key = $data['EventKey'];
    }
}
