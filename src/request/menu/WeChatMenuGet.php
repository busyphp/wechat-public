<?php

namespace BusyPHP\wechat\publics\request\menu;

use BusyPHP\helper\util\Transform;
use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 自定义菜单查询接口
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:59 上午 WeChatMenuGet.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141014
 */
class WeChatMenuGet extends WeChatMenu
{
    protected $path = 'cgi-bin/get_current_selfmenu_info?access_token=';
    
    
    /**
     * 执行查询
     * @return WeChatMenuGetResult
     * @throws WeChatPublicException
     */
    public function request()
    {
        $result = parent::request();
        
        $res             = new WeChatMenuGetResult();
        $res->isDisabled = Transform::dataToBool($result['is_menu_open']) === false;
        $res->menu       = new WeChatMenuList($result['selfmenu_info']['button']);
        
        return $res;
    }
}