<?php


    namespace common\models;


    class Goods extends BaseActiveRecord
    {
        public static function tableName()
        {
            return "{{%goods}}"; // TODO: Change the autogenerated stub
        }

        public function getCate()
        {
            return $this->hasOne(GoodsCate::class, ['id' => 'cate_id']);
        }


        public function attributeLabels()
        {
            return [
                'id'            => 'id',
                'cate_id'       => '分类id',
                'goods_cover'   => '商品图像',
                'goods_title'   => '商品标题',
                'goods_content' => '商品介绍',
                'goods_price'   => '商品价格',
                'goods_link'    => '商品链接',
                'status'        => '商品状态',
                'created_at'    => '创建时间',
                'updated_at'    => '更新时间',
            ]; // TODO: Change the autogenerated stub
        }
    }