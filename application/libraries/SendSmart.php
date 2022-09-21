<?php

class SendSmart
{
    private $username = 'taqnyahapikk0u622ev7szsj';
    private $password = 'zw56wvgodjlv5culysmufa4eavirkh53';
    private $from = 'PIONEER AUC';
    private $end_point = 'https://meapi.goinfinito.me/unified/v2/send?';

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
            '{from}' => str_replace(' ', '%20', $this->from),
            '{to}' => $to,
            '{msg}' => str_replace(' ', '%20', $message),
        ];
        
        $url = "https://meapi.goinfinito.me/unified/v2/send?username={un}&password={pw}&to={to}&from={from}&text={msg}";
        
        foreach ($vars as $key => $value) {
            $url = str_replace($key, $value, $url);
        }

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => array(
        //         "Content-Type: application/json"
        //     ),
        // ));


        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
}