<?php

namespace BusyPHP\wechat\publics\oauth;

use BusyPHP\exception\ParamInvalidException;
use BusyPHP\oauth\interfaces\OAuthApp;
use BusyPHP\oauth\interfaces\OAuthInfo;
use BusyPHP\oauth\OAuthType;

/**
 * 微信用户关注Oauth
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/22 下午6:57 下午 WeChatServiceOauth.php $
 * @property WeChatServiceOauthData $data
 */
class WeChatServiceOauth extends OAuthApp
{
    /**
     * @var string
     */
    private $avatar;
    
    
    /**
     * 获取登录类型
     * @return string
     */
    public function getType()
    {
        return OAuthType::TYPE_WECHAT_PUBLIC;
    }
    
    
    /**
     * 获取厂商类型
     * @return string
     */
    public function getUnionType()
    {
        return OAuthType::COMPANY_WECHAT;
    }
    
    
    /**
     * 获取用户信息，该方法可能会多次触发，请自行处理重复处理锁
     * @return OAuthInfo
     * @throws ParamInvalidException
     */
    public function onGetInfo()
    {
        $info = new OAuthInfo($this);
        $info->setOpenId($this->data->info->openid);
        $info->setUnionId($this->data->info->unionid);
        $info->setNickname($this->data->info->nickname);
        $info->setAvatar($this->data->info->avatar);
        $info->setSex($this->data->info->sex);
        $info->setUserInfo($this->data->getData());
        $this->avatar = $info->getAvatar();
        
        return $info;
    }
    
    
    /**
     * 验证是否可以更新头像
     * @param string $avatar 用户已设置的头像地址
     * @return bool
     */
    public function canUpdateAvatar($avatar) : bool
    {
        // 无头像需要更新
        if (!$avatar) {
            return true;
        }
        
        // 无需更新
        if ($avatar == $this->avatar) {
            return false;
        }
        
        // 如果用户已设置的头像包含微信域名，则可以更新头像
        if (stripos($avatar, 'qlogo.cn')) {
            return true;
        }
        
        return false;
    }
}
    

