<?php


    namespace common\models;

    /**
     * Class OnlineExercise
     * @property int id
     * @property int exercise_id
     * @property string content
     * @property int groups
     * @property int status
     * @property int created_at
     * @property int updated_at
     * @package common\models
     */
    class OnlineExerciseOption extends BaseActiveRecord
    {

        public static function tableName()
        {
            return "{{%online_exercise_options}}";
        }

    }