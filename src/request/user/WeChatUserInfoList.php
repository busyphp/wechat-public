<?php

namespace BusyPHP\wechat\publics\request\user;

use BusyPHP\helper\FilterHelper;
use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 批量获取用户基本信息
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:15 WeChatUserInfoList.php $
 */
class WeChatUserInfoList extends WeChatPublicBaseRequest
{
    protected $path = 'cgi-bin/user/info/batchget?access_token=';
    
    
    /**
     * 设置openid，最多100个
     * @param array|string $openid
     */
    public function setOpenid($openid)
    {
        if (!is_array($openid)) {
            $openid = [$openid];
        }
        $openid = FilterHelper::trimArray($openid);
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
     * @return WeChatUserInfoData[]
     */
    public function get() : array
    {
        $result = $this->request();
        $list   = [];
        foreach ($result['user_info_list'] as $vo) {
            $item           = new WeChatUserInfoData();
            $item->isFollow = $vo['subscribe'] == 1;
            $item->openid   = $vo['openid'];
            if ($item->isFollow) {
                $item->nickname    = $vo['nickname'];
                $item->sex         = $vo['sex'];
                $item->city        = $vo['city'];
                $item->country     = $vo['country'];
                $item->province    = $vo['province'];
                $item->language    = $vo['language'];
                $item->avatar      = $vo['headimgurl'];
                $item->followTime  = $vo['subscribe_time'];
                $item->unionid     = isset($vo['unionid']) ? $vo['unionid'] : null;
                $item->remark      = $vo['remark'];
                $item->groupId     = $vo['groupid'];
                $item->tagIds      = $vo['tagid_list'];
                $item->followScene = $vo['subscribe_scene'];
                $item->qrScene     = $vo['qr_scene'];
                $item->qrSceneStr  = $vo['qr_scene_str'];
            }
            $list[] = $item;
        }
        
        return $list;
    }
}
