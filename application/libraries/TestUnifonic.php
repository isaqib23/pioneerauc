<?php
require ('Unifonic/Autoload.php');
use \Unifonic\API\Client;
class TestUnifonic
{
    private $CI;

    function __construct()
    {
        $this->CI = get_instance();
    }

    function sendMessage($numberTo,$message)
    {
        // return $message;
        // die();
        $client = new Client();

        try {

            $response = $client->Messages->Send($numberTo, $message, 'qeopee'); // send regular massage
            return $response;

        }
        catch (exception $e) {
            return $e->getCode();
        }
    }
}


//Kindly note that the newly added sender ID nust me approved by our system if you have any issue please contact our support team
