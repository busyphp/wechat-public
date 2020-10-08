<?php

namespace BusyPHP\wechat\publics\request\message\template;

use BusyPHP\wechat\publics\WeChatPublicBaseRequest;
use BusyPHP\wechat\publics\WeChatPublicException;

/**
 * 获取模板列表
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午12:02 下午 WeChatTemplateGetList.php $
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
 */
class WeChatTemplateGetList extends WeChatPublicBaseRequest
{
    protected $path = 'cgi-bin/template/get_all_private_template?access_token=';
    
    
    /**
     * 执行发送
     * @return WeChatTemplateGetListList
     * @throws WeChatPublicException
     */
    public function request()
    {
        $result = parent::request();
        $list   = isset($result['template_list']) ? $result['template_list'] : [];
        $map    = new WeChatTemplateGetListList();
        foreach ($list as $i => $r) {
            $item                  = new WeChatTemplateGetListItem();
            $item->title           = $r['title'];
            $item->templateId      = $r['template_id'];
            $item->content         = $r['content'];
            $item->example         = $r['example'];
            $item->primaryIndustry = $r['primary_industry'];
            $item->deputyIndustry  = $r['deputy_industry'];
            $map->add($item);
        }
        
        return $map;
    }
}
