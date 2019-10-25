<?php
    $params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/../../common/config/params-local.php',
        require __DIR__ . '/params.php',
        require __DIR__ . '/params-local.php'
    );

    return [
        'id'                  => 'app-api',
        'name'                => '无界教育',
        'basePath'            => dirname(__DIR__),
        'bootstrap'           => ['log'],
        'controllerNamespace' => 'api\controllers',
        'components'          => [
            'request'    => [
                'csrfParam' => '_csrf-api',
                'parsers'   => [
                    'application/json' => 'yii\web\JsonParser',
                ]
            ],
            'response'   => [
                'class'         => 'yii\web\Response',
                // 'as resBeforeSend' => [
                //     'class'         => 'api\extensions\ResBeforeSendBehavior',
                //     'defaultCode'   => 500,
                //     'defaultMsg'    => 'error',
                // ],
                'on beforeSend' => function ($event) {
                    $response = $event->sender;
                    $data     = null;
                    $message  = $response->statusText;
                    if ($response->statusCode == 200) {
                        $data = $response->data;
                    } else {
                        if ($response->statusCode == 401) {
                            $message = '授权信息不正确';
                        }
                    }
                    $response->data       = [
                        'success' => $response->isSuccessful,
                        'code'    => $response->getStatusCode(),
                        'message' => $message,
                        'data'    => $data,
                    ];
                    $response->statusCode = 200;
                }
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
                'enablePrettyUrl'     => true,
                'enableStrictParsing' => true,
                'showScriptName'      => false,
                'rules'               => [
                    // '/<controller:\w+>/<action:\w+>' => '/<controller>/<action>',
                    [
                        'class'         => 'yii\rest\UrlRule',
                        'controller'    => [
                            'user',
                            'course'
                        ],
                        'pluralize'     => false,
                        'except'        => [],
                        'extraPatterns' => [
                            'abc'   => 'index',
                            'ttt'   => 'test',
                            'login' => 'login',
                            'reg'   => 'register',
                        ]
                    ],
                ],
            ],
        ],
        'params'              => $params,
    ];
