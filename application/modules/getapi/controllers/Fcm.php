<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once('vendor/autoload.php');

class Fcm extends MY_Controller
{

    function __construct()
    {

        parent::__construct();
        $this->load->model('customer/Customer_model', 'customer_model');
        $this->load->model('home/Home_model', 'home_model');
        $this->load->model('users/Users_model', 'users_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('files/files_model', 'files');
        $this->load->library('pagination');
    }


    public function home(){

        $this->load->view('getapi/fcm');
    }

    public function sendPushNotification()
    {

        $title = 'Test';
        $body =  'test test';
        $image = '';
        $link =  'hello';

        $data = [
            "notification" => [
                "body"  => $body,
                "title" => $title,
                "image" => $image
            ],
            "priority" =>  "high",
            "data" => [
                "click_action"  =>  "FLUTTER_NOTIFICATION_CLICK",
                "id"            =>  "1",
                "status"        =>  "done",
                "info"          =>  [
                    "title"  => $title,
                    "link"   => $link,
                ]
            ],
            "to" => "fic2P_Q5ELnaNTrXwsVT41:APA91bHGl2g8Nmi6K6AyP-sW3xK740l39fO-hmtZnLR6z7gjgY6098nJsfuZsLFUsu8XNZRiWkJlHz3b44FFyqgBeMEJe_E9yYUvvmD-PNL80n9bXRfyqw43-yqIS8mJzQWeDtyr3IMa"
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
       // $headers[] = 'Authorization: key=AAAAnpjDCgg:APA91bEgEVbKm_fMLUf5llOyuTQRZk_E_DtPXM9wKuaCkiednhG9hQIS1RhBk2lDjQIrcJ4_I1GNrftwSrG63U3ApbTT_J-fZ930XjN3m-1CFujgMqTI56Ne9SsOXYvvtw5ZbPC7i5C2';
  //   $headers[] = 'Authorization: key=AAAAma6uvDg:APA91bHNUAH7ENmEMozwRL837kM6HCmw-1bqWSD6VixA_KqihIoSqsF07K4sUNZR3dCAf3JfqXBOzvcVQOzjjQyjDNX0UrWFhMX725s4U81gCg7hYTO9ewLrAy9j7TvI0VbdT2K0PE9B';
 $headers[] = 'Authorization: key=AAAAASZ7Vwk:APA91bF6wZpCDm86aSx4HpBM6td3rvdytZqlViClzsqgy4OjdonZFlwpPxuZK0Ew1DLhwdGHPvcQ9ddZWI7hiaFfnTJWQIXkr6-VXR4p355fHnC1p-BPBo6j4GXj_Es3EJf4PP7Wyi84';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //$result = curl_exec($ch);
        curl_close ($ch);

        echo "request sent";

    }






}