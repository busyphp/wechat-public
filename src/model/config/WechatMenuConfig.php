<?php

namespace BusyPHP\wechat\publics\model\config;

use BusyPHP\wechat\publics\model\WechatConfig;
use BusyPHP\wechat\publics\request\menu\WeChatMenuCreate;
use BusyPHP\wechat\publics\request\menu\WeChatMenuDelete;
use BusyPHP\wechat\publics\request\menu\WeChatMenuGet;
use BusyPHP\wechat\publics\request\menu\WeChatMenuGetResult;
use BusyPHP\wechat\publics\request\menu\WeChatMenuItem;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;

/**
 * 微信菜单配置
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 下午6:32 WechatMenuConfig.php $
 */
class WechatMenuConfig extends WechatConfig
{
    protected $key = 'menu';
    
    
    /**
     * 获取菜单数据
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function getMenu() : WeChatMenuGetResult
    {
        $result = $this->getContent();
        if (!$result instanceof WeChatMenuGetResult) {
            return new WeChatMenuGetResult();
        }
        
        return $result;
    }
    
    
    /**
     * 编辑菜单
     * @param WeChatMenuItem[] $value
     * @throws DbException
     */
    public function editMenu(array $value)
    {
        $res       = $this->getMenu();
        $res->menu = $value;
        $this->setContent($res);
    }
    
    
    /**
     * 停用菜单
     * @throws DbException
     */
    public function clear()
    {
        (new WeChatMenuDelete)->delete();
        
        $res           = $this->getMenu();
        $res->disabled = (new WeChatMenuGet())->get()->disabled;
        $this->setContent($res);
    }
    
    
    /**
     * 发布菜单
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function publish()
    {
        $obj = new WeChatMenuCreate();
        $obj->setMenuList($this->getMenu()->menu);
        $obj->create();
    }
    
    
    /**
     * 同步菜单
     * @throws DbException
     */
    public function sync()
    {
        $this->setContent((new WeChatMenuGet())->get());
    }
}