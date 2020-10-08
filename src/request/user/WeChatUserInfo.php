<?php

namespace BusyPHP\wechat\publics\request\user;

use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 获取用户基本信息
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午12:06 下午 WeChatUserInfo.php $
 */
class WeChatUserInfo extends WeChatPublicBaseRequest
{
    protected $path   = 'cgi-bin/user/info?access_token=';
    
    protected $method = 'get';
    
    protected $openid;
    
    
    /**
     * 设置用户openid
     * @param string $openid
     */
    public function setOpenid($openid)
    {
        $this->params['openid'] = $openid;
        $this->openid           = $openid;
    }
    
    
    /**
     * 执行获取用户信息
     * @return WeChatUserInfoData
     * @throws WeChatPublicException
     */
    public function request()
    {
        $this->params['lang'] = 'zh_CN';
        $result               = parent::request();
        $res                  = new WeChatUserInfoData();
        
        $res->isFollow = $result['subscribe'] == 1;
        $res->openid   = $this->openid;
        if ($res->isFollow) {
            $res->nickname    = $result['nickname'];
            $res->sex         = $result['sex'];
            $res->city        = $result['city'];
            $res->country     = $result['country'];
            $res->province    = $result['province'];
            $res->language    = $result['language'];
            $res->avatar      = $result['headimgurl'];
            $res->followTime  = $result['subscribe_time'];
            $res->unionid     = isset($result['unionid']) ? $result['unionid'] : null;
            $res->remark      = $result['remark'];
            $res->groupId     = $result['groupid'];
            $res->tagIds      = $result['tagid_list'];
            $res->followScene = $result['subscribe_scene'];
            $res->qrScene     = $result['qr_scene'];
            $res->qrSceneStr  = $result['qr_scene_str'];
        }
        
        return $res;
    }
}
