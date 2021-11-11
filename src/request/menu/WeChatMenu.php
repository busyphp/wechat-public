<?php

namespace BusyPHP\wechat\publics\request\menu;

use BusyPHP\helper\TransHelper;
use BusyPHP\Model;
use BusyPHP\wechat\publics\WeChatPublicBaseRequest;

/**
 * 自定义菜单基本类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:58 上午 WeChatMenu.php $
 */
class WeChatMenu extends WeChatPublicBaseRequest
{
    //+--------------------------------------
    //| 菜单类型
    //+--------------------------------------
    /**
     * 点击推事件
     * @desc 用户点击菜单按钮后，微信服务器会通过消息接口推送消息类型为event的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互
     * @placeholder 请输入事件名称
     * @hide false
     * @label 事件名称
     * @var string
     */
    const TYPE_CLICK = 'click';
    
    /**
     * 跳转URL
     * @desc 用户点击菜单按钮后，微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息
     * @placeholder 请输入网页URL
     * @hide false
     * @label 网页URL
     * @var string
     */
    const TYPE_VIEW = 'view';
    
    /**
     * 扫码推事件
     * @desc 用户点击菜单按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL），且会将扫码的结果传给开发者，开发者可以下发消息
     * @hide true
     * @var string
     */
    const TYPE_SCAN_CODE_PUSH = 'scancode_push';
    
    /**
     * 扫码并等待系统回复
     * @desc 用户点击菜单按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者，同时收起扫一扫工具，然后弹出“消息接收中”提示框，随后可能会收到开发者下发的消息
     * @hide true
     * @var string
     */
    const TYPE_SCAN_CODE_WAIT_MSG = 'scancode_waitmsg';
    
    /**
     * 系统拍照
     * @desc 用户点击菜单按钮后，微信客户端将调起系统相机，完成拍照操作后，会将拍摄的相片发送给开发者，并推送事件给开发者，同时收起系统相机，随后可能会收到开发者下发的消息
     * @hide true
     * @var string
     */
    const TYPE_PIC_SYS_PHOTO = 'pic_sysphoto';
    
    /**
     * 拍照或者相册
     * @desc 用户点击菜单按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”。用户选择后即走其他两种流程
     * @hide true
     * @var string
     */
    const TYPE_PIC_PHOTO_OR_ALBUM = 'pic_photo_or_album';
    
    /**
     * 微信相册
     * @desc 用户点击菜单按钮后，微信客户端将调起微信相册，完成选择操作后，将选择的相片发送给开发者的服务器，并推送事件给开发者，同时收起相册，随后可能会收到开发者下发的消息
     * @hide true
     * @var string
     */
    const TYPE_PIC_WEIXIN = 'pic_weixin';
    
    /**
     * 地理位置
     * @desc 用户点击菜单按钮后，微信客户端将调起地理位置选择工具，完成选择操作后，将选择的地理位置发送给开发者的服务器，同时收起位置选择工具，随后可能会收到开发者下发的消息
     * @hide true
     * @var string
     */
    const TYPE_LOCATION_SELECT = 'location_select';
    
    /**
     * 下发微信素材(除文本消息)
     * @desc 用户点击菜单按钮后，微信服务器会将开发者填写的永久素材id对应的素材下发给用户，永久素材类型可以是图片、音频、视频、图文消息。请注意：永久素材id必须是在“素材管理/新增永久素材”接口上传后获得的合法id
     * @hide false
     * @placeholder 请输入素材ID
     * @label 素材ID
     * @var string
     */
    const TYPE_MEDIA_ID = 'media_id';
    
    /**
     * 跳转微信图文URL
     * @desc 用户点击菜单按钮后，微信客户端将打开开发者在按钮中填写的永久素材id对应的图文消息URL，永久素材类型只支持图文消息。请注意：永久素材id必须是在“素材管理/新增永久素材”接口上传后获得的合法id。
     * @hide false
     * @label 素材ID
     * @placeholder 请输入素材ID
     * @var string
     */
    const TYPE_VIEW_LIMITED = 'view_limited';
    
    /**
     * 跳转到小程序
     * @desc 用户点击菜单按钮后，微信客户端将打开对应的小程序
     * @hide false
     * @placeholder 请输入不支持小程序时展示的网址
     * @label URL网址
     * @var string
     */
    const TYPE_MINI_PROGRAM = 'miniprogram';
    
    
    /**
     * 获取菜单类型
     * @param null|string $const
     * @return array
     */
    public static function getTypes(?string $const = null) : array
    {
        return Model::parseVars(Model::parseConst(self::class, 'TYPE_', [
            'hide',
            'desc',
            'placeholder',
            'label'
        ], function($item) {
            $item['hide'] = TransHelper::toBool($item['hide']);
            
            return $item;
        }), $const);
    }
}