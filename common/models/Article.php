<?php


    namespace common\models;


    class Article extends BaseActiveRecord
    {
        public static function tableName()
        {
            return "{{%article}}"; // TODO: Change the autogenerated stub
        }

        public function attributeLabels()
        {
            return [
                'id'              => 'id',
                'nivo'            => '是否精选',  // 1是
                'cate_id'         => '分类id',
                'avatar'          => '文章封面图像',
                'article_name'    => '文章标题',
                'article_content' => '文章内容',
                'status'          => '状态',
                'created_at'      => '创建时间',
                'updated_at'      => '更新时间',
            ]; // TODO: Change the autogenerated stub
        }

    }