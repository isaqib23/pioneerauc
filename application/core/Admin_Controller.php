<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public  $loginUser;

	function __construct() {
		parent::__construct();
		// $this->load->model('Acl_Model'); 
		$this->loginUser= $this->session->userdata('logged_in');
		if(!$this->loginUser){
			$this->session->set_flashdata('login_error', 'Please login to proceed with protected section.');
			redirect(base_url().'login');
			exit;
		}
		// if (!$this->Acl_Model->has_permission($this->router->fetch_class(), $this->router->fetch_method(),$this->loginUser->role_id)){
		// 	redirect(base_url().'admin/users/page_403'); 
		// 	die;
		// }
    }
}

