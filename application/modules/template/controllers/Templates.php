<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends MY_Controller {

	function __construct()
	{
		parent::__construct(); 
		$this->load->helper('date');
		$this->load->helper('url');
	}//end constructor
	
	public function index($data)
	{
	        $data['theme_url'] = base_url('assets_admin/');
			$this->load->view('public_admin_template',$data);
	}

	public function page_403()
	{
		$data = array();
		$this->template->load_admin('template/page_403',$data);
	}

	public function forbidden()
	{
		$data = array();
		$this->template->load_user('template/page_user_403',$data);
	}

	public function update_lat_lng()
	{
		$data = $this->input->post();
		if($data){
			$this->session->set_userdata('cLat', $data['lat']);
			$this->session->set_userdata('cLng', $data['lng']);
			print_r([$this->session->userdata('cLat'),$this->session->userdata('cLng')]);
		}
	}

	public function language($lang='english')
	{
		$this->session->set_userdata('site_lang', $lang);
		$url = base_url();
		if($_GET['url']){
			$url = $_GET['url'];
		}
		redirect($url);
	}

	  // public function contactUs_footer()
	  //   {
	  //    $data['contact_us'] = $this->db->get('contact_us')->row_array();  
	  //    // print_r($data['contact_us']);die(); 
	  //    // $this->template->load_user('template/views/template_user_footer', $data);
	  //    $this->template->load_user('template_user_footer', $data);
	  //   }

	
}
