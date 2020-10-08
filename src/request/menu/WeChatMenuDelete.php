<?php

namespace BusyPHP\wechat\publics\request\menu;

use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 自定义菜单删除接口
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:59 上午 WeChatMenuDelete.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141015
 */
class WeChatMenuDelete extends WeChatMenu
{
    protected $path = 'cgi-bin/menu/delete?access_token=';
    
    
    /**
     * 执行删除
     * @return array
     * @throws WeChatPublicException
     */
    public function request()
    {
        return parent::request();
    }
}