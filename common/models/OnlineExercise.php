<?php


    namespace common\models;

    /**
     * Class OnlineExercise
     *
     * @property int    id
     * @property int    cate_id
     * @property string title
     * @property string content
     * @property string descption
     * @property string img_link
     * @property string audio_link
     * @property int    status
     * @property int    type
     * @property int    min_type
     * @package common\models
     */
    class OnlineExercise extends BaseActiveRecord
    {

        const STATUS_DELETED  = 2;
        const STATUS_INACTIVE = 9;
        const STATUS_ACTIVE   = 1;

        public static function tableName()
        {
            return "{{%online_exercise}}";
        }

        public function getComment()
        {
            return $this->hasMany(Comment::class, ['exercise_id' => 'id'])
                        ->select('id, parent_id, user_id, exercise_id, type, content, created_at')
                        ->with('userinfo');
        }

        public function getCate()
        {
            return $this->hasOne(OnlineExerciseCate::class, ['id' => 'cate_id'])
                        ->select('id, cate_name');
        }

        public function getAnswer()
        {
            return $this->hasMany(OnlineExerciseAnswer::class, ['exercise_id'=>'id']);
        }

        // public function getIscollection()
        // {
        //     return $this->hasMany(Collection::class, ['exercise_id' => 'id'])
        //                 ->onCondition([User::className() . '.id' => \Yii::$app->user->identity->id]);
        // }
    }