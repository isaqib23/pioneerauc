<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH . "third_party/MX/Controller.php";
class MY_Controller extends MX_Controller
{	

    public $language = 'english';
	function __construct() 
	{
		parent::__construct();
		$this->_hmvc_fixes();
		$this->load->model('acl/Acl_model','acl_model');
        $this->load->model('home/Home_model','home_model');
        $this->load->model('auction/Live_auction_model', 'lam');
        $this->language = ($this->session->userdata('site_lang')) ? $this->session->userdata('site_lang') : 'english';
        if ($this->session->userdata('logged_in')) {
            $user = $this->db->get_where('users', ['id' => $this->session->userdata('logged_in')->id])->row_array();
            if ($user['role'] == '1') {
                redirect(base_url().'admin/Dashboard/');
                exit;
            }
            if ($user['role'] == '7') {
                redirect(base_url().'livecontroller');
                exit;
            }
        }
	}
	
	function _hmvc_fixes()
	{
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
	}

    public function email_template($email='', $vars=array(), $btn=false,$register='register'){
        // if(!empty($slug)){
            $notification = 'welcome';
            $data = array();
            $data = [
                'btn' => $btn,
                'notification' => $notification
            ];
            $email_message = $this->load->view('email_templates/register', $data, true);
            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }
            $this->email->to($email);
            $this->email->subject($notification);
            $this->email->message($email_message);
            $is_send = $this->email->send();
            return ($is_send) ? true : false;
        // }
    }

    //  public function email_template_forgot($email='', $vars=array(), $btn=false,$register='reset_password'){
    //     // if(!empty($slug)){
    //         $notification = 'welcome';
    //         $data = array();
    //         $data = [
    //             'btn' => $btn,
    //             'notification' => $notification
    //         ];
    //         $email_message = $this->load->view('email_templates/reset_password', $data, true);
    //         if($vars){
    //             foreach ($vars as $key => $value) {
    //                 $email_message = str_replace($key, $value, $email_message);
    //             }
    //         }
    //         // print_r($email_message);
    //         // $this->email->to($email);
    //         // $this->email->subject($notification);
    //         // $this->email->message($email_message);
    //         // $is_send = $this->email->send();
    //         // return ($is_send) ? true : false;
    //         $send = $this->email->to($email)->subject($notification)->message($email_message)->send();
    //         return $send;
    //     // }
    // }
     public function email_template1($email='', $vars=array(), $btn=false,$common_view='common_template'){
        
            $notification = "welcome pineer auction";

            $data = [
                'btn' => $btn,
                'notification' => $notification
            ];
            
            $email_message = $this->load->view('email_templates/'.$common_view.'', $data, true);

            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }

            //return print_r($email_message);

            $this->email->to($email);
            $this->email->subject($notification);
            $this->email->message($email_message);
            $is_send = $this->email->send();
            return ($is_send) ? true : false;
        
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
