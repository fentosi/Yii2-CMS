<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/site_add.css',
        'css/jquery-ui.min.css',
        'css/jquery-ui.structure.min.css',        
        'css/jquery-ui.theme.min.css',
        'css/font-awesome.min.css'
    ];
    public $js = [	
    	'js/scripts.js',
    	'js/jquery-ui.min.js',
    	'js/jquery.sortable.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];  
}