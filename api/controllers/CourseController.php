<?php


    namespace api\controllers;


    class CourseController extends BaseActiveController
    {
        public $modelClass = 'common\models\Course';

        public function behaviors()
        {
            $behaviors = parent::behaviors();
            $behaviors['authenticator']['optional'] = ['index'];
            return $behaviors;
        }

        public function actionIndex()
        {
            return [
                'code' => 500,
                'msg'  => '这是course/index'
            ];
        }

        public function actionTest()
        {
            return [
                'code' => 200,
                'msg'  => '这是course/test'
            ];
        }

    }