<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 接收视频消息服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:44 上午 VideoMessageEvent.php $
 */
class VideoMessageEvent extends WeChatPublicBaseEvent
{
    /**
     * 消息ID
     * @var string
     */
    public $msgId;
    
    /**
     * 视频消息媒体id，可以调用多媒体文件下载接口拉取数据
     * @var string
     */
    public $mediaId;
    
    /**
     *  视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据
     * @var string
     */
    public $thumbMediaId;
    
    /**
     * 是否小视频
     * @var bool
     */
    public $isShortVideo;
    
    
    public function __construct($data, $isShortVideo = false)
    {
        parent::__construct($data);
        
        $this->mediaId      = $data['MediaId'];
        $this->msgId        = $data['MsgId'];
        $this->thumbMediaId = $data['ThumbMediaId'];
        $this->isShortVideo = $isShortVideo;
    }
}