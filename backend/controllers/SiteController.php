<?php

    namespace backend\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\filters\VerbFilter;
    use yii\filters\AccessControl;
    use common\models\LoginForm;

    /**
     * Site controller
     */
    class SiteController extends Controller
    {
        /**
         * {@inheritdoc}
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => [
                                'login',
                                'error'
                            ],
                            'allow'   => true,
                        ],
                        [
                            'actions' => [
                                'logout',
                                'index',
                                'test',
                                'main'
                            ],
                            'allow'   => true,
                            'roles'   => ['@'],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],
                ],
            ];
        }

        /**
         * {@inheritdoc}
         */
        public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
            ];
        }

        /**
         * Displays homepage.
         *
         * @return string
         */
        public function actionIndex()
        {
            // $this->view->title = '标题';
            return $this->render('index');
        }

        public function actionTest()
        {
            $this->layout = false;
            return $this->render('test');
        }

        public function actionMain()
        {
            return $this->render('main');
        }

        /**
         * Login action.
         *
         * @return string
         */
        public function actionLogin()
        {
            if (!Yii::$app->user->isGuest) {
                return $this->goHome();
            }

            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            } else {
                $model->password = '';

                return $this->renderPartial(
                    'login',
                    [
                        'model' => $model,
                    ]
                );
            }
        }

        /**
         * Logout action.
         *
         * @return string
         */
        public function actionLogout()
        {
            Yii::$app->user->logout();

            return $this->goHome();
        }
    }
