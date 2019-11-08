<?php


    namespace backend\controllers;

    use backend\models\UploadForm;
    use common\models\VideoClass;
    use Yii;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use common\models\VideoClassCate;

    class VideoClassController extends BaseController
    {
        public function actionCate()
        {
            $data          = [];
            $where         = ['parent_id' => 0];
            $query         = VideoClassCate::find()->where($where)->with('child');
            $pagination    = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $list          = $query->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
            $data['list']  = $list;
            $data['pages'] = $pagination;
            return $this->render('cate', $data);
        }


        /**
         * @return array|string
         * @throws NotFoundHttpException
         */
        public function actionCateEdit()
        {
            $id             = Yii::$app->request->get('id', 0) ?: Yii::$app->request->post('id', 0);
            $VideoClassCate = VideoClassCate::findOne($id);
            if (!$VideoClassCate) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $VideoClassCate->cate_name  = Yii::$app->request->post('cate_name');
                if ($VideoClassCate->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($VideoClassCate->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            return $this->render('cate-edit', ['model' => $VideoClassCate, 'error' => $VideoClassCate->getErrors()]);
        }


        /**
         * @return array|string
         */
        public function actionCateAdd()
        {
            $VideoClassCate = new VideoClassCate();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $VideoClassCate->cate_name       = Yii::$app->request->post('cate_name');
                if ($VideoClassCate->save()) {
                    return ['code' => 200, 'msg' => '添加成功'];
                }
                $errors = array_values($VideoClassCate->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            return $this->render('cate-add', ['model' => $VideoClassCate]);
        }



        public function actionList()
        {
            $data         = [];
            $where        = [];
            $keywords     = Yii::$app->request->get('keywords');
            $query        = VideoClass::find()->where($where)->andWhere(['like', 'video_title', "$keywords"]);
            $pagination   = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $data['list'] = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            // var_dump($data['list'] );
            $data['pages']    = $pagination;
            $data['keywords'] = $keywords;
            return $this->render('list', $data);
        }


        public function actionAdd()
        {
            $VideoClass = new VideoClass();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $VideoClass->cate_id             = Yii::$app->request->post('cate_id');
                $VideoClass->video_title         = Yii::$app->request->post('video_title');
                $VideoClass->video_content       = Yii::$app->request->post('video_content');
                $VideoClass->video_cover         = Yii::$app->request->post('video_cover');
                $VideoClass->video_link          = Yii::$app->request->post('video_link');
                if ($VideoClass->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($VideoClass->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            $query = VideoClassCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, 0);
            return $this->render('add', ['model' => $VideoClass, 'option' => $option]);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         */
        public function actionEdit()
        {
            $id    = Yii::$app->request->get('id', 0);
            $VideoClass = VideoClass::findOne($id);
            if (!$VideoClass) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $VideoClass->cate_id             = Yii::$app->request->post('cate_id');
                $VideoClass->video_title         = Yii::$app->request->post('video_title');
                $VideoClass->video_content       = Yii::$app->request->post('video_content');
                $VideoClass->video_cover         = Yii::$app->request->post('video_cover');
                $VideoClass->video_link          = Yii::$app->request->post('video_link');
                if ($VideoClass->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($VideoClass->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            $query = VideoClassCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, $VideoClass->cate_id);

            return $this->render('edit', ['model' => $VideoClass, 'option' => $option]);
        }


        public function actionDel()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && VideoClass::updateAll(['status' => VideoClass::STATUS_DELETED], ['id' => $ids])) {
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
                if (is_array($ids) && VideoClass::updateAll(['status' => VideoClass::STATUS_ACTIVE], ['id' => $ids])) {
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