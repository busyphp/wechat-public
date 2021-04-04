<?php

namespace BusyPHP\wechat\publics;

use BusyPHP\wechat\publics\events\AuthEvent;
use BusyPHP\wechat\publics\events\ClickEvent;
use BusyPHP\wechat\publics\events\FollowEvent;
use BusyPHP\wechat\publics\events\ImageMessageEvent;
use BusyPHP\wechat\publics\events\LinkMessageEvent;
use BusyPHP\wechat\publics\events\LocationEvent;
use BusyPHP\wechat\publics\events\LocationMessageEvent;
use BusyPHP\wechat\publics\events\NamingEvent;
use BusyPHP\wechat\publics\events\PicEvent;
use BusyPHP\wechat\publics\events\RenewEvent;
use BusyPHP\wechat\publics\events\ScanCodeEvent;
use BusyPHP\wechat\publics\events\ScanEvent;
use BusyPHP\wechat\publics\events\TemplateMessageEvent;
use BusyPHP\wechat\publics\events\TextMessageEvent;
use BusyPHP\wechat\publics\events\VideoMessageEvent;
use BusyPHP\wechat\publics\events\ViewEvent;
use BusyPHP\wechat\publics\events\VoiceMessageEvent;
use BusyPHP\wechat\WeChatLogs;
use think\Response;
use Throwable;


/**
 * 微信服务类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:25 上午 WeChatService.php $
 */
class WeChatPublicService extends WeChatPublic
{
    /**
     * WeChatPublicService constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        // 监听事件
        $listens = $this->getConfig('public.listen', []);
        foreach ($listens as $event => $listen) {
            if (!$listen) {
                continue;
            }
            $this->app->event->listen($event, $listen);
        }
    }
    
    
    /**
     * 校验Signature
     * @throws WeChatPublicException
     */
    protected function checkSignature()
    {
        $signature = isset($_REQUEST["signature"]) ? $_REQUEST["signature"] : '';
        $timestamp = isset($_REQUEST["timestamp"]) ? $_REQUEST["timestamp"] : '';
        $nonce     = isset($_REQUEST["nonce"]) ? $_REQUEST['nonce'] : '';
        $tempArr   = [$this->getToken(), $timestamp, $nonce];
        sort($tempArr, SORT_STRING);
        if ($signature == sha1(implode('', $tempArr))) {
            return isset($_REQUEST['echostr']) ? $_REQUEST['echostr'] : '';
        } else {
            throw new WeChatPublicException("signature校验失败");
        }
    }
    
    
    /**
     * 服务入口
     */
    public function service()
    {
        WeChatLogs::addInfo('微信公众号异步通知');
        
        try {
            // 校验签名
            $echoStr = $this->checkSignature();
            
            // 验证服务地址
            if (!$this->app->request->isPost()) {
                return WeChatPublicBaseEvent::replyMessage($echoStr);
            }
            
            // 执行服务调度
            return $this->dispatch();
        } catch (Throwable $e) {
            WeChatLogs::addError($e->getMessage());
            
            return WeChatPublicBaseEvent::replyMessage();
        }
    }
    
    
    /**
     * 执行调度
     * @return Response
     */
    protected function dispatch() : Response
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xml  = file_get_contents('php://input');
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        WeChatLogs::addInfo("请求数据: {$xml}");
        
