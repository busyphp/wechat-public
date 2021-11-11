<?php

namespace BusyPHP\wechat\publics\model\config;

use BusyPHP\helper\FilterHelper;
use BusyPHP\wechat\publics\model\WechatConfig;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;

/**
 * 关注时回复配置
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午1:46 下午 WechatReplyConfig.php $
 */
class WechatReplyConfig extends WechatConfig
{
    protected $key = 'reply';
    
    
    public function setContent($content)
    {
        $content = FilterHelper::trim($content);
        parent::setContent($content);
    }
    
    
    /**
     * 获取关注回复内容
     * @return string
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function getFollow() : string
    {
        return $this->getContent()['follow'] ?? '';
    }
    
    
    /**
     * 获取非关键词回复内容
     * @return string
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function getOther() : string
    {
        return $this->getContent()['other'] ?? '';
    }
}