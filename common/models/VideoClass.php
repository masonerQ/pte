<?php


    namespace common\models;


    /**
     * Class VideoClass
     * @property int id;
     * @property int cate_id;
     * @property string video_title;
     * @property string video_content;
     * @property string video_cover;
     * @property string video_link;
     * @property int status;
     * @package common\models
     */
    class VideoClass extends BaseActiveRecord
    {


        const STATUS_DELETED  = 2;
        const STATUS_INACTIVE = 9;
        const STATUS_ACTIVE   = 1;

        public static function tableName()
        {
            return "{{%video_class}}"; // TODO: Change the autogenerated stub
        }

        public function attributeLabels()
        {
            return [
                'id'            => 'id',
                'cate_id'       => '分类id',
                'video_title'   => '视频标题',
                'video_content' => '视频介绍',
                'video_cover'   => '视频图像',
                'video_link'    => '视频链接',
                'status'        => '视频状态',
                'created_at'    => '创建时间',
                'updated_at'    => '更新时间',
            ]; // TODO: Change the autogenerated stub
        }


        public function getCate()
        {
            return $this->hasOne(VideoClassCate::class, ['id' => 'cate_id']);
        }


    }