<?php

namespace BusyPHP\wechat\publics\request\media\temp;

use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 上传临时素材
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:27 WeChatUploadTemp.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738726
 */
class WeChatUploadTemp extends WeChatPublicBaseRequest
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
    
    protected $path   = 'cgi-bin/media/upload?access_token=';
    
    
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
     * 执行上传
     * @return string
     * @throws WeChatPublicException
     */
    public function upload() : string
    {
        $result = parent::request();
        
        return $result['media_id'];
    }
}