<?php

/**
 * 
 * @param type $user_id
 * @param type $message
 * @param type $notification_type (0:approve,1:Reject)
 * @param type $user_type (driver,member,member)
 */
ob_start();
function check_user_device($user_id,$message,$notification_type,$inquiryId = ''){

    $CI = & get_instance(); 
    $CI->load->model('General_model');
    
    $params = array(
            'table'=>'user_p',
            'fields'=>array('username,device_token,device_type'),
            'where'=>array('id' => $user_id),
            'compare_type' => '=',
            'wherestring' => 'device_token != ""',
        );
    $user = $CI->General_model->get_query_data($params);
    
        
    if($user[0]['device_type'] == 1)
    {
        $json_data_android = array(
                        'inquiry_id' => $inquiryId,
                    );

        $sendNotification = androidpushfcm($user[0]['device_token'],$message,$json_data_android);
        
    }
    elseif($user[0]['device_type'] == 2)
    {
        $json_data_iphone = array(
                        'badge' => 0,
                        'message' => $message,
                        'notification_type' => '1',
                        'inquiry_id' => $inquiryId,
                    );

        $sendNotification = send_notification_iphone($user[0]['device_token'],$message,$json_data_iphone);
    }
        
    /*$data['user_id']=$user_id;
    $data['order_no']=!empty($order_no)?$order_no:'';
    $data['message']=$message;
    $data['user_type']=$user_type;
    $data['notification_type']=$notification_type;
    $data['type']=$type;
    $data['is_read'] = 0;
    $data['inserted_date'] = date('Y-m-d H:i:s');
    $CI->Notifications_model->insert_notification($data);*/

    return array('username'=>$user[0]['username']);
}
    
    function androidpushfcm($deviceToken,$message,$json_data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $server_key = ANDROID_CUSTOMER_SERVER_KEY;
       
        $headers = array('Content-Type:application/json', 'Authorization:key='.$server_key);
        /*echo $deviceToken.'<br>';
        echo $server_key;*/
        $notification = array('title' => SITE_NAME,
                               'text' => $message,
                               'sound' => 'default',
                               'click_action' => 'OPEN_SPLASH_ACTIVITY',
                               'badge' => 0,
                               'msg' => $message,
                               'notification_type' => '1',
                               'inquiry_id' => $json_data['inquiry_id'],
                               );
        
        $fields = array(
                        'data' => $notification,
                        'to'=> $deviceToken
                        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        //pr($result);  die;
        //return $result;
    }

 function send_notification_iphone($deviceToken,$message,$json_data)
 {
        // echo phpinfo(); 
        if(!empty($deviceToken) && $deviceToken != '(null)')
        {
            // Construct the notification payload
            $body = array();
            $body['message']            = $message;
            $body['aps']                = array('alert' => $message);
            $body['aps']['badge']       = !empty($json_data['badge'])?$json_data['badge']:'';
            $body['aps']['alert']       = $message;
            $body['aps']['ft']          = $message;
            $body['aps']['sound']       = !empty($json_data['sound'])?'default':'';
            $body['aps']['data']        = $json_data;
//            $body['data']               = !empty($json_data['flag'])?$json_data['flag']:'';
    
            /* End of Configurable Items */
            $gateway = 'ssl://gateway.sandbox.push.apple.com:2195';
            try
            {
                $ctx = stream_context_create();
                // Define the certificate to use
                
                if($ctx)
                {
                    stream_context_set_option($ctx, 'ssl', 'local_cert', PEM_FILE_PATH.'apns-dev.pem');
                    $fp = stream_socket_client($gateway, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

                    //prd($fp);
                    if (!$fp) {
                        //echo $data['msgs'] = "Failed to connect $err $errstr \n";
                    } else {
                        $payload = json_encode($body);
                        $msg = chr(0) . pack("n", 32) . pack("H*", str_replace(" ", "", $deviceToken)) . pack("n", strlen($payload)) . $payload;
                        $result = fwrite($fp, $msg);
                        /*if (!$result)
                            echo $data['msgs'] = 'Message not delivered'; //. PHP_EOL;
                        else
                            echo $data['msgs'] = 'Message Success delivered'; //. PHP_EOL;*/
                        fclose($fp);
                    }
                }
            }
            catch(exception $ex)
            {
                
            }
            
            //return $data;
        }    
}


   