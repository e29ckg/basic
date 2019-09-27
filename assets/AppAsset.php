<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [ 
         
        'adminlte2/bower_components/font-awesome/css/font-awesome.min.css', 
        'adminlte2/bower_components/datatables/datatables.min.css',        
        'adminlte2/dist/css/AdminLTE.min.css?v=1.1',
        'adminlte2/bower_components/Ionicons/css/ionicons.min.css',
        'adminlte2/dist/css/skins/_all-skins.min.css',
        'adminlte2/bower_components/PACE/themes/green/pace-theme-minimal.css',         
        'adminlte2/bower_components/bootstrap/dist/css/bootstrap.min.css',
        'adminlte2/bower_components/toastr/toastr.min.css',
        // 'adminlte2/plugins/animisition/css/animsition.min.css',
        'css/site.css?v=1.16',     
    ];
    public $js = [
        
        'adminlte2/bower_components/datatables/datatables.min.js',
        'adminlte2/bower_components/bootstrap/dist/js/bootstrap.min.js',
        'adminlte2/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
        'adminlte2/bower_components/fastclick/lib/fastclick.js',             
        'adminlte2/dist/js/adminlte.min.js',
        'adminlte2/dist/js/demo.js',
        'adminlte2/bower_components/PACE/pace.min.js',
        'adminlte2/bower_components/toastr/toastr.min.js',
        'adminlte2/bower_components/moment/moment.js',
        // 'adminlte2/plugins/animisition/js/animsition.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
