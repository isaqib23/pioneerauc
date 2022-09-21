<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
    class Template 
    {
        var $ci;
        function __construct() 
        {
            $this->ci =& get_instance();
        }
		function load($template,$view, $data = null) 
		{
			 // $this->ci->load->model('Pages_model','Pages_model');
            // $this->ci->load->model('Global_icon_Model','Global_icon_model');
			if($view!='')
			$data['main_content'] = $this->ci->load->view($view,$data,TRUE);
			$data['page_title']=$this->ci->domain_info['page_title'];
			$data['site_title']=$this->ci->domain_info['site_title'];
			$this->ci->parser->parse('template_'.$template, $data);
		}
		function load_admin($view, $data = null) 
		{
			// $data['main_content'] = $this->ci->load->view('admin/'.$view,$data,TRUE);
			$data['main_content'] = $this->ci->load->view($view,$data,TRUE);
			if(!isset($data['page_title'])){
			 $data['page_title']='Auction App';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='Auction App';
			}
			$session_data = $this->ci->session->userdata('logged_in');
			$session_data_array = json_decode(json_encode($session_data), True);
			$data['logged_in'] = $session_data_array;
            $this->ci->parser->parse('template/template', $data);
		}
		function loadControllerView($view, $data = null) 
		{
			$data['language'] = ($this->ci->session->userdata('site_lang')) ? $this->ci->session->userdata('site_lang') : 'english';
			switch ($data['language']) {
				case 'english':
					$ln = 'en';
					$direction = 'ltr';
					break;
				case 'arabic':
					$ln = 'ar';
					$direction = 'rtl';
					break;
				default:
					$ln = 'en';
					$direction = 'ltr';
					break;
			}
			$data['ln'] = $ln;
			$data['direction'] = $direction;
			$data['main_content'] = $this->ci->load->view($view,$data,TRUE);
			if(!isset($data['page_title'])){
			 $data['page_title']='Auction App';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='Auction App';
			}
			$session_data = $this->ci->session->userdata('logged_in');
			$session_data_array = json_decode(json_encode($session_data), True);
			$data['logged_in'] = $session_data_array;
            $this->ci->parser->parse('template/template_controller', $data);
		}
		function load_login($data = null) 
		{ 
			if(!isset($data['page_title'])){
			 $data['page_title']='Auction App';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='Auction App';
			}
			echo $this->ci->load->view('login/login',$data,TRUE);
			//echo $this->ci->parser->parse('login/login', $data);
		}

		function load_jobcard_login($data = null) 
		{ 
			if(!isset($data['page_title'])){
			 $data['page_title']='Job Card Auction App';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='Job Card Auction App';
			}
			echo $this->ci->load->view('login/jobcard/login',$data,TRUE);
			//echo $this->ci->parser->parse('login/login', $data);
		}
		/*function load_user_login($view, $data = null) 
		{
			//$data['main_content'] = $this->ci->load->view('users/'.$view,$data,TRUE);
			$data['page_title']='Doctor App';
			$data['site_title']='Doctor App';
			echo $this->ci->load->view($view,$data,TRUE);
			//$this->ci->parser->parse('users/doctor/template', $data);
		}*/
		/*function load_user_login($data = null) 
		{	
			$data['page_title']='Doctor App';
			$data['site_title']='Doctor App';
			echo $this->ci->load->view('users/doctor/login',$data,TRUE);
			//echo $this->ci->parser->parse('login/login', $data);
		}*/
		function load_forgot($data = null) 
		{
			if(!isset($data['page_title'])){
			 $data['page_title']='Aucation App';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='Aucation App';
			}

			echo $this->ci->load->view('login/forgot_password',$data,TRUE);
			//echo $this->ci->parser->parse('login/login', $data);
		}

		function job_card_load_forgot($data = null) 
		{
			if(!isset($data['page_title'])){
			 $data['page_title']='Job Card Aucation App';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='Job Card Aucation App';
			}

			echo $this->ci->load->view('login/jobcard/forgot_password',$data,TRUE);
			//echo $this->ci->parser->parse('login/login', $data);
		}

		function load_email_success($data = null) 
		{		
			echo $this->ci->load->view('user/email_success',$data,TRUE);
			//echo $this->ci->parser->parse('login/login', $data);
		}
		function load_reset($data = null) 
		{
			if(!isset($data['page_title'])){
			 $data['page_title']='Auction App';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='Auction App';
			}
			echo $this->ci->load->view('user/reset_password',$data,TRUE);
			//echo $this->ci->parser->parse('login/login', $data);
		}
		function load_user($view, $data = null, $page='') 
		{
			// $data['main_content'] = $this->ci->load->view('admin/'.$view,$data,TRUE);
			$data['page'] = $page;
			// Need for view only
			$data['language'] = ($this->ci->session->userdata('site_lang')) ? $this->ci->session->userdata('site_lang') : 'english';
			switch ($data['language']) {
				case 'english':
					$ln = 'en';
					$direction = 'ltr';
					break;
				case 'arabic':
					$ln = 'ar';
					$direction = 'rtl';
					break;
				default:
					$ln = 'en';
					$direction = 'ltr';
					break;
			}
			$data['ln'] = $ln;
			$data['direction'] = $direction;
			$data['main_content'] = $this->ci->load->view($view,$data,TRUE);
			if(!isset($data['page_title'])){
			 $data['page_title']='PIONEER AUCTIONS';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='PIONEER AUCTIONS';
			}
			$session_data = $this->ci->session->userdata('logged_in');
			$session_data_array = json_decode(json_encode($session_data), True);
			$data['logged_in'] = $session_data_array;
	            $this->ci->parser->parse('template/new/template_user', $data);
			// if (isset($data['new']) && $data['new'] == 'new') {
			// } else {
	  //           $this->ci->parser->parse('template/template_user_old', $data);
			// }
		}

		function load_screen($view, $data = null, $page='') 
		{
			// $data['main_content'] = $this->ci->load->view('admin/'.$view,$data,TRUE);
			$data['page'] = $page;
			// Need for view only
			$data['language'] = ($this->ci->session->userdata('site_lang')) ? $this->ci->session->userdata('site_lang') : 'english';
			switch ($data['language']) {
				case 'english':
					$ln = 'en';
					$direction = 'ltr';
					break;
				case 'arabic':
					$ln = 'ar';
					$direction = 'rtl';
					break;
				default:
					$ln = 'en';
					$direction = 'ltr';
					break;
			}
			
			$data['ln'] = $ln;
			$data['direction'] = $direction;
			$data['main_content'] = $this->ci->load->view($view,$data,TRUE);
			if(!isset($data['page_title'])){
			 $data['page_title']='PIONEER AUCTIONS';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='PIONEER AUCTIONS';
			}
			$session_data = $this->ci->session->userdata('logged_in');
			$session_data_array = json_decode(json_encode($session_data), True);
			$data['logged_in'] = $session_data_array;
            $this->ci->parser->parse('template/template_screens', $data);
		}
		function load_screen3($view, $data = null, $page='') 
		{
			// $data['main_content'] = $this->ci->load->view('admin/'.$view,$data,TRUE);
			$data['page'] = $page;
			// Need for view only
			$data['language'] = ($this->ci->session->userdata('site_lang')) ? $this->ci->session->userdata('site_lang') : 'english';
			switch ($data['language']) {
				case 'english':
					$ln = 'en';
					$direction = 'ltr';
					break;
				case 'arabic':
					$ln = 'ar';
					$direction = 'rtl';
					break;
				default:
					$ln = 'en';
					$direction = 'ltr';
					break;
			}
			
			$data['ln'] = $ln;
			$data['direction'] = $direction;
			$data['main_content'] = $this->ci->load->view($view,$data,TRUE);
			if(!isset($data['page_title'])){
			 $data['page_title']='PIONEER AUCTIONS';
			}
            if(!isset($data['site_title'])){
			 $data['site_title']='PIONEER AUCTIONS';
			}
			$session_data = $this->ci->session->userdata('logged_in');
			$session_data_array = json_decode(json_encode($session_data), True);
			$data['logged_in'] = $session_data_array;
            $this->ci->parser->parse('template/template_screens3', $data);
		}


		function load_user_ajax($view, $data = null)
		{
			// Need for view only
			$data['language'] = ($this->ci->session->userdata('site_lang')) ? $this->ci->session->userdata('site_lang') : 'english';
			switch ($data['language']) {
				case 'english':
					$ln = 'en';
					break;
				case 'arabic':
					$ln = 'ar';
					break;
				default:
					$ln = 'en';
					break;
			}
			$data['ln'] = $ln;
			$content = $this->ci->load->view($view,$data,TRUE);
			return $content;
		}


		function make_dual($str)
		{
			$language = ($this->ci->session->userdata('site_lang')) ? $this->ci->session->userdata('site_lang') : 'english';
			$exp_str = explode('|', $str); 
			if ($language == 'english') {
                return $exp_str[0];
            } else {
                $value = (isset($exp_str[1])) ? $exp_str[1] : $exp_str[0];
                return $value; 
            }
		}
    }