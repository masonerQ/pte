<?php


    namespace backend\models;

    use Yii;
    use yii\base\Model;
    use yii\web\UploadedFile;

    class UploadForm extends Model
    {

        public $file;

        public function rules()
        {
            // 'extensions' => ['jpg', 'jpeg', 'png']
            return [
                [['file'], 'file', 'skipOnEmpty' => false]
            ];
        }


        /**
         * @return mixed
         */
        public function upload()
        {
            $this->file = UploadedFile::getInstanceByName('file');
            if (!$this->validate()) {
                return false;
            }

            $webRootPath = Yii::getAlias('@static');
            $uploadPath  = "/upload/";
            $finallyPath = $webRootPath . $uploadPath;

            if (!file_exists($finallyPath)) {
                mkdir($finallyPath, 0777);
                chmod($finallyPath, 0777);
            }

            $fileName = date("YmdHis") . '.' . $this->file->extension;
            if ($this->file->saveAs($finallyPath . $fileName)) {
                return $uploadPath . $fileName;
            } else {
                return false;
            }

        }

    }