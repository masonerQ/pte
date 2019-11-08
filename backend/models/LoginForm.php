<?php

    namespace backend\models;

    use Yii;
    use yii\base\Model;
    use common\models\Admin;

    /**
     * Login form
     */
    class LoginForm extends Model
    {
        public $username;
        public $email;
        public $password;
        public $rememberMe = true;

        private $_admin;


        /**
         * {@inheritdoc}
         */
        public function rules()
        {
            return [
                // username and password are both required
                [['username', 'password'], 'required', 'message'=>'{attribute}不能为空'],
                // rememberMe must be a boolean value
                ['rememberMe', 'boolean'],
                // password is validated by validatePassword()
                ['password', 'validatePassword'],
            ];
        }

        /**
         * Validates the password.
         * This method serves as the inline validation for password.
         *
         * @param string $attribute the attribute currently being validated
         * @param array  $params    the additional name-value pairs given in the rule
         */
        public function validatePassword($attribute, $params)
        {
            if (!$this->hasErrors()) {
                $user = $this->getUser();
                if (!$user || !$user->validatePassword($this->password)) {
                    $this->addError($attribute, '错误的邮箱账户或密码');
                }
            }
        }

        /**
         * Logs in a user using the provided username and password.
         *
         * @return bool whether the user is logged in successfully
         */
        public function login()
        {
            if ($this->validate()) {
                $this->_admin->generateAccessToken();
                Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                return $this->_admin->save();
            }

            return false;
        }

        /**
         * Finds user by [[username]]
         *
         * @return Admin|null
         */
        protected function getUser()
        {
            if ($this->_admin === null) {
                $this->_admin = Admin::findByUsername($this->username);
            }

            return $this->_admin;
        }


        public function attributeLabels()
        {
            return [
                'email' => '邮箱'
            ];
        }


    }
