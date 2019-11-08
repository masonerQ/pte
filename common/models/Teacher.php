<?php


    namespace common\models;


    /**
     * Class Teacher
     *
     * @property string name
     * @property string avatar
     * @property string content
     * @property string instruction
     * @property int    status
     * @package common\models
     */
    class Teacher extends BaseActiveRecord
    {

        const STATUS_DELETED  = 2;
        const STATUS_INACTIVE = 9;
        const STATUS_ACTIVE   = 1;

        public static function tableName()
        {
            return "{{%teacher}}";
        }

        public function rules()
        {
            return [
                ['status', 'default', 'value' => self::STATUS_ACTIVE],
                [
                    'status',
                    'in',
                    'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]
                ],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id'         => 'id',
                'name'       => '名字',
                'avatar'     => '头像',
                'content'    => '教师介绍',
                'status'     => '状态',
                'created_at' => '创建时间',
                'updated_at' => '更新时间',
            ];
        }

    }