<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . '/libraries/REST_Controller.php';

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); // cache for 1 day
}
// Access-Control headers are received during OPTIONjobS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}

class Api extends MX_Controller {
  public function __construct() { 
    parent::__construct();
    // Allow from any origin

    //if (isset($_SERVER['HTTP_ORIGIN'])) {
      // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
      // you want to allow, and if so:
      /*header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      header("Access-Control-Allow-Origin: http://localhost:8100 ");
      header("Access-Control-Allow-Origin: * ");
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Max-Age: 3600'); */   // cache for 1 day
    //}

    // Access-Control headers are received during OPTIONS requests

    /*if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }*/

    //load user model
    $this->load->model('Api_model','api_model');
    $this->load->model('files/Files_model', 'files_model');
    $this->load->model('Home/Home_model','home_model');
    $this->load->model('items/Items_model', 'items_model');
    $this->load->model('email/Email_model', 'et');
    $this->load->model('auction/Online_auction_model', 'oam');
    $this->load->library('jwt');
    $this->load->library('TestUnifonic');
    $this->load->helper('jwt_helper');
    $this->load->helper('general_helper');
    $this->load->library('session');

    //$_POST = json_decode(file_get_contents("php://input"), true);
    header("Access-Control-Allow-origin: *");
    header("Content-Type: application/json");
    header("Cache-Control: no-cache");

  }
    
    public function index()
    { 
      $this->load->view('Welcome_message');
        // redirect('user_insert');
    }

    public function save_user()
    {
      $data = array(
        'first_name' => $this->input->get('first_name'),
        'last_name' => $this->input->get('last_name'),
        'email' => $this->input->get('email'),
        'phone' => $this->input->get('phone'),
      );
      if(!empty($data)){
       $r= $this->api_model->insert($data);
       echo "successful"; 
      }else{
        echo "put some data";
      }

    }

