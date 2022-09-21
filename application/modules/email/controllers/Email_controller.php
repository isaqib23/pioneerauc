<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_controller extends MX_Controller {
	
	function __construct() {
		parent::__construct();		 		
        $this->load->model('Email_model', 'email_model');
		
	}

	public function index()
	{
		$to = "it@armsgroup.ae";
		///$to = "shoaibranjha6@gmail.com";
		$email = $this->email_model->test_email($to);
		echo $email;
	}

	public function my_test_email(){

		$this->config->load('email');
        $this->load->library('email');
        $this->email->from($this->config->item('from_email'), $this->config->item('email_from'));
    	
        /*$this->load->library('email', $config);
        $this->email->initialize($config);
        $this->email->from('no-reply@bundldesigns.com', 'Test Pioneer');*/

        $this->email->to('it@armsgroup.ae');

        $this->email->subject('Test Pioneer');
        $this->email->message('this is testing body.');
        $result = $this->email->send();

		var_dump($result);
		echo '<br />';
		echo '////////////////////////////////////////';
		echo '<br />';
		echo $this->email->print_debugger();
    }
}
