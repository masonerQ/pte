<?php


    namespace common\models;


    /**
     * Class Article
     *
     * @property int    nivo
     * @property int    cate_id
     * @property string avatar
     * @property string article_name
     * @property string article_content
     * @property int    status
     * @package common\models
     */
    class Article extends BaseActiveRecord
    {

        const STATUS_DELETED  = 2;
        const STATUS_INACTIVE = 9;
        const STATUS_ACTIVE   = 1;

        public static function tableName()
        {
            return "{{%article}}";
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
            ];
        }


        public function getCate()
        {
            return $this->hasOne(ArticleCate::class, ['id'=>'cate_id']);
        }

    }