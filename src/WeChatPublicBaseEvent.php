<?php

namespace BusyPHP\wechat\publics;

use think\facade\Event;
use think\Response;
use Throwable;

/**
 * 微信推送服务基本类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午11:02 WeChatPublicBaseEvent.php $
 */
abstract class WeChatPublicBaseEvent extends WeChatPublic
{
    //+--------------------------------------
    //| 消息类型
    //+--------------------------------------
    /** 普通文本消息 */
    const MSG_TYPE_TEXT = 'text';
    
    /** 图片消息 */
    const MSG_TYPE_IMAGE = 'image';
    
    /** 语音消息 */
    const MSG_TYPE_VOICE = 'voice';
    
    /** 视频消息 */
    const MSG_TYPE_VIDEO = 'video';
    
    /** 音乐消息 */
    const MSG_TYPE_MUSIC = 'music';
    
    /** 图文消息 */
    const MSG_TYPE_NEWS = 'news';
    
    //+--------------------------------------
    //| 服务参数
    //+--------------------------------------
    /**
     * 微信公众号账号
     * @var string
     */
    public $account;
    
    /**
     * 用户OPENID
     * @var string
     */
    public $openid;
    
    /**
     * 消息创建时间
     * @var int
     */
    public $createTime;
    
    /**
     * 是否可以回复
     * @var bool
     */
    public $canReply = true;
    
    /**
     * 图文消息合集
     * @var array
     */
    private $replyArticles = [];
    
