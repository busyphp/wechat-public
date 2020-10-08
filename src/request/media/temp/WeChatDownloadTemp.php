<?php

namespace BusyPHP\wechat\publics\request\media\temp;

use BusyPHP\helper\net\Http;
use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;
use Throwable;

/**
 * 下载临时素材
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午11:54 上午 WeChatDownloadTemp.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738727
 */
class WeChatDownloadTemp extends WeChatPublicBaseRequest
{
    protected $method  = 'get';
    
    protected $path    = 'cgi-bin/media/get?access_token=';
    
    protected $isVideo = false;
    
    
    /**
     * 设置下载的媒体ID
     * @param string $mediaId
     * @param bool   $isVideo 是否下载视频
     */
    public function setMediaId($mediaId, $isVideo = false)
    {
        $this->params['media_id'] = $mediaId;
        $this->isVideo            = $isVideo;
    }
    
    
    /**
     * 执行下载
     * todo 下载保存未处理
     * @return array|string
     * @throws WeChatPublicException
     */
    public function request()
    {
        $url  = "https://{$this->host}/{$this->path}{$this->getAccessToken()}";
        $http = new Http();
        $http->setTimeout(30);
        try {
            $result = Http::get($url, $this->params, $http);
        } catch (Throwable $e) {
            throw new WeChatPublicException("HTTP请求失败: {$e->getMessage()} [{$e->getCode()}]");
        }
        
        if (!$this->isVideo) {
            if (substr($result, 0, 2) == '{"') {
                $result = json_decode($result, true);
                throw new WeChatPublicException($result['errmsg'], $result['errcode']);
            }
        } else {
            $result = json_decode($result, true);
            if ($result['errcode'] != '0') {
                throw new WeChatPublicException($result['errmsg'], $result['errcode']);
            }
        }
        
        return $result;
    }
}
    

