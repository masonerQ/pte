<?php


    namespace common\models;


    /**
     * Class Banner
     * @property int int
     * @property string title
     * @property string url
     * @property string img_link
     * @property int created_at
     * @property int updated_at
     * @package common\models
     */
    class Banner extends BaseActiveRecord
    {


        public static function tableName()
        {
            return "{{%banner}}";
        }




    }