    /**
     * 服务名称
     * @var string
     */
    public static $eventName = '';
    
    
    /**
     * BaseService constructor.
     * @param array $data 微信推送数据
     */
    public function __construct($data)
    {
        parent::__construct();
        
        $this->account    = $data['ToUserName'];
        $this->openid     = $data['FromUserName'];
        $this->createTime = $data['CreateTime'];
    }
    
    
    /**
     * 设置是否可以回复
     * @param bool $canReply
     */
    public function setCanReply($canReply)
    {
        $this->canReply = $canReply;
    }
    
    
    /**
     * 执行服务处理
     * @return Response
     */
    public function handle() : Response
    {
        try {
            $result = Event::trigger(static::class, $this, true);
            if (!$result instanceof Response) {
                return self::replyMessage();
            }
            
            return $result;
        } catch (Throwable $e) {
            return $this->replyText($e->getMessage());
        }
    }
    
    
    /**
     * 回复消息，默认回复成功
     * @param string $message
     * @return Response
     */
    public static function replyMessage($message = 'success')
    {
        WeChatPublicService::log()->tag('回复消息')->info($message);
        
        return Response::create($message)->contentType('text/plain');
    }
    
    
    /**
     * 回复消息
     * @param string $type 消息类型
     * @param array  $data 消息内容
     * @return Response
     */
    public function reply($type, $data) : Response
    {
        // 接受回复
        if ($this->canReply) {
            $data['ToUserName']   = $this->openid;
            $data['FromUserName'] = $this->account;
            $data['CreateTime']   = time();
            $data['MsgType']      = $type;
            
            $xml = self::arrayToXml($data);
            WeChatPublicService::log()->tag('回复消息')->info($xml);
            
            return Response::create($xml)->contentType('text/plain');
        }
        
        return self::replyMessage();
    }
    
    
    /**
     * 回复文本消息
     * @param string $message 消息内容，支持换行
     * @return Response
     */
    public function replyText($message) : Response
    {
        $message = trim($message);
        if (!$message) {
            return self::replyMessage();
        }
        
        return $this->reply(self::MSG_TYPE_TEXT, [
            'Content' => $message
        ]);
    }
    
    
    /**
     * 回复图片消息
     * @param string $mediaId 通过素材管理中的接口上传多媒体文件，得到的id。
     * @return Response
     */
    public function replyImage($mediaId) : Response
    {
        $mediaId = trim($mediaId);
        if (!$mediaId) {
            return self::replyMessage();
        }
        
        return $this->reply(self::MSG_TYPE_IMAGE, [
            'Image' => [
                'MediaId' => $mediaId
            ]
        ]);
    }
    
    
    /**
     * 回复语音消息
     * @param string $mediaId 通过素材管理中的接口上传多媒体文件，得到的id。
     * @return Response
     */
    public function replyVoice($mediaId) : Response
    {
        $mediaId = trim($mediaId);
        if (!$mediaId) {
            return self::replyMessage();
        }
        
        return $this->reply(self::MSG_TYPE_VOICE, [
            'Voice' => [
                'MediaId' => $mediaId
            ]
        ]);
    }
    
    
    /**
     * 回复视频消息
     * @param string $mediaId 通过素材管理中的接口上传多媒体文件，得到的id。
     * @param string $title 视频标题
     * @param string $desc 视频描述
     * @return Response
     */
    public function replyVideo($mediaId, $title = '', $desc = '') : Response
    {
        $mediaId = trim($mediaId);
        if (!$mediaId) {
            return self::replyMessage();
        }
        
        return $this->reply(self::MSG_TYPE_VIDEO, [
            'Video' => [
                'MediaId'     => $mediaId,
                'Title'       => trim($title),
                'Description' => trim($desc)
            ]
        ]);
    }
    
    
    /**
     * 回复视频消息
     * @param string $url 音乐链接
     * @param string $thumbMediaId 缩略图的媒体id，通过素材管理中的接口上传多媒体文件，得到的id
     * @param string $title 音乐标题
     * @param string $desc 音乐描述
     * @param string $hqUrl 高质量音乐链接，WIFI环境优先使用该链接播放音乐
     * @return Response
     */
    public function replyMusic($url, $thumbMediaId, $title = '', $desc = '', $hqUrl = '') : Response
    {
        return $this->reply(self::MSG_TYPE_MUSIC, [
            'Music' => [
                'MusicURL'     => trim($url),
                'HQMusicUrl'   => trim(empty($hqUrl) ? $url : $hqUrl),
                'Title'        => trim($title),
                'Description'  => trim($desc),
                'ThumbMediaId' => trim($thumbMediaId),
            ]
        ]);
    }
    
    
    /**
     * 添加回复的图文消息
     * @param string $url 消息链接
     * @param string $title 标题
     * @param string $desc 描述
     * @param string $imageUrl 图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
     * @return $this
     */
    public function addReplyArticle($url, $title, $desc = '', $imageUrl = '')
    {
        $this->replyArticles[] = [
            'Item' => [
                'Title'       => trim($title),
                'Description' => trim($desc),
                'PicUrl'      => trim($imageUrl),
                'Url'         => trim($url)
            ]
        ];
        
        return $this;
    }
    
    
    /**
     * 回复图文消息
     * @return Response
     */
    public function replyArticles() : Response
    {
        $articles = [];
        $index    = 0;
        foreach ($this->replyArticles as $article) {
            $index++;
            if ($index > 8) {
                continue;
            }
            $articles[] = $article;
        }
        $this->replyArticles = [];
        
        return $this->reply(self::MSG_TYPE_NEWS, [
            'ArticleCount' => count($articles),
            'Articles'     => $articles
        ]);
    }
    
    
    /**
     * 将数组转换成XML格式的字符串
     * @param array  $params 要转换的数组
     * @param string $root 父标签
     * @return string
     */
    private static function arrayToXml($params, $root = 'xml')
    {
        $params = is_array($params) ? $params : [];
        $xml    = '';
        foreach ($params as $key => $val) {
            if (is_array($val)) {
                $xml .= "<{$key}>";
                foreach ($val as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k1 => $v1) {
                            $xml .= self::arrayToXml($v1, $k1);
                        }
                    } elseif (is_numeric($v)) {
                        $xml .= "<{$k}>{$v}</{$k}>";
                    } else {
                        $xml .= "<{$k}><![CDATA[{$v}]]></{$k}>";
                    }
                }
                $xml .= "</{$key}>";
            } elseif (is_numeric($val)) {
                $xml .= "<{$key}>{$val}</{$key}>";
            } else {
                $xml .= "<{$key}><![CDATA[{$val}]]></{$key}>";
            }
        }
        
        return "<{$root}>{$xml}</{$root}>";
    }
}