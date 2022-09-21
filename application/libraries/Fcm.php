<?php
class Fcm {
  function sendNotification($dataArr) {
    $fcmApiKey = "AAAAASZ7Vwk:APA91bF6wZpCDm86aSx4HpBM6td3rvdytZqlViClzsqgy4OjdonZFlwpPxuZK0Ew1DLhwdGHPvcQ9ddZWI7hiaFfnTJWQIXkr6-VXR4p355fHnC1p-BPBo6j4GXj_Es3EJf4PP7Wyi84"; //App API Key

           $url = 'https://fcm.googleapis.com/fcm/send';//Google URL
           $registrationIds = $dataArr['token'];//Fcm Device ids array
           $message = $dataArr['message'];//Message which you want to send
           $title = $dataArr['title'];
           $type = $dataArr['type'];
    
          if(is_array($registrationIds)){
              
               $notificationdata = array('body' => $message,'title' => $title,'type' => $type);
               $fields = array('registration_ids' => $registrationIds ,'notification' => $notificationdata);

          }else{
            
               $fields = array('to' => $registrationIds ,'body' => $message,'title' => $title,'type' => $type);
         
          }

         $headers = array(
            'Authorization: key=' . $fcmApiKey,
            'Content-Type: application/json'
         );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $url );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch);
    
    
          if ($result === FALSE) {
              die('Curl failed: ' . curl_error($ch));
           }
   
          curl_close($ch);  
          return $result;
        }
      }
?>