<?php
/*

*** Roles Description ***
role == 1 = Admin 
role == 2 = Sales Manager 
role == 3 = Sales Person 
role == 5 = Operational Department 
role == 6 = Tasker 
role == 7 = Live Auction Controller 
role == 8 = Cashier 
role == 9 = Appraiser

*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends Loggedin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Users_model','users_model');
		// $this->load->model('Common_Model', 'common_model');
        $this->load->model('acl/Acl_model', 'acl_model');
        $this->load->model('crm/Crm_model', 'crm_model');
        $this->load->model('settings/Setting_model', 'setting_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('files/Files_model','files_model');
        $this->load->model('email/Email_model','email_model');
        $this->load->model('admin/Common_model','common_model');
        $this->load->model('customer/Customer_model', 'customer_model');
        $this->load->model('transaction/Transaction_model','transaction_model');
        $this->load->model('auction/Auction_model','auction_model');
        $this->load->model('auction/Online_auction_model', 'oam');
	}
	public function index()
    {
        $this->output->enable_profiler(TRUE);
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Administrator Users';
        $data['current_page_users'] = 'current-page';
        $data['formaction_path'] = 'filter_report';
        $id = $this->loginUser->id;
        $role = $this->loginUser->role;
        
        if($role == 1){
            $data['users_list'] = $this->users_model->get_all_application_users();
        }elseif($role == 2) {
            $data['users_list'] = $this->users_model->get_users_list_by_id(3);
        }
        
        $data['code'] = $this->users_model->users_list($id);
        // print_r($data['users_list']);die();
        $this->template->load_admin('users/users_list', $data);
    }

    public function isload()
    {
        print_r($this->config->is_loaded('email'));    
    }

    public function pagination()
    {
     $this->load->library('pagination');
        $rowperpage = 5;
 
        if($rowno != 0){
          $rowno = ($rowno-1) * $rowperpage;
        }
  
        $allcount = $this->db->count_all('users');
 
        $this->db->limit($rowperpage, $rowno);
        $users_record = $this->db->get('users')->result_array();
        $config['base_url'] = base_url().'users/pagination';
        $config['use_page_numbers'] = TRUE;
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['result'] = $users_record;
        $data['row'] = $rowno;
        echo json_encode($data);
}


    function removeItemString($str, $item) {
        $parts = explode(',', $str);
        while(($i = array_search($item, $parts)) !== false) {
        unset($parts[$i]);
        }
        return implode(',', $parts);
    }
    public function filter_report()
    {
    	$data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();


        if (isset($posted_data['username']) && !empty($posted_data['username'])) {
            // print_r($posted_data['username']);
            $username = implode(",",$posted_data['username']);
            // print_r($username);die('aaaaa');
            $sql[] = " users.username LIKE '$username' ";
        }

        if (isset($posted_data['code']) && !empty($posted_data['code'])) {
            $users = implode(",",$posted_data['code']);
            $sql[] = " users.code LIKE '$users' ";
        }
        if (isset($posted_data['email']) && !empty($posted_data['email'])) {
            $email = implode(",",$posted_data['email']);
            $sql[] = " users.email LIKE '$email' ";
        }
        if (isset($posted_data['mobile']) && !empty($posted_data['mobile'])) {
            $mobile = implode(",",$posted_data['mobile']);
            

            $sql[] = " users.mobile LIKE '$mobile' ";
        }
        if (isset($posted_data['company_name']) && !empty($posted_data['company_name'])) {
            $company_name = implode(",",$posted_data['company_name']);
            $sql[] = " users.company_name LIKE '$company_name' ";
        }
        if (isset($posted_data['item_code']) && !empty($posted_data['item_code'])) {
            $item_code = implode(",",$posted_data['item_code']);
            $sql[] = "users.item_code LIKE '$item_code' ";
        }

            // print_r($sql);echo "++++++++++++++++++++++++++++++++++++++++++";


        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {


            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            
            $sql[] = " (DATE(users.created_on) between '$start_date' and '$end_date') ";

            // print_r($sql); die('aaaaa');

        }
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' OR ', $sql);

        }
        	$data['users_list'] = $this->users_model->users_filter_list($query);
             // print_r($data['users_list']);die('aaaaaa');

            // $data['users_list'] = $this->db->query("select users.* from users where '$query'");

            $data_view = $this->load->view('users/filter_data', $data, true);
            // print_r($data_view);die('aaaass');
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

      public function filter_seller_buyer()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();

        if (isset($posted_data['username']) && !empty($posted_data['username'])) {
            // print_r($posted_data['username']);
            $username = implode(",",$posted_data['username']);
            // print_r($username);die('aaaaa');
            $sql[] = " users.username LIKE '%$username%' ";
        }

        if (isset($posted_data['code']) && !empty($posted_data['code'])) {
            $users = implode(",",$posted_data['code']);
            $sql[] = " users.code LIKE '%$users%' ";
        }
        if (isset($posted_data['email']) && !empty($posted_data['email'])) {
            $email = implode(",",$posted_data['email']);
            $sql[] = " users.email LIKE '%$email%' ";
        }
        if (isset($posted_data['mobile']) && !empty($posted_data['mobile'])) {
            $mobile = implode(",",$posted_data['mobile']);
            $sql[] = " users.mobile LIKE '%$mobile%' ";
        }
        if (isset($posted_data['company_name']) && !empty($posted_data['company_name'])) {
            $company_name = implode(",",$posted_data['company_name']);
            $sql[] = " users.company_name LIKE '%$company_name%' ";
        }
        if (isset($posted_data['item_code']) && !empty($posted_data['item_code'])) {
            $items_sellera_arr = $this->items_model->get_item_bycode($posted_data['item_code']);
            $seller_id = $items_sellera_arr[0]['seller_id'];
            $sql[] = " users.id = $seller_id ";
        }

            // print_r($sql);echo "++++++++++++++++++++++++++++++++++++++++++";


        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {


            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            
            $sql[] = " (DATE(users.created_on) between '$start_date' and '$end_date') ";

            // print_r($sql); die('aaaaa');

        }
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' OR ', $sql);

        }
            $data['users_list'] = $this->users_model->users_sellerbuyer_filter_list($query);
             // print_r($data['users_list']);die('aaaaaa');

            // $data['users_list'] = $this->db->query("select users.* from users where '$query'");

            $data_view = $this->load->view('users/filter_seller_buyer', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    } 

   //  public function save_user_documents()
   //  {
   //      if(!empty($this->session->userdata('items_images')) && !empty($this->session->userdata('items_documents')))
   //      {
   //          $this->session->unset_userdata('items_images');
   //          $this->session->unset_userdata('items_documents');
   //      }
   //      $array = array();
   //      if(isset($_FILES) && !empty($_FILES))
   //      {
   //          $files = '';
   //          if(count($_FILES)>0)
   //          {
   //             $files .= $this->uploadFiles($_FILES);
   //          }
   //          if(isset($_FILES['documents']))
   //          {
   //              $array['items_documents'] =  $files;      
   //          }
   //      }
			// $this->session->set_userdata($array);
   //  }

	// Add a new User
    public function save_user()
    {
        $this->output->enable_profiler(TRUE);
    	$data['small_title'] = 'Add User';
        $data['formaction_path'] = 'save_user';
        $data['current_page_users'] = 'current-page';
        $data['all_users'] = array();
        $role_id = $this->loginUser->id;
        $data['userRole'] = $role_id;
        $data['loginUserRole'] = $this->db->get_where('users',['id' => $data['userRole']])->row_array();
        $customer_check = $this->input->get('user_type');
        if(isset($customer_check) && !empty($customer_check))
        {
            // echo $customer_check;
            unset($data['current_page_users']);
            $data['current_page_customers'] = 'current-page';
            $data['all_roles'] = $this->users_model->get_customer_roles();  
        }
        else
        {
            if($this->loginUser->role == 1)
            {
            $data['all_roles'] = $this->users_model->get_application_user_roles();
            }
            elseif ($this->loginUser->role == 2) {
                
            $data['all_roles'] = $this->users_model->get_user_roles_for_sale_manager();  
            }
            elseif ($this->loginUser->role == 3) {
                
            $data['all_roles'] = $this->users_model->get_customer_roles();  
            }


        }

        $data['get_users_list_by_id'] = $this->users_model->get_users_list_by_id($role_id);
        $sales_person_id = 3;
        $data['sales_person'] = $this->users_model->users_list($sales_person_id);    
        $this->load->library('form_validation');
		if ($this->input->post()) {
            $items_documents_data = $this->session->userdata('items_documents');

            // $all_documents_array = array();
            // $all_documents_array = array_merge($images_data,$items_documents_data);

            $users_attachment = array();

			$rules = array(
				array(
					'field' => 'fname',
					'label' => 'First Name',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'lname',
					'label' => 'Last Name',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'required|valid_email',
				),
                 array(
                    'field' => 'mobile',
                    'label' => 'Mobile ',
                    'rules' => 'trim|required',
                ),
			);

			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
                $output = [
                    'status' => 'false',
                    'msg' => validation_errors()
                ];
                echo json_encode($output);
                exit();
			} 
			// $documents = $this->uploadFiles($_FILES);
			if($this->input->post())
			{
                $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                $check_number = $this->users_model->check_user_number($number);
                if($check_number == true)
                {
                    $output = [
                        'status' => 'false',
                        'msg' => 'Mobile number already exist.'
                    ];
                    echo json_encode($output);
                    exit();
                }

                $email = $this->input->post('email');
				$result = $this->users_model->check_email($email);
				if($result == true)
				{
                    $output = [
                        'status' => 'false',
                        'msg' => 'Email already exist.'
                    ];
                    echo json_encode($output);
                    exit();
				}

                else{

				$data = array(
					'fname' => $this->input->post('fname'),
					'lname' => $this->input->post('lname'),
					'username' =>$this->input->post('fname').' '.$this->input->post('lname'),
					'city' => $this->input->post('city'),
					'email' => $this->input->post('email'),
					'role' => $this->input->post('role_id'),
					'type' => $this->input->post('type'),
					'password' => hash("sha256",$this->input->post('password')),
					'status' => $this->input->post('status'), 
				);

                if(!empty($items_documents_data))
                {
                    $data['documents'] = $items_documents_data;
                }
                if($this->input->post('sales_person')){
                    $data['sales_id'] = $this->input->post('sales_person');
                }
                 if($this->input->post('phone')){
                    $data['phone'] = $this->input->post('phone');
                }

                if($this->input->post('id_number')){
                    $data['id_number'] = $this->input->post('id_number');
                }
                if($this->input->post('po_box')){
                    $data['po_box'] = $this->input->post('po_box');
                }
                if($this->input->post('id_number')){
                    $data['id_number'] =$this->input->post('id_number');
                }
                if($this->input->post('id_number')){
                    $data['job_title'] =$this->input->post('job_title');     
                }

                if($this->input->post('vet_number')){
                    $data['vet_number'] =$this->input->post('vet_number');     
                }
                if($this->input->post('company_name')){
                    $data['company_name'] =$this->input->post('company_name');     
                }
                if($this->input->post('remarks')){
                    $data['remarks'] =$this->input->post('remarks');     
                }
                if($this->input->post('description')){
                    $data['description'] =$this->input->post('description');     
                }
                if($this->input->post('vat')){
                    $data['vat'] =$this->input->post('vat');     
                }
                if($this->input->post('vat_number')){
                    $data['vat_number'] =$this->input->post('vat_number');     
                }
                if($this->input->post('prefered_language')){
                    $data['prefered_language'] =$this->input->post('prefered_language');     
                }
                 if($this->input->post('social')){
                    $data['social'] =$this->input->post('social');     
                }
                if($this->input->post('mobile')){
                    $data['mobile'] =$this->input->post('mobile');  
                    $data['mobile'] = $number;
                }
                if($this->input->post('reg_type')){
                    $data['reg_type'] =$this->input->post('reg_type');     
                }
                $this->session->unset_userdata('items_documents');
                if(!empty($this->input->post('buyer_commission')))
                {
                    $data['buyer_commission'] = $this->input->post('buyer_commission');     
                }

                if(!empty($this->input->post('address')))
                {
                    $data['address'] = $this->input->post('address');    
                }
                // if(empty($this->input->post('buyer_commission')))
                // {
                //     $setting_conf = $this->setting_model->get_setting_row();
                //     $data['buyer_commission'] = $setting_conf['buyer_comission'];

                // }
				
				// $data['created_on'] = date('Y-m-d H:i:s');
                $data['created_on'] = date('Y-m-d H:i:s');
				$data['updated_on'] = date('Y-m-d H:i:s');

                $previous_id = $this->users_model->get_max_id();
                $data['code'] = $this->getNumber(4);
                $data['email_verification_code'] = $this->getNumber(4);
                // $data['code'] = sprintf('%08d', $previous_id[0]['max(id)']);
				$result = $this->users_model->insert_user_details($data);

				if (!empty($result)) {
                    //SMS verification process start
                    $this->load->library('SendSmart');
                    $sms = $this->lang->line('pioneer_verification_code_is') . $data['code'];
                    // $number = '971561387755';
                    // $number2 = '971561387755';
                    $sms_response = $this->sendsmart->sms($number, $sms);

    				if($this->input->post('email'))
    				{
                    // $this->load->library('email');
                        $user = $this->db->get_where('users', ['id' => $result])->row_array();
                 
        				$user_email = $this->input->post('email');	
        				$password = $this->input->post('password');			
                        $to = $user_email;

                        $template_name = "user registration";
                        $q = $this->db->query('select * from crm_email_template where slug = "user_registration"');
                        $email_message = $q->result_array();   

                        if(!empty($email_message)){
                            

                            $link_activate = base_url() . 'home/verify_email/' . $user['email_verification_code'];
                            $vars = [
                                '{username}' => $user['fname'],
                                '{email}' => $user['email'],
                                '{password}' => $this->input->post('password'),
                                '{login_link}' => $this->lang->line("please_login") . ':<a href="'.base_url().'?loginUrl=loginFirst" > ' . $this->lang->line("go_to_account") . ' </a>',
                                // '{email_verification_code}'=>$user['email_verification_code']
                                '{activation_link}' => $link_activate,
                                // '{btn_link}' => $link_activate,
                                '{btn_text}' => $this->lang->line('activate_your_account')
                            ];
                            if ($this->input->post('role_id') != 4) {
                                $vars['{login_link}'] = $this->lang->line("please_login") . ':<a href="'.base_url().'/admin" > ' . $this->lang->line("go_to_account") . ' </a>';
                                $vars['{activation_link}'] = base_url() . '/admin';
                            }
                            $send = $this->email_model->email_template($user_email, 'user_registration', $vars, true);
                        
                            $cust_name = $this->input->post('fname').' '.$this->input->post('lname');
                            // $password = $this->input->post('password');
                            // $email_messagee = str_replace("{username}", $cust_name,$email_message[0]['body']);
                            // $email_messageee = str_replace("{password}",$password, $email_messagee );
                            // $to =$this->input->post('email');
                       
                            // $this->email->to($to);
                            // $this->email->subject($email_message[0]['subject']);
                            // $this->email->message($email_messageee);
                            //print($this->email);die();
                            // $this->email->send();
                            if($this->input->post('sales_person'))
                            {
                            // $sales_person_id = $this->input->post('sales_person');

                            // $query_sales_person_email = $this->db->query('select email from users where id ='.$sales_person_id);
                            // $sales_person_email = $query_sales_person_email->result_array();
                            // $to = $sales_person_email[0]['email'];

                            // $q = $this->db->query('select * from crm_email_template where slug = "user_registration"');
                            // $email_message = $q->result_array();

                            // $email_messagee = str_replace("{username}", $cust_name,$email_message[0]['body']);
                            // $email_messageee = str_replace("{password}",$password, $email_messagee );

                            // $this->email->to($to);
                            // $this->email->subject($email_message[0]['subject']);
                            // $this->email->message($email_messageee);
                            //$this->email->send();
                            }
                        }
                    }

                    if( !empty($_FILES['profile_picture']['name'])){
                        // print_r($_FILES['profile_picture']['name']);die();
                        $user_id = $result;
                        $path = './uploads/profile_picture/'.$user_id."/";
                        if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                        }
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'png|jpg|jpeg';
                        $sizes = [
                           ['width' => 660, 'height' => 453]
                        ];

                        $uploaded_file_ids = $this->files_model->upload('profile_picture', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            $output = [
                                'status' => 'false',
                                'msg' => $uploaded_file_ids['error']
                            ];
                            echo json_encode($output);
                            exit();
                        }
                        // upload and save to database
                        $dp =  implode(',', $uploaded_file_ids);

                        $this->db->update('users',['picture' => $dp] ,['id' => $user_id]);
                        
                    }

                    if($this->input->post('role_id')==4){
                        $output = [
                            'status' => 'true',
                            'msg' => 'Customer has been added successfully!',
                            'redirect' => 'user_listing'
                        ];
                        echo json_encode($output);
                        exit();
                    }else{
                        $output = [
                            'status' => 'true',
                            'msg' => 'Administrator user has been added successfully!',
                            'redirect' => 'apllicationuser'
                        ];
                        echo json_encode($output);
                        exit();
                    }
				}
			}
		  }
		}
		else
		{
            // print_r($data['all_roles']);die();
			$this->template->load_admin('users/users_form',$data);
		}
	}

    public function check_dublication_mobile() {
        $data = array();
        $id = $this->loginUser->id;
        $mobile = $this->input->post('mobile'); 
        $mobile_code = $this->input->post('mobile_code');
        $user_id = $_GET['user_id'];
        if($user_id != 0)
        {
            $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
            $number = str_replace(' ', '', $number);
            $user_mobile = $this->db->get_where('users',['mobile' => $number, 'id !=' => $user_id])->row_array();
            
            if(empty($user_mobile))
            {
                $msg = 'not_duplicate';
                echo json_encode(array('msg' => $msg));
                exit();
            }

        }
        
        $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
        $check_number = $this->users_model->check_user_number($number);
        if($check_number == true)
        {   
        // $this->session->set_flashdata('error', 'Number already exist');
        $msg = 'duplicate';
        echo json_encode(array('msg' => $msg));
        }
          if($check_number == false)
        {   
        // $this->session->set_flashdata('error', 'Number already exist');
        $msg = 'not_duplicate';
        echo json_encode(array('msg' => $msg));
        }
                

        // $check_user = $this->users_model->check_users_oldpassword($id, $password);
        // if ($check_user == 1) {
        //             //$this->session->set_flashdata('success', 'about_section_edit_success');
        //           $msg = 'success';
        //           echo json_encode(array('msg' => $msg, 'in_id' => $check_user));
        //         }
        //         else  {
        //             //$this->session->set_flashdata('success', 'about_section_edit_success');
        //           $msg = 'error';
        //           echo json_encode(array('msg' => $msg, 'in_id' => $check_user));
        //         }
    }

    
	private function generate_string($input, $strength = 5) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) 
        {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;

            // print_r($random_string);die('aaaaaa');
        }
        return $random_string;
    }
	public function update_user() {
		$id = $this->uri->segment(3);
		$role_id = $this->loginUser->id;
        $data['userRole'] = $role_id;
        $data['loginUserRole'] = $this->db->get_where('users',['id' => $data['userRole']])->row_array();
		// $data['small_title'] = 'Update';
        $data['formaction_path'] = 'update_user';
         $sales_person_id = 3;
        $customer_check = $this->input->get('user_type');
        $data['sales_person'] = $this->users_model->users_list($sales_person_id);
        if(isset($customer_check) && !empty($customer_check))
        {
            $data['current_page_customers'] = 'current-page';
            // echo $customer_check;
            unset($data['current_page_users']);
            $data['current_page_customers'] = 'current-page';
            $data['all_roles'] = $this->users_model->get_customer_roles();  
        }
        else
        {
            $data['current_page_users'] = 'current-page';
            if($this->loginUser->role == 1)
            {
                $data['all_roles'] = $this->users_model->get_application_user_roles();  
            }
            elseif ($this->loginUser->role == 2) {
                $data['all_roles'] = $this->users_model->get_user_roles_for_sale_manager();  
            }
            elseif ($this->loginUser->role == 3) {
                $data['all_roles'] = $this->users_model->get_customer_roles();
            }
        }
        $data['get_users_list_by_id'] = $this->users_model->get_users_list_by_id($role_id);
		$this->load->library('form_validation');
        $data['all_users'] = $this->users_model->users_listing($id);
        // print_r( $data['all_users'] );
		if ($this->input->post()) {
		
			$rules = array(
				array(
					'field' => 'fname',
					'label' => 'First Name',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'lname',
					'label' => 'Last Name',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email',
				),
                array(
                    'field' => 'mobile',
                    'label' => 'Mobile',
                    'rules' => 'trim|required',
                ),
			);

			$this->form_validation->set_rules($rules);
			 if ($this->form_validation->run() == FALSE) {
                $output = [
                    'status' => 'false',
                    'msg' => validation_errors()
                ];
                echo json_encode($output);
                exit();
            } 
            else
            {
                    $id = $this->input->post('user_id');
                    $q = $this->db->query('select email from users where id ='.$id);
                    $query = $q->result_array();
                    if($this->input->post('email') != $query[0]['email'])
                    {
                        $email = $this->input->post('email');
                         $result = $this->users_model->check_email($email);
                        if($result == true){
                                $output = [
                                    'status' => 'false',
                                    'msg' => 'Email already exist.'
                                ];
                                echo json_encode($output);
                                exit();
                        }
                    } 

                    $q_for_number = $this->db->query('select mobile from users where id ='.$id);
                    $query_for_number = $q_for_number->result_array();
                    $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                    $number = str_replace(' ', '', $number);
                    if($number != $query_for_number[0]['mobile'])
                    {
                    // print_r($number);die();
                        $mobile = $this->input->post('mobile');
                         $result = $this->users_model->check_user_number($number);
                        if($result == true)
                                 {
                            $output = [
                                'status' => 'false',
                                'msg' => 'Mobile number already exist.'
                            ];
                            echo json_encode($output);
                            exit();
                        }
                         // die();
                    }
 
				$data = array(
					'fname' => $this->input->post('fname'),
					'lname' => $this->input->post('lname'),
					'username' =>$this->input->post('fname').' '.$this->input->post('lname'),
					'city' => $this->input->post('city'),
					// 'mobile' => $number,
					'email' => $this->input->post('email'),
					'type' => $this->input->post('type'),
					'role' => $this->input->post('role_id'),
					// 'password' => md5($this->input->post('password')),
                    
					// 'documents' => $items_documents_data,
                    'id_number' => $this->input->post('id_number'),
                    'po_box' => $this->input->post('po_box'),
                    'job_title' =>$this->input->post('job_title'),

				);
                if ($this->input->post('password')) {
                   $data['password'] = hash("sha256",$this->input->post('password'));
                }
                 if($this->input->post('sales_person')){
                    $data['sales_id'] = $this->input->post('sales_person');
                }

                if($this->input->post('id_number')){
                    $data['id_number'] = $this->input->post('id_number');
                }
                if($this->input->post('po_box')){
                    $data['po_box'] = $this->input->post('po_box');
                }
                if($this->input->post('id_number')){
                    $data['id_number'] =$this->input->post('id_number');
                }
                if($this->input->post('id_number')){
                    $data['job_title'] =$this->input->post('job_title');     
                }

                if($this->input->post('vet_number')){
                    $data['vet_number'] =$this->input->post('vet_number');     
                }
                if($this->input->post('company_name')){
                    $data['company_name'] =$this->input->post('company_name');     
                }
                if($this->input->post('remarks')){
                    $data['remarks'] =$this->input->post('remarks');     
                }
                if($this->input->post('description')){
                    $data['description'] =$this->input->post('description');     
                }
                if($this->input->post('vat')){
                    $data['vat'] =$this->input->post('vat');     
                }
                if($this->input->post('vat_number')){
                    $data['vat_number'] =$this->input->post('vat_number');     
                }
                if($this->input->post('prefered_language')){
                    $data['prefered_language'] =$this->input->post('prefered_language');     
                }
                 if($this->input->post('social')){
                    $data['social'] =$this->input->post('social');     
                }
                if($this->input->post('mobile')){
                   $data['mobile'] = $number;    
                }
                if($this->input->post('reg_type')){
                    $data['reg_type'] =$this->input->post('reg_type');     
                }
                if(!empty($this->input->post('buyer_commission')))
                {
                    $data['buyer_commission'] =$this->input->post('buyer_commission');     
                }

                if(!empty($this->input->post('address')))
                {
                    $data['address'] =$this->input->post('address');   
                }
                $id = $this->input->post('user_id');

                $data_for_delete_files = $this->users_model->users_listing($id); // fetch data for delete fles from folder
                $old_data = FCPATH .'uploads/profile_picture/'.$id.'/'.$data_for_delete_files[0]['picture'];
                if( !empty($_FILES['profile_picture']['name']))
                {
                    $user_detail = $this->db->get_where('users',['id'=>$this->input->post('post_id')])->row_array();
                    $user_id = $user_detail['id'];
                    $file_id = $user_detail['picture'];

                    // make path
                    $path = './uploads/profile_picture/'.$user_id."/";
                        if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                        }
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'png|jpg|jpeg';
                        $sizes = [
                           ['width' => 660, 'height' => 453]
                        ];

                        $uploaded_file_ids = $this->files_model->upload('profile_picture', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            $output = [
                            'status' => 'false',
                            'msg' => $uploaded_file_ids['error']
                            // 'redirect' => 'user_listing'
                        ];
                        echo json_encode($output);
                        exit();
                        }
                        $dp =  implode(',', $uploaded_file_ids);
                        $this->db->update('users',['picture' => $dp] ,['id' => $user_id]);
                }
                $id =$this->input->post('user_id');
               
                $q = $this->db->query('select password from users where id ='.$id );
                $old_pass = $q->result_array();

                if($this->input->post('password') != $old_pass[0])
				{
				$user_email = $this->input->post('email');
				$password = $this->input->post('password');			
		
				// $link_address=base_url('user/reset_password/').$unique_id;
				$to = $user_email;
				$subject = "Following are your Updated Email and Password ";
				$txt = "Your Email is : ".$user_email."\n\n Your Password is :".$password;
				
				}	
                $data['updated_on'] = date('Y-m-d H:i:s');  

				$result = $this->users_model->update_user($this->input->post('post_id'), $data);
                if($this->input->post('role_id')==4){
                        $output = [
                            'status' => 'true',
                            'msg' => 'Customer has been updated successfully!',
                            'redirect' => 'user_listing'
                        ];
                        echo json_encode($output);
                        exit();
                    }else{
                        $output = [
                            'status' => 'true',
                            'msg' => 'Administrator user has been updated successfully!',
                            'redirect' => 'apllicationuser'
                        ];
                        echo json_encode($output);
                        exit();
                    }
			}
		}
		else
		{
			$this->template->load_admin('users/users_form',$data);
		}
		
	}
    public function bank_detail()
    {
        $this->output->enable_profiler(TRUE);
        $data = array();
        $id = $this->uri->segment(3);
        $data['small_title'] = 'Bank Details';
        $data['current_page_customers'] = 'current-page';
        $data['bank_list'] = $this->users_model->bank_detail_list($id);
        $this->template->load_admin('users/bank_detail_list', $data);
    }
    // Add a new Bank Detail
    public function save_bank_detail()
    {
        $data['small_title'] = 'Add';
        $data['formaction_path'] = 'save_bank_detail';
        $data['all_users'] = array();    
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $rules = array(
                // array(
                //     'field' => 'account_no',
                //     'label' => 'Account No',
                //     'rules' => 'trim|required',
                // ),
                // array(
                //     'field' => 'account_title',
                //     'label' => 'Account Title',
                //     'rules' => 'trim|required',
                // ),
                // array(
                //     'field' => 'bank_code',
                //     'label' => 'Bank Code',
                //     'rules' => 'trim|required',
                // ),
                 array(
                    'field' => 'bank_name',
                    'label' => 'Bank Name',
                    'rules' => 'trim|required',
                ), array(
                    'field' => 'branch_name',
                    'label' => 'Branch Name',
                    'rules' => 'trim|required',
                ), array(
                    'field' => 'po_box',
                    'label' => 'PO Box',
                    'rules' => 'trim|required',
                ),
                 array(
                    'field' => 'account_name',
                    'label' => 'Account Name',
                    'rules' => 'trim|required',
                ),
                  array(
                    'field' => 'iban_no',
                    'label' => 'IBAN NO',
                    'rules' => 'trim|required',
                ),
                  array(
                    'field' => 'swift_code',
                    'label' => 'Swift Code',
                    'rules' => 'trim|required',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                exit();
            } 
            if($this->input->post())
            {
                $data = array(
                    // 'account_title' => $this->input->post('account_title'),
                    // 'account_no' => $this->input->post('account_no'),
                    'bank_name' =>$this->input->post('bank_name'),
                    'branch_name' =>$this->input->post('branch_name'),
                    'po_box' =>$this->input->post('po_box'),
                    'account_name' =>$this->input->post('account_name'),
                    'iban_no' =>$this->input->post('iban_no'),
                    'swift_code' =>$this->input->post('swift_code'),
                    // 'address' => $this->input->post('address'),
                    'user_id' => $this->input->post('user_id'),
                    // 'sort_code' => $this->input->post('sort_code'),
                    // 'swift_bic' => $this->input->post('swift_bic'),
                    // 'iban' => $this->input->post('iban'),
                    // 'clearance_date' => $this->input->post('clearance_date'),
                    // 'credit_limit' => $this->input->post('credit_limit'),
                    // 'skip_credit_check' => $this->input->post('skip_credit_check'),
                    // 'credit_days' => $this->input->post('credit_days'),
                    // 'contrast_allowed' => $this->input->post('contrast_allowed'),
                    // 'deposit_required' => $this->input->post('deposit_required'),
                    // 'clearance_funds' => $this->input->post('clearance_funds'),
                    // 'post_code' => $this->input->post('post_code'),
                );
                
                $data['created_on'] = date('Y-m-d H:i:s');
                 
                $result = $this->users_model->insert_user_bank_details($data);
                if (!empty($result)) {
                    $this->session->set_flashdata('msg', 'Detail Added Successfully');
                    $redict_users_list = "user_listing";
                    echo json_encode(array('msg'=>$redict_users_list,'mid'=>$result));
                    exit();
                }
            }
        }
        else
        {
            $this->template->load_admin('users/bank_detail_form',$data);
        }
    }
    public function update_bank_detail() {
        $id = $this->uri->segment(4);
        $data['small_title'] = 'Update';
        $data['formaction_path'] = 'update_bank_detail';

        $this->load->library('form_validation');
        $data['all_users'] = $this->users_model->get_users_record_by_id($id);
               if ($this->input->post()) {
            $rules = array(
                // array(
                //     'field' => 'account_no',
                //     'label' => 'Account No',
                //     'rules' => 'trim|required',
                // ),
                // array(
                //     'field' => 'account_title',
                //     'label' => 'Account Title',
                //     'rules' => 'trim|required',
                // ),
                // array(
                //     'field' => 'bank_code',
                //     'label' => 'Bank Code',
                //     'rules' => 'trim|required',
                // ),
                 array(
                    'field' => 'bank_name',
                    'label' => 'Bank Name',
                    'rules' => 'trim|required',
                ), array(
                    'field' => 'branch_name',
                    'label' => 'Branch Name',
                    'rules' => 'trim|required',
                ), array(
                    'field' => 'po_box',
                    'label' => 'PO Box',
                    'rules' => 'trim|required',
                ),
                 array(
                    'field' => 'account_name',
                    'label' => 'Account Name',
                    'rules' => 'trim|required',
                ),
                  array(
                    'field' => 'iban_no',
                    'label' => 'IBAN NO',
                    'rules' => 'trim|required',
                ),
                  array(
                    'field' => 'swift_code',
                    'label' => 'Swift Code',
                    'rules' => 'trim|required',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                exit();
            } else {
                 $data = array(
                    // 'account_title' => $this->input->post('account_title'),
                    // 'account_no' => $this->input->post('account_no'),
                    'bank_name' =>$this->input->post('bank_name'),
                    'branch_name' =>$this->input->post('branch_name'),
                    'po_box' =>$this->input->post('po_box'),
                    'account_name' =>$this->input->post('account_name'),
                    'iban_no' =>$this->input->post('iban_no'),
                    'swift_code' =>$this->input->post('swift_code'),
                    // 'address' => $this->input->post('address'),
                    'user_id' => $this->input->post('user_id'),
                    // 'sort_code' => $this->input->post('sort_code'),
                    // 'swift_bic' => $this->input->post('swift_bic'),
                    // 'iban' => $this->input->post('iban'),
                    // 'clearance_date' => $this->input->post('clearance_date'),
                    // 'credit_limit' => $this->input->post('credit_limit'),
                    // 'skip_credit_check' => $this->input->post('skip_credit_check'),
                    // 'credit_days' => $this->input->post('credit_days'),
                    // 'contrast_allowed' => $this->input->post('contrast_allowed'),
                    // 'deposit_required' => $this->input->post('deposit_required'),
                    // 'clearance_funds' => $this->input->post('clearance_funds'),
                    // 'post_code' => $this->input->post('post_code'),
                );
                // If profile picture is selected

                // $data_for_delete_files = $this->users_model->users_listing($id); // fetch data for delete fles from folder
                // $old_data = base_url().'uploads/profile_picture/'.$data_for_delete_files[0]['picture'];

                // $files = '';
                // if(count($_FILES)>0){
                //     $files .= $this->uploadFiles($_FILES);
                // }

                // if($files!=''){
                //     unlink($old_data);
                // }
                // $data['picture'] = $files;
                // $id =$this->input->post('user_id');
               
                // $old_pass = $q->result_array();

                   
                $data['updated_on'] = date('Y-m-d H:i:s');

                $result = $this->users_model->update_user_bank_detail($this->input->post('post_id'), $data);
                if (!empty($result)) {
                    $this->session->set_flashdata('msg', 'Bank Detail Updated Successfully');
                    $redict_users_list = "user_listing";
                    echo json_encode(array('msg'=>$redict_users_list,'mid'=>$result));
                    exit();
                    // echo "success";
                }
            }
        }
        else
        {
            $this->template->load_admin('users/bank_detail_form',$data);
        }
        
    }
    public function manage_deposite_acount()
    {
        $data = array();
        if($this->loginUser->role == 4 && $this->loginUser->type == 'buyer'){
            $id = $this->loginUser->id;
        }else{
            $id = $this->uri->segment(3);
        }
        $name = $this->db->get_where('users',['id'=>$id])->row_array();
        
        $data['small_title'] = 'Deposit History For '.$name['username'];
        $data['current_page_deposite'] = 'current-page';
        $data['formaction_path'] = 'filter_for_deposite';
        if(!empty($id)){
            $data['deposit_list'] = $this->users_model->deposit_detail_list($id);
            $deposit_list = $this->transaction_model->permanent_deposit($id);
            if (!empty($deposit_list)) {
                foreach ($deposit_list as $key => $value) {
                    $data['deposit_list'][] = $value;
                }
            }
        } else {
            $data['deposit_list'] = $this->users_model->deposit_detail_list();
            $deposit_list = $this->transaction_model->permanent_deposit($id);
            if (!empty($deposit_list)) {
                foreach ($deposit_list as $key => $value) {
                    $data['deposit_list'][] = $value;
                }
            }
        }
        // print_r($data); die();
        $data['transaction_detail'] = $this->users_model->get_transactiom_detail($id);

        // print_r($data['transaction_detail']); die('aaaaaaaaaa');
        $this->template->load_admin('users/deposite_acount_detail_list', $data);
    }

     public function filter_for_deposite()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();


        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {


            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            
            $sql[] = " (DATE(auction_deposit.created_on) between '$start_date' and '$end_date') ";


        }
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' AND ', $sql);

        }
            $data['deposit_list'] = $this->users_model->filter_for_deposite($query);
             // print_r($this->db->last_query());die('aaaaaa');

            // $data['users_list'] = $this->db->query("select users.* from users where '$query'");

            $data_view = $this->load->view('users/filter_for_deposite', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function manuall_info()
    {
        $data = array();
        $data['small_title'] = 'Manuall Details';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'manuall_info';
        $id = $this->uri->segment(3);
        $data['userid'] = $id;
        $data['auction_list'] = $this->users_model->get_manuall_detail($id);
        $this->template->load_admin('users/manuall_info', $data);
    }
    public function add_manuall_deposite()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Manuall Information';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'add_manuall_deposite';
        // $posted_data2 = 'manuall';
        
        if ($this->input->post()) {
        $auction_data = $this->input->post();
         // $auction_data['user_id'];
         // print_r($auction_data['id']);die();

        $this->load->library('form_validation');
            $rules = array(
               array(
                    'field' => 'manuall_bank_name_english',
                    'label' => 'Manuall Bank Name English',
                    'rules' => 'trim|required'),
               // array(
               //      'field' => 'manuall_bank_name_arabic',
               //      'label' => 'Manuall Bank Name Arabic',
               //      'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_account_number',
                    'label' => 'Manuall Account Number',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_bank_branch_english',
                    'label' => 'Manuall Bank Branch English',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'manuall_bank_branch_arabic',
                //     'label' => 'Manuall Bank Branch Arabic',
                //     'rules' => 'trim|required'),
                // array(
                //     'field' => 'manuall_deposit_name_arabic',
                //     'label' => 'Manuall Deposit Name Arabic',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_deposit_name_english',
                    'label' => 'Manuall Deposit Name English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_deposit_date',
                    'label' => 'Manuall Deposit Date',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_identity',
                    'label' => 'Manuall Identity',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_deposit_phone',
                    'label' => 'Manuall Deposit Phone',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_deposit_date',
                    'label' => 'manuall_deposit_date',
                    'rules' => 'trim|required'),
                // $close_auction_users_rule
                array(
                    'field' => 'manuall_transaction_id',
                    'label' => 'Manuall Transaction Id',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_currency',
                    'label' => 'Manuall Currency',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_deposit_type',
                    'label' => 'Manuall Deposit Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'manuall_amount',
                    'label' => 'Manuall Amount',
                    'rules' => 'trim|required'),
                 array(
                    'field' => 'manuall_title',
                    'label' => 'Manuall Title',
                    'rules' => 'trim|required'),

            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {
                // Cheque fields functionality
                $posted_data = array(
                'manuall_deposit_phone' => $auction_data['manuall_deposit_phone'],
                'manuall_transaction_id' => $auction_data['manuall_transaction_id'],
                'manuall_deposit_type' => $auction_data['manuall_deposit_type'],
                'manuall_account_number' => $auction_data['manuall_account_number'],
                'manuall_identity' => $auction_data['manuall_identity'],
                'manuall_title' => $auction_data['manuall_title'],
                'manuall_amount' => $auction_data['manuall_amount'],
                'manuall_currency' => $auction_data['manuall_currency'],
                // 'payment_type' => $posted_data2['payment_type'],

                'manuall_deposit_date' => date('Y-m-d H:i',strtotime($auction_data['manuall_deposit_date'])),
                );
               
                unset($auction_data['manuall_deposit_phone']);
                unset($auction_data['manuall_transaction_id']);
                unset($auction_data['manuall_deposit_type']);
                unset($auction_data['manuall_account_number']);
                unset($auction_data['manuall_amount']);
                unset($auction_data['manuall_title']);
                unset($auction_data['manuall_identity']);
                unset($auction_data['manuall_currency']);
                unset($auction_data['manuall_deposit_date']);

                $title = [
                'english' => $auction_data['manuall_bank_name_english'], 
                'arabic' => $auction_data['manuall_bank_name_arabic'], 
                ];
                unset($auction_data['manuall_bank_name_english']); 
                unset($auction_data['manuall_bank_name_arabic']); 
                $posted_data['manuall_bank_name'] = $title;

                $bank = [
                'english' => $auction_data['manuall_bank_branch_english'], 
                'arabic' => $auction_data['manuall_bank_branch_arabic'], 
                ];
                unset($auction_data['manuall_bank_branch_english']); 
                unset($auction_data['manuall_bank_branch_arabic']); 
                $posted_data['manuall_bank_branch'] = $bank;

                $account = [
                'english' => $auction_data['manuall_deposit_name_english'], 
                'arabic' => $auction_data['manuall_deposit_name_arabic'], 
                ];
                unset($auction_data['manuall_deposit_name_english']); 
                unset($auction_data['manuall_deposit_name_arabic']); 
                $posted_data['manuall_deposit_name'] = $account;
                
                $auction_data['transaction_info'] = json_encode($posted_data);
                
                // $this->db->insert('transaction',$data2);
                $auction_data['payment_type'] = 'manuall';
                $result = $this->users_model->insert_cheque($auction_data);
                if (!empty($result))
                {
                  $this->session->set_flashdata('msg', 'Manuall Information Added Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }
        else
        {
            $this->template->load_admin('users/manuall_form', $data);
        }
    }

       public function delete_manuall($tr_id='')
    {
        if(!empty($tr_id)){
            
            $result = $this->db->update('transaction',['delete_status' => 1] ,[
                'id' => $tr_id, 
                // 'item_id' => $ai_id
            ]);
            // print_r($result);die();
            if($result){
                $this->session->set_flashdata('success', 'Manuall deposit has been removed successfully!');
            }else{
                $this->session->set_flashdata('error', 'Manuall deposit has been failed to remove.');
            }
            redirect(base_url('users/manuall_info/'.$_GET['userid']));
        }else{
            show_404();
        }
    }

    public function cheque_info()
    {
        $data = array();
        $data['small_title'] = 'Cheque details';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'cheque_info';
        $id = $this->uri->segment(3);
        $data['userid'] = $id;
        $data['auction_list'] = $this->users_model->get_cheque_data($id);
        $this->template->load_admin('users/cheque_info', $data);
    }
    
    public function add_cheque()
    {
        $data = array();
        $dynaic_information = array();
        $id = $this->uri->segment(3);
        $name = $this->db->get_where('users',['id'=>$id])->row_array();
        $data['small_title'] = 'Add Cheque Information For '.$name['username'];
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'add_cheque';

        if ($this->input->post()) {
        $auction_data = $this->input->post();
         // $auction_data['user_id'];
         // print_r($auction_data);die();
        
        $this->load->library('form_validation');
            $rules = array(
               array(
                    'field' => 'cheque_title_english',
                    'label' => 'Cheque Title English',
                    'rules' => 'trim|required'),
               // array(
               //      'field' => 'cheque_title_arabic',
               //      'label' => 'Cheque Title English',
               //      'rules' => 'trim|required'),
                array(
                    'field' => 'cheque_amount',
                    'label' => 'Cheque Amount',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'bank_name_english',
                    'label' => 'Bank Name English',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'bank_name_arabic',
                //     'label' => 'Bank Name Arabic',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'account_title_english',
                    'label' => 'Account Title English',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'account_title_arabic',
                //     'label' => 'Account Title Arabic',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'cheque_date',
                    'label' => 'Cheque Date',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'account_number',
                    'label' => 'Account Number',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'cheque_number',
                    'label' => 'Cheque Number',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'cheque_type',
                    'label' => 'Cheque Type',
                    'rules' => 'trim|required'),
                // $close_auction_users_rule

            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {
                // Cheque fields functionality
                $posted_data = array(
                'cheque_number' => $auction_data['cheque_number'],
                'cheque_type' => $auction_data['cheque_type'],
                'cheque_amount' => $auction_data['cheque_amount'],
                'account_number' => $auction_data['account_number'],
                'cheque_date' => date('Y-m-d H:i',strtotime($auction_data['cheque_date'])),
                );

                unset($auction_data['cheque_number']);
                unset($auction_data['cheque_type']);
                unset($auction_data['cheque_amount']);
                unset($auction_data['account_number']);
                unset($auction_data['cheque_date']);

                $title = [
                'english' => $auction_data['cheque_title_english'], 
                'arabic' => $auction_data['cheque_title_arabic'], 
                ];
                unset($auction_data['cheque_title_english']); 
                unset($auction_data['cheque_title_arabic']); 
                $posted_data['cheque_title'] = $title;

                $bank = [
                'english' => $auction_data['bank_name_arabic'], 
                'arabic' => $auction_data['bank_name_english'], 
                ];
                unset($auction_data['bank_name_arabic']); 
                unset($auction_data['bank_name_english']); 
                $posted_data['bank_name'] = $bank;

                $account = [
                'english' => $auction_data['account_title_arabic'], 
                'arabic' => $auction_data['account_title_english'], 
                ];
                unset($auction_data['account_title_arabic']); 
                unset($auction_data['account_title_english']); 
                $posted_data['account_title'] = $account;

                $auction_data['transaction_info'] = json_encode($posted_data);
                $auction_data['payment_type'] = 'cheque';
                $auction_data['delete_status'] = 0;

                $result = $this->users_model->insert_cheque($auction_data);
                if (!empty($result))
                {
                  $this->session->set_flashdata('msg', 'Cheque Information Added Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  // redirect(base_url('users/cheque_info/'.$result));
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }
        else
        {
            $this->template->load_admin('users/cheque_form', $data);
        }
    }


       public function delete_cheque($tr_id='')
    {
        if(!empty($tr_id)){
            
            $result = $this->db->update('transaction',['delete_status' => 1] ,[
                'id' => $tr_id, 
                // 'item_id' => $ai_id
            ]);
            // print_r($result);die();
            if($result){
                $this->session->set_flashdata('success', 'Cheque deposit has been removed successfully!');
            }else{
                $this->session->set_flashdata('error', 'Cheque deposit has been failed to remove.');
            }
            redirect(base_url('users/cheque_info/'.$_GET['userid']));
        }else{
            show_404();
        }
    }
    public function show_deposite_options()
    {
        // print_r($this->session->userdata('logged_in')->id); die;
        $data = array();
        $id =$this->uri->segment(3);
        $name = $this->db->get_where('users',['id'=>$id])->row_array();
        $data['small_title'] = 'Deposit History For '.$name['username'];
        // $data['small_title'] = 'Deposit';
        // $data['category_list'] = $this->items_model->get_item_category();   
        $deposite_user_id = $this->uri->segment(3);
        $data['deposite_user_id'] = $deposite_user_id;
        $data['current_page_deposite'] = 'current-page';
        $admin_trensections = $this->users_model->get_amount_sum($deposite_user_id);
        $user_total_deposit = $this->customer_model->user_balance($deposite_user_id);
        $data['total'] = $admin_trensections->amount + $user_total_deposit['amount'];
        $data['auction_fee'] = $this->users_model->get_deposite_amount();
        $this->template->load_admin('users/deposite_user_options', $data);
    }
       //Add paytabs api
    public function place_order()
    {
        //  if ($this->input->post()) {
        //     $rules = array(
        //          array(
        //             'field' => 'category_id',
        //             'label' => 'Select Category',
        //             'rules' => 'trim|required',
        //         ),
        //     );
        //     $this->form_validation->set_rules($rules);
        //     if ($this->form_validation->run() == FALSE) {

        //         $this->session->set_flashdata('error', 'catogory field is required');
        //         redirect ('users/show_deposite_options');
        //     }
        // }


        $data=$this->input->post();
        $id=$data['user_id'];
        if(!empty($data['user_id_for_admin']))
        {
        $id = $data['user_id_for_admin'];  
        }  

        $q=$this->db->query('select fname,lname,mobile,email from users where id='.$id);
        $query=$q->result_array();
        $data['result']=$query[0];

        $this->session->set_userdata('id',$id);
            // 3. load PayTabs library
            $merchant_email='it@armsgroup.ae';
            $secret_key='UPFlCtGZ0kPkeD5J9SotYq6my2MdE58yUOUxhAxhUmC9ZIT9AhCzIJWgLJupOCYIqSgItN0o9Dx9lqiWIVvqErHqabQZOOHfHXXa';
            $merchant_id='10047347';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];
                 
            $this->load->library('Paytabs',$params);



            // 4. make a payment component through SADAD account and credit card
            $invoice = [
                "site_url" => "http://pa.yourvteams.com",
                "return_url" => base_url('users/return_paytab'),
                "title" =>'shoaib',
                "cc_first_name" => $query[0]['fname'],
                "cc_last_name" => $query[0]['lname'],
                "cc_phone_number" => '12345',
                "phone_number" => $query[0]['mobile'],
                "email" => $query[0]['email'],
                //"products_per_title" => "MobilePhone || Charger || Camera",
                "products_per_title" => 'bundls', //$order['project_name'],
                //"unit_price" => "12.123 || 21.345 || 35.678 ",
                "unit_price" =>$data['category_amount'],
                //"quantity" => "2 || 3 || 1",
                "quantity" => "1",
                "other_charges" => "0.0",
                //"amount" => "13.082",
                "amount" =>$data['category_amount'],
                "discount" => "0.0",
                "currency" => "SAR",
                "reference_no" => '3',

                "billing_address" => 'model town',
                "city" =>'lahore',
                "state" => 'punjab',
                "postal_code" =>'54000',
                "country" => "SAU",
                "shipping_first_name" => 'M',
                "shipping_last_name" => 'Shoaib',
                "address_shipping" => 'model town lahore',
                "city_shipping" => 'Lahore',
                "state_shipping" => 'punjab',
                "postal_code_shipping" => '5400',
                "country_shipping" => "SAU",
                "msg_lang" => "English",
                "cms_with_version" => "bundldesigns"
            ];

            //  echo "<pre>";
            // print_r($invoice);die();
            $response = $this->paytabs->create_pay_page($invoice);
     //        echo $response->payment_url;
     // print_r($response);
     // die();

            if($response->response_code == "4012"){

                $this->session->set_userdata('amount',$data['category_amount']); 
                $this->session->set_userdata('transaction_id',$response->p_id);
                // if()
                // $category_id = $data['category_id'];
                // $q = $this->db->query('select title from item_category where id='.$category_id );
                // $query = $q->result_array();
                // $category_name = $query[0]['title'];
                // $this->session->set_userdata('category_name',$category_name);

             // print_r($response-> );die('ghhghghgh');
                // $this->items_model->insert_transaction_data($data);
                // $dataa['transaction_id'] = $response->p_id;
                // $dataa['user_id'] = $id;
                // $dataa['amount'] = $data['category_amount'];
                // $dataa['transaction_time'] = date('Y-m-d H:i:s');
                // print_r($data['user_id']);
                // die();
                // $this->items_model->insert_transaction_data($dataa);
                redirect($response->payment_url);
            }
        // }
    }

    public function return_paytab()
    {
        // print_r($_REQUEST);die();
        if($_REQUEST['payment_reference']){
            //echo $_REQUEST['payment_reference'];
            $merchant_email='it@armsgroup.ae';
            $secret_key='   UPFlCtGZ0kPkeD5J9SotYq6my2MdE58yUOUxhAxhUmC9ZIT9AhCzIJWgLJupOCYIqSgItN0o9Dx9lqiWIVvqErHqabQZOOHfHXXa';
            $merchant_id='10047347';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];
                 
            $this->load->library('Paytabs',$params);
            $response = $this->paytabs->verify_payment($_REQUEST['payment_reference']);
            //print_r($response);die();

            if($response->response_code == 100){
                 $dataa['transaction_id'] = $this->session->userdata['transaction_id'];;
                $dataa['user_id'] = $this->session->userdata['id'];
                $dataa['amount'] = $this->session->userdata['amount'];
                $dataa['transaction_time'] =  date('Y-m-d H:i:s A');
                $dataa['created_on'] =  date('Y-m-d H:i:s A');
                $dataa['created_by'] = $this->loginUser->id;
                // $dataa['payment'] = "Paytabs";
                // $dataa['category_name'] = $this->session->userdata['category_name'];
                $this->items_model->insert_transaction_data($dataa);
                $this->session->unset_userdata['transaction_id'];
                $this->session->unset_userdata['id'];
                $this->session->unset_userdata['amount'];
                // $this->session->unset_userdata['category_name'];
                if($this->loginUser->id == 1)
                {
                    $this->session->set_flashdata('msg', 'Deposite Added Successfully');
                    redirect (base_url('users/manage_deposite_acount/'.$dataa['user_id']));
                }

                redirect ('users/manage_deposite_acount');
                // print_r($data['user_id']);
                // die();


                //if payment successful
                $order = $this->db->get_where('orders', ['trans_id' => $_REQUEST['payment_reference']]);
                if($order->num_rows() > 0){
                    // new order payment
                    $order = $order->row_array();
                    if($order['trans_id'] == $_REQUEST['payment_reference']){
                        // update order payment status to successful
                        $this->db->update('orders', ['payment_status' => 1], ['id' => $order['id']]);
                        
                        // get all order items against new order
                        $order_items = $this->db->get_where('order_items', ['order_id' => $order['id']])->result_array(); 
                        if($order['branding'] == 1){
                            //branding order
                            foreach ($order_items as $key => $item) {
                                $item_status = ($item['item_type'] == 'logo') ? '0' : '5';
                                $this->db->update('order_items', ['status' => $item_status], ['id' => $item['id']]);
                            }
                        }else{
                            //non branding order (custom bundle)
                            foreach ($order_items as $key => $item) {
                                // check each design has questionnaire or not
                                $q_exist = $this->db->get_where('questions_design', ['design_id' => $item['item_id']]);
                                $item_status = ($q_exist->num_rows() > 0) ?  '0' : '1';
                                $this->db->update('order_items', ['status' => $item_status], ['id' => $item['id']]);
                            }
                        }

                        $this->cart->destroy();
                        $this->session->unset_userdata('cart_total');
                        $this->session->set_flashdata('success', $this->lang->line('pm_success'));
                        $this->session->set_flashdata('order_id', $order['id']);
                        // redirect(base_url('questionnaire'));
                        $this->load->view('checkout');
                    }else{
                        //if trans id not matched
                        $this->session->set_flashdata('error', $this->lang->line('pm_cancel'));
                        redirect(base_url().'users/show_deposite_options/'.$dataa['user_id']);
                    }
                }else{
                    // existing order payment
                    $payment = $this->db->get_where('payments', ['trans_id' => $_REQUEST['payment_reference']]);
                    if($payment->num_rows() > 0){
                        //if order is existed
                        $payment = $payment->row_array();
                        if($payment['trans_id'] == $_REQUEST['payment_reference']){
                            $this->db->update('payments', ['payment_status' => 1], ['id' => $payment['id']]);

                            // check requested item is adjustment or not
                            $is_adjustment = $this->db->get_where('adjustment_items', ['item_id' => $payment['item_id']]);
                            if($is_adjustment->num_rows() > 0){
                                // adjustment handling
                                $this->db->update('adjustment_items', ['status' => 1], ['item_id' => $payment['item_id']]);
                                $this->db->update('order_items', ['status' => 4], ['id' => $payment['item_id']]);
                            }
                            
                            //check customize add-on item
                            $custom_addon = $this->db->get_where('order_items', ['order_id' => $payment['order_id'], 'item_type' => 'custom_addon']);
                            if($custom_addon->num_rows() > 0){
                                $custom_addon = $custom_addon->result_array();
                                // update only which are currently selected items
                                foreach ($this->cart->contents() as $key => $item) {

                                    if($item['type'] == 'custom_addon'){
                                        $design_id = explode('-', $item['id']);
                                        $design_id = $design_id[0];

                                        $existed_order = $this->db->get_where('orders', ['id' => $item['order_id']]);
                                        if($existed_order->num_rows() > 0){
                                            $existed_order = $existed_order->row_array();
                                            if($existed_order['branding'] == 1){
                                                $logo_is_approved = $this->db->get_where('order_items',[
                                                    'order_id' => $existed_order['id'],
                                                    'item_type' => 'logo',
                                                    'status' => 3
                                                ]);

                                                if($logo_is_approved->num_rows() > 0){
                                                    $q_exist = $this->db->get_where('questions_design', ['design_id' => $design_id]);
                                                    $item_status = ($q_exist->num_rows() > 0) ?  '0' : '1';
                                                }else{
                                                    $item_status = '5';
                                                }

                                            }else{
                                                $q_exist = $this->db->get_where('questions_design', ['design_id' => $design_id]);
                                                $item_status = ($q_exist->num_rows() > 0) ?  '0' : '1';
                                            }
                                            
                                            $this->db->update('order_items', ['status' => $item_status], [
                                                'order_id' => $existed_order['id'],
                                                'item_id' => $design_id,
                                                'item_type' => 'custom_addon'
                                            ]);
                                        }
                                    }
                                }
                            }
                            
                            $this->cart->destroy();
                            $this->session->unset_userdata('cart_total');
                            $this->session->set_flashdata('success', $this->lang->line('pm_success'));
                            // $this->load->view('my_view');
                            $this->session->set_flashdata('order_id', $payment['order_id']);
                            redirect(base_url('dashboard'));
                        
                        }
                    }
                }
            }else{
                $this->session->set_flashdata('error', $this->lang->line('pm_cancel'));
                // redirect(base_url('checkout'));
                $this->load->view('checkout');
            }

        }else{
            $this->session->set_flashdata('error', $this->lang->line('pm_failed'));
            redirect(base_url('checkout'));
        }
    }


    // Add a new Deposite Acount
    public function save_deposite_acount()
    {
        $data['small_title'] = 'Add Deposit Detail';
        $data['formaction_path'] = 'save_deposite_acount';
        $data['category_list'] = $this->items_model->get_item_category();
        $data['all_users'] = array();    
        $this->load->library('form_validation');
        if($this->input->post()) 
        {
            $rules = array(
                array(
                    'field' => 'category_id',
                    'label' => 'Category',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'payment_type',
                    'label' => 'Payment Type',
                    'rules' => 'trim|required',
                ),
            );
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE) 
            {
                echo validation_errors();
                exit();
            } 
            if($this->input->post())
            {
                $data = array(
                    'category_id' => $this->input->post('category_id'),
                    'payment_type' => $this->input->post('payment_type'),
                    'user_id' => $this->input->post('user_id'),
                    'amount' => $this->input->post('category_amount'),
                    'created_by' => $this->loginUser->id
                );
                $data['created_on'] = date('Y-m-d H:i:s');
                $result = $this->users_model->insert_user_deposit_details($data);
                

                if(!empty($result)) 
                {
                    $user_detail = array(
                    'payment' => 'complete'
                    );

                    $this->users_model->update_user($this->input->post('user_id'),$user_detail);

                    $this->session->set_flashdata('msg', 'Payment Added Successfully');
                    $redict_users_list = "user_listing";
                    if(isset($_POST['page_name']) && $_POST['page_name'] == 'cheque')
                    {
                        //email
                        $user_email = $this->db->query('select * from users where id = "'.$this->input->post('user_id').'"')->row_array()['email']; 
                        $amount = $this->input->post('category_amount');     
                        $category_name = $this->db->query('select * from item_category where id = "'.$this->input->post('category_id').'"')->row_array()['title'];     

                        $to = $user_email;
                        $template_name = "Deposit";
                        $q = $this->db->query('select * from crm_email_template where slug = "deposit"');
                        $email_message = $q->result_array();   

                        if(!empty($email_message)){
                            $cust_name = $this->db->query('select * from users where id = "'.$this->input->post('user_id').'"')->row_array()['username'];
                            $email_messagee = str_replace("{username}", $cust_name,$email_message[0]['body']);
                            $email_messageee = str_replace("{category_name}",$category_name, $email_messagee );
                            $email_messageee = str_replace("{amount}",$amount, $email_messageee );
                            $to = $user_email;
                       
                            $this->email->to($to);
                            $this->email->subject($email_message[0]['subject']);
                            $this->email->message($email_messageee);
                            $this->email->send();
                        }
                        redirect(base_url().'users/show_deposite_options');
                        exit(); 
                    }
                    else{


                        $this->session->set_flashdata('msg', 'Detail Added Successfully');
                        $redict_users_list = "user_listing";
                        echo json_encode(array('msg'=>$redict_users_list,'mid'=>$result));
                        exit();
                    }
                }
            }
        }
        else
        {
            $this->template->load_admin('users/deposit_detail_form',$data);
            // $this->template->load_admin('users/show_deposite_options',$data);
        }
    }
    public function update_deposit_detail() {
        $id = $this->uri->segment(4);
        $data['small_title'] = 'Update Deposit Detail';
        $data['formaction_path'] = 'update_deposit_detail';
        $data['category_list'] = $this->items_model->get_item_category();
        $this->load->library('form_validation');
        $data['all_users'] = $this->users_model->get_deposit_detail_by_id($id);
        // print_r($data['all_users']); die;
        if($this->input->post()){
            $rules = array(
                array(
                    'field' => 'category_id',
                    'label' => 'Category',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'payment_type',
                    'label' => 'Payment Type',
                    'rules' => 'trim|required',
                ),
            );

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                exit();
            } else {
                $data = array(
                    'category_id' => $this->input->post('category_id'),
                    'payment_type' => $this->input->post('payment_type'),
                    'amount' => $this->input->post('category_amount'),
                    'updated_by' => $this->loginUser->id
                );
                $data['created_on'] = date('Y-m-d H:i:s');
                $result = $this->users_model->update_user_deposit_detail($this->input->post('post_id'), $data);
                if (!empty($result)) {
                    $this->session->set_flashdata('msg', 'Deposit Detail Updated Successfully');
                    $redict_users_list = "user_listing";
                    echo json_encode(array('msg'=>$redict_users_list,'mid'=>$result));
                    exit();
                    // echo "success";
                }
            }
        }
        else
        {
            $this->template->load_admin('users/deposit_detail_form',$data);
        }
        
    }
    
	// public function delete_old()
 //    {   
 //        $id = $this->input->post("id");
 //        $table = $this->input->post("obj");
 //        $res = false;
 //        if ($table == "users") {
 //            $res = $this->users_model->delete_user_row($id);
 //        }
 //        //do not change below code
 //        if ($res) {
 //            echo $return = '{"type":"success","msg":"Ok"}';
 //        } else {
 //            echo $return = '{"type":"error","msg":"Something went wrong."}';
 //        }
 //    }
    //Delete Single Row
    public function delete( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        if ($table == "users") 
        {
            $user_row = $this->users_model->users_listing($id);
            if(isset($user_row[0]['crm_id']) && !empty($user_row[0]['crm_id']))
            {
                $status_immature = array(
                    'user_status' => 'immature'
                );
            $crm_row = $this->crm_model->update_crm($user_row[0]['crm_id'],$status_immature);
            }
        }
        $result = $this->users_model->delete_select_row($id,$table);

            // $file_path = FCPATH . 'uploads/users_documents/'.$id.'/';
            // $user_row_array = $this->users_model->get_user_byid($id);
            // $item_documents_ids_str = $user_row_array[0]['documents'];
            // $documents_ids_arr = explode(",",$item_documents_ids_str); 
        //     foreach ($documents_ids_arr as $file_id2) 
        //     {
        //         $this->files_model->delete_by_id($file_id2,$file_path);  // remove all documents 
        //     }
        //     // rmdir($file_path);
        //     $res = $this->users_model->delete_user_row($id);
        // }
        //do not change below code
         if ($result) {
            echo $return = '{"type":"success","msg":"success"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

    public function delete_bank_detail()
    {	
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;
        if ($table == "user_bank_detail") {
            $res = $this->users_model->delete_user_bank_detail($id);
        }
        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }
    public function delete_deposit_detail()
    {   
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;
      
        $res = $this->users_model->delete_user_deposit_detail($id,$table);
             //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }
    //Delete Multiple Rows
    public function delete_bulk()
    {

        // $ids_array =  rtrim($_REQUEST['id'], ",");
        $ids_array = $this->input->post('id');
      
        $table = $this->input->post("obj");
         if ($table == "users") {
             $ids_array = explode(",",$ids_array);
            foreach ($ids_array as $id) 
            {
                $user_row = $this->users_model->users_listing($id);
            if(isset($user_row[0]['crm_id']) && !empty($user_row[0]['crm_id']))
            {
                $status_immature = array(
                    'user_status' => 'immature'
                );
            $crm_row = $this->crm_model->update_crm($user_row[0]['crm_id'],$status_immature);
            }
            }
            

            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');


        } 

        //do not change below code
        if ($result) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }
    // Delete bank detail multiple rows
    public function delete_bank_detail_bulk()
    {

        $ids_array =  rtrim($_REQUEST['id'], ",");
        $table = $this->input->post("obj");
         if ($table == "user_bank_detail") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        } 
        //do not change below code
        if ($result) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }	

    // public static function uploadFiles_with_path($files,$path){
    //     if(isset($files['profile_picture']['name']) && !empty($files['profile_picture']['name']))
    //     {
    //         $images = '';
    //     // for($i=0; $i<=count($files['profile_picture']['name'])-1; $i++)
    //     // {
    //         // $target_path        = "./uploads/profile_picture/"; //Declaring Path for uploaded images
    //         $target_path        = $path; //Declaring Path for uploaded images
    //         $ext                = explode('.', basename($files['profile_picture']['name']));//explode file name from dot(.)
    //         $file_extension     = end($ext); //store extensions in the variable
    //         $new_name           = md5(uniqid()) . "." . $file_extension;

    //         $tmpFilePath        = $files['profile_picture']['tmp_name'];
    //         $newFilePath        = $target_path . $new_name;//set the target path with a new name of image

    //         if(move_uploaded_file($tmpFilePath, $newFilePath))
    //         {
    //             $images .= $new_name.',';
    //         }

    //     // }
    //     $images             = trim($images, ",");

    //     return $images;
    //     }
    //     if(isset($files['documents']['name']) && !empty($files['documents']['name']))
    //     {
    //         $images = '';
    //     for($i=0; $i<=count($files['documents']['name'])-1; $i++)
    //     {
    //         $target_path        = "./uploads/users_documents/"; //Declaring Path for uploaded images
    //         $ext                = explode('.', basename($files['documents']['name'][$i]));//explode file name from dot(.)
    //         $file_extension     = end($ext); //store extensions in the variable
    //         $new_name           = md5(uniqid()) . "." . $file_extension;

    //         $tmpFilePath        = $files['documents']['tmp_name'][$i];
    //         $newFilePath        = $target_path . $new_name;//set the target path with a new name of image

    //         if(move_uploaded_file($tmpFilePath, $newFilePath))
    //         {
    //             $images .= $new_name.',';
    //         }

    //     }
    //     $images             = trim($images, ",");    
    //     return $images;
    //     }
    // }

	// public static function uploadFiles($files){
 //        if(isset($files['profile_picture']['name']) && !empty($files['profile_picture']['name']))
 //        {
 //            $images = '';
 //        for($i=0; $i<=count($files['profile_picture']['name'])-1; $i++)
 //        {
 //            $target_path        = "./uploads/profile_picture/"; //Declaring Path for uploaded images
 //            $ext                = explode('.', basename($files['profile_picture']['name'][$i]));//explode file name from dot(.)
 //            $file_extension     = end($ext); //store extensions in the variable
 //            $new_name           = md5(uniqid()) . "." . $file_extension;

 //            $tmpFilePath        = $files['profile_picture']['tmp_name'][$i];
 //            $newFilePath        = $target_path . $new_name;//set the target path with a new name of image

 //            if(move_uploaded_file($tmpFilePath, $newFilePath))
 //            {
 //                $images .= $new_name.',';
 //            }

 //        }
 //        $images             = trim($images, ",");

 //        return $images;
 //        }
 //        if(isset($files['(documents)']['name']) && !empty($files['documents']['name']))
 //        {
 //            $images = '';
 //        for($i=0; $i<=count($files['documents']['name'])-1; $i++)
 //        {
 //            $target_path        = "./uploads/users_documents/"; //Declaring Path for uploaded images
 //            $ext                = explode('.', basename($files['documents']['name'][$i]));//explode file name from dot(.)
 //            $file_extension     = end($ext); //store extensions in the variable
 //            $new_name           = md5(uniqid()) . "." . $file_extension;

 //            $tmpFilePath        = $files['documents']['tmp_name'][$i];
 //            $newFilePath        = $target_path . $new_name;//set the target path with a new name of image

 //            if(move_uploaded_file($tmpFilePath, $newFilePath))
 //            {
 //                $images .= $new_name.',';
 //            }

 //        }
 //        $images             = trim($images, ",");    
 //        return $images;
 //        }
 //    }
    public function chage_status()
    {
        if ($_GET['status_id'] == '1') {
           $status_id = array('status' => $_GET['status_id'], 'code_status' => '1', 'email_verification' => '1');
        } else {
    	   $status_id = array('status' => $_GET['status_id']);
        }
    	$user_id = $_GET['user_id'];

        //check user is auto bidder or not. if user is auto bidder than admin will unable to block the user.
        $auto_bidder = $this->db->get_where('bid_auto', ['user_id' => $user_id, 'auto_status' => 'start']);
        if($auto_bidder->num_rows() > 0){
            $this->session->set_flashdata('msg', 'Auto bid is activated by this user so unable to perform an action.');
            redirect(base_url('customers'));
        }
        
    	$return = $this->users_model->chahge_status($status_id,$user_id);
        $data = $this->users_model->users_listing($user_id);


        if(!empty($data) && $data[0]['role'] == 4){
        	
            if($data[0]['status'] == 1){
        	   $this->session->set_flashdata('msg', 'User Active Successfully');
        	}

        	if($data[0]['status'] == 0){
        		$this->session->set_flashdata('msg', 'User InActive Successfully');
        	}
            redirect(base_url().'customers');
        }else{
            
            if($data[0]['status'] == 1){
                $this->session->set_flashdata('msg', 'User Active Successfully');
            }
            
            if($data[0]['status'] == 0){
                $this->session->set_flashdata('msg', 'User InActive Successfully');
            }
            redirect(base_url().'users/');
        }

    }
    public function users_sellers_buyers()
    {
        $this->output->enable_profiler(TRUE);
        $id = 4;
        $data['small_title'] = 'Customers';
        $data['formaction_path'] = 'filter_seller_buyer';
        $data['current_page_customers'] = 'current-page';
        $data['user'] = (array)$this->loginUser;
        $data['buyers_list'] = array();
        $data['users_list'] = $this->users_model->users_list($id);
        // print_r($data['users_list']);die('aa');
        // $data['search_list'] = $this->users_model->search_list($id);
        // $data['search_list'] = $this->users_model->search_list();
        $data['users_sellers_buyers'] = $this->users_model->users_sellers_buyers();
        // $data['sales_person'] = $this->users_model->sales_person($sales_person_id);
        $this->template->load_admin('users/buyers_seller_list',$data);
    }

    public function getSellerBuyer()
    {
        $posted_data = $this->input->post();

        ## Read value
         $draw = $posted_data['draw'];
         $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
         $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
         // print_r($posted_data);die('aaa');
         $columnIndex = $posted_data['order'][0]['column']; // Column index
         // print_r($columnIndex);die('jkjk');
         $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
         // $columnName = 'id';
         $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
         $searchValue = $posted_data['search']['value']; // Search value
                 // Custom search filter 
         $company_name = (isset($posted_data['company_name'])) ? $posted_data['company_name'] : '';
         $code = (isset($posted_data['code']))? $posted_data['code'] : '';
         $username = (isset($posted_data['username']))? $posted_data['username'] : '';
         // $assigned_to = (isset($posted_data['assigned_to']))? $posted_data['assigned_to']: ''; 
         $mobile = (isset($posted_data['mobile']))? $posted_data['mobile']: ''; 
         $email = (isset($posted_data['email']))? $posted_data['email']: ''; 

         ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
            $search_arr[] = " (username like '%".$searchValue."%' ) OR (email like '%".$searchValue."%' ) ";
            // $search_arr[] = "  ";
            // $search_arr[] = " (lname like '%".$searchValue."%' ) ";
         }
         if($username != ''){
            // $username = "'".implode("','",$username)."'";
            $search_arr[] = "users.username like '%".$username."%'  ";
         }
         if($code != ''){
            // $code = "'".implode("','",$code)."'";
            $search_arr[] = " users.id =".$code." ";
         }
         if($email != ''){
            // $email = "'".implode("','",$email)."'";
            $search_arr[] = " users.email like '%".$email."%' ";
         }
         if($mobile != ''){
            // $mobile = "'".implode("','",$mobile)."'";
            $search_arr[] = " users.mobile like '%".$mobile."%' ";
         }
         if($company_name != ''){
            // $company_name = "'".implode("','",$company_name)."'";
            $search_arr[] = " users.company_name like '%".$company_name."%' ";
         }
         // if($assigned_to != ''){
         //    // $assigned_to = "'".implode("','",$assigned_to)."'";
         //    $search_arr[] = " users.id like '%".$assigned_to.") ";
         // }
       
        if(isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $search_arr[] = " (DATE(users.created_on) between '".$start_date."' and '".$end_date."') ";
        }
         if(count($search_arr) > 0){
        
            $searchQuery = ' ('.implode("AND ",$search_arr).') ';
         }

        $role = $this->loginUser->role;
        $total = $this->items_model->get_items();
        $app_user = array('4');

                ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where_in('role',$app_user);
        $records = $this->db->get('users')->result();
        $totalRecords = $records[0]->allcount; // total records

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where_in('role',$app_user);
        $records = $this->db->get('users')->result();
        $totalRecordwithFilter = $records[0]->allcount; // count filtered record 
        // print_r($this->db->last_query());die();

         ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where_in('role',$app_user);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('users')->result(); //print_r($this->db->last_query());die(); // all filtered data 
        $data = array();
        // print_r(date('Y-m-d',(strtotime($records[8]->updated_on))));die();
        foreach ($records as $record) {
        $action = '';
        $status_btn = '';

        if($role == 1){
            if($record->status == 0) {
                $status_btn = '<a class="btn btn-xs btn-success large" href="'.base_url('users/chage_status?user_id='.$record->id.'&status_id=1').'">Active</a>';
            }
            if($record->status == 1){
                $status_btn = '<a class="btn btn-xs btn-warning" href="'.base_url().'users/chage_status?user_id='.$record->id.'&status_id=0">Inactive</a>';
            }
        }else{
            if($record->status == 0) {
                $status_btn = 'Inactive';
            }
            if($record->status == 1) {
                $status_btn = 'Active';
            }
        }


        if($role == 1){
            $action .= '<a href="'.base_url().'users/update_user/'.$record->id.'?user_type=customers" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            // $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="users" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record->id.'" data-url="'.base_url().'users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';
            $action .= '<a href="'.base_url().'users/add_documents/'.$record->id.'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Add Documents</a>';
            $action .= '<a href="'.base_url().'users/documents/'.$record->id.'/byr_sellr" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Documents</a>';
        }

        $action .= '<a href="'.base_url().'users/bank_detail/'.$record->id.'" class="btn btn-info btn-xs"><i class="fa fa-bank"> </i>Bank Detail</a>';
        $action .= '<a href="'.base_url().'user-deposits-general/'.$record->id.'" class="btn btn-primary btn-xs"><i class="fa fa-money"></i> Deposits</a>';
        $action .= '<a href="'.base_url().'user-deposits/'.$record->id.'" class="btn btn-primary btn-xs"><i class="fa fa-money"></i> Auction Item Security</a>';
        $action .= '<a href="'.base_url().'user-payments/'.$record->id.'" class="btn btn-warning btn-xs"><i class="fa fa-money"></i> Payments</a>';
        $action .= '<a href="'.base_url().'user-payables/'.$record->id.'" class="btn btn-warning btn-xs"><i class="fa fa-money"></i> Payables </a>';


        // if($record->role == 1){ $record_role = " Admin"; } 
        // if($record->role == 2) { $record_role = " Sales Manager"; } 
        // if($record->role == 3) { $record_role = " Sales Person";}
        // if($record->role == 5) { $record_role = " Operational Department";}
        // if($record->role == 6) { $record_role = " Tasker";}
        if($record->updated_on == '0000-00-00 00:00:00') {
            $record->updated_on = NULL;
        }

       $data[] = array( 
         "user_id" => $record->id,
         "username"=> $record->username,
         "id"=> $record->id,
         "reg_type"=> $record->reg_type,
         "company_name"=> $record->company_name,
         "mobile"=> $record->mobile,
         "email"=> $record->email,
         "created_on"=> date('Y-m-d',strtotime($record->created_on)),
         "updated_on"=> (!empty($record->updated_on)) ? date('Y-m-d',strtotime($record->updated_on)) : '',
         "status"=> $status_btn,
         "action"=> $action
       ); 
     }
                 ## Response
     $response = array(
       "draw" => intval($draw),
       "iTotalRecords" => $totalRecords,
       "iTotalDisplayRecords" => $totalRecordwithFilter,
       "aaData" => $data
     );
        echo json_encode($response); 
    }
    
    public function validate_mobile()
    {

        $check_number = $this->users_model->check_user_number($_GET['mobile']);

        if(isset($check_number) && !empty($check_number)){
            echo '404';
        }
        else
        {
            echo '200';
        }
    }

    public function validate_email()
    {
        $check_number = $this->users_model->check_email($_GET['email']);
        // print_r($check_number);die();
        if(isset($check_number) && !empty($check_number))
        {
            echo '404';
        }
        else
        {
            echo '200';
        }
    }

    public function documents()
    {
        $data = array();
        // $data['small_title'] = 'Manage Documents';
        $data['formaction_path'] = 'update_item';
        $data['current_page_customers'] = 'current-page';
        $data['user_id'] = $this->uri->segment(3);
        $user_id = $this->uri->segment(3);     

        $data['documents_info'] = $this->db->select('user_documents.*,documents_type.name,users.username')
        ->from('user_documents')
        ->join('documents_type', 'user_documents.document_type_id = documents_type.id', 'LEFT')
        ->join('users', 'user_documents.user_id = users.id', 'LEFT')->where('user_id',$user_id)
        ->get()
        ->result_array();
        if ($this->input->post()) 
        {

        }
        else
        {
              $this->template->load_admin('users/documents_form', $data);
        }
    }

    public function add_documents()
    {
        $data = array();
        $data['document_types'] = $this->db->get_where('documents_type')->result_array();
        // $data['small_title'] = 'Manage Documents';
        $data['formaction_path'] = 'update_item';
        $data['current_page_customers'] = 'current-page';
        $data['user_id'] = $this->uri->segment(3);
        $user_id = $this->uri->segment(3);     

        $data['user_info'] = $this->users_model->get_user_byid($user_id);
        if(isset($data['user_info']) && !empty($data['user_info']))
        {
            $data['documents'] = json_decode($data['user_info'][0]['documents'], true); 
            // print($data['documents']);die(); 
        }
        if ($this->input->post()) 
        {

        }
        else
        {
              $this->template->load_admin('users/add_documents_form', $data);
        }
    }

    public function user_payments($user_id)
    {
        $this->output->enable_profiler(TRUE);
        if(!empty($user_id)){
            
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            $sold_items = $this->users_model->get_user_payments($user_id);
            //print_r($sold_items);die();

            $this->template->load_admin('users/payments', [
                'user' => $user,
                'current_page_customers' => 'current-page',
                'sold_items' => $sold_items,
                'language' => $this->language
            ]);
        }else{
            show_404();
        }
    }

    public function user_payables($user_id)
    {
        if(!empty($user_id)){
            
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            $sold_items = $this->users_model->get_user_payables($user_id);
            //print_r($sold_items);die();

            $this->template->load_admin('users/payables', [
                'user' => $user,
                'current_page_customers' => 'current-page',
                'sold_items' => $sold_items
            ]);
        }else{
            show_404();
        }
    }

    public function payment_receipt($sold_item_id)
    {
        //echo $sold_item_id;
        $data = array();
        $data['small_title'] = 'Payment Receipt';
        $data['generate_link'] = 'generate_buyer_receipt';
        $data['invoice_link'] = 'users/view_payment_receipt/'.$sold_item_id;
        $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer']);
        if ($invoice->num_rows() > 0) {
            $data['button_link'] = 'Update Payments';
            $invoice = $invoice->row_array();
            if ($invoice['receipt_status'] == '0') {
                $data['receipt_button'] = 'Generate Receipt';
            } else {
                $data['receipt_button'] = 'View Receipt';
            }
        } else {
            $data['button_link'] = 'Generate Payments';
        }
        if($sold_item_id){
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $item = $this->db->get_where('item', ['id' => $sold_item['item_id']])->row_array();
            $buyer = $this->db->get_where('users', ['id' => $sold_item['buyer_id']])->row_array();
            $auction = $this->db->get_where('auctions', ['id' => $sold_item['auction_id']])->row_array();
            $auction_item = $this->db->get_where('auction_items', ['id' => $sold_item['auction_item_id']])->row_array();
            $vat_row = $this->db->select('*')->get_where('settings', ['code_key' => 'vat'])->row_array();
            $invoices = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();

            // $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            // $amount_in_words = $spellout->format($sold_item['payable_amount']);

            $this->template->load_admin('users/receipt', [
                'current_page_customers' => 'current-page',
                'data' => $data,
                'sold_item' => $sold_item,
                'item' => $item,
                'invoices' => $invoices,
                'buyer' => $buyer,
                'vat_row' => $vat_row,
                'vat_row' => $vat_row,
                'auction' => $auction,
                'auction_item' => $auction_item
            ]);
        }else{
            show_404();
        }
    }

    public function view_payment_receipt($sold_item_id)
    {
       $exist = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
        if($exist){
            $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
            if ($invoice['receipt_status'] == '0') {
                // '-01-' use for buyer receipt, --- '-02-' use for buyer invoice, --- '-03-' use for buyer statement, and '-04-' use for seller invoice
                $receipt_no = (!empty($invoice['receipt_no'])) ? json_decode($invoice['receipt_no'], true) : array();
                $receipt = date('y-M');
                $receipt = $receipt.'-01-'.$sold_item_id;
                $receipt_no['b_receipt'] = $receipt;
                $json_receipt_no = json_encode($receipt_no);
                $receipt_date = json_decode($invoice['receipt_date'], true);
                $receipt_date['b_receipt'] = date('Y-m-d H:i:s');
                $dates = json_encode($receipt_date);

                $generated_by = json_decode($invoice['generated_by'], true);
                $generated_by['b_receipt'] = $this->session->userdata('logged_in')->id;
                $generated_by_obj = json_encode($generated_by);
                $this->db->update('invoices', ['receipt_status' => '1','receipt_no' => $json_receipt_no,'receipt_date' => $dates,'generated_by' => $generated_by_obj], ['sold_item_id' => $sold_item_id, 'type' => 'buyer']);
            }
            $data = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
            if ($invoice['receipt_status'] == '0') {
                $data['new_recipt'] = 'yes'; 
            }
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $this->load->view('receipts/cash_receipt', ['data' => $data, 'sold_item' => $sold_item]); 
        }else{
            show_404();
        }
    }

    public function generate_buyer_receipt()
    {
        $data = $this->input->post();
        if($data){
            $sold_item_id = $data['sold_item_id'];
            $general_expenses = $data['general_expenses'];
            $general_description = $data['general_description'];
            $general_expenses_amount = $data['general_expenses_amount'];

            unset($data['general_expenses']);
            unset($data['general_description']);
            unset($data['general_expenses_amount']);
            $new_data = [
                'user_id' => $data['user_id'],
                'type' => 'buyer',
                'user_po_box' => $data['user_po_box'],
                'user_address' => $data['user_address'],
                'user_mobile_number' => $data['user_mobile_number'],
                'item_id' => $data['item_id'],
                'vin_number' => $data['vin_number'],
                'auction_id' => $data['auction_id'],
                'sold_item_id' => $data['sold_item_id'],
                'vat' => $data['vat'],
                'adjustments' => $data['adjustments'],
                'lot_no' => $data['lot_no'],
                'ref_no' => $data['ref_no'],
                'customer_name' => $data['customer_name'],
                'purpose' => $data['purpose'],
                'win_price' => $data['win_price'],
                'payment_terms' => $data['payment_terms'],
                'trn_no' => $data['trn_no'],
                'description' => $data['description'],
                'bank_id' => $data['bank'],
                'payable_amount' => $data['amount']
            ];
            $apply_vat_array = [];
            $comission_filelds = $this->db->select('*')->get_where('seller_charges', ['user_type' => 'buyer','status' => 'active'])->result_array();
            foreach ($comission_filelds as $key => $value) {
                $title = json_decode($value['title']);
                $field_title = strtolower(str_replace(' ', '_', $title->english));
                $apply_vat_array[$field_title] = $value['apply_vat'];
            }
            $new_data['apply_vat'] = json_encode($apply_vat_array);
            $new_data['general_expenses'] = json_encode($general_expenses);
            $new_data['general_description'] = json_encode($general_description);
            $new_data['general_expenses_amount'] = json_encode($general_expenses_amount);

            unset($data['user_id']);
            unset($data['user_po_box']);
            unset($data['user_address']);
            unset($data['user_mobile_number']);
            unset($data['item_id']);
            unset($data['vin_number']);
            unset($data['auction_id']);
            unset($data['sold_item_id']);
            unset($data['vat']);
            unset($data['adjustments']);
            unset($data['lot_no']);
            unset($data['customer_name']);
            unset($data['purpose']);
            unset($data['win_price']);
            unset($data['payment_terms']);
            unset($data['trn_no']);
            unset($data['ref_no']);
            unset($data['description']);
            unset($data['bank']);
            unset($data['amount']);
            $new_data['other_details'] = json_encode($data);
            $invoice_exist = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer']);
            if ($invoice_exist->num_rows() == 0) {
                $this->db->insert('invoices', $new_data);
                // print_r($data);
            } else {
                $this->db->update('invoices', $new_data, ['sold_item_id' => $sold_item_id, 'type' => 'buyer']);
            }

            $this->session->set_flashdata('success', 'Payment data updated successfully.');
            // print_r($sold_item_id);die();
            // print_r($_SERVER['HTTP_REFERER']);
            redirect($_SERVER['HTTP_REFERER']);
            // print_r($data);
            // $this->load->view('receipts/cash_receipt', ['data' => $data]); 
        }else{
            show_404();
        }
    }

    public function buyer_invoice($sold_item_id)
    {
        //echo $sold_item_id;
        $data = array();
        $data['small_title'] = 'Buyer Tax Invoice';
        $data['generate_link'] = 'generate_buyer_receipt';
        $data['invoice_link'] = 'users/view_buyer_tex_invoice/'.$sold_item_id;
        $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer']);
        if ($invoice->num_rows() > 0) {
            $data['button_link'] = 'Update Payments';
            $invoice = $invoice->row_array();
            if ($invoice['invoice_status'] == '0') {
                $data['receipt_button'] = 'Generate Invoice';
            } else {
                $data['receipt_button'] = 'View Invoice';
            }
        } else {
            $data['button_link'] = 'Generate Payments';
        }
        // $data['button_link'] = 'Generate Invoice';
        if($sold_item_id){
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $item = $this->db->get_where('item', ['id' => $sold_item['item_id']])->row_array();
            $buyer = $this->db->get_where('users', ['id' => $sold_item['buyer_id']])->row_array();
            $auction = $this->db->get_where('auctions', ['id' => $sold_item['auction_id']])->row_array();
            $auction_item = $this->db->get_where('auction_items', ['id' => $sold_item['auction_item_id']])->row_array();
            $bank_list = array();
            $bank_info = $this->db->get('bank_info')->result_array();

            $vat_row = $this->db->select('*')->get_where('settings', ['code_key' => 'vat'])->row_array();
            $invoices = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();

            $amount_in_words = '';
            // $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            // $amount_in_words = $spellout->format($sold_item['payable_amount']);

            $this->template->load_admin('users/receipt', [
                'current_page_customers' => 'current-page',
                'sold_item' => $sold_item,
                'data' => $data,
                'item' => $item,
                'invoices' => $invoices,
                'buyer' => $buyer,
                'vat_row' => $vat_row,
                'auction' => $auction,
                'auction_item' => $auction_item,
                'amount_in_words' => $amount_in_words,
                'bank_info' => $bank_info,
                'bank_list' => $bank_list
            ]);
        }else{
            show_404();
        }
    }

    public function view_buyer_tex_invoice($sold_item_id)
    {
        $exist = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
        if($exist){
            $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
            if ($invoice['invoice_status'] == '0') {
                $receipt_no = (!empty($invoice['receipt_no'])) ? json_decode($invoice['receipt_no'], true) : array();
                $receipt = date('y-M');
                $receipt = $receipt.'-02-'.$sold_item_id;
                $receipt_no['b_invoice'] = $receipt;
                $json_receipt_no = json_encode($receipt_no);
                
                $receipt_date = json_decode($invoice['receipt_date'], true);
                $receipt_date['b_invoice'] = date('Y-m-d H:i:s');
                $dates = json_encode($receipt_date);

                $generated_by = json_decode($invoice['generated_by'], true);
                $generated_by['b_invoice'] = $this->session->userdata('logged_in')->id;
                $generated_by_obj = json_encode($generated_by);
                $this->db->update('invoices', ['invoice_status' => '1', 'receipt_no' => $json_receipt_no, 'receipt_date' => $dates, 'generated_by' => $generated_by_obj], ['sold_item_id' => $sold_item_id, 'type' => 'buyer']);
            }
            $data = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();

            if ($invoice['invoice_status'] == '0') {
                $data['new_recipt'] = 'yes';
            }
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $this->load->view('receipts/buyer_invoice', ['data' => $data, 'sold_item' => $sold_item]); 
        }else{
            show_404();
        }
    }

    public function buyer_payment_detail($sold_item_id)
    {
        $sold_item = $this->db->get_where('sold_items',['id'=>$sold_item_id])->row_array();
        $user = $this->db->get_where('users',['id'=>$sold_item['buyer_id']])->row_array();
        
        $data = [];
        if($sold_item['payment_details']){
            $data = json_decode($sold_item['payment_details'],true);
        }

        $fields = [];
        if ($sold_item['payment_mode'] == 'cheque') {
            $fields = $this->load->view('users/deposit_mode_cheque',['data'=>$data],true);
        }
        if ($sold_item['payment_mode'] == 'manual_deposit') {
             $fields = $this->load->view('users/deposit_mode_manual',['data'=>$data],true);
        }
        $this->template->load_admin('payment_detail',['sold_item'=> $sold_item,'user'=> $user,'fields'=> $fields]);

    }


    public function buyer_statement($sold_item_id)
    {
        //echo $sold_item_id;
        $data = array();
        $data['small_title'] = 'Buyer Statement';
        $data['generate_link'] = 'generate_buyer_receipt';
        $data['invoice_link'] = 'users/view_buyer_tex_statement/'.$sold_item_id;
        $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer']);
        if ($invoice->num_rows() > 0) {
            $data['button_link'] = 'Update Payments';
            $invoice = $invoice->row_array();
            if ($invoice['statement_status'] == '0') {
                $data['receipt_button'] = 'Generate Statement';
            } else {
                $data['receipt_button'] = 'View Statement';
            }
        } else {
            $data['button_link'] = 'Generate Payments';
        }
        if($sold_item_id){
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $item = $this->db->get_where('item', ['id' => $sold_item['item_id']])->row_array();
            $buyer = $this->db->get_where('users', ['id' => $sold_item['buyer_id']])->row_array();
            $auction = $this->db->get_where('auctions', ['id' => $sold_item['auction_id']])->row_array();
            $auction_item = $this->db->get_where('auction_items', ['id' => $sold_item['auction_item_id']])->row_array();
            $vat_row = $this->db->select('*')->get_where('settings', ['code_key' => 'vat'])->row_array();
            $invoices = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
            $amount_in_words = '';
            // $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            // $spellout = ;
            // $amount_in_words = $spellout->format($sold_item['payable_amount']);

            $this->template->load_admin('users/receipt', [
                'current_page_customers' => 'current-page',
                'sold_item' => $sold_item,
                'data' => $data,
                'item' => $item,
                'invoices' => $invoices,
                'vat_row' => $vat_row,
                'buyer' => $buyer,
                'auction' => $auction,
                'auction_item' => $auction_item,
                'amount_in_words' => $amount_in_words
            ]);
        }else{
            show_404();
        }
    }

    public function view_buyer_tex_statement($sold_item_id)
    {
        $exist = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
        if($exist){
            $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
            if ($invoice['statement_status'] == '0') {
                $receipt_no = (!empty($invoice['receipt_no'])) ? json_decode($invoice['receipt_no'], true) : array();
                $receipt = date('y-M');
                $receipt = $receipt.'-03-'.$sold_item_id;
                $receipt_no['b_statement'] = $receipt;
                $json_receipt_no = json_encode($receipt_no);

                $receipt_date = json_decode($invoice['receipt_date'], true);
                $receipt_date['b_statement'] = date('Y-m-d H:i:s');
                $dates = json_encode($receipt_date);

                $generated_by = json_decode($invoice['generated_by'], true);
                $generated_by['b_statement'] = $this->session->userdata('logged_in')->id;
                $generated_by_obj = json_encode($generated_by);

                $this->db->update('invoices', ['statement_status' => '1','receipt_no' => $json_receipt_no,'receipt_date' => $dates,'generated_by' => $generated_by_obj], ['sold_item_id' => $sold_item_id, 'type' => 'buyer']);
            }
            $data = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'buyer'])->row_array();
            if ($invoice['statement_status'] == '0') {
                $data['new_recipt'] = 'yes';
            }
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $this->load->view('receipts/buyer_statement', ['data' => $data, 'sold_item' => $sold_item]); 
        }else{
            show_404();
        }
    }

    public function seller_invoice($sold_item_id)
    {
        //echo $sold_item_id;
        $data = array();
        $data['small_title'] = 'Seller Tax Invoice';
        $data['generate_link'] = 'generate_seller_tex_invoice';
        $data['invoice_link'] = 'view-seller-invoice/'.$sold_item_id;
        $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller']);
        if ($invoice->num_rows() > 0) {
            $data['button_link'] = 'Update Payments';
            $invoice = $invoice->row_array();
            if ($invoice['statement_status'] == '0') {
                $data['receipt_button'] = 'Generate Statement';
            } else {
                $data['receipt_button'] = 'View Statement';
            }
        } else {
            $data['button_link'] = 'Generate Payments';
        }
        if($sold_item_id){
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $item = $this->db->get_where('item', ['id' => $sold_item['item_id']])->row_array();
            $seller = $this->db->get_where('users', ['id' => $sold_item['seller_id']])->row_array();
            $auction = $this->db->get_where('auctions', ['id' => $sold_item['auction_id']])->row_array();
            $auction_item = $this->db->get_where('auction_items', ['id' => $sold_item['auction_item_id']])->row_array();
            $invoices = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller'])->row_array();
            $bank_list = array();
            $bank_info = $this->db->get('bank_info')->result_array();
            $vat_row = $this->db->select('*')->get_where('settings', ['code_key' => 'vat'])->row_array();

            // $amount_in_words = '';
            // $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            // $amount_in_words = $spellout->format($sold_item['payable_amount']);

            $this->template->load_admin('users/seller-receipt', [
                'current_page_customers' => 'current-page',
                'sold_item' => $sold_item,
                'invoices' => $invoices,
                'data' => $data,
                'item' => $item,
                'seller' => $seller,
                'auction' => $auction,
                'vat_row' => $vat_row,
                'auction_item' => $auction_item,
                // 'amount_in_words' => $amount_in_words,
                // 'amount_in_words' => 'amount_in_words',
                'bank_info' => $bank_info,
                'bank_list' => $bank_list
            ]);
        }else{
            show_404();
        }
    }

    public function generate_seller_tex_invoice()
    {
        $data = $this->input->post();
        if($data){
            $sold_item_id = $data['sold_item_id'];
            $item_expenses = $data['item_expenses'];
            $item_description = $data['item_description'];
            $item_expenses_amount = $data['item_expenses_amount'];
            $general_expenses = $data['general_expenses'];
            $general_description = $data['general_description'];
            $general_expenses_amount = $data['general_expenses_amount'];
            unset($data['item_expenses']);
            unset($data['item_description']);
            unset($data['item_expenses_amount']);
            unset($data['general_expenses']);
            unset($data['general_description']);
            unset($data['general_expenses_amount']);
            $new_data = [
                'user_id' => $data['user_id'],
                'type' => 'seller',
                'user_po_box' => $data['user_po_box'],
                'user_address' => $data['user_address'],
                'user_mobile_number' => $data['user_mobile_number'],
                'item_id' => $data['item_id'],
                'vin_number' => $data['vin_number'],
                'auction_id' => $data['auction_id'],
                'sold_item_id' => $data['sold_item_id'],
                'vat' => $data['vat'],
                'adjustments' => $data['adjustments'],
                'lot_no' => $data['lot_no'],
                'customer_name' => $data['customer_name'],
                'purpose' => $data['purpose'],
                'win_price' => $data['win_price'],
                'payment_terms' => $data['payment_terms'],
                'trn_no' => $data['trn_no'],
                'ref_no' => $data['ref_no'],
                'description' => $data['description'],
                'bank_id' => $data['bank'],
                'discount' => $data['discount'],
                // 'payable_amount_words' => $data['amount_words'],
                'payable_amount' => $data['amount']
            ];
            $apply_vat_array = [];
            $comission_filelds = $this->db->select('*')->get_where('seller_charges', ['user_type' => 'seller','status' => 'active'])->result_array();
            $item_expenses = $this->db->get_where('item_expencses', ['item_id' => $data['item_id']])->result_array();
            foreach ($comission_filelds as $key => $value) {
                $title = json_decode($value['title']);
                $field_title = strtolower(str_replace(' ', '_', $title->english));
                $apply_vat_array[$field_title] = $value['apply_vat'];
            }
            foreach ($item_expenses as $key1 => $value) {
                $title = json_decode($value['title']);
                $item_field_title = strtolower(str_replace(' ', '_', $title->english));
                $apply_vat_array[$item_field_title] = $value['apply_vat'];
            }
            $new_data['apply_vat'] = json_encode($apply_vat_array);
            $new_data['item_expenses'] = json_encode($item_expenses);
            $new_data['item_description'] = json_encode($item_description);
            $new_data['item_expenses_amount'] = json_encode($item_expenses_amount);
            $new_data['general_expenses'] = json_encode($general_expenses);
            $new_data['general_description'] = json_encode($general_description);
            $new_data['general_expenses_amount'] = json_encode($general_expenses_amount);

            unset($data['user_id']);
            unset($data['user_po_box']);
            unset($data['user_address']);
            unset($data['user_mobile_number']);
            unset($data['item_id']);
            unset($data['vin_number']);
            unset($data['auction_id']);
            unset($data['sold_item_id']);
            unset($data['vat']);
            unset($data['adjustments']);
            unset($data['lot_no']);
            unset($data['customer_name']);
            unset($data['purpose']);
            unset($data['win_price']);
            unset($data['payment_terms']);
            unset($data['trn_no']);
            unset($data['ref_no']);
            unset($data['description']);
            unset($data['bank']);
            unset($data['discount']);
            unset($data['amount']);
            unset($data['amount_words']);
            $new_data['other_details'] = json_encode($data);
            $invoice_exist = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller']);
            if ($invoice_exist->num_rows() == 0) {
                $this->db->insert('invoices', $new_data);
                // print_r($data);
            } else {
                $this->db->update('invoices', $new_data, ['sold_item_id' => $sold_item_id, 'type' => 'seller']);
            }

            $this->session->set_flashdata('success', 'Payment data updated successfully.');
            // print_r($sold_item_id);die();
            redirect(base_url('seller-invoice/'.$sold_item_id));
            // $this->load->view('receipts/seller-invoice', ['data' => $data]); 
        }else{
            show_404();
        }
    }

    public function view_seller_tex_invoice($sold_item_id)
    {
        // $data = $this->input->post();
        $exist = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller'])->row_array();
        if($exist){
            $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller'])->row_array();
            if ($invoice['invoice_status'] == '0') {
                $receipt_no = (!empty($invoice['receipt_no'])) ? json_decode($invoice['receipt_no'], true) : array();
                $receipt = date('y-M');
                $receipt = $receipt.'-04-'.$sold_item_id;
                $receipt_no['s_invoice'] = $receipt;
                $json_receipt_no = json_encode($receipt_no);

                $receipt_date = json_decode($invoice['receipt_date'], true);
                $receipt_date['s_invoice'] = date('Y-m-d H:i:s');
                $dates = json_encode($receipt_date);

                $generated_by = json_decode($invoice['generated_by'], true);
                $generated_by['s_invoice'] = $this->session->userdata('logged_in')->id;
                $generated_by_obj = json_encode($generated_by);
                $this->db->update('invoices', ['invoice_status' => '1','receipt_no' => $json_receipt_no,'receipt_date' => $dates,'generated_by' => $generated_by_obj], ['sold_item_id' => $sold_item_id, 'type' => 'seller']);
            }
            $data = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller'])->row_array();
            // print_r($data);
            if ($invoice['invoice_status'] == '0') {
                $data['new_recipt'] = 'yes';
            }
            $this->load->view('receipts/seller-invoice', ['data' => $data]); 
        }else{
            show_404();
        }
    }

    public function seller_statement($sold_item_id)
    {
        //echo $sold_item_id;
        $data = array();
        $data['small_title'] = 'Seller Statement';
        $data['generate_link'] = 'generate_seller_tex_invoice';
        $data['invoice_link'] = 'view-seller-statement/'.$sold_item_id;
        $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller']);
        if ($invoice->num_rows() > 0) {
            $data['button_link'] = 'Update Payments';
            $invoice = $invoice->row_array();
            if ($invoice['statement_status'] == '0') {
                $data['receipt_button'] = 'Generate Statement';
            } else {
                $data['receipt_button'] = 'View Statement';
            }
        } else {
            $data['button_link'] = 'Generate Payments';
        }
        if($sold_item_id){
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $item = $this->db->get_where('item', ['id' => $sold_item['item_id']])->row_array();
            $seller = $this->db->get_where('users', ['id' => $sold_item['seller_id']])->row_array();
            $auction = $this->db->get_where('auctions', ['id' => $sold_item['auction_id']])->row_array();
            $auction_item = $this->db->get_where('auction_items', ['id' => $sold_item['auction_item_id']])->row_array();
            $invoices = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller'])->row_array();
            $bank_list = array();
            $bank_info = $this->db->get('bank_info')->result_array();
            $vat_row = $this->db->select('*')->get_where('settings', ['code_key' => 'vat'])->row_array();

            // $amount_in_words = '';
            // $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            // $amount_in_words = $spellout->format($sold_item['payable_amount']);

            $this->template->load_admin('users/seller-receipt', [
                'current_page_customers' => 'current-page',
                'sold_item' => $sold_item,
                'invoices' => $invoices,
                'data' => $data,
                'item' => $item,
                'seller' => $seller,
                'auction' => $auction,
                'vat_row' => $vat_row,
                'auction_item' => $auction_item,
                // 'amount_in_words' => $amount_in_words,
                // 'amount_in_words' => 'amount_in_words',
                'bank_info' => $bank_info,
                'bank_list' => $bank_list
            ]);
        }else{
            show_404();
        }
    }

    public function view_seller_statement($sold_item_id)
    {
        // $data = $this->input->post();
        $exist = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller'])->row_array();
        if($exist){
            $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller'])->row_array();
            if ($invoice['statement_status'] == '0') {
                $receipt_no = (!empty($invoice['receipt_no'])) ? json_decode($invoice['receipt_no'], true) : array();
                $receipt = date('y-M');
                $receipt = $receipt.'-05-'.$sold_item_id;
                $receipt_no['s_statement'] = $receipt;
                $json_receipt_no = json_encode($receipt_no);

                $receipt_date = json_decode($invoice['receipt_date'], true);
                $receipt_date['s_statement'] = date('Y-m-d H:i:s');
                $dates = json_encode($receipt_date);

                $generated_by = json_decode($invoice['generated_by'], true);
                $generated_by['s_statement'] = $this->session->userdata('logged_in')->id;
                $generated_by_obj = json_encode($generated_by);
                $this->db->update('invoices', ['statement_status' => '1','receipt_no' => $json_receipt_no,'receipt_date' => $dates,'generated_by' => $generated_by_obj], ['sold_item_id' => $sold_item_id, 'type' => 'seller']);
            }
            $data = $this->db->get_where('invoices', ['sold_item_id' => $sold_item_id, 'type' => 'seller'])->row_array();
            // print_r($data);
            if ($invoice['statement_status'] == '0') {
                $data['new_recipt'] = 'yes';
            }
            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();
            $this->load->view('receipts/seller_statement', ['data' => $data, 'sold_item' => $sold_item]); 
        }else{
            show_404();
        }
    }

    public function pay_to_seller($sold_item_id,$user_id)
    {
        if($sold_item_id){
            $invoice = $this->db->update('sold_items',['seller_payment_status' => '1'], ['id' => $sold_item_id]);
            $this->session->set_flashdata('success', 'Pay Successfully.');
            redirect(base_url('user-payables/'.$user_id));
            // $this->load->view('receipts/seller-invoice', ['data' => $data]);
        }else{
            show_404();
        }
    }

    public function adjust_security($user_id, $auction_item_deposits_id, $sold_item_id, $item_security_amount)
    {
        $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();

        $payable_amount = (float)$sold_item['payable_amount'] - (float)$item_security_amount;

        $result = $this->db->update('sold_items', [
            'adjusted_security' => $item_security_amount,
            'payable_amount' => $payable_amount
        ], ['id' => $sold_item_id]);

        if($result){
            $this->db->update('auction_item_deposits', ['status' => 'adjusted'], ['id' => $auction_item_deposits_id]);
            $this->session->set_flashdata('success', 'Security has been adjusted successfully!');
        }else{
            $this->session->set_flashdata('error', 'Security has been failed to adjust.');
        }
        redirect(base_url('user-payments/'.$user_id));
    }

    public function adjust_deposit($user_id='',$sold_item_id='',$deposit_amount)
    {
        if(!empty($user_id) && !empty($sold_item_id) && !empty($deposit_amount)){

            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();

            //check for user is highest bidder or not
            // $sql = "
            //     SELECT * FROM bid
            //     INNER JOIN  ( Select max(bid_time) as LatestDate from bid  Group by bid.user_id  ) SubMax on bid.bid_time = SubMax.LatestDate
            //     LEFT JOIN bid_auto ON bid.user_id = bid_auto.user_id
            //     LEFT JOIN auctions ON bid.auction_id = auctions.id
            //     WHERE bid.user_id = '".$user_id."' AND bid.bid_status != 'won' AND ( CURDATE() BETWEEN auctions.start_time AND auctions.expiry_time )
            // ";

            // $query = $this->db->query($sql);

            $heighest_bids = $this->oam->sum_user_heighest_bids($user_id, $data['item_id']);

            // if($query->num_rows() > 0){
            if(!empty($heighest_bids)){
                $this->session->set_flashdata('error', 'Unable to adjust deposit due to user is bidding in another item.');
                redirect(base_url('user-payments/'.$user_id));
            }




            $price = (float)$sold_item['payable_amount'];
            $deposit_amount = (float)$deposit_amount;

            if($price > $deposit_amount){
                $payable_amount = $price - $deposit_amount;

                $sold_items_where = [
                    'adjusted_deposit' => $deposit_amount,
                    'payable_amount' => $payable_amount
                ];
            }else{
                $payable_amount = 0;

                $sold_items_where = [
                    'adjusted_deposit' => $price,
                    'payable_amount' => $payable_amount,
                    'payment_status' => 1,
                    'payment_mode' => 'adjustment'
                ];
            }

            $result = $this->db->update('sold_items', $sold_items_where, ['id' => $sold_item_id]);

            if($result){
                $deposit_data = [
                    'user_id' => $user_id,
                    'deposit_type' => 'permanent',
                    'payment_type' => 'adjustment',
                    'amount' => $sold_items_where['adjusted_deposit'],
                    'detail' => json_encode(['sold_item_id' => $sold_item_id]),
                    'created_on' => date('Y-m-d H:i:s'),
                    'account' => 'CR',
                    'status' => 'approved',
                    'deleted' => 'no'
                ];
                $this->db->insert('auction_deposit', $deposit_data);
                //$deposits = explode(',', urldecode($deposit_ids));
                //$this->db->where_in('id', $deposits)->update('auction_deposit', ['status' => 'adjusted']);
                $this->session->set_flashdata('success', 'Deposit amount has been adjusted successfully!');
            }else{
                $this->session->set_flashdata('error', 'Deposit amount has been failed to adjust.');
            }
            redirect(base_url('user-payments/'.$user_id));
        }else{
            show_404();
        }
    }

    public function pay_payment()
    {
        $data = $this->input->post();
        if($data){
            //print_r($data);
            $sold_item_id = $data['sold_item_id'];
            $user_id = $data['user_id'];
            unset($data['sold_item_id']);
            unset($data['user_id']);
            unset($data['payable_amount']);

            if($data['payment_mode'] == 'cheque'){
                $cheque_detail = [
                    'cheque_date' => $data['cheque_date'],
                    'cheque_title' => $data['cheque_title'],
                    'cheque_type' => $data['cheque_type'],
                    'cheque_number' => $data['cheque_number'],
                    'account_title' => $data['account_title'],
                    'account_number' => $data['account_number'],
                    'bank_name' => $data['bank_name']
                ];

                unset($data['cheque_date']);
                unset($data['cheque_title']);
                unset($data['cheque_type']);
                unset($data['cheque_number']);
                unset($data['account_title']);
                unset($data['account_number']);
                unset($data['bank_name']);

                $data['payment_details'] = json_encode($cheque_detail);
            }

            if($data['payment_mode'] == 'manual_deposit'){
                $deposit_detail = [
                    'deposit_date' => $data['deposit_date'],
                    'bank_name' => $data['bank_name'],
                    'bank_branch' => $data['bank_branch'],
                    'deposit_type' => $data['deposit_type'],
                    'deposit_currency' => $data['deposit_currency'],
                    'account_title' => $data['account_title'],
                    'account_number' => $data['account_number'],
                    'depositor_name' => $data['depositor_name'],
                    'depositor_id' => $data['depositor_id'],
                    'depositor_phone' => $data['depositor_phone'],
                    'deposit_txn_id' => $data['deposit_txn_id']
                ];

                unset($data['deposit_date']);
                unset($data['bank_name']);
                unset($data['bank_branch']);
                unset($data['deposit_type']);
                unset($data['deposit_currency']);
                unset($data['account_title']);
                unset($data['account_number']);
                unset($data['depositor_name']);
                unset($data['depositor_id']);
                unset($data['depositor_phone']);
                unset($data['deposit_txn_id']);

                $data['payment_details'] = json_encode($deposit_detail);
            }

            $data['payment_status'] = 1;
            //print_r($data);die();
            $result = $this->db->update('sold_items', $data, ['id' => $sold_item_id]);
            if($result){
                $this->session->set_flashdata('success', 'Payment has been successfully paid.');
            }else{
                $this->session->set_flashdata('error', 'Payment has been failed to pay.');
            }
            redirect(base_url('user-payments/'.$user_id));

        }else{
            show_404();
        }
    }

    public function user_deposits($user_id)
    {
        // User deposits by auction items
        //echo $user_id;
        $this->output->enable_profiler(TRUE);
        if(!empty($user_id)){
            
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            
            $today = date('Y-m-d H:i:s');
            $this->db->where_in('access_type', ['online', 'closed']);
            $auctions = $this->db->get_where('auctions', [
                'status' => 'active',
                'start_time <=' => $today,
                'expiry_time >=' => $today
            ])->result_array();

            $this->db->select('auction_item_deposits.*, auctions.title as auction_title, item.name as item_name, auction_item_deposits.deposit as deposit, auction_item_deposits.created_on as created_on');
            $this->db->from('auction_item_deposits');
            $this->db->join('auctions', 'auctions.id = auction_item_deposits.auction_id', 'left');
            $this->db->join('item', 'item.id = auction_item_deposits.item_id', 'left');
            $this->db->where('auction_item_deposits.deleted', 'no');
            $this->db->where('auction_item_deposits.account', 'DR');
            $this->db->where('auction_item_deposits.status !=', 'active');
            $this->db->where('auction_item_deposits.user_id', $user_id);
            $history = $this->db->get();
            $history = $history->result_array();

            $this->template->load_admin('users/auction_item_deposit', [
                'user' => $user,
                'auctions' => $auctions,
                'current_page_customers' => 'current-page',
                'history' => $history
            ]);
        }else{
            show_404();
        }
    }

    public function get_ai_list()
    {
        $data = $this->input->post();
        if($data){
            
            $user_id = $data['user_id'];
            unset($data['user_id']);
            
            if(isset($data['item_id'])){
                $item_id = $data['item_id'];
                unset($data['item_id']);
            }else{
                $item_id = '';
            }

            $item_array = array();
            $item_ids = $this->db->select('item_id')->get_where('auction_item_deposits',['user_id' => $user_id])->result_array();
            foreach ($item_ids as $key => $value) {
                array_push($item_array, $value['item_id']);
            }
            // print_r($item_array);die();
            $auction_id = $data['id'];
            $this->db->select('auction_items.*, item.name as item_name');
                $this->db->from('auction_items');
                $this->db->join('item','auction_items.item_id = item.id', 'left');
                $this->db->where([
                    'auction_items.auction_id' => $auction_id,
                    'auction_items.status' => 'active',
                    'auction_items.security' => 'yes',
                    'auction_items.sold_status' => 'not',
                    'auction_items.deposit !=' => null
                ]);
                if (!empty($item_array)) {
                    $this->db->where_not_in('auction_items.item_id', $item_array);
                }
                $auction_items = $this->db->get();

            //echo $this->db->last_query();

            if($auction_items->num_rows() > 0){
                $auction_items = $auction_items->result_array();

                $options = '<option value="">Choose</option>';
                foreach ($auction_items as $key => $item) {
                    $selected = (!empty($item_id) && $item_id == $item['id']) ? 'selected' : ''; 
                // print_r($selected);die();
                    $item_name = json_decode($item['item_name']);
                    $options .= '<option '.$selected.' value="'.$item['id'].'">'.$item_name->english.'</option>';
                }

                echo json_encode(['status' => true, 'data' => $options]);
            }else{
                echo json_encode(['status' => false]);
            }
        }
    }

    public function get_ai_deposit()
    {
        $data = $this->input->post();
        if($data){
            //return print_r($data);
            $auction_item_id = $data['id'];
            $auction_item = $this->db->get_where('auction_items', [
                'id' => $auction_item_id,
                'status' => 'active',
                'security' => 'yes',
                'deposit !=' => null
            ])->row_array();

            echo json_encode(['status' => true, 'deposit' => $auction_item['deposit'], 'item_id' => $auction_item['item_id']]);
        }
    }

    public function remove_auction_item_deposit()
    {
        $id = $this->input->post('id');
        $table = $this->input->post('obj');
        if(!empty($id)){
            $result = $this->db->update($table, ['deleted' => 'yes'], ['id' => $id]);
            if ($result) {
                echo $return = '{"type":"success","msg":"Action Succeded"}';
            } else {
                echo $return = '{"type":"error","msg":"Something went wrong."}';
            }
        }else{
            echo $return = '{"type":"error","msg":"Something went wrong."}';
            // show_404();
        }
    }

    public function add_auction_item_deposit()
    {
        $data = $this->input->post();
        if($data){
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = $this->loginUser->id;
            $data['status'] = 'approved';

            if($data['deposit_mode'] == 'cheque'){
                $cheque_detail = [
                    'cheque_date' => $data['cheque_date'],
                    'cheque_title' => $data['cheque_title'],
                    'cheque_type' => $data['cheque_type'],
                    'cheque_number' => $data['cheque_number'],
                    'account_title' => $data['account_title'],
                    'account_number' => $data['account_number'],
                    'bank_name' => $data['bank_name']
                ];

                unset($data['cheque_date']);
                unset($data['cheque_title']);
                unset($data['cheque_type']);
                unset($data['cheque_number']);
                unset($data['account_title']);
                unset($data['account_number']);
                unset($data['bank_name']);

                $data['deposit_detail'] = json_encode($cheque_detail);
            }

            if($data['deposit_mode'] == 'manual_deposit'){
                $deposit_detail = [
                    'deposit_date' => $data['deposit_date'],
                    'bank_name' => $data['bank_name'],
                    'bank_branch' => $data['bank_branch'],
                    'deposit_type' => $data['deposit_type'],
                    'deposit_currency' => $data['deposit_currency'],
                    'account_title' => $data['account_title'],
                    'account_number' => $data['account_number'],
                    'depositor_name' => $data['depositor_name'],
                    'depositor_id' => $data['depositor_id'],
                    'depositor_phone' => $data['depositor_phone'],
                    'deposit_txn_id' => $data['deposit_txn_id']
                ];

                unset($data['deposit_date']);
                unset($data['bank_name']);
                unset($data['bank_branch']);
                unset($data['deposit_type']);
                unset($data['deposit_currency']);
                unset($data['account_title']);
                unset($data['account_number']);
                unset($data['depositor_name']);
                unset($data['depositor_id']);
                unset($data['depositor_phone']);
                unset($data['deposit_txn_id']);

                $data['deposit_detail'] = json_encode($deposit_detail);
            }

            
            //print_r($data);die();
            $result = $this->db->insert('auction_item_deposits', $data);
            if($result){
                $this->session->set_flashdata('success', 'Deposit amount has been successfully added.');
            }else{
                $this->session->set_flashdata('error', 'Deposit amount has been failed to add.');
            }
            redirect(base_url('user-deposits/'.$data['user_id']));
        }
    }

    public function get_deposit_mode_fields()
    {
        $data = $this->input->post();
        if($data){
            //print_r($data);
            if($data['mode'] == 'cheque'){
                $fields = $this->load->view('users/deposit_mode_cheque',[],true);
                echo json_encode(['status' => true, 'fields' => $fields]);
            }

            if($data['mode'] == 'manual_deposit'){
                $fields = $this->load->view('users/deposit_mode_manual',[],true);
                echo json_encode(['status' => true, 'fields' => $fields]);
            }

            if($data['mode'] == 'cash'){
                echo json_encode(['status' => true, 'fields' => '']);
            }

        }else{
            echo json_encode(['status' => false]);
        }
    }


    public function save_user_file_documents()
    {

        if( ! empty($_FILES['documents']['name'])){

            // print_r($_FILES);
            // exit;
            $user_id = $_POST['user_id'];
            $documents_type_id = $_POST['field'];
            // print_r($user_id);
            // print_r($documents_type_id);
            
            $itemsIds_array = array();
            $ids_concate = '';
            $doc_result_array = $this->items_model->get_customersDocs($user_id,$documents_type_id);
            if(isset($doc_result_array) && !empty($doc_result_array))
            {
                $docIds_array = explode(',' ,$doc_result_array[0]['file_id']);
                if(!empty($docIds_array) && !empty($doc_result_array[0]['file_id']))
                {
                    $ids_concate = $doc_result_array[0]['file_id'].",";
                }
            }

            // make path
            $path = './uploads/users_documents/';
            if ( ! is_dir($path.$user_id)) {
                mkdir($path.$user_id, 0777, TRUE);
            }
            $path = $path.$user_id.'/'; 
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls|png|jpg|jpeg|icon';
            $filesCount = count($_FILES['documents']['name']);
            for($i = 0; $i < $filesCount; $i++){
                $_FILES['file']['name']     = $_FILES['documents']['name'][$i];
                $_FILES['file']['type']     = $_FILES['documents']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['documents']['tmp_name'][$i];
                $_FILES['file']['error']    = $_FILES['documents']['error'][$i];
                $_FILES['file']['size']     = $_FILES['documents']['size'][$i];
                // print_r($value);
                $uploaded_file_ids = $this->files_model->upload('file', $config); // upload and save to database

                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $insert_data = [
                    'user_id' => $user_id,
                    'document_type_id' => $documents_type_id,
                    'file_id' => $ids_concate.$uploaded_file_ids
                ];
                $saveDoc = $this->db->get_where('user_documents',['user_Id'=>$user_id,'document_type_id'=>$documents_type_id])->result_array();
                if (!empty($saveDoc)) 
                {
                    echo $result = $this->db->update('user_documents',$insert_data,['user_Id' =>$user_id,'document_type_id' =>$documents_type_id]) ? 'true' : 'false';
                } else{
                    echo $result = ($this->users_model->insert_user_documents($insert_data)) ? 'true' : 'false';
                }
            }
            if($result == 'true')
            {
                $this->session->set_flashdata('msg', 'User documents added successfully.');
            }
        }
    }
    public function delete_user_documents($userId)
    {
        $file_id = $this->input->post("id");
        $table = $this->input->post("obj");
        if ($table == "user_documents") 
        {
            $path = FCPATH .  "uploads/users_documents/".$userId."/";
            $result = $this->files_model->delete_by_id($file_id,$path);
            $result = $this->db->delete($table, ['file_id' => $file_id]);
        }
        //do not change below code
         if ($result) {
            echo $return = '{"type":"success","msg":"success"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }

        // $attach_name = $this->input->post('file_to_be_deleted');

        // $file_array = $this->files_model->get_file_byName($attach_name);
        // if(isset($file_array) && !empty($file_array))
        // {
        //     $result_array = $this->users_model->get_user_byid($userId);
        //     if(isset($result_array) && !empty($result_array))
        //     {
        //         $itemsIds_array = explode(',' ,$result_array[0]['documents']);

        //         if(!empty($itemsIds_array))
        //         {
        //             $str = $result_array[0]['documents'];
        //         }
        //     }
        //     $updated_str = $this->removeItemString($str, $file_array[0]['id']);
        //     $update = [
        //             'documents' => $updated_str,
        //             'updated_by' => $this->loginUser->id
        //         ];
        //     $update_item_row = ($this->users_model->update_user($userId,$update)) ? 'true' : 'false';
        // }
        // $path = FCPATH .  "uploads/users_documents/".$userId."/";
        // $result = $this->files_model->delete_by_name($attach_name,$path);
        // if($result)
        // {
        //     echo 'success';    
        // }
        
    }
     public function approve_user()
    {
        $status_id = array('status' => $_GET['status_id']);
        $user_id = $_GET['user_id'];


        // print_r($status_id);
        // print_r($user_id);die();
        $return = $this->users_model->chahge_status($status_id,$user_id);

        $data = $this->users_model->users_listing($user_id);
        if($data[0]['status'] == 1){
        $this->session->set_flashdata('msg', 'User Approved Successfully');
        }
        if($data[0]['status'] == 0){
            $this->session->set_flashdata('msg', 'User InActive Successfully');
        }
        redirect(base_url().'users/');

    }
   
   public function manually_deposite()
   {
       $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Manually Add Deposite';
        $data['current_page_users'] = 'current-page';
        $data['deposite_amount'] =  array();
        $id = $this->uri->segment(3);
        $data['userid'] = $id;
        $data['formaction_path'] = 'save_manually_deposite';
        $id = $this->loginUser->id;
        $this->template->load_admin('users/manually_deposite', $data);
   }

   public function save_manually_deposite()
   {
   if ($this->input->post()) {
        $items_documents_data = $this->session->userdata('items_documents');

        // $all_documents_array = array();
        // $all_documents_array = array_merge($images_data,$items_documents_data);

        $users_attachment = array();

            $rules = array(
                array(
                    'field' => 'deposite_amount',
                    'label' => 'Deposite Amount',
                    'rules' => 'trim|required',
                ),
               
            );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                exit();
            } 
            // $documents = $this->uploadFiles($_FILES);
            if($this->input->post())
                
                {
                $data = array(
                    'amount' => $this->input->post('deposite_amount'),
                   'user_id' => $this->input->post('id'),
                    'transaction_time' => date('Y-m-d H:i:s'),
                    'created_on' =>date('Y-m-d H:i:s'),
                    'created_by' => $this->loginUser->id,
                );
                $data['transaction_id'] = mt_rand(100000,999999);
                $result = $this->users_model->insert_manually_deposite($data);
                if (!empty($result)) {
                    $user_deposite_data  = array(
                        'total_deposite' =>$this->input->post('deposite_amount')
                      );
                        $id =  $this->input->post('id');
                    $this->users_model->update_user_deposite($id,$user_deposite_data);

                    $this->session->set_flashdata('msg', 'Deposite Added Successfully');
                    $redict_users_list = "success";
                    echo json_encode(array('msg'=>$redict_users_list,'mid'=>$result));
                    exit();
                }
            }
        }
    }
    public function user_security_history()
        {
        $data = array();
        if($this->loginUser->role == 4 && $this->loginUser->type == 'buyer'){
            $id = $this->loginUser->id;
        }else{
            $id = $this->uri->segment(3);
        }
        $data['small_title'] = 'Security History';
        $data['current_page_security'] = 'current-page';
        $data['formaction_path'] = 'filter_for_deposite';
        if(!empty($id)){
        $data['security_hisotry'] = $this->users_model->get_security_detail($id);
        }
        else{
            $data['security_hisotry'] = $this->users_model->get_security_detail();

             }
 
        $data['transaction_detail'] = $this->users_model->get_transactiom_detail($id);

        // print_r($data['transaction_detail']); die('aaaaaaaaaa');
        $this->template->load_admin('users/security_acount_detail_list', $data);
    }

    public function get_transaction_details()
    {
        $data = array();
        $q =  array();
        $data['current_date'] = date('Y/m/d');
        $deposit_id = $this->input->post('id');
        $user_id = $this->loginUser->id;
        $data['transacton_detail'] = $this->users_model->transaction_detail($deposit_id);
        $deposit_user =  $data['transacton_detail'][0]['user_id']; 
        $data['admin_detail'] = $this->users_model->users_listing($user_id);
         $q = $this->users_model->users_listing($deposit_user);
        if($q)
        {
         $data['user_transaction'] = $q;
        }
        $data_view = $this->load->view('users/print_transaction', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }
    public function username_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " username like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = 'AND status = 1 AND (role = 2 OR role = 3 OR role = 5 OR role = 8) ';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`username`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

    public function customerusername_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " username like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = 'AND status = 1 AND role = 4 ';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`username`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

    public function customerCompanyNameApi()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " company_name like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND status = 1 AND role = 4 ';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`company_name`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }


        public function email_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " email like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = 'AND status = 1 AND role = 2 OR role = 3 OR role = 5 ';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`email`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }
    public function customeremail_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " email like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = 'AND status = 1 AND role = 4 ';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`email`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

     public function usercode_api()
    {
        // make dynamicn query
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " code like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = 'AND role = 2 OR role = 3 OR role = 5';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`code`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );

        // print_r($query);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }
    
    public function customercode_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " code like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = 'AND role = 4';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`code`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );

        // print_r($query);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    } 
    public function usermobile_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " mobile like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = 'AND role = 2 OR role = 3 OR role = 5 OR role = 8';
        if (!empty($sql)){
            $query .= '(' . implode(' OR ', $sql).')'.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`mobile`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );

        // print_r($query);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }
    public function customermobile_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " mobile like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND status = 1 AND role = 4 ';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        // print_r($query);die('aaaa');
        $columns = "id, CONCAT(`mobile`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );

        // print_r($query);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }


    public function application_users_list()
    {
        $posted_data = $this->input->post();
        ## Read value
         $draw = $posted_data['draw'];
         $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
         $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
         // print_r($posted_data);die('aaa');
         $columnIndex = $posted_data['order'][0]['column']; // Column index
         // print_r($columnIndex);die('jkjk');
         $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
         // $columnName = 'id';
         $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
         $searchValue = $posted_data['search']['value']; // Search value
        // Custom search filter 
         $username = (isset($posted_data['username']))? $posted_data['username'] : '';
         $role = (isset($posted_data['role']))? $posted_data['role'] : '';
         $mobile = (isset($posted_data['mobile']))? $posted_data['mobile']: ''; 
         $email = (isset($posted_data['email']))? $posted_data['email']: ''; 
         $status = (isset($posted_data['status']))? $posted_data['status']: ''; 
         ## Search 
         // print_r($posted_data);die();
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
            $search_arr[] = " (users.username like '%".$searchValue."%' ) OR (users.email like '%".$searchValue."%' ) OR (users.status like '%".$searchValue."%' ) OR (users.mobile like '%".$searchValue."%' ) OR (users.created_on like '%".$searchValue."%' ) OR (users.updated_on like '%".$searchValue."%' )";
         }
         if($username != ''){
            // print_r($username);die();
            $username = trim($username);
            // $username = "'".implode("','",$username)."'";
            $search_arr[] = " users.username like '%".$username."%' ";
         }
         if($role != ''){
            // $code = "'".implode("','",$code)."'";
            $role = trim($role);
            $search_arr[] = " users.role = ".$role;
         }
         if($mobile != ''){
            // $mobile = "'".implode("','",$mobile)."'";
            $mobile = trim($mobile);
            $search_arr[] = " users.mobile like '%".$mobile."%'";
         }
          if($email != ''){
            // print_r($email);die();
            // $email = "'".implode("','",$email)."'";
            $email = trim($email);
            $search_arr[] = " users.email like '%".$email."%' ";
         }
         if($status != ''){
            $status = trim($status);
            $search_arr[] = " users.status like '%".$status."%' ";
         }

        if(isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $search_arr[] = " (DATE(users.created_on) between '".$start_date."' and '".$end_date."') ";
        }
         if(count($search_arr) > 0){
        
            $searchQuery = ' ('.implode(" AND ",$search_arr).') ';
         }

         // print_r($searchQuery);die();
        $role = $this->loginUser->role;
        $total = $this->items_model->get_items();

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        if($role == 1){
            $app_user = array(2,3,5,7,8,9,10);
            $this->db->where_in('role',$app_user);
        }
        elseif ($role == 2) {
            $app_user = array(3);
            $this->db->where_in('role',$app_user);
        }
        $records = $this->db->get('users')->result();
        $totalRecords = $records[0]->allcount; // total records
        // print_r($this->db->last_query());die();
         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
            if($role == 1){
            $app_user = array(2,3,5,7,8,9,10);
            $this->db->where_in('role',$app_user);
        }
        elseif ($role == 2) {
            $app_user = array(3);
            $this->db->where_in('role',$app_user);
        }
        $records = $this->db->get('users')->result();
        $totalRecordwithFilter = $records[0]->allcount; // count filtered record 

         ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        if($role == 1){
            $app_user = array(2,3,5,7,8,9,10);
            $this->db->where_in('role',$app_user);
        }
        elseif ($role == 2) {
            $app_user = array(3);
            $this->db->where_in('role',$app_user);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowperpage != '-1') {
            $this->db->limit($rowperpage, $start);
        }
        $records = $this->db->get('users')->result(); // all filtered data 
        $data = array();
        foreach ($records as $record) {
        $action = '';
        if($record->status == 0) { 
            $status_btn ='<a class="btn btn-success" href="'.base_url().'users/chage_status?user_id='.$record->id.'&status_id=1">Active</a>';
        }else{
            $status_btn ='<a class="btn btn-warning" href="'.base_url().'users/chage_status?user_id='.$record->id.'&status_id=0">Inactive</a>';
        }
        $action .= '<a href="'.base_url().'users/update_user/'.$record->id.'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
        // $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="users" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record->id.'" data-url="'.base_url().'users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';
        if($record->role == 1){ $record_role = " Admin"; } 
        if($record->role == 2) { $record_role = " Sales Manager"; } 
        if($record->role == 3) { $record_role = " Sales Person";}
        if($record->role == 5) { $record_role = " Operational Department";}
        if($record->role == 6) { $record_role = " Tasker";}
        if($record->role == 7) { $record_role = " Live Auction Controller";}
        if($record->role == 8) { $record_role = " Cashier";}
        if($record->role == 9) { $record_role = " Appraiser";}
        if($record->role == 10) { $record_role = " Marketing";}

       $data[] = array( 
        // "id" => $record->id, 
         "username"=> $record->username,
         "code"=> $record->id,
         "role"=> $record_role,
         "mobile"=> $record->mobile,
         "email"=> $record->email,
         "status"=> $record->status,
         "created_on"=> date('Y-m-d',strtotime($record->created_on)),
         "updated_on"=> date('Y-m-d',strtotime($record->updated_on)),
         "status"=> $status_btn,
         "action"=> $action
       );
     }  
                 ## Response
     $response = array(
       "draw" => intval($draw),
       "iTotalRecords" => $totalRecords,
       "iTotalDisplayRecords" => $totalRecordwithFilter,
       "aaData" => $data
     );
        echo json_encode($response); 
    }

    public function confirm_payment($id)
    {   
        $data = $this->db->get_where('bank_deposit_slip', ['id' => $id])->row_array();
        $posted_data['user_id'] = $data['user_id'];
        $posted_data['deposit_type'] = 'permanent';
        $posted_data['payment_type'] = 'bank_transfer';
        $posted_data['amount'] = $data['deposit_amount'];
        $posted_data['created_on'] = date('Y-m-d H:i:s');
        $posted_data['created_by'] = $this->loginUser->id;
        $posted_data['status'] = 'approved';
        $result = $this->db->insert('auction_deposit', $posted_data);
        // print_r($result);die();
        $update = $this->db->update('bank_deposit_slip', ['updated_on' => date('Y-m-d H:i:s'),'status' => '1'], ['id' => $id]);
        if ($update) {
            $this->session->set_flashdata('success', 'Payment Confirmed Successfully!');
            redirect(base_url().'transaction/view_slip/'.$id); // get response for select2 JQuery
        }else{
            $this->session->set_flashdata('error', 'Payment has been failed to update.');
            redirect(base_url().'transaction/view_slip/'.$id); // get response for select2 JQuery
        }
    }

    public function reject_payment($id)
    {   
        $data = $this->db->get_where('bank_deposit_slip', ['id' => $id])->row_array();
        $posted_data['user_id'] = $data['user_id'];
        $posted_data['deposit_type'] = 'permanent';
        $posted_data['payment_type'] = 'bank_transfer';
        $posted_data['amount'] = $data['deposit_amount'];
        $posted_data['created_on'] = date('Y-m-d H:i:s');
        $posted_data['status'] = 'rejected';
        $result = $this->db->insert('auction_deposit', $posted_data);
        // print_r($result);die();
        $update = $this->db->update('bank_deposit_slip', ['updated_on' => date('Y-m-d H:i:s'),'status' => '2'], ['id' => $id]);
        if ($update) {
            $this->session->set_flashdata('success', 'Payment Rejected Successfully!');
            redirect(base_url().'transaction/view_slip/'.$id); // get response for select2 JQuery
        }else{
            $this->session->set_flashdata('error', 'Payment has been failed to Reject.');
            redirect(base_url().'transaction/view_slip/'.$id); // get response for select2 JQuery
        }
    }

    public function user_deposits_general($user_id)
    {
        $this->output->enable_profiler(TRUE);
        if(!empty($user_id)){
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            $currentBalance = $this->customer_model->user_balance($user_id);

            $history = $this->db->get_where('auction_deposit', [
                'user_id' => $user_id,
                'deleted' => 'no',
                'status' => 'approved',
                'deposit_type' => 'permanent'
            ])->result_array();

            $this->template->load_admin('users/deposits', [
                'user' => $user,
                'current_page_customers' => 'current-page',
                'history' => $history,
                'currentBalance' => $currentBalance
            ]);
        }else{
            show_404();
        }
    }

    public function user_deposits_general_form($user_id)
    {
        if(!empty($user_id)){
            // $data['current_page_customers'] = 'current-page';
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();

            $this->template->load_admin('users/deposits_form', [
                'user' => $user,
                
            ]);
        }else{
            show_404();
        }   
    }

    public function user_deposits_return_form($user_id)
    {
        if(!empty($user_id)){
            // $data['current_page_customers'] = 'current-page';
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            $currentBalance = $this->customer_model->user_balance($user_id);
            $this->template->load_admin('users/deposit_return_form', [
                'user' => $user,
                'currentBalance' => $currentBalance,
                
            ]);
        }else{
            show_404();
        }   
    }

    public function add_deposit()
    {
        $data = $this->input->post();
        if($data){
            //print_r($data);

            if($data['deposit_mode'] == 'cheque'){
                $detail = [
                    'cheque_date' => $data['cheque_date'],
                    'cheque_title' => $data['cheque_title'],
                    'cheque_type' => $data['cheque_type'],
                    'cheque_number' => $data['cheque_number'],
                    'account_title' => $data['account_title'],
                    'account_number' => $data['account_number'],
                    'bank_name' => $data['bank_name']
                ];

                unset($data['cheque_date']);
                unset($data['cheque_title']);
                unset($data['cheque_type']);
                unset($data['cheque_number']);
                unset($data['account_title']);
                unset($data['account_number']);
                unset($data['bank_name']);
            }

            if($data['deposit_mode'] == 'manual_deposit'){
                $detail = [
                    'deposit_date' => $data['deposit_date'],
                    'bank_name' => $data['bank_name'],
                    'bank_branch' => $data['bank_branch'],
                    'deposit_type' => $data['deposit_type'],
                    'deposit_currency' => $data['deposit_currency'],
                    'account_title' => $data['account_title'],
                    'account_number' => $data['account_number'],
                    'depositor_name' => $data['depositor_name'],
                    'depositor_id' => $data['depositor_id'],
                    'depositor_phone' => $data['depositor_phone'],
                    'deposit_txn_id' => $data['deposit_txn_id']
                ];

                unset($data['deposit_date']);
                unset($data['bank_name']);
                unset($data['bank_branch']);
                unset($data['deposit_type']);
                unset($data['deposit_currency']);
                unset($data['account_title']);
                unset($data['account_number']);
                unset($data['depositor_name']);
                unset($data['depositor_id']);
                unset($data['depositor_phone']);
                unset($data['deposit_txn_id']);
            }

            if($data['deposit_mode'] == 'cash'){
                $detail = [];
            }

            $deposit_detail = json_encode($detail);

            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = $this->loginUser->id;
            $data['status'] = 1;

            $deposit = [
                'user_id' => $data['user_id'],
                'amount' => $data['deposit'],
                'payment_type' => $data['deposit_mode'],
                'deposit_type' => 'permanent',
                'detail' => $deposit_detail,
                'created_on' => date('Y-m-d H:i:s'),
                'status' => 'approved',
                'created_by' => $this->loginUser->id
            ];

            $result = $this->db->insert('auction_deposit', $deposit);

            if($result){
                $this->session->set_flashdata('success', 'Deposit amount has been successfully added.');
            }else{
                $this->session->set_flashdata('error', 'Deposit amount has been failed to add.');
            }
            redirect(base_url('user-deposits-general/'.$data['user_id']));
        }
    }

    public function return_deposit()
    {
        $data = $this->input->post();
        if($data){
            $query = 'Select bid.buyer_id, sum(bid.bid_amount) as total_bid
                from bid 
                inner join  ( 
                    Select max(bid.id) as LatestId, item_id  
                    from bid 
                    Group by bid.item_id  
                ) SubMax on bid.id = SubMax.LatestId and bid.item_id = SubMax.item_id 
                inner join auction_items on bid.item_id = auction_items.item_id and bid.auction_id = auction_items.auction_id
                left join invoices on bid.item_id = invoices.item_id and bid.auction_id = invoices.auction_id
                WHERE bid.buyer_id = '.$data["user_id"].' AND auction_items.sold_status IN("not", "sold","approval") and invoices.id IS NULL
                GROUP BY bid.buyer_id';
            $heighest_bids = $this->db->query($query)->row_array();
            $currentBalance = $this->customer_model->user_balance($data["user_id"]);
            $totalBalanceRequiredToRefund = (int)($data['deposit']*10) + (int)$heighest_bids['total_bid'];
            $currentBidAmount = $currentBalance['amount']*10;
            // print_r($totalBalanceRequiredToRefund);
            // echo '<br>';
            // print_r($currentBalance['amount']*10);die();
            if ($totalBalanceRequiredToRefund > $currentBidAmount) {
                $refundableBalance = ((int)$currentBidAmount - (int)$heighest_bids['total_bid'])/10;
                $refundableBalance = ($refundableBalance > 0) ? $refundableBalance : 0;
                // print_r($refundableBalance);die();
                $this->session->set_flashdata('error', 'Deposit amount has been failed to return. Total refund able balance is '.$refundableBalance);
                redirect(base_url('user-deposits-return-form/'.$data['user_id']));
            } else {

                $detail = [];

                $deposit_detail = json_encode($detail);

                $data['created_on'] = date('Y-m-d H:i:s');
                $data['created_by'] = $this->loginUser->id;
                $data['status'] = 1;

                $deposit = [
                    'user_id' => $data['user_id'],
                    'amount' => $data['deposit'],
                    'payment_type' => 'cash',
                    'deposit_type' => 'permanent',
                    'detail' => $deposit_detail,
                    'account' => 'CR',
                    'created_on' => date('Y-m-d H:i:s'),
                    'status' => 'approved',
                    'created_by' => $this->loginUser->id
                ];

                $result = $this->db->insert('auction_deposit', $deposit);

                if($result){
                    $this->session->set_flashdata('success', 'Deposit amount has been successfully returned.');
                }else{
                    $this->session->set_flashdata('error', 'Deposit amount has been failed to return.');
                }
                redirect(base_url('user-deposits-general/'.$data['user_id']));
            }
        }
    }

    public function remove_deposit()
    {
        $deposit_id = $this->input->post("id");
        $table = $this->input->post("obj");
        if(!empty($deposit_id)){
            $result = $this->db->update($table, ['deleted' => 'yes'], ['id' => $deposit_id]);
            // if($result){
            //     $this->session->set_flashdata('success', 'Deposit has been removed successfully!');
            // }else{
            //     $this->session->set_flashdata('error', 'Deposit has been failed to remove.');
            // }
            if ($result) {
            echo $return = '{"type":"success","msg":"Action Succeded"}';
            } else {
                echo $return = '{"type":"error","msg":"Something went wrong."}';
            }
        }else{
            echo $return = '{"type":"error","msg":"Something went wrong."}';
            show_404();
        }
    }

    public function make_payment($user_id='',$sold_item_id='')
    {
        if(!empty($user_id)){
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();

            $sold_item = $this->db->get_where('sold_items', ['id' => $sold_item_id])->row_array();

            $this->template->load_admin('users/make_payment',['user' => $user, 'sold_item' => $sold_item]);
        }else{
            show_404();
        }
    }

    public function getNumber($n) { 
        $characters = '0123456789'; 
        $randomString = ''; 
      
        for ($i = 0; $i < $n; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
        return $randomString; 
    }

}