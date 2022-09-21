<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Valuation extends MY_Controller {
	
	function __construct() {
		parent::__construct();		 
		$this->load->model('Valuation_model','valuation_model');
        $this->load->model('home/Home_model','home_model');
        $this->load->library('session');
		
	}
	// public function index()
	// {
	// 	$data =array();
	// 	$this->template->load_user('step1', $data);
	// }
	public function index()
	{
		$data =array();
        $data['new'] = 'new';
        $data['evaluation'] = 'evaluation';
		$data['makes_list'] = $this->valuation_model->makes_list();
        $data['milleage_list'] = $this->valuation_model->milleage_list();
        // print_r($data['milleage_list']);die();
        $data['option_list'] = $this->valuation_model->get_valuate_Option();
		$this->template->load_user('car_valuation', $data);
	}
	public function get_make_data()
    {
        // $id=$_GET('make_id');
        $id = $this->input->post('make_id');
        $data = array();
        $data = $this->valuation_model->get_makes_data($id);
        //$data['language'] = $this->language;
        echo json_encode($data);

    }

    public function car_valuation_results()
    {
        $data = array();
        $this->load->library('session');
        $this->load->library('form_validation');
        $rules = array(
            array(
                'field' => 'valuation_make_id',
                'label' => 'Make',
                'rules' => 'trim|required'),
            array(
                'field' => 'valuation_model_id',
                'label' => 'Model',
                'rules' => 'trim|required'),
            array(
                'field' => 'year_to',
                'label' => 'Year',
                'rules' => 'trim|required'),
            array(
                'field' => 'valuate_option',
                'label' => 'Option',
                'rules' => 'trim|required'),
            array(
                'field' => 'engine_size_id',
                'label' => 'Engine Size',
                'rules' => 'trim|required')
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
           echo json_encode(array('msg' => validation_errors()));
           exit();
        } 
        $valuation_make_id = $this->input->post('valuation_make_id');
        $valuation_model_id = $this->input->post('valuation_model_id');
     
        $year_to = $this->input->post('year_to');
        $valuation_milleage_id = $this->input->post('valuation_milleage_id');
        $engine_size_id = $this->input->post('engine_size_id');
        $valuate_option = $this->input->post('valuate_option');
        $valuate_paint = $this->input->post('valuate_paint');
       // $valuate_gc = $this->input->post('valuate_gc');
        $valuate_gc = $this->input->post('valuate_gc');
        $valuate_Opt = $this->input->post('valuate_option');
        $email = $this->input->post('email');
        // get price
        
        $result = $this->db->query("SELECT * FROM `valuation_price` WHERE 
        (`valuation_make_id` = '" . $valuation_make_id .
            "' AND `valuation_model_id` = '" . $valuation_model_id . "') AND (`engine_size_id` = '" . $engine_size_id . "')");
        $result = $result->row_array();
    
        $last_query = $this->db->last_query();
        if (isset($result) && !empty($result) && count($result) > 0) 
        {

            $orig_price = $result['price'];

            // check year depreciation
            $get_year_depre = $this->db->query("select year_depreciation from valuation_years where  (`make_id` = '" . $valuation_make_id .
            "' AND `model_id` = '" . $valuation_model_id . "') AND (year = '".$year_to."') ");
            $get_year_depre = $get_year_depre->row_array();
           

            if (count($get_year_depre) > 0) {
                $get_year_depre['year_depreciation'] = (float)$get_year_depre['year_depreciation'];
                if ($get_year_depre['year_depreciation'] > 0) {
                    $year_valuate_price = $orig_price * ($get_year_depre['year_depreciation'] / 100);
                    $orig_price = $orig_price - $year_valuate_price;
                }
            }

            // check mileage depreciation
            $get_mileage_depreciation = $this->db->query("select millage_depreciation from valuation_millage where mileage_id = '".$valuation_milleage_id."' ");
            if ($get_mileage_depreciation->num_rows() > 0) {
                $get_mileage_depre = $get_mileage_depreciation->row_array();
                if ($get_mileage_depre['millage_depreciation'] > 0) {
                
                }
            } else {
                $get_mileage_depre['millage_depreciation'] = 0;
            } 
            $id=1;
            // check global config depreciation
            $get_global_depreciation = $this->db->query("select * from valuation_config_setting where created_by='" . $id . "'");
            if ($get_global_depreciation->num_rows() > 0) {
                $get_global_depre = $get_global_depreciation->row_array();    
                // check paint depreciation
                $config_setting_paint = 0;
                if ($get_global_depre['config_setting_paint'] != "" && $valuate_paint!="") {
                    $config_setting_paint = json_decode($get_global_depre['config_setting_paint'], 1);
                    //print_r($config_setting_paint);
                    $config_setting_paint = $config_setting_paint[$valuate_paint];
                    if ($config_setting_paint > 0) {
                        $config_setting_paint = (float)$config_setting_paint;
 
                    }

                }
                $config_setting_specs = 0;
                $config_option = 0; 

                if(!empty($valuate_gc)){
                    // check paint depreciation
                    if ($get_global_depre['config_setting_specs'] != "") {
                        $config_setting_specs = json_decode($get_global_depre['config_setting_specs'], 1);
                        $config_setting_specs = $config_setting_specs[$valuate_gc];
                        if ($config_setting_specs > 0) {
                       
                        }

                    }
                }

                if(!empty($valuate_Opt)){
                    // check paint depreciation
                    // echo $valuate_Opt.'pta to karo ';
                    if ($get_global_depre['config_option'] != "") {
                        $config_option = json_decode($get_global_depre['config_option'], 1);
                        // print_r($config_option);
                        $config_option = $config_option[$valuate_Opt];
                        if ($config_option > 0) {                        
                            // echo $config_option.' option';
                        }

                    }
                }
              
            }
            // echo  $orig_price.'before config ';
            $total_depreciation = $config_setting_paint + $config_setting_specs + $config_option + $get_mileage_depre['millage_depreciation'];
            $orig_price_cofig_depri = $orig_price * ($total_depreciation / 100);
            $orig_price = $orig_price - $orig_price_cofig_depri;
            // echo  $orig_price.'after config depreciation ';
            // die('ddd');
            $data =  number_format($orig_price,2);
            // email send setting 

            $to = $email;
            $subject = "Your Car Evaluation Result";
            // $txt = "Your Email is : ".$email."\n\n Your Password is :".$password;

            $template_name = "user registration";
            $q = $this->db->query('select * from crm_email_template where slug = "car-evaluation"');
            $email_message = $q->row_array();   

            if(!empty($email_message)){
                $make = $this->db->get_where('valuation_make',['id'=>$valuation_make_id])->row_array();
                $model = $this->db->get_where('valuation_model',['id'=>$valuation_model_id])->row_array();

                $milleage = $this->db->get_where('milleage',['id'=>$valuation_milleage_id])->row_array();
                $make = $this->db->get_where('valuation_make',['id'=>$valuation_make_id])->row_array();
                $engine_size =  $this->db->get_where('valuation_enginesize',['id'=>$engine_size_id])->row_array();
                $option =  $this->db->get_where('valuate_cars_options',['id'=>$valuate_option])->row_array();
                // print_r($email_message);die('asaaaa');


                $email_messagee = str_replace("{valuation_make}", $make['title'],$email_message['body']);
                $email_messagee = str_replace("{valuation_model}", $model['title'],$email_messagee);
                $email_messagee = str_replace("{valuation_year}", $year_to,$email_messagee);
                $email_messagee = str_replace("{valuation_enginesize}", $engine_size['title'],$email_messagee);
                $email_messagee = str_replace("{option}", $option['title'],$email_messagee);
                $email_messagee = str_replace("{paint}", $valuate_paint,$email_messagee);
                $email_messagee = str_replace("{valuate_gc}", $valuate_gc,$email_messagee);
                // $email_messagee = str_replace("{valuate_gc}", $valuate_gc,$email_message['body']);
                $email_messagee = str_replace("{valuation_price}", $data,$email_messagee);
                // print_r($email_messagee);die('aaaaa');
                $to =$this->input->post('email');       
                $this->session->set_userdata('email', $to);
                $this->session->set_userdata('car_price', $data);
                // $this->email->to($to);
                // $this->email->subject($email_message['subject']);
                // $this->email->message($email_messagee);
                // $this->email->send();
            }
              $msg = 'success';
            echo json_encode(array('msg' => $msg, 'price' => $data));
            exit();
            // $this->template->load_admin('car_valuation/car_valuation_result',$data);
            
        
        } else {
            $this->session->set_flashdata('msg', $this->lang->line('not_avilable_record'));
                // redirect(base_url().'cars/car_valuation');
        
        }

    }



    public function get_years()
        {

            // $id=$_GET('make_id');
            // print_r($this->input->post());die();
            $make_id = $this->input->post('make_id');
            $model_id = $this->input->post('model_id');
            $data = array();
            $total_year = $this->db->query('select year from valuation_years where model_id= "'.$model_id.'" and make_id= "'.$make_id.'" ')->result_array() ;
            // $year_to = $this->db->query('select year_to from valuation_price where valuation_model_id= "'.$model_id.'" and valuation_make_id= "'.$make_id.'" ')->result_array();
            // $last_query  = $this->db->last_query();
            // $yf = 0;
            // $yt = 0;
            // foreach ($year_from as $key) {
            //    for ($year_from[$yf]['year_from']; $year_from[$yf]['year_from']<= $year_to[$yt]['year_to']  ; $year_from[$yf]['year_from']++ ) {
            //     $years[] =  $year_from[$yf]['year_from'];         
            //    }
            //    $yf++;
            //    $yt++;
            // }
            foreach ($total_year as  $value) {
               $years[] =  $value['year'];
            }
            if(!empty($years))
            {
            $years = array_unique($years); 
            // asort($years);
            // $msg = "success";
            echo json_encode(array('result' => $years));
            }
            else{
                echo json_encode(array('msg' => "No Years Found"));
            }

        }

     public function get_engineBymodel($model_id = 0)
    {
        $data = array();
        $make_id = $this->input->post('make_id');
        $model_id = $this->input->post('model_id');
        $query = $this->db->query('select engine_size_id from valuation_price where valuation_model_id= "'.$model_id.'" and valuation_make_id= "'.$make_id.'" ')->result_array() ;
        $query =$this->unique_multidim_array($query,'engine_size_id');
        foreach ($query as  $value) {
          
          $data[] = $this->db->query('select id, title from valuation_enginesize where id= "'.$value['engine_size_id'].'"')->row_array();
        }
        // print_r($data);die('ssssss');
        // $data = $this->valuation_model->get_engineBymodel($model_id);
        echo json_encode($data);

    }
    public function get_enginesize_by_year()
    {
        $data = array();
        $make_id = $this->input->post('make_id');
        $model_id = $this->input->post('model_id');
        $year = $this->input->post('year');
        $query = $this->db->query('select engine_size_id from valuation_price where valuation_make_id = "'.$make_id.'" and valuation_model_id = "'.$model_id.'"and year = "'.$year.'"')->result_array();
        // $query = $this->db->get_where('valuation_price',['valuation_make_id'=>$make_id,'valuation_model_id' => $model_id,'year'=>$year])->result_array();
        // print_r($this->db->last_query)die();
         foreach ($query as  $value) {
          
          $data[] = $this->db->query('select id, title from valuation_enginesize where id= "'.$value['engine_size_id'].'"')->row_array();
        }
        if(!empty($data)){
           echo json_encode( array('error' => false, 'result'=> $data));
        }else{
           echo json_encode( array('error' => true, 'result'=> $data));
        }
    }


    function unique_multidim_array($array, $key) {
            $temp_array = array();
            $i = 0;
            $key_array = array();
           
            foreach($array as $val) {
                if (!in_array($val[$key], $key_array)) {
                    $key_array[$i] = $val[$key];
                    $temp_array[$i] = $val;
                }
                $i++;
            }
            return $temp_array;
    }
    public function book_appointment()
    { 
        $this->load->model('email/Email_model','email_model');
        $data = $this->input->post();
        $email = $this->session->userdata('email');
        // $date = $this->input->post('date');
        $get_email = $this->db->get('contact_us')->row_array();
        if(empty($email))
        {
            $email = $this->input->post('email2');
            $posted_email = $this->input->post('email2');
        }
        if(isset($posted_email) && !empty($posted_email))
        {
            $template_name = "Book Appointment"; //get template
            $q = $this->db->query('select * from crm_email_template where slug = "book-appointment-request"');
            $email_message = $q->row_array();  
            if(!empty($email_message)){
                $email_messagee = str_replace("{date}", $this->input->post('date'),$email_message['body']);
                // $email_messagee = str_replace("{time}", $this->input->post('time'),$email_messagee);
                $email_messagee = str_replace("{first_name}", $this->input->post('f_name'),$email_messagee);
                $email_messagee = str_replace("{last_name}", $this->input->post('l_name'),$email_messagee);
                $email_messagee = str_replace("{user_email}", $email,$email_messagee);
                $email_messagee = str_replace("{mobile}", $this->input->post('mobile'),$email_messagee);

                // send email without evaluation
                $to = $get_email['email']; 
                // $this->email->to($to);
                // $this->email->subject($email_message['subject']);
                // $this->email->message($email_messagee);
                // $this->email->send();

                $send = $this->email->to($to)->subject($email_message['subject'])->message($email_messagee)->send();
                // return $send;
                 if ($send) {
                    $msg = "success";
                    echo json_encode(array('error'=>false,'msg'=>$msg));
                    exit();
                 }
                 else
                 {
                    $msg = "failed";
                    echo json_encode(array('error'=>true,'msg'=>$msg));
                    exit();
                 }
                
            }else
            {
                $msg = "failed";
                echo json_encode(array('msg' => $msg ));
                exit();
            }

        }
        else{
                $car_price = $this->session->userdata('car_price');
                $valuation_make_id = $this->input->post('valuation_make_id');
                $valuation_model_id = $this->input->post('valuation_model_id');
                // $cat = $this->db->query('select model_category from valuation_model where id ='.$valuation_model_id)->row_array();
                // print_r($cat);die('aaa');
                // $category = $cat['model_category'];
                $year_to = $this->input->post('year_to');
                $valuation_milleage_id = $this->input->post('valuation_milleage_id');
                $engine_size_id = $this->input->post('engine_size_id');
                $valuate_option = $this->input->post('valuate_option');
                $valuate_paint = $this->input->post('valuate_paint');
               // $valuate_gc = $this->input->post('valuate_gc');
                $valuate_gc = $this->input->post('valuate_gc');
                $template_name = "Book Appointment"; //get template
                $q = $this->db->query('select * from crm_email_template where slug = "book-appointment-request"');
                $email_message = $q->row_array();  
                if(!empty($email_message)){
                $make = $this->db->get_where('valuation_make',['id'=>$valuation_make_id])->row_array();
                $model = $this->db->get_where('valuation_model',['id'=>$valuation_model_id])->row_array();

                $milleage = $this->db->get_where('milleage',['id'=>$valuation_milleage_id])->row_array();
                $make = $this->db->get_where('valuation_make',['id'=>$valuation_make_id])->row_array();
                $engine_size =  $this->db->get_where('valuation_enginesize',['id'=>$engine_size_id])->row_array();
                $option =  $this->db->get_where('valuate_cars_options',['id'=>$valuate_option])->row_array();
                $email = $this->session->userdata('email');
                $this->session->unset_userdata('email');
                $this->session->unset_userdata('car_price');
                // templating
                $email_messagee = str_replace("{valuation_make}", $make['title'],$email_message['body']);
                $email_messagee = str_replace("{date}", $this->input->post('date'),$email_messagee);
                // $email_messagee = str_replace("{time}", $this->input->post('time'),$email_messagee);
                $email_messagee = str_replace("{first_name}", $this->input->post('f_name'),$email_messagee);
                $email_messagee = str_replace("{last_name}", $this->input->post('l_name'),$email_messagee);
                $email_messagee = str_replace("{user_email}", $email,$email_messagee);
                $email_messagee = str_replace("{mobile}", $this->input->post('mobile'),$email_messagee);
                $email_messagee = str_replace("{valuation_model}", $model['title'],$email_messagee);
                $email_messagee = str_replace("{valuation_year}", $year_to,$email_messagee);
                $email_messagee = str_replace("{valuation_enginesize}", $engine_size['title'],$email_messagee);
                $email_messagee = str_replace("{option}", $option['title'],$email_messagee);
                $email_messagee = str_replace("{paint}", $valuate_paint,$email_messagee);
                // $email_messagee = str_replace("{valuate_gc}", $valuate_gc,$email_message['body']);
                $email_messagee = str_replace("{valuation_price}", $car_price,$email_messagee);
                // Sending Email to admin
               
                $to =$get_email['email'];
                // $this->email->to($to);
                // $this->email->subject($email_message['subject']);
                // $this->email->message($email_messagee);
                // $this->email->send();

                $send = $this->email->to($to)->subject($email_message['subject'])->message($email_messagee)->send();
                
                // return $send;
                $msg = "success";

                echo json_encode(array('msg' => $msg, ));
                exit();
            }else
            {
                $msg = "failed";
                echo json_encode(array('msg' => $msg, ));
                exit();
            }

        }
    }
    
}
