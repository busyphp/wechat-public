<?php

namespace BusyPHP\wechat\publics\request\account;

use BusyPHP\helper\FilterHelper;
use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 生成带参数的二维码Ticket
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:11 WeChatQrCode.php $
 */
class WeChatQrCode extends WeChatPublicBaseRequest
{
    protected $path = 'cgi-bin/qrcode/create?access_token=';
    
    
    /**
     * 设置该二维码有效时间，以秒为单位。 最大不超过2592000（即30天），此字段如果不填，则默认有效期为30秒。
     * @param int $expire
     */
    public function setExpire($expire)
    {
        $expire = intval($expire);
        $expire = FilterHelper::max($expire, 2592000);
        
        $this->params['expire_seconds'] = $expire;
    }
    
    
    /**
     * 设为二维码类型
     * @param bool $isTemp 是否临时二维码，true临时，false永久
     * @param bool $isString 二维码值是否String类型
     */
    public function setType($isTemp, $isString = false)
    {
        if ($isTemp) {
            if ($isString) {
                $this->params['action_name'] = 'QR_STR_SCENE';
            } else {
                $this->params['action_name'] = 'QR_SCENE';
            }
        } else {
            if ($isString) {
                $this->params['action_name'] = 'QR_LIMIT_STR_SCENE';
            } else {
                $this->params['action_name'] = 'QR_LIMIT_SCENE';
            }
        }
    }
    
    
    /**
     * 设置二维码值
     * @param float|string|int $value
     * @throws WeChatPublicException
     */
    public function setValue($value)
    {
        if (!isset($this->params['action_name'])) {
            throw new WeChatPublicException('请先设置二维码类型');
        }
        
        $scene = [];
        switch ($this->params['action_name']) {
            case 'QR_STR_SCENE':
            case 'QR_LIMIT_STR_SCENE':
                $scene['scene_str'] = trim($value);
            break;
            default:
                $scene['scene_id'] = floatval($value);
        }
        
        $this->params['action_info'] = ['scene' => $scene];
    }
    
    
    /**
     * 执行请求
     * @return WeChatQrCodeResult
     */
    public function create() : WeChatQrCodeResult
    {
        $result         = parent::request();
        $res            = new WeChatQrCodeResult();
        $res->url       = $result['url'];
        $res->expire    = isset($result['expire_seconds']) ? $result['expire_seconds'] : false;
        $res->ticket    = $result['ticket'];
        $res->onlineUrl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$res->ticket}";
        
        return $res;
    }
}
