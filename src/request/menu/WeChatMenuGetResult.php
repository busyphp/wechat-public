<?php

namespace BusyPHP\wechat\publics\request\menu;

/**
 * 自定义菜单查询接口返回结构
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/8 下午3:44 下午 WeChatMenuGetResult.php $
 */
class WeChatMenuGetResult
{
    /**
     * 是否禁止本地使用
     * @var bool
     */
    public $isDisabled = false;
    
    /**
     * 菜单数据
     * @var WeChatMenuList
     */
    public $menu;
}