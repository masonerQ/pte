<?php

    return [
        // '/<controller:\w+>/<action:\w+>' => '/<controller>/<action>',
        [
            'class'         => 'yii\rest\UrlRule',
            'controller'    => [
                'user',
                'course',
                'teacher',
                'article',
                'goods',
                'video-class',
                'pte-online-exercise',
            ],
            'pluralize'     => false,
            'except'        => [],
            'extraPatterns' => [
                'index'     => 'index',
                'view'      => 'view',
                'login'     => 'login',
                'reg'       => 'register',
                'sendemail' => 'send-email',
                'restpwd'   => 'rest-password',
                'list'      => 'list',
                'classcate' => 'video-class-cate',
                'getuser'   => 'get-user',
            ]
        ]
    ];