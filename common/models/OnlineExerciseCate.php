<?php


    namespace common\models;

    class OnlineExerciseCate extends BaseActiveRecord
    {
        public static function tableName()
        {
            return "{{%online_exercise_cate}}";
        }

        public function getChild()
        {
            return $this->hasMany(OnlineExerciseCate::class, ['id' => 'parent_id']);
        }
    }