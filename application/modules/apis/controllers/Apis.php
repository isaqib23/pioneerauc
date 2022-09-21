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
class Apis extends MX_Controller {
     public function __construct() { 
        parent::__construct();
        // Allow from any origin
    // if (isset($_SERVER['HTTP_ORIGIN'])) {
    //     // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    //     // you want to allow, and if so:
    //     header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    //     header("Access-Control-Allow-Origin: http://localhost:8100 ");
    //     header("Access-Control-Allow-Origin: * ");
    //     header('Access-Control-Allow-Credentials: true');
    //     header('Access-Control-Max-Age: 3600');    // cache for 1 day
    // }

    // // Access-Control headers are received during OPTIONS requests
    // if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    //         // may also be using PUT, PATCH, HEAD etc
    //         header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    //         header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
         
    //     exit(0);
    // }

        //load user model
        $this->load->model('Apis_model','api_model');
        $this->load->model('files/Files_model', 'files_model');
        $this->load->library('jwt');
        $this->load->library('TestUnifonic');
        $this->load->helper('jwt_helper');
        $this->load->helper('general_helper');
        $this->load->library('session');

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
          
       }
       else
       {
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
         }
         else
         {
            // http_response_code(400);
            echo json_encode(array('status'=>false,'message'=>'No Record Found'));
         }
        
        }
        public function login_user()
        {

        $data = json_decode(file_get_contents("php://input"));
        // print_r($data);
         if (!empty($data->username) && !empty($data->password)) {

        $username = $data->username;
        $dataa=$data->password;
        $password = htmlspecialchars(strip_tags(md5($data->password)));
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
            echo json_encode(array('status'=>true,'message'=>'success','result'=> $data,'password'=>$dataa)); 
                }
              }
        else
        {
          http_response_code(404);
           echo json_encode(array('status'=>false,'message'=>'Unauthorized user'));
          // echo "error";   
        }
        }else{

            echo json_encode(array('status'=>false,'message'=>'Data is missing'));
        }
        
        }
        public function get_catagories()
        {
            $date['time'] =date('Y-m-d H:i:s', time());
            $data=$this->api_model->get_catagories();
             if (!empty($data)) {
                 http_response_code(200);    
                 echo json_encode(array('status'=>true,'message'=>'success','result'=> $data,'time'=>$date));
             }
             else
             {
                 http_response_code(400);
               echo json_encode(array('status'=>false,'message'=>'No record found'));
             }
        }

        public function upcoming_auctions()
        {    
            $date=date('Y-m-d');
            $data=$this->api_model->upcoming_auctions($date,'time');
            // $result=json_encode($data);
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

        public function user_account()
        {
            $userId = validateToken();  
            // $info=$this->api_model->user_account($userId);
            $info = $this->db->get_where('crm_buyer_detail', ['id' => $userId])->row_array();
            $infoo = $this->db->get_where('live_auction_customers', ['id' => $userId])->row_array();
            $info['buyer_amount']=0;
            $infoo['live_auction_customers_bids']=0;

            $query = $this->db->query('SELECT COUNT(DISTINCT(item_id)) as count FROM `bid` WHERE user_id = '.$userId.'');
            $bid_count = $query->row_array();
            if (!empty($info)) {
                http_response_code(200);
                echo json_encode(array('status'=>true,'message'=>'success','result'=> $info,'results'=> $bid_count));
            }
            elseif (empty($info))
             {
               http_response_code(200);
                echo json_encode(array('status'=>true,'message'=>'success','result'=>  $info['buyer_amount'],'results'=> $bid_count));
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

        public function forgot_password()
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
            $this->email_template_forgot($email,$vars,true,'reset_password');
            echo json_encode(array('status'=>true,'message'=>'Reset link is sent to your email'));
          
           } else {
            http_response_code(400);
            echo json_encode(array('status'=>false,'message'=>'error'));
           }
        }
        else
        {
          http_response_code(400);
            // echo json_encode(array('status'=>false,'message'=>'error'));

        }
      }
      public function verify_forgot_password($email_code)
    {
         $email_codes = base64_decode(urldecode($email_code));

        $user = $this->db->get_where('users', ['reset_password_code' => $email_codes])->row_array();
        if (!empty($user)) {
           
            $this->template->load_user('api/forgot-password');
          
        } 
        else {
            $data = array();
            // echo json_encode(array('status'=>true,'message'=>'email not verified'));
            $this->session->set_flashdata('error', 'You are not allowed to Access URL!');
            $data['active_login'] = 'active';
            $data['login_show'] = ' show';
            $this->template->load_user('home/login',$data);
            // redirect(base_url('home/login'));
        }     
 
    }

      public function updatePassword($encoded_id){

           if ($this->input->post()) {
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
                $this->db->update('users', ['password'=>md5($password),'reset_password_code'=>0],['id'=>$user['id']]);
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
      public function register_user()
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
          $number = $data->mobile;        
          $check_number = $this->api_model->check_user_number($number);
          if($check_number == true)
          {   
            http_response_code(400);
          // $this->session->set_flashdata('error', 'Number already exist');
          // echo "'error', 'Number already exist'";
          // $msg = 'duplicate';
          // echo json_encode(array('msg' => $msg,'mid' => 'Number already exist'));
          echo json_encode(array("status" => FALSE, "message" => 'Number already exist'));
          exit();
          }
          $email = $data->email;
          $result = $this->api_model->check_email_user($email);
          if($result == true)
          {   
          // $this->session->set_flashdata('error', 'Email already exist');
            http_response_code(400);
               // echo "'error', 'Email already exist'";
              // $msg = 'duplicate';
            echo json_encode(array("status" => FALSE, "message" => 'Email already exist'));
              // echo json_encode(array('msg' => $msg,'mid' => 'Email already exist'));
            exit();
          }
              // $generateCode = rand(1000, 9999);
          $mobile_verification_code = getNumber(4);
          $email_verification_code = getNumber(4);
          
             //SMS verification process start
          $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
          $sms_response = $this->testunifonic->sendMessage($number, $sms);
            // print_r($sms_response);die();
          if(isset($sms_response->MessageID)){
                  $sms_response = json_encode($sms_response);
                  // $this->db->update('users', ['sms_response' => $sms_response], ['id' => $result]); 
             $data = array(
              'fname' => $data->fname,
              'lname' => $data->lname,
              'email' => $data->email,
              'mobile' => $data->mobile,   
              'city' => $data->city,  
              'prefered_language' => $data->prefered_language,
              'dial_code' => $data->dial_code,   
              'role' => $data->role_id,
              'password' => md5($data->password),
              'reg_type' => $data->reg_type,   
              'social' => $data->social,         
               'code' =>  $mobile_verification_code,  
               'email_verification_code' => $email_verification_code,         
          );
             // print_r($data);die();
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
                  // echo "user insert successfully";
                  echo json_encode(array("status" => TRUE, "message" => 'user registered',"email_status" => $send));
              }
              else
              {
                  // echo "not successful";
                  echo json_encode(array("status" => FALSE, "message" => $sms_response));
              }
          }else{
               echo json_encode(array("status" => FALSE, "message" => 'message not sent'));
          }     
        }
      } 

      public function verify_code()
      {
         $data_array = json_decode(file_get_contents("php://input"),true);
         $data = json_decode(file_get_contents("php://input"));
        // $id=$data->id;
         $code=$data->code;
        $data=$this->api_model->verify_code($code);
        if (!empty($data)) {
           
        if ($data['code']==$code) {
          http_response_code(200);
        echo json_encode(array('status'=>true,'message'=>'Code Matched'));
        }
        else
        {
           http_response_code(400);
           echo json_encode(array('status'=>false,'message'=>'Code Not Matched'));
        }
        }
        else
        {   
          http_response_code(400);
          echo json_encode(array('status'=>false,'message'=>'Invalid Code'));
        }       
      }

      public function resendCode()
      {

        // $user = $this->db->get_where('users',['id' => $id]);
       $data_array = json_decode(file_get_contents("php://input"),true);
       $data = json_decode(file_get_contents("php://input"));
      
       $phone=$data->phone;
        // $phone = $this->input->post('phone');
        if (!empty($phone)) { 
            $mobile_existed = $this->db->get_where('users', ['mobile' => $phone]);
            if($mobile_existed->num_rows() > 0){
              $mobile_existed = $mobile_existed->row_array();
              
              $mobile_verification_code = $this->getNumber(4);
              $code_status = 0;

              //SMS verification process start
              $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
              $sms_response = $this->testunifonic->sendMessage($phone, $sms);
              //return print_r($sms_response);
              if(isset($sms_response->MessageID)){
                  $sms_response = json_encode($sms_response);
                  $this->db->update('users', [ 'code' => $mobile_verification_code,'code_status' => $code_status, ], 
                    ['id' => $mobile_existed['id']]);
              }

            $output = json_encode(['success' => true,'codeGenerated' => true,'msg' => 'Verification code has been sent to your mobile.']);
                return print_r($output);
            }else{
                $output = json_encode(['error' => true,'codeGenerated' => false,'msg' => 'Mobile number is not registered.']);
                return print_r($output);
            }

        }
        
    }


        public function RandomStringGenerator($n) {
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

        public function edit_profile()
        {
          $data_array = json_decode(file_get_contents("php://input"),true);
          $data = json_decode(file_get_contents("php://input"));
      
          $userId = validateToken();  
          // print_r($userId); 
          $payload_info = getPayload();
          $number = $data->mobile; 

          // if ($payload_info->mobile  !=$number) 
          // {
          $check_number = $this->api_model->check_user_number($number,$userId);
          if($check_number)
          {  
            echo json_encode(array("status" => false, "message" => 'number exist'));
            exit();
          }
          else
          {
            $check_mobile=$this->api_model->update_mobile($userId,$data);
          
          }

          $email =$data->email;
          // if($payload_info->email != $email)
          // {
          $result = $this->api_model->check_email_user($email,$userId);
          if($result)
          {   
           
          echo json_encode(array("status" => false, "message" => 'Email exist'));
          exit();
          }else{
            $check=$this->api_model->update_user($userId,$data);  
          }
          $data = array(
            // 'id' => $id,
            'fname' => $data->fname,
            'lname' => $data->lname,
            'email' => $data->email,
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

          if (!empty($data)) {
            $result= $this->api_model->edit_profile($userId, $data);
            echo json_encode(array('status'=>true,'message'=>'Profile updated successfully','data'=>$data));
            exit();
          }else{
            echo json_encode(array('status'=>false,'message'=>'No change found'));
            exit();
          }
              
        }

        public function wishList()
        {
          $info = $this->db->get_where('favorites_items', ['user_id' => $itemId])->row_array();         
          $data = array(
                  'customer_wish_list' => $data->wish_list,
                );
          if (!empty($data)) {
            $results= $this->api_model->add_wishlist($itemId,$data);//update wish list
            echo json_encode(array("status" => true, "message" => 'item added','result'=>$data));
          }
          else
          { $data = json_decode(file_get_contents("php://input"));
          $itemId = validateToken();
            http_response_code(200);
            echo json_encode(array('status'=>true,'message'=>''));
          }
        }

        public function getWishList()
        {
            // $data = json_decode(file_get_contents("php://input"));
            $id = validateToken();
            $output = array();
            $auction_data = array();
            // $info = $this->db->get_where('favorites_items', ['user_id' => $userId])->row_array();
            $details=$this->api_model->get_items_details($id);
            
            // $detailss=$this->api_model->get_items_images($id);
            $this->db->join('item', 'item.id = favorites_items.item_id', 'left');
            $record = $this->db->get_where('favorites_items',['user_id'=>$id])->result_array();
             foreach ($record as $value) {
              $item_id = $value['item_id'];
              $images_ids = explode(",",$value['item_images']);
              $item_detail_url =  base_url('items/details/').urlencode(base64_encode($value['item_id']));
              $make = $this->db->get_where('item_makes',['id'=>$value['make']])->row_array();
              $auction_items_row = $this->db->get_where('auction_items',['item_id'=>$value['item_id']])->row_array();
              $detail_data = $this->db->get_where('item_fields_data',['item_id'=>$value['item_id'],'category_id'=>$value['category_id']])->result_array();
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
            if(isset($auction_items_row) && !empty($auction_items_row)){

              $auction_row = $this->db->get_where('auctions',['id'=>$auction_items_row['auction_id']])->row_array();
              $auction_data = array(
                'auction_start_time' => $auction_row['start_time'],
                'auction_expiry_time' => $auction_row['expiry_time'],
                'item_start_time' => $auction_items_row['bid_start_time'],
                'item_end_time' => $auction_items_row['bid_end_time']
              );
            }
              $output[] = array(
                'name' => $value['name'],
                'price' => $value['price'],
                'detail' => $detail_data_str,
                'keyword' => $value['keyword'],
                'make'=>$make['title'],
                'model'=>$model['title'],
                'auction_detail'=>$auction_data,
                'item_status' => $value['item_status'],
                'image' => $image,
              );
              
            }
               
            if (!empty($output)) 
            {
              http_response_code(200);
              // $results= $this->api_model->add_wishlist($userId,$info);//update wish list
              echo json_encode(array("status" => true, "message" => 'Wish list found','result'=>$output));
            }
            else
            {
              http_response_code(200);
              echo json_encode(array('status'=>true,'message'=>'No item in Wish list'));
            }
        }


        


        public function changePassword()
        {
          // $data_array = json_decode(file_get_contents("php://input"),true);
          $data = json_decode(file_get_contents("php://input"));
          $userId = validateToken(); 
          $result= $this->db->get('users',['id'=>$userId])->row_array();
          $data = array(
           // 'id' => $id,
           'password' => md5($data->password),
          );
          if (!empty($data)) {
          $results= $this->api_model->update_password_new($userId,$data);
          echo json_encode(array("status" => true, "message" => 'password changed','result'=>$results));
          }
          else
          {
            echo json_encode(array("status" => false, "message" => 'password Not changed'));
          }
        }

    
      public function saveDocuments(){  
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
                echo json_encode(array("status" => true, "message" => 'doument uploaded'));
               $user_array =  $this->api_model->update_user($userId,$profile_pic_array);
                if(is_dir($path) && file_exists($picture)){
                    unlink($old_data); 

                } 
              }
            else{
            echo json_encode(array("status" => false, "message" => 'doument not uploaded'));
          }

          }else{
            echo json_encode(array("status" => false, "message" => 'Error'));
          }
              
        }else{
          return false;
        }  
      }

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
            echo json_encode(array("status" => false, "message" => 'Documents not uploaded'));
            exit();
          }
        }
        if (isset($_FILES['passport']['name'])) {
          $passport = $this->files_model->upload('passport', $config);
          if (isset($passport['id'])) {
            $documents_array['passport'] = $passport['id'];
          }else{
            echo json_encode(array("status" => false, "message" => 'Documents not uploaded'));
            exit();
          }
        }
        if (isset($_FILES['driver_license']['name'])) {
          $driver_license = $this->files_model->upload('driver_license', $config);
          if (isset($driver_license['id'])) {
            $documents_array['driver_license'] = $driver_license['id'];
          }else{
            echo json_encode(array("status" => false, "message" => 'Documents not uploaded'));
            exit();
          }
        }
        if (isset($_FILES['trade_license']['name'])) {
          $trade_license = $this->files_model->upload('trade_license', $config);
          if (isset($trade_license['id'])) {
            $documents_array['trade_license'] = $trade_license['id'];
          }else{
            echo json_encode(array("status" => false, "message" => 'Documents not uploaded'));
            exit();
          } 
        }
        $files_ids['documents'] = json_encode($documents_array);
        $user =  $this->api_model->update_user($userId,$files_ids);
        if ($user) {
          echo json_encode(array("status" => true, "message" => 'Documents uploaded'));
        }else{
          echo json_encode(array("status" => false, "message" => 'Documents not uploaded'));
        }

      }else{
        return false;
      }
  
    }


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
            echo json_encode(array("status" => true, "message" => 'Image uploaded.'));
           $user_array =  $this->api_model->update_user($userId,$profile_pic_array);
            if(is_dir($path) && file_exists($picture)){
              unlink($old_data); 
            }     
          }
          else
          {
            echo json_encode(array("status" => false, "message" => 'Image Not uploaded.'));
          }

      }
      else
      {
        return false;
      }
          
    }
    else
    {
       echo json_encode(array("status" => false, "message" => 'Image Not uploaded.'));
    }  
  }

  public static function uploadFiles_with_path($files,$path){
        if(isset($files['image']['name']) && !empty($files['image']['name']))
        {
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

         public static function uploadFiles_with_path_docs($files,$path){

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

            public function user_history_auctions()
            {
              $id= validateToken();
              $data=$this->api_model->user_history_auctions($id);
              if (!empty($data)) {
                http_response_code(200);
                echo json_encode(array('status'=>true,'message'=>'success','result'=> $data));
              }
              else
              {
                http_response_code(200);
                echo json_encode(array('status'=>true,'message'=>''));
              }
            }

             public function auctionDetail()
            {

              $data = json_decode(file_get_contents("php://input"));
              $id= $data->id;
              $date['time'] =date('Y-m-d H:i:s', time());
              $data=$this->api_model->get_auction_visitors($id);

              if (!empty($data)) {
                http_response_code(200);
                echo json_encode(array('status'=>true,'message'=>'success','result'=> $data,'time'=>$date));
              }
              else
              {
                http_response_code(200);
                echo json_encode(array('status'=>true,'message'=>''));
              }
            }

            public function getNotification()
            {
              $userId= validateToken();
              // $data = json_decode(file_get_contents("php://input"));
              // $id= $data->id;
              $info = $this->db->get_where('notification', ['receiver_id' => $userId])->result_array();
              if (!empty($info)) {
                http_response_code(200);
                echo json_encode(array('status'=>true,'result'=> $info));
              }
              else
                {
                    http_response_code(200);
                    echo json_encode(array('status'=>true,'message'=>'no notification'));
                }
            }

        public function user_history_bids()
        {
          $id= validateToken(); 
          $data = array();
          $output = array();
          // $data=$this->api_model->user_history_bids($id);
          
          $query = $this->db->query('Select bid_time,user_id,bid.item_id,bid_amount, bid_status ,item.name,item.make,item.model,item.item_images,item.item_attachments,item.category_id  from bid  
           inner join  ( Select max(bid_time) as LatestDate, item_id  from bid Group by bid.item_id   ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id
               LEFT JOIN item ON item.id = bid.item_id  WHERE bid.user_id = '.$id.';');
          foreach ($query->result_array() as $row){
             $item_id = $row['item_id'];
              $images_ids = explode(",",$row['item_images']);
              $item_detail_url =  base_url('items/details/').urlencode(base64_encode($row['item_id']));
              $make = $this->db->get_where('item_makes',['id'=>$row['make']])->row_array();
              $detail_data = $this->db->get_where('item_fields_data',['item_id'=>$row['item_id'],'category_id'=>$row['category_id']])->result_array();
              $detail_data_str =  implode(",",array_column($detail_data, 'value'));
              $model = $this->db->get_where('item_models',['id'=>$row['model']])->row_array();
              $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
            if(!empty($row['item_images']) || !empty($row['item_attachments'])){
              if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                  $file_name = $files_array[0]['name'];
                  $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                  $image =  $base_url_img;
              }else{
                   $image = $base_url_img;
              }
            }
              $output[] = array(
                'name' => $row['name'],
                'bid_amount' => $row['bid_amount'],
                'detail' => $detail_data_str,
                'make'=>$make['title'],
                'model'=>$model['title'],
                'bid_status' => $row['bid_status'],
                'image' => $image,
              );
          }

          if (!empty($output)) {
            http_response_code(200);
             echo json_encode(array('status'=>true,'message'=>'success','result'=> $output));
          }
          else
            { 
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
                // $this->session->set_flashdata('error', 'Email already exist');
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

            // public function car_valuation()
            // {
            //   $data=$this->api_model->car_valuation();
            //   if (!empty($data)) {
            //     http_response_code(200);
            //    echo json_encode(array('status'=>true,'message'=>'success','result'=> $data));
            //   }
            //   else
            //   {  
            //     http_response_code(400);
            //      echo json_encode(array('status'=>false,'message'=>'error'));
            //   }
            // }

            public function car_valuation()
            {

              $data = json_decode(file_get_contents("php://input") , true);
             // print_r($data);die();
             if (!empty($data->car)) {
              $result = array(
                  'car' => $data->car,
                  'model' => $data->model,
                  'year' => $data->year,
                  'millage' => $data->millage,
                  'eng_size' => $data->eng_size,
                  'value1' => $data->value1,
                  'value2' => $data->value2,
                  'value3' => $data->value3,
                  'email' => $data->email,
            );
              echo json_encode(array("status" => TRUE, "message" =>'success','result'=>$result));
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

                    // print_r($email_message);
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

            // $id = validateToken();
            // $details=$this->api_model->get_terms($id);
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
                'detail' => $detail_data_str,
                'keyword' => $value['keyword'],
                'make'=>$make['title'],
                'model'=>$model['title'],
                'item_status' => $value['item_status'],
                'image' => $image,
              );
              
            }
            // print_r($terms_info);die();
            if (!empty($terms_info)) #
            {
              http_response_code(200);
              echo json_encode(array("status" => true, "message" => 'inventory data','result'=>$output));
            }
            else
            {
                http_response_code(200);
                echo json_encode(array("status" => true, "message" => '','result'=>$response));
            }
        }

        public function email_template_forgot($email='', $vars=array(), $btn=false,$register='reset_password'){
        // if(!empty($slug)){
            $notification = 'welcome';
            $data = array();
            $data = [
                'btn' => $btn,
                'notification' => $notification
            ];       
            
            $email_message = $this->load->view('email_templates/reset_password', $data, true);
            $fname=$this->session->userData();
            // $email_message = str_replace(, $value, $email_message);

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
              $email = $data->email;
                
           $result = $this->db->query("SELECT * FROM `valuation_price` WHERE 
        (`valuation_make_id` = '" . $valuation_make_id .
            "' AND `valuation_model_id` = '" . $valuation_model_id . "') AND (`engine_size_id` = '" . $engine_size_id . "')");
            $result = $result->row_array();
            // print_r($result);die('jjj');
            // $last_query = $this->db->last_query();
            if (isset($result) && !empty($result) && count($result) > 0) 
            {
            $orig_price = $result['price'];
            $year_to= $data->year_to;
            // check year depreciation
            $get_year_depre = $this->db->query("select year_depreciation from valuation_years where (year = '".$year_to."') ");
            $get_year_depre = $get_year_depre->row_array();

            if (count($get_year_depre) > 0) {
                $get_year_depre['year_depreciation'] = (float)$get_year_depre['year_depreciation'];
                if ($get_year_depre['year_depreciation'] > 0) {
                    $year_valuate_price = $orig_price * ($get_year_depre['year_depreciation'] / 100);
                    $orig_price = $orig_price - $year_valuate_price;
                   
                }
            }
            // check mileage depreciation
            $get_mileage_depre = $this->db->query("select millage_depreciation from valuation_millage where ('".$valuation_milleage_id."' BETWEEN `millage_from` AND `millage_to`)
            || (millage_from='".$valuation_milleage_id."' || millage_to='".$valuation_milleage_id."' )");
            $get_mileage_depre = $get_mileage_depre->row_array();
            if (!empty($get_mileage_depre)) {
                if ($get_mileage_depre['millage_depreciation'] > 0) {
                    $mileage_valuate_price = $orig_price * ($get_mileage_depre['millage_depreciation'] /
                        100);
                    $orig_price = $orig_price - $mileage_valuate_price;

                }
            }
                 
            $id=1;
            // check global config depreciation
            $get_global_depre = $this->db->query("select * from valuation_config_setting where created_by='" .
                $id . "'");
            $get_global_depre = $get_global_depre->row_array();   
            if (count($get_global_depre) > 0) {
                // check paint depreciation
                if ($get_global_depre['config_setting_paint'] != "" && $valuate_paint!="") {
                    $config_setting_paint = json_decode($get_global_depre['config_setting_paint'], 1);
                    // print_r($config_setting_paint);
                    $config_setting_paint = $config_setting_paint[$valuate_paint];
                    if ($config_setting_paint > 0) {
                        $config_setting_paint = (float)$config_setting_paint;
                        $config_setting_paint = $orig_price * ($config_setting_paint / 100);
                        $orig_price = $orig_price - $config_setting_paint;
                       
                    }
                }             
                // check paint depreciation
                if ($get_global_depre['config_setting_specs'] != "" && $valuate_gc!="") {
                    $config_setting_specs = json_decode($get_global_depre['config_setting_specs'], 1);
                    $config_setting_specs = $config_setting_specs[$valuate_gc];
                    // print_r($config_setting_specs);die();
                    if ($config_setting_specs > 0) {
                       $config_setting_specs = (float)$config_setting_paint;
                        $config_setting_specs = $orig_price * ($config_setting_specs / 100);
                        $orig_price = $orig_price - $config_setting_specs;
                    }
                }
            }
               $data =  number_format($orig_price,2);
            // email send setting 

                $to = $email;
                $subject = "Your Car Evaluation Result";
                // $txt = "Your Email is : ".$email."\n\n Your Password is :".$password;
                $template_name = "user registration";
                $q = $this->db->query('select * from crm_email_template where slug = "car-evaluation"');
                $email_message = $q->row_array();   
                // print_r($email_message);die();
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
                $to =$this->input->post('email');       
                $this->session->set_userdata('email', $to);
                $this->session->set_userdata('car_price', $data);
                $this->email->to($to);
                $this->email->subject($email_message['subject']);
                $this->email->message($email_messagee);
                $this->email->send();
            }
              $msg = 'success';      
            echo json_encode(array('msg' => $msg, 'result' => $data));
            // $this->template->load_admin('car_valuation/car_valuation_result',$data);    
    }
        else{

                echo json_encode(array('msg' =>'error' , 'result' => 'No data available'));
                // redirect(base_url().'cars/car_valuation');             
        }
    }

    public function getMakes()
    {
                $data = json_decode(file_get_contents("php://input"));
                $this->db->select('title,valuation_make.id');
                $this->db->from('valuation_make');
                // $this->db->join('valuation_price','valuation_price.valuation_make_id=valuation_make.id');
                 $valuation_make= $this->db->get()->result_array();
                
                if (!empty( $valuation_make)) {
                echo json_encode(array('status' => true, 'result' =>  $valuation_make));
                }
                else
                {
                  echo json_encode(array('status' => true, 'result' => 'no result found'));
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
                 $valuation_make= $this->db->get()->result_array();
                 // print_r($valuation_make);die();
                 if (!empty( $valuation_make)) {
                echo json_encode(array('status' => true, 'result' =>  $valuation_make));
                }
                else
                {
                  echo json_encode(array('status' => true, 'result' => 'no result found'));
                }
    }

     public function getEngine()
    {
                $data = json_decode(file_get_contents("php://input"));
                $model= $data->model_id;
                $make= $data->make_id;
                $this->db->select('*');
                $this->db->from('valuation_price');
                $this->db->where('valuation_make_id',$make);
                $this->db->where('valuation_model_id',$model);
                $this->db->join('valuation_enginesize','valuation_enginesize.id=valuation_price.engine_size_id');
                 $valuation_make=$this->db->get()->result_array();    
                  
                 if (!empty( $valuation_make)) {
                echo json_encode(array('status' => true, 'result' =>  $valuation_make));
                }
                else
                {
                  echo json_encode(array('status' => true, 'result' => 'no result found'));
                }
    }

     public function getYear()
    {
                $data = json_decode(file_get_contents("php://input"));
                $this->db->select('valuation_years.id,year');
                $this->db->from('valuation_years');
                $this->db->where('make_id',$data->make_id); 
                $this->db->where('model_id',$data->model_id);
                // $this->db->join('valuation_make','valuation_make.id=valuation_years.make_id');
                // $this->db->join('valuation_model','valuation_model.id=valuation_years.model_id');
                $valuation_make= $this->db->get()->result_array();
                if (!empty( $valuation_make)) {
                echo json_encode(array('status' => true, 'result' =>  $valuation_make));
                }
                else
                {
                  echo json_encode(array('status' => true, 'result' => 'no result found'));
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
                  echo json_encode(array('status' => true, 'result' => 'no result found'));
                }
    }

       public function getOptions()
    {
                $this->db->select('title,valuate_cars_options.id');
                $this->db->from('valuate_cars_options');
                  $valuation_make= $this->db->get()->result_array();
                 if (!empty( $valuation_make)) {
                echo json_encode(array('status' => true, 'result' =>  $valuation_make));
                }
                else
                {
                  echo json_encode(array('status' => true, 'result' => 'no result found'));
                }
    }

        public function getPaint()
    {
                $this->db->select('config_setting_paint,valuation_config_setting.id');
                $this->db->from('valuation_config_setting');
                  $valuation_make= $this->db->get()->result_array();
                  // print_r($valuation_make);die();
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
                             // $arr22['id']= $a;
                             array_push($newArr, $arr22);

                           }
                         }
                       }

                       echo json_encode(array('status' => true, 'result' => $newArr));
                    }
                else
                {
                  echo json_encode(array('status' => true, 'result' => 'no result found'));
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
                  echo json_encode(array('status' => true, 'result' => 'no result found'));
                }

    }


          public function bookAppointment()
          {
               $data = json_decode(file_get_contents("php://input"));
               $result= array(
               // 'email'=> $data->email,
               'date'=> $data->date,
               'time'=> $data->time,
               'fname'=> $data->fname,
               'lname'=> $data->lname,
               'number'=> $data->number
           );
             if (!empty($result)) {
                $email_message['subject']='Book Appointment';
                $email_messagee ='"'.$result['fname'].' '.$result['lname'].'"Your Appointment is schdule :"'.$result['date'].'"at "'.$result['time'].'""'.$result['date'].'"'; 
                // print_r($email_messagee);die();
                $to= $data->email;
                $this->email->to($to);
                $this->email->subject($email_message['subject']);
                $this->email->message($email_messagee);
                $this->email->send();
                echo json_encode(array('error' => false, 'message' => 'Appointment booked ! check you email'));
             }
             else
             {
               echo json_encode(array('error' => true, 'message' => 'error'));
             }
           }
          public function contactUs(){
           $data= $this->db->get('contact_us')->row_array();
           if (!empty($data)) {
              echo json_encode(array('status' => true, 'result' => $data));
           }
           else
           {
            echo json_encode(array('status' => true, 'result' => 'no record found'));
           }
          }


          // public function contactEmail()
          // {

          //   $data = json_decode(file_get_contents("php://input"));
          //   if (!empty($data)) {
          //     echo json_encode(array('status' => true, 'result' => $data));
          //   }
          //   else
          //   {
          //     echo json_encode(array('status' => false, 'result' => 'no data found'));
          //   }
          // }

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
            }
            else
            {
              echo json_encode(array('error' => true, 'result' => 'email not sent'));
            }
          }

      }
