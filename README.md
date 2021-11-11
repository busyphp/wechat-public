微信公众号模块
===============

## 说明

用于BusyPHP微信公众号管理，支持菜单、自动回复简单管理以及各种公众号事件预设

## 安装
```
composer require busyphp/wechat-public
```

> 安装成功后在后台 开发模式 > 插件管理 进行数据表和菜单的 安装/卸载

## 设置微信公众号服务器配置

服务器地址

> http(s)://www.domain.com/service/plugins/wechat_public/index

## 配置 `config/extend/wechat.php`

```php
<?php
return [
    // 公众号配置
    'public' => [
        // 公众号 App Id
        'app_id'           => '',
    
    
        // 公众号 App Secret
        'app_secret'       => '',
    
    
        // 公众号 令牌(Token)
        'token'            => '',
    
    
        // todo 暂无意义
        // 消息加解密密钥 (EncodingAESKey)
        'encoding_aes_key' => '',
        
        
        // 监听事件
        // 请参考对于类中的描述绑定自己的Event即可
        'listen'           => [
            //AuthEvent::class            => '',
            //ClickEvent::class           => '',
            //FollowEvent::class          => '',
            //LocationEvent::class        => '',
            //NamingEvent::class          => '',
            //PicEvent::class             => '',
            //RenewEvent::class           => '',
            //ScanCodeEvent::class        => '',
            //ScanEvent::class            => '',
            //TemplateMessageEvent::class => '',
            //ViewEvent::class            => '',
            //ImageMessageEvent::class    => '',
            //LinkMessageEvent::class     => '',
            //LocationMessageEvent::class => '',
            //TextMessageEvent::class     => '',
            //VideoMessageEvent::class    => '',
            //VoiceMessageEvent::class    => '',
        ]
    ],
];
```