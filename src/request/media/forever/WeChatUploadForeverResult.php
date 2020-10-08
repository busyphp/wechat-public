<?php

namespace BusyPHP\wechat\publics\request\media\forever;

/**
 * 上传永久素材返回结构
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/8 下午3:40 下午 WeChatUploadForeverResult.php $
 */
class WeChatUploadForeverResult
{
    /**
     * 新增的图片素材的图片URL（仅新增图片素材时会返回该字段）
     * @var string
     */
    public $url;
    
    /**
     * 新增的永久素材的media_id
     * @var string
     */
    public $mediaId;
}