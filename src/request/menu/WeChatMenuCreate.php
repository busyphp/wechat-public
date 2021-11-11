<?php

namespace BusyPHP\wechat\publics\request\menu;

use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 自定义菜单创建接口
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:37 WeChatMenuCreate.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
 */
class WeChatMenuCreate extends WeChatMenu
{
    protected $path = 'cgi-bin/menu/create?access_token=';
    
    
    /**
     * 设置菜单集合
     * @param WeChatMenuItem[] $menuList
     * @throws WeChatPublicException
     */
    public function setMenuList(array $menuList)
    {
        if (count($menuList) > 3) {
            throw new WeChatPublicException('一级菜单最多允许添加3个');
        }
        if (count($menuList) < 1) {
            throw new WeChatPublicException('一级菜单最少需要添加1个');
        }
        
        foreach ($menuList as $menu) {
            if (isset($menu->sub_button) && count($menu->sub_button) > 1) {
                if (count($menu->sub_button) > 5) {
                    throw new WeChatPublicException("菜单{$menu->name}最多允许设置5个子菜单");
                } elseif (count($menu->sub_button) < 1) {
                    throw new WeChatPublicException("菜单{$menu->name}最少需要设置1个子菜单");
                }
            }
        }
        
        $this->params['button'] = $menuList;
    }
    
    
    /**
     * 执行设置
     * @return array
     */
    public function create()
    {
        return parent::request();
    }
}