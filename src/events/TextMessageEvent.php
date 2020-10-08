<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 接收文本消息服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:44 上午 TextMessageEvent.php $
 */
class TextMessageEvent extends WeChatPublicBaseEvent
{
    /**
     * 消息内容
     * @var string
     */
    public $content;
    
    /**
     * 消息ID
     * @var string
     */
    public $msgId;
    
    
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->content = $data['Content'];
        $this->msgId   = $data['MsgId'];
    }
}