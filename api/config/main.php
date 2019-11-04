<?php
    $params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/../../common/config/params-local.php',
        require __DIR__ . '/params.php',
        require __DIR__ . '/params-local.php'
    );

    $rules = require __DIR__ . '/UrlRules.php';

    return [
        'id'                  => 'app-api',
        'name'                => '无界教育',
        'basePath'            => dirname(__DIR__),
        'bootstrap'           => ['log'],
        'controllerNamespace' => 'api\controllers',
        'components'          => [
            'request'    => [
                'class'     => '\yii\web\Request',
                // 'csrfParam' => '_csrf-api',
                'parsers'   => [
                    'application/json' => 'yii\web\JsonParser',
                    'text/json'        => 'yii\web\JsonParser'
                ]
            ],
            'response'   => [
                'class'         => 'yii\web\Response',
                'format'        => yii\web\Response::FORMAT_JSON,
                'charset'       => 'UTF-8',
                'as beforeSend' => [
                    'class' => 'api\extensions\BeforeSendBehavior'
                ]
            ],
            'user'       => [
                'identityClass'   => 'common\models\User',
                'enableAutoLogin' => false,
                'enableSession'   => false,
                'loginUrl'        => null
                //'identityCookie'  => [
                //    'name'     => '_identity-api',
                //    'httpOnly' => true
                //],
            ],
            //'session' => [
            //    // this is the name of the session cookie used for login on the api
            //    'name' => 'advanced-api',
            //],
            'log'        => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets'    => [
                    [
                        'class'  => 'yii\log\FileTarget',
                        'levels' => [
                            'error',
                            'warning'
                        ],
                    ],
                ],
            ],
            //'errorHandler' => [
            //    'errorAction' => 'site/error',
            //],
            'urlManager' => [
                'class'               => 'yii\web\UrlManager',
                'enablePrettyUrl'     => true,
                'enableStrictParsing' => true,
                'showScriptName'      => false,
                'rules'               => $rules
            ],
        ],
        'params'              => $params,
    ];
