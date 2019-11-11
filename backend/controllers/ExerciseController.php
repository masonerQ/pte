<?php


    namespace backend\controllers;


    use common\models\OnlineExerciseOption;
    use Exception;
    use Yii;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use common\models\OnlineExerciseCate;
    use backend\models\UploadForm;
    use common\models\OnlineExercise;
    use common\models\OnlineExerciseAnswer;


    class ExerciseController extends BaseController
    {
        public function actionCate()
        {
            $data          = [];
            $where         = ['parent_id' => 0, 'status' => 1];
            $query         = OnlineExerciseCate::find()->where($where)->with('child');
            $pagination    = new Pagination(['totalCount' => $query->count(), 'pagesize' => '40']);
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
            $id           = Yii::$app->request->get('id', 0) ?: Yii::$app->request->post('id', 0);
            $ExerciseCate = OnlineExerciseCate::findOne($id);
            if (!$ExerciseCate) {
                throw new NotFoundHttpException('未找到......');
            }
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $ExerciseCate->cate_name    = Yii::$app->request->post('cate_name');
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


        /**
         * @return array|string
         * @throws NotFoundHttpException
         * @throws \yii\db\Exception
         */
        public function actionAdd()
        {
            $cid          = Yii::$app->request->get('cid') ?: Yii::$app->request->post('cid');
            $ExerciseCate = OnlineExerciseCate::findOne($cid);
            if (!$ExerciseCate) {
                throw new NotFoundHttpException('未找到......');
            }
            $OnlineExercise = new OnlineExercise();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $answer_content = Yii::$app->request->post('answer_content');
                // $type = gettype($option);
                // $op = $option;
                // echo "<pre>";
                // print_r($answer_content);
                // exit;

                $transaction                = Yii::$app->db->beginTransaction();
                Yii::$app->response->format = Response::FORMAT_JSON;
                $OnlineExercise->cate_id    = Yii::$app->request->post('cid');
                $OnlineExercise->title      = Yii::$app->request->post('title');
                $OnlineExercise->content    = Yii::$app->request->post('content');
                $OnlineExercise->img_link   = Yii::$app->request->post('img_link');
                $OnlineExercise->audio_link = Yii::$app->request->post('audio_link');
                if ($OnlineExercise->save()) {
                    $OnlineExerciseAnswer              = new OnlineExerciseAnswer();
                    $OnlineExerciseAnswer->exercise_id = $OnlineExercise->id;
                    $OnlineExerciseAnswer->content     = Yii::$app->request->post('answer_content');
                    $OnlineExerciseAnswer->audio_link  = Yii::$app->request->post('answer_audio_link');
                    if ($OnlineExerciseAnswer->save()) {
                        //带下拉选项的题目
                        if ($cid == 13) {
                            $option    = Yii::$app->request->post('answer_option');
                            $optionArr = explode('|', $option);
                            if (count($optionArr) > 0) {
                                $optionArr = array_filter($optionArr);
                                foreach ($optionArr as $key => $value) {
                                    $OnlineExerciseOption              = new OnlineExerciseOption();
                                    $OnlineExerciseOption->exercise_id = $OnlineExercise->id;
                                    $OnlineExerciseOption->content     = $value;
                                    if (!$OnlineExerciseOption->save()) {
                                        $transaction->rollBack();
                                        return ['code' => 203, 'msg' => '错误'];
                                    }
                                    unset($OnlineExerciseOption);
                                }
                            }
                        }
                        $transaction->commit();
                        return ['code' => 200, 'msg' => '成功'];
                    }
                    $transaction->rollBack();
                    $errors = array_values($OnlineExerciseAnswer->getFirstErrors());
                } else {
                    $transaction->rollBack();
                    $errors = array_values($OnlineExercise->getFirstErrors());
                }
                return ['code' => 203, 'msg' => $errors[0]];
            }

            // $query = OnlineExerciseCate::find()->where(['parent_id' => 0, 'status'=>1])->with('child')->asArray()->all();
            // $this->getTree($query, 0, $option, $cid, true);

            $answerList = "";

            return $this->render('add', ['model' => $OnlineExercise, 'Cate' => $ExerciseCate, 'answerList' => $answerList]);
        }

        /**
         * @return array|string
         * @throws NotFoundHttpException
         * @throws \yii\db\Exception
         */
        public function actionEdit()
        {
            $id             = Yii::$app->request->get('id');
            $OnlineExercise = OnlineExercise::findOne($id);

            if (!$OnlineExercise) {
                throw new NotFoundHttpException('未找到......');
            }

            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                $answer_content = Yii::$app->request->post('answer_content');
                // $type = gettype($option);
                // $op = $option;
                // echo "<pre>";
                // print_r($answer_content);
                // exit;

                $transaction                = Yii::$app->db->beginTransaction();
                Yii::$app->response->format = Response::FORMAT_JSON;
                $OnlineExercise->cate_id    = Yii::$app->request->post('cid');
                $OnlineExercise->title      = Yii::$app->request->post('title');
                $OnlineExercise->content    = Yii::$app->request->post('content');
                $OnlineExercise->img_link   = Yii::$app->request->post('img_link');
                $OnlineExercise->audio_link = Yii::$app->request->post('audio_link');
                if ($OnlineExercise->save()) {
                    $OnlineExerciseAnswer             = OnlineExerciseAnswer::find()->where(['exercise_id' => $OnlineExercise->id])->one();
                    $OnlineExerciseAnswer->content    = Yii::$app->request->post('answer_content');
                    $OnlineExerciseAnswer->audio_link = Yii::$app->request->post('answer_audio_link');
                    if ($OnlineExerciseAnswer->save()) {
                        //带下拉选项的题目
                        if ($OnlineExercise->cate_id == 13) {
                            $option    = Yii::$app->request->post('answer_option');
                            $optionArr = explode('|', $option);
                            if (count($optionArr) > 0) {
                                $optionArr = array_filter($optionArr);
                                OnlineExerciseOption::deleteAll(['exercise_id' => $OnlineExercise->id]);
                                foreach ($optionArr as $key => $value) {
                                    $OnlineExerciseOption              = new OnlineExerciseOption();
                                    $OnlineExerciseOption->exercise_id = $OnlineExercise->id;
                                    $OnlineExerciseOption->content     = $value;
                                    if (!$OnlineExerciseOption->save()) {
                                        $transaction->rollBack();
                                        return ['code' => 203, 'msg' => '错误'];
                                    }
                                    unset($OnlineExerciseOption);
                                }
                            }
                        }
                        $transaction->commit();
                        return ['code' => 200, 'msg' => '成功'];
                    }
                    $transaction->rollBack();
                    $errors = array_values($OnlineExerciseAnswer->getFirstErrors());
                } else {
                    $transaction->rollBack();
                    $errors = array_values($OnlineExercise->getFirstErrors());
                }
                return ['code' => 203, 'msg' => $errors[0]];
            }

            // $query = OnlineExerciseCate::find()->where(['parent_id' => 0, 'status'=>1])->with('child')->asArray()->all();
            // $this->getTree($query, 0, $option, $cid, true);

            $answerOptionArr = [];
            $answerOption = OnlineExerciseOption::find()->where(['exercise_id' => $id])->all();
            foreach ($answerOption as $key => $value) {
                array_unshift($answerOptionArr, $value['content']);
            }

            $answerOptionList = join('|', $answerOptionArr);

            return $this->render('edit', ['model' => $OnlineExercise, 'answerOptionList' => $answerOptionList]);

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
                    return ['code' => 203, 'msg' => '上传失败' . $errors[0], 'filename' => ''];
                }
            }
            return ['code' => 204, 'msg' => '非法操作', 'filename' => ''];
        }


    }