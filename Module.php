<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\im;


use Yii;
use yii\caching\Cache;
use yii\di\Instance;
use xutl\tim\Tim;
use yii\base\InvalidConfigException;

/**
 * Class Module
 * @package yuncms\im
 */
class Module extends \yii\base\Module
{
    /**
     * @var string|Tim 直播组件实例
     */
    public $im = 'im';

    /**
     * @var string|Cache 缓存实例
     */
    public $cache = 'cache';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->im = Instance::ensure($this->im, Tim::className());
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
        $this->registerTranslations();
    }

    /**
     * 注册语言包
     * @return void
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['im*'])) {
            Yii::$app->i18n->translations['im*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}