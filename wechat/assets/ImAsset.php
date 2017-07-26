<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\im\wechat\assets;

use yii\web\AssetBundle;

class ImAsset extends AssetBundle
{
    public $sourcePath = '@yuncms/im/wechat/views/assets';

    public $css = [
        'css/mobile.css'
    ];

    public $js = [
        'js/im_base.js',
        'js/im_group_notice.js',
    ];

    public $depends = [
        'xutl\tim\TimAsset',
    ];
}