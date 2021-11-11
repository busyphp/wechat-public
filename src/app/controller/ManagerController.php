<?php

namespace BusyPHP\wechat\publics\app\controller;

use BusyPHP\app\admin\model\system\menu\SystemMenu;
use BusyPHP\app\admin\model\system\menu\SystemMenuField;
use BusyPHP\app\admin\model\system\plugin\SystemPlugin;
use BusyPHP\contract\abstracts\PluginManager;
use BusyPHP\exception\VerifyException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\Response;
use Throwable;

/**
 * 插件管理
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 下午12:16 ManagerController.php $
 */
class ManagerController extends PluginManager
{
    private $createTableSql = [
        'wechat_config' => "CREATE TABLE `#__table_prefix__#wechat_config` (
  `id` VARCHAR(32) NOT NULL DEFAULT '',
  `content` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信配置表'",
    ];
    
    private $deleteTableSql = [
        "DROP TABLE IF EXISTS `#__table_prefix__#wechat_config`",
    ];
    
    
    protected function viewPath() : string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
    }
    
    
    /**
     * 安装
     * @return Response
     * @throws Throwable
     * @throws DataNotFoundException
     * @throws DbException
     */
    public function install() : Response
    {
        $menuModel = SystemMenu::init();
        if ($this->isPost()) {
            $parentPath   = $this->post('parent_path/s', 'trim');
            $replaceMenu  = $this->post('replace_menu/b');
            $replaceTable = $this->post('replace_table/b');
            if (!$parentPath) {
                throw new VerifyException('请选择菜单安装位置', 'parent_path');
            }
            
            $menuModel->startTrans();
            try {
                // 删除菜单
                $path = '#plugins_wechat_public';
                if ($replaceMenu) {
                    $menuModel->deleteByPath($path, true);
                }
                
                // 不存在菜单则创建
                if (!$menuModel->whereEntity(SystemMenuField::path($path))->count()) {
                    $menuModel->addMenu($path, '微信公众号管理', $parentPath, 'fa fa-wechat', false);
                    $menuModel->addMenu('plugins_wechat_public/menu', '菜单管理', $path, 'bicon bicon-menu', false, 1);
                    $menuModel->addMenu('plugins_wechat_public/menu_edit', '编辑菜单', 'plugins_wechat_public/menu', '', true, 2);
                    $menuModel->addMenu('plugins_wechat_public/menu_upload', '发布菜单', 'plugins_wechat_public/menu', '', true, 3);
                    $menuModel->addMenu('plugins_wechat_public/menu_download', '同步菜单', 'plugins_wechat_public/menu', '', true, 1);
                    $menuModel->addMenu('plugins_wechat_public/menu_stop', '停用菜单', 'plugins_wechat_public/menu', '', true, 4);
                    
                    $menuModel->addMenu('plugins_wechat_public/reply', '回复设置', $path, 'fa fa-reply-all', false, 2);
                }
                
                // 删除表
                if ($replaceTable) {
                    foreach ($this->deleteTableSql as $item) {
                        $this->executeSQL($item);
                    }
                }
                
                // 创建表
                foreach ($this->createTableSql as $name => $item) {
                    if (!$this->hasTable($name)) {
                        $this->executeSQL($item);
                    }
                }
                
                SystemPlugin::init()->setInstall($this->info->package);
                
                $menuModel->commit();
            } catch (Throwable $e) {
                $menuModel->rollback();
                
                throw $e;
            }
            
            $this->updateCache();
            $this->logInstall();
            
            return $this->success('安装成功');
        }
        
        $this->assign('menu_options', $menuModel->getTreeOptions());
        
        return $this->display();
    }
    
    
    /**
     * 卸载
     * @return Response
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Throwable
     */
    public function uninstall() : Response
    {
        if ($this->isPost()) {
            $retainMenu  = $this->post('retain_menu/b');
            $retainTable = $this->post('retain_table/b');
            if ($retainMenu && $retainTable) {
                throw new VerifyException('全部保留，无需卸载');
            }
            
            $menuModel = SystemMenu::init();
            $menuModel->startTrans();
            try {
                if (!$retainMenu) {
                    $menuModel->deleteByPath('#plugins_wechat_public', true);
                }
                
                if (!$retainTable) {
                    foreach ($this->deleteTableSql as $item) {
                        $this->executeSQL($item);
                    }
                }
                
                SystemPlugin::init()->setUninstall($this->info->package);
                $menuModel->commit();
            } catch (Throwable $e) {
                $menuModel->rollback();
                
                throw $e;
            }
            
            $this->updateCache();
            $this->logUninstall();
            
            return $this->success('卸载成功');
        }
        
        return $this->display();
    }
    
    
    public function setting() : Response
    {
        return response();
    }
}