<?php

namespace BusyPHP\wechat\publics\model;

use BusyPHP\exception\ParamInvalidException;
use BusyPHP\exception\SQLException;
use BusyPHP\Model;
use Exception;


/**
 * 微信配置模型
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午10:39 上午 WechatConfig.php $
 */
class WechatConfig extends Model
{
    public $name = 'wechat_config';
    
    /**
     * 配置Key
     * @var string
     */
    protected $key;
    
    
    /**
     * 获取配置
     * @return mixed
     * @throws Exception
     */
    public function getConfigure()
    {
        $info = $this->initConfigure();
        
        return $info['content'];
    }
    
    
    /**
     * 设置配置
     * @param mixed $value
     * @throws Exception
     */
    public function setConfigure($value)
    {
        $this->initConfigure();
        if (false === $this->where('id', '=', $this->key)->saveData(['content' => serialize($value)])) {
            throw new SQLException("修改配置{$this->key}失败", $this);
        }
        
        $this->initConfigure(true);
    }
    
    
    /**
     * 初始化配置
     * @param bool $must
     * @return mixed
     * @throws Exception
     */
    private function initConfigure($must = false)
    {
        if (!$this->key) {
            throw new ParamInvalidException('需要配置key');
        }
        
        $info = $this->getCache($this->key);
        if (!$info || $must) {
            $info = $this->where('id', '=', $this->key)->findData();
            if (!$info) {
                if (!$this->addData(['id' => $this->key])) {
                    throw new SQLException("插入配置{$this->key}失败", $this);
                }
                
                $info = $this->findData($this->key);
            }
            
            $info['content'] = unserialize($info['content']);
            $this->setCache($this->key, $info);
        }
        
        return $info;
    }
}