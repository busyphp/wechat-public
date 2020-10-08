<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 接收链接消息服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:43 上午 LinkMessageEvent.php $
 */
class LinkMessageEvent extends WeChatPublicBaseEvent
{
    /**
     * 消息ID
     * @var string
     */
    public $msgId;
    
    /**
     * 消息标题
     * @var string
     */
    public $title;
    
    /**
     * 消息描述
     * @var string
     */
    public $description;
    
    /**
     * 消息链接
     * @var string
     */
    public $url;
    
    
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->title       = $data['Title'];
        $this->msgId       = $data['MsgId'];
        $this->description = $data['Description'];
        $this->url         = $data['Url'];
    }
}