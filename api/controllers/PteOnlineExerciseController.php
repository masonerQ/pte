<?php


    namespace api\controllers;


    use yii\data\ActiveDataProvider;
    use yii\data\Pagination;
    use yii\db\Query;

    class PteOnlineExerciseController extends BaseActiveController
    {

        public $modelClass = 'common\models\OnlineExerciseCate';

        public function behaviors()
        {
            $behaviors                              = parent::behaviors();
            $behaviors['authenticator']['optional'] = ['*'];
            return $behaviors;
        }

        public function actions()
        {
            $actions = parent::actions();
            unset($actions['index']);
            return $actions;
        }


        public function actionIndex()
        {

            $where = ['parent_id' => 0];
            $model = ($this->modelClass)::find()
                                        ->select(['id', 'parent_id', 'cate_name', 'cate_desc'])
                                        ->where($where)
                                        ->asArray()
                                        ->with('child');

            return new ActiveDataProvider(['query' => $model, 'pagination' => new Pagination(['pageSize' => 20])]);
        }


    }