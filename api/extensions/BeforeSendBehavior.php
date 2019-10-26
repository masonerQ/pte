<?php


    namespace api\extensions;

    use Yii;
    use yii\web\Response;
    use yii\base\Behavior;

    class BeforeSendBehavior extends Behavior
    {
        // 重载events() 使得在事件触发时，调用行为中的一些方法
        public function events()
        {
            // 在 EVENT_BEFORE_SEND 事件触发时，调用成员函数 beforeSend
            return [Response::EVENT_BEFORE_SEND => 'beforeSend',];
        }

        public function beforeSend($event)
        {
            $data     = null;
            $response = $event->sender;
            $message  = $response->statusText;
            if ($response->statusCode == 200) {
                $data = $response->data;
            } else {
                if ($response->statusCode == 400) {
                    $message = $response->data['message'];
                    if (strstr($message, ': ')) {
                        $messageArr = explode(': ', $message);
                        $message    = '字段' . $messageArr[1] . '必传';
                    } else {
                        $message = '必传字段丢失';
                    }
                } else if ($response->statusCode == 401) {
                    $message = '授权信息不正确';
                } else if ($response->statusCode == 404) {
                    $message = '无记录, 不存在';
                } else if ($response->statusCode == 405) {
                    $message = '请求方法不允许';
                } else {
                    $message = $response->data['message'];
                }
            }

            $response->data       = [
                'success' => $response->isSuccessful,
                'code'    => $response->getStatusCode(),
                'message' => $message,
                'data'    => $data,
            ];
            $response->statusCode = 200;
            return true;
        }


    }