<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 接收地理位置消息服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:43 上午 LocationMessageEvent.php $
 */
class LocationMessageEvent extends WeChatPublicBaseEvent
{
    /**
     * 消息ID，主动有效
     * @var string
     */
    public $msgId;
    
    /**
     * 缩放级别，主动有效
     * @var int
     */
    public $scale;
    
    /**
     * 地理位置信息，主动有效
     * @var string
     */
    public $address;
    
    /**
     * 经度
     * @var double
     */
    public $lng;
    
    /**
     * 纬度
     * @var double
     */
    public $lat;
    
    
    /**
     * LocationMessageEvent constructor.
     * @param array $data 微信数据
     */
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->msgId   = $data['MsgId'];
        $this->lng     = floatval($data['Location_Y']);
        $this->lat     = floatval($data['Location_X']);
        $this->scale   = intval($data['Scale']);
        $this->address = $data['Label'];
    }
}