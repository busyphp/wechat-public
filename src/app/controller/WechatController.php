<?php

namespace BusyPHP\wechat\publics\app\controller;

use BusyPHP\app\admin\controller\AdminCurdController;
use BusyPHP\helper\util\Transform;
use BusyPHP\wechat\publics\model\config\WechatMenuConfig;
use BusyPHP\wechat\publics\model\config\WechatReplyConfig;
use BusyPHP\wechat\publics\request\menu\WeChatMenu;
use BusyPHP\wechat\publics\request\menu\WeChatMenuItem;
use BusyPHP\wechat\publics\request\menu\WeChatMenuList;
use Exception;

/**
 * 微信管理
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/7/8 下午12:36 下午 Wechat.php $
 */
class WechatController extends AdminCurdController
{
    /**
     * 公众号菜单管理
     */
    public function menu()
    {
        try {
            $config = WechatMenuConfig::init();
            $config->initConfigure();
            $result = $config->getConfigure();
            $this->assign('list', $config::parseList($result->menu));
            $this->assign('is_disabled', $result->isDisabled);
            $this->assign('type_options', Transform::arrayToOption(WeChatMenu::getTypes()));
            
            return $this->display();
        } catch (Exception $e) {
            $this->assign('error_message', $e->getMessage());
            
            return $this->display('menu_fail');
        }
    }
    
    
    /**
     * 编辑公众号菜单
     */
    public function menu_edit()
    {
        return $this->submit('request', function() {
            $list = $this->getMenuData();
            WechatMenuConfig::init()->setConfigure($list);
            $this->setRedirectUrl(url('menu'));
            $this->log('修改微信菜单', $list->_toArray(), self::LOG_UPDATE);
            
            return '保存成功';
        });
    }
    
    
    /**
     * 保存并发布到微信
     */
    public function menu_sync_up()
    {
        return $this->submit('request', function() {
            $list = $this->getMenuData();
            WechatMenuConfig::init()->setConfigure($list);
            WechatMenuConfig::init()->publish();
            $this->setRedirectUrl(url('menu'));
            $this->log('修改微信菜单并同步到微信服务器', $list->_toArray(), self::LOG_SET);
            
            return '保存并同步到微信成功';
        });
    }
    
    
    /**
     * 同步菜单结构
     */
    public function menu_sync_down()
    {
        return $this->submit('request', function() {
            WechatMenuConfig::init()->initConfigure(true);
            $this->log('同步微信菜单结构', WechatMenuConfig::init()->getConfigure(), self::LOG_SET);
            
            $this->setRedirectUrl(url('menu'));
            
            return '菜单结构同步成功';
        });
    }
    
    
    /**
     * 停用菜单
     */
    public function menu_delete()
    {
        return $this->submit('request', function() {
            WechatMenuConfig::init()->clear();
            $this->setRedirectUrl(url('menu'));
            
            $this->log('停用微信菜单', '', self::LOG_DELETE);
            
            return '停用成功';
        });
    }
    
    
    /**
     * 解析提交数据
     * @return WeChatMenuList
     * @throws Exception
     */
    private function getMenuData()
    {
        $list = new WeChatMenuList();
        foreach ($_POST['button'] as $item) {
            $subList = new WeChatMenuList();
            foreach ($item['sub_button']['list'] as $sub) {
                $obj = new WeChatMenuItem();
                $obj->create(true, $sub['type'], $sub['name'], $sub['value'], $sub['app_id'], $sub['app_path']);
                $subList->add($obj);
            }
            
            $obj = new WeChatMenuItem();
            $obj->create(false, $item['type'], $item['name'], $item['value'], $item['app_id'], $item['app_path']);
            $obj->setSubButton($subList);
            $list->add($obj);
        }
        
        return $list;
    }
    
    
    /**
     * 自动回复设置
     */
    public function reply()
    {
        return $this->submit('post', function($data) {
            WechatReplyConfig::init()->setConfigure($data['data']);
            $this->setRedirectUrl(url());
            $this->log('设置微信公众号自动回复', $data['data'], self::LOG_SET);
            
            return '设置成功';
        }, function() {
            return WechatReplyConfig::init()->getConfigure();
        });
    }
    
    
    protected function display($template = '', $charset = 'utf-8', $contentType = '', $content = '')
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
        if ($template) {
            $template = $dir . $template . '.html';
        } else {
            $template = $dir . ACTION_NAME . '.html';
        }
        
        return parent::display($template, $charset, $contentType, $content);
    }
}