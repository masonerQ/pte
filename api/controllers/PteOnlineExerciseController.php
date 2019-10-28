<?php


    namespace api\controllers;


    class PteOnlineExerciseController extends BaseActiveController
    {

        public $modelClass = 'common\models\OnlineExercise';

        public function behaviors()
        {
            $behaviors                              = parent::behaviors();
            $behaviors['authenticator']['optional'] = ['*'];
            return $behaviors;
        }

    }