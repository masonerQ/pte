<?php


    namespace api\controllers;


    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\data\Pagination;

    class BannerController extends BaseActiveController
    {
        public $modelClass = 'common\models\Banner';

        public function behaviors()
        {
            $behaviors                              = parent::behaviors();
            $behaviors['authenticator']['optional'] = ['index'];
            return $behaviors;
        }


        protected function verbs()
        {
            $verbs          = parent::verbs();
            $verbs['index'] = ['GET'];
            $verbs['view']  = ['GET'];
            return $verbs;
        }

        public function actions()
        {
            $actions = parent::actions();
            unset($actions['index']);
            unset($actions['view']);
            return $actions;
        }


        public function actionIndex()
        {
            $where = [];

            $model = ($this->modelClass)::find()->select(['id', 'title', 'url', 'img_link'])->where($where);
            return new ActiveDataProvider(['query' => $model, 'pagination' => new Pagination(['pageSize' => 20])]);
        }


    }
