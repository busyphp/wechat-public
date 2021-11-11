<?php

namespace BusyPHP\wechat\publics\model\config;

use BusyPHP\helper\FilterHelper;
use BusyPHP\wechat\publics\model\WechatConfig;
use Exception;

/**
 * 关注时回复配置
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午1:46 下午 WechatReplyConfig.php $
 */
class WechatReplyConfig extends WechatConfig
{
    protected $key = 'reply';
    
    
    /**
     * 获取数据
     * @param string $key 获取键
     * @return array|mixed
     * @throws Exception
     */
    public function getConfigure($key = '')
    {
        $value = parent::getConfigure();
        if ($key) {
            return $value[$key];
        }
        
        return $value;
    }
    
    
    /**
     * 设置数据
     * @param array $value
     * @throws Exception
     */
    public function setConfigure($value)
    {
        $value = FilterHelper::trim($value);
        
        parent::setConfigure($value);
    }
    
    
    /**
     * 获取关注回复内容
     * @return string|false
     */
    public function getFollow()
    {
        try {
            $message = $this->getConfigure('follow');
            if (empty($message)) {
                throw new Exception();
            }
            
            return $message;
        } catch (Exception $e) {
            return false;
        }
    }
    
    
    /**
     * 获取非关键词回复内容
     * @return string|false
     */
    public function getOther()
    {
        try {
            $message = $this->getConfigure('other');
            if (empty($message)) {
                throw new Exception();
            }
            
            return $message;
        } catch (Exception $e) {
            return false;
        }
    }
}