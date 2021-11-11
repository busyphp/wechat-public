<?php

namespace BusyPHP\wechat\publics\model;

use BusyPHP\Model;
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
     * @param bool $cache
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function getContent($cache = true)
    {
        $content = $this->getCache($this->key);
        if (!$content || $cache) {
            $info = $this->findInfo($this->key);
            if (!$info) {
                $this->setContent('');
                $info = $this->getInfo($this->key);
            }
            
            $content = unserialize($info['content']);
            $this->setCache($this->key, $content);
        }
        
        return $content;
    }
    
    
    /**
     * 设置配置
     * @param mixed $content
     * @throws DbException
     */
    public function setContent($content)
    {
        $this->addData([
            'id'      => $this->key,
            'content' => serialize($content)
        ], true);
    }
    
    
    public function onChanged(string $method, $id, array $options)
    {
        $this->deleteCache($id);
    }
}