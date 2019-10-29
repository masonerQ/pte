<?php


    namespace api\controllers;

    use common\models\VideoClassCate;
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\data\Pagination;

    class ArticleController extends BaseActiveController
    {
        public $modelClass = 'common\models\Article';

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

            if ($cate_id) {
                $where['cate_id'] = $cate_id;
            }

            if ($nivo) {
                $where['nivo'] = $nivo;
            }

            $model = ($this->modelClass)::find()
                                        ->select(
                                            ['id', 'cate_id', 'avatar', 'article_name', 'article_content', 'created_at']
                                        )
                                        ->where($where);

            return new ActiveDataProvider(
                ['query'      => $model,
                 'pagination' => new Pagination(['pageSize' => 20])
                ]
            );
        }

    }