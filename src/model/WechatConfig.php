<?php

namespace BusyPHP\wechat\publics\model;

use BusyPHP\exception\ParamInvalidException;
use BusyPHP\Model;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;

/**
 * 微信配置模型
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:43 WechatConfig.php $
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
     * @throws DbException
     */
    public function setConfigure($value)
    {
        $this->initConfigure();
        $this->where('id', '=', $this->key)->saveData(['content' => serialize($value)]);
        
        $this->initConfigure(true);
    }
    
    
    /**
     * 初始化配置
     * @param bool $must
     * @return mixed
     * @throws DbException
     * @throws DataNotFoundException
     */
    private function initConfigure($must = false)
    {
        if (!$this->key) {
            throw new ParamInvalidException('key');
        }
        
        $info = $this->getCache($this->key);
        if (!$info || $must) {
            $info = $this->where('id', '=', $this->key)->findInfo();
            if (!$info) {
                $this->addData(['id' => $this->key]);
                $info = $this->getInfo($this->key);
            }
            
            $info['content'] = unserialize($info['content']);
            $this->setCache($this->key, $info);
        }
        
        return $info;
    }
}