<?php


    namespace backend\controllers;


    use backend\models\UploadForm;
    use common\models\JijingClass;
    use Yii;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;

    class JjController extends BaseController
    {

        public function actionList()
        {
            $data         = [];
            $where        = [];
            $keywords     = Yii::$app->request->get('keywords');
            $query        = JijingClass::find()->where($where)->andWhere(['like', 'video_title', "$keywords"]);
            $pagination   = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $data['list'] = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            // var_dump($data['list'] );
            $data['pages']    = $pagination;
            $data['keywords'] = $keywords;
            return $this->render('list', $data);
        }


        public function actionAdd()
        {
            $JijingClass = new JijingClass();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $JijingClass->year          = Yii::$app->request->post('year');
                $JijingClass->video_title   = Yii::$app->request->post('video_title');
                $JijingClass->video_content = Yii::$app->request->post('video_content');
                $JijingClass->video_cover   = Yii::$app->request->post('video_cover');
                $JijingClass->video_link    = Yii::$app->request->post('video_link');
                if ($JijingClass->save()) {
                    return ['code' => 200, 'msg' => '添加成功'];
                }
                $errors = array_values($JijingClass->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            // $query = VideoClassCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            // $this->getTree($query, 0, $option, 0);
            $option = '';
            for ($i = 2010; $i <= 2020; $i++) {
                $option .= "<option value='" . $i . "'>$i</option>";
            }
            return $this->render('add', ['model' => $JijingClass, 'option' => $option]);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         */
        public function actionEdit()
        {
            $id          = Yii::$app->request->get('id', 0);
            $JijingClass = JijingClass::findOne($id);
            if (!$JijingClass) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $JijingClass->year          = Yii::$app->request->post('year');
                $JijingClass->video_title   = Yii::$app->request->post('video_title');
                $JijingClass->video_content = Yii::$app->request->post('video_content');
                $JijingClass->video_cover   = Yii::$app->request->post('video_cover');
                $JijingClass->video_link    = Yii::$app->request->post('video_link');
                if ($JijingClass->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($JijingClass->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            // $query = $JijingClass::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            // $this->getTree($query, 0, $option, $JijingClass->cate_id);

            $option = '';
            for ($i = 2010; $i <= 2020; $i++) {
                $select = '';
                if ($JijingClass->year == $i) {
                    $select = ' selected';
                }
                $option .= "<option value=" . $i . " $select>$i</option>";
            }
            return $this->render('edit', ['model' => $JijingClass, 'option' => $option]);
        }


        public function actionDel()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && JijingClass::updateAll(['status' => JijingClass::STATUS_DELETED], ['id' => $ids])) {
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
                if (is_array($ids) && JijingClass::updateAll(['status' => JijingClass::STATUS_ACTIVE], ['id' => $ids])) {
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
                    return ['code' => 203, 'msg' => '上传失败' . $errors[0], 'filename' => ''];
                }
            }
            return ['code' => 204, 'msg' => '非法操作', 'filename' => ''];
        }


    }