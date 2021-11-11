<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 接收语音消息服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:45 上午 VoiceMessageEvent.php $
 */
class VoiceMessageEvent extends WeChatPublicBaseEvent
{
    /**
     * 消息ID
     * @var string
     */
    public $msgId;
    
    /**
     * 语音消息媒体id，可以调用多媒体文件下载接口拉取数据
     * @var string
     */
    public $mediaId;
    
    /**
     * 语音格式，如amr，speex等
     * @var string
     */
    public $format;
    
    
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->mediaId = $data['MediaId'];
        $this->msgId   = $data['MsgId'];
        $this->format  = $data['Format'];
    }
}