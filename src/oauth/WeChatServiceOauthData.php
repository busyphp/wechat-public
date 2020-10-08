<?php

namespace BusyPHP\wechat\publics\oauth;

use BusyPHP\oauth\interfaces\OAuthAppData;
use BusyPHP\wechat\publics\request\user\WeChatUserInfoData;

/**
 * 微信服务端登录数据结构
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/8 下午3:35 下午 WeChatServiceOauthData.php $
 */
class WeChatServiceOauthData extends OAuthAppData
{
    /**
     * @var WeChatUserInfoData
     */
    public $info;
    
    
    public function __construct(WeChatUserInfoData $info)
    {
        $this->info = $info;
    }
    
    
    /**
     * 获取数据
     * @return array
     */
    function getData()
    {
        return get_object_vars($this->info);
    }
}