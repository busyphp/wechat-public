<?php

namespace BusyPHP\wechat\publics;

use BusyPHP\Service as BaseService;
use BusyPHP\wechat\publics\app\controller\ServiceController;
use BusyPHP\wechat\publics\app\controller\WechatController;
use think\Route;

class Service extends \think\Service
{
    public function boot()
    {
        $this->registerRoutes(function(Route $route) {
            $actionPattern = '<' . BaseService::ROUTE_VAR_ACTION . '>';
            
            // 注册事件通知路由
            $route->rule("service/plugins/wechat_public/index", ServiceController::class . "@index")->append([
                BaseService::ROUTE_VAR_TYPE    => 'plugin',
                BaseService::ROUTE_VAR_CONTROL => 'service',
                BaseService::ROUTE_VAR_ACTION  => 'index',
            ]);
            
            
            // 后台路由
            if ($this->app->http->getName() === 'admin') {
                $route->rule("plugins_wechat_public/{$actionPattern}", WechatController::class . '@' . $actionPattern)
                    ->append([
                        BaseService::ROUTE_VAR_TYPE    => 'plugin',
                        BaseService::ROUTE_VAR_CONTROL => 'plugins_wechat_public',
                    ]);
            }
        });
    }
}