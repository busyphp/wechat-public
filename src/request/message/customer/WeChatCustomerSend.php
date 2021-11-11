<?php

namespace BusyPHP\wechat\publics\request\message\customer;

use BusyPHP\wechat\publics\WeChatPublicBaseEvent;
use BusyPHP\wechat\publics\WeChatPublicBaseRequest;

/**
 * 客服接口-发消息
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:21 WeChatCustomerSend.php $
 */
class WeChatCustomerSend extends WeChatPublicBaseRequest
{
    protected $path = 'cgi-bin/message/custom/send?access_token=';
    
    
    /**
     * 设置接收者openid
     * @param $openid
     */
    public function setOpenid($openid)
    {
        $this->params['touser'] = trim($openid);
    }
    
    
    /**
     * 设置文本消息内容
     * @param string $text 支持插入跳小程序的文字链
     */
    public function setText($text)
    {
        $this->params['msgtype'] = WeChatPublicBaseEvent::MSG_TYPE_TEXT;
        $this->params['text']    = [
            'content' => trim($text)
        ];
    }
    
    
    /**
     * 设置发送图片媒体ID
     * @param $mediaId
     */
    public function setImage($mediaId)
    {
        $this->params['msgtype'] = WeChatPublicBaseEvent::MSG_TYPE_IMAGE;
        $this->params['image']   = [
            'media_id' => trim($mediaId)
        ];
    }
    
    
    /**
     * 设置发送语音媒体ID
     * @param $mediaId
     */
    public function setVoice($mediaId)
    {
        $this->params['msgtype'] = WeChatPublicBaseEvent::MSG_TYPE_VOICE;
        $this->params['voice']   = [
            'media_id' => trim($mediaId)
        ];
    }
    
    
    /**
     * 设置发送视频消息的数据
     * @param string $mediaId 视频媒体ID
     * @param string $imageMediaId 视频缩图媒体ID
     * @param string $title 视频标题
     * @param string $desc 视频描述
     */
    public function setVideo($mediaId, $imageMediaId, $title, $desc)
    {
        $this->params['msgtype'] = WeChatPublicBaseEvent::MSG_TYPE_VIDEO;
        $this->params['video']   = [
            "media_id"       => trim($mediaId),
            "thumb_media_id" => trim($imageMediaId),
            "title"          => trim($title),
            "description"    => trim($desc)
        ];
    }
    
    
    /**
     * 设置发送视频消息的数据
     * @param string $url 音乐播放地址
     * @param string $imageMediaId 音乐缩图媒体ID
     * @param string $title 音乐标题
     * @param string $desc 音乐描述
     * @param string $hqUrl 高清音乐播放地址
     */
    public function setMusic($url, $imageMediaId, $title = '', $desc = '', $hqUrl = '')
    {
        $this->params['msgtype'] = WeChatPublicBaseEvent::MSG_TYPE_MUSIC;
        $this->params['music']   = [
            "musicurl"       => trim($url),
            "hqmusicurl"     => trim(empty($hqUrl) ? $url : $hqUrl),
            "thumb_media_id" => trim($imageMediaId),
            "title"          => trim($title),
            "description"    => trim($desc)
        ];
    }
    
    
    /**
     * 添加发送的图文消息, 点击跳转到外链
     * @param string $url 消息链接
     * @param string $title 标题
     * @param string $desc 描述
     * @param string $imageUrl 图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
     */
    public function addArticle($url, $title, $desc = '', $imageUrl = '')
    {
        $this->params['msgtype'] = WeChatPublicBaseEvent::MSG_TYPE_NEWS;
        if (!isset($this->params['news'])) {
            $this->params['news'] = ['articles' => []];
        }
        
        $this->params['news']['articles'][] = [
            "title"       => trim($title),
            "description" => trim($desc),
            "url"         => trim($url),
            "picurl"      => trim($imageUrl)
        ];
    }
    
    
    /**
     * 发送内容图文消息, 点击跳转到图文消息页面
     * @param array|string $mediaIds 图文消息媒体ID
     */
    public function setMpNews($mediaIds)
    {
        $this->params['msgtype'] = 'mpnews';
        $this->params['mpnews']  = [];
        
        if (!is_array($mediaIds)) {
            $mediaIds = [$mediaIds];
        }
        
        $mediaIds = array_map('trim', $mediaIds);
        $index    = 0;
        foreach ($mediaIds as $mediaId) {
            $index++;
            if ($index > 8) {
                continue;
            }
            
            $this->params['mpnews'][] = ['media_id' => $mediaId];
        }
    }
    
    
    /**
     * 设置发送卡券ID
     * @param $cardId
     */
    public function setCard($cardId)
    {
        $this->params['msgtype'] = 'wxcard';
        $this->params['wxcard']  = [
            'card_id' => trim($cardId)
        ];
    }
    
    
    /**
     * 执行发送
     * @return array
     */
    public function send() : array
    {
        if ($this->params['msgtype'] == WeChatPublicBaseEvent::MSG_TYPE_NEWS) {
            $articles                         = $this->params['news']['articles'];
            $this->params['news']['articles'] = [];
            $index                            = 0;
            foreach ($articles as $article) {
                $index++;
                if ($index > 8) {
                    continue;
                }
                $this->params['news']['articles'][] = $article;
            }
        }
        
        
        return parent::request();
    }
}