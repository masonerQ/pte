<?php


    namespace backend\controllers;


    use backend\models\UploadForm;
    use common\models\Banner;
    use Yii;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;

    class BannerController extends BaseController
    {
        public function actionList()
        {
            $where         = [];
            $query         = Banner::find()->where($where);
            $pagination    = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $list          = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            $data['list']  = $list;
            $data['pages'] = $pagination;
            return $this->render('list', $data);
        }

        public function actionAdd()
        {
            $data  = [];
            $model = new Banner();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->title               = Yii::$app->request->post('title');
                $model->url                 = Yii::$app->request->post('url');
                $model->img_link            = Yii::$app->request->post('img_link');
                if (!$model->save()) {
                    return ['code' => 203, 'msg' => '操作失败'];
                }
                return ['code' => 200, 'msg' => '操作成功'];
            }

            $data['model'] = $model;
            return $this->render('add', $data);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         */
        public function actionEdit()
        {
            $id = Yii::$app->request->get('id') ? Yii::$app->request->get('id') : Yii::$app->request->post('id');
            // var_dump($id);
            // exit;
            $model = Banner::findOne($id);
            if (!$model) {
                throw new NotFoundHttpException('未找到');
            }

            $data          = [];
            $data['model'] = $model;

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $id                         = Yii::$app->request->post('id');
                $title                      = Yii::$app->request->post('title');
                $url                        = Yii::$app->request->post('url');
                $img_link                   = Yii::$app->request->post('img_link');
                $isUpdate                   = Banner::updateAll(
                    ['status' => 2, 'url' => $url, 'title' => $title, 'img_link' => $img_link],
                    ['id' => $id]
                );
                if (!$isUpdate) {
                    return ['code' => 203, 'msg' => '操作失败'];
                }
                return ['code' => 200, 'msg' => '操作成功'];
            }


            return $this->render('edit', $data);
        }


        public function actionDel()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && Banner::updateAll(['status' => Banner::STATUS_DELETED], ['id' => $ids])) {
                    return ['code' => 200, 'msg' => '下线成功'];
                } else {
                    return ['code' => 203, 'msg' => '下线失败'];
                }
            }
            return ['code' => 203, 'msg' => '非法操作'];
        }


        public function actionStart()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && Banner::updateAll(['status' => Banner::STATUS_ACTIVE], ['id' => $ids])) {
                    return ['code' => 200, 'msg' => '上线成功'];
                } else {
                    return ['code' => 203, 'msg' => '上线失败'];
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
                    return ['code' => 203, 'msg' => '上传失败', 'filename' => ''];
                }
            }
            return ['code' => 204, 'msg' => '非法操作', 'filename' => ''];
        }

    }