    public function login_user_list()
    { 
      $user_array=$this->api_model->login_user_list(); 
      // $user_data=json_encode( $user_array);
      if (!empty($user_array)) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'success','result'=> $user_array));
      }else{
        // http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'No Record Found'));
      }
      
    }

    public function loginUser()
    {
      $data = json_decode(file_get_contents("php://input"));
      // print_r($data);
      if (!empty($data->username) && !empty($data->password)) {

        $username = $data->username;
        $dataa=$data->password;
        $password = hash("sha256",$data->password);
        // $password = $data->password;
        $result = $this->api_model->login_check($username,$password);
        // verify email is valid or not
        if (!empty($result)) {
          
          $status = $result[0]->status;
          // check if user is active and check if paid with status is 1
          if ($status == '1') {
            // Generate JWT Token with payload 
            // payload have expire time information with user role id and user id 
            
            $paylod = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() + (120*60*24),
            'user_id' => $result[0]->id,
            'email' => $result[0]->email,
            'mobile' => $result[0]->mobile,
            'user_role_id' => $result[0]->role,
            ];
            $token = Jwt::encode($paylod, SECRETE_KEY);
            $user_list = $this->api_model->get_login_user($result[0]->id);
            // $_SESSION['loggedin_user'] = $loginuser;
            $data = array(
              'user_id' => $result[0]->id,
              'token' => $token,
              'data' => $user_list
            );
            http_response_code(200);
            echo json_encode(array('status'=>true,'message'=>'Logged in successfully.','result'=> $data,'password'=>$dataa)); 
          }
        }else{
          http_response_code(404);
          echo json_encode(array('status'=>false,'message'=>'Unauthorized user'));
          // echo "error";   
        }
      }else{

          echo json_encode(array('status'=>false,'message'=>'Data is missing'));
      }  
    }


    // public function auctionCategories()
    // {
      // $data=$this->api_model->get_online_auctions(); 
    //   foreach ($data as $key => $value) {
    //     $count= $this->db->select('*')->from('auction_items')->where('auction_id',$value['id'])->where('bid_end_time >',date('Y-m-d H:i'))->where('bid_start_time <',date('Y-m-d H:i'))->where('sold_status', 'not')->get()->result_array();

    //     // ,["FIND_IN_SET(".'online'." => auction_type)"])->result_array();
    //     $count = count($count);
    //     $data[$key]['item_count'] =  $count;
    //     $title = json_decode($value['title']);
    //     $data[$key]['title'] =  $title;
    //   }

    //   if (!empty($data)) {
    //     http_response_code(200);    
    //     echo json_encode(array('status'=>true,'message'=>'success','result'=> $data));
    //   }else{
    //     http_response_code(400);
    //     echo json_encode(array('status'=>false,'result'=>''));
    //   }
    // }

    public function upcomingAuctions()
    {    
      $date=date('Y-m-d');
      $datee['time'] =date('Y-m-d H:i:s', time());
      $data=$this->api_model->upcoming_auctions($date,'time');
      if (!empty($data)) {

        foreach ($data as $key => $value) {
          $title = json_decode($value['title']);
          $data[$key]['title'] =  $title;
        }
        http_response_code(200);    
        echo json_encode(array('status'=>true,'message'=>'success','result'=> $data));
      }
      // // $result=json_encode($data);
      // if (!empty($data)) {
             
      // }
      else
      {
        http_response_code(200);
        echo json_encode(array('status'=>true,'result'=>''));
      }
    }


    public function userAccount()
    {
      $userId = validateToken();  
      // $info=$this->api_model->user_account($userId);
      $info = $this->db->get_where('crm_buyer_detail', ['id' => $userId])->row_array();
      $infoo = $this->db->get_where('live_auction_customers', ['id' => $userId])->row_array();
      $user_total_deposit= $this->api_model->user_balance($userId);

      $info['amount']= $user_total_deposit['amount'];
      

      $query = $this->db->query('SELECT COUNT(DISTINCT(item_id)) as count FROM `bid` WHERE user_id = '.$userId.'');
      $bid_count = $query->row_array();
      if (!empty($info)) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'success','amount'=> $info['amount'],'count'=> $bid_count['count']));
      }
      elseif (empty($info))
      {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'success','amount'=> 0,'count'=> 0 ));
      }
      else
      {
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'Failed'));
      }
    }

      
    public function user_detail()
    {
      $userId = validateToken();   
      // $info=$this->api_model->user_account($userId);
      $info = $this->db->get_where('users', ['id' => $userId])->row_array();
      if (!empty($info)) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'success','result'=> $info));
      }
      else
      {
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'Failed'));
      }
    }


    public function product_detail()
    {
       $data= $this->api_model->product_detail();
       if (!empty($data)) {
          http_response_code(200);
          echo json_encode(array('status'=>true,'message'=>'success','result'=> $data));
       }
       else
       {
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'No Record Found'));
       }
    }

    public function forgotPassword()
    {
      $data = json_decode(file_get_contents("php://input"));
      if (!empty($data->email)) {
        $email=$data->email;
        $query=$this->api_model->check_email($email);
        if(!empty($query)) {
          $email_code=$this->getNumber(4);
          $this->db->update('users',['reset_password_code'=>$email_code],['email'=>$email]);
          $link_activate = base_url() . 'api/verify_forgot_password/' . urlencode(base64_encode($email_code));
          $vars = [
          '{link_activation}' => $link_activate,
          '{btn_link}' => $link_activate,
          ];
          // $new_password = md5($this->RandomStringGenerator(10));
          // $email= $this->api_model->forgot_password_update($data->email,$new_password);
          $this->et->email_template_forgot($email,$vars,true,'reset_password');
          echo json_encode(array('status'=>true,'message'=>'Reset link is sent to your email'));
        } else {
          http_response_code(400);
          echo json_encode(array('status'=>false,'message'=>'Email not exists.'));
        }
      }else{
        http_response_code(400);
          echo json_encode(array('status'=>false,'message'=>'Invalid email.'));
      }
    }


    public function verify_forgot_password($email_code)
    {
      $email_codes = base64_decode(urldecode($email_code));
      $user = $this->db->get_where('users', ['reset_password_code' => $email_codes])->row_array();
      if (!empty($user)) {
        $this->template->load_user('api/forgot-password'); 
      }else{
        $data = array();
        // echo json_encode(array('status'=>true,'message'=>'email not verified'));
        $this->session->set_flashdata('error', 'You are not allowed to Access URL!');
        $data['active_login'] = 'active';
        $data['login_show'] = ' show';
        $this->template->load_user('home/login',$data);
        // redirect(base_url('home/login'));
      }     
    }

    public function updatePassword($encoded_id)
    {

      if ($this->input->post()) 
      {
        $this->load->library('form_validation');
        $rules = array(
          array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[5]'),
          array(
            'field' => 'email_code',
            'label' => 'Email not valid',
            'rules' => 'trim|required'),
          array(
            'field' => 'cpassword',
            'label' => 'Confirm password',
            'rules' => 'trim|min_length[5]|matches[password]')
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
          echo json_encode(array('error' => true, 'message' => validation_errors()));
        }else{
          $code = base64_decode(urldecode($this->input->post('email_code')));
          $password = $this->input->post('password');
          $user = $this->db->get_where('users',['reset_password_code'=>$code])->row_array();

          if(!empty($user)){
            $this->db->update('users', ['password'=>hash("sha256",$password),'reset_password_code'=>0],['id'=>$user['id']]);
            $this->session->set_flashdata('success', 'Password has been updated');
            echo json_encode(array('error' => false, 'message' => $encoded_id.' Password change successfully'));

          }else{
            echo json_encode(array('error' => true, 'message' => ' Email not valid!'));
            
          }

        } 
      }else{
        echo json_encode(array('error' => true, 'message' => 'Data error'));
      }
    }

    public function register_User_email()
    {
       $email = 'it@armsgroup.ae';
      $user = $this->db->get_where('users',['id' => '446']);
       $user = $user->row_array();
       $user['email_verification_code'] = '1122';
      if (!empty($user)) {
        $link_activate = base_url() . 'home/verify_email/' .  $user['email_verification_code'];
        $vars = [
          '{username}' => $user['fname'],
          '{email}' => $user['email'],
          '{link_activation}' => $link_activate,
          '{btn_link}' => $link_activate,
          '{btn_text}' => 'Verify'
        ];
      }
      echo $send=$this->email_template($email,$vars,true,'register');
      print_r($send);die();
      exit();
    }

    public function registerUser()
    {
      $data_array = json_decode(file_get_contents("php://input"),true);

      $data = json_decode(file_get_contents("php://input"));
      $this->form_validation->set_data($data_array);

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
        // echo validation_errors();
        echo json_encode(array("status" => FALSE, "message" => validation_errors()));
        exit();
      } 
      if(!empty($data))
      {
        $number = preg_replace('/\s/', '', $data->mobile);   
        $check_number = $this->api_model->check_user_number($number);
        if($check_number == true)
        {   
          http_response_code(400);
          echo json_encode(array("status" => FALSE, "message" => 'Number already exist.'));
          exit();
        }
        $email = $data->email;
        $result = $this->api_model->check_email_user($email);
        if($result == true)
        {   
          http_response_code(400);
          echo json_encode(array("status" => FALSE, "message" => 'Email already exist.'));
          exit();
        }
        // $generateCode = rand(1000, 9999);
        $mobile_verification_code = getNumber(4);
        $email_verification_code = getNumber(4);
        
        //SMS verification process start
        $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
        $sms_response = $this->testunifonic->sendMessage($number, $sms);
        if(isset($sms_response->MessageID)){
          $sms_response = json_encode($sms_response);
          $data = array(
            'fname' => $data->fname,
            'lname' => $data->lname,
            'username' => $data->fname.' '.$data->lname,
            'email' => $data->email,
            'mobile' => $number,   
            'city' => $data->city,  
            'prefered_language' => $data->prefered_language,
            'dial_code' => $data->dial_code,   
            'role' => $data->role,
            'password' => hash("sha256",$data->password),
            'reg_type' => $data->reg_type,   
            'social' => $data->social,
            'code' =>  $mobile_verification_code,  
            'email_verification_code' => $email_verification_code,         
          );
          // print_r($data);die();
          $data['created_on'] = date('Y-m-d H:i:s');
          $data['updated_on'] = date('Y-m-d H:i:s');
          $result = $this->api_model->insert_user_details($data);

          $user = $this->db->get_where('users',['id' => $result]);
          $user = $user->row_array();
          if (!empty($user)) {
            $link_activate = base_url() . 'home/verify_email/' .  $user['email_verification_code'];
            $vars = [
              '{username}' => $user['fname'],
              '{email}' => $user['email'],
              '{link_activation}' => $link_activate,
              '{btn_link}' => $link_activate,
              '{btn_text}' => 'Verify'
            ];

            $send=$this->email_template($email,$vars,true,'register');
            echo json_encode(array("status" => TRUE, "message" => 'user registered',"email_status" => $send));
          }else{
            echo json_encode(array("status" => FALSE, "message" => $sms_response));
          }
        }else{
          echo json_encode(array("status" => FALSE, "message" => 'message not sent'));
        }     
      }
    } 

    public function verifyCode()
    {
       $data_array = json_decode(file_get_contents("php://input"),true);
       $data = json_decode(file_get_contents("php://input"));
       $code=$data->code;
       $data=$this->api_model->verify_code($code);
      if (!empty($data)) {        
        if ($data['code']==$code) {
          http_response_code(200);
          echo json_encode(array('status'=>true,'message'=>'Please verify account from email now.'));
        } else {
          http_response_code(400);
          echo json_encode(array('status'=>false,'message'=>'Incorrect verification code.'));
        }
      } else {   
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'Incorrect verification code.'));
      }       
    }

    public function resendCode()
    {
      $data_array = json_decode(file_get_contents("php://input"),true);
      $data = json_decode(file_get_contents("php://input"));
      
      $phone = preg_replace('/\s/', '', $data->phone);
      // $phone=$data->phone;
      if (!empty($phone)) { 
        $mobile_existed = $this->db->get_where('users', ['mobile' => $phone]);
        if($mobile_existed->num_rows() > 0){
          $mobile_existed = $mobile_existed->row_array();
          
          $mobile_verification_code = $this->getNumber(4);
          $code_status = 0;

          //SMS verification process start
          $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
          $sms_response = $this->testunifonic->sendMessage($phone, $sms);
          if(isset($sms_response->MessageID)){
            $sms_response = json_encode($sms_response);
            $this->db->update('users', [ 'code' => $mobile_verification_code,'code_status' => $code_status, ], 
            ['id' => $mobile_existed['id']]);
          }

          $output = json_encode(['success' => true,'codeGenerated' => true,'message' => 'Verification code has been sent to your mobile.']);
          return print_r($output);
        } else{
          $output = json_encode(['error' => true,'codeGenerated' => false,'message' => 'Mobile number is not registered.']);
          return print_r($output);
        }

      }   
    }


    public function RandomStringGenerator($n) 
    {
      // Variable which store final string
      $generated_string = "";
      $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
      // Find the lenght of created string
      $len = strlen($domain);
      // Loop to create random string
      for ($i = 0; $i < $n; $i++) {
        // Generate a random index to pick
        // characters
        $index = rand(0, $len - 1);
        // Concatenating the character
        // in resultant string
        $generated_string = $generated_string . $domain[$index];
      }
      // Return the random generated string
      return $generated_string;
    }

    public function get_auction_cat()
    {
      $data=$this->api_model->get_auction_cat();
      if (!empty($data)) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'success','result'=> $data));
      }
      else
      {
        http_response_code(400);
       echo json_encode(array('status'=>false,'message'=>'No record found'));
      }
    }

    public function detail_of_auctions()
    {
      $data=$this->api_model->detail_of_auctions();
      if (!empty($data)) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'success','result'=> $data));
      }
      else
      {
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'No Record Found'));
      }
    }

    public function editProfile()
    {
      $data_array = json_decode(file_get_contents("php://input"),true);
      $data = json_decode(file_get_contents("php://input"));

      $userId = validateToken();  
      $payload_info = getPayload();
      $number = $data->mobile; 

      // if ($payload_info->mobile  !=$number) 
      // {
      $check_number = $this->api_model->check_user_number($number,$userId);
      if($check_number)
      {  
          echo json_encode(array("status" => false, "message" => 'Number already exist'));
          exit();
      }
      else
        {
          $check_mobile=$this->api_model->update_mobile($userId,$data);
      
      }

      // $email = $data->email;
      // if($payload_info->email != $email)
      // {
      // $result = $this->api_model->check_email_user($email,$userId);
      $result = $this->api_model->check_email_user($userId);
       if($result)
      {   
       
      echo json_encode(array("status" => false, "message" => 'Email already exist'));
      exit();
      } else {
         $check=$this->api_model->update_user($userId,$data);  
      }
      $data = array(
        // 'id' => $id,
        'fname' => $data->fname,
        'lname' => $data->lname,
        // 'email' => $data->email,
        'mobile' => $data->mobile,   
        'city'=>$data->city,
        'reg_type'=>$data->reg_type,
        'job_title'=>$data->job_title,
        'id_number'=>$data->id_number,
        'address' => $data->address,  
        'po_box' => $data->po_box,   
        'company_name' => $data->company_name, 
        'vat_number' => $data->vat_number,  
        'remarks' => $data->remarks, 
        'description'=>$data->description,
        'vat'=> $data->vat,                            
      );

      $get_password = $this->db->get_where('users',['id'=> $userId])->row_array();
      $token = Jwt::encode($payload_info, SECRETE_KEY);
      $data1 = array(
        'user_id' => $get_password['id'],
        'token' => $token,
        'data' => $get_password
      );

      $password = hash("sha256",$get_password['password']);

      if (!empty($data)) {
        $result= $this->api_model->edit_profile($userId, $data);
        echo json_encode(array('status'=>true,'message'=>'Profile updated successfully.','result'=>$data1, 'password' => $password));
        
      } else {
        echo json_encode(array('status'=>false,'message'=>'No change found.'));
        exit();
      }
              
    }  
      
    public function wishList()
    {
      $id= validateToken(); 
      // print_r($id);die();
      $posted_data= json_decode(file_get_contents("php://input"));
      $data['item_id']=$posted_data->item_id;
      $data['user_id']=$id;
      // $id=$data->user_id;
      $data2=$this->db->get_where('favorites_items',['user_id'=>$id, 'item_id'=> $data['item_id']])->row_array();
      
      if (!empty($data2)) {
        $result=$this->api_model->deleteWish($id,$data['item_id']);
        echo json_encode(array("status" => true, "message" => 'Item removed from favourite.'));
       
      } else {
        // $id= validateToken();
        $results= $this->api_model->add_wishlist($data);//update wish list
        echo json_encode(array("status" => true, "message" => 'Added to favourite.','result'=>$data));
      }
         
    } 

    public function getWishList()
    {
      // $data = json_decode(file_get_contents("php://input"));
      $page = 1;
      $id = validateToken(); 
      $posted_data= json_decode(file_get_contents("php://input"),true);
      if (isset($posted_data['page']) && !empty($posted_data['page'])) 
      {
        $page = $posted_data['page'];
      }
      $date['time'] =date('Y-m-d H:i:s', time());
      $output = array();
      $auction_data = array();
      $limit = 10;
      $offset = $limit * $page - $limit;
      $this->db->join('item', 'item.id = favorites_items.item_id', 'left');
      // $this->db->join('auctions','auctions.category_id = item.category_id', 'left');
      $this->db->offset($offset);
      $this->db->limit($limit);
      $record = $this->db->get_where('favorites_items',['user_id'=>$id])->result_array();
      // print_r($record);die();
      foreach ($record as $value) 
      {
        $item_id = $value['item_id'];
        $images_ids = explode(",",$value['item_images']);
        
        $item_detail_url =  base_url('items/details/').urlencode(base64_encode($value['item_id']));
        $make = $this->db->get_where('item_makes',['id'=>$value['make']])->row_array();
        $auction_items_row = $this->db->get_where('auction_items',['item_id'=>$value['item_id']])->row_array();
        $detail_data = $this->db->get_where('item_fields_data',['item_id'=>$value['item_id'],'category_id'=>$value['category_id']])->result_array();
        $detail_data_str =  implode(",",array_column($detail_data, 'value'));
        $model = $this->db->get_where('item_models',['id'=>$value['model']])->row_array();
        $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        // $image =  $base_url_img;
          // $image = 'No image Found';
        if(!empty($value['item_images']) || !empty($value['item_attachments']))
        {
          if(isset($files_array[0]['name']) && !empty($files_array[0]['name']))
          {
            $file_name = $files_array[0]['name'];
            $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
          } else {
            // $image = base_url('assets_admin/images/product-default/');
            // $image = 'No image Found';
          }
        }
        if(isset($auction_items_row) && !empty($auction_items_row))
        {

          $auction_row = $this->db->get_where('auctions',['id'=>$auction_items_row['auction_id']])->row_array();
          $auction_data = array(
            'auction_start_time' => $auction_row['start_time'],
            'auction_expiry_time' => $auction_row['expiry_time'],
            'item_start_time' => $auction_items_row['bid_start_time'],
            'item_end_time' => $auction_items_row['bid_end_time']
          );
        }
        $output[] = array(
          'item_id' => $value['item_id'],
          'name' => $value['name'],
          'price' => $value['price'],
          // 'options' => $value['bid_options'],
          'detail' => $detail_data_str,
          'created_on' => $value['created_on'],
          'auction_detail'=>$auction_data,
          'item_status' => $value['item_status'],
          'image' => $file_name
        );
        
      }
         
      if (!empty($output)) 
      {
        http_response_code(200);
        echo json_encode(array("status" => true, "message" => 'Wish list found','result'=>$output ,'time' => $date));
      }
      else
      {
          http_response_code(200);
          echo json_encode(array('status'=>true,'result'=>''));
      }
    }
    
    /// get list of user documents
    public function getDocuments()
        {
            $id = validateToken();
            $documents = array();
            $doc_type = $this->db->get('documents_type')->result_array();
            $details = $this->db->get_where('users',['id' => $id])->row_array();
            $user_documents = json_decode($details['documents'],true);
            if (isset($user_documents['id_card'])) {
              $documents['id_card'] = $this->db->get_where('files', ['id' => $user_documents['id_card']])->row_array();
            }
            if (isset($user_documents['passport'])) {
              $documents['passport'] = $this->db->get_where('files', ['id' => $user_documents['passport']])->row_array();
            }
            if (isset($user_documents['driver_license'])) {
              $documents['driver_license'] = $this->db->get_where('files', ['id' => $user_documents['driver_license']])->row_array();
            }
            if (isset($user_documents['trade_license'])) {
              $documents['trade_license'] = $this->db->get_where('files', ['id' => $user_documents['trade_license']])->row_array();
            }
            if (!empty($documents)) #
            {
              echo json_encode(array("status" => true, 'result'=>$documents,'document_type' => $doc_type));
            }
            else
            {
              http_response_code(200);
              echo json_encode(array('status'=>false,'message'=>'No document found.' ,'document_type' => $doc_type));
            }
        }


   //// save user documents
    public function saveUserDocuments()
    {
      $userId = validateToken(); 
      $documents_array = array();
      $data_for_delete_files = $this->api_model->users_listing($userId);
      if (isset($data_for_delete_files) && !empty($data_for_delete_files[0]['documents'])) {
        $uploaded_file_ids = json_decode($data_for_delete_files[0]['documents'],true);
        if (isset($uploaded_file_ids['id_card'])) {
          $documents_array['id_card'] = $uploaded_file_ids['id_card'];
        }
        if (isset($uploaded_file_ids['passport'])) {
          $documents_array['passport'] = $uploaded_file_ids['passport'];
        }
        if (isset($uploaded_file_ids['driver_license'])) {
          $documents_array['driver_license'] = $uploaded_file_ids['driver_license'];
        }
        if (isset($uploaded_file_ids['trade_license'])) {
          $documents_array['trade_license'] = $uploaded_file_ids['trade_license'];
        }
      }

      if(isset($_FILES) && !empty($_FILES))
      {
        $path = './uploads/users_documents/';
        if ( ! is_dir($path.$userId)) 
        {
            mkdir($path.$userId, 0777, TRUE);
        }
        $path = $path.$userId.'/'; 
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'ico|png|jpg|jpeg';
        if (isset($_FILES['id_card']['name'])) {
          $id_card = $this->files_model->upload('id_card', $config);
          if (isset($id_card['id'])) {
            $documents_array['id_card'] = $id_card['id'];
          }else{
            echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
            exit();
          }
        }
        if (isset($_FILES['passport']['name'])) {
          $passport = $this->files_model->upload('passport', $config);
          if (isset($passport['id'])) {
            $documents_array['passport'] = $passport['id'];
          }else{
            echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
            exit();
          }
        }
        if (isset($_FILES['driver_license']['name'])) {
          $driver_license = $this->files_model->upload('driver_license', $config);
          if (isset($driver_license['id'])) {
            $documents_array['driver_license'] = $driver_license['id'];
          }else{
            echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
            exit();
          }
        }
        if (isset($_FILES['trade_license']['name'])) {
          $trade_license = $this->files_model->upload('trade_license', $config);
          if (isset($trade_license['id'])) {
            $documents_array['trade_license'] = $trade_license['id'];
          }else{
            echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
            exit();
          } 
        }
        $files_ids['documents'] = json_encode($documents_array);
        $user =  $this->api_model->update_user($userId,$files_ids);
        if ($user) {
          echo json_encode(array("status" => true, "message" => 'Documents uploaded successfully.'));
        }else{
          echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
        }

      }else{
        return false;
      }
  
    }

    ///delete user documents/////
    public function deleteDocuments()
    {
        $data = json_decode(file_get_contents("php://input"));
        $doc_id = $data->id;
        // $attach_name = $this->input->post('file_to_be_deleted');
        $attach_name = $data->name;
        $user_id = $data->user_id;
        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {
            $result_array = $this->items_model->get_customersDocs_new($user_id);
            $user_documents = json_decode($result_array['documents'],true);
            $docs_id = $user_documents['id_card'];
            if(isset($docs_id) && !empty($docs_id))
            {
                $docIds_array = explode(',' ,$docs_id[0]['file_id']);
                if(!empty($docIds_array))
                {
                    $str = $docs_id[0]['file_id'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'file_id' => $updated_str,
            ];
            print_r($updated_str);die();
            $result_update = $this->db->update('users',$update,['id' => $user_id,'document_type_id'=>$d]);
            $get_image = $this->db->get_where('files',['id'=>$result_array['file_id']])->row_array();
        }
        $path = FCPATH .  "uploads/users_documents/".$user_id."/";

        unlink(FCPATH.'uploads/users_documents/'.$user_id."/".$get_image."/");
        // $this->load->helper("file");
        // delete_files($path);

        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';    
        }
    }


    public function changePassword()
    {
      // $data_array = json_decode(file_get_contents("php://input"),true);
      $data = json_decode(file_get_contents("php://input"));
      $userId = validateToken(); 

      $result= $this->db->get('users',['id'=>$userId])->row_array();
      $data = array(
        'password' => hash("sha256",$data->password),
      );
      if (!empty($data)) {
        $results= $this->api_model->update_password_new($userId,$data);
        echo json_encode(array("status" => true, "message" => 'Password updated successfully.','result'=>$results));
      } else {
        echo json_encode(array("status" => false, "message" => 'Password has been failed to updated.'));
      }
    }

    // public function saveDocuments()
    // {  
    //   $userId = validateToken();  
    //   $payload_info = getPayload();              
    //   if(isset($_FILES['documents']['name'])){
    //     $data_for_delete_files = $this->api_model->users_listing($userId); // fetch data 
    //     if(!empty($data_for_delete_files))
    //     {
    //       $old_data = FCPATH .'uploads/users_documents/'.$userId.'/'.$data_for_delete_files[0]['picture'];
    //       $picture = $data_for_delete_files[0]['picture'];
    //       $path = "uploads/users_documents/".$userId."/";
    //         // make path
    //       if ( !is_dir($path)) {
    //           mkdir($path, 0777, TRUE);
    //       }
    //       // If profile picture is selected
    //       $files = '';                                 
    //       if(!empty($_FILES)){
    //         $files .= $this->uploadFiles_with_path_docs($_FILES,$path);
    //         $profile_pic_array = array(
    //         'documents' => $files
    //         );
    //         echo json_encode(array("status" => true, "message" => 'document uploaded'));
    //         $user_array =  $this->api_model->update_user($userId,$profile_pic_array);
    //         if(is_dir($path) && file_exists($picture)){
    //           unlink($old_data); 
    //         } 
    //       } else {
    //         echo json_encode(array("status" => false, "message" => 'document not uploaded'));
    //       }

    //     } else {
    //       echo json_encode(array("status" => false, "message" => 'Error'));
    //     }

    //   }else{
    //     return false;
    //   }  
    // }

    ///get document types

    // public function saveUserDocuments()
    // {

    // }


  public function uploadPicture(){  
    $userId = validateToken();  
    if(isset($_FILES['image']['name'])){
      $data_for_delete_files = $this->api_model->users_listing($userId); // fetch data 
      if(!empty($data_for_delete_files))
      {
        $old_data = FCPATH .'uploads/profile_picture/'.$userId.'/'.$data_for_delete_files[0]['picture'];
        $picture = $data_for_delete_files[0]['picture']; 
          $path = "uploads/profile_picture/".$userId."/";
          // make path
          if ( !is_dir($path)) {
              mkdir($path, 0777, TRUE);
          }
          // If profile picture is selected
          $files = '';                                 
        
          if(!empty($_FILES))
          {
            $files .= $this->uploadFiles_with_path($_FILES,$path);
            $profile_pic_array = array(
            'picture' => $files
            );
            echo json_encode(array("status" => true, "message" => 'Image updated successfully.','picture' => $files));
           $user_array =  $this->api_model->update_user($userId,$profile_pic_array);
            if(is_dir($path) && file_exists($picture)){
              unlink($old_data); 
            }     
          }
          else
          {
            echo json_encode(array("status" => false, "message" => 'Image Not updated.'));
          }

      }
      else
      {
        return false;
      }
          
    }
    else
    {
       echo json_encode(array("status" => false, "message" => 'Image Not updated.'));
    }  
  }

    public static function uploadFiles_with_path($files,$path)
    {
      if(isset($files['image']['name']) && !empty($files['image']['name'])){
        $images = '';
        $target_path        = $path; //Declaring Path for uploaded images
        $ext                = explode('.', basename($files['image']['name']));//explode file name from dot(.)
        $file_extension     = end($ext); //store extensions in the variable
        $new_name           = md5(uniqid()) . "." . $file_extension;
        $tmpFilePath        = $files['image']['tmp_name'];
        $newFilePath        = $target_path . $new_name;//set the target path with a new name of image
        if(move_uploaded_file($tmpFilePath, $newFilePath))
        {
            $images .= $new_name.',';
        }
        $images             = trim($images, ",");
        return $images;
      } 
    }

    public static function uploadFiles_with_path_docs($files,$path)
    {
      if(isset($files['documents']['name']) && !empty($files['documents']['name']))
      {
        $images = '';
        $target_path        = $path; //Declaring Path for uploaded images
        $ext                = explode('.', basename($files['documents']['name']));//explode file name from dot(.)
        $file_extension     = end($ext); //store extensions in the variable
        $new_name           = md5(uniqid()) . "." . $file_extension;
        $tmpFilePath        = $files['documents']['tmp_name'];
        $newFilePath        = $target_path . $new_name;//set the target path with a new name of image
        if(move_uploaded_file($tmpFilePath, $newFilePath))
        {
          $images .= $new_name.',';
        }
        $images             = trim($images, ",");
        return $images;
      } 
    }

    public function userAuctions()
    {
      $id= validateToken();
      $date['time'] =date('Y-m-d H:i:s', time());
      $data=$this->api_model->user_history_auctions($id);
      // $count =0;
       foreach ($data as $key => $value) {
        $data[$key]['view_count'] = $this->db->where('item_id',$value['itm_id'])->where('auction_id',$id)->from('online_auction_item_visits')->count_all_results();
         $arr = array();
         $arr[$key] =$value;
         // $count++;
       }
      if (!empty($data)) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'success','result'=> $data,'time'=>$date));
      } else {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>''));
      }
    }

    
     
