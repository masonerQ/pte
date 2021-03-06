<?php


    namespace api\controllers;

    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\data\Pagination;
    use common\models\ArticleCate;

    class ArticleController extends BaseActiveController
    {
        public $modelClass = 'common\models\Article';


        public function behaviors()
        {
            $behaviors                              = parent::behaviors();
            $behaviors['authenticator']['optional'] = ['index','view', 'cate'];
            return $behaviors;
        }

        protected function verbs()
        {
            $verbs          = parent::verbs(); // TODO: Change the autogenerated stub
            $verbs['cate']  = ['GET'];
            $verbs['index'] = ['GET'];
            return $verbs;
        }

        public function actions()
        {
            $actions = parent::actions();
            unset($actions['index']);
            return $actions;
        }


        public function actionIndex()
        {
            $where   = [];
            $nivo    = Yii::$app->request->get('nivo');
            $cate_id = Yii::$app->request->get('cate_id');

            if ($nivo) {
                $where['nivo'] = $nivo;
            }

            if ($cate_id) {
                $where['cate_id'] = $cate_id;
            }

            $model = ($this->modelClass)::find()
                                        ->select(['id', 'cate_id', 'avatar', 'article_name', 'article_content', 'created_at'])
                                        ->where($where);

            return new ActiveDataProvider(['query' => $model, 'pagination' => new Pagination(['pageSize' => 20])]);
        }


        /**
         * @desc 分类列表
         * @return ActiveDataProvider
         */
        public function actionCate()
        {
            $where = ['parent_id' => 0];
            $model = ArticleCate::find()
                                ->select(['id', 'parent_id', 'cate_name', 'cate_desc'])
                                ->where($where)
                                ->asArray();

            return new ActiveDataProvider(['query' => $model, 'pagination' => new Pagination(['pageSize' => 20])]);
        }


    }