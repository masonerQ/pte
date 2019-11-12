<?php


    namespace api\controllers;


    class HomeController extends BaseActiveController
    {
        public $modelClass = 'common\models\OnlineExercise';

        public function behaviors()
        {
            $behaviors                              = parent::behaviors();
            $behaviors['authenticator']['optional'] = ['index', 'cate', 'view'];
            return $behaviors;
        }


        protected function verbs()
        {
            $verbs               = parent::verbs();
            $verbs['index']      = ['GET'];
            $verbs['cate']       = ['GET'];
            $verbs['comment']    = ['POST', 'OPTIONS'];
            $verbs['pass-exam']  = ['POST', 'OPTIONS'];
            $verbs['collection'] = ['POST', 'OPTIONS'];
            return $verbs;
        }


        public function actionIndex(){}


    }
