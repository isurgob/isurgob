<?php

namespace yii\tree;

use yii\web\AssetBundle;

/**
 * TreeAsset bundle
 *
 * @author Igor Chepurnoi <chepurnoi.igor@gmail.com>
 *
 * @since 1.0
 */
class TreeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '/var/www/html/yii/fancytree/dist';

    /**
     * @var array
     */
    public $css = [
        'skin-win8/ui.fancytree.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'jquery.fancytree-all.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\jui\JuiAsset',
    ];
}
