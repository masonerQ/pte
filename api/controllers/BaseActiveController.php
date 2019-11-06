<?php

    namespace api\controllers;

    use yii\filters\AccessControl;
    use yii\filters\auth\CompositeAuth;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\filters\auth\QueryParamAuth;
    use yii\filters\ContentNegotiator;
    use yii\filters\Cors;
    use yii\filters\RateLimiter;
    use yii\filters\VerbFilter;
    use yii\rest\ActiveController;
    use yii\web\Response;

    class BaseActiveController extends ActiveController
    {
        public $serializer = [
            'class'              => 'yii\rest\Serializer',
            'collectionEnvelope' => 'items',
            'metaEnvelope'       => 'page',
            'linksEnvelope'      => 'link',
        ];

        protected function verbs()
        {
            $verbs          = parent::verbs();
            $verbs['index'] = ['GET'];
            $verbs['view']  = ['GET'];
            return $verbs;
        }

        public function actions()
        {
            $actions = parent::actions();
            unset($actions['create']);
            unset($actions['update']);
            return $actions;
        }

        public function behaviors()
        {
            parent::behaviors();
            $behaviors = [
                'contentNegotiator' => [
                    'class'   => ContentNegotiator::className(),
                    'formats' => [
                        'application/json' => Response::FORMAT_JSON,
                    ]
                ],
                'corsFilter'        => [
                    'class' => Cors::class,
                    'cors'  => [
                        'Origin'                           => ['*'],
                        'Access-Control-Request-Method'    => ['GET', 'POST', 'OPTIONS'],
                        'Access-Control-Request-Headers'   => ['*'],
                        'Access-Control-Allow-Credentials' => null,
                        'Access-Control-Max-Age'           => 86400
                    ]
                ],
                'verbFilter'        => [
                    'class'   => VerbFilter::className(),
                    'actions' => $this->verbs(),
                ],
                'authenticator'     => [
                    'class'       => CompositeAuth::className(),
                    'optional'    => ['video-class' => 'video-class-cate', 'index', 'view', 'cate'],
                    'authMethods' => [
                        /*下面是三种验证access_token方式*/
                        // 1.HTTP 基本认证: access token 当作用户名发送，应用在access token可安全存在API使用端的场景，例如，API使用端是运行在一台服务器上的程序。
                        'HttpBasicAuth'  => [
                            'class' => HttpBasicAuth::className()
                        ],
                        // 2.OAuth 2: 使用者从认证服务器上获取基于OAuth2协议的access token，然后通过 HTTP Bearer Tokens 发送到API 服务器。
                        'HttpBearerAuth' => [
                            'class' => HttpBearerAuth::className()
                        ],
                        // 3.请求参数: access token 当作API URL请求参数发送，这种方式应主要用于JSONP请求，因为它不能使用HTTP头来发送access token
                        'QueryParamAuth' => [
                            'class'      => QueryParamAuth::className(),
                            'tokenParam' => 'access-token'
                        ]
                    ],
                ],
                'rateLimiter'       => [
                    'class'                  => RateLimiter::className(),
                    'enableRateLimitHeaders' => true
                ]
            ];
            return $behaviors;
        }


        public function generateCode($length = 6)
        {
            $pool        = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//定义一个验证码池，验证码由其中几个字符组成
            $word_length = $length;                                                         // 验证码长度
            $code        = '';                                                              // 验证码
            for ($i = 0, $mt_rand_max = strlen($pool) - 1; $i < $word_length; $i++) {
                $code .= $pool[mt_rand(0, $mt_rand_max)];
            }
            return $code;
        }


        public function getStartEndTime($type = 'week')
        {

            $time = [];
            if ($type == 'week') {
                //当前日期
                $sdefaultDate = date("Y-m-d");
                //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
                $first = 1;
                //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
                $w = date('w', strtotime($sdefaultDate));
                //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
                $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days')) . ' 00:00:00';
                //本周结束日期
                $week_end = date('Y-m-d', strtotime("$week_start +6 days")) . ' 23:59:59';

                // echo $week_start."============".strtotime($week_end);

                $week_start_timestamp = strtotime($week_start);
                $week_end_timestamp   = strtotime($week_end);
                $time['start']        = $week_start_timestamp;
                $time['end']          = $week_end_timestamp;
            } else if ($type == 'month') {
                //获取本月开始的时间戳
                $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
                //获取本月结束的时间戳
                $endThismonth  = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
                $time['start'] = $beginThismonth;
                $time['end']   = $endThismonth;
            }

            return $time;

        }


    }