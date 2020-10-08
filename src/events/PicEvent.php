<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 发图的事件推送
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:39 上午 EventPicService.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141016
 */
class PicEvent extends WeChatPublicBaseEvent
{
    /**
     * 是否弹出微信相册发图器的事件推送
     * @var bool
     */
    public $isWeChat = false;
    
    /**
     * 是否弹出拍照或者相册发图的事件推送
     * @var bool
     */
    public $isPhotoOrAlbum = false;
    
    /**
     * 弹出系统拍照发图的事件推送
     * @var bool
     */
    public $isSystem = false;
    
    /**
     * 事件KEY值，由开发者在创建菜单时设定
     * @var string
     */
    public $key = '';
    
    /**
     * 图片总数
     * @var int
     */
    public $imageCount = 0;
    
    /**
     * 图片列表
     * @var array
     */
    public $imageList = [];
    
    
    /**
     * 构造器
     * @param array $data 微信数据
     * @param bool  $isWeChat 是否弹出微信相册发图器的事件推送
     * @param bool  $isPhotoOrAlbum 是否弹出拍照或者相册发图的事件推送
     */
    public function __construct($data, $isWeChat = false, $isPhotoOrAlbum = false)
    {
        parent::__construct($data);
        $this->isWeChat       = $isWeChat;
        $this->isPhotoOrAlbum = $isPhotoOrAlbum;
        if (!$this->isWeChat && !$this->isPhotoOrAlbum) {
            $this->isSystem = true;
        } else {
            $this->isSystem = false;
        }
        
        $this->key        = $data['EventKey'];
        $this->imageCount = $data['SendPicsInfo']['Count'];
        $this->imageList  = $data['SendPicsInfo']['PicList'];
    }
}