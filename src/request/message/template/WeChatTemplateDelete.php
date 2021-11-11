<?php

namespace BusyPHP\wechat\publics\request\message\template;

use BusyPHP\wechat\publics\WeChatPublicBaseRequest;

/**
 * 删除模板消息
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午10:20 WeChatTemplateDelete.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
 */
class WeChatTemplateDelete extends WeChatPublicBaseRequest
{
    protected $path = 'cgi-bin/template/del_private_template?access_token=';
    
    
    /**
     * 设置模板ID
     * @param $templateId
     */
    public function setTemplateId($templateId)
    {
        $this->params['template_id'] = trim($templateId);
    }
    
    
    /**
     * 执行删除
     */
    public function delete()
    {
        parent::request();
    }
}
