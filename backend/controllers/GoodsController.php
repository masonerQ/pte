<?php


    namespace backend\controllers;

    use backend\models\SignupForm;
    use backend\models\UploadForm;
    use common\models\ArticleCate;
    use common\models\Goods;
    use common\models\GoodsCate;
    use common\models\User;
    use Yii;
    use yii\base\Exception;
    use yii\web\Response;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use common\models\OnlineExerciseCate;

    class GoodsController extends BaseController
    {

        public function actionCate()
        {
            $data          = [];
            $where         = ['parent_id' => 0];
            $query         = GoodsCate::find()->where($where)->with('child');
            $pagination    = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $list          = $query->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
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
            $id        = Yii::$app->request->get('id', 0) ?: Yii::$app->request->post('id', 0);
            $GoodsCate = GoodsCate::findOne($id);
            if (!$GoodsCate) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $GoodsCate->cate_name       = Yii::$app->request->post('cate_name');
                if ($GoodsCate->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($GoodsCate->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            return $this->render('cate-edit', ['model' => $GoodsCate, 'error' => $GoodsCate->getErrors()]);
        }


        public function actionCateAdd()
        {
            $GoodsCate = new GoodsCate();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $GoodsCate->cate_name       = Yii::$app->request->post('cate_name');
                if ($GoodsCate->save()) {
                    return ['code' => 200, 'msg' => '添加成功'];
                }
                $errors = array_values($GoodsCate->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            return $this->render('cate-add', ['model' => $GoodsCate]);
        }


        public function actionList()
        {
            $data         = [];
            $where        = [];
            $keywords     = Yii::$app->request->get('keywords');
            $query        = Goods::find()->where($where)->andWhere(['like', 'goods_title', "$keywords"]);
            $pagination   = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $data['list'] = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            // var_dump($data['list'] );
            $data['pages']    = $pagination;
            $data['keywords'] = $keywords;
            return $this->render('list', $data);
        }

        public function actionAdd()
        {
            $goods = new Goods();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $goods->cate_id             = Yii::$app->request->post('cate_id');
                $goods->goods_title         = Yii::$app->request->post('goods_title');
                $goods->goods_content       = Yii::$app->request->post('goods_content');
                $goods->goods_cover         = Yii::$app->request->post('goods_cover');
                $goods->goods_link          = Yii::$app->request->post('goods_link');
                $goods->goods_price         = Yii::$app->request->post('goods_price');
                if ($goods->save()) {
                    return ['code' => 200, 'msg' => '添加成功'];
                }
                $errors = array_values($goods->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            $query = GoodsCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, 0);
            return $this->render('add', ['model' => $goods, 'option' => $option]);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         * @throws Exception
         */
        public function actionEdit()
        {
            $id    = Yii::$app->request->get('id', 0);
            $goods = Goods::findOne($id);
            if (!$goods) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $goods->cate_id             = Yii::$app->request->post('cate_id');
                $goods->goods_title         = Yii::$app->request->post('goods_title');
                $goods->goods_content       = Yii::$app->request->post('goods_content');
                $goods->goods_cover         = Yii::$app->request->post('goods_cover');
                $goods->goods_link          = Yii::$app->request->post('goods_link');
                $goods->goods_price         = Yii::$app->request->post('goods_price');
                if ($goods->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($goods->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            $query = GoodsCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, $goods->cate_id);

            return $this->render('edit', ['model' => $goods, 'option' => $option]);
        }


        public function actionDel()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && Goods::updateAll(['status' => Goods::STATUS_DELETED], ['id' => $ids])) {
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
                if (is_array($ids) && Goods::updateAll(['status' => Goods::STATUS_ACTIVE], ['id' => $ids])) {
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
                    return ['code' => 203, 'msg' => '上传失败', 'filename' => ''];
                }
            }
            return ['code' => 204, 'msg' => '非法操作', 'filename' => ''];
        }

    }