public function getItems()    //get items against cat
    {
        $result1 = '';
        $result2 = '';
        $data = json_decode(file_get_contents("php://input"));
        $date['time'] =date('Y-m-d H:i:s', time());
        $id= $data->item_id;

        $headers = getAuthorizationHeader();
        if (!empty($headers)) {
          $user_id= validateToken();
          // print_r($user_id);die();
          
          $favorites_items = $this->db->get_where('favorites_items', ['user_id' => $user_id, 'item_id' => $id])->row_array();
          $result=$this->api_model->item_details_cat($id);
          //add user view
          $visit_data = array(
              'user_id' => $user_id,
              'auction_id' => $result['auction_id'],
              'item_id' => $id
          );
          // print_r($visit_data);die();
          $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
          if (empty($visit_info)) {
              $visit = $this->db->insert('online_auction_item_visits', $visit_data);
          }

          if (!empty($result['item_images'])) {
            $result1=explode(',', $result['item_images']);
            foreach ($result1 as $key => $value) {
              $result3[]=$this->db->select('name as file_name')->get_where('files',['id'=>$value])->row_array();
            } 
          }
          $datafields = $this->oam->fields_data($result['category_id']);
          $fdata = array();
          foreach ($datafields as $key => $fields)
            {
              $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($id,$fields['id']);
              $fields['values'] = json_decode($fields['values'],true);   
              $fields['data-id'] = $fields['id'];
              $item_dynamic_fields_info['value'] = str_replace(array( '[', ']' ), '', $item_dynamic_fields_info['value']);
              if (!empty($fields['values'])) {
                  $fields['data_value'] = $item_dynamic_fields_info['value'];
                  foreach ($fields['values'] as $key => $values) {
                    if ($values['value'] == $item_dynamic_fields_info['value']) {
                        $exclude_lable = explode('|', $values['label']);
                        $fields['data_value'] = $exclude_lable[0];
                    }
                  }
              }else {
                  $fields['data_value'] = $item_dynamic_fields_info['value'];
              }
            $fdata[] = $fields;
            }
          $fields = $fdata;
          // $dynamic_fields = $this->db->get_where('item_fields_data'['']);
          if (!empty($result['bid_options'])) {
            $result2=explode(',', $result['bid_options']);
          }       
          if (!empty($result)) {

            $balance=$this->api_model->user_balance($user_id);
            $balance['amount']= $balance['amount'];
            echo json_encode(array(
                    'status'=>true,
                    'result'=> $result,
                    'time'=>$date,
                    'images'=>$result3,
                    'options'=>$result2,
                    'user_balance'=> $balance, 
                    'fields'=> $fields,
                    'favorites' => $favorites_items
                ));
          }
          else
          {
            echo json_encode(array('status'=>true,'result'=>''));
          }
        }

        //without token
        else{
          $result=$this->api_model->item_details_cat($id);
          //add user view
          $visit_data = array(
              'auction_id' => $result['auction_id'],
              'item_id' => $id
          );
          // print_r($visit_data);die();
          $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
          if (empty($visit_info)) {
              $visit = $this->db->insert('online_auction_item_visits', $visit_data);
          }

          if (!empty($result['item_images'])) {
            $result1=explode(',', $result['item_images']);
            foreach ($result1 as $key => $value) {
              $result3[]=$this->db->select('name as file_name')->get_where('files',['id'=>$value])->row_array();
            } 
          }
          $datafields = $this->oam->fields_data($result['category_id']);
          $fdata = array();
          foreach ($datafields as $key => $fields)
          {
            $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($id,$fields['id']);
            $fields['values'] = json_decode($fields['values'],true);   
            $fields['data-id'] = $fields['id'];
            $item_dynamic_fields_info['value'] = str_replace(array( '[', ']' ), '', $item_dynamic_fields_info['value']);
            if (!empty($fields['values'])) {
              $fields['data_value'] = $item_dynamic_fields_info['value'];
              foreach ($fields['values'] as $key => $values) {
                if ($values['value'] == $item_dynamic_fields_info['value']) {
                  $fields['data_value'] = $values['label'];
                }
              }
            } else {
              $fields['data_value'] = $item_dynamic_fields_info['value'];
            }
            $fdata[] = $fields;
          }
          $fields = $fdata;
          // $dynamic_fields = $this->db->get_where('item_fields_data'['']);      
          if (!empty($result)) {

            // $balance=$this->api_model->user_balance($user_id);
            // $balance['amount']= $balance['amount'];
            echo json_encode(array('status'=>true,'result'=> $result,'time'=>$date, 'images'=>$result3,'fields'=> $fields));
          }
          else
          {
            echo json_encode(array('status'=>true,'result'=>''));
          }
        
        }
    }

    public function getNotification()
    {
      $userId= validateToken();
      // $data = json_decode(file_get_contents("php://input"));
      $info = $this->db->get_where('notification', ['receiver_id' => $userId])->result_array();
      foreach ($info as $key => $detail) {
        $info[$key]['message'] = ucfirst($detail['message']);
        $info[$key]['type'] = ucfirst($detail['type']);
      }
      $arr = array();
      if (!empty($info)) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'result'=> $info));
      }
      else
        {
            http_response_code(200);
            echo json_encode(array('status'=>true,'result'=>$arr));
        }
    }

    public function notificationStatus()
    {
     $data = json_decode(file_get_contents("php://input"));
      $id= $data->id;
      $status= 'read';
      $result=$this->api_model->notificationStatus($id,$status);
      
      if (!empty($result)) {
        echo json_encode(array("status" => true));
      }
      else
      {
        echo json_encode(array("status" => false));
      }
      
    }

            

    public function userBids()
    {
      $id= validateToken(); 
      $data = array();
      $output = array();
      $image = '';
      $base_url_img = base_url('uploads/items_documents/');
      $page = 1;
      $data = json_decode(file_get_contents("php://input"));
      // $posted_data = json_decode(file_get_contents("php://input"), true);
      // $id= $data->id;
      if (isset($posted_data['page']) && !empty($posted_data['page'])) {
        $page = $posted_data['page'];
      }
      $limit = 10;
      $offset = $limit * $page - $limit;
      
      $query = $this->db->query('Select bid.bid_time,bid.user_id,bid.item_id,bid.auction_id,bid.bid_amount, bid.bid_status ,item.name,item.created_on,item.make,item.model,item.item_images,item.item_attachments,item.category_id  from bid  
       inner join  ( Select max(bid_time) as LatestDate, item_id  from bid where user_id = '.$id.'  Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id
           LEFT JOIN item ON item.id = bid.item_id  WHERE bid.user_id = '.$id.' LIMIT '.$offset.', '.$limit.';');
      foreach ($query->result_array() as $row){
      
      // print_r($this->db->last_query());die();
        $item_id = $row['item_id'];
        $images_ids = explode(",",$row['item_images']);
        $item_detail_url =  base_url('items/details/').urlencode(base64_encode($row['item_id']));
        $make = $this->db->get_where('item_makes',['id'=>$row['make']])->row_array();
        $detail_data = $this->db->get_where('item_fields_data',['item_id'=>$row['item_id'],'category_id'=>$row['category_id']])->result_array();
        $view_count = $this->db->where('item_id',$row['item_id'])->where('auction_id',$row['auction_id'])->from('online_auction_item_visits')->count_all_results();
        $detail_data_str =  implode(",",array_column($detail_data, 'value'));
        $model = $this->db->get_where('item_models',['id'=>$row['model']])->row_array();
        $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        if(!empty($row['item_images']) || !empty($row['item_attachments'])){
          if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
            $file_name = $files_array[0]['name'];
            $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
            $image =  $base_url_img;
          } else {
            $image = $base_url_img;
          }
        }
        $output[] = array(
          'item_id' => $row['item_id'],
          'created_on' => $row['created_on'],
          'name' => $row['name'],
          'bid_amount' => $row['bid_amount'],
          'detail' => $detail_data_str,
          'make'=>$make['title'],
          'model'=>$model['title'],
          'bid_status' => $row['bid_status'],
          'views_count' => $view_count,
          'image' => $image,
        );
      }
      if (!empty($output)) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'success','result'=> $output));
      } else { 
        http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>''));
      }
    }

    public function Logout()
    {
      $id= $this->input->post('id');
      $data=$this->api_model->get_users($id);
      if (!empty($id)) {
        if ($id ==$data['id']) {
         $this->session->sess_destroy();
         echo "user logout successfully";
        }
           else
        {
          echo "user not exist";
        }
      }
    }

    public function contact()
    {

      $rules = array(
        array(
          'field' => 'name',
          'label' => 'name',
          'rules' => 'trim|required',
        ),
        array(
          'field' => 'email',
          'label' => 'Email',
          'rules' => 'required|valid_email',
        ),
      );

      $this->form_validation->set_rules($rules);
      if ($this->form_validation->run() == FALSE) {
        echo validation_errors();
        exit();
      }
      $email = $this->input->post('email');

      $result = $this->api_model->check_email_user($email);
      if($result == true)
      {   
        echo json_encode(array('status'=>true,'message'=>'success','result'=> $result));
        exit();
      } 
      $data=array(
        'name'=>$this->input->post('name'),
        'email'=>$this->input->post('email'),
        'user_id'=>$this->input->post('user_id')
      );
      $result=$this->api_model->contact($data);
      if (!empty($result)) {
        http_response_code(200);
      echo json_encode(array('status'=>true,'message'=>'success','result'=> $result));
      }
      else
      {
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'error'));
      }
    }

    public function car_valuation()
    {
      $data = json_decode(file_get_contents("php://input"));
      // $data = json_decode($this->input->post('valuation_make_id'));
      // echo json_encode(array("status" => TRUE, "message" =>'success','result'=>$data));
      // exit();
      if (!empty($data->valuation_make_id)) {
        $result = array(
          'car' => $data->valuation_make_id,
          'model' => $data->valuation_model_id,
          'year' => $data->year_to,
          'eng_size' => $data->engine_size_id,
          'millage' => $data->valuation_milleage_id,
          'value1' => $data->valuate_option,
          'value2' => $data->valuate_paint,
          'value3' => $data->valuate_gc,
          'email' => $data->email,
        );
        // $this->load->library('form_validation');
        // $rules = array(
        //   array(
        //     'field' => 'valuation_make_id',
        //     'label' => 'Make',
        //     'rules' => 'trim|required'),
        //   array(
        //     'field' => 'valuation_model_id',
        //     'label' => 'Model',
        //     'rules' => 'trim|required'),
        //   array(
        //     'field' => 'year_to',
        //     'label' => 'Year',
        //     'rules' => 'trim|required'),
        //   array(
        //     'field' => 'valuate_option',
        //     'label' => 'Option',
        //     'rules' => 'trim|required'),
        //   array(
        //     'field' => 'engine_size_id',
        //     'label' => 'Engine Size',
        //     'rules' => 'trim|required')
        // );
        // $this->form_validation->set_rules($rules);
        // if ($this->form_validation->run() == false) {
        //    echo json_encode(array('msg' => validation_errors()));
        //    exit();
        // } 
        $valuation_make_id = $data->valuation_make_id;
        $valuation_model_id = $data->valuation_model_id;
     
        $year_to = $data->year_to;
        $valuation_milleage_id = $data->valuation_milleage_id;
        $engine_size_id = $data->engine_size_id;
        $valuate_option = $data->valuate_option;
        $valuate_paint = $data->valuate_paint;
       // $valuate_gc = $this->input->post('valuate_gc');
        $valuate_gc = $data->valuate_gc;
        $valuate_Opt = $data->valuate_option;
        $email = $data->email;
        // get price
        
        $result = $this->db->query("SELECT * FROM `valuation_price` WHERE 
        (`valuation_make_id` = '" . $valuation_make_id .
            "' AND `valuation_model_id` = '" . $valuation_model_id . "') AND (`engine_size_id` = '" . $engine_size_id . "')");
        $result = $result->row_array();
    
        $last_query = $this->db->last_query();
        if (isset($result) && !empty($result) && count($result) > 0){

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
          $get_mileage_depre = $this->db->query("select millage_depreciation from valuation_millage where mileage_id = '".$valuation_milleage_id."' ");
          $get_mileage_depre = $get_mileage_depre->row_array();
          if (count($get_mileage_depre) > 0) {
            if ($get_mileage_depre['millage_depreciation'] > 0) {
            
            }
          } 
          $id=1;
          // check global config depreciation
          $get_global_depre = $this->db->query("select * from valuation_config_setting where created_by='" . $id . "'");
          $get_global_depre = $get_global_depre->row_array();    
          if (count($get_global_depre) > 0) {
            // check paint depreciation
            if ($get_global_depre['config_setting_paint'] != "" && $valuate_paint!="") {
              $config_setting_paint = json_decode($get_global_depre['config_setting_paint'], 1);
              //print_r($config_setting_paint);
              $config_setting_paint = $config_setting_paint[$valuate_paint];
              if ($config_setting_paint > 0) {
                $config_setting_paint = (float)$config_setting_paint;

              }

            }
            $config_setting_specs = '';
            $config_option = ''; 

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
            // $email_messagee = str_replace("{valuate_gc}", $valuate_gc,$email_message['body']);
            $email_messagee = str_replace("{valuation_price}", $data,$email_messagee);
            // print_r($email_messagee);die('aaaaa');
            $to =$this->input->post('email');       
            $this->session->set_userdata('email', $to);
            $this->session->set_userdata('car_price', $data);
            $this->email->to($to);
            $this->email->subject($email_message['subject']);
            $this->email->message($email_messagee);
            $this->email->send();
          }
          $message = 'success';
          echo json_encode(array("status" => TRUE, 'message' => $message, 'price' => $data));
          // $this->template->load_admin('car_valuation/car_valuation_result',$data);
        }else{
          echo json_encode(array("status" => TRUE, 'message' => 'Not Aailable Record', 'price' => $data));
          // redirect(base_url().'cars/car_valuation');
        }
        // echo json_encode(array("status" => TRUE, "message" =>'success','result'=>$result));
      }
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
      // print_r($email_message);die();

      $this->email->to($email);
      $this->email->subject($notification);
      $this->email->message($email_message);
      $is_send = $this->email->send();
      return ($is_send) ? true : false;
      // }
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

    public function getTerms()
    {
      $terms_info = $this->db->get('terms_condition')->row_array();
      if (!empty($terms_info)) #
      {
        $title = json_decode($terms_info['title']);
        $description = json_decode($terms_info['description']);
        $data = array(
          'title' => $title,
          'description' => $description
        );
        echo json_encode(array("status" => true, "message" => 'terms and condition','result'=>$data));
      }
      else
      {
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'terms and condition not available'));
      }
    }

    public function getInventory()
    {
      $id = validateToken();
      $terms_info = $this->api_model->inventory_list($id);
      $record=0;
      $output=array();
      $response=array();
      $base_url_img =base_url('assets_admin/images/product-default.jpg');
      $image = $base_url_img;
      if (isset($terms_info) && !empty($terms_info)) {
        foreach ($terms_info as $value) {
          $item_id = '';
          $file_name = '';
          $image = $base_url_img;
          $item_id = $value['id'];
          $images_ids = explode(",",$value['item_images']);
          $item_detail_url =  base_url('items/details/').urlencode(base64_encode($value['id']));
          $make = $this->db->get_where('item_makes',['id'=>$value['make']])->row_array();
          $detail_data = $this->db->get_where('item_fields_data',['item_id'=>$value['id'],'category_id'=>$value['category_id']])->result_array();
          $detail_data_str =  implode(",",array_column($detail_data, 'value'));
          $model = $this->db->get_where('item_models',['id'=>$value['model']])->row_array();
          $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
          if(!empty($value['item_images']) || !empty($value['item_attachments'])){
            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
              $file_name = $files_array[0]['name'];
              $image = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
            } else {
              $image = $base_url_img;
            }
          }
          $output[] = array(
            'item_id' => $value['id'],
            'name' => $value['name'],
            'file_name' => $file_name,
            'price' => $value['price'],
            'created_on' => $value['created_on'],
            'detail' => $detail_data_str,
            // 'keyword' => $value['keyword'],
            // 'make'=>$make['title'],
            // 'model'=>$model['title'],
            'status' => $value['status'],
            'item_status' => $value['item_status'],
            'image' => $image,
          );
          
        }
        if (!empty($terms_info)) #
        {
          http_response_code(200);
          echo json_encode(array("status" => true, "message" => 'inventory data','result'=>$output));
        }
      } else {
        http_response_code(200);
        echo json_encode(array("status" => true, "message" => '','result'=> ''));
      }
    }


     
    public function inventry_item_detail()    //get items against cat
    {
      $user_id= validateToken();
      // print_r($user_id);die();
      $result3 = array();
      $data = json_decode(file_get_contents("php://input"));
      $date['time'] =date('Y-m-d H:i:s', time());
      $id= $data->item_id;
      $result=$this->db->get_where('item', ['id' => $id])->row_array();

      if (!empty($result['item_images'])) {
        $result1=explode(',', $result['item_images']);
        foreach ($result1 as $key => $value) {
          $result3[]=$this->db->select('name as file_name')->get_where('files',['id'=>$value])->row_array();
        } 
      }
      $datafields = $this->oam->fields_data($result['category_id']);
      $fdata = array();
      foreach ($datafields as $key => $fields)
      {
        $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($id,$fields['id']);
        $fields['values'] = json_decode($fields['values'],true);   
        $fields['data-id'] = $fields['id'];
        $item_dynamic_fields_info['value'] = str_replace(array( '[', ']' ), '', $item_dynamic_fields_info['value']);
        if (!empty($fields['values'])) {
          $fields['data_value'] = $item_dynamic_fields_info['value'];
          foreach ($fields['values'] as $key => $values) {
            if ($values['value'] == $item_dynamic_fields_info['value']) {
              $fields['data_value'] = $values['label'];
            }
          }
        } else {
          $fields['data_value'] = $item_dynamic_fields_info['value'];
        }
        $fdata[] = $fields;
      }
      $fields = $fdata;     
      if (!empty($result)) {

        echo json_encode(array('status'=>true,'result'=> $result,'time'=>$date, 'images'=>$result3, 'fields'=> $fields));
      }
      else
      {
        echo json_encode(array('status'=>true,'result'=>''));
      }
    }

    public function getInventoryByItems()
    {
      $data = json_decode(file_get_contents("php://input"));
      $id = $data->item_id;
      $terms_info = $this->api_model->inventory_listByItems($id);
      $record=0;
      $output=array();
      $response=array();
      $base_url_img =base_url('assets_admin/images/product-default.jpg');
      $image = $base_url_img;
      if (isset($terms_info) && !empty($terms_info)) {
        foreach ($terms_info as $value) {
          $item_id = $value['id'];
          $images_ids = explode(",",$value['item_images']);
          $item_detail_url =  base_url('items/details/').urlencode(base64_encode($value['id']));
          $make = $this->db->get_where('item_makes',['id'=>$value['make']])->row_array();
          $detail_data = $this->db->get_where('item_fields_data',['item_id'=>$value['id'],'category_id'=>$value['category_id']])->result_array();
          $detail_data_str =  implode(",",array_column($detail_data, 'value'));
          $model = $this->db->get_where('item_models',['id'=>$value['model']])->row_array();
          $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
          if(!empty($value['item_images']) || !empty($value['item_attachments'])){
            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                $image =  $base_url_img;
            }else{
                 $image = $base_url_img;
            }
          }

          $output[] = array(
            'name' => $value['name'],
            'price' => $value['price'],
            'file_name' => $value['file_name'],
            'lot_id' => $value['lot_id'],
            'detail' => $detail_data_str,
            'keyword' => $value['keyword'],
            'make'=>$make['title'],
            'model'=>$model['title'],
            'item_status' => $value['item_status'],
            'image' => $image,
          );
          
        }
        if (!empty($terms_info)) 
        {
          http_response_code(200);
          echo json_encode(array("status" => true, "message" => 'inventory data','result'=>$output));
        }
      } else {
          http_response_code(200);
          echo json_encode(array("status" => true, "message" => '','result'=> ''));
      }
    }

    public function carValuation()
    {           
      $data = json_decode(file_get_contents("php://input"));
        $valuation_make_id= $data->valuation_make_id;
        $valuation_model_id = $data->valuation_model_id;
        $year_to = $data->year_to;
        $valuation_milleage_id = $data->valuation_milleage_id;
        $engine_size_id = $data->engine_size_id;
        $valuate_option = $data->valuate_option;
        $valuate_paint = $data->valuate_paint;
        $valuate_gc = $data->valuate_gc;
        $valuate_Opt = $data->valuate_option;
        $email = $data->email;
                
      $result = $this->db->query("SELECT * FROM `valuation_price` WHERE 
        (`valuation_make_id` = '" . $valuation_make_id .
        "' AND `valuation_model_id` = '" . $valuation_model_id . "') AND (`engine_size_id` = '" . $engine_size_id . "')");
      $result = $result->row_array();
      // $last_query = $this->db->last_query();
      if (isset($result) && !empty($result) && count($result) > 0) 
        {

          $orig_price = $result['price'];

          // check year depreciation
          $get_year_depre = $this->db->query("select year_depreciation from valuation_years where  (`make_id` = '" . $valuation_make_id .
          "' AND `model_id` = '" . $valuation_model_id . "') AND (year = '".$year_to."') ");
          $get_year_depre = $get_year_depre->row_array();
          // print_r($get_year_depre);die();
         

          if (count($get_year_depre) > 0) {
              $get_year_depre['year_depreciation'] = (float)$get_year_depre['year_depreciation'];
              if ($get_year_depre['year_depreciation'] > 0) {
                  $year_valuate_price = $orig_price * ($get_year_depre['year_depreciation'] / 100);
                  $orig_price = $orig_price - $year_valuate_price;
              }
          }

          // check mileage depreciation
          $get_mileage_depre = $this->db->query("select millage_depreciation from valuation_millage where mileage_id = '".$valuation_milleage_id."' ");
          $get_mileage_depre = $get_mileage_depre->row_array();
          if (count($get_mileage_depre) > 0) {
              if ($get_mileage_depre['millage_depreciation'] > 0) {
              
              }
          } 
          $id=1;
          // check global config depreciation
          $get_global_depre = $this->db->query("select * from valuation_config_setting where created_by='" . $id . "'");
          $get_global_depre = $get_global_depre->row_array();    
          if (count($get_global_depre) > 0) {
            // check paint depreciation
            if ($get_global_depre['config_setting_paint'] != "" && $valuate_paint!="") {
                $config_setting_paint = json_decode($get_global_depre['config_setting_paint'], 1);
                //print_r($config_setting_paint);
                $config_setting_paint = $config_setting_paint[$valuate_paint];
                if ($config_setting_paint > 0) {
                    $config_setting_paint = (float)$config_setting_paint;

                }

            }
            $config_setting_specs = '';
            $config_option = ''; 

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
            // $paint = $this->db->get('item_category_fields')->row_array();
            // $paint_dcode = json_decode($paint['values']);
            // $paint_result =  $this->db->get_where('item_category_fields',['values'=>$valuate_paint])->row_array();
            // $option =  $this->db->get_where('valuate_cars_options',['id'=>$valuate_option])->row_array();
            // print_r($email_message);die('asaaaa');
            $email_messagee = str_replace("{valuation_make}", $make['title'],$email_message['body']);
            $email_messagee = str_replace("{valuation_model}", $model['title'],$email_messagee);
            $email_messagee = str_replace("{valuation_year}", $year_to,$email_messagee);
            $email_messagee = str_replace("{valuation_enginesize}", $engine_size['title'],$email_messagee);
            $email_messagee = str_replace("{option}", $valuate_option,$email_messagee);
            $email_messagee = str_replace("{paint}", $valuate_paint,$email_messagee);
            // $email_messagee2 = $valuate_gc;
            // $email_messagee = str_replace("{valuate_gc}", $valuate_gc,$email_messagee);
            $email_messagee = str_replace("{valuation_price}", $data,$email_messagee);
            // print_r($email_messagee2);die('aaaaa');
            $to = $email;       
            $this->session->set_userdata('email', $to);
            $this->session->set_userdata('car_price', $data);
            $this->email->to($to);
            $this->email->subject($email_message['subject']);
            $this->email->message($email_messagee);
            $this->email->send(); 
          }

            $msg = 'Valuation completed successfully.';
          echo json_encode(array('message' => $msg, 'price' => $data));
          // $this->template->load_admin('car_valuation/car_valuation_result',$data);
        } else {
        echo json_encode(array('message' =>'error' , 'result' => 'Valuation failed.'));
      }
    }

    public function getMakes()
    {
      $data = json_decode(file_get_contents("php://input"));
      $this->db->select('title,valuation_make.id');
      $this->db->from('valuation_make');
      $this->db->order_by('title','asc'); 
      $valuation_make= $this->db->get()->result_array();
      
      if (!empty( $valuation_make)) {
        echo json_encode(array('status' => true, 'result' =>  $valuation_make));
      } else {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
      }
    }


//// get the make when save item /////
    public function get_makes_for_inspection()
    {
      $data = json_decode(file_get_contents("php://input"));
      $this->db->select('*');
      $this->db->from('item_makes');
      // $this->db->where('id',);
      $inspection= $this->db->get()->result_array();
      foreach ($inspection as $key => $inspections) {
          $item_name = json_decode($inspections['title']);
          $inspection[$key]['title'] = $item_name;
      }
      if (!empty( $inspection)) {
        echo json_encode(array('status' => true, 'result' =>  $inspection));
      } else {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
      }
    }
//// get the models when save item /////
    public function get_models_for_inspection()
    {
      $data = json_decode(file_get_contents("php://input"));
      $this->db->select('*');
      $this->db->from('item_models');
      $this->db->where('make_id',$data->make_id);
      $inspection= $this->db->get()->result_array();
      foreach ($inspection as $key => $inspections) {
          $item_name = json_decode($inspections['title']);
          $inspection[$key]['title'] = $item_name;
      }
      if (!empty( $inspection)) {
        echo json_encode(array('status' => true, 'result' =>  $inspection));
      } else {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
      }
    }

    public function getModels()
    {
      $data = json_decode(file_get_contents("php://input"));
      $d=$data->make_id;
      $this->db->select('valuation_make_id,valuation_model.title,valuation_model.id'); 
      $this->db->from('valuation_model');
      $this->db->where('valuation_make_id',$data->make_id);
      $this->db->join('valuation_make','valuation_make.id=valuation_model.valuation_make_id','inner');
      $this->db->order_by('valuation_model.title','asc');
       $valuation_make= $this->db->get()->result_array();
       if (!empty( $valuation_make)) {
      echo json_encode(array('status' => true, 'result' =>  $valuation_make));
      }
      else
      {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
      }
    }

    public function getEngine()
    {
      $data = json_decode(file_get_contents("php://input"));
      $model_id = $data->model_id;
      $make_id = $data->make_id;
      $year = $data->year;
        $query = $this->db->query('select engine_size_id from valuation_price where valuation_make_id = "'.$make_id.'" and valuation_model_id = "'.$model_id.'"and year = "'.$year.'"')->result_array();
         foreach ($query as  $value) {
          $result = array();
          $result[] = $this->db->query('select id, title from valuation_enginesize where id= "'.$value['engine_size_id'].'"')->row_array();
        }
        if(!empty($result)){
           echo json_encode( array('error' => false, 'result'=> $result));
        }else{
           echo json_encode( array('error' => true,));
        }
    }

     public function getYear()
    {
      $data = json_decode(file_get_contents("php://input"));
      $this->db->select('valuation_years.id,valuation_years.year');
      $this->db->from('valuation_years');
      $this->db->where('make_id',$data->make_id); 
      $this->db->where('model_id',$data->model_id); 
      // $this->db->where('model_id',$data->model_id);
      $valuation_make= $this->db->get()->result_array();
      if (!empty( $valuation_make)) {
      echo json_encode(array('status' => true, 'result' =>  $valuation_make));
      }
    
      else
      {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found.'));
      }
    }

      public function getMillage()
    {
      $this->db->select('mileage_label,milleage.id');
      $this->db->from('milleage');
      $this->db->order_by('milleage', 'ASC');
       $valuation_make= $this->db->get()->result_array();
       if (!empty( $valuation_make)) {
      echo json_encode(array('status' => true, 'result' =>  $valuation_make));
      }
      else
      {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
      }
    }

    public function getOptions()
    {
      $this->db->select('title,valuate_cars_options.id');
      $this->db->from('valuate_cars_options');
      $valuation_make= $this->db->get()->result_array();
      if (!empty( $valuation_make)) {
        echo json_encode(array('status' => true, 'result' =>  $valuation_make));
      } else {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
      }
    }

    public function getPaint()
    {
      $this->db->select('config_setting_paint,valuation_config_setting.id');
      $this->db->from('valuation_config_setting');
      $valuation_make= $this->db->get()->result_array();
      if (!empty($valuation_make)) {
        $newArr=array();
        foreach ($valuation_make as $key => $value) {
          $json= $value['config_setting_paint'];
          if (!empty($json)) {
            $arr= json_decode($json);
            foreach ($arr as $key1 => $a) {
              $arr22=array();
              $arr22['title']= $key1;
              $arr22['value']= $a;
              array_push($newArr, $arr22);
            }
          }
        }
        echo json_encode(array('status' => true, 'result' => $newArr));
      } else {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
      }
    }

    public function getSpecs()
    {
      $this->db->select('config_setting_specs');
      $this->db->from('valuation_config_setting');
      $valuation= $this->db->get()->result_array();
      if(!empty($valuation)) {
        $newArr = array();
        foreach($valuation as $key => $value) {
          $json = $value['config_setting_specs'];
          if(!empty($json)) {
            $arr = json_decode($json);
            foreach($arr as $key1 => $a) {
              $arr22 = array();
              $arr22['title'] = $key1;
              $arr22['value'] = $a;
              array_push($newArr, $arr22);
            }
          }
        }
        echo json_encode(array('status' => true, 'result' => $newArr));
      } else {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
      }
    }

    public function bookAppointment()
    {
      $data = json_decode(file_get_contents("php://input"));
      $result= array(
        'date'=> $data->date,
        'time'=> $data->time,
        'fname'=> $data->fname,
        'lname'=> $data->lname,
        'number'=> $data->number
      );
      if (!empty($result)) {
        $email_message['subject']='Book Appointment';
        $email_messagee ='"'.$result['fname'].' '.$result['lname'].'"Your Appointment is schdule :"'.$result['date'].'"at "'.$result['time'].'""'.$result['date'].'"'; 
        $to= $data->email;
        $this->email->to($to);
        $this->email->subject($email_message['subject']);
        $this->email->message($email_messagee);
        $this->email->send();
        echo json_encode(array('error' => false, 'message' => 'Appointment booked ! check you email'));
      } else {
        echo json_encode(array('error' => true, 'message' => 'error'));
      }
    }
    public function contactUs()
    {
      $data= $this->db->get('contact_us')->row_array();
      $number = $data['mobile'];
      // if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $number,  $matches ) )
      // {
      //     $result = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
      //     $data['mobile'] = $result;
      // }
      if (!empty($data)) {
        echo json_encode(array('status' => true, 'result' => $data));
      } else {
        echo json_encode(array('status' => true, 'result' => 'no record found'));
      }
    }

    public function contactEmailSend()
    {
      $data = json_decode(file_get_contents("php://input"));
      $data2= $this->api_model->contactUs();
      if (!empty($data2)) {
        $this->email->to($data2);
        $this->email->subject($data->subject);
        $this->email->message($data->message);
        $this->email->send();
        echo json_encode(array('error' => false, 'message' => 'Thanks for contacting us.'));
      } else {
        echo json_encode(array('error' => true, 'result' => 'email not sent'));
      }
    }


            ////////////////  Create item  /////////////////
       //     public function saveItem()
       // {
       //     $data = $this->input->post('category_id');

       //     if ($data == 1) // for vehicles
       //     {
       //       $result=array(
       //          'make'=>$this->input->post('make'),
       //          'model'=>$this->input->post('model'),
       //          'vin_number'=>$this->input->post('vin_number'),
       //          'year'=>$this->input->post('year'),
       //          'millage'=>$this->input->post('millage'),
       //          'eng_size'=>$this->input->post('eng_size'),
       //          'color'=>$this->input->post('color'),
       //          'transmittion'=>$this->input->post('transmittion'),
       //       );
       //       $d= $this->api_model->insert_item($result);
       //     }
       //     elseif ($data == 7) // for general
       //     {
       //       $result=array(
       //          'sub_cat'=>$this->input->post('sub_cat'),
       //          'brand'=>$this->input->post('brand'),
       //          'ram'=>$this->input->post('ram'),
       //          'processor'=>$this->input->post('processor'),
       //          'touch'=>$this->input->post('touch'),         
       //          'color'=>$this->input->post('color'),
       //       );
       //     }
       //     elseif ($data == 2) // for property
       //      {
       //        $result=array(
       //          'area'=>$this->input->post('area'),
       //          'address'=>$this->input->post('address'),
       //       );
       //     }
       //     elseif ($data == 5) // for number plate
       //      {
       //        $result=array(
       //          'digit'=>$this->input->post('digit'),
       //          'color'=>$this->input->post('color'),
       //       );
       //     }

       //       $result=array(
       //          'name'=>$this->input->post('item_name'),
       //          'registration_no'=>$this->input->post('reg_code'),
       //          'price'=>$this->input->post('price'),
       //          'keyword'=>$this->input->post('keyword'),
       //          'detail'=>$this->input->post('detail'),
       //          'status'=>$this->input->post('status'),
       //          'item_status'=>$this->input->post('item_status'),
       //          // 'feature_item'=>$this->input->post('feature_item'),
       //          'seller_id'=>$this->input->post('seller'),
       //       );
       //      $d= $this->api_model->insert_item($result);

           
       // }

    //item makes
    public function get_item_categories()
    {
      $data = array();
      $category_list = $this->api_model->get_item_category_active();
      if ($category_list) {
        foreach ($category_list as $key => $value) {
          $title = json_decode($value['title']);
          $category_list[$key]['title'] = $title;
        }
        echo json_encode(array('status' => true, 'result' => $category_list));  
      }else{
        echo json_encode(array('status' => true, 'result' => '', 'message'=> 'No record found.'));  
      }
    }
    //item makes
    public function get_makes_options()
    {
      $data = array();
      $data_makes_array = $this->api_model->get_makes_list_active();
      echo json_encode(array('status' => true, 'result' => $data_makes_array));  
    }
    //item subcategories
    public function get_subcategories()
    {
      $data = array();
      $data_subcategory_array = array();
      // $category_id = $this->input->post('category_id');
      $data = json_decode(file_get_contents("php://input"));
      if (!empty($data->category_id)) {
          $data_subcategory_array = $this->api_model->get_item_subcategory_list($data->category_id);
      }
      foreach ($data_subcategory_array as $key => $data_subcategory_arrays) {
          $item_name = json_decode($data_subcategory_arrays['title']);
          $data_subcategory_array[$key]['title'] = $item_name;
      }
      echo json_encode(array('status' => true, 'result' => $data_subcategory_array)); 
    }

    ///// Millage type against item_id /////
     public function getMillageType()
    {
      $data = json_decode(file_get_contents("php://input"));
      $this->db->select('item.id,item.mileage_type,item.make,item.model');
      $this->db->from('item');
      $this->db->where('make',$data->make_id); 
      $this->db->where('model',$data->model_id); 
      $valuation_make= $this->db->get()->result_array();
      if (!empty( $valuation_make)) {
      echo json_encode(array('status' => true, 'result' =>  $valuation_make));
      }
      else
      {
        echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found.'));
      }
    }
    ////////// End Millage Type //////////


    //item model
    public function get_model_options()
    {   
      $data = json_decode(file_get_contents("php://input"));
      $make_id = $data->make_id;
      $data_model_array = $this->api_model->get_item_model_list_active($make_id);
      if ($data_model_array) {
        echo json_encode(array('status' => true, 'result' => $data_model_array));  
      }else{
        echo json_encode(array('status' => true, 'result' => '', 'message' => 'Do not found any model.'));  
      }
    }
    //category dynamic fields
    public function get_item_fields()
    {
        $data = json_decode(file_get_contents("php://input"));
        $category_id = $data->category_id;
        $output_data = [];

        if (isset($data->item_id)) {
            $item_id = $data->item_id;
            $item_category_fields = $this->api_model->get_item_fields_with_data($category_id, $data->item_id);
        }else{
            $item_category_fields = $this->db->order_by('id','asc')->get_where('item_category_fields', ['category_id'=>$category_id])->result_array();
        }
            $item_category_fields_all = $this->db->order_by('id','asc')->get_where('item_category_fields', ['category_id'=>$category_id])->result_array();

        foreach ($item_category_fields as $key => $item_category_field) {
            if($item_category_field['multiple'] == true){
                $item_category_field['all_values'] = json_decode($item_category_field['values'],true);
                if (isset($item_category_field['selected_value']) && !empty($item_category_field['selected_value'])) {
                    if( strpos($item_category_field['selected_value'], ',') !== false ) {
                      $item_category_field['selected_value'] = explode(',', $item_category_field['selected_value']);
                    }
                }
                else{
                    $item_category_field['selected_value'] = '';
                }

            }else{
              $item_category_field['all_values'] = $item_category_field['value'];
            }

            unset($item_category_field['value']);
            unset($item_category_field['values']);
            $output_data[] = $item_category_field;
        }
        
      echo json_encode(array('status' => true, 'result' => $output_data, 'all_fields' => $item_category_fields_all));
    }

    public function isJson($string) {
     json_decode($string);
     return (json_last_error() == JSON_ERROR_NONE);
    }


    public function sellItems()
    {
      $id = validateToken();
      $data = json_decode(file_get_contents("php://input"));

      $category_list = $this->api_model->get_item_category_active();       
      $seller_list = $this->api_model->get_all_sales_user(); 

      if ($data) {
        $item_dynamic_data = $data;    
        $item_data = $data->item; // get basic information 
        unset($item_dynamic_data->item);  // remove basic information form data 
        $items_attachment = array();
        $seller_id = $id;
        $rand_str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $randomKey = $this->generate_string($rand_str);
        $posted_data = array(
        'name' => $item_data->name,
        'detail' => $item_data->detail,
        'status' => $item_data->status,
        'item_status' => $item_data->itemStatus,
        'inspected' => 'no',
        'category_id' => $item_data->categoryId,
        'seller_id' => $seller_id,
        'location' => $item_data->location,
        'mileage' => $item_data->mileage,
        'mileage_type' => $item_data->mileageType,
        'specification' => $item_data->specification,
        'terms' => $item_data->terms,
        'feature' => $item_data->feature,
        'created_on' => date('Y-m-d H:i:s'),
        'created_by' => $seller_id
        );
        // a:
        // $posted_data['registration_no'] = $this->generate_string($rand_str);
        // $reg_no = $this->db->get_where('item', ['registration_no' => $posted_data['registration_no']]);
        // if ($reg_no->num_rows() > 0) {
        //     goto a;
        // }
        if(isset($item_data->registrationCode) && !empty($item_data->registrationCode))
        {
          $posted_data['registration_no'] = $item_data->registrationCode;
        }
        if(isset($item_data->subCategory) && !empty($item_data->subCategory))
        {
          $posted_data['subcategory_id'] = $item_data->subCategory;
        }
        if(isset($item_data->keyword) && !empty($item_data->keyword))
        {
          $posted_data['keyword'] = $item_data->keyword;
        }
        if(isset($item_data->price) && !empty($item_data->price))
        {
          $posted_data['price'] = $item_data->price;
        }
        if(isset($item_data->vinNo) && !empty($item_data->vinNo))
        {
          $posted_data['vin_number'] = $item_data->vinNo;
        }
        if(isset($item_data->year) && !empty($item_data->year))
        {
          $posted_data['year'] = $item_data->year;
        } 
        if(isset($item_data->makeId))
        {
          $posted_data['make'] = $item_data->makeId;
          $posted_data['model'] = $item_data->modelId;    
        }
        $result = $this->api_model->insert_item($posted_data);
        $path = "uploads/items_documents/".$result."/qrcode/";
        // print_r($posted_data);die(); 
        // make path
        if ( !is_dir($path)) {
          mkdir($path, 0777, TRUE);
        } 
        $qrcode_name = $this->generate_code($result,$path, $posted_data);
        if(!empty($qrcode_name))
        {
          $barcode_array = array(
          'barcode' => $qrcode_name
          );
          $this->api_model->update_item($result,$barcode_array);
        }
        foreach ($item_dynamic_data->dynamic as $dynamic_keys => $dynamic_values) {
          $ids_arr[0] = $dynamic_values->id;
          if (isset($dynamic_values->newvalue)) {
            $dynamic_values_new = $dynamic_values->newvalue;
          }else{
            $dynamic_values_new = '';
          }
          if (!empty($dynamic_values_new)) {
            $dynaic_information = array(
            'category_id' => $item_data->categoryId,
            'item_id' => $result,
            'fields_id' => $ids_arr[0],
            'value' => $dynamic_values_new,
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $seller_id
            );
            // print_r($dynaic_information);die();
            $result_info = $this->api_model->insert_item_fields_data($dynaic_information);
          }
        }
        if (!empty($result)) {
          echo json_encode(array('status' => true, 'item_id' => $result, 'message' => 'Item Added Successfully.'));
        }else{
          echo json_encode(array('status' => false, 'message' => 'Item failed to add.'));
        }
          
      }else{
        echo json_encode(array('status' => false, 'message' => 'Item failed to add.'));
      }
    } 

    public function getVehicle()  //// select model and type againt vehicle 
    {
      $id=$this->input->post('id');
      $result= $this->api_model->catList($id);
      if(!empty($result))
      {
        echo json_encode(array('error' => false, 'result' => $result));
      }
      else
      {
        echo json_encode(array('error' => true, 'message'=>'no data found'));
      }
    }

    public function getGeneral()  //// select category general 
    {
      $id=$this->input->post('id');
      $result= $this->api_model->GeneralList($id);
      if(!empty($result))
      {
        echo json_encode(array('error' => false, 'result' => $result));
      }
      else
      {
        echo json_encode(array('error' => true, 'message'=>'No Data Found'));
      }
    }

    public function getFaq()  //// get the faq list 
    {
      $faq_info = $this->api_model->getFaq();
      if (!empty($faq_info)) #
      {
        $faqs= array();
        foreach ($faq_info as $key => $value) 
        {
          $title = json_decode($value['question']);
          $description = json_decode($value['answer']);
          $faqs[$key]['id'] = $value['id'];    
          $faqs[$key]['question']['english'] = $title->english;    
          $faqs[$key]['question']['arabic']= $title->arabic;    
          $faqs[$key]['answer']['english'] = $description->english;    
          $faqs[$key]['answer']['arabic'] = $description->arabic;    
        }
        echo json_encode(array("status" => true, "message" => 'Faq','result'=>$faqs));
      } else {
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'Faq not available'));
      }
    }
    //// get the faq list 
    public function socialLinks()  
    {
      $social = $this->db->get('social_links')->row_array();
      if (!empty($social)) #
      {
        echo json_encode(array("status" => true, "message" => 'social links','result'=>$social));
      } else {
        http_response_code(400);
        echo json_encode(array('status'=>false,'message'=>'Socail media not available'));
      }
    }

    // public function checkDeposit()
    // {
    //   $userId = validateToken();
    //   $data = json_decode(file_get_contents("php://input")); 
    //   $bid_amount= $data->bid_amount;
    //   // $result= $this->api_model->checkDeposit($userId);

    //   $balance=$this->api_model->user_balance($userId);
    //   $admin_trensections = $this->api_model->get_amount_sum($userId);
    //   $result['amount'] = $balance['amount'] + $admin_trensections->amount;
    //   $amount =$result['amount'];
    //   if (!empty($result) &&  $bid_amount <= $result['amount']) {
    //     $blnce = $result['amount'] - $bid_amount;
    //     $this->api_model->updateDeposit($userId,$blnce);
    //     echo json_encode(array('error' => false, 'message' => 'Bid successfully placed','balance'=> $blnce));
    //   } else {   
    //     echo json_encode(array('error' => true,'message'=> 'Insufficient amount ' .$amount .' please deposit amount'));
    //   }
      
    // }

    public function deposit()
    {
      $userId= validateToken();
      if (!empty($userId)) {
        echo "string";
      }
      else
      {
        echo "stringk";
      }
    }

    public function BankTransfer()  //// upload the deposit slip and admin will receive the email
    {  
      $userId = validateToken();  
      $payload_info = getPayload();              
      if(isset($_FILES['documents']['name'])){
        $data_for_delete_files = $this->api_model->users_listing($userId); // fetch data 
        if(!empty($data_for_delete_files))
        {
          $old_data = FCPATH .'uploads/users_documents/'.$userId.'/'.$data_for_delete_files[0]['picture'];
          $picture = $data_for_delete_files[0]['picture'];
          $path = "uploads/users_documents/".$userId."/";
          // make path
          if ( !is_dir($path)) {
              mkdir($path, 0777, TRUE);
          }
          // If profile picture is selected
          $files = '';                                 
          if(!empty($_FILES)){
            $files .= $this->uploadFiles_with_path_docs($_FILES,$path);
            $profile_pic_array = array(
            'documents' => $files
            );
            echo json_encode(array("status" => true, "message" => 'Deposit slip uploaded'));
            $user_array =  $this->api_model->update_user($userId,$profile_pic_array);
            if(is_dir($path) && file_exists($picture)){
              unlink($old_data); 
            } 
            $email_message='Deposit';
            $email_messagee='deposit slip uploaded by user';
            $to='it@armsgroup.ae';
            $this->email->to($to);
            $this->email->subject($email_message);
            $this->email->message($email_messagee);
            $this->email->send();
          } else {
            echo json_encode(array("status" => false, "message" => 'doument not uploaded'));
          }
        } else {
          echo json_encode(array("status" => false, "message" => 'Error'));
        }
            
      } else {
        return false;
      }  
    }

    public function categoryCount()
    {
      $data = json_decode(file_get_contents("php://input"));
      $id=$data->id;
      $data=0;
      $result=$this->api_model->categoryCount($id);
      if (!empty($result)) {
        echo json_encode(array("status" => true, 'result'=>$result));
      }
      else
      {
        echo json_encode(array("status" => false,'result'=>$data));
      }
    }

       public function palceBids()
        {
          $id= validateToken(); 
          $posted_data = json_decode(file_get_contents("php://input") , true);
          $data = array();
          $output = array();
          $item = $this->db->get_where('auction_items', ['item_id' => $posted_data['item_id']])->row_array();
          $data['user_id'] = $id;
          $data['date'] = date('Y-m-d', time());
          $data['item_id'] = $posted_data['item_id'];
          // $data['item_id']     = $item['item_id'];
          $data['start_price'] = $item['bid_start_price'];
          $data['end_price']   = $posted_data['bid_amount'];
          $data['auction_id']  = $item['auction_id'];
          $data['buyer_id']    = $id;
          $data['bid_amount']  = $posted_data['bid_amount'];
          $data['bid_time']    = date('Y-m-d H:i:s', time());
          $data['bid_status']  = 'pending';
          $insert = $this->db->insert('bid',$data);

          if ($insert) {
            http_response_code(200);
             echo json_encode(array('status'=>true,'message'=>'Bid successful.'));
          }
          else
          { 
            http_response_code(200);
            echo json_encode(array('status'=>true,'message'=>'Bid failed'));
          }
        }

    public function userDeposite()
    {
      $id = validateToken();
      $data = json_decode(file_get_contents("php://input"));
      $date = $data->date;
      $user = $this->db->get_where('users', ['id' => $id])->row_array();
      if (empty($user['mobile']) || empty($user['email']) || empty($user['address']) || empty($user['po_box']) || empty($user['fname']) || empty($user['lname']) || empty($user['city']) || empty($user['city']) ) {
           echo json_encode(array("status" => true,'msg'=>'please update your profile first'));
           echo json_encode(array('msg'=>'success','response' => 'package_payment_shipping_failed'));
      }else{
        // 3. load PayTabs library
        $merchant_email='it@armsgroup.ae';
        $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
        $merchant_id='10050328';

        $params = [
          'merchant_email'=>$merchant_email,
          'merchant_id'=>$merchant_id,
          'secret_key'=>$secret_key
        ];           
        $this->load->library('Paytabs',$params);
        $order_id = $id;
        $invoice = [
          "site_url" => "https://pa.yourvteams.com",
          "return_url" => base_url('customer/dashboard'),
          "title" =>'customer',
          "cc_first_name" => 'customer',
          "cc_last_name" => 'customer',
          "cc_phone_number" => $user['mobile'],
          "phone_number" => $user['mobile'],
          "email" => $user['email'],
          //"products_per_title" => "MobilePhone || Charger || Camera",
          "products_per_title" => 'pioneer auctoin deposit', //$order['project_name'],
          //"unit_price" => "12.123 || 21.345 || 35.678 ",
          "unit_price" =>$data->amount,
          //"quantity" => "2 || 3 || 1",
          "quantity" => "1",
          "other_charges" => "0.0",
          "payment_type" => "mada",
          "amount" =>$data->amount,
          "discount" => "0.0",
          "currency" => "SAR",
          "reference_no" => $id,
          "billing_address" => $user['address'],
          // "billing_address" => 'UAE',
          "city" =>$user['city'],
          "state" => $user['city'],
          // "state" => 'UAE',
          // "postal_code" =>'54000',
          "postal_code" =>$user['po_box'],
          "country" => 'SAU',
          "shipping_first_name" => $user['fname'],
          "shipping_last_name" => $user['lname'],
          // "address_shipping" => $user['address'],
          "address_shipping" => 'UAE',
          "city_shipping" => $user['city'],
          // "city_shipping" => 'Lahore',
          "state_shipping" => $user['city'],
          // "state_shipping" => 'UAE',
          "postal_code_shipping" => $user['po_box'],
          // "postal_code_shipping" => '54000',
          "country_shipping" => 'SAU',
          // "country_shipping" => 'SAU',
          "msg_lang" => "English",
          "cms_with_version" => "pioneer deposit"
        ];
        $response = $this->paytabs->create_pay_page($invoice);
        if(isset($response->response_code) && $response->response_code == "4012"){
          $transaction_data['transaction_id'] = $response->p_id;
          $transaction_data['user_id'] = $id;
          $transaction_data['amount'] = $data->amount;
          $transaction_data['status'] = 'active';
          $transaction_data['payment_type'] = 'card';
          $transaction_data['deposit_type'] = 'permanent';
          $transaction_data['created_on'] = date('Y-m-d H:i:s');
          $this->db->insert('auction_deposit', $transaction_data);
          echo json_encode(array('msg'=>'success','response' => 'package_recharge_success','redirect'=> $response->payment_url,'date'=>$date));
        }else{
          // $this->session->set_flashdata('error', $this->lang->line('package_payment_shipping_failed'));
          echo json_encode(array("status" => false,'msg'=>'package_payment_shipping_failed'));
          // echo json_encode(array('msg'=>'error','response' => $this->lang->line('package_payment_shipping_failed'),'redirect'=> base_url('customer/deposit')));
        }
      }
    }

    public function addBankSlip()
    {
      $id = validateToken();
      // $posted_data = $this->input->post('deposit_date');
      if ($this->input->post()) {
        $posted_data = $this->input->post();
        if(!empty($_FILES['slip']['name']))
        {
          $path = './uploads/bank_slips/';
          if ( ! is_dir($path)) 
          {
            mkdir($path, 0777, TRUE);
          }
          $config['upload_path'] = $path;
          $config['allowed_types'] = 'ico|png|jpg|jpeg|pdf|doc|docx|txt|xls';
          $uploaded_file_ids = $this->files_model->upload('slip', $config);
          if (isset($uploaded_file_ids['error'])) {
            $this->session->set_flashdata('error', $uploaded_file_ids['error']);
            echo json_encode(array('msg'=>'error','message' => $uploaded_file_ids['error']));
            exit();
          }
          $posted_data['user_id'] = $id;
          $posted_data['slip'] = implode(',', $uploaded_file_ids);
          $posted_data['created_on'] = date('Y-m-d H:i:s');
          $result = $this->db->insert('bank_deposit_slip', $posted_data);
          $inserted_id = $this->db->insert_id();
          if ($result) {
              echo json_encode(array('status'=>true,'message' => 'Slip has been added seccessfully.'));
          } else {
              echo json_encode(array('status'=>false,'message' => 'Slip has been failed to add.'));
          }       
        }
      } else {
        echo json_encode(array('status'=>false,'message' => 'slip not added'));
      }
    } 


    public function bankDetail()
    {
      $data= $this->db->get('bank_info')->row_array();
      $data2 = $this->db->get('contact_us')->row_array();
      $email = $data2['email'];
      $fax = $data2['fax'];
      if (!empty($data)) 
      {
        echo json_encode(array('status'=>true,'result' => $data,'email'=>$email,'fax'=>$fax));
      }
      else
      {
        echo json_encode(array('status'=>true,'message' => 'no data found'));
      }
    }

    private function generate_string($input, $strength = 6) 
    {
      $input_length = strlen($input);
      $random_string = '';
      for($i = 0; $i < $strength; $i++) 
      {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
      }
      return $random_string;
    }

    public function generate_code($item_id,$path, $item_details_array= array())
    {
      $this->load->library('ciqrcode');
      // how to save PNG codes to server 
      $tempDir = FCPATH .  $path;
      $tempDir_url = base_url()."uploads/items_documents/";
      $codeContents = json_encode($item_details_array); 
       
      // we need to generate filename somehow,  
      // with md5 or with database ID used to obtains $codeContents... 
      $fileName = $item_id.'_'.md5($codeContents).'.png'; 
      $pngAbsoluteFilePath = $tempDir.$fileName; 
      $urlRelativeFilePath = $tempDir_url.$fileName; 
      // generating 
      if (!file_exists($pngAbsoluteFilePath)) { 
        QRcode::png($codeContents, $pngAbsoluteFilePath); 
        return $fileName;
      } else { 
        return $fileName;
      } 

    }






















    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////inscpectoin app apis //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function loginAppraiser()
    {
      // $config['csrf_protection'] = TRUE;
      $data = json_decode(file_get_contents("php://input"));
      if (!empty($data->username) && !empty($data->password)) {
        $username = $data->username;
        $dataa=$data->password;
        // $password = htmlspecialchars(strip_tags(md5($data->password)));
        $password = hash("sha256",$data->password);;
        $result = $this->api_model->appraise_login_check($username,$password);
        // verify email is valid or not
        if (!empty($result)) {
          $status = $result[0]->status;
          // check if user is active and check if paid with status is 1
          if ($status == '1') {
            // Generate JWT Token with payload 
            // payload have expire time information with user role id and user id 
            $paylod = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() + (120*60*24),
            'user_id' => $result[0]->id,
            'email' => $result[0]->email,
            'mobile' => $result[0]->mobile,
            'user_role_id' => $result[0]->role,
            ];
            $token = Jwt::encode($paylod, SECRETE_KEY);
            $user_list = $this->api_model->get_login_user($result[0]->id);
            // $_SESSION['loggedin_user'] = $loginuser;
            $data = array(
              // 'user_id' => $result[0]->id,
              'token' => $token,
              'data' => $user_list
            );
            // $data2['picture'] = $this->db->select('name as picture')->get_where('files',['id'=>$user_list['picture']])->row_array();
            // $data = array_merge($data,$data2['picture']);
            // print_r($data);
            http_response_code(200);
            echo json_encode(array('status'=>true,'message'=>'Logged in successfully.','result'=> $data)); 
          }
        }else{
          http_response_code(404);
          echo json_encode(array('status'=>false,'message'=>'Unauthorized user.'));
          // echo "error";   
        }
      }else{
          echo json_encode(array('status'=>false,'message'=>'Data is missing.'));
      }  
    }

    public function insertItem()
    {
      $userid = validateToken();
      $payload_info = getPayload();
      $data = json_decode(file_get_contents("php://input"));
      // $condition = $this->input->post('signature');
      // print_r($condition);die('nnn');
      $item = $data->item;
      $location = $data->location;
      $dynamic_feilds = $data->dynamic;

      ////insert data in item table
      //for english and arabic 
      $item_name = [
        'english' => $item->name_english,
        'arabic' => $item->name_arabic,
      ];
      if (empty($item->detail_english)) {
            $engDescription = array();
            $arabicDescription = array();
            // print_r($dynamic_feilds);die();
            foreach ($dynamic_feilds as $key => $value) {
                $dataValues = array();
                $fields = array();
                $ids = $value->id;
                // $fields = $this->db->get_where('item_category_fields', ['id' => $ids])->row_array();
                if (!empty($value->newvalue)) {
                    if (isset($value->all_values) && !empty($value->all_values)) {
                        $fields['values'] = $value->all_values;  
                        $fields['data-id'] = $value->id;
                        foreach ($fields['values'] as $key => $options) {
                            $selected_values = explode(',', $value->newvalue);
                            if (is_array($selected_values)) {
                                if (in_array($options->value, $selected_values)) {
                                    $dataValues[] = $options->label;
                                }
                            } else {
                                if ($options->value == $value->newvalue) {
                                    $dataValue = $options->label;
                                }
                            }
                        }
                    }else{
                        $dataValue = $value->newvalue;
                    }
                    if (!empty($dataValues)) {
                        foreach ($dataValues as $key => $dataVal) {
                            $exp_str = explode('|', $dataVal);
                            $engDescription[] =  $exp_str[0];
                            if (isset($exp_str[1])) {
                                $arabicDescription[] = $exp_str[1];
                            }
                        }
                    } else {
                        $exp_str = explode('|', $dataValue);
                        $engDescription[] =  $exp_str[0];
                        if (isset($exp_str[1])) {
                            $arabicDescription[] = $exp_str[1];
                        }
                    }
                }
            }
            $endes = implode(',', $engDescription);
            $ardes = implode(',', $arabicDescription);
            $item_detail = [
                'english' => $endes,
                'arabic' => $ardes,
            ];
        } else{
            $item_detail = [
                'english' => $item->detail_english,
                'arabic' => $item->detail_arabic,
            ];
        }
       $item_terms = [
        'english' => $item->terms_english,
        'arabic' => $item->terms_arabic,
      ];
      $item_comment = [
        'english' => $item->comment_english,
        'arabic' => $item->comment_arabic,
      ];

      $insert_item =array(
          // 'name' => $item->name,
          'name' => json_encode($item_name),
          // 'detail' => $item->detail,
          'detail' => json_encode($item_detail),
          'terms' => json_encode($item_terms),
          // 'terms' => $item->terms,
          'status' => $item->status,
          'item_status' => $item->item_status,
          'specification' => $item->specification,
          'inspected' => 'no',
          'mileage_type' => $item->mileage_type,
          'category_id' => $item->category_id,
          'year' => $item->year,
          'seller_id' => $item->seller_id,
          'feature' => $item->feature,
          'location' => $location,
          'created_on' => date('Y-m-d H:i:s'),
          'updated_on' => date('Y-m-d H:i:s'),
          'created_by' => $userid,
          'lat' => $item->lat,
          'lng' => $item->lng,
          'registration_no' => $item->registration_no,
          'subcategory_id' => $item->subcategory_id,
          'keyword' => $item->keyword,
          'price' => $item->price,
          'mileage' => $item->mileage,
          'vin_number' => $item->vin_number,
          'model' => $item->model,
          'make' => $item->make,
          // 'comment' => $item->comment
          'comment' => json_encode($item_comment),
        );
        $result = $this->db->insert('item',$insert_item);
        if($result){
            $item_id = $this->db->insert_id();
            $category_id = $insert_item['category_id'];

            // QR Code
             $path = "uploads/items_documents/".$item_id."/qrcode/";
              // make path
              if ( !is_dir($path)) {
                mkdir($path, 0777, TRUE);
              } 
              $qrcode_name = $this->generate_code($item_id,$path, ['id'=>$item_id]);
              if(!empty($qrcode_name))
              {
                $barcode_array = array(
                'barcode' => $qrcode_name
                );
                $this->db->update('item',$barcode_array,['id'=> $item_id]);
              }


            //Handle dynamic fields
            if($dynamic_feilds){
                foreach ($dynamic_feilds as $key => $field) {
                    $field_id = $field->id;
                    $val = $field->newvalue;
                    if(empty($val)){
                        $val = NULL;
                    }
                    /*if( strpos($val, ',') !== false ){
                        $val = explode(',', $val);
                        $val = json_encode($val);
                    }
*/
                    $insert_dynamic_field = [
                        'category_id' => $category_id,
                        'item_id' => $item_id,
                        'fields_id' => $field_id,
                        'value' => $val,
                        'updated_on' => date('Y-m-d H:i:s'),
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $userid,
                        'updated_by' => $userid
                    ];
                    $this->db->insert('item_fields_data',$insert_dynamic_field);
                }

            }
            $output = ['status' => true, 'message' => 'Item added successfully.', 'item_id' => $item_id];
        }else{
            $output = ['status' => false, 'message' => 'Item is failed to add.'];
        }
        echo json_encode($output);

        //// Dynamic feilds

    }

    public function updateItem()
    {
        $userid = validateToken();
        $payload_info = getPayload();
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->item_id) && empty($data->item_id)) {
            $output = ['status' => false, 'message' => 'Item is failed to update.'];
            echo json_encode($output);
            exit;
        }
        $item = $data->item;
        $location = $data->location;
        $sub_cat = $item->subcategory_id;
        $dynamic_feilds = $data->dynamic;

        /// Arabic and English details
        $item_name = [
            'english' => $item->name_english,
            'arabic' => $item->name_arabic,
        ];
        if (empty($item->detail_english)) {
            $engDescription = array();
            $arabicDescription = array();
            // print_r($dynamic_feilds);die();
            foreach ($dynamic_feilds as $key => $value) {
                $dataValues = array();
                $fields = array();
                $ids = $value->id;
                // $fields = $this->db->get_where('item_category_fields', ['id' => $ids])->row_array();
                if (!empty($value->selected_value)) {
                    $fields['values'] = $value->all_values;  
                    $fields['data-id'] = $value->id;
                    if (!empty($fields['values'])) {
                        foreach ($fields['values'] as $key => $options) {
                            $selected_values = explode(',', $value->selected_value);
                            if (is_array($selected_values)) {
                                if (in_array($options->value, $selected_values)) {
                                    $dataValues[] = $options->label;
                                }
                            } else {
                                if ($options->value == $value->selected_value) {
                                    $dataValue = $options->label;
                                }
                            }
                        }
                    }else{
                        $dataValue = $value->selected_value;
                    }
                    if (!empty($dataValues)) {
                        foreach ($dataValues as $key => $dataVal) {
                            $exp_str = explode('|', $dataVal);
                            $engDescription[] =  $exp_str[0];
                            if (isset($exp_str[1])) {
                                $arabicDescription[] = $exp_str[1];
                            }
                        }
                    } else {
                        $exp_str = explode('|', $dataValue);
                        $engDescription[] =  $exp_str[0];
                        if (isset($exp_str[1])) {
                            $arabicDescription[] = $exp_str[1];
                        }
                    }
                }
            }
            $endes = implode(',', $engDescription);
            $ardes = implode(',', $arabicDescription);
            $item_detail = [
                'english' => $endes,
                'arabic' => $ardes,
            ];
        } else{
            $item_detail = [
                'english' => $item->detail_english,
                'arabic' => $item->detail_arabic,
            ];
        }
        $item_terms = [
            'english' => $item->terms_english,
            'arabic' => $item->terms_arabic,
        ];
        $item_comment = [
            'english' => $item->comment_english,
            'arabic' => $item->comment_arabic,
        ];

        ////insert data in item table
        $update_item =array(
            'name' => json_encode($item_name),
            'detail' => json_encode($item_detail),
            'terms' => json_encode($item_terms),
            'status' => $item->status,
            'item_status' => $item->item_status,
            'inspected' => 'no',
            'specification' => $item->specification,
            'category_id' => $item->category_id,
            'mileage_type' => $item->mileage_type,
            'year' => $item->year,
            'seller_id' => $item->seller_id,
            'subcategory_id' => $item->subcategory_id,
            'feature' => $item->feature,
            'location' => $location,
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $userid,
            'lat' => $item->lat,
            'lng' => $item->lng,
            'registration_no' => $item->registration_no,
            'keyword' => $item->keyword,
            'mileage' => $item->mileage,
            'price' => $item->price,
            'vin_number' => $item->vin_number,
            'make' => $item->make,
            'model' => $item->model,
            'comment' => json_encode($item_comment),
        );
          // if (!empty($sub_cat)) {
          //   $update_item =array(
          //     'subcategory_id' =>$item->subcategory_id,
          //   );
          // }


        // if(!empty($data->make)){
        //   $update_item['make'] = $item->make;
        //   $update_item['model'] = $item->model; 
        //   echo "echoooooooo";
        //   print_r($update_item['model']);
        // }
      // if (isset($data->subcategory_id) && !empty($data->subcategory_id)) {
      //    $update_item['subcategory_id'] => $item->subcategory_id;
      // }

        $result = $this->db->update('item',$update_item, ['id' => $data->item_id]);
        if($result){
            $item_id = $data->item_id;
            // $category_id = $update_item['category_id'];
            // QR Code
            $path = "uploads/items_documents/".$item_id."/qrcode/";
            // make path
            if ( !is_dir($path)) {
                mkdir($path, 0777, TRUE);
            } 
            $qrcode_name = $this->generate_code($item_id,$path, ['id'=>$item_id]);
            if(!empty($qrcode_name))
            {
                $barcode_array = array(
                    'barcode' => $qrcode_name
                );
                $this->db->update('item',$barcode_array,['id'=> $item_id]);
            }
            //Handle dynamic fields
            if($dynamic_feilds){
                foreach ($dynamic_feilds as $key => $field) {
                    $field_id = $field->id;
                    $val = $field->selected_value;
                    // if(empty($val)){
                    //     $val = NULL;
                    // }
                    // if(is_array($val)){
                    //     $val = json_encode($val);
                    // } 
                    $insert_dynamic_field = [
                        'value' => $val,
                        'updated_on' => date('Y-m-d H:i:s'),
                        'created_on' => date('Y-m-d H:i:s')
                    ];
                        $this->db->update(
                            'item_fields_data',
                            $insert_dynamic_field,
                            ['item_id' => $item_id, 'fields_id' => $field_id]
                        );
                }
                    // print_r($dynamic_feilds);die('kjkkkkkk');
            }
            $output = ['status' => true, 'message' => 'Item updated successfully.','item_id' => $item_id];
        }else{
            $output = ['status' => false, 'message' => 'Item is failed to update.'];
        }
        echo json_encode($output);
    }


    public function pagination_items(){
        $this->load->library('pagination');
        $data = json_decode(file_get_contents("php://input"));
        $index = $data->index;
        $limit = 10;
        // $total = count($this->api->get_item_list($data['auction_id'], 0, 0, $item_ids));
        $total = $this->db->select('count(*) as allcount')->limit($limit)->get('item')->result();;
        print_r($total);die();
            // if (isset($index)) {
            //     $limit = $index;
            // } else {
            //     $limit = 10;
            // }

            $config['total_rows'] = $total;
            $config['per_page'] = $limit;

            $this->pagination->initialize($config); // pagination created 
            $pagination_links = $this->pagination->create_links();
    }

    //update Images
    public function upload_images()
    {
      $data = json_decode(file_get_contents("php://input"));
      $result = $this->input->post('item_id');
      // $result = $_GET['item_id'];
      if(isset($_FILES['images']['name']) && !empty($result)){
        $result_array = $this->api_model->get_item_byid($result);
        if(isset($result_array) && !empty($result_array))
        {
          $ids_concate = '';
          $itemsIds_array = explode(',' ,$result_array[0]['item_images']);
          if(!empty($itemsIds_array) && !empty($result_array[0]['item_images']))
          {
            $ids_concate = $result_array[0]['item_images'].",";
          }
        }
        $path = "uploads/items_documents/".$result."/";
          // make path
        if ( !is_dir($path)) {
          mkdir($path, 0777, TRUE);
        }
        // If profile picture is selected
        $files = '';                                 
        if(!empty($_FILES)){
          $config['allowed_types'] = 'ico|png|jpg|jpeg';
          $config['upload_path'] = $path;
          $sizes = [
            // ['width' => 1184, 'height' => 661],
            // ['width' => 191, 'height' => 120], // for details page carusal icons 
            // ['width' => 305, 'height' => 180], // for gallery page 
            // ['width' => 294, 'height' => 204], // for auction page
            // ['width' => 349, 'height' => 207], // for auction page
            ['width' => 37, 'height' => 36] // for table listing
          ];
          $uploaded_file_ids = $this->files_model->upload('images', $config, $sizes);
          if (isset($uploaded_file_ids['error'])) {
            $img_msg = $uploaded_file_ids['error'];
          } else {
            $uploaded_file_ids = implode(',', $uploaded_file_ids);
            $item_attachments_array = array(
              'item_images' => $ids_concate.$uploaded_file_ids
            );
            $item_array =  $this->api_model->update_item($result,$item_attachments_array);
            $img_msg = 'Images uploaded successfully.';
            echo json_encode(array('status' => true, 'message' => $img_msg , 'item_id' => $result));
          }
        } else {
          $img_msg = 'Images not uploaded';
          echo json_encode(array('status' => false, 'message' => $img_msg));
        }

      }else{
          echo json_encode(array('status' => false, 'message' => 'Image not selected.'));

      } 
    }

    //update item document
    public function upload_item_documents()
    {
      //upload documents
      // $result = $this->input->post('item_id');
      $result = $_GET['item_id'];
      if(isset($_FILES['documents']['name']) && !empty($result)){
      
        $result_array = $this->api_model->get_item_byid($result);
        if(isset($result_array) && !empty($result_array))
        {
          $ids_concate = '';
          $itemsIds_array = explode(',' ,$result_array[0]['item_attachments']);
          if(!empty($itemsIds_array) && !empty($result_array[0]['item_attachments']))
          {
            $ids_concate = $result_array[0]['item_attachments'].",";
          }
        }
        $path = "uploads/items_documents/".$result."/";
          // make path
        if ( !is_dir($path)) {
          mkdir($path, 0777, TRUE);
        }
        $files = '';                                 
        if(!empty($_FILES)){
          $config['allowed_types'] = 'ico|png|jpg|pdf|ai|psd|eps|indd|doc|docx|ppt|pptx|xlsx|xls';
          $config['upload_path'] = $path;
          $uploaded_file_ids = $this->files_model->upload('documents', $config);
          if (isset($uploaded_file_ids['error'])) {
            $doc_msg = $uploaded_file_ids['error'];
          } else {
            $uploaded_file_ids = implode(',', $uploaded_file_ids);
            $item_attachments_array = [
              'item_attachments' => $ids_concate.$uploaded_file_ids
            ];
            $item_array =  $this->api_model->update_item($result,$item_attachments_array);
            $doc_msg = 'Document uploaded successfully.';
            echo json_encode(array('status' => true, 'message' => $doc_msg , 'item_id' => $result));
          }
        } else {
          $doc_msg = 'Document not uploaded';
          echo json_encode(array('status' => false, 'message' => $doc_msg));
        }

      }else{
        echo json_encode(array('status' => false, 'message' => 'Document not selected.'));
        
      }  
      //end upload documents 
    }

    //update signature old
    // public function upload_signature()
    // {
    //   //upload signature
    //   $result = $_GET['item_id'];
    //   $directory = $_GET['directory_name'];
    //   $feild_name = $_GET['feild_name'];

    // if(!empty($_FILES['document']['name'])){
    //     $picture ='';
    //     // $result_array = $this->api_model->get_item_byid($result);
    //     $result_array = $this->db->get_where('item',['id' => $result])->result_array();

    //     if(isset($result_array) && !empty($result_array[0]['item_signature']))
    //     {
    //       $picture = $result_array[0]['item_signature'];
    //       $old_data = FCPATH .'uploads/users_documents/'.$result.'/'.$result_array[0]['item_signature'];
    //     }

    //     if(isset($result_array) && !empty($result_array[0]['item_images']))
    //     {
    //       $picture = $result_array[0]['item_images'];
    //       $old_data = FCPATH .'uploads/users_documents/'.$result.'/'.$result_array[0]['item_images'];
    //     }

    //     if(isset($result_array) && !empty($result_array[0]['item_attachments']))
    //     {
    //       $picture = $result_array[0]['item_attachments'];
    //       $old_data = FCPATH .'uploads/users_documents/'.$result.'/'.$result_array[0]['item_attachments'];
    //     }

    //     if(isset($result_array) && !empty($result_array[0]['item_test_report']))
    //     {
    //       $picture = $result_array[0]['item_test_report'];
    //       $old_data = FCPATH .'uploads/users_documents/'.$result.'/'.$result_array[0]['item_test_report'];
    //     }

    //     $path = "uploads/".$directory."/".$result."/";
    //     if (!is_dir($path)) 
    //     {
    //         mkdir($path, 0777, TRUE);
    //     }
    //     $newfilename = date('dmYHis');
    //     $file_name = basename($newfilename.$_FILES['document']['name']).'.jpg';
    //     $target_path = $path.''.$file_name;

    //     if (move_uploaded_file($_FILES['document']['tmp_name'], $target_path)) {
    //       $item_attachments_array = array(
    //         $feild_name => $file_name
    //       );
    //       $item_array =  $this->api_model->update_item($result,$item_attachments_array);
    //         $docs_msg = 'Documents updated successfully.';
    //         if(is_dir($path) && file_exists($picture)){
    //           unlink($old_data); 
    //         }
    //       echo json_encode(array('status' => true, 'message' => $docs_msg));
    //     }else{
    //       $docs_msg = 'Documents not uploaded';
    //       echo json_encode(array('status' => false, 'message' => $docs_msg));
    //     }
    // } else{
    //     echo json_encode(array('status' => false, 'message' => 'Documents not found.'));
    //   }
    // }


    //update signature old
    // public function upload_condition()
    // {
    //   //upload signature
    //   $result = $_GET['item_id'];
    //   if(!empty($_FILES['condition']['name'])){
    //     $picture ='';
    //     $result_array = $this->api_model->get_item_byid($result);
    //     if(isset($result_array) && !empty($result_array[0]['item_condition']))
    //     {
    //       $picture = $result_array[0]['item_condition'];
    //       $old_data = FCPATH .'uploads/users_documents/'.$result.'/'.$result_array[0]['item_condition'];
    //     }
    //     $path = "uploads/items_documents/".$result."/";
    //     if (!is_dir($path)) 
    //     {
    //         mkdir($path, 0777, TRUE);
    //     }
    //     $newfilename = date('dmYHis');
    //     $file_name = basename($newfilename.$_FILES['condition']['name']).'.jpg';
    //     $target_path = $path.''.$file_name;
    //     if (move_uploaded_file($_FILES['condition']['tmp_name'], $target_path)) {
    //       $item_attachments_array = array(
    //         'item_condition' => $file_name
    //       );
    //       $item_array =  $this->api_model->update_item($result,$item_attachments_array);
    //         $signature_msg = 'Condition updated successfully.';
    //         if(is_dir($path) && file_exists($picture)){
    //           unlink($old_data); 
    //         }
    //       echo json_encode(array('status' => true, 'message' => $signature_msg));
    //     }else{
    //       $signature_msg = 'Condition not uploaded';
    //       echo json_encode(array('status' => false, 'message' => $signature_msg));
    //     }
    //   } else{
    //     echo json_encode(array('status' => false, 'message' => 'Condition not found.'));
    //   }
    // }


    public function items_multiple_docs()
    { 
        $data = $this->input->post();
        $sig = $this->input->post('signature');
        $itemId = $this->input->post('item_id');
        $error = '';
        $condition = isset($data['condition']) ? $data['condition'] : '';
        // explode an base 64 array to sting
        if (isset($sig) && !empty($sig)) {    
            $sig_str = explode(',', $sig);
            $sig_img = base64_decode($sig_str[1]);
            $output_file = $_SERVER['DOCUMENT_ROOT']."/uploads/items_documents/".$itemId."/";
            if (file_exists($output_file.'sig.png')) {
                unlink($output_file.'sig.png');
            }
            // Get server path
            if (!is_dir($output_file)) {
                mkdir($output_file, 0777, TRUE);
            } 
            file_put_contents($output_file.'sig.png', $sig_img);
        }

        // condition report 
        if (isset($condition) && !empty($condition)) {    
            $condition_str = explode(',', $condition);
            $condition_img = base64_decode($condition_str[1]);
            $output_file = $_SERVER['DOCUMENT_ROOT']."/uploads/items_documents/".$itemId."/";
            if (file_exists($output_file.'condition.png')) {
                unlink($output_file.'condition.png');
            }
            if (!is_dir($output_file)) {
                mkdir($output_file, 0777, TRUE);
            } 
            file_put_contents($output_file.'condition.png', $condition_img);
        }


        $item_docs_array = [];
        /// for images 
        if(isset($data['images']) && !empty($data['images'])){
            //upload multiple base64 images through files model
            $path64 = "/uploads/items_documents/".$itemId."/";
            $sizes = [
             ['width' => 37, 'height' => 36]
            ];

            $image_ids = $this->files_model->base64_multiupload($data['images'], $path64, $sizes);
            $old_img_ids = $this->db->get_where('item',['id' => $itemId])->row_array();
            if (isset($image_ids['error'])) {
                $error = $image_ids['error'];
            } else {
              $imgs_ids = implode(',', $image_ids);
              $item_docs_array['item_images'] = $imgs_ids;
              if(empty($old_img_ids['item_images'])){
                  $item_docs_array['item_images'] = $imgs_ids;
              }else{
                  $item_docs_array['item_images'] = $old_img_ids['item_images'].",".$imgs_ids;
              }
            }
        }
        ///for documents 
        if (isset($_FILES['otherDocuments']) && strlen( $_FILES['otherDocuments']['name'][0] ) > 0) {
            // print_r($_FILES['otherDocuments']);die();
            $result_array = $this->api_model->get_list_item($itemId);
            if(isset($result_array) && !empty($result_array)) {
                if(empty($result_array[0]['item_attachments']) ) {
                    $ids_concate = $result_array[0]['item_attachments'];
                }else{
                    $ids_concate = $result_array[0]['item_attachments'].",";   
                }
            }
            $path = "uploads/items_documents/".$itemId."/";
            if ( !is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $config['upload_path'] = $path;
            $config['allowed_types'] ='*';
            $type = 'otherDocuments';
            $uploaded_file_ids = $this->files_model->multiUpload($type, $config);
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
            } else {
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                // print_r($item_docs_array['item_attachments']);die('other');
                $item_docs_array['item_attachments'] = $ids_concate.$uploaded_file_ids;
            }
        }
        /// for item test report
        if (isset($_FILES['testDocument']) && strlen( $_FILES['testDocument']['name'][0] ) > 0) {
            $result_array = $this->api_model->get_list_item($itemId);

            if(isset($result_array) && !empty($result_array)) {
            //$docIds_array = explode(',' ,$result_array[0][$column_name]);
                if(empty($result_array[0]['item_test_report']) ) {
                    $ids_concate = $result_array[0]['item_test_report'];
                }else{
                    $ids_concate = $result_array[0]['item_test_report'].",";   
                }
            }

            $ids_concate = '';
            $path = "uploads/items_documents/".$itemId."/";
            if ( !is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $config['upload_path'] = $path;
            // $config['allowed_types'] ='gif|png|jpg|jpeg|pdf|doc|docx|ppt|pptx|xlsx|xls|txt|word';
            $config['allowed_types'] ='*';
            $type = 'testDocument';
            $uploaded_file_ids = $this->files_model->multiUpload($type, $config);
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
            } else {
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $item_docs_array['item_test_report'] = $ids_concate.$uploaded_file_ids;
            }
        }
        if (!empty($item_docs_array['item_test_report']) || !empty($item_docs_array['item_images']) || !empty( $item_docs_array['item_attachments'])) {
            $this->db->update('item',$item_docs_array,['id' => $itemId]);
            echo json_encode(array("status" => true, "message" => 'Documents added successfully.'));
        } else {
            echo json_encode(array("status" => false, "message" => $error));
        }
    }


    //delete item documents
    public function delete_item_documents()
    {
      $userid = validateToken();
      $data = json_decode(file_get_contents("php://input"));
      $itemId = $data->itemId;
      $attach_name = $data->file_to_be_deleted;
      // $attach_name = $this->input->post('file_to_be_deleted');
      $file_array = $this->files_model->get_file_byName($attach_name);
      if(isset($file_array) && !empty($file_array))
      {
        $result_array = $this->api_model->get_item_byid($itemId);
        if(isset($result_array) && !empty($result_array)){
        $itemsIds_array = explode(',' ,$result_array[0]['item_attachments']);
            if(!empty($itemsIds_array))
            {
                $str = $result_array[0]['item_attachments'];
            }
        }
        $updated_str = $this->removeItemString($str, $file_array[0]['id']);
        $update = [
          'item_attachments' => $updated_str,
          'updated_by' => $userid
        ];
        $update_item_row = ($this->api_model->update_item($itemId,$update)) ? 'true' : 'false';
      }
      $path = FCPATH .  "uploads/items_documents/".$itemId."/";
      $result = $this->files_model->delete_by_name($attach_name,$path);
      if($result)
      {
        echo json_encode(array('status' => true, 'item_id' => $itemId, 'message' => 'File has been deleted successfully.'));
        echo json_encode(array('status' => false, 'message' => 'File has been failed to delete.'));   
      }
        
    }

    public function delete_item_image($itemId)
    {
      $userid = validateToken();
      $data = json_decode(file_get_contents("php://input"));
      $itemId = $data->itemId;
      $attach_name = $data->file_to_be_deleted;
      $file_array = $this->files_model->get_file_byName($attach_name);
      if(isset($file_array) && !empty($file_array))
      {

      $result_array = $this->api_model->get_item_byid($itemId);
          if(isset($result_array) && !empty($result_array))
          {
              $itemsIds_array = explode(',' ,$result_array[0]['item_images']);

              if(!empty($itemsIds_array))
              {
                  $str = $result_array[0]['item_images'];
              }
          }
          $updated_str = $this->removeItemString($str, $file_array[0]['id']);
          $update = [
              'item_images' => $updated_str,
              'updated_by' => $userid
          ];
          $result_update = ($this->api_model->update_item($itemId,$update)) ? 'true' : 'false';
      }
      $path = FCPATH .  "uploads/items_documents/".$itemId."/";
      $result = $this->files_model->delete_by_name($attach_name,$path);
      if($result)
      {
          echo 'success';    
      }  
    }      

    public function delete_item_docs()
    {
      $userid = validateToken();
      $data = json_decode(file_get_contents("php://input"));
      $itemId = $data->itemId;
      $fileId = $data->file_id;
      $column_name = $data->column ;
      $attach_name = $data->file_name;
        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {
            $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0][$column_name]);
                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0][$column_name];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                    $column_name => $updated_str,
                    'updated_by' => $userid
                ];
            $update_item_row = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
        else
        {
            echo json_encode(array("status" => false, "message" => 'Something went wrong.'));
        }
       
        $path = FCPATH .  "uploads/items_documents/".$itemId."/";
        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo json_encode(array("status" => true, "message" => 'File deleted successfully.'));   
        }
    }

      public function itemList()
      {
        $data = json_decode(file_get_contents("php://input"));
        $page = 1;
        if (isset($data->page) && !empty($data->page)) {
           $page = $data->page;
        }
        if (isset($data->category_id) && !empty($data->category_id)) {
            $cat_id = $data->category_id;
        }
        $limit = 10;
        $offset = $limit * $page - $limit;
        /// items list against category_id for search and pagination of items result
        if (!empty($cat_id)) {
            $list_info = $this->api_model->item_list_pagination($offset, $limit, $cat_id);
        }else{
            $list_info = $this->api_model->item_list_pagination($offset, $limit);
        }
        foreach ($list_info as $key => $value) {
            $new1 = array();
            $e =  explode(',' ,$value['item_images']);
            $data = $this->db->where_in('id', $e)->get('files')->row_array();
            $list_info[$key]['image_name'] = $data['name'];

            $item_name = json_decode($value['name']);
            $list_info[$key]['name'] = $item_name;

            $item_name = json_decode($value['detail']);
            $list_info[$key]['detail'] = $item_name;

            $item_name = json_decode($value['terms']);
            $list_info[$key]['terms'] = $item_name;

            $item_name = json_decode($value['comment']);
            $list_info[$key]['comment'] = $item_name;

        } 
        if (!empty($list_info)) 
        {
            echo json_encode(array("status" => true, "message" => 'Items List.','result'=>$list_info));
        }
         else 
        {
            $list_info = array();
            http_response_code(200);
            echo json_encode(array('status'=>true,'message'=>'Items not available.','result'=>$list_info));
        }
      }  


    public function updateProfile()
    {
      $data_array = json_decode(file_get_contents("php://input"),true);
      $data = json_decode(file_get_contents("php://input"));
      $userId = validateToken();  
      $payload_info = getPayload();
      $data = array(
        // 'id' => $id,
        'fname' => $data->fname,
        'lname' => $data->lname,   
      );
      
      if (!empty($data)) {    
        $result= $this->api_model->edit_profile($userId, $data);
            echo json_encode(array('status'=>true,'message'=>'Profile updated successfully.','data'=>$data));
            exit();
      } else {
            echo json_encode(array('status'=>false,'message'=>'No change found.'));
        exit();
      }       
    }

    // public function updateProfilePic(){  
    //     $userId = validateToken(); 
    //     if(isset($_FILES['image']['name'])){
    //       $data_for_delete_files = $this->api_model->users_listing($userId); // fetch data 
    //       if(!empty($data_for_delete_files)){
    //         $old_data = FCPATH .'uploads/profile_picture/'.$userId.'/'.$data_for_delete_files[0]['picture'];
    //       }
    //       $picture = $data_for_delete_files[0]['picture']; 
    //       $path = "uploads/profile_picture/".$userId."/";
    //       if ( !is_dir($path)) {
    //         mkdir($path, 0777, TRUE);
    //       }
    //       $files = '';                              
    //       if(!empty($_FILES)){
    //         $files .= $this->uploadFiles_with_path($_FILES,$path);
    //         $profile_pic_array = array(
    //             'picture' => $files
    //         );
    //         echo json_encode(array("status" => true, "message" => 'Image updated successfully.','picture' => $files));
    //         $user_array =  $this->api_model->update_user($userId,$profile_pic_array);
    //         if(is_dir($path) && file_exists($picture)){
    //            unlink($old_data); 
    //         }  
    //       } else {
    //         echo json_encode(array("error" => true, "message" => 'Image Not Uploaded.'));
    //       }

    //     } else {
    //         echo json_encode(array("error" => true, "message" => 'Image Not uploaded.'));
    //     }  
    // }
      public function updateProfilePic(){  
        $userId = validateToken(); 
        $data = $this->input->post();
        if(isset($data['image'])){
            $data_for_delete_files = $this->api_model->users_listing($userId); // fetch data 
            if(!empty($data_for_delete_files)){
                $old_data = FCPATH .'/uploads/profile_picture/'.$userId.'/'.$data_for_delete_files[0]['picture'];
            }
            $picture = $data_for_delete_files[0]['picture']; 
            $path = "/uploads/profile_picture/".$userId."/";
            // if (!is_dir($path)) {
            //     mkdir($path, 0777, TRUE);
            // }
            $sizes = [
                ['width' => 37, 'height' => 36]
            ];
            $item_docs_array = [];                        
            $image_ids = $this->files_model->base64_upload($data['image'], $path, $sizes);
            $imgs_ids = implode(',', $image_ids);
            $image_name = $this->db->get_where('files',['id' => $imgs_ids])->row_array(); 
            $item_docs_array['picture'] = $imgs_ids;
            echo json_encode(array("status" => true, "message" => 'Image updated successfully.','picture' => $image_name['name']));
            $user_array =  $this->api_model->update_user($userId,$item_docs_array);
            if(is_dir($path) && file_exists($picture)){
                unlink($old_data); 
            } 
        } else {
        echo json_encode(array("error" => true, "message" => 'Image Not uploaded.'));
        }  
    }
  
