<?php


    namespace backend\controllers;

    use Yii;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use common\models\OnlineExerciseCate;
    use common\models\User;


    class ExerciseController extends BaseController
    {
        public function actionCate()
        {
            $data       = [];
            $where      = ['parent_id' => 0];
            $query      = OnlineExerciseCate::find()->where($where)->with('child');
            $pagination = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $list       = $query->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
            $data['list']  = $list;
            $data['pages'] = $pagination;
            return $this->render('cate', $data);
        }


        /**
         * @return array|string
         * @throws NotFoundHttpException
         * @throws \Throwable
         * @throws \yii\db\StaleObjectException
         */
        public function actionCateEdit()
        {
            $id   = Yii::$app->request->get('id', 0) ?: Yii::$app->request->post('id',0);
            $ExerciseCate = OnlineExerciseCate::findOne($id);
            if (!$ExerciseCate){
                throw new NotFoundHttpException('未找到......');
            }
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $ExerciseCate->cate_name = Yii::$app->request->post('cate_name');
                if ($ExerciseCate->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($ExerciseCate->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }
            return $this->render('cate-edit', ['model' => $ExerciseCate, 'error' => $ExerciseCate->getErrors()]);
        }


        public function actionList()
        {
            $data             = [];
            $where            = [];
            $keywords         = Yii::$app->request->get('keywords');
            $query            = User::find()->where($where)->andWhere(['like', 'username', "$keywords"]);
            $pagination       = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $data['list']     = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            $data['pages']    = $pagination;
            $data['keywords'] = $keywords;
            return $this->render('list', $data);

        }

    }