<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	

	public function index()
	{
	        $data['small_title'] = 'Admin Login';
			$this->template->load_login();	
	}

	public function login_user()
	{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('user_name','user_name','required');
			$this->form_validation->set_rules('password','password','required');
			if($this->form_validation->run()==true)
			{
				$user_name=$this->input->post('user_name');
				$password=$this->input->post('password');
				$this->load->model('login_user');

				$result=$this->login_user->logined_user($user_name,$password);
				if($result)
				{
					$this->session->set_userdata('logged_in',$result[0]);
					 if ($this->session->userdata('logged_in') !== FALSE) {
						redirect(base_url('login/form_data')); 
  						
						} else {
 						 echo 'Variable is not set';
						}	  

				}
				else
				{
					$this->session->flashdata('User Name and Password Incorrect');
					redirect(base_url('login/'));
				}
			}
			else{
				 	$this->session->flashdata('Enter Usename and Password');
					redirect(base_url('login/'));
				}	

	}



    public function jobcard()
    { 
       $data = array();
		if($this->session->userdata('logged_in'))
		{
			if($this->session->userdata('logged_in')->role == 5 || $this->session->userdata('logged_in')->role == 6){
				redirect(base_url().'jobcard/task_list');
			}
			else
			{
			redirect(base_url().'admin/Dashboard/');
			}
		}
		else
		{
			$this->template->load_jobcard_login($data);
		}
    }

	public function form_data()
	{
		$this->load->view('form_data');
	}

	
}
