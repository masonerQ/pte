<?php

    namespace api\controllers;

    use api\models\SignupForm;
    use common\models\LoginForm;
    use Yii;

    class UserController extends BaseActiveController
    {
        public $modelClass = 'common\models\User';

        public function behaviors()
        {
            $behaviors                              = parent::behaviors();
            $behaviors['authenticator']['optional'] = [
                'login',
                'register'
            ];
            return $behaviors;
        }

        public function actionIndex()
        {
            $modelClass = $this->modelClass;
            $result     = $modelClass::findOne(1);
            return $result;
        }

        public function actionTest()
        {
            Yii::$app->response->statusText = '注册成功';
            return [
                'code' => 200,
                'msg'  => 'test'
            ];
        }

        public function actionRegister()
        {
            $username      = Yii::$app->request->post('username');
            $password      = Yii::$app->request->post('password');
            $UserFormModel = new SignupForm();
            if ($UserFormModel->load(Yii::$app->request->post(), '') && $UserFormModel->signup()) {
                Yii::$app->response->statusText = '注册成功';
            } else {
                Yii::$app->response->statusCode = 304;
                Yii::$app->response->statusText = $UserFormModel->getErrors();
                // return ['error_code'=>'2000', 'res_msg'=>'asdfa', 'content'=>'asdf'];
            }
        }

        public function actionLogin()
        {
            $loginForm = new LoginForm();
            if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()){

            }
            return [
                'code' => 200,
                'msg'  => '这是登录'
            ];
        }

    }