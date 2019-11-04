<?php


    namespace common\models;

    /**
     * Class PassExam
     * @property int exercise_id
     * @property int user_id
     * @package common\models
     */
    class PassExam extends BaseActiveRecord
    {

        public static function tableName()
        {
            return "{{%pass_exam}}";
        }


    }