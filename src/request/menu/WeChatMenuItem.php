<?php

namespace BusyPHP\wechat\publics\request\menu;

use BusyPHP\exception\VerifyException;

/**
 * 微信菜单Item
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/8 下午3:43 下午 WeChatMenuItem.php $
 */
class WeChatMenuItem
{
    /**
     * 菜单的响应动作类型，view表示网页类型，click表示点击类型，miniprogram表示小程序类型
     * @var string
     */
    public $type;
    
    /**
     * 菜单标题，不超过16个字节，子菜单不超过60个字节
     * @var string
     */
    public $name;
    
    /**
     * click等点击类型必须, 菜单KEY值，用于消息接口推送，不超过128字节
     * @var string
     */
    public $key;
    
    /**
     * view、miniprogram类型必须, 网页 链接，用户点击菜单可打开链接，不超过1024字节。 type为miniprogram时，不支持小程序的老版本客户端将打开本url。
     * @var string
     */
    public $url;
    
    /**
     * media_id类型和view_limited类型必须, 调用新增永久素材接口返回的合法media_id
     * @var string
     */
    public $media_id;
    
    /**
     * miniprogram类型必须, 小程序的appid 仅认证公众号可配置
     * @var string
     */
    public $appid;
    
    /**
     * miniprogram类型必须, 小程序的页面路径
     * @var string
     */
    public $pagepath;
    
    /**
     * 子菜单合集
     * @var WeChatMenuList
     */
    public $sub_button;
    
    
    /**
     * WeChatMenu_Item constructor.
     * @param array $array
     */
    public function __construct($array = [])
    {
        foreach (get_object_vars($this) as $key => $value) {
            if ($key == 'sub_button') {
                if ($array[$key]) {
                    if (isset($array[$key]['list'])) {
                        $this->$key = new WeChatMenuList($array[$key]['list']);
                    } else {
                        $this->$key = new WeChatMenuList($array[$key]);
                    }
                }
            } elseif (isset($array[$key])) {
                $this->$key = $array[$key];
            }
        }
    }
    
    
    /**
     * @param WeChatMenuList $sub_button
     */
    public function setSubButton($sub_button)
    {
        $this->sub_button = $sub_button;
    }
    
    
    /**
     * 创建菜单
     * @param bool   $isSub 是否子菜单
     * @param string $type 菜单类型
     * @param string $name 菜单名称
     * @param string $value 菜单值
     * @param string $appId 小程序AppId
     * @param string $appPath 小程序路径
     * @return $this
     * @throws VerifyException
     */
    public function create($isSub, $type, $name, $value, $appId = '', $appPath = '')
    {
        $name    = trim($name);
        $type    = trim($type);
        $value   = trim($value);
        $appId   = trim($appId);
        $appPath = trim($appPath);
        
        if (!$name) {
            throw new VerifyException(($isSub ? '子' : '主') . '菜单名称不能为空', 'name');
        }
        if ($isSub) {
            if (strlen($name) > 60) {
                throw new VerifyException('子菜单名称长度不能超过60个字节', 'name');
            }
        } else {
            if (strlen($name) > 16) {
                //throw new VerifyException('主菜单名称长度不能超过16个字节', 'name');
            }
        }
        
        $this->name = $name;
        $this->type = $type;
        switch ($type) {
            // 网址跳转
            case WeChatMenu::TYPE_VIEW:
                if (!$value) {
                    throw new VerifyException('请输入跳转的网址', 'value');
                }
                $this->url = $value;
            break;
            
            // 素材
            case WeChatMenu::TYPE_MEDIA_ID:
            case WeChatMenu::TYPE_VIEW_LIMITED:
                if (!$value) {
                    throw new VerifyException('请输入素材id');
                }
                $this->media_id = $value;
            break;
            
            // 小程序
            case WeChatMenu::TYPE_MINI_PROGRAM:
                if (!$value) {
                    throw new VerifyException('请输入不支持小程序的网址', 'value');
                }
                if (!$appId) {
                    throw new VerifyException('请输入小程序appId', 'app_id');
                }
                if (!$appPath) {
                    throw new VerifyException('请输入小程序的页面路径', 'app_path');
                }
                $this->url      = $value;
                $this->appid    = $appId;
                $this->pagepath = $appPath;
            break;
            
            // 无类型
            case '':
                $this->type = null;
            break;
            
            // 单击类型
            default:
                if (!$value) {
                    throw new VerifyException('请输入单击参数', 'value');
                }
                $this->key = $value;
        }
        
        return $this;
    }
    
    
    /**
     * 将结果转换成数组
     * @return array
     */
    public function _toArray()
    {
        $array = [];
        foreach (get_object_vars($this) as $key => $value) {
            if (!isset($value)) {
                continue;
            }
            
            if ($value instanceof WeChatMenuList) {
                $array[$key] = $value->_toArray();
            } else {
                $array[$key] = $value;
            }
        }
        
        return $array;
    }
}