<?php

namespace BusyPHP\wechat\publics\app\controller;

use BusyPHP\app\admin\controller\AdminController;
use BusyPHP\helper\TransHelper;
use BusyPHP\wechat\publics\model\config\WechatMenuConfig;
use BusyPHP\wechat\publics\model\config\WechatReplyConfig;
use BusyPHP\wechat\publics\request\menu\WeChatMenu;
use BusyPHP\wechat\publics\request\menu\WeChatMenuItem;
use Exception;
use think\db\exception\DbException;

/**
 * 微信公众号管理
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/11/11 下午5:50 WechatController.php $
 */
class WechatController extends AdminController
{
    /**
     * 公众号菜单管理
     */
    public function menu()
    {
        try {
            $config = WechatMenuConfig::init();
            $result = $config->getMenu();
            $this->assign('list', $result->menu);
            $this->assign('disabled', $result->disabled);
            $this->assign('type_options', TransHelper::toOptionHtml(WeChatMenu::getTypes(), '', 'name', 'value', [
                'data-config' => function($item) {
                    return json_encode($item, JSON_UNESCAPED_UNICODE);
                }
            ]));
            
            return $this->display();
        } catch (Exception $e) {
            $this->assign('error_message', $e->getMessage());
            
            return $this->display('menu_fail');
        }
    }
    
    
    /**
     * 编辑公众号菜单
     * @throws DbException
     */
    public function menu_update()
    {
        WechatMenuConfig::init()->editMenu($this->getMenuData());
        $this->log()->record(self::LOG_UPDATE, '编辑微信公众号菜单');
        
        return $this->success('保存成功');
    }
    
    
    /**
     * 保存并发布到微信
     * @throws DbException
     */
    public function menu_upload()
    {
        WechatMenuConfig::init()->editMenu($this->getMenuData());
        WechatMenuConfig::init()->publish();
        $this->log()->record(self::LOG_UPDATE, '编辑微信公众号菜单并发布到微信');
        
        return $this->success('发布成功，预计48小时全量生效');
    }
    
    
    /**
     * 同步菜单结构
     * @throws DbException
     */
    public function menu_download()
    {
        WechatMenuConfig::init()->sync();
        $this->log()->record(self::LOG_UPDATE, '同步微信公众号菜单至本地');
        
        return $this->success('同步成功');
    }
    
    
    /**
     * 停用菜单
     * @throws DbException
     */
    public function menu_stop()
    {
        WechatMenuConfig::init()->clear();
        $this->log()->record(self::LOG_UPDATE, '停用微信公众号菜单');
        
        return $this->success('停用成功');
    }
    
    
    /**
     * 解析提交数据
     * @return WeChatMenuItem[]
     */
    private function getMenuData() : array
    {
        $list = [];
        foreach ($this->post('button/a') as $item) {
            $subList = [];
            foreach ($item['sub_button']['list'] ?? [] as $sub) {
                $subObj = new WeChatMenuItem();
                $subObj->create(true, $sub['type'], $sub['name'], $sub['value'], $sub['app_id'], $sub['app_path']);
                $subList[] = $subObj;
            }
            
            $obj = new WeChatMenuItem();
            $obj->create(false, $item['type'], $item['name'], $item['value'], $item['app_id'], $item['app_path']);
            $obj->setSubButton($subList);
            $list[] = $obj;
        }
        
        return $list;
    }
    
    
    /**
     * 自动回复设置
     * @throws DbException
     */
    public function reply()
    {
        if ($this->isPost()) {
            WechatReplyConfig::init()->setContent($this->post('data/a'));
            $this->log()->record(self::LOG_UPDATE, '设置微信公众号自动回复');
            
            return $this->success('设置成功');
        }
        
        $this->assign('info', WechatReplyConfig::init()->getContent());
        
        return $this->display();
    }
    
    
    protected function display($template = '', $charset = 'utf-8', $contentType = '', $content = '')
    {
        $this->app->config->set(['view_path' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR], 'view');
        
        return parent::display($template, $charset, $contentType, $content);
    }
}