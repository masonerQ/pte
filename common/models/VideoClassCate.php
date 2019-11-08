<?php


    namespace common\models;


    /**
     * Class VideoClassCate
     * @property int parent_id
     * @property string cate_cover
     * @property string cate_name
     * @property string cate_desc
     * @package common\models
     */
    class VideoClassCate extends BaseActiveRecord
    {
        public static function tableName()
        {
            return "{{%video_class_cate}}";
        }

        public function attributeLabels()
        {
            return [
                'id'         => 'id',
                'parent_id'  => '父分类id',
                'cate_cover' => '分类图像',
                'cate_name'  => '分类标题',
                'cate_desc'  => '分类介绍',
                'created_at' => '创建时间',
                'updated_at' => '更新时间',
            ];
        }

        public function getChild()
        {
            return $this->hasMany(VideoClassCate::class, ['parent_id' => 'id'])->select(['id', 'parent_id', 'cate_name', 'cate_desc'])->with('child');
        }

    }