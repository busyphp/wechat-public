<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 扫码推事件服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:39 上午 ScanCodeEvent.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141016
 */
class ScanCodeEvent extends WeChatPublicBaseEvent
{
    /**
     * 是否等待回复消息
     * @var bool
     */
    public $isWait = false;
    
    /**
     * 事件KEY值，由开发者在创建菜单时设定
     * @var string
     */
    public $key;
    
    /**
     * 扫描类型，一般是qrcode
     * @var string
     */
    public $type;
    
    /**
     * 扫描结果，即二维码对应的字符串信息
     * @var string
     */
    public $result;
    
    
    /**
     * 构造器
     * @param array $data 微信数据
     * @param bool  $isWait 是否等待消息回复
     */
    public function __construct($data, $isWait = false)
    {
        parent::__construct($data);
        
        $this->isWait = $isWait;
        $this->type   = $data['ScanCodeInfo']['ScanType'];
        $this->result = $data['ScanCodeInfo']['ScanResult'];
        $this->key    = $data['EventKey'];
    }
}