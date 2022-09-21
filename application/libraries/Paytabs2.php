<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paytabs2
{
    public function __construct()
    {
        if(!function_exists('curl_init')){
            throw new RuntimeException('Paytabs requires cURL module');
        }
    }

    public function runPaytabs($data=array())
    {
        if(empty($data)){
            return;
        }

        $data['profile_id'] = 59496;
        //$data['profile_id'] = 59275;
        $data = json_encode($data);
        
        $server_key = "SHJN9NR292-JBDNJLGGZR-W62LWMZJBM";
        //$server_key = "SMJN9NR2JB-JBD9BJJJRH-DJ6ZWMWLGT";
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://secure.paytabs.com/payment/request",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "authorization: {$server_key}"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    function verify_payment($payment_reference){
        $values['merchant_email'] = $this->merchant_email;
        $values['secret_key'] = $this->secret_key;
        $values['payment_reference'] = $payment_reference;
        return json_decode($this->runPaytabs($values));
    }

    function authenticationPay(){
        $obj = json_decode($this->runPaytabs(array("merchant_email"=> $this->merchant_email, "secret_key"=>  $this->secret_key)));
        if($obj->access == "granted")
            $this->api_key = $obj->api_key;
        else 
            $this->api_key = "";
        return $this->api_key;
    }
}