        $event     = null;
        $eventName = '未识别类型';
        $canReply  = true;
        switch ($data['MsgType']) {
            // 文本消息
            case 'text':
                $event     = new TextMessageEvent($data);
                $eventName = '文本消息';
            break;
            // 图片消息
            case 'image':
                $event     = new ImageMessageEvent($data);
                $eventName = '图片消息';
            break;
            // 语音消息
            case 'voice':
                $event     = new VoiceMessageEvent($data);
                $eventName = '语音消息';
            break;
            case 'video':  // 视频消息
            case 'shortvideo': // 小视频消息
                $isShorVideo = $data['MsgType'] == 'shortvideo';
                $event       = new VideoMessageEvent($data, $isShorVideo);
                $eventName   = $isShorVideo ? '小视频消息' : '视频消息';
            break;
            // 链接消息
            case 'link':
                $event     = new LinkMessageEvent($data);
                $eventName = '链接消息';
            break;
            // 地理位置消息
            case 'location':
                $event     = new LocationMessageEvent($data);
                $eventName = '地理位置消息';
            break;
            // 事件
            case 'event':
                switch ($data['Event']) {
                    // 关注和扫码进入
                    case 'subscribe':   // 用户已关注时的事件推送
                    case 'unsubscribe': // 用户取消关注时的事件推送
                    case 'SCAN':        // 扫描带参数二维码事件
                        $isScan    = false;
                        $scanValue = '';
                        if ($data['Event'] == 'SCAN') {
                            $isScan    = true;
                            $scanValue = $data['EventKey'];
                        } elseif (isset($data['EventKey']) && is_string($data['EventKey']) && substr($data['EventKey'], 0, 8) == 'qrscene_') {
                            $isScan    = true;
                            $scanValue = substr($data['EventKey'], 8);
                        }
                        
                        // 扫码进入
                        if ($isScan) {
                            $event     = new ScanEvent($data, $data['Event'] == 'subscribe', $scanValue);
                            $eventName = '扫描带参数二维码事件';
                        } else {
                            $event     = new FollowEvent($data, $data['Event'] == 'unsubscribe');
                            $eventName = '关注/取消关注事件';
                        }
                    break;
                    
                    // 上报地理位置事件
                    case 'LOCATION':
                        $event     = new LocationEvent($data);
                        $eventName = '上报地理位置事件';
                    break;
                    
                    // 自定义菜单单击事件
                    case 'CLICK':
                        $event     = new ClickEvent($data);
                        $eventName = '自定义菜单单击事件';
                    break;
                    
                    // 点击菜单跳转链接时的事件推送
                    case 'VIEW':
                        $event     = new ViewEvent($data);
                        $canReply  = false;
                        $eventName = '点击菜单跳转链接事件推送';
                    break;
                    
                    // 模板消息送达事件推送
                    case 'TEMPLATESENDJOBFINISH':
                        $event     = new TemplateMessageEvent($data);
                        $canReply  = false;
                        $eventName = '模板消息送达事件推送';
                    break;
                    
                    // 资质认证
                    case 'qualification_verify_success': // 资质认证成功（此时立即获得接口权限）
                    case 'qualification_verify_fail': // 资质认证失败
                        $event     = new AuthEvent($data, $data['Event'] == 'qualification_verify_success');
                        $canReply  = false;
                        $eventName = '资质认证事件推送';
                    break;
                    
                    // 名称认证
                    case 'naming_verify_success': // 名称认证成功（即命名成功）
                    case 'naming_verify_fail': // 名称认证失败（这时虽然客户端不打勾，但仍有接口权限）
                        $event     = new NamingEvent($data, $data['Event'] == 'naming_verify_success');
                        $canReply  = false;
                        $eventName = '名称认证事件推送';
                    break;
                    
                    // 年审通知
                    case 'annual_renew':
                        $event     = new RenewEvent($data);
                        $canReply  = false;
                        $eventName = '年审通知';
                    break;
                    
                    // 扫码推事件
                    case 'scancode_push': // 扫码推事件的事件推送
                    case 'scancode_waitmsg': // 扫码推事件且弹出“消息接收中”提示框的事件推送
                        $isWait    = $data['Event'] == 'scancode_waitmsg';
                        $event     = new ScanCodeEvent($data, $isWait);
                        $eventName = $isWait ? '扫码等待消息事件' : '扫码推事件推送';
                    break;
                    
                    // 发图的事件推送
                    case 'pic_sysphoto': // 弹出系统拍照发图的事件推送
                    case 'pic_photo_or_album': // 弹出拍照或者相册发图的事件推送
                    case 'pic_weixin': // 弹出微信相册发图器的事件推送
                        $isWeChat     = false;
                        $isPhotoAlbum = false;
                        switch ($data['Event']) {
                            case 'pic_sysphoto':
                                $eventName = '弹出系统拍照发图的事件推送';
                            break;
                            case 'pic_photo_or_album':
                                $eventName    = '弹出拍照或者相册发图的事件推送';
                                $isPhotoAlbum = true;
                            break;
                            default:
                                $eventName = '弹出微信相册发图器的事件推送';
                                $isWeChat  = true;
                        }
                        $event = new PicEvent($data, $isWeChat, $isPhotoAlbum);
                    break;
                    
                    // todo 弹出地理位置选择器的事件推送
                    case 'location_select':
                        $eventName = '弹出地理位置选择器的事件推送';
                        $canReply  = false;
                    break;
                }
            break;
        }
        
        $event::$eventName = $eventName;
        
        // 执行指定的服务
        WeChatLogs::addInfo("开始处理: {$eventName} (" . get_class($event) . ")");
        if ($event != null && $event instanceof WeChatPublicBaseEvent) {
            $event->setCanReply($canReply);
            WeChatLogs::addInfo('处理成功');
            
            return $event->handle();
        }
        
        // 其它未知服务一律回复处理成功
        return WeChatPublicBaseEvent::replyMessage();
    }
}
