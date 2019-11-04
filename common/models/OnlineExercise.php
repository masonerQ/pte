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
    class OnlineExercise extends BaseActiveRecord
    {
        public static function tableName()
        {
            return "{{%online_exercise}}";
        }

        public function getComment()
        {
            return $this->hasMany(Comment::class, ['exercise_id'=>'id'])->select('id, parent_id, user_id, exercise_id, type, content');
        }

        public function getIscollection()
        {
            return $this->hasMany(Collection::class, ['exercise_id'=>'id'])->onCondition([User::className().'.id'=>\Yii::$app->user->identity->id]);
        }
    }