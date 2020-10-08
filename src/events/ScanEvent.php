<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 扫描带参数二维码事件
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:40 上午 ScanEvent.php $
 */
class ScanEvent extends WeChatPublicBaseEvent
{
    /**
     * 是否取消关注
     * @var bool
     */
    public $isFlow = false;
    
    /**
     * 扫码携带参数
     * @var string
     */
    public $value;
    
    /**
     * 二维码的ticket，可用来换取二维码图片
     * @var string
     */
    public $ticket;
    
    
    /**
     * 构造器
     * @param array  $data 微信数据
     * @param bool   $isFlow 是否执行了关注
     * @param string $value 扫码携带的参数
     */
    public function __construct($data, $isFlow = false, $value = '')
    {
        parent::__construct($data);
        
        $this->isFlow = $isFlow;
        $this->value  = $value;
        $this->ticket = $data['Ticket'];
    }
}