<?php

namespace BusyPHP\wechat\publics\request\message\template;

/**
 * 模板列表集合Item
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/8 下午3:47 下午 WeChatTemplateGetListItem.php $
 */
class WeChatTemplateGetListItem
{
    /**
     * 模板消息ID
     * @var string
     */
    public $templateId;
    
    /**
     * 标题
     * @var string
     */
    public $title;
    
    /**
     * 模板内容
     * @var string
     */
    public $content;
    
    /**
     * 模板示例
     * @var string
     */
    public $example;
    
    /**
     * 模板所属行业的一级行业
     * @var string
     */
    public $primaryIndustry;
    
    /**
     * 模板所属行业的二级行业
     * @var string
     */
    public $deputyIndustry;
}