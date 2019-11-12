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

        const STATUS_DELETED  = 2;
        const STATUS_INACTIVE = 9;
        const STATUS_ACTIVE   = 1;

        public static function tableName()
        {
            return "{{%banner}}";
        }




    }
