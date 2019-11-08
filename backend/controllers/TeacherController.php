<?php


    namespace backend\controllers;


    use backend\models\SignupForm;
    use backend\models\UploadForm;
    use common\models\Goods;
    use common\models\Teacher;
    use common\models\User;
    use Yii;
    use yii\base\Exception;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use yii\web\UploadedFile;

    class TeacherController extends BaseController
    {

        public function actionList()
        {
            $data             = [];
            $where            = [];
            $keywords         = Yii::$app->request->get('keywords');
            $query            = Teacher::find()->where($where)->andWhere(['like', 'name', "$keywords"]);
            $pagination       = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $list             = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            foreach ($list as $key=>&$value){
                $content = preg_replace("/<img.*?>/si","", $value->content);
                $content = mb_substr($content, 0, 150, 'utf8').'...';
                $value->content =  $content;
                $instruction = preg_replace("/<img.*?>/si","", $value->instruction);
                $instruction = mb_substr($instruction, 0, 150, 'utf8').'...';
                $value->instruction =  $instruction;
            }

            $data['list']     = $list;
            $data['pages']    = $pagination;
            $data['keywords'] = $keywords;
            return $this->render('list', $data);
        }

        public function actionAdd()
        {
            $teacher = new Teacher();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $teacher->name              = Yii::$app->request->post('name');
                $teacher->avatar            = Yii::$app->request->post('avatar');
                $teacher->content           = Yii::$app->request->post('content');
                $teacher->instruction       = Yii::$app->request->post('instruction');
                if ($teacher->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($teacher->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            return $this->render('add', ['model' => $teacher]);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         * @throws Exception
         */
        public function actionEdit()
        {
            $id      = Yii::$app->request->get('id', 0);
            $teacher = Teacher::findOne($id);
            if (!$teacher) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $teacher->name              = Yii::$app->request->post('name');
                $teacher->avatar            = Yii::$app->request->post('avatar');
                $teacher->content           = Yii::$app->request->post('content');
                $teacher->instruction       = Yii::$app->request->post('instruction');
                if ($teacher->save()) {
                    return ['code' => 200, 'msg' => '更新成功'];
                }
                $errors = array_values($teacher->getFirstErrors());
                return ['code' => 203, 'msg' => $errors[0]];
            }

            return $this->render('edit', ['model' => $teacher]);

        }


        public function actionDel()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('id');
                if (is_array($ids) && Teacher::updateAll(['status' => Teacher::STATUS_DELETED], ['id' => $ids])) {
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
                if (is_array($ids) && Teacher::updateAll(['status' => Teacher::STATUS_ACTIVE], ['id' => $ids])) {
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