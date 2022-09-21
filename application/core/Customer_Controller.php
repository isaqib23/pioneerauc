<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Customer_Controller extends MX_Controller {
	
	public  $loginUser;
	public  $language = 'english';
	function __construct() {
		parent::__construct();
		$this->_hmvc_fixes();
		$this->loginUser = $this->session->userdata('logged_in');
		//print_r($this->loginUser);die();
		$this->language = ($this->session->userdata('site_lang')) ? $this->session->userdata('site_lang') : 'english';
		if(!$this->loginUser){
			if (isset($_GET['rurl'])) {
				$rurl = $_GET['rurl'];
				$this->session->set_flashdata('error', 'Please login to proceed with protected section.');
				redirect(base_url().'home/login?rurl='.$rurl);
				exit;
			} else {
				$this->session->set_flashdata('error', 'Please login to proceed with protected section.');
				redirect(base_url().'home/login');
				exit;
			}
		} else {
			//if user logged in but blocked by admin
			$user = $this->db->get_where('users', ['id' => $this->loginUser->id])->row_array();
			if ($user['status'] == 0) {
				session_destroy();
				$this->session->set_flashdata('error', 'Your account has been blocked by admin.');
				redirect(base_url().'home/login');
				exit;
			}
			if ($user['role'] == '1') {
				redirect(base_url().'admin/Dashboard/');
				exit;
			}
			if ($user['role'] == '7') {
				redirect(base_url().'livecontroller');
				exit;
			}

			//user logged in successfully.
			$this->load->model('acl/Acl_model','acl_model'); 
			$this->load->model('files/Files_model','files_model');
			$this->load->model('email/Redirect_model','redirect_model');
		}
		// if (!$this->acl_model->has_permission($this->router->fetch_class(), $this->router->fetch_method(),$this->loginUser->role)){
		// 	redirect(base_url().'page/forbidden'); 
		// 	die;
		// }
    }

    function _hmvc_fixes()
	{		
		//fix callback form_validation		
		//https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
	}
}

