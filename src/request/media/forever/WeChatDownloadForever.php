<?php

namespace BusyPHP\wechat\publics\request\media\forever;

use BusyPHP\helper\HttpHelper;
use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;
use Throwable;

/**
 * 下载获取永久素材
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:11 WeChatDownloadForever.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738730
 */
class WeChatDownloadForever extends WeChatPublicBaseRequest
{
    protected $path    = 'cgi-bin/material/get_material?access_token=';
    
    protected $isVideo = false;
    
    
    /**
     * 设置下载的媒体ID
     * @param string $mediaId
     */
    public function setMediaId($mediaId)
    {
        $this->params['media_id'] = $mediaId;
    }
    
    
    /**
     * 执行下载
     * todo 下载保存未处理, 未按照类型处理，因为暂时用不到
     * @return array
     */
    public function download() : array
    {
        $url  = "https://{$this->host}/{$this->path}{$this->getAccessToken()}";
        $http = new HttpHelper();
        $http->setTimeout(30);
        try {
            $result = HttpHelper::get($url, $this->params, $http);
        } catch (Throwable $e) {
            throw new WeChatPublicException("HTTP请求失败: {$e->getMessage()} [{$e->getCode()}]");
        }
        
        if (!$this->isVideo) {
            if (substr($result, 0, 2) == '{"') {
                $result = json_decode($result, true) ?: [];
                throw new WeChatPublicException($result['errmsg'], $result['errcode']);
            }
        } else {
            $result = json_decode($result, true) ?: [];
            if ($result['errcode'] != '0') {
                throw new WeChatPublicException($result['errmsg'], $result['errcode']);
            }
        }
        
        return $result;
    }
}
    

