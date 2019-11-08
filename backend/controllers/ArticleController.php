<?php


    namespace backend\controllers;


    use backend\models\UploadForm;
    use common\models\Article;
    use common\models\ArticleCate;
    use common\models\OnlineExerciseCate;
    use common\models\Teacher;
    use Yii;
    use yii\base\Exception;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;

    class ArticleController extends BaseController
    {

        public function actionCate()
        {
            $data          = [];
            $where         = ['parent_id' => 0];
            $query         = ArticleCate::find()->where($where)->with('child');
            $pagination    = new Pagination(['totalCount' => $query->count(), 'pagesize' => '40']);
            $list          = $query->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
            $data['list']  = $list;
            $data['pages'] = $pagination;
            return $this->render('cate', $data);
        }


        public function actionCateAdd()
        {
            $ArticleCate = new ArticleCate();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $ArticleCate->cate_name     = Yii::$app->request->post('cate_name');
                if ($ArticleCate->save()) {
                    return ['code' => 200, 'msg' => '添加成功'];
                }
                $errors = array_values($ArticleCate->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }
            return $this->render('cate-add', ['model' => $ArticleCate, 'error' => $ArticleCate->getErrors()]);
        }


        /**
         * @return array|string
         * @throws NotFoundHttpException
         */
        public function actionCateEdit()
        {
            $id          = Yii::$app->request->get('id', 0) ?: Yii::$app->request->post('id', 0);
            $ArticleCate = ArticleCate::findOne($id);
            if (!$ArticleCate) {
                throw new NotFoundHttpException('未找到......');
            }
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $ArticleCate->cate_name     = Yii::$app->request->post('cate_name');
                if ($ArticleCate->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($ArticleCate->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }
            $query = ArticleCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, 0);
            return $this->render('cate-edit', ['model' => $ArticleCate, 'option' => $option, 'error' => $ArticleCate->getErrors()]);
        }


        public function actionList()
        {
            $data       = [];
            $where      = [];
            $keywords   = Yii::$app->request->get('keywords');
            $query      = Article::find()->where($where)->andWhere(['like', 'article_name', "$keywords"]);
            $pagination = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $list       = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            foreach ($list as $key => &$value) {
                $article_name           = preg_replace("/<img.*?>/si", "", $value->article_name);
                $article_name           = mb_substr($article_name, 0, 150, 'utf8') . '...';
                $value->article_name    = $article_name;
                $article_content        = preg_replace("/<img.*?>/si", "", $value->article_content);
                $article_content        = mb_substr($article_content, 0, 150, 'utf8') . '...';
                $value->article_content = $article_content;
            }

            $data['list']     = $list;
            $data['pages']    = $pagination;
            $data['keywords'] = $keywords;
            return $this->render('list', $data);
        }

        public function actionAdd()
        {
            $article = new Article();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $article->cate_id           = Yii::$app->request->post('cate_id');
                $article->nivo              = Yii::$app->request->post('nivo') == 1 ? Yii::$app->request->post('nivo') : 2;
                $article->avatar            = Yii::$app->request->post('avatar');
                $article->article_name      = Yii::$app->request->post('article_name');
                $article->article_content   = Yii::$app->request->post('article_content');
                if ($article->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($article->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            $query = ArticleCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, 0);
            return $this->render('add', ['model' => $article, 'option' => $option]);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         * @throws Exception
         */
        public function actionEdit()
        {
            $id      = Yii::$app->request->get('id', 0);
            $article = Article::findOne($id);
            if (!$article) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $article->cate_id           = Yii::$app->request->post('cate_id');
                $article->nivo              = Yii::$app->request->post('nivo') == 1 ? Yii::$app->request->post('nivo') : 2;
                $article->avatar            = Yii::$app->request->post('avatar');
                $article->article_name      = Yii::$app->request->post('article_name');
                $article->article_content   = Yii::$app->request->post('article_content');
                if ($article->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($article->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            $query = ArticleCate::find()->where(['parent_id' => 0])->with('child')->asArray()->all();
            $this->getTree($query, 0, $option, $article->cate_id);

            return $this->render('edit', ['model' => $article, 'option' => $option]);
        }


        public function actionDel()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && Article::updateAll(['status' => Article::STATUS_DELETED], ['id' => $ids])) {
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
                if (is_array($ids) && Article::updateAll(['status' => Article::STATUS_ACTIVE], ['id' => $ids])) {
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