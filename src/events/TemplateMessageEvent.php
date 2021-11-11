<?php

namespace BusyPHP\wechat\publics\events;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;

/**
 * 在模版消息发送任务完成后事件推送服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:41 上午 TemplateMessageEvent.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
 */
class TemplateMessageEvent extends WeChatPublicBaseEvent
{
    /**
     * 消息id
     * @var string
     */
    public $msgId;
    
    /**
     * 是否发送成功
     * @var bool
     */
    public $isSuccess = false;
    
    /**
     * 是否被拒收
     * @var bool
     */
    public $isRefuse = false;
    
    /**
     * 是否发送失败
     * @var bool
     */
    public $isError = false;
    
    
    /**
     * 构造器
     * @param array $data 微信数据
     */
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->msgId = $data['MsgID'];
        
        // 发送成功
        if ($data['Status'] == 'success') {
            $this->isSuccess = true;
        } elseif (false !== stripos($data['Status'], 'user block')) {
            $this->isRefuse = true;
        } else {
            $this->isError = true;
        }
    }
}