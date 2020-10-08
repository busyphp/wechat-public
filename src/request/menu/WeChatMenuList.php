<?php

namespace BusyPHP\wechat\publics\request\menu;

use BusyPHP\helper\util\Map;

/**
 * 微信菜单集合
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/8 下午3:41 下午 WeChatMenuList.php $
 * @method $this add(WeChatMenuItem $object)
 * @method WeChatMenuItem fetch()
 */
class WeChatMenuList extends Map
{
    public function __construct($list = [])
    {
        foreach ($list as $menu) {
            $this->add(new WeChatMenuItem($menu));
        }
    }
    
    
    public function _toArray()
    {
        $list = [];
        while ($item = $this->fetch()) {
            $list[] = $item->_toArray();
        }
        
        return $list;
    }
}