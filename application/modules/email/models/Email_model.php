<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Email_model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
        // $this->load->helper(array('email'));
        $this->setup_email();
	}

    public function setup_email()
    {
        $this->config->load('email');
        $this->load->library('email');
        $this->email->from($this->config->item('from_email'), $this->config->item('email_from'));
    }

    public function test_email($to)
    {   
        $this->email->clear();
        $this->setup_email();
        $subject='Test email subject ';
        $notification = array(
            'title_english' => 'Test Email Title',
            'description_english' => 'Yor Are Successfully Recieve Email',
        );
        $data = [
            'btn' => 'true',
            'notification' => $notification
        ];
        $vars = [
            '{btn_link}' => base_url(),
            '{btn_text}' => 'Verify'
        ];
        
        $email_message = $this->load->view('email_templates/common_template', $data, true);
        if($vars){
            foreach ($vars as $key => $value) {
                $email_message = str_replace($key, $value, $email_message);
            }
        }
        // print_r($to);
        // print_r($email_message);die();
        $send = $this->email->to($to)->subject($subject)->message($email_message)->send();
        //var_dump($send);
        return $this->email->print_debugger();
        // return $send;
    }

	// public function template($email='', $slug='', $vars=array(), $btn=false)
 //    {
 //        if(!empty($slug)){
 //            $notification = $this->db->get_where('email_templates', ['slug' => $slug])->row_array();
 //            $language = $this->language;
 //            if(empty($language)){
 //                $language = 'english';
 //            }
 //            $data = [
 //                'btn' => $btn,
 //                'notification' => $notification
 //            ];
            
 //            $email_message = $this->load->view('email_templates/common_template', $data, true);

 //            if($vars){
 //                foreach ($vars as $key => $value) {
 //                    $email_message = str_replace($key, $value, $email_message);
 //                }
 //            }
 //            $subject = json_decode($notification['email_subject']);
 //            $this->email->to($email);
 //            $this->email->subject($subject->english);
 //            $this->email->message($email_message);
 //            $is_send = $this->email->send();
 //            print_r($is_send);die();
 //            return ($is_send) ? true : false;
 //        }
 //    }

    public function common_template($email='', $subject , $body , $vars=array(), $btn=false){
        // if(!empty($slug)){
            $this->email->clear();
            $this->setup_email();
            $data = array();
            
            $data = [
                'btn' => $btn,
                'notification' => $body
            ];
            
            // print_r($data);
            $email_message = $this->load->view('email_templates/common_template', $data, true);
            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }

            // print_r($email_message);
            $this->email->to($email);
            $this->email->subject($subject);
            $this->email->message($email_message);
            $send = $this->email->send();
            //var_dump($send);
            // return $this->email->print_debugger();
            // $is_send = $this->email->to($email)->subject($notification)->message($email_message)->send();
            // print_r($send);die();
            //return ($send) ? 'true' : 'false';
        // }
    }

    public function email_template($email='',$slug='', $vars=array(), $btn=false){
        // if(!empty($slug)){
            $this->email->clear();
            $this->setup_email();
            $email_data = $this->db->get_where('crm_email_template', ['slug' => $slug])->row_array();
            // print_r($email_data);die();
            $data = array();

            $subject = $email_data['subject'];

            $body = array(
                'title_english' => $email_data['subject'],
                'description_english' => $email_data['body'],
            );
            $data = [
                'btn' => $btn,
                'notification' => $body
            ];
            
            // print_r($data);
            $email_message = $this->load->view('email_templates/Email-template', $data, true);
            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }

            // print_r($email_message);
            $this->email->to($email);
            $this->email->subject($subject);
            $this->email->message($email_message);
            $send = $this->email->send();
            //return $send;
            var_dump($send);
            return $this->email->print_debugger();
            // $is_send = $this->email->to($email)->subject($notification)->message($email_message)->send();
            // print_r($send);die();
            //return ($send) ? 'true' : 'false';
        // }
    }

    public function email_template_forgot($email='', $vars=array(), $btn=false,$register='reset_password'){
        // if(!empty($slug)){
            $this->email->clear();
            $this->setup_email();
            $notification = 'Reset Password';
            $data = array();
            $data = [
                'btn' => $btn,
                'notification' => $notification
            ];
            
            
            $email_message = $this->load->view('email_templates/reset_password', $data, true);

            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }

                    // print_r($email_message);
            $this->email->to($email);
            $this->email->subject($notification);
            $this->email->message($email_message);
            $is_send = $this->email->send();
            return ($is_send) ? true : false;
        // }
    }

    public function email_template_update_password_success($email='', $vars=array(), $btn=false,$register='update_password_success'){
        // if(!empty($slug)){
            $this->email->clear();
            $this->setup_email();
            $notification = 'Password Changed';
            $data = array();
            $data = [
                'btn' => $btn,
                'notification' => $notification
            ];
            
            
            $email_message = $this->load->view('email_templates/update_password_success', $data, true);

            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }

                    // print_r($email_message);
            $this->email->to($email);
            $this->email->subject($notification);
            $this->email->message($email_message);
            $is_send = $this->email->send();
            return ($is_send) ? true : false;
        // }
    }
    public function email_template1($email='', $vars=array(), $btn=false,$common_view='common_template'){
        $this->email->clear();
        $this->setup_email();
        
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


      public function visitor_template($email='', $vars=array(), $btn=false,$common_view='visitor_template'){
        $this->email->clear();
        $this->setup_email();
        
        $notification['title_english']= "Contact us email";
        $sub ='Contact Us';
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
        $this->email->subject($sub);
        $this->email->message($email_message);
        $is_send = $this->email->send();
        return ($is_send) ? true : false;
    }

    public function direct_template($email='', $subject , $body , $vars=array()){
        // if(!empty($slug)){
            $this->email->clear();
            $this->setup_email();
            $data = array();
            
            $data = [
                'body' => $body
            ];
            
            $email_message = $this->load->view('email_templates/direct-email-template', $data, true);
            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }

            // print_r($email_message);
            $this->email->to($email);
            $this->email->subject($subject);
            $this->email->message($email_message);
            $send = $this->email->send();


            //var_dump($send);
            // return $this->email->print_debugger();
            // $is_send = $this->email->to($email)->subject($notification)->message($email_message)->send();
            // print_r($send);die();
            //return ($send) ? 'true' : 'false';
        // }
    }

	
}