////// users result expect one user/////
  public function all_sellers()
  {
    $users_lists = array();
    $data = json_decode(file_get_contents("php://input"));
    // check if token exists.
    $headers = getAuthorizationHeader();
    if (!empty($headers) ) {
        $userId = validateToken(); 
        $users_lists = $this->db->select('*')->from('users')->where('id !=', $userId)->where('role',4)->get()->result_array();
    }
    else{
        $users_lists = $this->db->select('*')->from('users')->where('role',4)->get()->result_array();
    }
    if ($users_lists) {
        http_response_code(200);
        echo json_encode(array('status'=>true,'error'=>false,'message'=>'success.','result'=> $users_lists));
    }
    else{
        echo json_encode(array('status' => true,'error' => false,'message'=>'No record found.'));
    }
  }

  //////// Item detail against id ////////
    public function itemDetailId()
    {
        $data = json_decode(file_get_contents("php://input"));
        //check signature available or not
        $check_file = $_SERVER['DOCUMENT_ROOT']."/uploads/items_documents/".$data->item_id."/".'sig.png';
        if (file_exists($check_file)) {
            $signature = true;  
        }else{
            $signature = false;
        }
        //check condition 
        $check_file2 = $_SERVER['DOCUMENT_ROOT']."/uploads/items_documents/".$data->item_id."/".'condition.png';
        if (file_exists($check_file2)) {
            $condition = true;
        }else{
            $condition = false;
        }
        $list_info = $this->db->select('*')->from('item')->where('id', $data->item_id)->order_by('created_on','DESC')->get()->row_array();
        $item_name = json_decode($list_info['name']);
        $list_info['name'] = $item_name;

        $item_name = json_decode($list_info['detail']);
        $list_info['detail'] = $item_name;

        $item_name = json_decode($list_info['terms']);
        $list_info['terms'] = $item_name;

        $item_name = json_decode($list_info['comment']);
        $list_info['comment'] = $item_name;

        if (isset($list_info['item_images']) && !empty($list_info['item_images'])) {
            $e =  explode(',' ,$list_info['item_images']);
        }else{
            $e =array();
        }
        if (isset($list_info['item_attachments']) && !empty($list_info['item_attachments'])) {
            $attach =  explode(',' ,$list_info['item_attachments']);
        }else{
            $attach = array();
        }
        $attach_test_docs =  explode(',' ,$list_info['item_test_report']);
        if (isset($list_info['item_images']) && !empty($list_info['item_images'])) {
            $data2 = $this->db->where_in('id', $e)->get('files')->result_array();
        }
        if (isset($list_info['item_attachments']) && !empty($list_info['item_attachments'])) {
            $data_attach = $this->db->where_in('id', $attach)->get('files')->result_array();
        }
        $data_attach_testDocs = $this->db->where_in('id', $attach_test_docs)->get('files')->result_array();
        if (isset($data_attach) && !empty($data_attach)) {
            foreach ($data_attach as $key => $attachment) {
                $list_info2[] = $attachment['name'];
            }
        }else{
            $list_info2 = array();
        }
        if (isset($data_attach_testDocs) && !empty($data_attach_testDocs)) {
            foreach ($data_attach_testDocs as $key => $attachTest) {
                $list_info3[] = $attachTest['name'];
                $list_info4[] = $attachTest['id'];
            }
        }else{
            $list_info3 = array();
            $list_info4 = array();
        }
        if (isset($data2) && !empty($data2)) {
            foreach ($data2 as $key => $d) {
                $list_info1[] = $d['name'];
            }
        }else{
            $list_info1 = array();
        }      
        if ($list_info) {
            http_response_code(200);
            echo json_encode(array('status'=>true,'error'=>false,'message'=>'Success.','result'=> $list_info,'item_attachments_name'=>$list_info2,'item_test_doc'=>$list_info3,'item_test_doc_id'=>$list_info4,'item_images_name' =>$list_info1,'item_images' =>$e,'item_attachments' =>$attach,'signature'=>$signature,'condition'=>$condition));
        }
        else{
            echo json_encode(array('status' => true,'error' => false,'message'=>'No record found.'));
        }
    }

  ////////////////////////////////////***********************///////////////////////////////////
    function removeItemString($str, $item) {
        $parts = explode(',', $str);
        while(($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }
            return implode(',', $parts);
    }


    /////////////////Api After ///////////////
    public function aboutUs(){
        $data = $this->db->get('about_us')->row_array();
        $description_decode = json_decode($data['description']);
        $title_decode = json_decode($data['title']);
        ///get image name from files model
        $result = array(
          'title' => $title_decode ,
          'description' => $description_decode
        );
        $files = $this->db->get_where('files',['id' => $data['policy_image']])->row_array();
        if (isset($data) && !empty($data)) {
            echo json_encode(array('status' => true,'error' => false,'result'=> $result,'image' => $files));
        }
    }

    ///////APIS new//////
        public function auctionCategories(){
            $data = $this->api_model->home_cats(); //get catagories for home with count
            foreach ($data as $key => $value) {
                $data[$key]['icon'] = $this->db->get_where('files', ['id' => $value['category_icon']])->row_array(); 
                $data[$key]['cat_title'] = json_decode($value['title']);
                $auctions_online = $this->api_model->get_auctions_home($value['cat_id']);
                $data[$key]['auction_id'] =  $auctions_online['id'];
                if (!empty($auctions_online)) {
                    $count= $this->db->select('*')->from('auction_items')->where('auction_id',$auctions_online['id'])->where('sold_status','not')->where('auction_items.bid_start_time <',date('Y-m-d H:i'))->get()->result_array();
                    $count = count($count);
                    $data[$key]['item_count'] =  $count;
                }
            }
            $data2=$this->home_model->featured_item();
            foreach ($data2 as $key => $img) {
                $data2[$key]['featured_images'] = $this->db->get_where('files', ['id' => $img['item_images']])->row_array();
            }
            if (!empty($result)) {
                echo json_encode(array('status' => true,'error' => false,'categories'=>$data,'featured_item'=>$data2));
            }else{
                echo json_encode(array('status' => true,'error' => false,'categories'=>$data,'featured_item'=>$data2 ));
            }
        }


      /// API for items list against categories auction type and pagination /// 
        public function categoryItems(){
            $data1 = json_decode(file_get_contents("php://input"));
            $id = $data1->id;
            $type = $data1->type; //auction type
            $page = 1;
            if (isset($data1->page) && !empty($data1->page)) {
                $page = $data1->page;
            }
            $limit = 10;
            $offset = $limit * $page - $limit;
            $date['time'] =date('Y-m-d H:i:s', time());
            $get_item_list_against_cat = $this->api_model->get_item_list_against_cat($id,$type,$offset,$limit);///get data from auction table against cat_id
            $auction_item_data = $this->db->get_where('auction_items',['sold_status' => 'not','auction_id' => $get_item_list_against_cat['id']])->result_array();////get the data from auction_items against auction id and sold status
            if (!empty($auction_item_data)) {
                foreach ($auction_item_data as $key => $img) {
                    $title = $data1->title;
                    /// get data from item table against item_id in auction item ///
                    $items_data[$key] = $this->db->get_where('item',['id' => $img['item_id']])->row_array();
                }
            }else{
                $res = array();
                echo json_encode(array('status'=>true, 'result'=> $res,'message'=>'No item against this search.'));
                exit();
            }
            foreach ($items_data  as $keys => $img_id) {
                /// get images from files table against item_images ///
                $items_data[$keys]['item_images'] = $this->db->get_where('files',['id' => $img_id['item_images']])->row_array();
            }
            $data = $items_data;
            if (!empty($data)) {
                http_response_code(200);
                echo json_encode(array('status'=>true,'message'=>'success','result'=> $data,'time'=>$date));
            } else {
                http_response_code(200);
                echo json_encode(array('status'=>true,'message'=>'No data'));
            }
        }

      //// For search in inspection app //// 

    public function inspection_search(){
        $data = json_decode(file_get_contents("php://input"));
        $filter = $data->filter;
        $search_data = $this->db->select('*')
                  ->from('item')
                  ->like('registration_no' , $filter , 'BOTH')
                  ->or_like('vin_number',$filter, 'BOTH')
                  ->order_by('updated_on', 'DESC')
                  ->get()->result_array();
        $output = array();        
        foreach ($search_data as $key => $item) {

          $title_name = json_decode($item['name']);
          $item['name'] = $title_name;

          $title_detail = json_decode($item['detail']);
          $item['detail'] = $title_detail;

          $title_terms = json_decode($item['terms']);
          $item['terms'] = $title_terms;

          $image_ids =  explode(',', $item['item_images']);
          $item['img'] = $this->db->where_in('id',$image_ids)->get('files')->result_array();//get item images multiple 

          array_push($output,$item);
        }
        if ($search_data) {
          echo json_encode(array('status'=>true,'result' => $output));
        }else{
          echo json_encode(array('status'=>false,'result' => 'No record found.'));
        }
    }


}

