<?php


    namespace backend\controllers;

    use Yii;
    use yii\data\Pagination;
    use backend\models\SignupForm;
    use common\models\User;
    use yii\web\Response;


    class MemberController extends BaseController
    {


        public function actionList()
        {
            $data          = $where = [];
            $query         = User::find()
                                 ->where($where);
            $pagination    = new Pagination(['totalCount' => $query->count(), 'pagesize' => '20']);
            $data['list']  = $query->offset($pagination->offset)
                                   ->limit($pagination->limit)
                                   ->all();
            $data['pages'] = $pagination;
            return $this->render('list', $data);

        }

        public function actionAdd()
        {
            $Signup = new SignupForm();
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                if ($Signup->load(Yii::$app->request->post(), '') && $Signup->signup()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['code'=>200, 'msg'=>'添加用户成功'];
                }
            }
            return $this->render('add', ['model'=>$Signup]);
        }

        public function actionEdit()
        {
            return $this->render('edit');
        }


    }