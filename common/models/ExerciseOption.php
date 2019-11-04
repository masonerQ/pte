<?php


    namespace common\models;

    /**
     * Class OnlineExercise
     * @property int cate_id
     * @property string title
     * @property string content
     * @property string descption
     * @property string img_link
     * @property string audio_link
     * @property int status
     * @property int type
     * @property int min_type
     * @package common\models
     */
    class ExerciseOption extends BaseActiveRecord
    {

        public static function tableName()
        {
            return "{{%online_exercise_options}}";
        }

    }