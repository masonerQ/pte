<?php


    namespace backend\controllers;


    use common\models\User;
    use yii\data\Pagination;

    class MemberController extends BaseController
    {


        public function actionList()
        {

            $data         = $where = [];
            $query        = User::find()
                                ->where($where);
            $pagination   = new Pagination(['totalCount' => $query->count(), 'pagesize' => '5']);
            $data['list'] = $query->offset($pagination->offset)
                                  ->limit($pagination->limit)
                                  ->all();
            $data['pages'] = $pagination;
            return $this->render('list', $data);

        }

        public function actionAdd()
        {
            return $this->render('add');
        }

        public function actionEdit()
        {
            return $this->render('edit');
        }


    }