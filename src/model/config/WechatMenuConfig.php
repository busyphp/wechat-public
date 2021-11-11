<?php

namespace BusyPHP\wechat\publics\model\config;

use BusyPHP\wechat\publics\request\menu\WeChatMenu;
use BusyPHP\wechat\publics\request\menu\WeChatMenuCreate;
use BusyPHP\wechat\publics\request\menu\WeChatMenuDelete;
use BusyPHP\wechat\publics\request\menu\WeChatMenuGet;
use BusyPHP\wechat\publics\model\WechatConfig;
use BusyPHP\wechat\publics\request\menu\WeChatMenuGetResult;
use BusyPHP\wechat\publics\request\menu\WeChatMenuItem;
use Exception;

/**
 * 微信菜单配置
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午10:49 上午 WeMenuConfig.php $
 */
class WechatMenuConfig extends WechatConfig
{
    protected $key = 'menu';
    
    
    /**
     * 初始化菜单数据
     * @param bool $must 是否强制初始化
     * @throws Exception
     */
    public function initConfigure($must = false)
    {
        if (count($this->getConfigure()->menu) < 1 || $must) {
            $get = new WeChatMenuGet();
            $this->setConfigure($get->get());
        }
    }
    
    
    /**
     * 获取菜单数据
     * @return mixed
     * @throws Exception
     */
    public function getConfigure() : WeChatMenuGetResult
    {
        $result = parent::getConfigure();
        if (!$result instanceof WeChatMenuGetResult) {
            $obj             = new WeChatMenuGetResult();
            $obj->isDisabled = false;
            $obj->menu       = [];
            
            return $obj;
        }
        
        return $result;
    }
    
    
    /**
     * 设置菜单数据
     * @param null|WeChatMenuGetResult|WeChatMenuItem[] $value
     * @throws Exception
     */
    public function setConfigure($value)
    {
        if (is_null($value)) {
            $value             = new WeChatMenuGetResult();
            $value->isDisabled = false;
            $value->menu       = [];
        } elseif (is_array($value)) {
            $saveValue         = $value;
            $value             = new WeChatMenuGetResult();
            $value->isDisabled = false;
            $value->menu       = $saveValue;
        }
        
        if (count($value->menu) > 0) {
            $obj = new WeChatMenuCreate();
            $obj->setMenuList($value->menu);
        }
        
        parent::setConfigure($value);
    }
    
    
    /**
     * 删除微信自定义菜单
     * @throws Exception
     */
    public function clear()
    {
        $del = new WeChatMenuDelete();
        $del->delete();
        $this->initConfigure(true);
    }
    
    
    /**
     * 发布菜单
     * @throws Exception
     */
    public function publish()
    {
        $obj = new WeChatMenuCreate();
        $obj->setMenuList($this->getConfigure()->menu);
        $obj->create();
    }
    
    
    /**
     * 获取菜单值
     * @param WeChatMenuItem $data 单个菜单数据
     * @return string
     */
    public static function parseValue($data)
    {
        switch ($data['type']) {
            case WeChatMenu::TYPE_VIEW:
                return $data['url'];
            case WeChatMenu::TYPE_MEDIA_ID:
            case WeChatMenu::TYPE_VIEW_LIMITED:
                return $data['media_id'];
            default:
                return $data['key'];
        }
    }
    
    
    protected function onParseBindList(array &$list)
    {
        foreach ($list as $i => $r) {
            $r['value'] = self::parseValue($r);
            if ($r->sub_button) {
                foreach ($r->sub_button as $j => $item) {
                    $item['value']     = self::parseValue($item);
                    $r->sub_button[$j] = $item;
                }
            }
            
            $list[$i] = $r;
        }
    }
}