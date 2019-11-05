<?php

    namespace backend\models;

    use Yii;
    use yii\base\Exception;
    use yii\base\Model;
    use common\models\User;
    use common\models\EmailSendLog;

    /**
     * Signup form
     */
    class SignupForm extends Model
    {
        public $email;
        public $username;
        public $password;
        public $code;


        /**
         * {@inheritdoc}
         */
        public function rules()
        {
            return [
                ['username', 'trim'],
                ['username', 'required'],
                ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '昵称已存在'],
                ['username', 'string', 'min' => 2, 'max' => 255],
                ['email', 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'string', 'max' => 255],
                ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '此邮箱已存在'],

                ['password', 'required'],
                ['password', 'string', 'min' => 6],

                // ['code', 'required'],
                // ['code', 'string', 'min' => 6, 'max' => 6],
            ];
        }

        /**
         * Signs user up.
         *
         * @return bool whether the creating new account was successful and email was sent
         * @throws Exception
         */
        public function signup()
        {
            if (!$this->validate()) {
                return null;
            }

            $transaction = Yii::$app->db->beginTransaction();

            $user           = new User();
            $user->username = $this->username;
            $user->email    = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->verification_token = $this->code;
            // $user->generateEmailVerificationToken();
            // && $this->sendEmail($user)

            // $EmailSendLog->active        = 2;
            // $EmailSendLog->bind_username = $this->username;

            if ($user->save()) {
                $transaction->commit();
                return true;
            }
            $transaction->rollBack();
            return false;
        }

        /**
         * Sends confirmation email to user
         *
         * @param User $user user model to with email should be send
         * @return bool whether the email was sent
         */
        protected function sendEmail($user)
        {
            // var_dump($user->verification_token);
            // return  true;
            return Yii::$app->mailer->compose(['html' => 'emailVerify-html', 'text' => 'emailVerify-text'], ['user' => $user])
                                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' 自动发送(请勿回复)'])
                                    ->setTo($this->email)
                                    ->setCharset('utf-8')
                                    ->setSubject(Yii::$app->name . '账户激活')
                                    ->send();
        }


        public function attributeLabels()
        {
            return [
                'username' => '昵称',
                'email'    => '邮箱',
                'password' => '密码',
                'code'     => '验证码'
            ]; // TODO: Change the autogenerated stub
        }

    }
