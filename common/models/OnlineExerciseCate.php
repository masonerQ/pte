<?php


    namespace common\models;

    /**
     * Class OnlineExerciseCate
     * @property  string cate_name;
     * @package common\models
     */
    class OnlineExerciseCate extends BaseActiveRecord
    {
        public static function tableName()
        {
            return "{{%online_exercise_cate}}";
        }

        public function getChild()
        {
            return $this->hasMany(OnlineExerciseCate::class, ['parent_id' => 'id'])->where(['status'=>1])->select(['id', 'parent_id', 'status', 'cate_name', 'cate_desc'])->with('child');
        }

    }