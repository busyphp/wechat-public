<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 接收图片消息服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:42 上午 ImageMessageEvent.php $
 */
class ImageMessageEvent extends WeChatPublicBaseEvent
{
    /**
     * 消息ID
     * @var string
     */
    public $msgId;
    
    /**
     * 图片链接
     * @var string
     */
    public $imageUrl;
    
    /**
     *  图片消息媒体id，可以调用多媒体文件下载接口拉取数据
     * @var string
     */
    public $mediaId;
    
    
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->msgId    = $data['MsgId'];
        $this->imageUrl = $data['PicUrl'];
        $this->mediaId  = $data['MediaId'];
    }
}