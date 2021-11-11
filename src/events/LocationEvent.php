<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 上报地理位置事件服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:36 上午 EventLocationService.php $
 */
class LocationEvent extends WeChatPublicBaseEvent
{
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
     * 地理位置精度，被动有效
     * @var string
     */
    public $precision;
    
    
    /**
     * LocationMessageEvent constructor.
     * @param array $data 微信数据
     */
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->lng       = floatval($data['Longitude']);
        $this->lat       = floatval($data['Latitude']);
        $this->precision = floatval($data['Precision']);
    }
}