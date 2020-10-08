<?php

namespace BusyPHP\wechat\publics\request\user;

/**
 * 获取用户基本信息结构
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/8 下午3:48 下午 WeChatUserInfoData.php $
 */
class WeChatUserInfoData
{
    /**
     * 用户是否订阅该公众号，false代表此用户没有关注该公众号，拉取不到其余信息
     * @var bool
     */
    public $isFollow = false;
    
    /**
     * 用户的标识，对当前公众号唯一
     * @var string
     */
    public $openid;
    
    /**
     * 用户的昵称
     * @var string
     */
    public $nickname;
    
    /**
     * 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
     * @var int
     */
    public $sex;
    
    /**
     * 用户所在城市
     * @var string
     */
    public $city;
    
    /**
     * 用户所在国家
     * @var string
     */
    public $country;
    
    /**
     * 用户所在省份
     * @var string
     */
    public $province;
    
    /**
     * 用户的语言，简体中文为zh_CN
     * @var string
     */
    public $language;
    
    /**
     * 用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
     * @var string
     */
    public $avatar;
    
    /**
     * 用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间
     * @var int
     */
    public $followTime;
    
    /**
     * 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。
     * @var string
     */
    public $unionid;
    
    /**
     * 公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注
     * @var string
     */
    public $remark;
    
    /**
     *  用户所在的分组ID（兼容旧的用户分组接口）
     * @var string
     */
    public $groupId;
    
    /**
     *  用户被打上的标签ID合集
     * @var array
     */
    public $tagIds;
    
    /**
     * 返回用户关注的渠道来源，
     * ADD_SCENE_SEARCH 公众号搜索，
     * ADD_SCENE_ACCOUNT_MIGRATION 公众号迁移，
     * ADD_SCENE_PROFILE_CARD 名片分享，
     * ADD_SCENE_QR_CODE 扫描二维码，
     * ADD_SCENEPROFILE LINK 图文页内名称点击，
     * ADD_SCENE_PROFILE_ITEM 图文页右上角菜单，
     * ADD_SCENE_PAID 支付后关注，
     * ADD_SCENE_OTHERS 其他
     * @var array
     */
    public $followScene;
    
    /**
     *  二维码扫码场景开 发者自定义
     * @var string
     */
    public $qrScene;
    
    /**
     * 二维码扫码场景描述 开发者自定义
     * @var string
     */
    public $qrSceneStr;
}