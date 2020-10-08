<?php

namespace BusyPHP\wechat\publics\app\controller;

use BusyPHP\app\admin\model\system\menu\SystemMenu;
use BusyPHP\app\admin\model\system\menu\SystemMenuField;
use BusyPHP\Controller;
use BusyPHP\exception\AppException;
use BusyPHP\helper\util\Str;

/**
 * 安装
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/7 下午5:41 下午 InstallController.php $
 */
class InstallController extends Controller
{
    public function index()
    {
        $this->app->config->load($this->app->getRootPath() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'extend' . DIRECTORY_SEPARATOR . 'wechat.php', '{$controlName}');
        $groupName   = Str::snake(trim($this->app->config->get('wechat.public.admin_module', 'system')));
        $controlName = Str::snake(trim($this->app->config->get('wechat.public.admin_control', 'wechat')));
        
        try {
            $db             = SystemMenu::init();
            $where          = SystemMenuField::init();
            $where->action  = '';
            $where->control = $controlName;
            $where->module  = $groupName;
            
            // 是否安装过该菜单
            $menuInfo = $db->whereof($where)->findData();
            if ($menuInfo) {
                throw new AppException('您已安装过该插件，请勿重复安装');
            }
            
            // 查询是否创建了表
            if ($db->query("SELECT table_name FROM information_schema.TABLES where table_name='busy_wechat_config' AND table_schema='{$db->getConfig('database')}'")) {
                throw new AppException('您已安装过该插件，请勿重复安装');
            }
            
            // 查询是否有plugin分组
            $where          = SystemMenuField::init();
            $where->action  = '';
            $where->control = '';
            $where->module  = $groupName;
            $groupInfo      = $db->whereof($where)->findData();
            if (!$groupInfo) {
                throw new AppException("没有找到分组: [{$groupName}]");
            }
            
            // 创建表
            $createSQL = <<<SQL
CREATE TABLE `busy_wechat_config` (
  `id` VARCHAR(30) NOT NULL DEFAULT '',
  `content` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信配置表';
SQL;
            $db->execute($createSQL);
            
            
            // 创建数据
            $insertSQL = <<<SQL
INSERT INTO `busy_wechat_config` (`id`, `content`) VALUES
('menu', ''),
('reply', '');
SQL;
            $db->execute($insertSQL);
            
            
            // 插入菜单数据
            $dataSQL = <<<SQL
INSERT INTO `busy_system_menu` (`name`, `action`, `control`, `module`, `pattern`, `params`, `higher`, `icon`, `link`, `target`, `is_default`, `is_show`, `is_disabled`, `is_has_action`, `is_system`, `sort`) VALUES
    ('微信管理', '', '{$controlName}', '{$groupName}', '', '', '', 'weixin', '', '', 0, 1, 0, 1, 0, 50),
    ('公众号菜单', 'menu', '{$controlName}', '{$groupName}', '', '', '', 'navicon', '', '', 0, 1, 0, 1, 0, 50),
    ('编辑公众号菜单', 'menu_edit', '{$controlName}', '{$groupName}', '', '', 'menu', '', '', '', 0, 0, 0, 1, 0, 50),
    ('将公众号菜单保存并发布到微信', 'menu_sync_up', '{$controlName}', '{$groupName}', '', '', 'menu', '', '', '', 0, 0, 0, 1, 0, 50),
    ('同步公众号菜单', 'menu_sync_down', '{$controlName}', '{$groupName}', '', '', 'menu', '', '', '', 0, 0, 0, 1, 0, 50),
    ('停用公众号菜单', 'menu_delete', '{$controlName}', '{$groupName}', '', '', 'menu', '', '', '', 0, 0, 0, 1, 0, 50),
    ('自动回复设置', 'reply', '{$controlName}', '{$groupName}', '', '', '', 'comment', '', '', 0, 1, 0, 1, 0, 50);
SQL;
            $db->execute($dataSQL);
            
            return $this->success('安装成功', '/');
        } catch (\Exception $e) {
            dump($e);
            //return $this->error($e->getMessage(), '/');
        }
    }
}