<?php


    namespace backend\controllers;

    use backend\models\SignupForm;
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


        /**
         * @return array|string
         * @throws NotFoundHttpException
         * @throws \Throwable
         * @throws \yii\db\StaleObjectException
         */
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
            $data             = [];
            $where            = [];
            $keywords         = Yii::$app->request->get('keywords');
            $query            = Goods::find()->where($where)->andWhere(['like', 'goods_title', "$keywords"]);
            $pagination       = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $data['list']     = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            $data['pages']    = $pagination;
            $data['keywords'] = $keywords;
            return $this->render('list', $data);

        }

        public function actionAdd()
        {
            $Signup = new SignupForm();
            $Signup->setScenario('add');
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                if ($Signup->load(Yii::$app->request->post(), '') && $Signup->signup()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['code' => 200, 'msg' => '添加用户成功'];
                }
            }
            return $this->render('add', ['model' => $Signup]);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         * @throws Exception
         */
        public function actionEdit()
        {
            $Signup = new SignupForm();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($Signup->load(Yii::$app->request->post(), '') && $Signup->update()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($Signup->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            } else {
                $id   = Yii::$app->request->get('id', 0);
                $user = User::findOne($id);
                if (!$user) {
                    throw new NotFoundHttpException('未找到......');
                }
                return $this->render('edit', ['model' => $user, 'error' => $Signup->getErrors()]);
            }
        }


        public function actionDel()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && User::updateAll(['status' => User::STATUS_DELETED], ['id' => $ids])) {
                    return ['code' => 200, 'msg' => '删除成功'];
                } else {
                    return ['code' => 203, 'msg' => '删除失败'];
                }
            }
            return ['code' => 203, 'msg' => '非法操作'];
        }

    }