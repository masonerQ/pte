<?php

    namespace backend\assets;

    use yii\web\AssetBundle;

    /**
     * Main backend application asset bundle.
     */
    class AppAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl  = '@web';
        public $css      = [
            'X-admin/css/font.css',
            'X-admin/css/xadmin.css',
        ];
        public $js       = [
            'X-admin/lib/layui/layui.js',
            'X-admin/js/xadmin.js',
            [
                'X-admin/js/xadmin.js',
                'jsOptions' => [
                    'condition'=>'lte IE9'
                ]
            ]
        ];
        public $depends  = [
            // 'yii\web\YiiAsset',
            // 'yii\bootstrap\BootstrapAsset',
        ];
    }
