<?php


    namespace api\controllers;


    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\data\Pagination;
    use yii\db\ActiveRecord;

    use common\models\Collection;
    use common\models\Comment;
    use common\models\ExerciseAnswer;
    use common\models\ExerciseOption;
    use common\models\OnlineExercise;
    use common\models\OnlineExerciseCate;
    use common\models\PassExam;

    class PteOnlineExerciseController extends BaseActiveController
    {

        public $modelClass = 'common\models\OnlineExercise';

        public function behaviors()
        {
            $behaviors                              = parent::behaviors();
            $behaviors['authenticator']['optional'] = ['index', 'cate', 'view'];
            return $behaviors;
        }


        protected function verbs()
        {
            $verbs               = parent::verbs(); // TODO: Change the autogenerated stub
            $verbs['index']      = ['GET'];
            $verbs['cate']       = ['GET'];
            $verbs['comment']    = ['POST'];
            $verbs['pass-exam']  = ['POST'];
            $verbs['collection'] = ['POST'];
            return $verbs;
        }

        public function actions()
        {
            $actions = parent::actions();
            unset($actions['index']);
            unset($actions['view']);
            return $actions;
        }


        /**
         * @desc 分类列表
         * @return ActiveDataProvider
         */
        public function actionCate()
        {
            $where = ['parent_id' => 0];
            $model = OnlineExerciseCate::find()
                                       ->select(['id', 'parent_id', 'cate_name', 'cate_desc'])
                                       ->where($where)
                                       ->asArray()
                                       ->with('child');

            return new ActiveDataProvider(['query' => $model, 'pagination' => new Pagination(['pageSize' => 20])]);
        }


        /**
         * @desc 题目列表
         * @return ActiveDataProvider|mixed
         */
        public function actionIndex()
        {
            $cid     = Yii::$app->request->get('cid');
            $keyword = Yii::$app->request->get('keyword');
            $sort    = Yii::$app->request->get('sort', 'id');
            if (!$cid) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '分类id不能为空';
                return true;
            }

            if (!in_array($sort, ['id', 'newest', 'week', 'month'])) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '排序筛选不正常';
                return true;
            }

            $sort_value = SORT_DESC;
            if ($sort == 'id') {
                $sort_field = 'id';
                $sort_value = SORT_ASC;
            }

            if ($sort == 'newest') {
                $sort_field = 'created_at';
            }

            $where             = [];
            $where['cate_id']  = $cid;
            $andWhereLookStart = $andWhereLookEnd = [];
            if (in_array($sort, ['week', 'month'])) {
                $sort_field        = 'looks';
                $time              = $this->getStartEndTime($sort);
                $start             = $time['start'];
                $end               = $time['end'];
                $andWhereLookStart = ['>=', 'created_at', $start];
                $andWhereLookEnd   = ['<=', 'created_at', $end];
            }


            $andWhere = ['or'];
            if ($keyword) {
                array_push($andWhere, ['like', 'title', "$keyword"]);
                array_push($andWhere, ['like', 'content', "$keyword"]);
            }

            $onlineExercise = OnlineExercise::find()
                                            ->select('id, title, cate_id, content, looks, status, type, min_type')
                                            ->where($where)
                                            ->asArray()
                                            ->andWhere($andWhere)
                                            ->andWhere($andWhereLookStart)
                                            ->andWhere($andWhereLookEnd);

            $ActiveDataProvider = new ActiveDataProvider(
                [
                    'query'      => $onlineExercise,
                    'pagination' => new Pagination(['pageSize' => 20]),
                    'sort'       => [
                        'defaultOrder' => [
                            "$sort_field" => $sort_value
                        ]
                    ]
                ]
            );

            $data = $ActiveDataProvider->getModels();

            foreach ($data as $key => &$value) {
                $isCollection = 0;
                if (!Yii::$app->user->isGuest) {
                    $isExists = Collection::find()
                                          ->where(['exercise_id' => $value, 'user_id' => Yii::$app->user->identity->getId()])
                                          ->exists();
                    if ($isExists) {
                        $isCollection = 1;
                    }
                    unset($isExists);
                }
                $value['is_collection'] = $isCollection;
            }

            $ActiveDataProvider->setModels($data);

            return $ActiveDataProvider;

        }


        /**
         * @desc 题目详情
         * @return array|bool|ActiveRecord|null
         */
        public function actionView()
        {
            $eid = Yii::$app->request->get('eid');
            if (!$eid) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '请传入题目id';
                return false;
            }

            $where          = ['id' => $eid];
            $onlineExercise = OnlineExercise::find()
                                            ->select('id, cate_id, title, content, descption, img_link, audio_link, looks, status, type, min_type')
                                            ->where($where)
                                            ->asArray()
                                            ->with(
                                                [
                                                    'comment',
                                                ]
                                            )
                // ->with('userinfo')
                                            ->one();

            //口语练习    答案[文字/音频]
            //标记一下 大类型： 1口语，  2写作， 3阅读， 4听力
            //小类型
            $answer = $options = null;
            if ($onlineExercise) {
                $answer = ExerciseAnswer::find()
                                        ->select('id, exercise_id, content, audio_link, sorts')
                                        ->where(['exercise_id' => $eid])
                                        ->orderBy('sorts asc')
                                        ->all();

                //是否收藏
                $isCollection = 0;
                if (!Yii::$app->user->isGuest) {
                    $CollectionIsExists = Collection::find()
                                                    ->where(['exercise_id' => $onlineExercise['id'], 'user_id' => Yii::$app->user->identity->getId()])
                                                    ->exists();
                    if ($CollectionIsExists) {
                        $isCollection = 1;
                    }
                    unset($CollectionIsExists);
                }
                $onlineExercise['is_collection'] = $isCollection;

                //是否考过
                $isPass = 0;
                if (!Yii::$app->user->isGuest) {
                    $PassExamIsExists = PassExam::find()
                                                ->where(['exercise_id' => $onlineExercise['id'], 'user_id' => Yii::$app->user->identity->getId()])
                                                ->exists();
                    if ($PassExamIsExists) {
                        $isPass = 1;
                    }
                    unset($PassExamIsExists);
                }
                $onlineExercise['is_pass'] = $isPass;


                $type     = $onlineExercise['type'];
                $min_type = $onlineExercise['min_type'];
                // 阅读
                if ($type == 3) {
                    //1拖拽 2排序
                    $options = ExerciseOption::find()
                                             ->select('id, exercise_id, content, groups, status')
                                             ->where(['exercise_id' => $eid])
                                             ->asArray()
                                             ->all();
                    $arr     = null;
                    if ($min_type == 3) { //3 下拉框选择
                        foreach ($options as $key => $value) {
                            $arr[$value['groups']][] = $value;
                        }
                        $options = array_values($arr);
                    }
                }
            }

            //自增1
            OnlineExercise::updateAllCounters(['looks' => 1], $where);

            //上一个
            $prev = OnlineExercise::find()
                                  ->select('id')
                                  ->where(['<', 'id', $eid])
                                  ->scalar();
            //下一个
            $next = OnlineExercise::find()
                                  ->select('id')
                                  ->where(['>', 'id', $eid])
                                  ->scalar();

            return ['exercise' => $onlineExercise, 'prev' => $prev, 'next' => $next, 'options' => $options, 'answer' => $answer];
        }

        /**
         * @desc 发表评论
         * @return bool
         */
        public function actionComment()
        {
            $parent_id   = intval(Yii::$app->request->post('parent_id'));
            $exercise_id = intval(Yii::$app->request->post('exercise_id'));
            $type        = intval(Yii::$app->request->post('type'));
            $content     = Yii::$app->request->post('content');
            if (!$parent_id || !$exercise_id || !$type || !$content) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '参数错误';
                return false;
            }

            $isHave = OnlineExercise::findOne($exercise_id);
            if (!$isHave) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '评论对象不存在';
                return false;
            }

            $comment              = new Comment();
            $comment->parent_id   = $parent_id;
            $comment->exercise_id = $exercise_id;
            $comment->type        = $type;
            $comment->content     = $content;
            if (!$comment->save()) {
                $errorsValue                    = array_values($comment->getFirstErrors());
                Yii::$app->response->statusText = '发表评论失败:' . $errorsValue[0];
                return false;
            }
            return true;
        }


        //考过
        public function actionPassExam()
        {
            $id = Yii::$app->request->post('id');
            if (!$id) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '参数不正确';
                return false;
            }
            $PassExamModel = PassExam::findOne($id);
            if (!$PassExamModel) {
                $PassExamModel              = new PassExam();
                $PassExamModel->exercise_id = $id;
                $PassExamModel->user_id     = Yii::$app->user->identity->getId();
                if ($PassExamModel->save()) {
                    Yii::$app->response->statusText = '考过成功';
                    return true;
                } else {
                    Yii::$app->response->statusCode = 203;
                    Yii::$app->response->statusText = '失败';
                    return false;
                }
            } else {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '已考过';
                return false;
            }

        }


        //收藏
        public function actionCollection()
        {
            $id    = Yii::$app->request->post('id');
            $level = Yii::$app->request->post('level', 1);
            if (!$id) {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '参数不正确';
                return false;
            }
            $CollectionModel = Collection::findOne($id);
            if (!$CollectionModel) {
                $CollectionModel              = new Collection();
                $CollectionModel->exercise_id = $id;
                $CollectionModel->level       = $level;
                $CollectionModel->user_id     = Yii::$app->user->identity->getId();
                if ($CollectionModel->save()) {
                    Yii::$app->response->statusText = '收藏成功';
                    return true;
                } else {
                    Yii::$app->response->statusCode = 203;
                    Yii::$app->response->statusText = '收藏失败';
                    return false;
                }
            } else {
                Yii::$app->response->statusCode = 203;
                Yii::$app->response->statusText = '已收藏过';
                return false;
            }

        }


    }