<?php

namespace BusyPHP\wechat\publics\request\user;

use BusyPHP\helper\util\Filter;
use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 批量获取用户基本信息
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午12:06 下午 WeChatUserInfoList.php $
 */
class WeChatUserInfoList extends WeChatPublicBaseRequest
{
    protected $path = 'cgi-bin/user/info/batchget?access_token=';
    
    
    /**
     * 设置openid，最多100个
     * @param array|string $openid
     * @throws WeChatPublicException
     */
    public function setOpenid($openid)
    {
        if (!is_array($openid)) {
            $openid = [$openid];
        }
        $openid = Filter::trimArray($openid);
        if (count($openid) > 100) {
            throw new WeChatPublicException('每次最多允许拉取100个用户');
        }
        
        $this->params['user_list'] = [];
        foreach ($openid as $value) {
            $this->params['user_list'][] = [
                'openid' => $value,
                'lang'   => 'zh_CN'
            ];
        }
    }
    
    
    /**
     * 执行获取用户信息
     * @return WeChatUserInfoListMap
     * @throws WeChatPublicException
     */
    public function request()
    {
        $result = parent::request();
        $map    = new WeChatUserInfoListMap();
        foreach ($result['user_info_list'] as $item) {
            $res           = new WeChatUserInfoData();
            $res->isFollow = $item['subscribe'] == 1;
            $res->openid   = $item['openid'];
            if ($res->isFollow) {
                $res->nickname    = $item['nickname'];
                $res->sex         = $item['sex'];
                $res->city        = $item['city'];
                $res->country     = $item['country'];
                $res->province    = $item['province'];
                $res->language    = $item['language'];
                $res->avatar      = $item['headimgurl'];
                $res->followTime  = $item['subscribe_time'];
                $res->unionid     = isset($item['unionid']) ? $item['unionid'] : null;
                $res->remark      = $item['remark'];
                $res->groupId     = $item['groupid'];
                $res->tagIds      = $item['tagid_list'];
                $res->followScene = $item['subscribe_scene'];
                $res->qrScene     = $item['qr_scene'];
                $res->qrSceneStr  = $item['qr_scene_str'];
            }
            $map->add($res);
        }
        
        return $map;
    }
}
