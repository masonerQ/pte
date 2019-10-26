<?php


    namespace api\controllers;

    use common\models\VideoClassCate;
    use yii\data\ActiveDataProvider;
    use yii\data\Pagination;

    class VideoClassController extends BaseActiveController
    {
        public $modelClass = 'common\models\VideoClass';

        public function actionVideoClassCate()
        {
            $model = VideoClassCate::find()->select(['id', 'parent_id', 'cate_name', 'cate_desc']);
            return new ActiveDataProvider(
                [
                    'query'      => $model,
                    'pagination' => new Pagination(['pageSize' => 20])
                ]
            );
        }
    }