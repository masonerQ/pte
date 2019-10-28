<?php

    namespace common\models;

    use yii\base\Action;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\filters\RateLimitInterface;
    use yii\web\Request;

    /**
     * BaseActiveRecord
     *
     * @property integer $allowance
     * @property integer $allowance_updated_at
     */
    class BaseActiveRecord extends ActiveRecord implements RateLimitInterface
    {

        private $rateLimit = 1;

        /**
         * {@inheritdoc}
         */
        public function behaviors()
        {
            return [
                TimestampBehavior::className(),
            ];
        }

        /**
         * 返回在单位时间内允许的请求的最大数目，例如，[10, 60] 表示在60秒内最多请求10次。
         * Returns the maximum number of allowed requests and the window size.
         *
         * @param Request $request the current request
         * @param Action $action  the action to be executed
         * @return array an array of two elements. The first element is the maximum number of allowed requests,
         *                                  and the second element is the size of the window in seconds.
         */
        public function getRateLimit($request, $action)
        {
            return [1, 1];
        }

        /**
         * 返回剩余的允许的请求数。
         * Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
         *
         * @param Request $request the current request
         * @param Action $action  the action to be executed
         * @return array an array of two elements. The first element is the number of allowed requests,
         *                                  and the second element is the corresponding UNIX timestamp.
         */
        public function loadAllowance($request, $action)
        {
            return [
                $this->allowance,
                $this->allowance_updated_at
            ];
        }

        /**
         * 保存请求时的UNIX时间戳。
         * Saves the number of allowed requests and the corresponding timestamp to a persistent storage.
         *
         * @param Request $request   the current request
         * @param Action $action    the action to be executed
         * @param int              $allowance the number of allowed requests remaining.
         * @param int              $timestamp the current timestamp.
         */
        public function saveAllowance($request, $action, $allowance, $timestamp)
        {
            $this->allowance            = $allowance;
            $this->allowance_updated_at = $timestamp;
            $this->save();
        }
    }