<?php

class SendSmart
{
    private $username = 'httptaqnyah';
    private $password = '7a9NYa4';
    private $from = 'PIONEER AUC';
    private $end_point = 'http://global.myvaluefirst.com/smpp/sendsms?';

    function __construct(){}

    function sms($to,$message)
    {
        try 
        {
            $response = $this->send($to, $message);
            return $response;
        }

        catch (exception $e) 
        {
            return $e->getCode();
        }

    }

    function send($to, $message)
    {  
        $vars = [
            '{un}' => $this->username,
            '{pw}' => $this->password,
            '{from}' => $this->from,
            '{to}' => $to,
            '{msg}' => $message,
        ];
        
        $url = "http://global.myvaluefirst.com/smpp/sendsms?username={un}&password={pw}&to={to}&from={from}&text={msg}";
        
        foreach ($vars as $key => $value) {
            $value = urlencode($value);
            $url = str_replace($key, $value, $url);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data; boundary=--------------------------761288081773500036787034"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}