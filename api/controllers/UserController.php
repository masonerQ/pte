<?php

    namespace api\controllers;

    use Yii;

    class UserController extends BaseActiveController
    {
        public $modelClass = 'common\models\User';


        public function actionIndex()
        {
            return [
                'code' => 200,
                'msg'  => 'index'
            ];
        }

        public function actionTest()
        {
            return [
                'code' => 200,
                'msg'  => 'test'
            ];
        }

    }