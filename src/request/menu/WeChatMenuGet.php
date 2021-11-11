<?php

namespace BusyPHP\wechat\publics\request\menu;

use BusyPHP\helper\TransHelper;

/**
 * 自定义菜单查询接口
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:31 WeChatMenuGet.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141014
 */
class WeChatMenuGet extends WeChatMenu
{
    protected $path = 'cgi-bin/get_current_selfmenu_info?access_token=';
    
    
    /**
     * 执行查询
     * @return WeChatMenuGetResult
     */
    public function get()
    {
        $result = parent::request();
        
        $res             = new WeChatMenuGetResult();
        $res->isDisabled = TransHelper::toBool($result['is_menu_open']) === false;
        $menu            = [];
        foreach ($result['selfmenu_info']['button'] as $item) {
            $menu[] = new WeChatMenuItem($item);
        }
        $res->menu = $menu;
        
        return $res;
    }
}