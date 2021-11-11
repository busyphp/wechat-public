<?php

namespace BusyPHP\wechat\publics\request\media\forever;

use BusyPHP\wechat\publics\WeChatPublicBaseRequest;

/**
 * 上传永久素材
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:26 WeChatUploadForever.php $
 */
class WeChatUploadForever extends WeChatPublicBaseRequest
{
    /** 图片 */
    const TYPE_IMAGE = 'image';
    
    /** 语音 */
    const TYPE_VOICE = 'voice';
    
    /** 视频 */
    const TYPE_VIDEO = 'video';
    
    /** 缩略图 */
    const TYPE_THUMB = 'thumb';
    
    protected $method = 'post';
    
    protected $path   = 'cgi-bin/material/add_material?access_token=';
    
    
    /**
     * 设置上传的附件
     * @param string $type 附件类型
     * @param string $file 附件路径
     */
    public function setFile($type, $file)
    {
        $this->params['type'] = $type;
        $this->files['media'] = $file;
    }
    
    
    /**
     * 设置视频信息
     * @param string $title 视频标题
     * @param string $desc 视频描述
     */
    public function setVideoInfo($title, $desc)
    {
        $this->params['description'] = json_encode([
            'title'        => $title,
            'introduction' => $desc
        ], JSON_UNESCAPED_UNICODE);
    }
    
    
    /**
     * 执行上传
     * @return WeChatUploadForeverResult
     */
    public function upload() : WeChatUploadForeverResult
    {
        $result = parent::request();
        
        $res          = new WeChatUploadForeverResult();
        $res->url     = $result['url'];
        $res->mediaId = $result['media_id'];
        
        return $res;
    }
}
