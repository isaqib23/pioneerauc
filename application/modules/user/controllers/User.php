<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends MX_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Users_model','users_model');
	}
	function index() {
		$data = array();
		if($this->session->userdata('logged_in'))
		{
			if($this->session->userdata('logged_in')->role == 5 || $this->session->userdata('logged_in')->role == 6){
				redirect(base_url().'jobcard/task_list');
			}elseif($this->session->userdata('logged_in')->role == 7 ){
				redirect(base_url().'livecontroller');
			}

			else
			{
				$this->session->set_flashdata('msg', 'Email sent successfully to your email address.');
				redirect(base_url().'admin/Dashboard/');
			}
			
		}
		else
		{
				$this->template->load_login($data);
		}
	}
	function logout()
	{ 
		if($this->session->userdata('logged_in')->role == 7){
			$this->db->update('auctions', ['start_status' => 'stop']);
		}
		$this->session->unset_userdata('logged_in', array());
		$this->session->set_flashdata('login_success_message', 'You have successfully logged out of your account.');
		redirect(base_url().'admin');			
	}
	public function login()
	{ 
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
	
		if ($this->form_validation->run() == true){

			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$password = hash("sha256",$password);
 			$result = $this->users_model->login_check($username, $password);

			if($result){
				
				$user = $result[0];

				if($user->role == 1){
					//Super Admin
					$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('admin/Dashboard/'));
				}

				if($user->role == 2){
					//Sales Manager
					$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('admin/Dashboard/')); 
				}

				if($user->role == 3){
					//Sales Person
					$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('admin/Dashboard/')); 
				}

				if($user->role == 4){
					//Customer
			    	redirect(base_url('home/login'));
				}

				if($user->role == 5){
					//Operation Department
			    	$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('admin/Dashboard/'));
				}

				if($user->role == 6){
					//Tasker
					$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('admin/Dashboard/')); 
				}

				if($user->role == 7){
					//Live Auction Controller
					$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('livecontroller'));
				}

				if($user->role == 8){
					//Live Auction Cashier
					$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('admin/Dashboard/'));
				}

				if($user->role == 9){
					//Appraiser
					$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('admin/Dashboard/'));
				}

				if($user->role == 10){
					//Appraiser
					$this->session->set_userdata('logged_in', $result[0]);
					redirect(base_url('admin/Dashboard/'));
				}

			}else{ 
				$this->session->set_flashdata('login_error', 'Incorrect username/password combination being used.');
				redirect(base_url().'admin');
			}
		}else{
			$this->session->set_flashdata('login_error', 'Incorrect username/password combination being used.');
			redirect(base_url().'admin');
		}
	}

	public function jobcard_login()
	{ 
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
	
		if ($this->form_validation->run() == true)
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$result = $this->users_model->login_check($username, hash("sha256",$password));
			if($result)
			{
				$this->session->set_userdata('logged_in', $result[0]);//(array)
				redirect(base_url().'admin/Dashboard/'); 
			}
			else
			{ 
				$this->session->set_flashdata('login_error', 'Incorrect username/password combination being used.');
				redirect(base_url().'user/index');
			}
		}
		else
		{
			$this->session->set_flashdata('login_error', 'Incorrect username/password combination being used.');
			redirect(base_url().'user/index');
		}
	}

	public function jobcard_forgot_password_form()
	{
		$data['forget_password_form'] = array();
		$this->template->job_card_load_forgot($data);

	}


	public function jobcard_forgot_password(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$email = $this->input->post('email');
		$result = $this->users_model->check_email($email);
		if($result == false)
		{	
			$this->session->set_flashdata('msg', 'Email is not exist');
				redirect(base_url().'user/forgot');
		}
		if($this->form_validation->run() == true)
		{

			$email=$this->input->post('email');
			$que=$this->db->query("select id,password,email from jobcard_users where email='".$email."'");

			$row=$que->row_array();
			// print_r($row);
			$user_email=$row['email'];
			// print_r($user_email);die();
			// $data['unique_id']=uniqid();
			$data['unique_id']=uniqid();

			$this->db->where('email',$email);
			$this->db->update("jobcard_users",$data);
			$que=$this->db->query("select unique_id,fname from jobcard_users where email='$email'");
			$row=$que->row_array();
			// print_r($row);
			$unique_id=$row['unique_id'];
			if((!strcmp($email, $user_email))){
			// print_r($pass); 
				/*Mail Code*/
				$link_address=base_url('user/reset_jobcard_password/').$unique_id;
				$to = $user_email;
				$subject = "You can update your password from this link";
				$txt = $link_address;
				// $headers = "From: password@example.com" . "\r\n" .
				// "CC: ifany@example.com";
				// echo "<a href=".$link_address.">Link for Reset Password</a>";

				mail($to,$subject,$txt);

				}
			else{
				$data['error']="
				Invalid Email ID !
					";
			}
				$this->session->set_flashdata('login_success_message', 'Email sent successfully to your email address.');
				// redirect(base_url().'user/email_success'); 
				redirect(base_url().'admin');
		}
	}

	public function forgot_password_form()
	{
		$data['forget_password_form'] = array();
		$this->template->load_forgot($data);

	}
	public function forgot_password(){
		
		// $this->load->library('email');
		// $email_message ='dummy message ';
		// 	 $subject='dummy subject ';
	 //        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	 //        <html xmlns="http://www.w3.org/1999/xhtml">
	 //        <head>
	 //            <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
	 //            <title>' . html_escape($subject) . '</title>
	 //            <style type="text/css">
	 //                body {
	 //                    font-family: Arial, Verdana, Helvetica, sans-serif;
	 //                    font-size: 16px;
	 //                }
	 //            </style>
	 //        </head>
	 //        <body>
	 //        ' . $email_message . '
	 //        </body>
	 //        </html>';
	 // 		$send = $this->email->from($this->config->item('sending_email'))->to("muhammadqadeer661@gmail.com")->subject($subject)->message($body)->send();
			
			
		// 	echo $send;die;

		
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$email = $this->input->post('email');
		$result = $this->users_model->check_email($email);
		if($result == false)
		{	
			$this->session->set_flashdata('msg', 'Email is not existed.');
			redirect(base_url().'user/forgot');
		}
		if($this->form_validation->run() == true)
		{

			$email=$this->input->post('email');
			$que=$this->db->query("select id,password,email from users where email='".$email."'");
			$row=$que->row_array();
			// print_r($row);
			$user_email=$row['email'];
			// print_r($user_email);die();
			// $data['unique_id']=uniqid();
			$data['unique_id']=uniqid();
			$this->db->where('email',$email);
			$this->db->update("users",$data);
			$que=$this->db->query("select unique_id,fname from users where email='$email'");
			$row=$que->row_array();
			// print_r($row);
			$unique_id=$row['unique_id'];
			if((!strcmp($email, $user_email))){
			// print_r($pass); 
				/*Mail Code*/
				$link_address=base_url('user/reset_password/').$unique_id;
				$to = $user_email;
				$subject = "You can update your password from this link.";
				$txt = $link_address;
				// $headers = "From: password@example.com" . "\r\n" .
				// "CC: ifany@example.com";
				// echo "<a href=".$link_address.">Link for Reset Password</a>";

				mail($to,$subject,$txt);

				}
			else{
				$data['error']="
				Invalid Email ID !
					";
			}
				$this->session->set_flashdata('login_success_message', 'Email sent successfully to your email address.');
				redirect(base_url().'user/index'); 
		
		}
	}
 	public function email_success()
	{
		$data['page_title'] = "Success";
		$this->template->load_email_success('user/email_success',$data);
	 
	}
	public function reset_password()
	{
		$data = array();
		$unique_id = $this->uri->segment(3);
		
		if($this->input->post())
		{
		$this->load->library('form_validation');
		// $id = $this->loginUser->id;
		$old_password = hash("sha256" , $this->input->post('new_password'));
		$new_password = hash("sha256" , $this->input->post('conf_password'));
		$unique_id = $this->input->post('unique_id');
		if ($this->input->post()) {
			$rules = array(
				array(
					'field' => 'new_password',
					'label' => 'New Password',
					'rules' => 'trim|min_length[5]',
				),
				array(
					'field' => 'conf_password',
					'label' => 'Confirm Password',
					'rules' => 'trim|min_length[5]|matches[new_password]',
				),
			);
		}
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {

			$data['updated_on'] = date('y-m-d H:m:s');
			if (($this->input->post('new_password') != null) && ($this->input->post('conf_password') != null)) {
				$data['password'] = hash("sha256",$this->input->post('new_password'));
			$result = $this->users_model->update_forgot_password($unique_id, $data);
			if (!empty($result)) {
				redirect(base_url().'user/index');

				}
			}
		}
	}
		$this->template->load_reset($data);
	}
	
	public function reset_jobcard_password()
	{
		$data = array();
		$unique_id = $this->uri->segment(3);
		if($this->input->post())
		{
			$this->load->library('form_validation');
			$old_password = hash("sha256",$this->input->post('new_password'));
			$new_password = hash("sha256",$this->input->post('conf_password'));
			$unique_id = $this->input->post('unique_id');
			if ($this->input->post()) 
			{
				$rules = array(
					array(
						'field' => 'new_password',
						'label' => 'New Password',
						'rules' => 'trim|min_length[5]',
					),
					array(
						'field' => 'conf_password',
						'label' => 'Confirm Password',
						'rules' => 'trim|min_length[5]|matches[new_password]',
					),
				);
			}
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) 
			{
				echo validation_errors();
			} 
			else
			{
				$data['updated_on'] = date('y-m-d H:m:s');
				if (($this->input->post('new_password') != null) && ($this->input->post('conf_password') != null)) 
				{
					$data['password'] = hash("sha256",$this->input->post('new_password'));
					$result = $this->users_model->update_forgot_password($unique_id, $data);
					if (!empty($result)) 
					{
						redirect(base_url().'user/index');
					}
				}
			}
		}
			$this->template->load_reset($data);
	}

}