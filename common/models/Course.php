<?php


    namespace common\models;


    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;

    class Course extends ActiveRecord
    {
        /**
         * {@inheritdoc}
         */
        public static function tableName()
        {
            return '{{%course}}';
        }

        /**
         * {@inheritdoc}
         */
        public function behaviors()
        {
            return [
                TimestampBehavior::className(),
            ];
        }
    }