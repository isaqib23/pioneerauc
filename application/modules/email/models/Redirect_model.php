<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Redirect_model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

    public function custom_redirect($rurl)
    {
        $d_rurl = urldecode($rurl);
        $redirect = json_decode($d_rurl);
        $max_index = max(array_keys($redirect));
        $url = $redirect[$max_index];
        $url = ltrim($url, '/');
        unset($redirect[$max_index]);
            // $r = '';
        if (!empty($redirect)) {
            $redirect = json_encode($redirect);
            $back_rurl = urlencode($redirect);
            $r = $url.'?r='.$back_rurl;
        } else {
            $r = $url;
        }
        return $r;
        // return $send;
    }

	
}