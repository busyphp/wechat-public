<?php

namespace BusyPHP\wechat\publics;

use BusyPHP\helper\util\Str;
use BusyPHP\wechat\publics\app\controller\InstallController;
use BusyPHP\wechat\publics\app\controller\WechatController;
use think\Route;

class Service extends \think\Service
{
    public function boot()
    {
        $this->registerRoutes(function(Route $route) {
            $route->rule('general/plugin/install/wechat-public', InstallController::class . '@index');
            
            // 后台路由
            if ($this->app->http->getName() === 'admin') {
                $this->app->config->load($this->app->getRootPath() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'extend' . DIRECTORY_SEPARATOR . 'wechat.php', 'wechat');
                
                $group   = ucfirst(Str::camel(trim($this->app->config->get('wechat.public.admin_module', 'system'))));
                $control = ucfirst(Str::camel(trim($this->app->config->get('wechat.public.admin_control', 'wechat'))));
                
                $route->rule("{$group}.{$control}/<action>", WechatController::class . '@<action>')->pattern([
                    'action' => '[menu|menu_edit|menu_sync_up|menu_sync_down|menu_delete|reply]+'
                ])->append([
                    'group'   => $group,
                    'control' => $control,
                    'type'    => 'plugin'
                ]);
            }
        });
    }
}