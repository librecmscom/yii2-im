<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\im;

use Yii;
use yii\web\GroupUrlRule;
use yii\i18n\PhpMessageSource;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package yuncms/user
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * 初始化
     * @param \yii\base\Application $app
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            if ($app instanceof \yii\console\Application) {
                $app->controllerMap['im'] = [
                    'class' => 'yuncms\im\console\ImController',
                ];
            } else if (class_exists('\xutl\wechat\Application') && $app instanceof \xutl\wechat\Application) {
                //监听用户登录事件
                /** @var \yii\web\UserEvent $event */
                $app->user->on(\yii\web\User::EVENT_AFTER_LOGIN, function ($event) {
                    //记录最后登录时间记录最后登录IP记录登录次数
                    $event->identity->resetLoginData();
                });
            } elseif ($module instanceof Module) {//前台判断放最后
                //监听用户登录事件
                /** @var \yii\web\UserEvent $event */
                $app->user->on(\yii\web\User::EVENT_AFTER_LOGIN, function ($event) {
                    //记录最后登录时间记录最后登录IP记录登录次数
                    $event->identity->resetLoginData();
                });
            }
        }
        $this->registerTranslations($app);
    }

    /**
     * 注册语言包
     * @param \yii\base\Application $app
     * @return void
     */
    public function registerTranslations($app)
    {
        if (!isset($app->get('i18n')->translations['im*'])) {
            $app->get('i18n')->translations['im*'] = [
                'class' => PhpMessageSource::className(),
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}