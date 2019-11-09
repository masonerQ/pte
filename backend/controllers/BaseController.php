<?php


    namespace backend\controllers;


    use yii\web\Controller;

    class BaseController extends Controller
    {
        public function getTree($query, $level, &$option, $id, $isgroup=false)
        {
            foreach ($query as $key => $value) {
                $selected = '';
                if ($id == $value['id']){
                    $selected = " selected";
                }
                if ($value['child']) {
                    if ($isgroup){
                        $option .= '<optgroup label="'.$value['cate_name'].'">';
                        $this->getTree($value['child'], $level+4, $option, $id);
                        $option .= '</optgroup>';
                    }else{
                        $option .= '<option value="'.$value['id'].'" '.$selected.'>'.str_repeat('-', $level).$value['cate_name'].'</option>';
                        $this->getTree($value['child'], $level+4, $option, $id);
                    }
                }else{
                    $option .= '<option value="'.$value['id'].'" '.$selected.'>'.str_repeat('-', $level).$value['cate_name'].'</option>';
                }
            }
            return $option;
        }
    }