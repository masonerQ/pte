<?php


    namespace backend\controllers;

    use backend\models\UploadForm;
    use common\models\OnlineExercise;
    use common\models\Teacher;
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
            $pagination = new Pagination(['totalCount' => $query->count(), 'pagesize' => '40']);
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
            $data         = [];
            $where        = [];
            $keywords     = Yii::$app->request->get('keywords');
            $query        = OnlineExercise::find()->where($where)->andWhere(['like', 'title', "$keywords"]);
            $pagination   = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $data['list'] = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            // var_dump($data['list'] );
            $data['pages']    = $pagination;
            $data['keywords'] = $keywords;
            return $this->render('list', $data);
        }


        public function actionAdd()
        {
            $OnlineExercise = new OnlineExercise();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $OnlineExercise->cate_id             = Yii::$app->request->post('cate_id');
                $OnlineExercise->video_title         = Yii::$app->request->post('video_title');
                $OnlineExercise->video_content       = Yii::$app->request->post('video_content');
                $OnlineExercise->video_cover         = Yii::$app->request->post('video_cover');
                $OnlineExercise->video_link          = Yii::$app->request->post('video_link');
                if ($OnlineExercise->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($OnlineExercise->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            $query = OnlineExerciseCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, 0, true);
            return $this->render('add', ['model' => $OnlineExercise, 'option' => $option]);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         */
        public function actionEdit()
        {
            $id    = Yii::$app->request->get('id', 0);
            $OnlineExercise = OnlineExercise::findOne($id);
            if (!$OnlineExercise) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $OnlineExercise->cate_id             = Yii::$app->request->post('cate_id');
                $OnlineExercise->video_title         = Yii::$app->request->post('video_title');
                $OnlineExercise->video_content       = Yii::$app->request->post('video_content');
                $OnlineExercise->video_cover         = Yii::$app->request->post('video_cover');
                $OnlineExercise->video_link          = Yii::$app->request->post('video_link');
                if ($OnlineExercise->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($OnlineExercise->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            $query = OnlineExerciseCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, $OnlineExercise->cate_id);

            return $this->render('edit', ['model' => $OnlineExercise, 'option' => $option]);
        }


        public function actionDel()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && OnlineExercise::updateAll(['status' => OnlineExercise::STATUS_DELETED], ['id' => $ids])) {
                    return ['code' => 200, 'msg' => '下架成功'];
                } else {
                    return ['code' => 203, 'msg' => '下架失败'];
                }
            }
            return ['code' => 203, 'msg' => '非法操作'];
        }


        public function actionStart()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && OnlineExercise::updateAll(['status' => OnlineExercise::STATUS_ACTIVE], ['id' => $ids])) {
                    return ['code' => 200, 'msg' => '上架成功'];
                } else {
                    return ['code' => 203, 'msg' => '上架失败'];
                }
            }
            return ['code' => 203, 'msg' => '非法操作'];
        }


        public function actionUpload()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $UploadForm = new UploadForm();
                if ($filename = $UploadForm->upload()) {
                    $webRootUrl = Yii::$app->params['imgServer'];
                    return [
                        'code'     => 200,
                        'msg'      => '上传成功',
                        'filename' => $filename,
                        'data'     => ['src' => $webRootUrl . $filename, 'title' => '']
                    ];
                } else {
                    $errors = array_values($UploadForm->getFirstErrors());
                    // return $errors;
                    return ['code' => 203, 'msg' => '上传失败'.$errors[0], 'filename' => ''];
                }
            }
            return ['code' => 204, 'msg' => '非法操作', 'filename' => ''];
        }




    }