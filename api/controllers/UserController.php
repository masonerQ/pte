<?php

    namespace api\controllers;

    use api\models\PasswordResetRequestForm;
    use api\models\ResetPasswordForm;
    use api\models\SignupForm;
    use common\models\EmailSendLog;
    use common\models\LoginForm;
    use common\models\User;
    use Yii;
    use yii\base\InvalidArgumentException;
    use yii\db\Exception;
    use yii\web\BadRequestHttpException;

    class UserController extends BaseActiveController
    {
        public $modelClass = 'common\models\User';

        protected function verbs()
        {
            $verbs                  = parent::verbs();
            $verbs['login']         = ['POST'];
            $verbs['register']      = ['POST'];
            $verbs['send-email']    = ['POST'];
            $verbs['rest-password'] = ['POST'];
            $verbs['get-user']      = ['GET'];
            return $verbs;
        }

        public function behaviors()
        {
            $behaviors                              = parent::behaviors();
            $behaviors['authenticator']['optional'] = [
                'login',
                'register',
                'send-email',
                'rest-password'
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

        /**
         * 注册接口
         *
         * @throws \yii\base\Exception
         * @author 郭锋
         */
        public function actionRegister()
        {
            $UserFormModel = new SignupForm();
            if ($UserFormModel->load(Yii::$app->request->post(), '') && $UserFormModel->signup()) {
                Yii::$app->response->statusText = '注册成功';
            } else {
                Yii::$app->response->statusCode = 203;
                $errorValues                    = array_values($UserFormModel->getFirstErrors());
                Yii::$app->response->statusText = '注册失败:' . $errorValues[0];
            }
        }

        /**
         * 登录
         *
         * @return array|null
         * @author 郭锋
         */
        public function actionLogin()
        {
            $loginForm = new LoginForm();
            if ($loginForm->load(Yii::$app->request->post(), '') && $loginForm->login()) {
                Yii::$app->response->statusText = '登录成功';
                return [
                    'access_token' => Yii::$app->user->identity->access_token
                ];
            } else {
                $errorsValue                    = array_values($loginForm->getFirstErrors());
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '登录失败:' . $errorsValue[0];
                return null;
            }
        }


        /**
         * 发送验证码
         *
         * @author 郭锋
         */
        public function actionSendEmail()
        {
            $email = Yii::$app->request->post('email');
            $type  = Yii::$app->request->post('type');

            if (!$email || !$type || !in_array(
                    $type,
                    [1, 2]
                )) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '参数错误';
                return null;
            }

            if ($type == 2 && !(User::findByEmail($email))) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '您还未注册, 请注册';
                return null;
            }

            $EmailSendLog = EmailSendLog::find()
                                        ->where(['email_address' => $email, 'type' => $type, 'active' => 1])
                                        ->one();
            if ($EmailSendLog && $EmailSendLog->created_at + 3600 > time()) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '您已经发送过验证码了';
                return null;
            }

            $verification_token = $this->generateCode();
            $transaction        = Yii::$app->db->beginTransaction();

            $isSend = Yii::$app->mailer->compose(['html' => 'register-html'], ['verification_token' => $verification_token])
                                       ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' 自动发送(请勿回复)'])
                                       ->setTo([$email => '哈哈'])
                                       ->setCharset('utf-8')
                                       ->setSubject('注册验证码 ' . Yii::$app->name)
                                       ->send();

            $EmailSendLogModel                     = new EmailSendLog();
            $EmailSendLogModel->email_address      = $email;
            $EmailSendLogModel->verification_token = $verification_token;
            $EmailSendLogModel->type               = $type;

            try {
                if ($isSend && $EmailSendLogModel->save()) {
                    Yii::$app->response->statusText = '发送成功';
                    $transaction->commit();
                } else {
                    Yii::$app->response->statusText = '发送失败';
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '发送失败:' . $e->getMessage();
                $transaction->rollBack();
            }
        }

        /**
         * 重置密码
         *
         * @throws \yii\base\Exception
         */
        public function actionRestPassword()
        {
            $model = new ResetPasswordForm();
            if ($model->load(Yii::$app->request->post(), '') && $model->validate() && $model->resetPassword()) {
                Yii::$app->response->statusText = '重置成功';
            } else {
                Yii::$app->response->statusCode = 203;
                $errorsValue                    = array_values($model->getFirstErrors());
                Yii::$app->response->statusText = '重置失败:' . $errorsValue[0];
            }
        }


        public function actionGetUser()
        {
            $userInfo = Yii::$app->user->identity;
            return [
                'id'           => $userInfo['id'],
                'username'     => $userInfo['username'],
                'email'        => $userInfo['email'],
                'access_token' => $userInfo['access_token'],
            ];
        }
    }