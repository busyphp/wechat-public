<?php

namespace BusyPHP\wechat\publics\request\message\template;

use BusyPHP\wechat\publics\WeChatPublicBaseRequest;

/**
 * 发送模板消息
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:19 WeChatTemplateSend.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
 */
class WeChatTemplateSend extends WeChatPublicBaseRequest
{
    protected $path = 'cgi-bin/message/template/send?access_token=';
    
    
    /**
     * 设置接收者openid
     * @param $openid
     */
    public function setOpenid($openid)
    {
        $this->params['touser'] = trim($openid);
    }
    
    
    /**
     * 设置模板ID
     * @param $templateId
     */
    public function setTemplateId($templateId)
    {
        $this->params['template_id'] = trim($templateId);
    }
    
    
    /**
     * 设置点击模板跳转链接
     * @param $url
     */
    public function setUrl($url)
    {
        $this->params['url'] = trim($url);
    }
    
    
    /**
     * 设置跳小程序所需数据，不需跳小程序可不用传该数据
     * @param string $appId 所需跳转到的小程序appid（该小程序appid必须与发模板消息的公众号是绑定关联关系）
     * @param string $path 所需跳转到小程序的具体页面路径，支持带参数,（示例index?foo=bar）
     */
    public function setProgram($appId, $path)
    {
        $this->params['miniprogram'] = [
            'appid'    => trim($appId),
            'pagepath' => trim($path)
        ];
    }
    
    
    /**
     * 添加消息标签
     * @param string $key 标签键
     * @param string $value 消息内容
     * @param string $color 消息颜色
     */
    public function addKeyword($key, $value, $color = '')
    {
        if (!isset($this->params['data'])) {
            $this->params['data'] = [];
        }
        
        $this->params['data'][$key] = [
            'value' => $value,
            'color' => $color
        ];
    }
    
    
    /**
     * 设置消息标题
     * @param string $value 标题
     * @param string $color 颜色
     */
    public function setTitle($value, $color = '')
    {
        $this->addKeyword('first', $value, $color);
    }
    
    
    /**
     * 设置消息备注
     * @param string $value 备注内容
     * @param string $color 颜色
     */
    public function setRemark($value, $color = '')
    {
        $this->addKeyword('remark', $value, $color);
    }
    
    
    /**
     * 执行发送
     * @return string 发送成功模板消息ID
     */
    public function send() : string
    {
        $result = parent::request();
        
        return $result['msgid'];
    }
}