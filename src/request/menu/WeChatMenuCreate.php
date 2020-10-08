<?php

namespace BusyPHP\wechat\publics\request\menu;

use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 自定义菜单创建接口
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:58 上午 WeChatMenuCreate.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
 */
class WeChatMenuCreate extends WeChatMenu
{
    protected $path = 'cgi-bin/menu/create?access_token=';
    
    
    /**
     * 设置菜单集合
     * @param WeChatMenuList $menuList
     * @throws WeChatPublicException
     */
    public function setMenuList($menuList)
    {
        if ($menuList->length() > 3) {
            throw new WeChatPublicException('一级菜单最多允许添加3个');
        }
        if ($menuList->length() < 1) {
            throw new WeChatPublicException('一级菜单最少需要添加1个');
        }
        
        while ($menu = $menuList->fetch()) {
            if (isset($menu->sub_button) && $menu->sub_button->length() > 1) {
                if ($menu->sub_button->length() > 5) {
                    throw new WeChatPublicException("菜单{$menu->name}最多允许设置5个子菜单");
                } elseif ($menu->sub_button->length() < 1) {
                    throw new WeChatPublicException("菜单{$menu->name}最少需要设置1个子菜单");
                }
            }
        }
        
        $this->params['button'] = $menuList->_toArray();
    }
    
    
    /**
     * 执行设置
     * @return array
     * @throws WeChatPublicException
     */
    public function request()
    {
        return parent::request();
    }
}