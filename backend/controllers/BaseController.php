<?php


    namespace backend\controllers;


    use yii\web\Controller;

    class BaseController extends Controller
    {
        public function getTree($query, $level, &$option, $id)
        {
            foreach ($query as $key => $value) {
                $selected = '';
                if ($id == $value['id']){
                    $selected = " selected";
                }
                $option .= '<option value="'.$value['id'].'" '.$selected.'>'.str_repeat('-', $level).$value['cate_name'].'</option>';
                if ($value['child']) {
                    $this->getTree($value['child'], $level+4, $option, $id);
                }
            }
            return $option;
        }
    }