<?php

namespace BusyPHP\wechat\publics\app\controller;

use BusyPHP\wechat\publics\WeChatPublicService;
use think\Container;
use think\Response;

/**
 * 微信公众号事件服务
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 上午11:59 ServiceController.php $
 */
class ServiceController extends Container
{
    /**
     * 入口
     * @return Response
     */
    public function index()
    {
        $service = new WeChatPublicService();
        
        return $service->service();
    }
}