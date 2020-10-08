<?php

namespace BusyPHP\wechat\publics\model\config;

use BusyPHP\wechat\publics\request\menu\WeChatMenu;
use BusyPHP\wechat\publics\request\menu\WeChatMenuCreate;
use BusyPHP\wechat\publics\request\menu\WeChatMenuDelete;
use BusyPHP\wechat\publics\request\menu\WeChatMenuGet;
use BusyPHP\wechat\publics\model\WechatConfig;
use BusyPHP\wechat\publics\request\menu\WeChatMenuGetResult;
use BusyPHP\wechat\publics\request\menu\WeChatMenuList;
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
        if ($this->getConfigure()->menu->length() < 1 || $must) {
            $get = new WeChatMenuGet();
            $this->setConfigure($get->request());
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
            $obj->menu       = new WeChatMenuList();
            
            return $obj;
        }
        
        return $result;
    }
    
    
    /**
     * 设置菜单数据
     * @param null|WeChatMenuGetResult|WeChatMenuList $value
     * @throws Exception
     */
    public function setConfigure($value)
    {
        if (is_null($value)) {
            $value             = new WeChatMenuGetResult();
            $value->isDisabled = false;
            $value->menu       = new WeChatMenuList();
        } elseif ($value instanceof WeChatMenuList) {
            $saveValue         = $value;
            $value             = new WeChatMenuGetResult();
            $value->isDisabled = false;
            $value->menu       = $saveValue;
        }
        
        if ($value->menu->length() > 0) {
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
        $del->request();
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
        $obj->request();
    }
    
    
    /**
     * 获取菜单值
     * @param array $data 单个菜单数据
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
    
    
    /**
     * 解析菜单数据
     * @param WeChatMenuList $list
     * @return array
     */
    public static function parseList($list)
    {
        $array = $list->_toArray();
        foreach ($array as $i => $r) {
            $r['value'] = self::parseValue($r);
            if (isset($r['sub_button'])) {
                foreach ($r['sub_button'] as $j => $item) {
                    $item['value']       = self::parseValue($item);
                    $r['sub_button'][$j] = $item;
                }
            }
            $array[$i] = $r;
        }
        
        
        return parent::parseList($array);
    }
}