<?php


    namespace common\models;

    class OnlineExercise extends BaseActiveRecord
    {
        public static function tableName()
        {
            return "{{%online_exercise}}";
        }

        public function getComment()
        {
            return $this->hasMany(Comment::class, ['to_id'=>'id'])->select('id, parent_id, user_id, to_id, type, content');
        }
    }