<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . '/libraries/REST_Controller.php';
require_once('vendor/autoload.php');

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

class Getapi extends MX_Controller
{
    public $language = 'en';


    public function __construct()
    {
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
        $this->load->model('Getapi_model', 'api_model');
        $this->load->model('files/Files_model', 'files_model');
        $this->load->model('Home/Home_model', 'home_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('email/Email_model', 'et');
        $this->load->model('auction/Online_auction_model', 'oam');
        $this->load->model('auction/Live_auction_model', 'lam');
        $this->load->model('Search/Search_model', 'sm');
        $this->load->model('getapi/Getapi_model', 'getapimodel');
        $this->load->model('customer/Customer_model', 'customer_model');
        $this->load->library('jwt');
        $this->load->library('TestUnifonic');
        $this->load->helper('jwt_helper');
        $this->load->helper('general_helper');
        $this->load->library('session');
        $this->load->library('fcm');


        //$_POST = json_decode(file_get_contents("php://input"), true);
        header("Access-Control-Allow-origin: *");
        header("Content-Type: application/json");
        header("Cache-Control: no-cache");

        $language = $this->_getLanguage();

        $headers = $this->input->request_headers();

        if (!empty($headers['language'])) {
            $this->language = $headers['language'];
        } else {

            $this->language = $language;
        }

    }


    public function index()
    {
        $this->load->view('Welcome_message');
        // redirect('user_insert');
    }


    public function login_user_list()
    {
        $user_array = $this->api_model->login_user_list();
        // $user_data=json_encode( $user_array);
        if (!empty($user_array)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $user_array));
        } else {
            // http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'No Record Found'));
        }

    }

    function get_all_users()
    {

//        $version = $this->config->item('appVersion');
//        $data = array();
//        $this->db->select('*');
//        // $this->db->select('*');
//        $this->db->from('users');
//        $query = $this->db->get();
//        if ($query->num_rows() > 0) {
//            $data = $query->result_array();
//            $codee = http_response_code(200);
//            echo json_encode(array('status' => true, 'code' => $codee, 'message' => 'ALL USERS', 'result' => $data));
//
//        } else {
//            return array();
//        }
    }


    public function delete_user_row($id)
    {
//        $this->db->select('*');
//        $this->db->from('users');
//        $this->db->where('id', $id);
//        $query = $this->db->get();
//        if ($query->num_rows() > 0) {
//            $this->db->where('id', $id);
//            $this->db->delete('users');
//            echo "true";
//        } else {
//            echo "false";
//        }
    }


    public function otp()
    {

        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if (!empty($data->email)) {

            $username = $data->email;
            $result = $this->api_model->check_email2($username);
            // verify email is valid or not
            if (!empty($result[0]->id)) {


                $status = $result[0]->status;
                // check if user is active and check if paid with status is 1

                // Generate JWT Token with payload
                // payload have expire time information with user role id and user id

                //SMS verification process start
                $number = $result[0]->mobile;
                $code_status = 0;

                $mobile_verification_code = getNumber(4);
                $email_verification_code = $mobile_verification_code;


                $this->load->library('SendSmart');
                $sms = $this->lang->line('pioneer_verification_code_is') . $mobile_verification_code;
                // $sms_response = $this->testunifonic->sendMessage($phone, $sms);

                $sms_response = $this->sendsmart->sms($number, $sms);
                //SMS verification process start //International
//            $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
//            $sendotp = $this->sendotp("+971545899756", $sms);
                $sms_response = json_encode($sms_response);


                //Send Email

                $link_activate = base_url() . 'home/verify_email/' . $email_verification_code;
                $vars = [
                    '{username}' => $result[0]->fname,
                    '{email}' => $result[0]->email,
                    '{link_activation}' => $link_activate,
                    '{btn_link}' => $link_activate,
                    '{btn_text}' => 'Verify',
                    'otp' => $email_verification_code,
                ];


                $send = $this->email_template($result[0]->email, $vars, true, 'register');


                $this->db->update('users',
                    ['code' => $mobile_verification_code,
                        'code_status' => $code_status,
                        'email_verification_code' => $mobile_verification_code,
                        'otp_expire' => date('Y-m-d H:i:s'),
                        'status' => '0',
                    ],
                    ['id' => $result[0]->id]);


                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (120 * 60 * 24),
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

                $codee = http_response_code(200);
                echo json_encode(array('status' => true, 'code' => $codee, 'message' => 'OTP Send.', 'english' => $english_l['Verification_code_sent_to_your_mobile'], 'arabic' => $arabic_l['Verification_code_sent_to_your_mobile'], 'result' => $data, "email_status" => $send, "sms_status" => $sms_response));


            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'User not found', 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again']));
                // echo "error";
            }
        } else {

            $codee = http_response_code(200);
            echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'Email is missing', 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again']));
        }
    }


    public function _getLanguage()
    {
        $language = $this->language;

        if (isset($_COOKIE['language'])) {
            $language = $_COOKIE['language'];
        }

        return $language;
    }

    public
    function loginUser()
    {

        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));

        $data_array = json_decode(file_get_contents("php://input"), true);

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        if (!empty($data->username) && !empty($data->password)) {

            $username = $data->username;
            $dataa = $data->password;
            $password = hash("sha256", $data->password);
            // $password = $data->password;
            $result = $this->api_model->login_check($username, $password);
            // verify email is valid or not
            if (!empty($result)) {

                $status = $result[0]->status;

                if ($result[0]->role == '4') {
                    if ($result[0]->code_status == '1') {

                        if ($status == '1') {
                            $paylod = [
                                'iat' => time(),
                                'iss' => 'localhost',
                                'exp' => time() + (120 * 60 * 24),
                                'user_id' => $result[0]->id,
                                'email' => $result[0]->email,
                                'mobile' => $result[0]->mobile,
                                'user_role_id' => $result[0]->role,
                            ];
                            $token = Jwt::encode($paylod, SECRETE_KEY);
                            $user_list = $this->api_model->get_login_user($result[0]->id);
                            $user_list['profile_url'] = base_url() . 'uploads/profile_picture/' . $user_list['id'] . '/' . $user_list['picture'];

                            // $_SESSION['loggedin_user'] = $loginuser;
                            $data = array(
                                'user_id' => $result[0]->id,
                                'token' => $token,
                                'data' => $user_list
                            );
                            $codee = http_response_code(200);
                            echo json_encode(array('status' => true, 'code' => $codee, 'message' => $this->lang->line('login_successfully'), 'english' => $english_l['login_successfully'], 'arabic' => $arabic_l['login_successfully'], 'result' => $data));

                        } else {
                            $codee = http_response_code(200);
                            echo json_encode(array('status' => false, 'code' => $codee, 'message' => $this->lang->line('account_blocked'), 'english' => $english_l['account_blocked'], 'arabic' => $arabic_l['account_blocked'], 'appVersion' => $version, 'result' => ''));
                        }

                    }else{
                        $codee = http_response_code(200);
                        echo json_encode(array('status' => false, 'code' => $codee, 'message' => $this->lang->line('account_is_not_verified'), 'english' => $english_l['account_is_not_verified'], 'arabic' => $arabic_l['account_is_not_verified'], 'appVersion' => $version, 'result' => ''));

                    }
                } else {

                    $codee = http_response_code(200);
                    echo json_encode(array('status' => false, 'code' => $codee, 'message' => $this->lang->line('only_user_login'), 'english' => $english_l['only_user_login'], 'arabic' => $arabic_l['only_user_login'], 'appVersion' => $version, 'result' => ''));
                    exit();
                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => $this->lang->line('invalid_username_password'), 'english' => $english_l['invalid_username_password'], 'arabic' => $arabic_l['invalid_username_password'], 'appVersion' => $version, 'result' => ''));
                // echo "error";
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array('status' => false, 'code' => $codee, 'message' => $this->lang->line('invalid_username_password'), 'english' => $english_l['invalid_username_password'], 'arabic' => $arabic_l['invalid_username_password'], 'appVersion' => $version, 'result' => ''));
        }
    }

    public function token_update()
    {
        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));


        if (!empty($data->id)) {

            $username = $data->id;

            $result = $this->api_model->login_check3($username);
            if (!empty($result)) {

                $status = $result[0]->status;
                if ($status == '1') {


                    $paylod = [
                        'iat' => time(),
                        'iss' => 'localhost',
                        'exp' => time() + (120 * 60 * 24),
                        'user_id' => $result[0]->id,
                        'email' => $result[0]->email,
                        'mobile' => $result[0]->mobile,
                        'user_role_id' => $result[0]->role,
                    ];
                    $token = Jwt::encode($paylod, SECRETE_KEY);
                    $user_list = $this->api_model->get_login_user($result[0]->id);
                    $user_list['profile_url'] = base_url() . 'uploads/profile_picture/' . $user_list['id'] . '/' . $user_list['picture'];

                    // $_SESSION['loggedin_user'] = $loginuser;
                    $data = array(
                        'user_id' => $result[0]->id,
                        'token' => $token,
                        'data' => $user_list
                    );
                    $codee = http_response_code(200);
                    echo json_encode(array('status' => true, 'code' => $codee, 'message' => 'Token Updated Successfully.', 'result' => $data));

                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'User not Verified', 'appVersion' => $version, 'result' => ''));
                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'Incorrect Email or Password', 'appVersion' => $version, 'result' => ''));
                // echo "error";
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'Email or Password Not Found', 'appVersion' => $version, 'result' => ''));
        }
    }


    public
    function fb_login_register()
    {
        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));
        $language = "english";
        $data_array = json_decode(file_get_contents("php://input"), true);

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        

//        $rules = array(
//            array(
//                'field' => 'fname',
//                'label' => 'First Name',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'lname',
//                'label' => 'Last Name',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'email',
//                'label' => 'Email',
//                'rules' => 'required|valid_email',
//            ),
//            array(
//                'field' => 'password',
//                'label' => 'Password',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'mobile',
//                'label' => 'Mobile ',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'dial_code',
//                'label' => 'Dial Code ',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'city',
//                'label' => 'City ',
//                'rules' => 'trim|required',
//            ),
//        );

        if (!isset($data->email)) {

            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => $english_l['invalid_email_address_try_again'], 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again'], 'appVersion' => $version));
            exit();
        }
        if (!empty($data->email)) {



            $email = $data->email;
            $result = $this->api_model->check_email_user_fb($email);
            if ($result != false) {

//                if ($result['social'] == "facebook") {

                $user = $this->db->get_where('users', ['email' => $email]);
                $user = $user->row_array();


                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (120 * 60 * 24),
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'mobile' => $user['mobile'],
                    'user_role_id' => $user['role'],
                ];
                $token = Jwt::encode($paylod, SECRETE_KEY);


                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => $english_l['login_successfully2'], 'english' => $english_l['login_successfully2'], 'arabic' => $arabic_l['login_successfully2'], 'appVersion' => $version, 'result' => $user, 'token' => $token));
                exit();
//                } else {
//                    $codee = http_response_code(200);
//                    echo json_encode(array("status" => false, 'code' => $codee, "message" => $this->lang->line('not_fb'), 'appVersion' => $version));
//                    exit();
//                }
            }
            // $generateCode = rand(1000, 9999);
            $mobile_verification_code = getNumber(4);
            $email_verification_code = $mobile_verification_code;


            $d_fname = "";
            $d_lname = "";
            $city = "";
            $dial_code = 0;
            $rolee = 4;
            $password = " ";

            if (isset($data->first_name)) {
                $d_fname = $data->first_name;
            }
            if (isset($data->last_name)) {
                $d_lname = $data->last_name;
            }
            if (isset($data->city)) {
                $city = $data->city;
            }
            if (isset($data->dial_code)) {
                $dial_code = $data->dial_code;
            }
            if (isset($data->role)) {
                $rolee = $data->role;
            }

            if (isset($data->password)) {
                $password = $data->password;
            }


            $data = array(
                'fname' => $d_fname,
                'lname' => $d_lname,
                'username' => $data->first_name . ' ' . $data->last_name,
                'email' => $data->email,
                'mobile' => '',
                'city' => $city,
                'prefered_language' => "English",
                'dial_code' => $dial_code,
                'role' => $rolee,
                'password' => hash("sha256", $password),
                'reg_type' => "individual",
                'social' => "facebook",
                'code' => $mobile_verification_code,
                'email_verification_code' => $email_verification_code,
                'otp_expire' => date('Y-m-d H:i:s'),
                'status' => '1',
                'code_status' => '1',
            );
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['updated_on'] = date('Y-m-d H:i:s');
            $result = $this->api_model->insert_user_details($data);

            $user = $this->db->get_where('users', ['id' => $result]);
            $user = $user->row_array();
            if (!empty($user)) {
                $link_activate = base_url() . 'home/verify_email/' . $user['email_verification_code'];
                $vars = [
                    '{username}' => $user['fname'],
                    '{email}' => $user['email'],
                    '{link_activation}' => $link_activate,
                    '{btn_link}' => $link_activate,
                    '{btn_text}' => 'Verify',
                    'otp' => $email_verification_code,
                ];


                $user2 = $this->db->get_where('users', ['id' => $result]);
                $user2 = $user2->row_array();


                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (120 * 60 * 24),
                    'user_id' => $user2['id'],
                    'email' => $user2['email'],
                    'mobile' => $user2['mobile'],
                    'user_role_id' => $user2['role'],
                ];
                $token = Jwt::encode($paylod, SECRETE_KEY);


                $send = "";
//                $send = $this->email_template($email, $vars, true, 'register');
//                $this->session->set_tempdata('otp', $email_verification_code, 30);
                $codee = http_response_code(200);
                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => $english_l['register_successfully'], 'english' => $english_l['register_successfully'], 'arabic' => $arabic_l['register_successfully'], 'appVersion' => $version, "email_status" => $send, 'result' => $user2, 'token' => $token));


                $p_noti = ['email'=> $user2['email'] ];
               $this->getRegPushNotification($user2['fcm_token'], $p_noti);

            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => $english_l['already_have_account'], 'english' => $english_l['already_have_account'], 'arabic' => $arabic_l['already_have_account'], 'appVersion' => $version, 'result' => ''));
            }
        }
    }

    public
    function google_login_register()
    {

        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);
        $language = "english";
        $data_array = json_decode(file_get_contents("php://input"), true);

//        $rules = array(
//            array(
//                'field' => 'fname',
//                'label' => 'First Name',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'lname',
//                'label' => 'Last Name',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'email',
//                'label' => 'Email',
//                'rules' => 'required|valid_email',
//            ),
//            array(
//                'field' => 'password',
//                'label' => 'Password',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'mobile',
//                'label' => 'Mobile ',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'dial_code',
//                'label' => 'Dial Code ',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'city',
//                'label' => 'City ',
//                'rules' => 'trim|required',
//            ),
//        );

        if (!isset($data->zt)) {
            // echo validation_errors();
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => $english_l['invalid_email_address_try_again'], 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again'], 'appVersion' => $version));
            exit();
        }
        if (!empty($data->zt)) {


            $email = $data->zt;
            $result = $this->api_model->check_email_user_google($email);
            if ($result != false) {

//                if ($result['social'] == "google") {

                $user = $this->db->get_where('users', ['email' => $email]);
                $user = $user->row_array();


                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (120 * 60 * 24),
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'mobile' => $user['mobile'],
                    'user_role_id' => $user['role'],
                ];
                $token = Jwt::encode($paylod, SECRETE_KEY);


                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => $english_l['login_successfully2'], 'english' => $english_l['login_successfully2'], 'arabic' => $arabic_l['login_successfully2'], 'appVersion' => $version, 'result' => $user, 'token' => $token));
                exit();
//                } else {
//                    $codee = http_response_code(200);
//                    echo json_encode(array("status" => false, 'code' => $codee, "message" => $this->lang->line('not_google'), 'appVersion' => $version));
//                    exit();
//                }
            }
            // $generateCode = rand(1000, 9999);
            $mobile_verification_code = getNumber(4);
            $email_verification_code = $mobile_verification_code;


            $d_fname = "";
            $d_lname = "";
            $city = "";
            $dial_code = 0;
            $rolee = 4;
            $password = " ";

            if (isset($data->ET)) {
                $d_fname = $data->ET;
            }
            if (isset($data->GR)) {
                $d_lname = $data->GR;
            }
            if (isset($data->city)) {
                $city = $data->city;
            }
            if (isset($data->dial_code)) {
                $dial_code = $data->dial_code;
            }
            if (isset($data->role)) {
                $rolee = $data->role;
            }

            if (isset($data->password)) {
                $password = $data->password;
            }


            $data = array(
                'fname' => $d_fname,
                'lname' => $d_lname,
                'username' => $d_fname . ' ' . $d_lname,
                'email' => $data->zt,
                'mobile' => '',
                'city' => $city,
                'prefered_language' => "English",
                'dial_code' => $dial_code,
                'role' => $rolee,
                'password' => hash("sha256", $password),
                'reg_type' => "individual",
                'social' => "google",
                'code' => $mobile_verification_code,
                'email_verification_code' => $email_verification_code,
                'otp_expire' => date('Y-m-d H:i:s'),
                'status' => '1',
                'code_status' => '1',
            );
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['updated_on'] = date('Y-m-d H:i:s');
            $result = $this->api_model->insert_user_details($data);

            $user = $this->db->get_where('users', ['id' => $result]);
            $user = $user->row_array();
            if (!empty($user)) {
                $link_activate = base_url() . 'home/verify_email/' . $user['email_verification_code'];
                $vars = [
                    '{username}' => $user['fname'],
                    '{email}' => $user['email'],
                    '{link_activation}' => $link_activate,
                    '{btn_link}' => $link_activate,
                    '{btn_text}' => 'Verify',
                    'otp' => $email_verification_code,
                ];


                $user2 = $this->db->get_where('users', ['id' => $result]);
                $user2 = $user2->row_array();


                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (120 * 60 * 24),
                    'user_id' => $user2['id'],
                    'email' => $user2['email'],
                    'mobile' => $user2['mobile'],
                    'user_role_id' => $user2['role'],
                ];
                $token = Jwt::encode($paylod, SECRETE_KEY);


                $send = "";
                //$send = $this->email_template($email, $vars, true, 'register');
                //$this->session->set_tempdata('otp', $email_verification_code, 30);
                $codee = http_response_code(200);
                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => $english_l['register_successfully'], 'english' => $english_l['register_successfully'], 'arabic' => $arabic_l['register_successfully'], 'appVersion' => $version, "email_status" => $send, 'result' => $user2, 'token' => $token));

                $p_noti = ['email'=> $user2['email'] ];
               $this->getRegPushNotification($user2['fcm_token'], $p_noti);

            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => $english_l['already_have_account'], 'english' => $english_l['already_have_account'], 'arabic' => $arabic_l['already_have_account'], 'appVersion' => $version, 'result' => ''));
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => $english_l['invalid_email_address_try_again'], 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again'], 'appVersion' => $version));
            exit();
        }
    }


    public
    function apple_login_register()
    {

        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);
        $language = "english";
        $data_array = json_decode(file_get_contents("php://input"), true);

//        $rules = array(
//            array(
//                'field' => 'fname',
//                'label' => 'First Name',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'lname',
//                'label' => 'Last Name',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'email',
//                'label' => 'Email',
//                'rules' => 'required|valid_email',
//            ),
//            array(
//                'field' => 'password',
//                'label' => 'Password',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'mobile',
//                'label' => 'Mobile ',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'dial_code',
//                'label' => 'Dial Code ',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'city',
//                'label' => 'City ',
//                'rules' => 'trim|required',
//            ),
//        );

        if (!isset($data->email)) {
            // echo validation_errors();
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => $english_l['invalid_email_address_try_again'], 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again'], 'appVersion' => $version));
            exit();
        }
        if (!empty($data->email)) {


            $email = $data->email;
            $result = $this->api_model->check_email_user_apple($email);
            if ($result != false) {

//                if ($result['social'] == "google") {

                $user = $this->db->get_where('users', ['email' => $email]);
                $user = $user->row_array();


                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (120 * 60 * 24),
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'mobile' => $user['mobile'],
                    'user_role_id' => $user['role'],
                ];
                $token = Jwt::encode($paylod, SECRETE_KEY);


                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => $english_l['login_successfully2'], 'english' => $english_l['login_successfully2'], 'arabic' => $arabic_l['login_successfully2'], 'appVersion' => $version, 'result' => $user, 'token' => $token));
                exit();
//                } else {
//                    $codee = http_response_code(200);
//                    echo json_encode(array("status" => false, 'code' => $codee, "message" => $this->lang->line('not_google'), 'appVersion' => $version));
//                    exit();
//                }
            }
            // $generateCode = rand(1000, 9999);
            $mobile_verification_code = getNumber(4);
            $email_verification_code = $mobile_verification_code;


            $d_fname = "";
            $d_lname = "";
            $city = "";
            $dial_code = 0;
            $rolee = 4;
            $password = " ";

            if (isset($data->FULL_NAME)) {
                $d_fname = $data->FULL_NAME;
            }
            if (isset($data->LAST_NAME)) {
                $d_lname = $data->LAST_NAME;
            }
            if (isset($data->city)) {
                $city = $data->city;
            }
            if (isset($data->dial_code)) {
                $dial_code = $data->dial_code;
            }
            if (isset($data->role)) {
                $rolee = $data->role;
            }

            if (isset($data->password)) {
                $password = $data->password;
            }


            $data = array(
                'fname' => $d_fname,
                'lname' => $d_lname,
                'username' => $d_fname . ' ' . $d_lname,
                'email' => $data->email,
                'mobile' => '',
                'city' => $city,
                'prefered_language' => "English",
                'dial_code' => $dial_code,
                'role' => $rolee,
                'password' => hash("sha256", $password),
                'reg_type' => "individual",
                'social' => "apple",
                'code' => $mobile_verification_code,
                'email_verification_code' => $email_verification_code,
                'otp_expire' => date('Y-m-d H:i:s'),
                'status' => '1',
                'code_status' => '1',
            );
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['updated_on'] = date('Y-m-d H:i:s');
            $result = $this->api_model->insert_user_details($data);

            $user = $this->db->get_where('users', ['id' => $result]);
            $user = $user->row_array();
            if (!empty($user)) {
                $link_activate = base_url() . 'home/verify_email/' . $user['email_verification_code'];
                $vars = [
                    '{username}' => $user['fname'],
                    '{email}' => $user['email'],
                    '{link_activation}' => $link_activate,
                    '{btn_link}' => $link_activate,
                    '{btn_text}' => 'Verify',
                    'otp' => $email_verification_code,
                ];


                $user2 = $this->db->get_where('users', ['id' => $result]);
                $user2 = $user2->row_array();


                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (120 * 60 * 24),
                    'user_id' => $user2['id'],
                    'email' => $user2['email'],
                    'mobile' => $user2['mobile'],
                    'user_role_id' => $user2['role'],
                ];
                $token = Jwt::encode($paylod, SECRETE_KEY);


                $send = "";
                //$send = $this->email_template($email, $vars, true, 'register');
                //$this->session->set_tempdata('otp', $email_verification_code, 30);
                $codee = http_response_code(200);
                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => $english_l['register_successfully'], 'english' => $english_l['register_successfully'], 'arabic' => $arabic_l['register_successfully'], 'appVersion' => $version, "email_status" => $send, 'result' => $user2, 'token' => $token));

                 $p_noti = ['email'=> $user2['email'] ];
               $this->getRegPushNotification($user2['fcm_token'], $p_noti);

            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => $english_l['already_have_account'], 'english' => $english_l['already_have_account'], 'arabic' => $arabic_l['already_have_account'], 'appVersion' => $version, 'result' => ''));
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => $english_l['invalid_email_address_try_again'], 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again'], 'appVersion' => $version));
            exit();
        }
    }


    public
    function deauthorize()
    {

    }


    public
    function registerUser()
    {
        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));
        $language = "english";
        $data_array = json_decode(file_get_contents("php://input"), true);
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $this->form_validation->set_data($data_array);

//        $rules = array(
//            array(
//                'field' => 'fname',
//                'label' => 'First Name',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'lname',
//                'label' => 'Last Name',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'email',
//                'label' => 'Email',
//                'rules' => 'required|valid_email',
//            ),
//            array(
//                'field' => 'password',
//                'label' => 'Password',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'mobile',
//                'label' => 'Mobile ',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'dial_code',
//                'label' => 'Dial Code ',
//                'rules' => 'trim|required',
//            ),
//            array(
//                'field' => 'city',
//                'label' => 'City ',
//                'rules' => 'trim|required',
//            ),
//        );

        $rules = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|min_length[1]|max_length[25]',
            ),
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
        );

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == FALSE) {
            // echo validation_errors();
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => validation_errors(), 'appVersion' => $version));
            exit();
        }
        if (!empty($data)) {

//            if (isset($data->mobile) && isset($data->dial_code)) {
//                $phonee = $data->dial_code . "" . $data->mobile;
//            } else {
//                $phonee = "";
//            }

            if (isset($data->mobile)) {
                $phonee = $data->mobile;
            } else {
                $phonee = "";
            }


            $number = preg_replace('/\s/', '', $phonee);
            $check_number = $this->api_model->check_user_number($number);
            if ($check_number == true) {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => $this->lang->line('mobile_already_exist'), 'english' => $english_l['mobile_already_exist'], 'arabic' => $arabic_l['mobile_already_exist'], 'appVersion' => $version));
                exit();
            }
            $email = $data->email;
            $result = $this->api_model->check_email_user($email);
            if ($result == true) {

                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => $this->lang->line('already_have_account'), 'english' => $english_l['already_have_account'], 'arabic' => $arabic_l['already_have_account'], 'appVersion' => $version));
                exit();
            }
            // $generateCode = rand(1000, 9999);
            $mobile_verification_code = getNumber(4);
            $email_verification_code = $mobile_verification_code;


            $this->load->library('SendSmart');
            $sms = $this->lang->line('pioneer_verification_code_is') . $mobile_verification_code;
            // $sms_response = $this->testunifonic->sendMessage($phone, $sms);

            $sms_response = $this->sendsmart->sms($number, $sms);
            //SMS verification process start //International
//            $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
//            $sendotp = $this->sendotp("+971545899756", $sms);
            $sms_response = json_encode($sms_response);

            $d_fname = "";
            $d_lname = "";
            $city = "";
            $dial_code = 0;
            $rolee = 4;

            if (isset($data->fname)) {
                $d_fname = $data->fname;
            }
            if (isset($data->lname)) {
                $d_lname = $data->lname;
            }
            if (isset($data->city)) {
                $city = $data->city;
            }
            if (isset($data->dial_code)) {
                $dial_code = $data->dial_code;
            }
            if (isset($data->role)) {
                $rolee = $data->role;
            }


            $data = array(
                'fname' => $d_fname,
                'lname' => $d_lname,
                'username' => $data->fname . ' ' . $data->lname,
                'email' => $data->email,
                'mobile' => $phonee,
                'city' => $city,
                'prefered_language' => "English",
                'dial_code' => $dial_code,
                'role' => $rolee,
                'password' => hash("sha256", $data->password),
                'reg_type' => "individual",
                'social' => "mobile",
                'code' => $mobile_verification_code,
                'email_verification_code' => $email_verification_code,
                'otp_expire' => date('Y-m-d H:i:s'),
                'status' => '0',
            );

            // print_r($data);die();
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['updated_on'] = date('Y-m-d H:i:s');


            $check_user2 = $this->api_model->check_email_user2($email);
            if($check_user2 == true) {
                $result = $this->api_model->update_user_details2($check_user2,$data);

            }else{
                $result = $this->api_model->insert_user_details2($data);
            }


            $user = $this->db->get_where('users2', ['id' => $result]);
            $user = $user->row_array();
            if (!empty($user)) {
                $link_activate = base_url() . 'home/verify_email/' . $user['email_verification_code'];
                $vars = [
                    '{username}' => $user['fname'],
                    '{email}' => $user['email'],
                    '{password}' => $this->input->post('password'),
                    '{login_link}' => $this->lang->line("please_login") . ':<a href="'.base_url().'?loginUrl=loginFirst" > ' . $this->lang->line("go_to_account") . ' </a>',
                    '{email_verification_code}'=>$user['code'],
                    '{activation_link}' => $link_activate,
                    // '{btn_link}' => $link_activate,
                    '{btn_text}' => $this->lang->line('activate_your_account')
                ];

                //$send = $this->email_template($email, $vars, true, 'register');
                $send = $this->et->email_template($email, 'user_registration', $vars, true);
                // $this->session->set_tempdata('otp', $email_verification_code, 30);
                $codee = http_response_code(200);
                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => $this->lang->line('register_successfully'), 'english' => $english_l['register_successfully'], 'arabic' => $arabic_l['register_successfully'], 'appVersion' => $version, "email_status" => $send, "sms_status" => $sms_response));
               // $p_noti = ['email'=> $user['email'] ];
                //$this->getRegPushNotification($user['fcm_token'], $p_noti);

            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => $this->lang->line('already_have_account'), 'english' => $english_l['already_have_account'], 'arabic' => $arabic_l['already_have_account'], 'appVersion' => $version, 'result' => ''));
            }
        }
    }

    public
    function sendotp($number, $sms)
    {
        $sms_response = $this->testunifonic->sendMessage($number, $sms);

        if (isset($sms_response->MessageID)) {
            return true;
        } else {
            return false;
        }
    }


    public
    function verifyOtp()
    {
        $version = $this->config->item('appVersion');


        $data_array = json_decode(file_get_contents("php://input"), true);
        $data = json_decode(file_get_contents("php://input"));
        $code = $data->code;
        $username = $data->email;

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $data = $this->api_model->verify_code2_user2($code, $username);
        $result = $this->api_model->check_email2_users2($username);


        $create_at = $result[0]->otp_expire;
        $submit = date('Y-m-d H:i:s');

        $from = strtotime($create_at);
        $to = strtotime($submit);
        $otp_expire = round(abs($to - $from) / 60, 2);


        if ($otp_expire <= 3) {

            if (!empty($data) && !empty($result)) {
                if ($data['code'] == $code) {

//                    $this->db->update('users',
//                        ['status' => '1', 'code_status' => '1',
//
//                        ],
//                        ['id' => $result[0]->id]);

                    $data3 = array(
                        'fname' => $result[0]->fname,
                        'lname' => $result[0]->lname,
                        'username' => $result[0]->username,
                        'email' => $result[0]->email,
                        'mobile' => $result[0]->mobile,
                        'city' => $result[0]->city,
                        'prefered_language' => "English",
                        'dial_code' => $result[0]->dial_code,
                        'role' => $result[0]->role,
                        'password' => $result[0]->password,
                        'reg_type' => "individual",
                        'social' => "mobile",
                        'code' => $result[0]->code,
                        'email_verification_code' => $result[0]->email_verification_code,
                        'otp_expire' => date('Y-m-d H:i:s'),
                        'status' => '1',
                        'code_status'=>'1',
                    );

                    // print_r($data);die();
                    $data3['created_on'] = date('Y-m-d H:i:s');
                    $data3['updated_on'] = date('Y-m-d H:i:s');


                    $check_user = $this->api_model->check_email_user_with_id($username);
                    if($check_user == false) {
                        $result = $this->api_model->insert_user_details($data3);
                    }else{
                        $result = $check_user['id'];
                    }

                    $user = $this->db->get_where('users', ['id' => $result]);
                    $user = $user->row_array();

                    $paylod = [
                        'iat' => time(),
                        'iss' => 'localhost',
                        'exp' => time() + (120 * 60 * 24),
                        'user_id' => $user['id'],
                        'email' => $user['email'],
                        'mobile' => $user['mobile'],
                        'user_role_id' => $user['role'],
                    ];
                    $token = Jwt::encode($paylod, SECRETE_KEY);
                    $user_list = $this->api_model->get_login_user($user['id']);

                    $data2 = array(
                        'user_id' => $user['id'],
                        'token' => $token,
                        'data' => $user_list
                    );

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'OTP IS VERIFIED', 'english' => $english_l['verified_successfully_please_check_email'], 'arabic' => $arabic_l['verified_successfully_please_check_email'], 'appVersion' => $version, 'result' => $data2));
                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Invalid Code', 'english' => $english_l['verification_code_is_invalid'], 'arabic' => $arabic_l['verification_code_is_invalid'], 'appVersion' => $version));
                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Invalid Email or Code', 'english' => $english_l['verification_code_is_invalid'], 'arabic' => $arabic_l['verification_code_is_invalid'], 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Your Otp is Expire', 'english' => $english_l['otp_expire'], 'arabic' => $arabic_l['otp_expire'], 'appVersion' => $version));

        }
    }


    public
    function resendCode()
    {
        $data_array = json_decode(file_get_contents("php://input"), true);
        $data = json_decode(file_get_contents("php://input"));
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $phone = preg_replace('/\s/', '', $data->phone);
        // $phone=$data->phone;
        if (!empty($phone)) {
            $result = $this->api_model->check_phone($phone);


            if (!empty($result)) {

                $code_status = 0;
                $mobile_verification_code = getNumber(4);
                $email_verification_code = $mobile_verification_code;

                //SMS verification process start
                $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
                $sendotp = $this->sendotp($phone, $sms);
                $sms_response = json_encode($sendotp);


                //Send Email

                $link_activate = base_url() . 'home/verify_email/' . $email_verification_code;
                $vars = [
                    '{username}' => $result['fname'],
                    '{email}' => $result['email'],
                    '{link_activation}' => $link_activate,
                    '{btn_link}' => $link_activate,
                    '{btn_text}' => 'Verify',
                    'otp' => $email_verification_code,
                ];


                $send = $this->email_template($result['email'], $vars, true, 'Verification Code');


                $this->db->update('users',
                    ['code' => $mobile_verification_code,
                        'code_status' => $code_status,
                        'email_verification_code' => $mobile_verification_code,
                        'status' => '0',
                    ],
                    ['id' => $result['id']]);

                $output = json_encode(['success' => true, 'codeGenerated' => true, 'message' => 'Verification code has been sent to your mobile and Email', 'english' => $english_l['Verification_code_sent_to_your_mobile'], 'arabic' => $arabic_l['Verification_code_sent_to_your_mobile']]);
                return print_r($output);
            } else {
                $output = json_encode(['error' => true, 'codeGenerated' => false, 'message' => 'Mobile number is not registered.', 'english' => $english_l['mobile_number_not_registered'], 'arabic' => $arabic_l['mobile_number_not_registered']]);
                return print_r($output);
            }

        }
    }


    public function resendOtp()
    {

        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));


        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        if (!empty($data->email)) {

            $username = $data->email;
            $result = $this->api_model->check_email2_users2($username);
            // verify email is valid or not
            if (!empty($result)) {


                $status = $result[0]->status;
                // check if user is active and check if paid with status is 1

                // Generate JWT Token with payload
                // payload have expire time information with user role id and user id

                //SMS verification process start
                $number = $result[0]->mobile;
                $code_status = 0;

                $mobile_verification_code = getNumber(4);
                $email_verification_code = $mobile_verification_code;

                //SMS verification process start
                $this->load->library('SendSmart');
                $sms = $this->lang->line('pioneer_verification_code_is') . $mobile_verification_code;
                // $sms_response = $this->testunifonic->sendMessage($phone, $sms);

                $sms_response = $this->sendsmart->sms($number, $sms);
                //SMS verification process start //International
//            $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
//            $sendotp = $this->sendotp("+971545899756", $sms);
                $sms_response = json_encode($sms_response);


                //Send Email

                $link_activate = base_url() . 'home/verify_email/' . $email_verification_code;
                $vars = [
                    '{username}' => $result[0]->fname,
                    '{email}' => $result[0]->email,
                    '{link_activation}' => $link_activate,
                    '{btn_link}' => $link_activate,
                    '{btn_text}' => 'Verify',
                    'otp' => $email_verification_code,
                ];


                $send = $this->email_template($result[0]->email, $vars, true, 'register');


                $this->db->update('users2',
                    ['code' => $mobile_verification_code,
                        'code_status' => $code_status,
                        'email_verification_code' => $mobile_verification_code,
                        'otp_expire' => date('Y-m-d H:i:s'),
                        'status' => '0',
                    ],
                    ['id' => $result[0]->id]);


                $paylod = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (120 * 60 * 24),
                    'user_id' => $result[0]->id,
                    'email' => $result[0]->email,
                    'mobile' => $result[0]->mobile,
                    'user_role_id' => $result[0]->role,
                ];
                $token = Jwt::encode($paylod, SECRETE_KEY);
                $user_list = $this->api_model->get_login_user2($result[0]->id);
                // $_SESSION['loggedin_user'] = $loginuser;
                $data = array(
                    'user_id' => $result[0]->id,
                    'token' => $token,
                    'data' => $user_list
                );
                $codee = http_response_code(200);
                echo json_encode(array('status' => true, 'code' => $codee, 'message' => 'OTP Send.', 'english' => $english_l['Verification_code_sent_to_your_mobile'], 'arabic' => $arabic_l['Verification_code_sent_to_your_mobile'], 'result' => $data, "email_status" => $send, "sms_status" => $sms_response));


            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'User not found', 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again']));
            }
        } else {

            $codee = http_response_code(200);
            echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'Email is missing', 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again']));
        }
    }


    public
    function forgotPassword()
    {

        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->email)) {
            $email = $data->email;
            $query = $this->api_model->check_email2($email);
            if (!empty($query)) {
                $email_code = getNumber(4);
                $this->db->update('users', ['reset_password_code' => $email_code], ['email' => $email]);

                $notification = "Reset Password Poineer";
                $data = array();
                $data = [
                    '{email}' => $email,
                    '{email_code}' => $email_code
                ];
                $email_message = $this->load->view('email_templates/api_email_forget', $data, true);
                if ($data) {
                    foreach ($data as $key => $value) {
                        $email_message = str_replace($key, $value, $email_message);
                    }
                }
                $this->email->to($email);
                $this->email->subject($notification);
                $this->email->message($email_message);
                $is_send = $this->email->send();


//                $sms = 'Your Forget Password Code is ' . $email_code;
//                $sendotp = $this->sendotp($query[0]->mobile, $sms);
//                $sms_response = json_encode($sendotp);


                //SMS verification process start
                $this->load->library('SendSmart');
                $sms = 'Your Forget Password Code is ' . $email_code;
                // $sms_response = $this->testunifonic->sendMessage($phone, $sms);

                $sms_response = $this->sendsmart->sms($query[0]->mobile, $sms);
                //SMS verification process start //International
//            $sms = 'Your Pioneer account verification code is ' . $mobile_verification_code;
//            $sendotp = $this->sendotp("+971545899756", $sms);
                $sms_response = json_encode($sms_response);


                $codee = http_response_code(200);
                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'CODE SEND', 'english' => $english_l['Verification_code_sent_to_your_mobile'], 'arabic' => $arabic_l['Verification_code_sent_to_your_mobile'], 'appVersion' => $version, 'sms_status' => $sms_response, 'email_status' => $is_send));


            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User Not Found', 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again'], 'appVersion' => $version));


            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User Not Found', 'english' => $english_l['invalid_email_address_try_again'], 'arabic' => $arabic_l['invalid_email_address_try_again'], 'appVersion' => $version));


        }
    }

    public
    function updatePassword()
    {
        $version = $this->config->item('appVersion');

        $data_array = json_decode(file_get_contents("php://input"), true);
        $data = json_decode(file_get_contents("php://input"));
        $this->form_validation->set_data($data_array);

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $encoded_id = $data->email_code;

        if (!empty($data->email_code)) {


            $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'trim|required|min_length[5]'),
                array(
                    'field' => 'email_code',
                    'label' => 'Email Code',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'cpassword',
                    'label' => 'Confirm password',
                    'rules' => 'trim|min_length[5]|matches[password]')
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Invalid Password', 'english' => $english_l['invalid_current_password'], 'arabic' => $arabic_l['invalid_current_password'], 'appVersion' => $version));

            } else {

                $code = $data->email_code;
                $password = $data->password;
                $user = $this->db->get_where('users', ['reset_password_code' => $code])->row_array();

                if (!empty($user)) {
                    $this->db->update('users', ['password' => hash("sha256", $password), 'reset_password_code' => 0], ['id' => $user['id']]);
                    $this->session->set_flashdata('success', 'Password has been updated');

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Password Changed Successfully', 'english' => $english_l['password_changed_successfully'], 'arabic' => $arabic_l['password_changed_successfully'], 'appVersion' => $version));

                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Invalid Verification Code', 'english' => $english_l['verification_code_is_invalid'], 'arabic' => $arabic_l['verification_code_is_invalid'], 'appVersion' => $version));

                }

            }
        } else {

            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Verification Code is Required', 'appVersion' => $version));
        }
    }


    public
    function getUserProfile()
    {
        $version = $this->config->item('appVersion');

        $userId = validateToken();

        $result = $this->db->get('users', ['id' => $userId])->row_array();

        if (!empty($result)) {
            echo json_encode(array("status" => true, "message" => 'Get Profile Successfully', 'result' => $result));
        } else {
            echo json_encode(array("status" => false, "message" => 'Failed to fetch user profile'));
        }
    }


    public
    function changePassword()
    {
        $version = $this->config->item('appVersion');

        $data_array = json_decode(file_get_contents("php://input"), true);
        $data = json_decode(file_get_contents("php://input"));
        $this->form_validation->set_data($data_array);

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $this->load->library('form_validation');
        $rules = array(
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[5]'),
            array(
                'field' => 'old_password',
                'label' => 'Old Password',
                'rules' => 'trim|required|min_length[5]')
        );


        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Password Field Missing', 'english' => $english_l['password_length_should_be_long'], 'arabic' => $arabic_l['password_length_should_be_long'], 'appVersion' => $version));

        } else {


            $userId = validateToken();


            $old_password = hash("sha256", $data->old_password);

            $result = $this->api_model->check_email_pass($userId, $old_password);

            if ($result) {
                if (isset($data->password) && !empty($data->password)) {
                    $results = $this->api_model->update_password_new($userId, array(
                        'password' => hash("sha256", $data->password),
                    ));;
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'PASSWORD CHANGE SUCCESSFULLY', 'english' => $english_l['password_updated_successfully'], 'arabic' => $arabic_l['password_updated_successfully'], 'appVersion' => $version, 'result' => $result));


                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Password Not Found', 'english' => $english_l['invalid_current_password'], 'arabic' => $arabic_l['invalid_current_password'], 'appVersion' => $version));

                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Incorrect Old Password or Token', 'english' => $english_l['invalid_current_password'], 'arabic' => $arabic_l['invalid_current_password'], 'appVersion' => $version));

            }
        }
    }

    public
    function categories_data($category_id)
    {

        $version = $this->config->item('appVersion');

        $data = array();
        //$this->db->select('*')->from('home_slider')->where('start_date <=', $current_date)->where('status', 'active')->where('end_date >=', $current_date)->order_by('sort_order', 'ASC')->get()->result_array();
        $inputs = json_decode(file_get_contents("php://input"));

        if (!empty($inputs->category_id)) {
            $category_id = $inputs->category_id;
        }

        if (isset($category_id) && !empty($category_id)) {
            $data['category'] = $this->db->get_where('item_category', ['id' => $category_id])->row_array();
        } else {
            $category_data = $this->db->select('*')->order_by('sort_order', 'ASC')->get_where('item_category', ['status' => 'active', 'show_web' => 'yes'])->result_array();
            $data['category'] = $category_data;
        }

        $codee = http_response_code(200);
        echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Category Data', 'appVersion' => $version, 'result' => $data));
    }

    public
    function upcoming_auction_data()
    {

        $version = $this->config->item('appVersion');


        $data = array();
        //$this->db->select('*')->from('home_slider')->where('start_date <=', $current_date)->where('status', 'active')->where('end_date >=', $current_date)->order_by('sort_order', 'ASC')->get()->result_array();
        $date = date('Y-m-d');
        $datee['time'] = date('Y-m-d H:i:s', time());


        $data['live_auctions'] = $this->home_model->upcoming_auctions_home($date, 'time');
        $data['online_auctions'] = $this->home_model->singleOnlineAuctionsWithItem();
        $data['nearlyCloseAuctions'] = $this->home_model->nearlyCloseAuctions();


        $codee = http_response_code(200);
        echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Get Home Listing Successfully', 'appVersion' => $version, 'result' => $data));

    }

    public
    function AllFeaturedItems()
    {
        $version = $this->config->item('appVersion');

        $data = array();


        @$auction_id = '';
        $data['active_auction_categories'] = $this->home_model->get_items_categories();

        foreach ($data['active_auction_categories'] as $key => $value) {
            $count = 0;
            $auctions_online = $this->home_model->get_online_auctions($value['id']);
            if (!empty($auctions_online)) {
                $data['active_auction_categories'][$key]['auction_id'] = $auctions_online['id'];
                $data['active_auction_categories'][$key]['expiry_time'] = $auctions_online['expiry_time'];
                $count = $this->db->select('*')->from('auction_items')->where('auction_id', $auctions_online['id'])->where('sold_status', 'not')->where('auction_items.bid_start_time <', date('Y-m-d H:i'))->get()->result_array();
                $count = count($count);
            }
            if ($this->session->userdata('logged_in')) {
                $u_id = $this->session->userdata('logged_in')->id;
                $close_auctions = $this->db->where("FIND_IN_SET('" . $u_id . "', close_auction_users)")->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active', 'access_type' => 'closed', 'category_id' => $value['id']])->result_array();
                if ($close_auctions) {
                    foreach ($close_auctions as $key1 => $close_auction) {
                        $item_ids = array();
                        $sub_total = count($this->lam->get_live_auction_items($close_auction['id'], 0, 0, $item_ids));
                        $count = $count + $sub_total;
                    }
                }
            }
            $live_auctions = $this->db->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active', 'access_type' => 'live', 'category_id' => $value['id']])->result_array();
            if ($live_auctions) {
                foreach ($live_auctions as $key2 => $live_auction) {
                    $live_item_ids = array();
                    $total = count($this->lam->get_live_auction_items($live_auction['id'], 0, 0, $live_item_ids));
                    $count = $count + $total;
                }
            }
            $data['active_auction_categories'][$key]['item_count'] = $count;

            $catID = $value['id'];
            @$auction_id = $data['active_auction_categories'][$key]['auction_id'];


            $featured_items = $this->home_model->featured_item($catID, $auction_id);
            foreach ($featured_items as $_list) {
                //	print_r($_list);
                $data['featured_items'][$catID][] = array(
                    'id' => $_list['id'],
                    'name' => $_list['name'],
                    'item_id' => $_list['item_id'],
                    'auction_id' => $_list['auction_id'],
                    'item_images' => $_list['item_images'],
                    'bid_start_price' => $_list['bid_start_price'],
                    'bid_amount' => '',
                    'itemSpecification' => $_list['itemSpecification'],
                    'itemMileage' => $_list['itemMileage'],
                    'mileageType' => $_list['mileageType'],
                    'visits' => '',
                    'item_datail' => $_list['item_datail'],
                    'itemYear' => $_list['itemYear'],
                    'bid_end_time' => $_list['bid_end_time']
                );
            }

        }

        $codee = http_response_code(200);
        echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Feature Items', 'appVersion' => $version, 'result' => $data));


    }

    public
    function AllHomeListing()
    {
        $version = $this->config->item('appVersion');


        $userId = "";

        $data = array();
        $data['home_class'] = 'home_class';
        //slider
        $current_date = date('Y-m-d H:i:s');
        $data['slider'] = $this->home_model->get_home_slider();

        $inputs = json_decode(file_get_contents("php://input"));
        if (isset($inputs->category_id) && !empty($inputs->category_id)) {
            $category_id = $inputs->category_id;
            $data['category'] = $this->db->get_where('item_category', ['id' => $category_id])->row_array();
        } else {
            $category_data = $this->db->select('*')->order_by('sort_order', 'ASC')->get_where('item_category', ['status' => 'active', 'show_web' => 'yes'])->row_array();
            $category_id = $category_data['id'];
            $data['category'] = $category_data;
        }


        $data['categoryId'] = $category_id;
        $auction = $this->db->order_by('start_time', 'ASC')->get_where('auctions', ['category_id' => $category_id, 'access_type' => 'online', 'auctions.status' => 'active', 'auctions.expiry_time >' => date('Y-m-d H:i:s')])->row_array();


        $data['latestBids'] = $this->home_model->latestBidsOnItems($category_id, (isset($auction['id']) ? $auction['id'] : 0));
        // Popular bids
        $data['popularBids'] = $this->home_model->popularBidsOnItems($category_id, (isset($auction['id']) ? $auction['id'] : 0));
        if (!empty($data['popularBids'])) {
            foreach ($data['popularBids'] as $key => $popularBid) {
                $visist_count = $this->db->select('COUNT(id) as visits')->get_where('online_auction_item_visits', ['auction_id' => $popularBid['auctionId'], 'item_id' => $popularBid['itemId']])->row_array();
                $data['popularBids'][$key]['visits'] = $visist_count['visits'];
            }
        }


        $date = date('Y-m-d');
        $datee['time'] = date('Y-m-d H:i:s', time());
        $data['live_auctions'] = $this->home_model->upcoming_auctions_home($date, 'time');
        $data['online_auctions'] = $this->home_model->singleOnlineAuctionsWithItem();
        $data['nearlyCloseAuctions'] = $this->home_model->nearlyCloseAuctions();
        $data['media'] = $this->db->order_by('id', 'DESC')->limit(3)->get_where('item', ['item_status' => 'completed'])->result_array();
        $data['faqs'] = $this->db->get('ques_ans')->result_array();


        @$auction_id = '';
        $data['active_auction_categories'] = $this->home_model->get_items_categories();

        foreach ($data['active_auction_categories'] as $key => $value) {
            $count = 0;
            $auctions_online = $this->home_model->get_online_auctions($value['id']);
            if (!empty($auctions_online)) {
                $data['active_auction_categories'][$key]['auction_id'] = $auctions_online['id'];
                $data['active_auction_categories'][$key]['expiry_time'] = $auctions_online['expiry_time'];
                $count = $this->db->select('*')->from('auction_items')->where('auction_id', $auctions_online['id'])->where('sold_status', 'not')->where('auction_items.bid_start_time <', date('Y-m-d H:i'))->get()->result_array();
                $count = count($count);
            }
            if ($userId) {
                $u_id = $userId;
                $close_auctions = $this->db->where("FIND_IN_SET('" . $u_id . "', close_auction_users)")->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active', 'access_type' => 'closed', 'category_id' => $value['id']])->result_array();
                if ($close_auctions) {
                    foreach ($close_auctions as $key1 => $close_auction) {
                        $item_ids = array();
                        $sub_total = count($this->lam->get_live_auction_items($close_auction['id'], 0, 0, $item_ids));
                        $count = $count + $sub_total;
                    }
                }
            }
            $live_auctions = $this->db->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active', 'access_type' => 'live', 'category_id' => $value['id']])->result_array();
            if ($live_auctions) {
                foreach ($live_auctions as $key2 => $live_auction) {
                    $live_item_ids = array();
                    $total = count($this->lam->get_live_auction_items($live_auction['id'], 0, 0, $live_item_ids));
                    $count = $count + $total;
                }
            }
            $data['active_auction_categories'][$key]['item_count'] = $count;

            $catID = $value['id'];
            @$auction_id = $data['active_auction_categories'][$key]['auction_id'];


            $featured_items = $this->home_model->featured_item($catID, $auction_id);
            foreach ($featured_items as $_list) {
                //	print_r($_list);
                $data['featured_items'][$catID][] = array(
                    'id' => $_list['id'],
                    'name' => $_list['name'],
                    'item_id' => $_list['item_id'],
                    'auction_id' => $_list['auction_id'],
                    'item_images' => $_list['item_images'],
                    'bid_start_price' => $_list['bid_start_price'],
                    'bid_amount' => '',
                    'itemSpecification' => $_list['itemSpecification'],
                    'itemMileage' => $_list['itemMileage'],
                    'mileageType' => $_list['mileageType'],
                    'visits' => '',
                    'item_datail' => $_list['item_datail'],
                    'itemYear' => $_list['itemYear'],
                    'bid_end_time' => $_list['bid_end_time']
                );
            }


        }

        $codee = http_response_code(200);
        echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'ALL HOME PAGE DATA', 'appVersion' => $version, 'result' => $data));

    }


    public function singleOnlineAuctionsWithItem()
    {
        $date = new DateTime("now");
        $current_date = $date->format('Y-m-d H:i:s');
        $this->db->select('auctions.id, auctions.title as auction_title, auctions.detail as auctionDetail, auction_items.auction_id,auctions.start_time,auctions.expiry_time,auction_items.bid_start_time,auction_items.bid_end_time,auctions.access_type,auction_items.item_id,auction_items.lot_no,auction_items.order_lot_no,auction_items.bid_start_price,item_category.id as categoryId,item.id,item.item_images,item.feature,files.id,files.name,files.path,item.name as item_name');
        $this->db->from('auctions');
        $this->db->where('auctions.start_time <=', $current_date);
        $this->db->where('auctions.expiry_time >=', $current_date);
        $this->db->where('auction_items.bid_start_time <=', $current_date);
        $this->db->where('auction_items.bid_end_time >=', $current_date);
        $this->db->where('access_type', 'online');
        $this->db->where('auctions.status', 'active');
        $this->db->where('item_category.status', 'active');
        $this->db->where('auction_items.sold_status','not');
        $this->db->join('auction_items', 'auction_items.auction_id = auctions.id', 'left');
        $this->db->join('item_category', 'item_category.id = auctions.category_id', 'left');
        $this->db->join('item', 'item.id = auction_items.item_id', 'left');
        $this->db->join('files', 'files.id = item_category.category_icon', 'left');
        $this->db->group_by('auctions.id');
        $this->db->order_by('auctions.start_time', 'ASC');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else
            return [];
    }

    public function upcoming_auctions_home($date)
    {
        $date = new DateTime("now");
        $current_date = $date->format('Y-m-d H:i:s');
        $this->db->select('auctions.id,auctions.title as auction_title, auctions.detail as auctionDetail, item_category.category_icon as categoryIcon, item_category.id as categoryId,auction_items.auction_id,auctions.start_time,auctions.expiry_time,auction_items.item_id,auction_items.bid_start_price,auction_items.lot_no,auction_items.order_lot_no,auction_items.bid_start_time,auction_items.bid_end_time,item.id,item.item_images,item.feature,files.id,files.name,files.path,item.name as item_name,auctions.access_type');
        $this->db->from('auctions');
        $this->db->where('auctions.start_time >', $current_date);
        $this->db->or_where('auctions.expiry_time >', $current_date);
        $this->db->where('auctions.access_type', 'live');
        $this->db->where('item_category.status', 'active');
        $this->db->join('auction_items', 'auction_items.auction_id = auctions.id', 'left');
        $this->db->join('item_category', 'item_category.id = auctions.category_id', 'inner');
        $this->db->join('item', 'item.id = auction_items.item_id', 'left');
        $this->db->join('files', 'files.id = item_category.category_icon', 'left');
        $this->db->order_by('auctions.start_time', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else
            return false;
    }

    public function nearlyCloseAuctions2()
    {
        //BETWEEN CURRENT_TIMESTAMP() and NOW() + INTERVAL 1 DAY)
        //BETWEEN '2021-08-29' and '2021-08-31 14:10:00')
        $select = "SELECT auction_items.id as auctionItemId, item.id as item_id, item.name, item.item_images, item.detail, auction_items.bid_start_time, auction_items.bid_end_time,auction_items.bid_start_price,auction_items.category_id as categoryId, auction_items.auction_id, auction_items.lot_no, auction_items.order_lot_no,item.name as item_name,item.feature, COUNT(online_auction_item_visits.id) AS visits
      FROM auction_items 
      INNER JOIN item ON item.id = auction_items.item_id
      LEFT JOIN online_auction_item_visits ON online_auction_item_visits.item_id = auction_items.item_id AND online_auction_item_visits.auction_id = auction_items.auction_id
     WHERE (auction_items.bid_end_time BETWEEN CURRENT_TIMESTAMP() and NOW() + INTERVAL 1 DAY)   AND auction_items.sold_status = 'not'
      GROUP BY item_id,auction_items.category_id
      ORDER BY auction_items.bid_end_time ASC

      LIMIT 10";
        // (CURRENT_TIMESTAMP() BETWEEN auction_items.bid_start_time AND auction_items.bid_end_time)
        // ORDER BY auction_items.bid_end_time ASC


        // print_r( $select);
        $query = $this->db->query($select);
        // $query=$this->db->get();
        //AND auction_items.category_id = ".$cat_id."
        //AND auction_items.auction_id = ".$aucId."
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else
            return false;

    }

    public function featured_item($cat_id, $auction_id)
    {
        $this->db->select('auction_items.*,item.id as itemId,item.name, item.detail as item_datail, item.feature, item.item_images, item.year as itemYear,item.specification as itemSpecification, item.mileage as itemMileage, item.mileage_type as mileageType, auctions.expiry_time, auctions.access_type,auctions.title as auction_title, auctions.detail as auctionDetail,item.name as item_name ,item_category.id as categoryId,auctions.start_time,auctions.expiry_time');
        //$this->db->select('auction_items.*,item.id as itemId,item.name, item.detail as item_datail, item.feature, item.item_images, item.year as itemYear,item.specification as itemSpecification, item.mileage as itemMileage, item.mileage_type as mileageType, auctions.expiry_time, auctions.access_type , COUNT(online_auction_item_visits.id) AS visits');
        $this->db->from('auction_items');
        $this->db->join('item', 'item.id = auction_items.item_id');
        $this->db->join('auctions', 'auctions.id = auction_items.auction_id');
        $this->db->join('item_category', 'item_category.id = auction_items.category_id');
        $this->db->join('online_auction_item_visits', 'online_auction_item_visits.item_id = auction_items.item_id AND online_auction_item_visits.auction_id = auction_items.auction_id', 'LEFT');
        $this->db->where('item.feature', 'yes');
        $this->db->where('item.sold', 'no');
        $this->db->where('auction_items.sold_status', 'not');
        $this->db->where('auctions.status', 'active');
        $this->db->where('auctions.id', $auction_id);
        $this->db->where('item_category.id', $cat_id);

        $this->db->where('auctions.start_time <=', date('Y-m-d H:i:s'));
        $this->db->where('auctions.expiry_time >=', date('Y-m-d H:i:s'));
        $this->db->where('auction_items.bid_start_time <=', date('Y-m-d H:i:s'));
        $this->db->where('auction_items.bid_end_time >=', date('Y-m-d H:i:s'));
        $this->db->group_by('item.id');
        $this->db->order_by('rand()');
        $featured_items = $this->db->get()->result_array();
        return $featured_items;
    }

    public function get_online_auctions()
    {
        $where = [
            'auctions.status' => 'active',
            'auctions.access_type' => 'live',
            'auctions.expiry_time >=' => date('Y-m-d H:i:s')
        ];

        $auctions = $this->db->select('auctions.*,item_category.title as cat_title,item_category.category_icon as cat_icon')
            // $auctions = $this->db->select('auctions.*,item_category.title as cat_title,item_category.category_icon as cat_icon,auction_items.auction_id,auction_items.sold_status')
            ->from('auctions')
            ->join('item_category', 'auctions.category_id = item_category.id', 'LEFT')
            // ->join('auction_items','auction_items.auction_id = auctions.id')
            ->where($where)
            ->get()->result_array();

        return $auctions;
    }


    public
    function AllHomeListing2()
    {
        $version = $this->config->item('appVersion');
        $userId = "";
        $data = array();
        $data2 = array();

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $current_date = date('Y-m-d H:i:s');


        $inputs = json_decode(file_get_contents("php://input"));
        if (isset($inputs->category_id) && !empty($inputs->category_id)) {
            $category_id = $inputs->category_id;
            $data['category'] = $this->db->get_where('item_category', ['id' => $category_id])->row_array();
        } else {
            $category_data = $this->db->select('*')->order_by('sort_order', 'ASC')->get_where('item_category', ['status' => 'active', 'show_web' => 'yes'])->row_array();
            $category_id = $category_data['id'];
            $data['active_categories'] = $category_data;
        }


        $data2['all_categories'] = $this->getapimodel->get_catagories_home();
        foreach ($data2['all_categories'] as $key => $record) {

            $cat_labels = json_decode($record['cat_title']);


            $data2['all_categories'][$key]['cat_label_eng'] = $cat_labels->english;
            $data2['all_categories'][$key]['cat_label_ar'] = $cat_labels->arabic;

            if (isset($data2['all_categories'][$key]['path'])) {
                $file_name = $data2['all_categories'][$key]['path'];
                $base_url_img = base_url() . 'uploads/category_icon/' . $data2['all_categories'][$key]['image_name'];
                $image = $base_url_img;
                $data2['all_categories'][$key]['image'] = $image;
            }
        }

        $online_auctions = $this->get_online_auctions();
        foreach ($online_auctions as $key => $value) {

            $data2['live_auction'][$key] = array(
                'item_name' => $online_auctions[$key]['title'],
                'category_title' => $online_auctions[$key]['cat_title'],
                'auction_id' => $online_auctions[$key]['id'],
                'auction_access_type' => $online_auctions[$key]['access_type'],
                'image' => base_url('uploads/live_hall_banner.png'),
                'category_id' => $online_auctions[$key]['category_id'],
                'start_time' => $online_auctions[$key]['start_time'],
                'expiry_time' => $online_auctions[$key]['expiry_time'],
                'created_time' => $online_auctions[$key]['created_on'],
                'start_status' => $online_auctions[$key]['start_status']
            );
        }


        $date = date('Y-m-d');
        $datee['time'] = date('Y-m-d H:i:s', time());
        $data['upcoming_auctions'] = $this->upcoming_auctions_home($date, 'time');

        if (!empty($data['upcoming_auctions'])) {

            foreach ($data['upcoming_auctions'] as $key => $val) {

                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {
                        $image = $this->db->get_where('files', ['id' => $item_images[0]])->row_array();
                        $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
                    $data['upcoming_auctions'][$key]['item_images'] = $image_get;

                }

//                $access_type = $this->db->get_where('auctions', ['id' => $data['upcoming_auctions'][$key]['auction_id']])->row_array();
//                $data['upcoming_auctions'][$key]['auction_access_type'] = $access_type['access_type'];


                $data['upcoming_auctions'][$key]['bid_count'] = $this->db->where('item_id', $data['upcoming_auctions'][$key]['item_id'])->where('auction_id', $data['upcoming_auctions'][$key]['auction_id'])->from('bid')->count_all_results();


                $heighest_bid_data = $this->oam->item_heighest_bid_data($data['upcoming_auctions'][$key]['item_id'], $data['upcoming_auctions'][$key]['auction_id']);
                $current_bid = "";
                $current_bid_user = "";
                if (!empty($heighest_bid_data)) {
                    $data['upcoming_auctions'][$key]['current_bid'] = $heighest_bid_data['current_bid'];
                    $data['upcoming_auctions'][$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                    $current_bid = $heighest_bid_data['current_bid'];
                    $current_bid_user = $heighest_bid_data['user_id'];
                }


                $data2['upcoming_auctions'][$key] = array(
                    'title' => $data['upcoming_auctions'][$key]['auction_title'],
                    'item_name' => $data['upcoming_auctions'][$key]['item_name'],
                    'item_id' => $data['upcoming_auctions'][$key]['item_id'],
                    'auction_id' => $data['upcoming_auctions'][$key]['auction_id'],
                    'bid_amount' => $data['upcoming_auctions'][$key]['bid_start_price'],
                    'current_bid' => $current_bid,
                    'current_bid_user' => $current_bid_user,
                    'lot' => $data['upcoming_auctions'][$key]['order_lot_no'],
                    'auction_access_type' => $data['upcoming_auctions'][$key]['access_type'],
                    'bid_count' => $data['upcoming_auctions'][$key]['bid_count'],
                    'image' => $data['upcoming_auctions'][$key]['item_images'],
                    'category_id' => $data['upcoming_auctions'][$key]['categoryId'],
                    'item_feature' => $data['upcoming_auctions'][$key]['feature'],
                    'start_time' => $data['upcoming_auctions'][$key]['start_time'],
                    'expiry_time' => $data['upcoming_auctions'][$key]['expiry_time'],
                    'created_time' => ""
                );


            }
        }


        $data['online_auctions'] = $this->singleOnlineAuctionsWithItem();


        if (count($data['online_auctions']) > 0) {
            foreach ($data['online_auctions'] as $key => $val) {

                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {

                        $image = $this->db->get_where('files', ['id' => $item_images[0]])->row_array();
                        $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }

                    $data['online_auctions'][$key]['item_images'] = $image_get;
                }


//                $access_type = $this->db->get_where('auctions', ['id' => $data['online_auctions'][$key]['auction_id']])->row_array();
//                $data['online_auctions'][$key]['auction_access_type'] = $access_type['access_type'];
//

                $data['online_auctions'][$key]['bid_count'] = $this->db->where('item_id', $data['online_auctions'][$key]['item_id'])->where('auction_id', $data['online_auctions'][$key]['auction_id'])->from('bid')->count_all_results();


                $heighest_bid_data = $this->oam->item_heighest_bid_data($data['online_auctions'][$key]['item_id'], $data['online_auctions'][$key]['auction_id']);


                $current_bid = "";
                $current_bid_user = "";

                if (!empty($heighest_bid_data)) {
                    $data['online_auctions'][$key]['current_bid'] = $heighest_bid_data['current_bid'];
                    $data['online_auctions'][$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                    $current_bid = $heighest_bid_data['current_bid'];
                    $current_bid_user = $heighest_bid_data['user_id'];
                }


                $data2['online_auctions'][$key] = array(
                    'title' => $data['online_auctions'][$key]['auction_title'],
                    'item_name' => $data['online_auctions'][$key]['item_name'],
                    'item_id' => $data['online_auctions'][$key]['item_id'],
                    'auction_id' => $data['online_auctions'][$key]['auction_id'],
                    'bid_amount' => $data['online_auctions'][$key]['bid_start_price'],
                    'current_bid' => $current_bid,
                    'current_bid_user' => $current_bid_user,
                    'lot' => $data['online_auctions'][$key]['order_lot_no'],
                    'auction_access_type' => $data['online_auctions'][$key]['access_type'],
                    'bid_count' => $data['online_auctions'][$key]['bid_count'],
                    'image' => $data['online_auctions'][$key]['item_images'],
                    'category_id' => $data['online_auctions'][$key]['categoryId'],
                    'item_feature' => $data['online_auctions'][$key]['feature'],
                    'start_time' => $data['online_auctions'][$key]['bid_start_time'],
                    'expiry_time' => $data['online_auctions'][$key]['bid_end_time'],
                    'created_time' => ""
                );


            }
        }

        $data['nearlyCloseAuctions'] = $this->nearlyCloseAuctions2();

        if (!empty($data['nearlyCloseAuctions'])) {
            foreach ($data['nearlyCloseAuctions'] as $key => $val) {

                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {

                        $image = $this->db->get_where('files', ['id' => $item_images[0]])->row_array();
                        $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
//                    $image_get = trim($image_get, ',');
                    $data['nearlyCloseAuctions'][$key]['item_images'] = $image_get;
                }

                $access_type = $this->db->get_where('auctions', ['id' => $data['nearlyCloseAuctions'][$key]['auction_id']])->row_array();
                $data['nearlyCloseAuctions'][$key]['auction_access_type'] = $access_type['access_type'];
                $data['nearlyCloseAuctions'][$key]['bid_count'] = $this->db->where('item_id', $data['nearlyCloseAuctions'][$key]['item_id'])->where('auction_id', $data['nearlyCloseAuctions'][$key]['auction_id'])->from('bid')->count_all_results();


                $heighest_bid_data = $this->oam->item_heighest_bid_data($data['nearlyCloseAuctions'][$key]['item_id'], $data['nearlyCloseAuctions'][$key]['auction_id']);


                $current_bid = "";
                $current_bid_user = "";

                if (!empty($heighest_bid_data)) {
                    $data['nearlyCloseAuctions'][$key]['current_bid'] = $heighest_bid_data['current_bid'];
                    $data['nearlyCloseAuctions'][$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                    $current_bid = $heighest_bid_data['current_bid'];
                    $current_bid_user = $heighest_bid_data['user_id'];
                }


                $data2['nearlyCloseAuctions'][$key] = array(
                    'title' => "",
                    'item_name' => $data['nearlyCloseAuctions'][$key]['item_name'],
                    'item_id' => $data['nearlyCloseAuctions'][$key]['item_id'],
                    'auction_id' => $data['nearlyCloseAuctions'][$key]['auction_id'],
                    'bid_amount' => $data['nearlyCloseAuctions'][$key]['bid_start_price'],
                    'current_bid' => $current_bid,
                    'current_bid_user' => $current_bid_user,
                    'lot' => $data['nearlyCloseAuctions'][$key]['order_lot_no'],
                    'auction_access_type' => $data['nearlyCloseAuctions'][$key]['auction_access_type'],
                    'bid_count' => $data['nearlyCloseAuctions'][$key]['bid_count'],
                    'image' => $data['nearlyCloseAuctions'][$key]['item_images'],
                    'category_id' => $data['nearlyCloseAuctions'][$key]['categoryId'],
                    'item_feature' => $data['nearlyCloseAuctions'][$key]['feature'],
                    'start_time' => $data['nearlyCloseAuctions'][$key]['bid_start_time'],
                    'expiry_time' => $data['nearlyCloseAuctions'][$key]['bid_end_time'],
                    'created_time' => ""
                );


            }
        }


        @$auction_id = '';
        $data['active_auction_categories'] = $this->home_model->get_items_categories();

        foreach ($data['active_auction_categories'] as $key => $value) {

            $item_images = $value['category_icon'];
            $image = $this->db->get_where('files', ['id' => $item_images])->row_array();
            $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
            $data['active_auction_categories'][$key]['category_icon'] = $image_get;


            $count = 0;
            $auctions_online = $this->home_model->get_online_auctions($value['id']);
            if (!empty($auctions_online)) {
                $data['active_auction_categories'][$key]['auction_id'] = $auctions_online['id'];
                $data['active_auction_categories'][$key]['expiry_time'] = $auctions_online['expiry_time'];
                $count = $this->db->select('*')->from('auction_items')->where('auction_id', $auctions_online['id'])->where('sold_status', 'not')->where('auction_items.bid_start_time <', date('Y-m-d H:i'))->get()->result_array();
                $count = count($count);
            }
            if ($userId) {
                $u_id = $userId;
                $close_auctions = $this->db->where("FIND_IN_SET('" . $u_id . "', close_auction_users)")->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active', 'access_type' => 'closed', 'category_id' => $value['id']])->result_array();
                if ($close_auctions) {
                    foreach ($close_auctions as $key1 => $close_auction) {
                        $item_ids = array();
                        $sub_total = count($this->lam->get_live_auction_items($close_auction['id'], 0, 0, $item_ids));
                        $count = $count + $sub_total;
                    }
                }
            }
            $live_auctions = $this->db->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active', 'access_type' => 'live', 'category_id' => $value['id']])->result_array();
            if ($live_auctions) {
                foreach ($live_auctions as $key2 => $live_auction) {
                    $live_item_ids = array();
                    $total = count($this->lam->get_live_auction_items($live_auction['id'], 0, 0, $live_item_ids));
                    $count = $count + $total;
                }
            }
            $data['active_auction_categories'][$key]['item_count'] = $count;

            $catID = $value['id'];
            @$auction_id = $data['active_auction_categories'][$key]['auction_id'];


            $featured_items = $this->featured_item($catID, $auction_id);


            foreach ($featured_items as $_list) {


                $data['featured_items'][$catID][] = array(
                    'id' => $_list['id'],
                    'auction_title' => $_list['auction_title'],
                    'lot_no' => $_list['lot_no'],
                    'order_lot_no' => $_list['order_lot_no'],
                    'bid_start_time' => $_list['bid_start_time'],
                    'bid_end_time' => $_list['bid_end_time'],
                    'bid_start_price' => $_list['bid_start_price'],
                    'feature' => $_list['feature'],
                    'access_type' => $_list['access_type'],
                    'item_name' => $_list['item_name'],
                    'item_id' => $_list['item_id'],
                    'auction_id' => $_list['auction_id'],
                    'item_images' => $_list['item_images'],
                    'start_time' => $_list['start_time'],
                    'expiry_time' => $_list['expiry_time'],
                    'bid_amount' => '',
                    'itemSpecification' => $_list['itemSpecification'],
                    'itemMileage' => $_list['itemMileage'],
                    'mileageType' => $_list['mileageType'],
                    'categoryId' => $_list['categoryId'],
                    'visits' => '',
                    'item_datail' => $_list['item_datail'],
                    'itemYear' => $_list['itemYear']
                );
            }


        }

        $j = 1;


        foreach ($data['active_auction_categories'] as $categories) {
            $current_category = $categories['id'];

            foreach (@$data['featured_items'][$current_category] as $key => $featured_item) {


                $bid_data = '';
                $image = '';
                $query = $this->db->query('Select bid.bid_amount, bid.bid_status ,bid.user_id from bid inner join ( Select max(bid_time) as LatestDate, item_id  from bid  Group by bid.item_id ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id  WHERE bid.item_id = ' . $featured_item['item_id'] . ' AND bid.auction_id = ' . $featured_item['auction_id'] . ';');

                if ($query->num_rows() > 0) {
                    $bid_data = $query->row_array();

                }


                if (!empty($featured_item['item_images'])) {


                    if (!is_array($featured_item['item_images'])) {
                        $item_images = explode(',', $featured_item['item_images']);

                        $i = 0;
                        $image_get = array();
                        while ($i < count($item_images)) {
                            if ($i == 0) {

                                $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                                if (!empty(trim($image['name']))) {
                                    $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                                    $data['featured_items'][$current_category][$key]['item_images'] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                                }
                            }
                            $i++;
                        }


                    }
                }


//                $access_type = $this->db->get_where('auctions', ['id' => $data['featured_items'][$key]['auction_id']])->row_array();
//                $data['featured_items'][$key]['auction_access_type'] = $access_type['access_type'];


                $data['featured_items'][$current_category][$key]['bid_count'] = $this->db->where('item_id', $featured_item['item_id'])->where('auction_id', $featured_item['auction_id'])->from('bid')->count_all_results();


                $heighest_bid_data = $this->oam->item_heighest_bid_data($featured_item['item_id'], $featured_item['auction_id']);
                $current_bid = "";
                $current_bid_user = "";
                if (!empty($heighest_bid_data)) {
                    $data['featured_items'][$current_category][$key]['current_bid'] = $heighest_bid_data['current_bid'];
                    $data['featured_items'][$current_category][$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                    $current_bid = $heighest_bid_data['current_bid'];
                    $current_bid_user = $heighest_bid_data['user_id'];
                }


                $data2['featured_items'][] = array(
                    'title' => $data['featured_items'][$current_category][$key]['auction_title'],
                    'item_name' => $data['featured_items'][$current_category][$key]['item_name'],
                    'item_id' => $data['featured_items'][$current_category][$key]['item_id'],
                    'auction_id' => $data['featured_items'][$current_category][$key]['auction_id'],
                    'bid_amount' => $data['featured_items'][$current_category][$key]['bid_start_price'],
                    'current_bid' => $current_bid,
                    'current_bid_user' => $current_bid_user,
                    'lot' => $data['featured_items'][$current_category][$key]['order_lot_no'],
                    'auction_access_type' => $data['featured_items'][$current_category][$key]['access_type'],
                    'bid_count' => $data['featured_items'][$current_category][$key]['bid_count'],
                    'image' => $data['featured_items'][$current_category][$key]['item_images'],
                    'category_id' => $data['featured_items'][$current_category][$key]['categoryId'],
                    'item_feature' => $data['featured_items'][$current_category][$key]['feature'],
                    'start_time' => $data['featured_items'][$current_category][$key]['bid_start_time'],
                    'expiry_time' => $data['featured_items'][$current_category][$key]['bid_end_time'],
                    'created_time' => ""
                );

            }
            $j++;
        }

        $data2['active_auction_categories'] = $data['active_auction_categories'];


        $codee = http_response_code(200);
        echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'ALL HOME PAGE DATA', 'english' => $english_l['home_page_data'], 'arabic' => $arabic_l['home_page_data'], 'appVersion' => $version, 'result' => $data2));

    }


    public function all_categories()
    {

        $version = $this->config->item('appVersion');
        $category_data = array();

        $data = $this->getapimodel->get_catagories_home();

        foreach ($data as $key => $record) {

            if (isset($data[$key]['path'])) {
                $file_name = $data[$key]['path'];
                $base_url_img = base_url() . 'uploads/category_icon/' . $data[$key]['image_name'];
                $image = $base_url_img;
            }

            $data[$key]['image'] = $image;

        }


        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Categories ', 'appVersion' => $version, 'result' => $data));


    }


    public
    function getOnlineLiveAuctions($categoryId)
    {

        $version = $this->config->item('appVersion');

        $userId = "";

        $postData = json_decode(file_get_contents("php://input"));

        if (!empty($postData->categoryId)) {
            $categoryId = $postData->categoryId;
        }


        if (isset($categoryId) && !empty($categoryId)) {

            $data = array();

            $language = "english";


            $data['selectedCategory'] = $this->db->get_where('item_category', ['id' => $categoryId])->row_array();

            if (empty($data['selectedCategory']['id'])) {

                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'NO CATEGORY ID FOUND  ', 'appVersion' => $version));

            } else {


                $data['categoryId'] = $data['selectedCategory']['id'];

                $selectedCategoryName = json_decode($data['selectedCategory']['title']);
                $data['selectedCategoryName'] = $selectedCategoryName->$language;


                $data['itemCategoryFields'] = $this->db->get_where('item_category_fields', ['category_id' => $categoryId])->result_array();
                $data['itemMakes'] = $this->db->order_by('title', 'ASC')->get_where('item_makes', ['status' => 'active'])->result_array();

                $data['hasClosedAuctions'] = 0;
                if ($userId) {
                    $closedAuctions = $this->db->where("FIND_IN_SET('{$userId}', close_auction_users)")
                        ->where('expiry_time >= CURDATE()')
                        ->where([
                            'status' => 'active',
                            'access_type' => 'closed',
                            'category_id' => $categoryId
                        ])->get('auctions');
                    $data['hasClosedAuctions'] = $closedAuctions->num_rows();
                }

                $codee = http_response_code(200);
                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Succfully Get Online/Live Auctions', 'appVersion' => $version, 'result' => $data));

            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'NO CATEGORY ID FOUND  ', 'appVersion' => $version));
        }

    }


    //search

    public function search($slug = '')
    {

        $version = $this->config->item('appVersion');
        $language = "english";
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        if (isset($_GET['select_language']) && !empty($_GET['select_language'])) {
            $language = $_GET['select_language'];
        }

        $id = $slug;

        if (isset($_GET['categoryId'])) {
            $id = $_GET['categoryId'];
        }

        if (is_numeric($id) && is_numeric($id) > 0) {
            $cat_data = $this->db->get_where('item_category', ['id' => $id])->row_array();
            $categoryId = $cat_data['slug'];
            $slug = $categoryId;

        } else {

            if (!empty($slug)) {
                $categoryId = $slug;
            } elseif (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
                $categoryId = $_GET['categoryId'];
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Search Id not Found', 'english' => $english_l['search_id_not_found'], 'arabic' => $arabic_l['search_id_not_found'], 'appVersion' => $version));
                exit();

            }

        }

        $searchTerm = "";
        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $searchTerm = htmlspecialchars($_GET['query']);
            $data['searchTerm'] = $searchTerm;
        }


        $data['slug'] = $slug;

        $data['selectedCategory'] = $this->db->get_where('item_category', ['slug' => $slug])->row_array();
        if (empty($data['selectedCategory'])) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Search Id not Found', 'english' => $english_l['search_id_not_found'], 'arabic' => $arabic_l['search_id_not_found'], 'appVersion' => $version));
            exit();

        } else {


            $data['categoryId'] = $data['selectedCategory']['id'];

            $selectedCategoryName = json_decode($data['selectedCategory']['title']);


            $data['selectedCategoryName'] = $selectedCategoryName->$language;


            //print_r($data);die();

            $data['itemCategoryFields'] = $this->db->get_where('item_category_fields', ['category_id' => $categoryId])->result_array();
            $data['itemMakes'] = $this->db->order_by('title', 'ASC')->get_where('item_makes', ['status' => 'active'])->result_array();

            $data['hasClosedAuctions'] = 0;
            $user = validateToken2();
            if ($user) {

                $closedAuctions = $this->db->where("FIND_IN_SET('{$user}', close_auction_users)")
                    ->where('expiry_time >= CURDATE()')
                    ->where([
                        'status' => 'active',
                        'access_type' => 'closed',
                        'category_id' => $categoryId
                    ])->get('auctions');
                $data['hasClosedAuctions'] = $closedAuctions->num_rows();
            }

            if ($this->session->userdata('logged_in')) {
                $balance = $this->user_balance($this->session->userdata('logged_in')->id);
                $data['balance'] = $balance['amount'] ?? 0;
            }else{
                $data['balance'] =  0;
            }


            $a1 = $this->getAuctionItems3('online', $id, $data, $language);

            $a2 = $this->getAuctionItems3('live', $id, $data, $language);

            $records = array_merge($a1, $a2);

            $codee = http_response_code(200);
            echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Successfully Got Auction Item Data By Category', 'english' => $english_l['successful_get_auction_id_by_cat'], 'arabic' => $arabic_l['successful_get_auction_id_by_cat'], 'appVersion' => $version, 'result' => $records));


        }

    }

//    public
//    function getAuctionItems()
//    {
//        $version = $this->config->item('appVersion');
//
//        $userId = "";
//
//
//        $postData = json_decode(file_get_contents("php://input"));
//
//        $data = json_decode(json_encode($postData), true);
//
//        if ($data) {
//
//            $minMil = $data['itemMilageMin'] ?? 0;
//            $maxMil = $data['itemMilageMax'] ?? 0;
//            $milageType = $data['milageType'] ?? '';
//
//            $filterSQL = [];
//            $filterQuery = '';
//
//            $data = array_filter($data);
//
//
//            $this->db->where([
//                'access_type' => $data['auctionType'],
//                'category_id' => $data['categoryId'],
//                'status' => 'active'
//            ]);
//
//            if (in_array($data['auctionType'], ['online', 'closed'])) {
//                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `start_time` AND `expiry_time`');
//            }
//
//            if (in_array($data['auctionType'], ['live'])) {
//                $this->db->where("(`expiry_time` >= CURRENT_TIMESTAMP() OR `start_status` = 'start')");
//                $this->db->order_by('`start_time`', 'ASC');
//            } else {
//                $this->db->order_by('id', 'ASC');
//            }
//
//            if (in_array($data['auctionType'], ['online', 'live'])) {
//                $this->db->limit(1);
//            }
//
//            $availableAuctions = $this->db->get('auctions')->result_array();
//
//
//            $auctionIds = [];
//            foreach ($availableAuctions as $key => $availableAuction) {
//                array_push($auctionIds, $availableAuction['id']);
//            }
//            if (empty($auctionIds)) {
//                array_push($auctionIds, 0);
//            }
//
//            if (empty($auctionIds)) {
//                //$output = json_encode(['status' => 'failed']);
//                //return print_r($output);
//            }
//
//            //offset and limit for records
//            $limit = 15;
//            $offset = (isset($data['offset']) && !empty($data['offset'])) ? (int)$data['offset'] : 0;
//            //$next_offset = (int)$offset + $limit;
//
//
//            $itemIds = [];
//            if (isset($data['fields']) && !empty($data['fields'])) {
//                $fieldIds = $fieldValues = [];
//                $fields = array_filter($data['fields']);
//                unset($data['fields']);
//
//                if ($fields) {
//                    foreach ($fields as $key => $value) {
//                        array_push($fieldIds, $key);
//                        if (is_array($value)) {
//                            foreach ($value as $k => $v) {
//                                array_push($fieldValues, $v);
//                            }
//                        } else {
//                            array_push($fieldValues, $value);
//                        }
//                    }
//
//                    $fieldItemIds = $this->db->distinct('item_id')->select('item_id')
//                        ->from('item_fields_data')
//                        ->where('category_id', $data['categoryId'])
//                        ->where_in('fields_id', $fieldIds)
//                        ->where_in('value', $fieldValues)
//                        ->get();
//
//                    if ($fieldItemIds->num_rows() > 0) {
//                        $fieldItemIds = $fieldItemIds->result_array();
//                        foreach ($fieldItemIds as $key => $value) {
//                            array_push($itemIds, $value['item_id']);
//                        }
//                    }
//                }
//            }
//
//
//            if (isset($data['itemYearFrom']) && !empty($data['itemYearFrom'])
//                && isset($data['itemYearTo']) && !empty($data['itemYearTo'])) {
//                $from = $data['itemYearFrom'];
//                $to = $data['itemYearTo'];
//                $filterSQL[] = " ( `i`.`year` BETWEEN '{$from}' AND '{$to}') ";
//            }
//
//            if (($minMil >= 0) && ($maxMil > 0)) {
//                $filterSQL[] = " ( `i`.`mileage` BETWEEN '{$minMil}' AND '{$maxMil}') ";
//            }
//
//
//            if (!empty($milageType)) {
//                $milageType = $data['milageType'];
//                $filterSQL[] = " ( `i`.`mileage_type` = '{$milageType}') ";
//            }
//
//            if (isset($data['itemMake']) && !empty($data['itemMake'])) {
//                $itemMake = $data['itemMake'];
//                $filterSQL[] = " ( `i`.`make` = '{$itemMake}') ";
//            }
//
//            if (isset($data['itemModel']) && !empty($data['itemModel'])) {
//                $itemModel = $data['itemModel'];
//                $filterSQL[] = " ( `i`.`model` = '{$itemModel}') ";
//            }
//
//            if (isset($data['specification']) && !empty($data['specification'])) {
//                $specification = $data['specification'];
//                $filterSQL[] = " ( `i`.`specification` = '{$specification}') ";
//            }
//
//            if (isset($data['itemLot']) && !empty($data['itemLot'])) {
//                $itemLot = $data['itemLot'];
//                $filterSQL[] = " ( `ai`.`order_lot_no` = '{$itemLot}') ";
//            }
//
//            if (!empty($filterSQL)) {
//                $filterQuery .= ' ' . implode(' AND ', $filterSQL);
//            }
//
//
//            $select = $this->sm->getItemSelect($data['auctionType']);
//
//            $this->db->reset_query();
//            $this->db->flush_cache();
//            $this->db->start_cache();
//
//            $this->db->select($select);
//            $this->db->from('auction_items ai');
//            $this->db->join('item i', 'i.id = ai.item_id');
//            $this->db->join('item_makes imake', 'i.make = imake.id', 'LEFT');
//            $this->db->join('item_models imodel', 'i.model = imodel.id', 'LEFT');
//
//            if ($data['auctionType'] != 'live') {
//                $this->db->join('bid b', 'ai.auction_id = b.auction_id AND ai.item_id = b.item_id', 'LEFT');
//            }
//
//            $this->db->where('ai.sold_status', 'not');
//            $this->db->where('i.sold', 'no');
//            $this->db->where('ai.category_id', $data['categoryId']);
//
//            if ($data['auctionType'] != 'live') {
//                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `ai`.`bid_start_time` AND `ai`.`bid_end_time`');
//            }
//
//            if (!empty($auctionIds)) {
//                $this->db->where_in('ai.auction_id', $auctionIds);
//            }
//
//            if (!empty($filterQuery)) {
//                $this->db->where($filterQuery);
//            }
//
//            if (!empty($itemIds)) {
//                // $this->db->or_where_in('ai.item_id', $itemIds);
//                $this->db->where_in('ai.item_id', $itemIds);
//            }
//
//            if (isset($data['searchTerm']) && !empty($data['searchTerm'])) {
//                $searchTerm = strtolower($data['searchTerm']);
//                $language = $this->language;
//                $searchTermSQL = ' LOWER(JSON_EXTRACT(item_name, "$.' . $language . '")) LIKE "%' . $searchTerm . '%"
//                        OR LOWER(JSON_EXTRACT(item_detail, "$.' . $language . '")) LIKE "%' . $searchTerm . '%"
//                        OR LOWER(JSON_EXTRACT(item_make_title, "$.' . $language . '")) LIKE "%' . $searchTerm . '%"
//                        OR LOWER(JSON_EXTRACT(item_model_title, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" ';
//
//                $this->db->having($searchTermSQL);
//
//            }
//
//
//            if (isset($data['itemPriceMin']) && !empty($data['itemPriceMin'])
//                && isset($data['itemPriceMax']) && !empty($data['itemPriceMax'])) {
//                $min = $data['itemPriceMin'];
//                $max = $data['itemPriceMax'];
//                $this->db->having(" (final_price BETWEEN '{$min}' AND '{$max}') ");
//            }
//
//            if ($data['auctionType'] != 'live') {
//                $this->db->group_by('auction_item_id');
//            }
//
//            $this->db->stop_cache();
//
//
//            $totalRecords = $this->db->get()->num_rows();
//
//
//            $this->db->start_cache();
//
//
//            //order by
//            $orderBy = isset($data['sortBy']) ? $data['sortBy'] : "";
//            if (!empty($orderBy)) {
//                switch ($orderBy) {
//                    case 'hp':
//                        $this->db->order_by('final_price', "DESC");
//                        break;
//                    case 'lp':
//                        $this->db->order_by('final_price', "ASC");
//                        break;
//                    case 'latest':
//                        $this->db->order_by('ai.id', "DESC");
//                        break;
//                    case 'featured':
//                        $this->db->order_by('item_feature', "ASC");
//                        break;
//
//                    default:
//                        $this->db->order_by('item_feature', "ASC");
//                        break;
//                }
//            } else {
//                $this->db->order_by('item_feature', "ASC");
//            }
//
//            $this->db->stop_cache();
//
//
//            $query = $this->db->get();
//            $totalDisplayRecords = $query->num_rows();
//
//
//            $this->db->flush_cache();
//            $this->db->reset_query();
//
//
//            if ($totalRecords > 0 || !empty($availableAuctions)) {
//                $items = $query->result_array();
//
//                foreach ($items as $key => $val) {
//                    if (!empty($val['item_images'])) {
//                        $item_images = explode(',', $val['item_images']);
//                        $i = 0;
//                        $image_get = array();
//                        while ($i < count($item_images)) {
//
//                            $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
//                            $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
//                            $i++;
//                        }
//                        $items[$key]['item_images'] = $image_get;
//                        if ($data['auctionType'] == "online") {
//                            $items[$key]['online_detail_url'] = base_url() . 'api/v1/online-auction/details/' . $val['auction_id'] . '/' . $val['item_id'];
//
//                        } else {
//                            $items[$key]['live_detail_url'] = base_url() . 'api/v1/live-online-detail/details/' . $val['auction_id'] . '/' . $val['item_id'];
//                        }
//                    }
//                    $items[$key]['bid_count'] = $this->db->where('item_id', $val['item_id'])->where('auction_id', $val['auction_id'])->from('bid')->count_all_results();
//                }
//
//                foreach ($availableAuctions as $key => $val) {
//                    if (!empty($val['id'])) {
//                        $availableAuctions[$key]['auction_url'] = base_url('api/v1/live-online/') . $val['id'];
//
//                    }
//                }
//
//                $records = array(
//                    'live_auction_check' => $availableAuctions,
//                    'items' => $items,
//                    'orderBy' => $orderBy,
//                    'totalRecords' => $totalRecords,
//                );
//
//                $codee = http_response_code(200);
//                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Successfully Got Auction Item Data By Category', 'appVersion' => $version, 'result' => $records));
//
//
//            } else {
//                $codee = http_response_code(200);
//                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Failed Get Online/Live Auctions Listing', 'appVersion' => $version, 'result' => $records));
//
//            }
//
//        } else {
//            $codee = http_response_code(200);
//            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Category ID Or Auction Type Not Found ', 'appVersion' => $version));
//
//        }
//
//
//    }


    public function getAuctionItems()
    {
        $version = $this->config->item('appVersion');

        $userId = "";


        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);
        $language = "english";
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        if (isset($data['select_language']) && !empty($data['select_language'])) {
            $language = $data['select_language'];
        }


        if ($data) {


            $minMil = $data['itemMilageMin'] ?? 0;
            $maxMil = $data['itemMilageMax'] ?? 0;
            $milageType = $data['milageType'] ?? '';

            $filterSQL = [];
            $filterQuery = '';

            // remove all empty value's keys
            $data = array_filter($data);


            //auction selection for displaying record
            $this->db->where([
                'access_type' => $data['auctionType'],
                'category_id' => $data['categoryId'],
                'status' => 'active'
            ]);

            if (in_array($data['auctionType'], ['online', 'closed'])) {
                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `start_time` AND `expiry_time`');
            }

            if (in_array($data['auctionType'], ['live'])) {
                $this->db->where("(`expiry_time` >= CURRENT_TIMESTAMP() OR `start_status` = 'start')");
                // $this->db->or_where('`start_status`', 'start');
                $this->db->order_by('`start_time`', 'ASC');
            } else {
                $this->db->order_by('id', 'ASC');
            }

            if (in_array($data['auctionType'], ['online', 'live'])) {
                $this->db->limit(1);
            }

            $availableAuctions = $this->db->get('auctions')->result_array();





            $auctionIds = [];
            foreach ($availableAuctions as $key => $availableAuction) {
                array_push($auctionIds, $availableAuction['id']);
            }
            if (empty($auctionIds)) {
                array_push($auctionIds, 0);
            }

            if (empty($auctionIds)) {
                //$output = json_encode(['status' => 'failed']);
                //return print_r($output);
            }

            // echo $this->db->last_query();return;

            //offset and limit for records
            $limit = 15;
            $offset = (isset($data['offset']) && !empty($data['offset'])) ? (int)$data['offset'] : 0;
            //$next_offset = (int)$offset + $limit;


            //get item ids from category fields data
            $itemIds = [];
            if (isset($data['fields']) && !empty($data['fields'])) {
                $fieldIds = $fieldValues = [];
                $fields = array_filter($data['fields']);
                unset($data['fields']);

                if ($fields) {
                    foreach ($fields as $key => $value) {
                        array_push($fieldIds, $key);
                        if (is_array($value)) {
                            foreach ($value as $k => $v) {
                                array_push($fieldValues, $v);
                            }
                        } else {
                            array_push($fieldValues, $value);
                        }
                    }

                    $fieldItemIds = $this->db->distinct('item_id')->select('item_id')
                        ->from('item_fields_data')
                        ->where('category_id', $data['categoryId'])
                        ->where_in('fields_id', $fieldIds)
                        ->where_in('value', $fieldValues)
                        ->get();

                    if ($fieldItemIds->num_rows() > 0) {
                        $fieldItemIds = $fieldItemIds->result_array();
                        foreach ($fieldItemIds as $key => $value) {
                            array_push($itemIds, $value['item_id']);
                        }
                    }
                }
            }

            /*if (isset($data['itemPriceMin']) && !empty($data['itemPriceMin'])
                && isset($data['itemPriceMax']) && !empty($data['itemPriceMax']))
            {
            	$min = $data['itemPriceMin'];
            	$max = $data['itemPriceMax'];
	            $filterSQL[] = " ( `i`.`price` BETWEEN '{$min}' AND '{$max}') ";
	        }*/

            if (isset($data['itemYearFrom']) && !empty($data['itemYearFrom'])
                && isset($data['itemYearTo']) && !empty($data['itemYearTo'])) {
                $from = $data['itemYearFrom'];
                $to = $data['itemYearTo'];
                $filterSQL[] = " ( `i`.`year` BETWEEN '{$from}' AND '{$to}') ";
            }

            if (($minMil >= 0) && ($maxMil > 0)) {
                $filterSQL[] = " ( `i`.`mileage` BETWEEN '{$minMil}' AND '{$maxMil}') ";
            }


            if (!empty($milageType)) {
                $milageType = $data['milageType'];
                $filterSQL[] = " ( `i`.`mileage_type` = '{$milageType}') ";
            }

            if (isset($data['itemMake']) && !empty($data['itemMake'])) {
                $itemMake = $data['itemMake'];
                $filterSQL[] = " ( `i`.`make` = '{$itemMake}') ";
            }

            if (isset($data['itemModel']) && !empty($data['itemModel'])) {
                $itemModel = $data['itemModel'];
                $filterSQL[] = " ( `i`.`model` = '{$itemModel}') ";
            }

            if (isset($data['specification']) && !empty($data['specification'])) {
                $specification = $data['specification'];
                $filterSQL[] = " ( `i`.`specification` = '{$specification}') ";
            }

            if (isset($data['itemLot']) && !empty($data['itemLot'])) {
                $itemLot = $data['itemLot'];
                $filterSQL[] = " ( `ai`.`order_lot_no` = '{$itemLot}') ";
            }

            if (!empty($filterSQL)) {
                $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            }


            $select = $this->sm->getItemSelect($data['auctionType']);

            $this->db->reset_query();
            $this->db->flush_cache();
            $this->db->start_cache();

            $this->db->select($select);
            $this->db->from('auction_items ai');
            $this->db->join('item i', 'i.id = ai.item_id');
            $this->db->join('item_makes imake', 'i.make = imake.id', 'LEFT');
            $this->db->join('item_models imodel', 'i.model = imodel.id', 'LEFT');

            if ($data['auctionType'] != 'live') {
                $this->db->join('bid b', 'ai.auction_id = b.auction_id AND ai.item_id = b.item_id', 'LEFT');
            }
            //$this->db->where('a.access_type', $data['auctionType']);
            $this->db->where('ai.sold_status', 'not');
            $this->db->where('i.sold', 'no');
            $this->db->where('ai.category_id', $data['categoryId']);

            if ($data['auctionType'] != 'live') {
                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `ai`.`bid_start_time` AND `ai`.`bid_end_time`');
            }

            if (!empty($auctionIds)) {
                $this->db->where_in('ai.auction_id', $auctionIds);
            }

            if (!empty($filterQuery)) {
                $this->db->where($filterQuery);
            }

            if (!empty($itemIds)) {
                // $this->db->or_where_in('ai.item_id', $itemIds);
                $this->db->where_in('ai.item_id', $itemIds);
            }

            if (isset($data['searchTerm']) && !empty($data['searchTerm'])) {
                $searchTerm = strtolower($data['searchTerm']);
                $searchTermSQL = ' LOWER(JSON_EXTRACT(item_name, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" 
                	OR LOWER(JSON_EXTRACT(item_detail, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" 
                	OR LOWER(JSON_EXTRACT(item_make_title, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" 
                	OR LOWER(JSON_EXTRACT(item_model_title, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" ';

                $this->db->having($searchTermSQL);

            }


            if (isset($data['itemPriceMin']) && !empty($data['itemPriceMin'])
                && isset($data['itemPriceMax']) && !empty($data['itemPriceMax'])) {
                $min = $data['itemPriceMin'];
                $max = $data['itemPriceMax'];
                $this->db->having(" (final_price BETWEEN '{$min}' AND '{$max}') ");
            }

            if ($data['auctionType'] != 'live') {
                $this->db->group_by('auction_item_id');
            }

            $this->db->stop_cache();

            ///   echo $this->db->get_compiled_select();return;

            $totalRecords = $this->db->get()->num_rows();


            $this->db->start_cache();
            //$this->db->limit($limit, $offset);


            //order by
            $orderBy = isset($data['sortBy']) ? $data['sortBy'] : "";
            if (!empty($orderBy)) {
                switch ($orderBy) {
                    case 'hp':
                        $this->db->order_by('final_price', "DESC");
                        break;
                    case 'lp':
                        $this->db->order_by('final_price', "ASC");
                        break;
                    case 'latest':
                        $this->db->order_by('ai.id', "DESC");
                        break;
                    case 'featured':
                        $this->db->order_by('item_feature', "ASC");
                        break;

                    default:
                        $this->db->order_by('item_feature', "ASC");
                        break;
                }
            } else {
                $this->db->order_by('item_feature', "ASC");
            }

            $this->db->stop_cache();

            //echo $this->db->get_compiled_select();return;

            /* print_r($data['searchTerm']);
              echo '<br>';
               print_r($searchTermSQL);
               echo '<br>';
               print_r($filterQuery);
                die;
                */

            //Execute Query
            $query = $this->db->get();
            $totalDisplayRecords = $query->num_rows();


            $this->db->flush_cache();
            $this->db->reset_query();

            //Make pagination

            $data2 = array();


            if ($totalRecords > 0 || !empty($availableAuctions)) {
                $items = $query->result_array();


                foreach ($items as $key => $val) {
                    if (!empty($val['item_images'])) {
                        $item_images = explode(',', $val['item_images']);
                        $i = 0;
                        $image_get = array();
                        while ($i < count($item_images)) {
                            if ($i == 0) {

                                $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                                $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                            }
                            $i++;
                        }
                        $items[$key]['image'] = $image_get;

                        if ($data['auctionType'] == "online") {
                            $items[$key]['online_detail_url'] = base_url() . 'api/v1/online-auction/details/' . $val['auction_id'] . '/' . $val['item_id'];
                            $items[$key]['access_type'] = "online";
                        } else {
                            $items[$key]['live_detail_url'] = base_url() . 'api/v1/live-online-detail/details/' . $val['auction_id'] . '/' . $val['item_id'];
                            $items[$key]['access_type'] = "live";
                        }
                    }
                    $items[$key]['bid_count'] = $this->db->where('item_id', $val['item_id'])->where('auction_id', $val['auction_id'])->from('bid')->count_all_results();

                    $heighest_bid_data = $this->oam->item_heighest_bid_data($val['item_id'], $val['auction_id']);
                    $current_bid = "";
                    $current_bid_user = "";
                    if (!empty($heighest_bid_data)) {
                        $items[$key]['current_bid'] = $heighest_bid_data['current_bid'];
                        $items[$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                        $current_bid = $heighest_bid_data['current_bid'];
                        $current_bid_user = $heighest_bid_data['user_id'];
                    }

                    $check_auction = $this->db->get_where('auctions', ['id' => $items[$key]['auction_id']])->row_array();
                    $auction_start_time = "";
                    $auction_end_time = "";
                    if (!empty($check_auction)) {

                        $auction_start_time = $check_auction['start_time'];
                        $auction_end_time = $check_auction['expiry_time'];
                    }


                    $data2[$key] = array(
                        'title' => "",
                        'item_name' => $items[$key]['item_name'],
                        'item_id' => $items[$key]['item_id'],
                        'auction_id' => $items[$key]['auction_id'],
                        'bid_amount' => $items[$key]['bid_start_price'],
                        'current_bid' => $current_bid,
                        'current_bid_user' => $current_bid_user,
                        'lot' => $items[$key]['order_lot_no'],
                        'auction_access_type' => $items[$key]['access_type'],
                        'bid_count' => $items[$key]['bid_count'],
                        'image' => $items[$key]['image'],
                        'category_id' => $items[$key]['category_id'],
                        'item_feature' => $items[$key]['item_feature'],
                        'start_time' => $items[$key]['bid_start_time'],
                        'expiry_time' => $items[$key]['bid_end_time'],
                        'created_time' => $items[$key]['bid_start_time'],
                        'specification' => $items[$key]['specification'],
                        'auction_start_time' => $auction_start_time,
                        'auction_end_time' => $auction_end_time

                    );


                }

                foreach ($availableAuctions as $key => $val) {
                    if (!empty($val['id'])) {
                        if(empty($availableAuctions[$key]['start_status'])){
                            $availableAuctions[$key]['start_status'] = "stop";
                        }
                        $availableAuctions[$key]['auction_url'] = base_url('api/v1/live-online/') . $val['id'];

                    }
                }

                $itemCategoryFields = $this->db->get_where('item_category_fields', ['category_id' => $data['categoryId']])->result_array();
                $itemMakes = $this->db->order_by('title', 'ASC')->get_where('item_makes', ['status' => 'active'])->result_array();

                foreach ($itemCategoryFields as $key => $val) {

                    $itemCategoryFields[$key]['name'] = "fields[" . $itemCategoryFields[$key]['id'] . "]" . "[]";

                }

                $records = array(
                    'live_auction_check' => $availableAuctions,
                    'item_fields' => $itemCategoryFields,
                    'item_make' => $itemMakes,
                    'items' => $data2,
                    'orderBy' => $orderBy,
                    'totalRecords' => $totalRecords,
                );

                $codee = http_response_code(200);
                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Successfully Got Auction Item Data By Category', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $records));

            } else {
                $output = json_encode(['status' => 'failed', 'totalRecords' => $totalRecords]);
                return print_r($output);
            }

        }
    }


    public function getAuctionItems3($auctionType, $categoryId, $record = array(), $language)
    {
        $version = $this->config->item('appVersion');

        $userId = "";


        $postData = array();

        $data = array();

        if (isset($record['searchTerm'])) {

            $data['searchTerm'] = $record['searchTerm'];
        }

        $data['auctionType'] = $auctionType;
        $data['categoryId'] = $categoryId;


        if ($data) {
            $minMil = $data['itemMilageMin'] ?? 0;
            $maxMil = $data['itemMilageMax'] ?? 0;
            $milageType = $data['milageType'] ?? '';

            $filterSQL = [];
            $filterQuery = '';

            // remove all empty value's keys
            $data = array_filter($data);


            //auction selection for displaying record
            $this->db->where([
                'access_type' => $data['auctionType'],
                'category_id' => $data['categoryId'],
                'status' => 'active'
            ]);

            if (in_array($data['auctionType'], ['online', 'closed'])) {
                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `start_time` AND `expiry_time`');
            }

            if (in_array($data['auctionType'], ['live'])) {
                $this->db->where("(`expiry_time` >= CURRENT_TIMESTAMP() OR `start_status` = 'start')");
                // $this->db->or_where('`start_status`', 'start');
                $this->db->order_by('`start_time`', 'ASC');
            } else {
                $this->db->order_by('id', 'ASC');
            }

            if (in_array($data['auctionType'], ['online', 'live'])) {
                $this->db->limit(1);
            }

            $availableAuctions = $this->db->get('auctions')->result_array();


            $auctionIds = [];
            foreach ($availableAuctions as $key => $availableAuction) {
                array_push($auctionIds, $availableAuction['id']);
            }
            if (empty($auctionIds)) {
                array_push($auctionIds, 0);
            }

            if (empty($auctionIds)) {
                //$output = json_encode(['status' => 'failed']);
                //return print_r($output);
            }

            // echo $this->db->last_query();return;

            //offset and limit for records
            $limit = 15;
            $offset = (isset($data['offset']) && !empty($data['offset'])) ? (int)$data['offset'] : 0;
            //$next_offset = (int)$offset + $limit;


            //get item ids from category fields data
            $itemIds = [];
            if (isset($data['fields']) && !empty($data['fields'])) {
                $fieldIds = $fieldValues = [];
                $fields = array_filter($data['fields']);
                unset($data['fields']);

                if ($fields) {
                    foreach ($fields as $key => $value) {
                        array_push($fieldIds, $key);
                        if (is_array($value)) {
                            foreach ($value as $k => $v) {
                                array_push($fieldValues, $v);
                            }
                        } else {
                            array_push($fieldValues, $value);
                        }
                    }

                    $fieldItemIds = $this->db->distinct('item_id')->select('item_id')
                        ->from('item_fields_data')
                        ->where('category_id', $data['categoryId'])
                        ->where_in('fields_id', $fieldIds)
                        ->where_in('value', $fieldValues)
                        ->get();

                    if ($fieldItemIds->num_rows() > 0) {
                        $fieldItemIds = $fieldItemIds->result_array();
                        foreach ($fieldItemIds as $key => $value) {
                            array_push($itemIds, $value['item_id']);
                        }
                    }
                }
            }

            /*if (isset($data['itemPriceMin']) && !empty($data['itemPriceMin'])
                && isset($data['itemPriceMax']) && !empty($data['itemPriceMax']))
            {
            	$min = $data['itemPriceMin'];
            	$max = $data['itemPriceMax'];
	            $filterSQL[] = " ( `i`.`price` BETWEEN '{$min}' AND '{$max}') ";
	        }*/

            if (isset($data['itemYearFrom']) && !empty($data['itemYearFrom'])
                && isset($data['itemYearTo']) && !empty($data['itemYearTo'])) {
                $from = $data['itemYearFrom'];
                $to = $data['itemYearTo'];
                $filterSQL[] = " ( `i`.`year` BETWEEN '{$from}' AND '{$to}') ";
            }

            if (($minMil >= 0) && ($maxMil > 0)) {
                $filterSQL[] = " ( `i`.`mileage` BETWEEN '{$minMil}' AND '{$maxMil}') ";
            }


            if (!empty($milageType)) {
                $milageType = $data['milageType'];
                $filterSQL[] = " ( `i`.`mileage_type` = '{$milageType}') ";
            }

            if (isset($data['itemMake']) && !empty($data['itemMake'])) {
                $itemMake = $data['itemMake'];
                $filterSQL[] = " ( `i`.`make` = '{$itemMake}') ";
            }

            if (isset($data['itemModel']) && !empty($data['itemModel'])) {
                $itemModel = $data['itemModel'];
                $filterSQL[] = " ( `i`.`model` = '{$itemModel}') ";
            }

            if (isset($data['specification']) && !empty($data['specification'])) {
                $specification = $data['specification'];
                $filterSQL[] = " ( `i`.`specification` = '{$specification}') ";
            }

            if (isset($data['itemLot']) && !empty($data['itemLot'])) {
                $itemLot = $data['itemLot'];
                $filterSQL[] = " ( `ai`.`order_lot_no` = '{$itemLot}') ";
            }

            if (!empty($filterSQL)) {
                $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            }


            $select = $this->sm->getItemSelect($data['auctionType']);

            $this->db->reset_query();
            $this->db->flush_cache();
            $this->db->start_cache();

            $this->db->select($select);
            $this->db->from('auction_items ai');
            $this->db->join('item i', 'i.id = ai.item_id');
            $this->db->join('item_makes imake', 'i.make = imake.id', 'LEFT');
            $this->db->join('item_models imodel', 'i.model = imodel.id', 'LEFT');

            if ($data['auctionType'] != 'live') {
                $this->db->join('bid b', 'ai.auction_id = b.auction_id AND ai.item_id = b.item_id', 'LEFT');
            }
            //$this->db->where('a.access_type', $data['auctionType']);
            $this->db->where('ai.sold_status', 'not');
            $this->db->where('i.sold', 'no');
            $this->db->where('ai.category_id', $data['categoryId']);

            if ($data['auctionType'] != 'live') {
                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `ai`.`bid_start_time` AND `ai`.`bid_end_time`');
            }

            if (!empty($auctionIds)) {
                $this->db->where_in('ai.auction_id', $auctionIds);
            }

            if (!empty($filterQuery)) {
                $this->db->where($filterQuery);
            }

            if (!empty($itemIds)) {
                // $this->db->or_where_in('ai.item_id', $itemIds);
                $this->db->where_in('ai.item_id', $itemIds);
            }

            if (isset($data['searchTerm']) && !empty($data['searchTerm'])) {
                $searchTerm = strtolower($data['searchTerm']);
                $searchTermSQL = ' LOWER(JSON_EXTRACT(item_name, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" 
                	OR LOWER(JSON_EXTRACT(item_detail, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" 
                	OR LOWER(JSON_EXTRACT(item_make_title, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" 
                	OR LOWER(JSON_EXTRACT(item_model_title, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" ';

                $this->db->having($searchTermSQL);

            }


            if (isset($data['itemPriceMin']) && !empty($data['itemPriceMin'])
                && isset($data['itemPriceMax']) && !empty($data['itemPriceMax'])) {
                $min = $data['itemPriceMin'];
                $max = $data['itemPriceMax'];
                $this->db->having(" (final_price BETWEEN '{$min}' AND '{$max}') ");
            }

            if ($data['auctionType'] != 'live') {
                $this->db->group_by('auction_item_id');
            }

            $this->db->stop_cache();

            ///   echo $this->db->get_compiled_select();return;

            $totalRecords = $this->db->get()->num_rows();


            $this->db->start_cache();
            //$this->db->limit($limit, $offset);


            //order by
            $orderBy = isset($data['sortBy']) ? $data['sortBy'] : "";
            if (!empty($orderBy)) {
                switch ($orderBy) {
                    case 'hp':
                        $this->db->order_by('final_price', "DESC");
                        break;
                    case 'lp':
                        $this->db->order_by('final_price', "ASC");
                        break;
                    case 'latest':
                        $this->db->order_by('ai.id', "DESC");
                        break;
                    case 'featured':
                        $this->db->order_by('item_feature', "ASC");
                        break;

                    default:
                        $this->db->order_by('item_feature', "ASC");
                        break;
                }
            } else {
                $this->db->order_by('item_feature', "ASC");
            }

            $this->db->stop_cache();

            //echo $this->db->get_compiled_select();return;

            /* print_r($data['searchTerm']);
              echo '<br>';
               print_r($searchTermSQL);
               echo '<br>';
               print_r($filterQuery);
                die;
                */

            //Execute Query
            $query = $this->db->get();
            $totalDisplayRecords = $query->num_rows();


            $this->db->flush_cache();
            $this->db->reset_query();

            //Make pagination

            $data2 = array();

            if ($totalRecords > 0 || !empty($availableAuctions)) {
                $items = $query->result_array();


                foreach ($items as $key => $val) {
                    if (!empty($val['item_images'])) {
                        $item_images = explode(',', $val['item_images']);
                        $i = 0;
                        $image_get = array();
                        while ($i < count($item_images)) {
                            if ($i == 0) {

                                $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                                $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                            }
                            $i++;
                        }
                        $items[$key]['image'] = $image_get;
                        if ($data['auctionType'] == "online") {
                            $items[$key]['online_detail_url'] = base_url() . 'api/v1/online-auction/details/' . $val['auction_id'] . '/' . $val['item_id'];
                            $items[$key]['access_type'] = "online";
                        } else {
                            $items[$key]['live_detail_url'] = base_url() . 'api/v1/live-online-detail/details/' . $val['auction_id'] . '/' . $val['item_id'];
                            $items[$key]['access_type'] = "live";
                        }
                    }
                    $items[$key]['bid_count'] = $this->db->where('item_id', $val['item_id'])->where('auction_id', $val['auction_id'])->from('bid')->count_all_results();

                    $heighest_bid_data = $this->oam->item_heighest_bid_data($val['item_id'], $val['auction_id']);
                    $current_bid = "";
                    $current_bid_user = "";
                    if (!empty($heighest_bid_data)) {
                        $items[$key]['current_bid'] = $heighest_bid_data['current_bid'];
                        $items[$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                        $current_bid = $heighest_bid_data['current_bid'];
                        $current_bid_user = $heighest_bid_data['user_id'];
                    }


                    $data2[$key] = array(
                        'title' => "",
                        'item_name' => $items[$key]['item_name'],
                        'item_id' => $items[$key]['item_id'],
                        'auction_id' => $items[$key]['auction_id'],
                        'bid_amount' => $items[$key]['bid_start_price'],
                        'current_bid' => $current_bid,
                        'current_bid_user' => $current_bid_user,
                        'lot' => $items[$key]['order_lot_no'],
                        'auction_access_type' => $items[$key]['access_type'],
                        'bid_count' => $items[$key]['bid_count'],
                        'image' => $items[$key]['image'],
                        'category_id' => $items[$key]['category_id'],
                        'item_feature' => $items[$key]['item_feature'],
                        'start_time' => $items[$key]['bid_start_time'],
                        'expiry_time' => $items[$key]['bid_end_time'],
                        'created_time' => $items[$key]['bid_start_time']
                    );

                }

                foreach ($availableAuctions as $key => $val) {
                    if (!empty($val['id'])) {
                        $availableAuctions[$key]['auction_url'] = base_url('api/v1/live-online/') . $val['id'];

                    }
                }

                return $data2;

            } else {
                return array();
            }

        }
    }


    public
    function getAuctionItems2($auctionType, $categoryId, $record = array())
    {
        $version = $this->config->item('appVersion');

        $userId = "";


        $postData = array();

        $data = array();

        if (isset($record['searchTerm'])) {

            $data['searchTerm'] = $record['searchTerm'];
        }

        $data['auctionType'] = $auctionType;
        $data['categoryId'] = $categoryId;

        if ($data) {

            $minMil = $data['itemMilageMin'] ?? 0;
            $maxMil = $data['itemMilageMax'] ?? 0;
            $milageType = $data['milageType'] ?? '';

            $filterSQL = [];
            $filterQuery = '';

            $data = array_filter($data);


            $this->db->where([
                'access_type' => $data['auctionType'],
                'category_id' => $data['categoryId'],
                'status' => 'active'
            ]);

            if (in_array($data['auctionType'], ['online', 'closed'])) {
                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `start_time` AND `expiry_time`');
            }

            if (in_array($data['auctionType'], ['live'])) {
                $this->db->where("(`expiry_time` >= CURRENT_TIMESTAMP() OR `start_status` = 'start')");
                $this->db->order_by('`start_time`', 'ASC');
            } else {
                $this->db->order_by('id', 'ASC');
            }

            if (in_array($data['auctionType'], ['online', 'live'])) {
                $this->db->limit(1);
            }

            $availableAuctions = $this->db->get('auctions')->result_array();


            $auctionIds = [];
            foreach ($availableAuctions as $key => $availableAuction) {
                array_push($auctionIds, $availableAuction['id']);
            }
            if (empty($auctionIds)) {
                array_push($auctionIds, 0);
            }

            if (empty($auctionIds)) {
                //$output = json_encode(['status' => 'failed']);
                //return print_r($output);
            }

            //offset and limit for records
            $limit = 15;
            $offset = (isset($data['offset']) && !empty($data['offset'])) ? (int)$data['offset'] : 0;
            //$next_offset = (int)$offset + $limit;


            $itemIds = [];
            if (isset($data['fields']) && !empty($data['fields'])) {
                $fieldIds = $fieldValues = [];
                $fields = array_filter($data['fields']);
                unset($data['fields']);

                if ($fields) {
                    foreach ($fields as $key => $value) {
                        array_push($fieldIds, $key);
                        if (is_array($value)) {
                            foreach ($value as $k => $v) {
                                array_push($fieldValues, $v);
                            }
                        } else {
                            array_push($fieldValues, $value);
                        }
                    }

                    $fieldItemIds = $this->db->distinct('item_id')->select('item_id')
                        ->from('item_fields_data')
                        ->where('category_id', $data['categoryId'])
                        ->where_in('fields_id', $fieldIds)
                        ->where_in('value', $fieldValues)
                        ->get();

                    if ($fieldItemIds->num_rows() > 0) {
                        $fieldItemIds = $fieldItemIds->result_array();
                        foreach ($fieldItemIds as $key => $value) {
                            array_push($itemIds, $value['item_id']);
                        }
                    }
                }
            }


            if (isset($data['itemYearFrom']) && !empty($data['itemYearFrom'])
                && isset($data['itemYearTo']) && !empty($data['itemYearTo'])) {
                $from = $data['itemYearFrom'];
                $to = $data['itemYearTo'];
                $filterSQL[] = " ( `i`.`year` BETWEEN '{$from}' AND '{$to}') ";
            }

            if (($minMil >= 0) && ($maxMil > 0)) {
                $filterSQL[] = " ( `i`.`mileage` BETWEEN '{$minMil}' AND '{$maxMil}') ";
            }


            if (!empty($milageType)) {
                $milageType = $data['milageType'];
                $filterSQL[] = " ( `i`.`mileage_type` = '{$milageType}') ";
            }

            if (isset($data['itemMake']) && !empty($data['itemMake'])) {
                $itemMake = $data['itemMake'];
                $filterSQL[] = " ( `i`.`make` = '{$itemMake}') ";
            }

            if (isset($data['itemModel']) && !empty($data['itemModel'])) {
                $itemModel = $data['itemModel'];
                $filterSQL[] = " ( `i`.`model` = '{$itemModel}') ";
            }

            if (isset($data['specification']) && !empty($data['specification'])) {
                $specification = $data['specification'];
                $filterSQL[] = " ( `i`.`specification` = '{$specification}') ";
            }

            if (isset($data['itemLot']) && !empty($data['itemLot'])) {
                $itemLot = $data['itemLot'];
                $filterSQL[] = " ( `ai`.`order_lot_no` = '{$itemLot}') ";
            }

            if (!empty($filterSQL)) {
                $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            }


            $select = $this->sm->getItemSelect($data['auctionType']);

            $this->db->reset_query();
            $this->db->flush_cache();
            $this->db->start_cache();

            $this->db->select($select);
            $this->db->from('auction_items ai');
            $this->db->join('item i', 'i.id = ai.item_id');
            $this->db->join('item_makes imake', 'i.make = imake.id', 'LEFT');
            $this->db->join('item_models imodel', 'i.model = imodel.id', 'LEFT');

            if ($data['auctionType'] != 'live') {
                $this->db->join('bid b', 'ai.auction_id = b.auction_id AND ai.item_id = b.item_id', 'LEFT');
            }

            $this->db->where('ai.sold_status', 'not');
            $this->db->where('i.sold', 'no');
            $this->db->where('ai.category_id', $data['categoryId']);

            if ($data['auctionType'] != 'live') {
                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `ai`.`bid_start_time` AND `ai`.`bid_end_time`');
            }

            if (!empty($auctionIds)) {
                $this->db->where_in('ai.auction_id', $auctionIds);
            }

            if (!empty($filterQuery)) {
                $this->db->where($filterQuery);
            }

            if (!empty($itemIds)) {
                // $this->db->or_where_in('ai.item_id', $itemIds);
                $this->db->where_in('ai.item_id', $itemIds);
            }

            if (isset($data['searchTerm']) && !empty($data['searchTerm'])) {
                $searchTerm = strtolower($data['searchTerm']);
                $language = "english";
                $searchTermSQL = ' LOWER(JSON_EXTRACT(item_name, "$.' . $language . '")) LIKE "%' . $searchTerm . '%"
                        OR LOWER(JSON_EXTRACT(item_detail, "$.' . $language . '")) LIKE "%' . $searchTerm . '%"
                        OR LOWER(JSON_EXTRACT(item_make_title, "$.' . $language . '")) LIKE "%' . $searchTerm . '%"
                        OR LOWER(JSON_EXTRACT(item_model_title, "$.' . $language . '")) LIKE "%' . $searchTerm . '%" ';

                $this->db->having($searchTermSQL);

            }


            if (isset($data['itemPriceMin']) && !empty($data['itemPriceMin'])
                && isset($data['itemPriceMax']) && !empty($data['itemPriceMax'])) {
                $min = $data['itemPriceMin'];
                $max = $data['itemPriceMax'];
                $this->db->having(" (final_price BETWEEN '{$min}' AND '{$max}') ");
            }

            if ($data['auctionType'] != 'live') {
                $this->db->group_by('auction_item_id');
            }

            $this->db->stop_cache();


            $totalRecords = $this->db->get()->num_rows();


            $this->db->start_cache();
            $this->db->limit($limit, $offset);


            //order by
            $orderBy = isset($data['sortBy']) ? $data['sortBy'] : "";
            if (!empty($orderBy)) {
                switch ($orderBy) {
                    case 'hp':
                        $this->db->order_by('final_price', "DESC");
                        break;
                    case 'lp':
                        $this->db->order_by('final_price', "ASC");
                        break;
                    case 'latest':
                        $this->db->order_by('ai.id', "DESC");
                        break;
                    case 'featured':
                        $this->db->order_by('item_feature', "ASC");
                        break;

                    default:
                        $this->db->order_by('item_feature', "ASC");
                        break;
                }
            } else {
                $this->db->order_by('item_feature', "ASC");
            }

            $this->db->stop_cache();


            $query = $this->db->get();
            $totalDisplayRecords = $query->num_rows();


            $this->db->flush_cache();
            $this->db->reset_query();


            if ($totalRecords > 0 || !empty($availableAuctions)) {
                $items = $query->result_array();

                foreach ($items as $key => $val) {
                    if (!empty($val['item_images'])) {
                        $item_images = explode(',', $val['item_images']);
                        $i = 0;
                        $image_get = array();
                        while ($i < count($item_images)) {

                            $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                            $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                            $i++;
                        }
                        $items[$key]['item_images'] = $image_get;
                        $items[$key]['online_detail_url'] = base_url() . 'api/v1/online-auction/details/' . $val['auction_id'] . '/' . $val['item_id'];
                        $items[$key]['bid_count'] = $this->db->where('item_id', $val['item_id'])->where('auction_id', $val['auction_id'])->from('bid')->count_all_results();
                    }
                }


                $record['online_auction_items'] = $items;
                $record['orderBy'] = $orderBy;
                $record['totalRecords'] = $totalRecords;


                $codee = http_response_code(200);
                echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Auction Online Data', 'appVersion' => $version, 'result' => $record));


            } else {
                return [];
            }

        } else {
            return [];
        }


    }


    public function printCatalog($auctionId = '')
    {

        $version = $this->config->item('appVersion');

        if (empty($auctionId)) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Search Id not Found', 'appVersion' => $version));

        }

        $language = "english";

        $auction = $this->db->get_where('auctions', ['id' => $auctionId])->row_array();

        $select = $this->sm->getItemCatalog();

        $this->db->select($select);
        $this->db->from('auction_items ai');
        $this->db->join('item i', 'i.id = ai.item_id');
        //$this->db->join('auctions a', 'a.id = ai.auction_id');
        $this->db->join('item_makes imake', 'imake.id = i.make', 'LEFT');
        $this->db->join('item_models imodel', 'imodel.id = i.model', 'LEFT');

        $this->db->where('ai.auction_id', $auctionId);

        $this->db->order_by('ai.order_lot_no', 'ASC');

        $catalog = $this->db->get()->result_array();

        $data['catalog'] = $catalog;
        $data['auction'] = $auction;
        $data['language'] = $language;

        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Catlog Details', 'appVersion' => $version, 'result' => $data));


    }


    // live auction detail page
    public function live_online_item_detail($auction_id, $item_id)
    {


        $version = $this->config->item('appVersion');
        $data_array = json_decode(file_get_contents("php://input"), true);
        $data2 = json_decode(file_get_contents("php://input"));
        $language = "english";

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if (isset($data_array['select_language']) && !empty($data_array['select_language'])) {
            $language = $data_array['select_language'];
        }


        if (empty($auction_id) || empty($auction_id)) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'AUCTION OR ITEM IDS NOT FOUND', 'english' => $english_l['auction_id_or_item_id_not_found'], 'arabic' => $arabic_l['auction_id_or_item_id_not_found'], 'appVersion' => $version));

        } else {

            $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $ret = strtr(rawurlencode($actual_link), $revert);
            $data['auction_id'] = $auction_id;
            $data['item_id'] = $item_id;


            // Get online auctions
//            $data['online_auctions'] = $this->oam->get_online_auctions();
//            foreach ($data['online_auctions'] as $key => $val) {
//
//                if (!empty($val['cat_icon'])) {
//                    $item_images = $val['cat_icon'];
//                    $i = 0;
//                    $image = $this->db->get_where('files', ['id' => $item_images])->row_array();
//                    $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
//                    $i++;
//
//                    $data['online_auctions'][$key]['item_images'] = $image_get;
//
//                }
//            }
//
//
//            $data['all_categories'] = $this->getapimodel->get_catagories_home();
//            foreach ($data['all_categories'] as $key => $val) {
//
//                if (!empty($val['path'])) {
//                    $image_get = trim(base_url(), '/') . trim($val['path'], '.') . $val['image_name'];
//                    $data['all_categories'][$key]['image_url'] = $image_get;
//
//                }
//            }


            //auction item details
            $item_data = $this->oam->get_items_details($item_id, $auction_id);
            $data['item'] = $this->get_items_details($item_id, $auction_id);

            $data['category_id'] = $item_data['category_id'];
            $data['category'] = $this->db->get_where('item_category', ['id' => $data['category_id']])->row_array();

            $odometer = "";
            $odometer_image = "";

            $spec_mileage = "";
            $spec_image = "";
            $specsType = "";

            foreach ($data['item'] as $key => $val) {


                //specs data

                if (($data['category']['include_make_model'] == 'yes')) {

                    $odometer = number_format($val['mileage']);
                    $odometer_image = base_url('assets_user') . "/new/images/detail-icons/odometer-icons.svg";

                }

                if (($data['category']['include_make_model'] == 'yes') && isset($val['specification'])) {

                    $spec_mileage = number_format($val['mileage']);
                    $spec_image = base_url('assets_user') . "/new/images/detail-icons/specification-icons.svg";

                    if ($val['specification'] == 'GCC') {
                        $specsType = 'GCC';
                    } else {
                        $specsType = 'IMPORTED';
                    }
                }

                if (empty($data['item'][$key]['additional_info'])) {
                    $additional_info = [
                        'english' => '',
                        'arabic' => '',
                    ];
                    $data['item'][$key]['additional_info'] = json_encode($additional_info);
                }

                $item_make = $this->db->get_where('item_makes', ['id' => $val['item_make']])->row_array();
                $item_model = $this->db->get_where('item_models', ['id' => $val['item_model']])->row_array();
                $make_name = $item_make['title'];
                $model_name = $item_model['title'];
                $data['item'][$key]['item_make_title'] = $make_name;
                $data['item'][$key]['item_model_title'] = $model_name;
                $data['item'][$key]['map'] = "https://www.google.com/maps/@" . $data['item'][$key]['item_lat'] . ',' . $data['item'][$key]['item_lng'] . ',15z';


                $data['item'][$key]['report'] = base_url('inspection_report/') . $data['item'][$key]['id'];

                $f = 0;
                if (!empty($data['item'][$key]['item_test_report'])) {

                    $inspection_report = array();
                    $inspection_report_name = array();

                    $doc_ids = explode(',', $data['item'][$key]['item_test_report']);
                    while ($f < count($doc_ids)) {
                        $item_test_report = $this->db->where_in('id', $doc_ids[$f])->get('files')->row_array();

                        $inspection_report[$f] = base_url() . $item_test_report['path'] . $item_test_report['name'];
                        $inspection_report_name[$f] = $item_test_report['orignal_name'];

                        $data['item'][$key]['inspection_report'][] = array(
                            'url' => $inspection_report[$f],
                            'title' => $inspection_report_name[$f]
                        );
                        $f++;
                    }


                } else {
                    $data['item'][$key]['inspection_report'] = null;
                }

                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {
                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
                    $data['item'][$key]['item_images'] = $image_get;
                }

                if (!empty($val['threed_images'])) {
//                    $item_images = explode(',', $val['threed_images']);
//                    $i = 0;
//                    $image_get = array();
//                    while ($i < count($item_images)) {
//                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
//                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
//                        $i++;
//                    }
                    $data['item'][$key]['threed_images'] = true;
                } else {
                    $data['item'][$key]['threed_images'] = false;
                }
            }


            //items category dynamic fields
            $datafields = $this->oam->fields_data($item_data['category_id']);
            $fdata = array();
            foreach ($datafields as $key => $fields) {
                $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id, $fields['id']);
                if (!empty($item_dynamic_fields_info)) {
                    $fields['values'] = json_decode($fields['values'], true);
                    $fields['data-id'] = $fields['id'];
                    if (!empty($fields['values'])) {
                        foreach ($fields['values'] as $key => $options) {
                            if ($options['value'] == $item_dynamic_fields_info['value']) {
                                $fields['data-value'] = $options['label'];
                            }
                        }
                    } else {
                        $fields['data-value'] = $item_dynamic_fields_info['value'];
                    }
                    $fdata[] = $fields;
                }
            }
            $j = 11;
            $i = 0;
            foreach ($fdata as $key => $value) {
                if (!empty($value['data-value'])) {
                    $i++;
                    if ($i <= $j) {
                        $data['field']['label'][] = $this->make_dual($value['label'], $language);
                        $data['field']['value'][] = $this->make_dual($value['data-value'], $language);

                        if ($i == 1) {
                            if (($data['category']['include_make_model'] == 'yes')) {
                                if ($language == "english") {

                                    $data['field']['label'][] = "Odometer";
                                    $data['field']['label'][] = "Specification";

                                    if ($specsType == 'GCC') {
                                        $specsType = 'GCC';
                                    } else {
                                        $specsType = 'IMPORTED';
                                    }

                                    $data['field']['value'][] = $odometer;
                                    $data['field']['value'][] = $specsType;
                                } else {
                                    $data['field']['label'][] = 'عداد المركبة';
                                    $data['field']['label'][] = "المواصفات";
                                    if ($specsType == 'GCC') {
                                        $specsType = 'خليجية';
                                    } else {
                                        $specsType = 'وارد';
                                    }
                                    $data['field']['value'][] = $odometer;
                                    $data['field']['value'][] = $specsType;
                                }
                            }
                        }
                    }
                }
            }


            // get contact us details
            $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();

            //highest bid data
            $data['heighest_bid_data'] = $this->oam->item_heighest_bid_data($item_id, $auction_id);

            //related items
            $item_ids = [];
            $related_items = $this->db->where('item_id !=', $item_id)->get_where('auction_items', ['auction_id' => $auction_id])->result_array();
            foreach ($related_items as $key => $v) {
                array_push($item_ids, $v['item_id']);
            }
            if (empty($item_ids)) {
                array_push($item_ids, 0);
            }
            $data['related_items'] = $this->lam->get_live_auction_items($auction_id, 10, 0, $item_ids);

            foreach ($data['related_items'] as $key => $val) {

                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {

                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
                    $data['related_items'][$key]['item_images'] = $image_get;
                }

            }


            //get total bid count of this item
            $data['bid_count'] = $this->db->where('item_id', $item_id)->where('auction_id', $auction_id)->from('bid')->count_all_results();

            //count total users view this item
            $data['visit_count'] = $this->db->where('item_id', $item_id)->where('auction_id', $auction_id)->from('online_auction_item_visits')->count_all_results();

            $data['user_id'] = '';
            $data['balance'] = 'N/A';
            $data['hide_auto_bid'] = 'N/A';
            $data['user'] = NULL;
            $data['fav_item'] = NULL;

            $user = validateToken2();
            if (!empty($user)) {
                if ($user) {
                    $user_id = $user;
                    $data['user'] = (array)$user;
                    $data['user_id'] = $user_id;

                    //check user is blocked or not
                    $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
                    if ($user['status'] == 1) {

                        //get user balance
                        $user_total_deposit = $this->customer_model->user_balance($user_id);
                        $data['balance'] = $user_total_deposit['amount'];

                        $visit_data = array(
                            'user_id' => $user_id,
                            'auction_id' => $auction_id,
                            'item_id' => $item_id
                        );

                        //check user already visit this item or not
                        $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
                        if (!$visit_info) {
                            $this->db->insert('online_auction_item_visits', $visit_data);
                        }

                        //user is auto bidder or not
                        $data['hide_auto_bid'] = $this->db->get_where('bid_auto', ['user_id' => $user_id, 'item_id' => $item_id, 'auto_status' => 'start'])->row_array();

                        // User favourite item
                        $data['fav_item'] = $this->db->get_where('favorites_items', ['user_id' => $user_id, 'item_id' => $item_id])->row_array();
                        // print_r($u);die();
                        $data['auto_bid_data'] = $this->db->get_where('bid_auto', ['user_id' => $user_id, 'item_id' => $item_id, 'auction_id' => $auction_id])->row_array();

                    }
                }
                $data['min_increment'] = $this->db->select('min_increment')->get('auction_live_settings')->row_array();
            }

            $codee = http_response_code(200);
            echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'Successfully Get Auction Item Details', 'appVersion' => $version, 'result' => $data));

        }
    }


    public function three_d($item_id = "")
    {
        $this->load->view('getapi/three_d', ['item_id' => $item_id]);
    }

    public function live_online_auction_view($auction_id)
    {
        $version = $this->config->item('appVersion');
        $data_array = json_decode(file_get_contents("php://input"), true);
        $data2 = json_decode(file_get_contents("php://input"));
        $language = "english";

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if (isset($data_array['select_language']) && !empty($data_array['select_language'])) {
            $language = $data_array['select_language'];
        }

        if (empty($auction_id)) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'AUCTION ID NOT FOUND', 'english' => $english_l['auction_id_not_found'], 'arabic' => $arabic_l['auction_id_not_found'], 'appVersion' => $version));
            exit;
        }

        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $ret = strtr(rawurlencode($actual_link), $revert);
        $data['rurl'] = $ret;

        $data['new'] = 'new';
        $data['auction_id'] = $auction_id;
        $data['auction'] = $this->db->get_where('auctions', ['id' => $data['auction_id']])->row_array();

        $last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id]);
        if ($last_bid->num_rows() > 0) {
            $last_bid = $last_bid->row_array();
            $item_id = $last_bid['item_id'];
            $data['item_id'] = $item_id;
            $data['last_bid'] = $last_bid;
        } else {

            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Live auction Available', 'english' => $english_l['auction_not_initialized'], 'arabic' => $arabic_l['auction_not_initialized'], 'appVersion' => $version));
            exit;
        }

        //auction item details

        $item_data = $this->oam->get_items_details($item_id, $auction_id);
        $data['item'] = $this->get_items_details($item_id, $auction_id);


        $data['category_id'] = $item_data['category_id'];
        $data['category'] = $this->db->get_where('item_category', ['id' => $data['category_id']])->row_array();

        $odometer = "";
        $odometer_image = "";

        $spec_mileage = "";
        $spec_image = "";
        $specsType = "";

        foreach ($data['item'] as $key => $val) {


            //specs data

            if (($data['category']['include_make_model'] == 'yes')) {

                $odometer = number_format($val['mileage']);
                $odometer_image = base_url('assets_user') . "/new/images/detail-icons/odometer-icons.svg";

            }

            if (($data['category']['include_make_model'] == 'yes') && isset($val['specification'])) {

                $spec_mileage = number_format($val['mileage']);
                $spec_image = base_url('assets_user') . "/new/images/detail-icons/specification-icons.svg";

                if ($val['specification'] == 'GCC') {
                    $specsType = 'GCC';
                } else {
                    $specsType = 'IMPORTED';
                }
            }

            if (empty($data['item'][$key]['additional_info'])) {
                $additional_info = [
                    'english' => '',
                    'arabic' => '',
                ];
                $data['item'][$key]['additional_info'] = json_encode($additional_info);
            }

            $item_make = $this->db->get_where('item_makes', ['id' => $val['item_make']])->row_array();
            $item_model = $this->db->get_where('item_models', ['id' => $val['item_model']])->row_array();

            $make_name = $item_make['title'];
            $model_name = $item_model['title'];
            $data['item'][$key]['item_make_title'] = $make_name;
            $data['item'][$key]['item_model_title'] = $model_name;
            $data['item'][$key]['map'] = "https://www.google.com/maps/@" . $data['item'][$key]['item_lat'] . ',' . $data['item'][$key]['item_lng'] . ',15z';


            $data['item'][$key]['report'] = base_url('inspection_report/') . $data['item'][$key]['id'];

            $f = 0;
            if (!empty($data['item'][$key]['item_test_report'])) {

                $inspection_report = array();
                $inspection_report_name = array();

                $doc_ids = explode(',', $data['item'][$key]['item_test_report']);
                while ($f < count($doc_ids)) {
                    $item_test_report = $this->db->where_in('id', $doc_ids[$f])->get('files')->row_array();

                    $inspection_report[$f] = base_url() . $item_test_report['path'] . $item_test_report['name'];
                    $inspection_report_name[$f] = $item_test_report['orignal_name'];

                    $data['item'][$key]['inspection_report'][] = array(
                        'url' => $inspection_report[$f],
                        'title' => $inspection_report_name[$f]
                    );
                    $f++;
                }


            } else {
                $data['item'][$key]['inspection_report'] = null;
            }

            if (!empty($val['item_images'])) {
                $item_images = explode(',', $val['item_images']);
                $i = 0;
                $image_get = array();
                while ($i < count($item_images)) {
                    $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                    $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                    $i++;
                }
                $data['item'][$key]['item_images'] = $image_get;
            }

            if (!empty($val['threed_images'])) {
//                    $item_images = explode(',', $val['threed_images']);
//                    $i = 0;
//                    $image_get = array();
//                    while ($i < count($item_images)) {
//                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
//                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
//                        $i++;
//                    }
                $data['item'][$key]['threed_images'] = true;
            } else {
                $data['item'][$key]['threed_images'] = false;
            }

            $data['bid_place']['min_bid'] = $data['item'][$key]['minimum_bid_price'];
            $data['bid_place']['data-auction-id'] = $data['item'][$key]['auction_id'];
            $data['bid_place']['data-item-id'] = $data['item'][$key]['item_id'];
            $data['bid_place']['data-seller-id'] = $data['item'][$key]['item_seller_id'];
            $data['bid_place']['data-lot-no'] = $data['item'][$key]['auction_item_id'];
            if($data['item'][$key]['bid_start_price'] != null){
                $data['bid_place']['data-start-price'] = $data['item'][$key]['bid_start_price'];
            }else{
                $data['item'][$key]['bid_start_price'] = 0;
                $data['bid_place']['data-start-price']  = 0;
            }
            
            $data['bid_place']['auction_item_id'] = $data['item'][$key]['auction_item_id'];
        }


        $data['item_category_fields'] = $this->db->get_where('item_category_fields', ['category_id' => $data['auction']['category_id']])->result_array();
        $data['category_id'] = $data['auction']['category_id'];
        $data['item_category'] = $this->db->get_where('item_category', ['id' => $data['auction']['category_id']])->row_array();


        //items category dynamic fields
        $datafields = $this->oam->fields_data($data['auction']['category_id']);
        $fdata = array();
        foreach ($datafields as $key => $fields) {
            $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id, $fields['id']);
            if (!empty($item_dynamic_fields_info)) {
                $fields['values'] = json_decode($fields['values'], true);
                $fields['data-id'] = $fields['id'];
                if (!empty($fields['values'])) {
                    foreach ($fields['values'] as $key => $options) {
                        if ($options['value'] == $item_dynamic_fields_info['value']) {
                            $fields['data-value'] = $options['label'];
                        }
                    }
                } else {
                    $fields['data-value'] = $item_dynamic_fields_info['value'];
                }
                $fdata[] = $fields;
            }
        }
        $j = 11;
        $i = 0;
        foreach ($fdata as $key => $value) {
            if (!empty($value['data-value'])) {
                $i++;
                if ($i <= $j) {
                    $data['field']['label'][] = $this->make_dual($value['label'], $language);
                    $data['field']['value'][] = $this->make_dual($value['data-value'], $language);

                    if ($i == 1) {
                        if (($data['category']['include_make_model'] == 'yes')) {
                            if ($language == "english") {

                                $data['field']['label'][] = "Odometer";
                                $data['field']['label'][] = "Specification";

                                if ($specsType == 'GCC') {
                                    $specsType = 'GCC';
                                } else {
                                    $specsType = 'IMPORTED';
                                }

                                $data['field']['value'][] = $odometer;
                                $data['field']['value'][] = $specsType;
                            } else {
                                $data['field']['label'][] = 'عداد المركبة';
                                $data['field']['label'][] = "المواصفات";
                                if ($specsType == 'GCC') {
                                    $specsType = 'خليجية';
                                } else {
                                    $specsType = 'وارد';
                                }
                                $data['field']['value'][] = $odometer;
                                $data['field']['value'][] = $specsType;
                            }
                        }
                    }
                }
            }
        }

        // get contact us details
        $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();

        //highest bid data
        //$data['heighest_bid_data'] =  $this->oam->item_heighest_bid_data($item_id,$auction_id);

        //related items
        $item_ids = [];
        $related_items = $this->db->where('item_id !=', $item_id)->limit(10)->get_where('auction_items', ['auction_id' => $auction_id])->result_array();
        foreach ($related_items as $key => $v) {
            array_push($item_ids, $v['item_id']);
        }
        $data['related_items'] = $this->oam->get_online_auction_items($auction_id, 10, 0, $item_ids);

        //bid buttons from admin panel
        $data['auction_live_settings'] = $this->db->get('auction_live_settings')->row_array();

        //get total bid count of this item
        //$data['bid_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('bid')->count_all_results();

        //count total users view this item
        //$data['visit_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('online_auction_item_visits')->count_all_results();

        $data['user_id'] = '';
        $data['balance'] = 'N/A';
        $data['hide_auto_bid'] = 'N/A';
        $data['user'] = NULL;
        $data['fav_item'] = NULL;
        //live video link
        $lvl = $this->db->get('auction_live_settings')->row_array();
        $data['lvl'] = $lvl['live_video_link'];

        $user = validateToken2();
        if ($user) {
            $user_id = $user;
            $data['user'] = (array)$user;
            $data['user_id'] = $user_id;

            //check user is blocked or not
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            if ($user['status'] == 0) {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Your Account is Not Active', 'english' => $english_l['account_is_not_verified'], 'arabic' => $arabic_l['account_is_not_verified'], 'appVersion' => $version));
                exit;
            }

            //get user balance
            $user_total_deposit = $this->customer_model->user_balance($user_id);
            $data['balance'] = $user_total_deposit['amount'];

            $percentage_amount = $this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();
            $data['percentage_amount'] = $percentage_amount['value'];

            $data['max_bid_limit'] = (float)$data['percentage_amount'] * (float)$data['balance'];

            $visit_data = array(
                'user_id' => $user_id,
                'auction_id' => $auction_id,
                'item_id' => $item_id
            );

            //check user already visit this item or not
            $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
            if (!$visit_info) {
                $this->db->insert('online_auction_item_visits', $visit_data);
            }

        }

        if (isset($data['balance']) && $data['balance'] > 0):
            $nBalance = (float)$data['balance'] * 10;
        else:
            $nBalance = 5000;
        endif;

        $cur_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id,'item_id' => $item_id]);
        if ($cur_bid->num_rows() > 0) {
            $cur_bid = $cur_bid->row_array();
            $data['bid_place']['current_bid2'] = $cur_bid['bid_amount'];
        }else{
            $data['bid_place']['current_bid2'] = 0;
        }

        $data['bid_place']['data-max-bid'] = $nBalance;
        $crrnt = (isset($data['heighest_bid_data']) && !empty($data['heighest_bid_data']['current_bid'])) ? $data['heighest_bid_data']['current_bid'] : $data['item'][0]['bid_start_price'];
        $data['bid_place']['current_bid'] = $crrnt;
        $data['bid_place']['data-price'] = ((float)$crrnt + (float)$data['item'][0]['minimum_bid_price']);
        $data['bid_place']['balance'] = $data['balance'];

        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Success', 'appVersion' => $version, 'result' => $data));
        exit;

    }


    public function live_online_auction_view_2($auction_id,$item_id)
    {
       
        $version = $this->config->item('appVersion');
        $data_array = json_decode(file_get_contents("php://input"), true);
        $data2 = json_decode(file_get_contents("php://input"));
        $language = "english";

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);
        $get_last_bid = 0;

        if (isset($data_array['select_language']) && !empty($data_array['select_language'])) {
            $language = $data_array['select_language'];
        }

        if (empty($auction_id)) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'AUCTION ID NOT FOUND', 'english' => $english_l['auction_id_not_found'], 'arabic' => $arabic_l['auction_id_not_found'], 'appVersion' => $version));
            exit;
        }

        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $ret = strtr(rawurlencode($actual_link), $revert);
        $data['rurl'] = $ret;

        $data['new'] = 'new';
        $data['auction_id'] = $auction_id;
        $data['auction'] = $this->db->get_where('auctions', ['id' => $data['auction_id']])->row_array();

        $last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id]);

        if ($last_bid->num_rows() > 0) {
            $last_bid = $last_bid->row_array();
            //$item_id = $last_bid['item_id'];
            $data['item_id'] = $item_id;
            $data['last_bid'] = $last_bid;
        } else {

            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Live auction Available', 'english' => $english_l['auction_not_initialized'], 'arabic' => $arabic_l['auction_not_initialized'], 'appVersion' => $version));
            exit;
        }

        //auction item details

        $item_data = $this->oam->get_items_details($item_id, $auction_id);
        $data['item'] = $this->get_items_details($item_id, $auction_id);


        $data['category_id'] = $item_data['category_id'];
        $data['category'] = $this->db->get_where('item_category', ['id' => $data['category_id']])->row_array();

        $odometer = "";
        $odometer_image = "";

        $spec_mileage = "";
        $spec_image = "";
        $specsType = "";

        foreach ($data['item'] as $key => $val) {


            //specs data

            if (($data['category']['include_make_model'] == 'yes')) {

                $odometer = number_format($val['mileage']);
                $odometer_image = base_url('assets_user') . "/new/images/detail-icons/odometer-icons.svg";

            }

            if (($data['category']['include_make_model'] == 'yes') && isset($val['specification'])) {

                $spec_mileage = number_format($val['mileage']);
                $spec_image = base_url('assets_user') . "/new/images/detail-icons/specification-icons.svg";

                if ($val['specification'] == 'GCC') {
                    $specsType = 'GCC';
                } else {
                    $specsType = 'IMPORTED';
                }
            }

            if (empty($data['item'][$key]['additional_info'])) {
                $additional_info = [
                    'english' => '',
                    'arabic' => '',
                ];
                $data['item'][$key]['additional_info'] = json_encode($additional_info);
            }

            $item_make = $this->db->get_where('item_makes', ['id' => $val['item_make']])->row_array();
            $item_model = $this->db->get_where('item_models', ['id' => $val['item_model']])->row_array();

            $make_name = $item_make['title'];
            $model_name = $item_model['title'];
            $data['item'][$key]['item_make_title'] = $make_name;
            $data['item'][$key]['item_model_title'] = $model_name;
            $data['item'][$key]['map'] = "https://www.google.com/maps/@" . $data['item'][$key]['item_lat'] . ',' . $data['item'][$key]['item_lng'] . ',15z';


            $data['item'][$key]['report'] = base_url('inspection_report/') . $data['item'][$key]['id'];

            $f = 0;
            if (!empty($data['item'][$key]['item_test_report'])) {

                $inspection_report = array();
                $inspection_report_name = array();

                $doc_ids = explode(',', $data['item'][$key]['item_test_report']);
                while ($f < count($doc_ids)) {
                    $item_test_report = $this->db->where_in('id', $doc_ids[$f])->get('files')->row_array();

                    $inspection_report[$f] = base_url() . $item_test_report['path'] . $item_test_report['name'];
                    $inspection_report_name[$f] = $item_test_report['orignal_name'];

                    $data['item'][$key]['inspection_report'][] = array(
                        'url' => $inspection_report[$f],
                        'title' => $inspection_report_name[$f]
                    );
                    $f++;
                }


            } else {
                $data['item'][$key]['inspection_report'] = null;
            }

            if (!empty($val['item_images'])) {
                $item_images = explode(',', $val['item_images']);
                $i = 0;
                $image_get = array();
                while ($i < count($item_images)) {
                    $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                    $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                    $i++;
                }
                $data['item'][$key]['item_images'] = $image_get;
            }

            if (!empty($val['threed_images'])) {
//                    $item_images = explode(',', $val['threed_images']);
//                    $i = 0;
//                    $image_get = array();
//                    while ($i < count($item_images)) {
//                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
//                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
//                        $i++;
//                    }
                $data['item'][$key]['threed_images'] = true;
            } else {
                $data['item'][$key]['threed_images'] = false;
            }

            $data['bid_place']['min_bid'] = $data['item'][$key]['minimum_bid_price'];
            $data['bid_place']['data-auction-id'] = $data['item'][$key]['auction_id'];
            $data['bid_place']['data-item-id'] = $data['item'][$key]['item_id'];
            $data['bid_place']['data-seller-id'] = $data['item'][$key]['item_seller_id'];
            $data['bid_place']['data-lot-no'] = $data['item'][$key]['auction_item_id'];
            if($data['item'][$key]['bid_start_price'] != null){
                $data['bid_place']['data-start-price'] = $data['item'][$key]['bid_start_price'];
            }else{
                $data['item'][$key]['bid_start_price'] = 0;
                $data['bid_place']['data-start-price']  = 0;
            }

            $data['bid_place']['auction_item_id'] = $data['item'][$key]['auction_item_id'];
        }


        $data['item_category_fields'] = $this->db->get_where('item_category_fields', ['category_id' => $data['auction']['category_id']])->result_array();
        $data['category_id'] = $data['auction']['category_id'];
        $data['item_category'] = $this->db->get_where('item_category', ['id' => $data['auction']['category_id']])->row_array();


        //items category dynamic fields
        $datafields = $this->oam->fields_data($data['auction']['category_id']);
        $fdata = array();
        foreach ($datafields as $key => $fields) {
            $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id, $fields['id']);
            if (!empty($item_dynamic_fields_info)) {
                $fields['values'] = json_decode($fields['values'], true);
                $fields['data-id'] = $fields['id'];
                if (!empty($fields['values'])) {
                    foreach ($fields['values'] as $key => $options) {
                        if ($options['value'] == $item_dynamic_fields_info['value']) {
                            $fields['data-value'] = $options['label'];
                        }
                    }
                } else {
                    $fields['data-value'] = $item_dynamic_fields_info['value'];
                }
                $fdata[] = $fields;
            }
        }
        $j = 11;
        $i = 0;
        foreach ($fdata as $key => $value) {
            if (!empty($value['data-value'])) {
                $i++;
                if ($i <= $j) {
                    $data['field']['label'][] = $this->make_dual($value['label'], $language);
                    $data['field']['value'][] = $this->make_dual($value['data-value'], $language);

                    if ($i == 1) {
                        if (($data['category']['include_make_model'] == 'yes')) {
                            if ($language == "english") {

                                $data['field']['label'][] = "Odometer";
                                $data['field']['label'][] = "Specification";

                                if ($specsType == 'GCC') {
                                    $specsType = 'GCC';
                                } else {
                                    $specsType = 'IMPORTED';
                                }

                                $data['field']['value'][] = $odometer;
                                $data['field']['value'][] = $specsType;
                            } else {
                                $data['field']['label'][] = 'عداد المركبة';
                                $data['field']['label'][] = "المواصفات";
                                if ($specsType == 'GCC') {
                                    $specsType = 'خليجية';
                                } else {
                                    $specsType = 'وارد';
                                }
                                $data['field']['value'][] = $odometer;
                                $data['field']['value'][] = $specsType;
                            }
                        }
                    }
                }
            }
        }

        // get contact us details
        $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();

        //highest bid data
        //$data['heighest_bid_data'] =  $this->oam->item_heighest_bid_data($item_id,$auction_id);

        //related items
        $item_ids = [];
        $related_items = $this->db->where('item_id !=', $item_id)->limit(10)->get_where('auction_items', ['auction_id' => $auction_id])->result_array();
        foreach ($related_items as $key => $v) {
            array_push($item_ids, $v['item_id']);
        }
        $data['related_items'] = $this->oam->get_online_auction_items($auction_id, 10, 0, $item_ids);

        //bid buttons from admin panel
        $data['auction_live_settings'] = $this->db->get('auction_live_settings')->row_array();

        //get total bid count of this item
        //$data['bid_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('bid')->count_all_results();

        //count total users view this item
        //$data['visit_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('online_auction_item_visits')->count_all_results();

        $data['user_id'] = '';
        $data['balance'] = 'N/A';
        $data['hide_auto_bid'] = 'N/A';
        $data['user'] = NULL;
        $data['fav_item'] = NULL;
        //live video link
        $lvl = $this->db->get('auction_live_settings')->row_array();
        $data['lvl'] = $lvl['live_video_link'];

        $user = validateToken2();
        if ($user) {
            $user_id = $user;
            $data['user'] = (array)$user;
            $data['user_id'] = $user_id;

            //check user is blocked or not
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            if ($user['status'] == 0) {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Your Account is Not Active', 'english' => $english_l['account_is_not_verified'], 'arabic' => $arabic_l['account_is_not_verified'], 'appVersion' => $version));
                exit;
            }

            //get user balance
            $user_total_deposit = $this->customer_model->user_balance($user_id);
            $data['balance'] = $user_total_deposit['amount'];

            $percentage_amount = $this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();
            $data['percentage_amount'] = $percentage_amount['value'];

            $data['max_bid_limit'] = (float)$data['percentage_amount'] * (float)$data['balance'];

            $visit_data = array(
                'user_id' => $user_id,
                'auction_id' => $auction_id,
                'item_id' => $item_id
            );

            //check user already visit this item or not
            $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
            if (!$visit_info) {
                $this->db->insert('online_auction_item_visits', $visit_data);
            }


            //user is auto bidder or not
            //$data['hide_auto_bid'] = $this->db->get_where('bid_auto',['user_id' =>$user_id,'item_id' => $item_id, 'auto_status'=> 'start'])->row_array();

            // User favourite item
            //$data['fav_item'] = $this->db->get_where('favorites_items',['user_id'=> $user_id,'item_id' => $item_id])->row_array();
            // print_r($u);die();

        }

        if (isset($data['balance']) && $data['balance'] > 0):
            $nBalance = (float)$data['balance'] * 10;
        else:
            $nBalance = 5000;
        endif;

        $cur_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id,'item_id' => $item_id]);
        if ($cur_bid->num_rows() > 0) {
            $cur_bid = $cur_bid->row_array();
            $data['bid_place']['current_bid2'] = $cur_bid['bid_amount'];
        }else{
            $data['bid_place']['current_bid2'] = 0;
        }

        $data['bid_place']['data-max-bid'] = $nBalance;
        $crrnt = (isset($data['heighest_bid_data']) && !empty($data['heighest_bid_data']['current_bid'])) ? $data['heighest_bid_data']['current_bid'] : $data['item'][0]['bid_start_price'];
        $data['bid_place']['current_bid'] = $crrnt;
        $data['bid_place']['data-price'] = ((float)$crrnt + (float)$data['item'][0]['minimum_bid_price']);
        $data['bid_place']['balance'] = $data['balance'];

        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Success', 'appVersion' => $version, 'result' => $data));
        exit;

    }

    //live auction deatail pag end


    //search end


    //online auction detail page

    public function online_auction($id)
    {

        $version = $this->config->item('appVersion');

        if (empty($id)) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'AUCTION ID NOT Found', 'appVersion' => $version));

        } else {

            $this->output->enable_profiler(TRUE);
            $cat_id = $id;
            $data['new'] = 'new';
            $auctions_online = $this->home_model->get_online_auctions($cat_id);
            $id = $auctions_online['id'];
            $data['auction_id'] = $id;
            // $data['sold']= $this->db->get_where('auction_items', ['auction_id' => $id])->row_array();
            $data['online_auctions'] = $this->oam->get_online_auctions();
            //// for catagories for header and count ////

            ////// End for catagories for header and count ///////
            $data['selected_auction'] = $this->db->get_where('auctions', ['id' => $cat_id])->row_array();
            $data['selected_category'] = $this->db->get_where('item_category', ['id' => $cat_id])->row_array();
            $data['item_makes'] = $this->db->get_where('item_makes', ['status' => 'active'])->result_array();
            $data['item_category_fields'] = $this->db->get_where('item_category_fields', ['category_id' => $cat_id])->result_array();

            $data['category_id'] = $cat_id;

            $data['limit'] = $this->db->get_where('auction_items', ['auction_id' => $id])->result_array();

            $codee = http_response_code(200);
            echo json_encode(array("status" => TRUE, 'code' => $codee, "message" => 'ONLINE AUCTION ITEM', 'appVersion' => $version, 'result' => $data));

        }

    }


    public function get_items_details($item_id = '', $auction_id = '')
    {

        $select = 'ai.id as auction_item_id,
              ai.item_id as item_id,
              ai.allowed_bids as allowed_bids,
              ai.auction_id as auction_id,
              ai.security as security,
              ai.deposit as deposit,
              ai.bid_start_price as bid_start_price,
              ai.minimum_bid_price as minimum_bid_price,
              ai.bid_start_time as bid_start_time,
              ai.lot_no as lot_no,
              ai.bid_end_time as bid_end_time,
              ai.order_lot_no as order_lot_no,

              i.id as id,
              i.name as item_name,
              i.category_id as category_id,
              i.specification as specification,
              i.location as item_location,
              i.lat as item_lat,
              i.lng as item_lng,
              i.price as item_price,
              i.registration_no as item_registration_no,
              i.subcategory_id as subcategory_id,
              i.lot_id as item_lot_id,
              i.detail as item_detail,
              i.unique_code as item_unique_code,
              i.unique_code as item_unique_code,
              i.barcode as item_barcode,
              i.item_images as item_images,
              i.make as item_make,
              i.mileage_type as mileage_type,
              i.mileage as mileage,
              i.year as year,
              i.feature as item_feature,
              i.model as item_model,
              i.vin_number as item_vin_number,
              i.keyword as item_keyword,
              i.item_attachments as item_attachments,
              i.item_test_report as item_test_report,
              i.item_status as item_status,
              i.inspected as inspected,
              i.sold as item_sold,
              i.in_auction as item_in_auction,
              i.seller_id as item_seller_id,
              i.other_charges as item_other_charges,
              i.terms as terms,
              i.additional_info as additional_info,
              i.threed_images as threed_images
              ';

        $where = [
            'i.id' => $item_id
        ];

        $this->db->select($select);
        $this->db->from('auction_items ai');
        $this->db->join('item i', 'ai.item_id = i.id', 'LEFT');
        $this->db->where($where);
        if (!empty($auction_id)) {
            $this->db->where('ai.auction_id', $auction_id);
        }

        $item = $this->db->get();
        $item = $item->result_array();
        // echo $this->db->last_query();die();
        return $item;
    }


    function make_dual($str, $language)
    {
        $exp_str = explode('|', $str);
        if ($language == 'english') {
            return $exp_str[0];
        } else {
            $value = (isset($exp_str[1])) ? $exp_str[1] : $exp_str[0];
            return $value;
        }
    }


    public function item_detail($auction_id = '', $item_id = '')
    {


        $version = $this->config->item('appVersion');
        $data_array = json_decode(file_get_contents("php://input"), true);
        $data2 = json_decode(file_get_contents("php://input"));
        $language = "english";

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if (isset($data_array['select_language']) && !empty($data_array['select_language'])) {
            $language = $data_array['select_language'];
        }


        if (empty($auction_id) || empty($item_id)) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'AUCTION ID OR ITEM ID NOT Found', 'english' => $english_l['auction_id_or_item_id_not_found'], 'arabic' => $arabic_l['auction_id_or_item_id_not_found'], 'appVersion' => $version));

        } else {


            $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $ret = strtr(rawurlencode($actual_link), $revert);
            $data['auction_id'] = $auction_id;
            $data['item_id'] = $item_id;


            // Get online auctions
//            $data['online_auctions'] = $this->oam->get_online_auctions();
//            foreach ($data['online_auctions'] as $key => $val) {
//
//                if (!empty($val['cat_icon'])) {
//                    $item_images = $val['cat_icon'];
//                    $i = 0;
//                    $image = $this->db->get_where('files', ['id' => $item_images])->row_array();
//                    $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
//                    $i++;
//
//                    $data['online_auctions'][$key]['item_images'] = $image_get;
//
//                }
//            }
//
//
//            $data['all_categories'] = $this->getapimodel->get_catagories_home();
//            foreach ($data['all_categories'] as $key => $val) {
//
//                if (!empty($val['path'])) {
//                    $image_get = trim(base_url(), '/') . trim($val['path'], '.') . $val['image_name'];
//                    $data['all_categories'][$key]['image_url'] = $image_get;
//
//                }
//            }


            $item_data = $this->oam->get_items_details($item_id, $auction_id);
            $data['item'] = $this->get_items_details($item_id, $auction_id);

            $data['category_id'] = $item_data['category_id'];
            $data['category'] = $this->db->get_where('item_category', ['id' => $data['category_id']])->row_array();

            $odometer = "";
            $odometer_image = "";

            $spec_mileage = "";
            $spec_image = "";
            $specsType = "";


            foreach ($data['item'] as $key => $val) {


                //specs data

                if (($data['category']['include_make_model'] == 'yes')) {

                    $odometer = number_format($val['mileage']);
                    $odometer_image = base_url('assets_user') . "/new/images/detail-icons/odometer-icons.svg";

                }

                if (($data['category']['include_make_model'] == 'yes') && isset($val['specification'])) {

                    $spec_mileage = number_format($val['mileage']);
                    $spec_image = base_url('assets_user') . "/new/images/detail-icons/specification-icons.svg";

                    if ($val['specification'] == 'GCC') {
                        $specsType = 'GCC';
                    } else {
                        $specsType = 'IMPORTED';
                    }
                }

                if (empty($data['item'][$key]['additional_info'])) {
                    $additional_info = [
                        'english' => '',
                        'arabic' => '',
                    ];
                    $data['item'][$key]['additional_info'] = json_encode($additional_info);
                }


                $item_make = $this->db->get_where('item_makes', ['id' => $val['item_make']])->row_array();
                $item_model = $this->db->get_where('item_models', ['id' => $val['item_model']])->row_array();

                $make_name = $item_make['title'];
                $model_name = $item_model['title'];
                $data['item'][$key]['item_make_title'] = $make_name;
                $data['item'][$key]['item_model_title'] = $model_name;
                $data['item'][$key]['map'] = "https://www.google.com/maps/@" . $data['item'][$key]['item_lat'] . ',' . $data['item'][$key]['item_lng'] . ',15z';
                $phonee = $this->db->select('phone')->get('contact_us')->row_array();
                $data['item'][$key]['phone'] = $phonee['phone'];

                $data['item'][$key]['report'] = base_url('inspection_report/') . $data['item'][$key]['id'];

                $f = 0;
                if (!empty($data['item'][$key]['item_test_report'])) {

                    $inspection_report = array();
                    $inspection_report_name = array();

                    $doc_ids = explode(',', $data['item'][$key]['item_test_report']);
                    while ($f < count($doc_ids)) {
                        $item_test_report = $this->db->where_in('id', $doc_ids[$f])->get('files')->row_array();

                        $inspection_report[$f] = base_url() . $item_test_report['path'] . $item_test_report['name'];
                        $inspection_report_name[$f] = $item_test_report['orignal_name'];

                        $data['item'][$key]['inspection_report'][] = array(
                            'url' => $inspection_report[$f],
                            'title' => $inspection_report_name[$f]
                        );
                        $f++;
                    }


                } else {
                    $data['item'][$key]['inspection_report'] = null;
                }


                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {
                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
                    $data['item'][$key]['item_images'] = $image_get;
                }

                if (!empty($val['threed_images'])) {
//                    $item_images = explode(',', $val['threed_images']);
//                    $i = 0;
//                    $image_get = array();
//                    while ($i < count($item_images)) {
//                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
//                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
//                        $i++;
//                    }
                    $data['item'][$key]['threed_images'] = true;
                } else {
                    $data['item'][$key]['threed_images'] = false;
                }

                $data['bid_place']['min_bid'] = $data['item'][$key]['minimum_bid_price'];
                $data['bid_place']['data-auction-id'] = $data['item'][$key]['auction_id'];
                $data['bid_place']['data-item-id'] = $data['item'][$key]['item_id'];
                $data['bid_place']['data-seller-id'] = $data['item'][$key]['item_seller_id'];
                $data['bid_place']['data-lot-no'] = $data['item'][$key]['auction_item_id'];
                $data['bid_place']['data-start-price'] = $data['item'][$key]['bid_start_price'];
                $data['bid_place']['auction_item_id'] = $data['item'][$key]['auction_item_id'];
            }

            //items category dynamic fields
            $datafields = $this->oam->fields_data($item_data['category_id']);
            $fdata = array();
            foreach ($datafields as $key => $fields) {
                $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id, $fields['id']);
                if (!empty($item_dynamic_fields_info)) {
                    $fields['values'] = json_decode($fields['values'], true);
                    $fields['data-id'] = $fields['id'];
                    if (!empty($fields['values'])) {
                        foreach ($fields['values'] as $key => $options) {
                            if ($options['value'] == $item_dynamic_fields_info['value']) {
                                $fields['data-value'] = $options['label'];
                            }
                        }
                    } else {
                        $fields['data-value'] = $item_dynamic_fields_info['value'];
                    }
                    $fdata[] = $fields;
                }
            }
            $j = 11;
            $i = 0;
            foreach ($fdata as $key => $value) {
                if (!empty($value['data-value'])) {
                    $i++;
                    if ($i <= $j) {
                        $data['field']['label'][] = $this->make_dual($value['label'], $language);
                        $data['field']['value'][] = $this->make_dual($value['data-value'], $language);

                        if ($i == 1) {
                            if (($data['category']['include_make_model'] == 'yes')) {
                                if ($language == "english") {

                                    $data['field']['label'][] = "Odometer";
                                    $data['field']['label'][] = "Specification";

                                    if ($specsType == 'GCC') {
                                        $specsType = 'GCC';
                                    } else {
                                        $specsType = 'IMPORTED';
                                    }

                                    $data['field']['value'][] = $odometer;
                                    $data['field']['value'][] = $specsType;
                                } else {
                                    $data['field']['label'][] = 'عداد المركبة';
                                    $data['field']['label'][] = "المواصفات";
                                    if ($specsType == 'GCC') {
                                        $specsType = 'خليجية';
                                    } else {
                                        $specsType = 'وارد';
                                    }
                                    $data['field']['value'][] = $odometer;
                                    $data['field']['value'][] = $specsType;
                                }
                            }
                        }
                    }
                }
            }


            // get contact us details
            $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();

            //highest bid data
            $data['heighest_bid_data'] = $this->oam->item_heighest_bid_data($item_id, $auction_id);

            //related items
            $item_ids = [];
            $related_items = $this->db->where('item_id !=', $item_id)->get_where('auction_items', ['auction_id' => $auction_id])->result_array();
            if (!empty($related_items)) {
                foreach ($related_items as $key => $v) {
                    array_push($item_ids, $v['item_id']);
                }
                if (empty($item_ids)) {
                    array_push($item_ids, 0);
                }
                $data['related_items'] = $this->oam->get_online_auction_items($auction_id, 10, 0, $item_ids);


                foreach ($data['related_items'] as $key => $val) {

                    if (!empty($val['item_images'])) {
                        $item_images = explode(',', $val['item_images']);
                        $i = 0;
                        $image_get = array();
                        while ($i < count($item_images)) {

                            $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                            $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                            $i++;
                        }
                        $data['related_items'][$key]['item_images'] = $image_get;
                    }

                }

            } else {
                $data['related_items'] = array();
            }
            //get total bid count of this item
            $data['bid_count'] = $this->db->where('item_id', $item_id)->where('auction_id', $auction_id)->from('bid')->count_all_results();

            //count total users view this item
            $data['visit_count'] = $this->db->where('item_id', $item_id)->where('auction_id', $auction_id)->from('online_auction_item_visits')->count_all_results();

            $data['user_id'] = '';
            $data['balance'] = 'N/A';
            $data['hide_auto_bid'] = 'N/A';
            $data['user'] = NULL;
            $data['fav_item'] = NULL;


            $user = validateToken2();
            if (!empty($user)) {
                $user_id = $user;
                $data['user'] = (array)$user;
                $data['user_id'] = $user_id;

                //check user is blocked or not
                $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
                if ($user['status'] == 0) {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Your Account is not Activate', 'english' => $english_l['activate_your_account'], 'arabic' => $arabic_l['activate_your_account'], 'appVersion' => $version, 'result' => $data));
                    exit;
                }

                //get user balance
                $user_total_deposit = $this->customer_model->user_balance($user_id);
                $data['balance'] = $user_total_deposit['amount'];

                $visit_data = array(
                    'user_id' => $user_id,
                    'auction_id' => $auction_id,
                    'item_id' => $item_id
                );

                //check user already visit this item or not
                $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
                if (!$visit_info) {
                    $this->db->insert('online_auction_item_visits', $visit_data);
                }

                //user is auto bidder or not
                $data['hide_auto_bid'] = $this->db->get_where('bid_auto', ['user_id' => $user_id, 'item_id' => $item_id, 'auto_status' => 'start'])->row_array();

                // User favourite item
                $data['fav_item'] = $this->db->get_where('favorites_items', ['user_id' => $user_id, 'item_id' => $item_id, 'auction_id' => $auction_id])->row_array();
                // print_r($u);die();

                $data['bid_place']['user_have_bids'] = $this->db->get_where('bid', ['auction_id' => $auction_id, 'user_id' => $user_id, 'item_id' => $item_id])->num_rows();


            }


            if (isset($data['balance']) && $data['balance'] > 0):
                $nBalance = (float)$data['balance'] * 10;
            else:
                $nBalance = 5000;
            endif;
            $data['bid_place']['data-max-bid'] = $nBalance;
            $crrnt = (isset($data['heighest_bid_data']) && !empty($data['heighest_bid_data']['current_bid'])) ? $data['heighest_bid_data']['current_bid'] : $data['item'][0]['bid_start_price'];
            $data['bid_place']['current_bid'] = $crrnt;
            $data['bid_place']['data-price'] = ((float)$crrnt + (float)$data['item'][0]['minimum_bid_price']);
            $data['bid_place']['balance'] = $data['balance'];


            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'ONLINE AUCTION ITEMS', 'appVersion' => $version, 'result' => $data));


        }
    }

    public function placebid()
    {


        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);
        $id = validateToken();
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {
            if ($data) {
                $auto_bid_msg = '';

                $query = $this->db->query('Select bid.bid_amount, bid.bid_status ,bid.user_id ,item.category_id,item.name,item.year,item.registration_no, users.username, users.email, users.fname, users.fcm_token  from bid 
                  inner join  ( Select max(bid_time) as LatestDate, item_id  from bid  Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id
                     LEFT JOIN item ON item.id = bid.item_id LEFT JOIN users ON users.id = bid.user_id  WHERE bid.item_id = ' . $data['item_id'] . ' AND bid.auction_id = ' . $data['auction_id'] . ';');
                $bid_data = $query->row_array();
                if (isset($bid_data) && $bid_data['bid_amount'] > $data['current_price']) {
                    // $sold_out_item_msg = 'item is sold out';
                    $sold_out_item_msg = $this->lang->line('bid_not_placed_try_again');

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, 'msg' => $sold_out_item_msg . " bidAmountChanged", 'english' => $english_l['bid_not_placed_try_again'], 'arabic' => $arabic_l['bid_not_placed_try_again'], 'appVersion' => $version));
                    exit();
                }
                if (!empty($bid_data['bid_status']) && $bid_data['bid_status'] == 'won') {
                    // $sold_out_item_msg = 'item is sold out';
                    $sold_out_item_msg = $this->lang->line('sold_out_new');
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, 'msg' => $sold_out_item_msg . " soldout", 'english' => $english_l['sold_out_new'], 'arabic' => $arabic_l['sold_out_new'], 'appVersion' => $version));
                    exit();

                }
                $auction_item_data = $this->db->get_where('auction_items', ['item_id' => $data['item_id'], 'auction_id' => $data['auction_id']]);
                if ($auction_item_data->num_rows() > 0) {
                    $auction_item_data = $auction_item_data->row_array();
                    $today = date('Y-m-d H:i:s');
                    $date = strtotime($today);
                    $bid_end_time = strtotime($auction_item_data['bid_end_time']);
                    if ($bid_end_time < $date) {


                        $codee = http_response_code(200);
                        echo json_encode(array("status" => false, 'code' => $codee, 'msg' => $this->lang->line('item_time_expired') . " soldout", 'english' => $english_l['item_time_expired'], 'arabic' => $arabic_l['item_time_expired'], 'appVersion' => $version));
                        exit();

                    }
                    if ($auction_item_data['sold_status'] != 'not') {

                        $codee = http_response_code(200);
                        echo json_encode(array("status" => false, 'code' => $codee, 'msg' => "Item is soldout", 'english' => $english_l['item_sold_out'], 'arabic' => $arabic_l['item_sold_out'], 'appVersion' => $version));
                        exit();

                    }
                }
                $user = $result3[0];


                if ($user) {
                    $heighest_bids = $this->oam->used_bid_limit($user['id'], $data['item_id']);
                    $currentBalance = $this->customer_model->user_balance($user['id']);
                    $new_bid_amount = $bid_data['bid_amount'] + $data['bid_amount'];
                    $new_bid_limit = $new_bid_amount;
                    $totalBalanceRequiredToPlaceBid = (int)($new_bid_limit) + (!empty($heighest_bids['total_bid']) ? (int)$heighest_bids['total_bid'] : 0);
                    $currentBidAmount = $currentBalance['amount'] * 10;
                    // print_r($heighest_bids);
                    // echo '<br>';
                    // print_r($totalBalanceRequiredToPlaceBid);
                    // print_r($currentBidAmount);
                    // die();
                    // echo '<br>';
                    // print_r($currentBalance['amount']*10);die();
                    if ($totalBalanceRequiredToPlaceBid > $currentBidAmount) {
                        $refundableBalance = ((int)$currentBidAmount - (int)$heighest_bids['total_bid']);
                        if ($bid_data['user_id'] == $user['id']) {
                            $refundableBalance = $refundableBalance - $data['current_price'];
                        }
                        $refundableBalance = ($refundableBalance > 0) ? $refundableBalance : 0;
                        // print_r($refundableBalance);die();
                        // $this->session->set_flashdata('error', $this->lang->line('you_have_reached_your_bid_limit').$refundableBalance);


                        $codee = http_response_code(200);
                        echo json_encode(array("status" => false, 'code' => $codee, 'msg' => $this->lang->line('you_have_reached_your_bid_limit') . $refundableBalance . " limitExceed", 'english' => $english_l['you_have_reached_your_bid_limit'] . $refundableBalance, 'arabic' => $arabic_l['you_have_reached_your_bid_limit'] . $refundableBalance, 'appVersion' => $version));
                        exit();

                    } else {
                        $this->load->model('email/Email_model', 'email_model');
                        $bid_amount = $this->db->order_by('id', 'desc')->get_where('bid', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id']])->row_array();
                        if (isset($data['bid_limit']) && $bid_amount['user_id'] == $user['id']) {
                            $result = false;
                        } else {
                            if (!empty($bid_amount)) {
                                $bid_data_to_insert['bid_amount'] = $bid_amount['bid_amount'] + $data['bid_amount'];
                            } else {
                                $a_item = $this->db->get_where('auction_items', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id']])->row_array();
                                $bid_data_to_insert['bid_amount'] = $a_item['bid_start_price'] + $data['bid_amount'];
                            }
                            $bid_data_to_insert['start_price'] = $data['start_price'];
                            $bid_data_to_insert['end_price'] = $bid_data_to_insert['bid_amount'];
                            $bid_data_to_insert['date'] = date('Y-m-d');
                            $bid_data_to_insert['user_id'] = $user['id'];
                            $bid_data_to_insert['item_id'] = $data['item_id'];
                            $bid_data_to_insert['auction_id'] = $data['auction_id'];
                            $bid_data_to_insert['buyer_id'] = $user['id'];
                            $bid_data_to_insert['seller_id'] = $data['seller_id'];
                            $bid_data_to_insert['bid_status'] = 'pending';

                            // print_r($data);die();
                            $result = $this->db->insert('bid', $bid_data_to_insert);
                        }

                        if (isset($data['bid_limit'])) {
                            $auto_bid = array();
                            $auto_bid['item_id'] = $data['item_id'];
                            $auto_bid['auction_item_id'] = $data['auction_item_id'];
                            $auto_bid['auction_id'] = $data['auction_id'];
                            $user = $result3[0];
                            $auto_bid['user_id'] = $user['id'];
                            $auto_bid['bid_limit'] = $data['bid_limit'];
                            $auto_bid['bid_increment'] = $data['bid_amount'];
                            $auto_bid['auto_status'] = 'start';
                            $auto_bid['date'] = date('Y-m-d H:i:s');

                            if ($auto_bid) {
                                $users = $this->db->insert('bid_auto', $auto_bid);
                                $auto_bid_msg = 'true';
                            }
                        }

                        if ($result) {
                            $options = array(
                                'cluster' => 'ap1',
                                'useTLS' => true
                            );
                            $pusher = new Pusher\Pusher(
                                $this->config->item('pusher_app_key'),
                                $this->config->item('pusher_app_secret'),
                                $this->config->item('pusher_app_id'),
                                $options
                            );
                            $username = $user['username'];
                            $push['item_id'] = $data['item_id'];
                            $push['user_id'] = $user['id'];
                            $push['buyer_id'] = $user['id'];
                            $push['username'] = $username;
                            $push['bid_amount'] = $bid_data_to_insert['bid_amount'];
                            $pusher->trigger('ci_pusher', 'my-event', $push);
                            if (!empty($bid_amount) && $bid_amount['user_id'] != $user['id']) {
                                if (!empty($bid_data)) {
                                    //Send eamil
                                    $auto_bidder = $this->db->get_where('bid_auto', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id'], 'user_id' => $bid_data['user_id']])->row_array();
                                    if (!$auto_bidder) {
                                        $item_link = base_url('auction/online-auction/details/') . $data['auction_id'] . '/' . $data['item_id'];
                                        $vars = array(
                                            '{username}' => $bid_data['fname'],
                                            '{item_name}' => '<a href="' . $item_link . '">' . ucwords(json_decode($bid_data['name'])->english) . '</a>',
                                            '{year}' => $bid_data['year'],
                                            '{bid_price}' => $bid_amount['bid_amount'],
                                            '{lot_number}' => $auction_item_data['order_lot_no'],
                                            '{registration_number}' => $bid_data['registration_no'],
                                            '{login_link}' => 'Please login:<a href="' . base_url('user-login') . '" > Go to My Account </a>'
                                        );
                                        $email = $bid_data['email'];
                                        $check_email = $this->email_model->email_template($email, 'out-bid-email', $vars, false);

                                        $p_noti = ['item_detail'=> $auction_item_data['order_lot_no'].' - '. ucwords(json_decode($bid_data['name'])->english), 'auctionId' => $data['auction_id'], 'itemId' => $data['item_id']];
                                      $this->getOutBidPushNotification($bid_data['fcm_token'], $p_noti);
                                       //var_dump($bid_data);

                                      //$this->sendPushNotificationTest($bid_data['fcm_token']);
                                    }
                                }
                            }

                            //goto loop
                            auto_bid_loop:
                            $j = 0;
                            $auto_bidders = $this->db->get_where('bid_auto', ['item_id' => $data['item_id'], 'auction_id' => $data['auction_id'], 'auto_status' => 'start'])->result_array();
                            // print_r($auto_bidders);die();
                            if (!empty($auto_bidders)) {
                                foreach ($auto_bidders as $key => $value) {

                                    $bid_price = $bid_data_to_insert['bid_amount'] + $value['bid_increment'];
                                    if ($value['bid_limit'] >= $bid_price) {
                                        $bid_data_to_insert['user_id'] = $value['user_id'];
                                        $bid_data_to_insert['buyer_id'] = $value['user_id'];
                                        $bid_amount = $this->db->order_by('id', 'desc')->get_where('bid', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id']])->row_array();
                                        if ($value['user_id'] != $bid_amount['user_id']) {
                                            if (!empty($bid_amount)) {
                                                $bid_data_to_insert['bid_amount'] = $bid_amount['bid_amount'] + $value['bid_increment'];
                                            } else {
                                                $a_item = $this->db->get_where('auction_items', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id']])->row_array();
                                                $bid_data_to_insert['bid_amount'] = $a_item['bid_start_price'] + $value['bid_increment'];
                                            }
                                            $j++;
                                            $bid_data_to_insert['end_price'] = $data['bid_amount'];
                                            $bid_data_to_insert['date'] = date('Y-m-d');
                                            $bid_data_to_insert['bid_status'] = 'pending';
                                            $result = $this->db->insert('bid', $bid_data_to_insert);
                                            if ($result) {
                                                $options = array(
                                                    'cluster' => 'ap1',
                                                    'useTLS' => true
                                                );
                                                $pusher = new Pusher\Pusher(
                                                    $this->config->item('pusher_app_key'),
                                                    $this->config->item('pusher_app_secret'),
                                                    $this->config->item('pusher_app_id'),
                                                    $options
                                                );
                                                $userdata = $this->db->select('username')->get_where('users', ['id' => $value['user_id']])->row_array();
                                                $push['item_id'] = $data['item_id'];
                                                $push['username'] = $userdata['username'];
                                                $push['user_id'] = $bid_data_to_insert['user_id'];
                                                $push['buyer_id'] = $user['id'];
                                                $push['bid_amount'] = $bid_data_to_insert['bid_amount'];
                                                $pusher->trigger('ci_pusher', 'my-event', $push);
                                            }
                                            $auto_bidder = $this->db->get_where('bid_auto', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id'], 'user_id' => $bid_amount['user_id']])->row_array();
                                            if (!$auto_bidder) {
                                                //Send eamil
                                                $item_link = base_url('auction/online-auction/details/') . $data['auction_id'] . '/' . $data['item_id'];
                                                $out_user = $this->db->select('email,fname, fcm_token')->get_where('users', ['id' => $bid_amount['user_id']])->row_array();
                                                $vars = array(
                                                    '{username}' => $out_user['fname'],
                                                    '{item_name}' => '<a href="' . $item_link . '">' . ucwords(json_decode($bid_data['name'])->english) . '</a>',
                                                    '{year}' => $bid_data['year'],
                                                    '{bid_price}' => $bid_amount['bid_amount'],
                                                    '{lot_number}' => $auction_item_data['order_lot_no'],
                                                    '{registration_number}' => $bid_data['registration_no'],
                                                    '{login_link}' => 'Please login:<a href="' . base_url('user-login') . '" > Go to My Account </a>'
                                                );
                                                $email = $out_user['email'];
                                                $this->email_model->email_template($email, 'out-bid-email', $vars, false);

                                                 $p_noti = ['item_detail'=> $auction_item_data['order_lot_no'].' - '. ucwords(json_decode($bid_data['name'])->english), 'auctionId' => $data['auction_id'], 'itemId' => $data['item_id']];
                                   //   $this->getOutBidPushNotification($out_user['fcm_token'], $p_noti);
                                            }
                                        }
                                    } else {
                                        $update = $this->db->update('bid_auto', ['auto_status' => 'stop'], ['id' => $value['id']]);

                                        //email to auto bidder of out bid
                                        if ($update) {
                                            //Send eamil
                                            $item_link = base_url('auction/online-auction/details/') . $data['auction_id'] . '/' . $data['item_id'];
                                            $out_user = $this->db->select('email,fname, fcm_token')->get_where('users', ['id' => $value['user_id']])->row_array();
                                            $bid_data_name = json_decode($bid_data['name']);
                                            $lan = $this->language;
                                            $vars = array(
                                                '{username}' => $out_user['fname'],
                                                '{item_name}' => '<a href="' . $item_link . '">' . ucwords($bid_data_name->english) . '</a>',
                                                '{year}' => $bid_data['year'],
                                                '{bid_price}' => $bid_amount['bid_amount'],
                                                '{lot_number}' => $auction_item_data['order_lot_no'],
                                                '{registration_number}' => $bid_data['registration_no'],
                                                '{login_link}' => 'Please login:<a href="' . base_url('user-login') . '" > Go to My Account </a>'
                                            );
                                            $email = $out_user['email'];

                                            $email = $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                            $p_noti = ['item_detail'=> $auction_item_data['order_lot_no'].' - '. ucwords(json_decode($bid_data['name'])->english), 'auctionId' => $data['auction_id'], 'itemId' => $data['item_id']];
                                      $this->getOutBidPushNotification($out_user['fcm_token'], $p_noti);
                                        }

                                        //pusher
                                        $options = array(
                                            'cluster' => 'ap1',
                                            'useTLS' => true
                                        );
                                        $pusher = new Pusher\Pusher(
                                            $this->config->item('pusher_app_key'),
                                            $this->config->item('pusher_app_secret'),
                                            $this->config->item('pusher_app_id'),
                                            // 'ce5fe3c21179ff69c27a',
                                            // '9144aece744d52060fc7',
                                            // '1001119',
                                            $options
                                        );
                                        $push['item_id'] = $data['item_id'];
                                        $push['user_id'] = $value['user_id'];
                                        $push['status'] = 'stop';
                                        $pusher->trigger('ci_pusher', 'my-event', $push);
                                    }
                                }

                            }
                            if ($j >= 1) {
                                goto auto_bid_loop;
                            }

                            $output = json_encode([
                                'status' => 'success',
                                'msg' => $this->lang->line('bid_successfully'),
                                'english' => $english_l['bid_successfully'],
                                'arabic' => $arabic_l['bid_successfully'],
                                'auto_bid_msg' => $auto_bid_msg,
                                'current_bid' => $bid_data_to_insert['bid_amount']
                            ]);
                        } elseif ($users) {
                            $output = json_encode([
                                'status' => 'success',
                                'english' => $english_l['bid_successfully'],
                                'arabic' => $arabic_l['bid_successfully'],
                                'auto_bid_msg' => $auto_bid_msg
                            ]);
                        } else {
                            $output = json_encode([
                                'status' => 'fail',
                                'msg' => $this->lang->line('bid_failed'),
                                'english' => $english_l['bid_failed'],
                                'arabic' => $arabic_l['bid_failed']
                            ]);
                        }
                    }
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Bid Output', 'english' => $english_l['bid_output'], 'arabic' => $arabic_l['bid_output'], 'appVersion' => $version, 'result' => $output));

                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Bid Output', 'english' => $english_l['bid_output'], 'arabic' => $arabic_l['bid_output'], 'appVersion' => $version));

                }
            }

        } else {

            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User or Token Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));


        }
    }

    public function place_bid_live()
    {


        $language = "english";
        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {

            if ($data) {
                $check_auction = $this->db->get_where('auctions', ['id' => $data['auction_id']])->row_array();
                if ($check_auction['start_status'] == 'stop' || $check_auction['expiry_time'] < date('Y-m-d H:i:s')) {

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => 'stop_by_admin', 'code' => $codee, 'msg' => 'Bidding is stoped by admin', 'english' => $english_l['bidding_stop_by_admin'], 'arabic' => $arabic_l['bidding_stop_by_admin'], 'appVersion' => $version, 'result' => false));
                    exit();

                }


                //return print_r($data);

                //check user is logged in or not
                $user = $id;
                if ($user) {

                    //last bid data
                    $last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id']])->row_array();
                    //return print_r($last_bid);
                    $heighest_bids = $this->oam->used_bid_limit($user, $data['item_id']);
                    $currentBalance = $this->customer_model->user_balance($user);
                    $new_bid_amount = $last_bid['bid_amount'] + $data['bid_amount'];
                    $new_bid_limit = $new_bid_amount;
                    $totalBalanceRequiredToPlaceBid = (int)($new_bid_limit) + (!empty($heighest_bids['total_bid']) ? (int)$heighest_bids['total_bid'] : 0);
                    $currentBidAmount = $currentBalance['amount'] * 10;
                    // print_r($heighest_bids);
                    // echo '<br>';
                    // print_r($totalBalanceRequiredToPlaceBid);
                    // print_r($currentBidAmount);
                    // die();
                    // echo '<br>';
                    // print_r($currentBalance['amount']*10);die();
                    if ($totalBalanceRequiredToPlaceBid > $currentBidAmount) {
                        $refundableBalance = ((int)$currentBidAmount - (int)$heighest_bids['total_bid']);
                        if ($last_bid['user_id'] == $user) {
                            $refundableBalance = $refundableBalance - $last_bid['bid_amount'];
                        }
                        $refundableBalance = ($refundableBalance > 0) ? $refundableBalance : 0;
                        // print_r($refundableBalance);die();
                        // $this->session->set_flashdata('error', $this->lang->line('you_have_reached_your_bid_limit').$refundableBalance);


                        $codee = http_response_code(200);
                        echo json_encode(array("status" => 'limitExceed', 'code' => $codee, 'msg' => $this->lang->line('you_have_reached_your_bid_limit') . $refundableBalance, 'english' => $english_l['you_have_reached_your_bid_limit'] . $refundableBalance, 'arabic' => $arabic_l['you_have_reached_your_bid_limit'] . $refundableBalance, 'appVersion' => $version, 'result' => false));
                        exit();

                    } else {

                        //if controller not added initial price yet
                        if (empty($last_bid)) {

                            $codee = http_response_code(200);
                            echo json_encode(array("status" => 'not_initialized', 'code' => $codee, 'msg' => $this->lang->line('auction_not_initialized'), 'english' => $english_l['auction_not_initialized'], 'arabic' => $arabic_l['auction_not_initialized'], 'appVersion' => $version, 'result' => false));
                            exit();
                        }

                        //if item is already sold out
                        if (in_array($last_bid['bid_status'], ['win', 'not_sold'])) {
                            $codee = http_response_code(200);
                            echo json_encode(array("status" => 'soldout', 'code' => $codee, 'msg' => $this->lang->line('item_sold_out'), 'english' => $english_l['item_sold_out'], 'arabic' => $arabic_l['item_sold_out'], 'appVersion' => $version, 'result' => false));
                            exit();
                        }

                        //calculate bid total amount and increment amount
                        $bid_total_amount = (float)$last_bid['bid_amount'] + (float)$data['bid_amount'];
                        //return print_r($bid_total_amount);

                        if ($bid_total_amount > $data['max_bid_limit']) {

                            $codee = http_response_code(200);
                            echo json_encode(array("status" => 'limitCross', 'code' => $codee, 'msg' => $this->lang->line('insufficient_balance'), 'english' => $english_l['insufficient_balance'], 'arabic' => $arabic_l['insufficient_balance'], 'appVersion' => $version, 'result' => false));
                            exit();
                        }

                        //get item data
                        $auction_item = $this->lam->get_live_auction_items($data['auction_id'], '', '', [$data['item_id']]);
                        if ($auction_item) {
                            $auction_item = $auction_item[0];

                            //create bid data
                            $bid = [
                                'auction_id' => $data['auction_id'],
                                'item_id' => $data['item_id'],
                                'lot_no' => $auction_item['order_lot_no'],
                                'bid_type' => 'online',
                                'initial_priority_type' => 'no',
                                'user_id' => $user,
                                'bid_amount' => $bid_total_amount,
                                'bid_status' => 'bid',
                                'bid_increment_amount' => $data['bid_amount'],
                                'seller_id' => $auction_item['item_seller_id'],
                                'created_on' => date('Y-m-d H:i:s')
                            ];
                            $result = $this->db->insert('live_auction_bid_log', $bid);
                        } else {
                            $result = [];
                        }

                        if ($result) {

                            // $pusher_data = $this->broadcast_pusher($bid['item_id'], $bid['auction_id']);
                            //return print_r($pusher_data);
                            $pusher_data['item_id'] = $bid['item_id'];
                            $pusher_data['auction_id'] = $bid['auction_id'];

                            //broadcast pusher data
                            $options = array(
                                'cluster' => 'ap1',
                                'useTLS' => true
                            );
                            $pusher = new Pusher\Pusher(
                                $this->config->item('pusher_app_key'),
                                $this->config->item('pusher_app_secret'),
                                $this->config->item('pusher_app_id'),
                                $options
                            );
                            $pusher->trigger('ci_pusher', 'live-event', $pusher_data);

                            //make auto_status to stop in bid_auto table for all those entries
                            //which has less limit than current bid amount.
                            $this->db->update('bid_auto', ['auto_status' => 'stop'],
                                ['auction_id' => $data['auction_id'],
                                    'item_id' => $data['item_id'],
                                    'bid_limit <' => $bid_total_amount
                                ]
                            );

                            //auto bidders loop
                            do {
                                //get all auto bidders
                                $auto_bidders = $this->db->get_where('bid_auto', ['item_id' => $data['item_id'], 'auction_id' => $data['auction_id'], 'auto_status' => 'start']);
                                //get count of all auto bidders
                                $count = $auto_bidders->num_rows();
                                if ($count > 0) {
                                    $auto_bidders = $auto_bidders->result_array();
                                    //loop on all auto bidders
                                    foreach ($auto_bidders as $key => $value) {
                                        //get latest bid data
                                        $bid_amount = $this->db->order_by('id', 'desc')->get_where('live_auction_bid_log', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id']])->row_array();
                                        $bid_price = $bid_amount['bid_amount'] + $value['bid_increment'];
                                        //if current auto bidder limit is greater or equal than current bid price
                                        if ($value['bid_limit'] >= $bid_price) {
                                            //if last bid user not equal to current bid user
                                            if ($value['user_id'] != $bid_amount['user_id']) {

                                                $auto_bid = [
                                                    'auction_id' => $data['auction_id'],
                                                    'item_id' => $data['item_id'],
                                                    'lot_no' => $bid['lot_no'],
                                                    'bid_type' => 'online',
                                                    'initial_priority_type' => 'no',
                                                    'user_id' => $user,
                                                    'bid_amount' => $bid_price,
                                                    'bid_status' => 'bid',
                                                    'bid_increment_amount' => $value['bid_increment'],
                                                    'seller_id' => $bid['seller_id'],
                                                    'created_on' => date('Y-m-d H:i:s')
                                                ];

                                                $res = $this->db->insert('live_auction_bid_log', $auto_bid);
                                                if ($res) {
                                                    $options = array(
                                                        'cluster' => 'ap1',
                                                        'useTLS' => true
                                                    );
                                                    $pusher = new Pusher\Pusher(
                                                        $this->config->item('pusher_app_key'),
                                                        $this->config->item('pusher_app_secret'),
                                                        $this->config->item('pusher_app_id'),
                                                        $options
                                                    );

                                                    // $pusher_data = $this->broadcast_pusher($auto_bid['item_id'], $auto_bid['auction_id']);

                                                    $pusher_data['item_id'] = $auto_bid['item_id'];
                                                    $pusher_data['auction_id'] = $auto_bid['auction_id'];
                                                    $userdata = $this->db->select('username')->get_where('users', ['id' => $value['user_id']])->row_array();
                                                    $pusher_data['username'] = $userdata['username'];
                                                    $pusher_data['user_id'] = $bid['user_id'];
                                                    $pusher_data['buyer_id'] = $user;
                                                    $pusher->trigger('ci_pusher', 'live-event', $pusher_data);
                                                }


                                                /*$auto_bidder = $this->db->get_where('bid_auto', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id'],'user_id' =>$bid_amount['user_id']])->row_array();
                                                if (!$auto_bidder) {
                                                    //Send eamil
                                                    $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                                    $out_user = $this->db->select('email,fname')->get_where('users', ['id' => $bid_amount['user_id']])->row_array();
                                                    $vars = array(
                                                        '{username}' => $out_user['fname'],
                                                        '{item_name}' => '<a href="'.$item_link.'">'.ucwords($bid_data['name']).'</a>',
                                                        '{year}' => $bid_data['year'],
                                                        '{bid_price}' => $bid_amount['bid_amount'],
                                                        '{lot_number}' => $auction_item_data['order_lot_no'],
                                                        '{registration_number}' => $bid_data['registration_no'],
                                                        '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                                    );
                                                    $email = $out_user['email'];
                                                    $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                                }*/
                                            }
                                        } else {
                                            //update status of auto bidder to stop if bid cross the bidder limit.
                                            $update = $this->db->update('bid_auto', ['auto_status' => 'stop'], ['id' => $value['id']]);

                                            //email to auto bidder of out bid
                                            /*if ($update) {
                                                //Send eamil
                                                $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                                $out_user = $this->db->select('email,fname')->get_where('users', ['id' => $value['user_id']])->row_array();
                                                $vars = array(
                                                    '{username}' => $out_user['fname'],
                                                    '{item_name}' => '<a href="'.$item_link.'">'.ucwords($bid_data['name']).'</a>',
                                                    '{year}' => $bid_data['year'],
                                                    '{bid_price}' => $bid_amount['bid_amount'],
                                                    '{lot_number}' => $auction_item_data['order_lot_no'],
                                                    '{registration_number}' => $bid_data['registration_no'],
                                                    '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                                );
                                                $email = $out_user['email'];

                                                $email = $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                            }*/

                                            //pusher
                                            $options = array(
                                                'cluster' => 'ap1',
                                                'useTLS' => true
                                            );
                                            $pusher = new Pusher\Pusher(
                                                $this->config->item('pusher_app_key'),
                                                $this->config->item('pusher_app_secret'),
                                                $this->config->item('pusher_app_id'),
                                                $options
                                            );
                                            $pusher_data['auction_id'] = $data['auction_id'];
                                            $push['item_id'] = $data['item_id'];
                                            $push['user_id'] = $value['user_id'];
                                            $push['status'] = 'stop';
                                            $pusher->trigger('ci_pusher', 'live-event', $push);
                                        }
                                    }
                                }
                            } while ($count > 1);

                            $bid_success = $this->lang->line('bid_success');
                            $codee = http_response_code(200);
                            echo json_encode(array("status" => 'success', 'english' => $english_l['bid_success'], 'arabic' => $arabic_l['bid_success'], 'code' => $codee, 'msg' => $bid_success, 'appVersion' => $version, 'result' => true));
                            exit();

                        } else {

                            $codee = http_response_code(200);
                            echo json_encode(array("status" => 'info', 'code' => $codee, 'msg' => $this->lang->line('bid_failed'), 'english' => $english_l['bid_failed'], 'arabic' => $arabic_l['bid_failed'], 'appVersion' => $version, 'result' => false));
                            exit();

                        }
                    }
                } else {
                    //user not logged in
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, 'msg' => $this->lang->line('you_need_login'), 'english' => $english_l['you_need_login'], 'arabic' => $arabic_l['you_need_login'], 'appVersion' => $version, 'result' => false));
                    exit();
                }
            } else {
                //data not received from user end
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, 'msg' => $this->lang->line('some_information_missed'), 'english' => $english_l['some_information_missed'], 'arabic' => $arabic_l['some_information_missed'], 'appVersion' => $version, 'result' => false));
                exit();
            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, 'msg' => 'Token/User Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version,'result' => false));
            exit();
        }
    }

    public function broadcast_pusher($item_id, $auction_id)
    {

        $language = "english";
        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {


            $item = $this->lam->get_live_auction_items($auction_id, '', '', [$item_id]);
            $item = $item[0];

            $item['vehicle'] = false;
            if (isset($item['item_make_model']) && $item['item_make_model'] == 'yes') {
                $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array();
                $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array();
                $item['vehicle'] = true;
                $item['make'] = $make;
                $item['model'] = $model;
            }

            //item images
            $images = explode(',', $item['item_images']);
            $item_images = $this->files_model->get_multiple_files_by_ids($images);

            //current bid data
            $currentBidAmount = 0;
            $current_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id, 'item_id' => $item_id])->row_array();
            if (!empty($current_bid)) {
                $currentBidAmount = $current_bid['bid_amount'];
            }

            // $item_category_fields = $this->db->get_where('item_category_fields',['category_id' => $item['category_id'],'name' => 'transmission'])->result_array();

            $select = "sort_catagories.sort_id AS sortId, item_category_fields.label AS fieldName, item_fields_data.value AS fieldValue";
            $sortedCateFields = $this->db->select($select)->from('sort_catagories')
                ->join('item_category_fields', 'sort_catagories.field_id = item_category_fields.id')
                ->join('item_fields_data', 'item_category_fields.id = item_fields_data.fields_id')
                ->where(['sort_catagories.category_id' => $item['category_id'], 'item_fields_data.item_id' => $item_id])
                ->order_by('sort_catagories.sort_id', 'ASC')
                ->get()->result_array();

            $item_fields_data = array();
            if ($sortedCateFields) {
                foreach ($sortedCateFields as $key => $field) {
                    $fieldNames = explode("|", $field['fieldName']);
                    $item_fields_data[$key]['fieldName'] = ($fieldNames[0]) ? $fieldNames[0] : '';
                    $fieldValues = explode("|", $field['fieldValue']);
                    $item_fields_data[$key]['fieldValue'] = ($fieldValues[0]) ? $fieldValues[0] : '';
                }
            }


            //auction data
            $auction = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();
            $auction_blink_text = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();

            $push['item_id'] = $item_id;
            $push['auction_id'] = $auction_id;
            $push['auction'] = $auction;
            $push['cb_amount'] = @$currentBidAmount;
            $push['current_bid'] = $current_bid;
            $push['data'][] = $item;
            $push['item_fields_data'] = $item_fields_data;
            $push['item_images'] = $item_images;

            foreach ($push['data'] as $key => $val) {

                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {

                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
                    $push['data'][$key]['item_images'] = $image_get;
                }

            }

            $push['lot_number'] = $item['order_lot_no'];
            $push['blink_text'] = $auction_blink_text['blink_text'];
            $push['item_model'] = isset($model['title']) ? json_decode($model['title'])->english : '';

            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, 'message' => 'Success', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $push));
            exit();

            // return $push;
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, 'message' => 'Token/User Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version, 'result' => 'false'));
            exit();
        }
    }

    public function mix_function()
    {


        $record = array();
        $echo = true;
        $version = $this->config->item('appVersion');
        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $language = "english";
        if (isset($data['select_language']) && !empty($data['select_language'])) {
            $language = $data['select_language'];
        }

        $id = validateToken();
        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();
        $auction_id = "";
        $item_id = "";
        if (isset($data['auctionId'])) {
            $auction_id = $data['auctionId'];
        }
        if (isset($data['itemId'])) {
            $item_id = $data['itemId'];
        }

        if (!empty($result3)) {


            $record['get_bid_log2'] = $this->get_bid_log2($echo, $auction_id, $item_id, $id);
            $record['get_all_lots2'] = $this->get_all_lots2($auction_id, $id);
            $record['get_winning_lots2'] = $this->get_winning_lots2($auction_id, $id);
            $record['get_hall_auto_bids2'] = $this->get_hall_auto_bids2($auction_id, $id);
            $record['get_current_lot2'] = $this->get_current_lot2($auction_id, $item_id, $id);
            $record['get_upcoming_auctions2'] = $this->get_upcoming_auctions2($auction_id, $id);
            $record['get_winner_status_model2'] = $this->get_winner_status_model2($auction_id, $item_id, $id);
            $record['get_item_fields_data2'] = $this->get_item_fields_data2($auction_id, $item_id, $id, $language);
            $record['get_item_box_data2'] = $this->get_item_box_data2($auction_id, $item_id, $language);


            echo json_encode($record);
        }


    }

    public function mix_function2(){



        $record = array();
        $echo = true;
        $version = $this->config->item('appVersion');
        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $language = "english";
        if (isset($data['select_language']) && !empty($data['select_language'])) {
            $language = $data['select_language'];
        }

        $id = validateToken();
        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();
        $auction_id = "";
        $item_id = "";
        if (isset($data['auctionId'])) {
            $auction_id = $data['auctionId'];
        }
        if (isset($data['itemId'])) {
            $item_id = $data['itemId'];
        }

        if (!empty($result3)) {


            $record['get_bid_log2'] = $this->get_bid_log2($echo, $auction_id, $item_id,$id);
            //$record['get_all_lots2'] = $this->get_all_lots2($auction_id);
            //$record['get_winning_lots2']=  $this->get_winning_lots2($auction_id);
            $record['get_hall_auto_bids2'] = $this->get_hall_auto_bids2($auction_id,$id);
            //$record['get_current_lot2'] = $this->get_current_lot2($auction_id,$item_id);
            //$record['get_upcoming_auctions2'] = $this->get_upcoming_auctions2($auction_id);
            $record['get_winner_status_model2'] = $this->get_winner_status_model2($auction_id, $item_id,$id);
            //$record['get_item_fields_data2'] = $this->get_item_fields_data2($auction_id,$item_id);
            //$record['get_item_box_data2'] = $this->get_item_box_data2($auction_id,$item_id);


        }
        echo json_encode($record);


    }

    public function get_bid_log()
    {


        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {
            if ($data) {

                $output = '';
                $language = "english";
                $auction_id = $data['auctionId'];
                //$item_id = $data['itemId'];
                // $bid_amount = $this->input->post('bid_amount');

                $data2['bidLog'] = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id])->result_array();

                $i = 0;

                foreach ($data2['bidLog'] as $key => $bidLogValue) {
                    $i++;

                    $active = '';
                    if ($i == 1) {
                        $active = 'active';
                        $data2['bidLog'][$key]['active'] = $active;
                    }

                    $icon = base_url("assets_admin/images/law-gray.svg");
                    $data2['bidLog'][$key]['icon'] = $icon;
                    $color = 'class="text-primary"';
                    $data2['bidLog'][$key]['color'] = $color;

                    $bidder = $this->lang->line('hall_bidder');
                    $data2['bidLog'][$key]['bidder'] = $bidder;
                    $bid_type = $this->lang->line('hall_bid');
                    $data2['bidLog'][$key]['bid_type'] = $bid_type;
                    if ($bidLogValue['bid_type'] == 'online') {
                        $icon = base_url("assets_admin/images/law-red.svg");
                        $data2['bidLog'][$key]['icon'] = $icon;
                        $color = 'class="text-default"';
                        $data2['bidLog'][$key]['color'] = $color;
                        $bidder = $this->lang->line('online_bidder');
                        $data2['bidLog'][$key]['bidder'] = $bidder;
                        $bid_type = $this->lang->line('online_bid');
                        $data2['bidLog'][$key]['bidder_type'] = $bid_type;
                    }

                    if ($bidLogValue['bid_status'] == 'win') {
                        //$this->get_winner_status_model($bidLogValue['bid_amount'],$bidLogValue['user_id']);
                        $icon = '<i class="fa fa-check"></i>';
                        $data2['bidLog'][$key]['icon'] = $icon;
                        $color = 'style="color: green;"';
                        $data2['bidLog'][$key]['color'] = $color;
                        $item = $this->db->get_where('item', ['id' => $bidLogValue['item_id']])->row_array();
                        $data2['bidLog'][$key]['item_detail'] = $item;
                        $item_name = json_decode($item['name']);
                        $data2['bidLog'][$key]['item_name'] = $item_name;
                        //$output.= '<li class="'.$active.' '.$color.'">'.$icon.$item['name'].' sold to '.$bidder.' for AED '.$bidLogValue['bid_amount'].' at '.$bidLogValue['created_on'].'</li>';
                        if ($language == 'arabic'):

                            $_showListSold = $icon . ' ' . $this->lang->line('sold_to_new') . ' ' . $item_name->$language . ' ' . $bidder . ' ' . number_format($bidLogValue['bid_amount'], 0, ".", ",") . ' ' . $this->lang->line('aed');

                            $data2['bidLog'][$key]['show_data'] = $_showListSold;

                        else:
                            $_showListSold = $icon . $item_name->$language . ' ' . $this->lang->line('sold_to') . ' ' . $bidder . ' ' . $this->lang->line('for_aed_new') . ' ' . number_format($bidLogValue['bid_amount'], 0, ".", ",");
                            $data2['bidLog'][$key]['show_data'] = $_showListSold;
                        endif;

                        $output .= $color . date('h:i A', strtotime($bidLogValue['created_on'])) . $_showListSold;
                        $data2['bidLog'][$key]['show_data'] = $_showListSold;
                    }

                    //$output.= '<li class="'.$active.' '.$color.'">'.$icon.' '.$bid_type.' '.$bidLogValue['bid_increment_amount'].' '.$bidLogValue['created_on'].'</li>';
                    if ($language == 'arabic'):

                        $_showList = $icon . $bid_type . ' ' . number_format($bidLogValue['bid_amount'], 0, ".", ",") . ' ' . $this->lang->line('aed');
                        $data2['bidLog'][$key]['show_data'] = $_showList;
                    else:
                        $_showList = $icon . $bid_type . ' ' . $this->lang->line('aed') . ' ' . number_format($bidLogValue['bid_amount'], 0, ".", ",");
                        $data2['bidLog'][$key]['show_data'] = $_showList;
                    endif;
                    $output .= $color . date('h:i A', strtotime($bidLogValue['created_on'])) . $_showList;


                }


                $codee = http_response_code(200);
                echo json_encode(array("status" => 'true', 'code' => $codee, 'msg' => 'success', 'appVersion' => $version, 'result' => $data2));
                exit();
            } else {

                $codee = http_response_code(200);
                echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                exit();
            }

        } else {

            $codee = http_response_code(200);
            echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
            exit();
        }
    }

    public function get_bid_log2($echo, $auction_id, $item_id, $id)
    {


        if (!empty($auction_id)) {

            $output = '';
            $language = "english";
            //$item_id = $data['itemId'];
            // $bid_amount = $this->input->post('bid_amount');

            //$data2['bidLog'] = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id])->result_array();


            $this->db->select('*');
            $this->db->from('live_auction_bid_log');
            $this->db->where('auction_id', $auction_id);
            $this->db->limit(40, 0);
            $this->db->order_by('id', "desc");
            $bidLog = $this->db->get();
            $data2['bidLog'] = $bidLog->result_array();

            $i = 0;

            foreach ($data2['bidLog'] as $key => $bidLogValue) {
                $i++;

                $active = '';
                if ($i == 1) {
                    $active = 'active';
                    $data2['bidLog'][$key]['active'] = $active;
                }

                $icon = base_url("assets_admin/images/law-gray.svg");
                $data2['bidLog'][$key]['icon'] = $icon;
                $color = 'class="text-primary"';
                $data2['bidLog'][$key]['color'] = $color;

                $bidder = $this->lang->line('hall_bidder');
                $data2['bidLog'][$key]['bidder'] = $bidder;
                $bid_type = $this->lang->line('hall_bid');
                $data2['bidLog'][$key]['bid_type'] = $bid_type;
                if ($bidLogValue['bid_type'] == 'online') {
                    $icon = base_url("assets_admin/images/law-red.svg");
                    $data2['bidLog'][$key]['icon'] = $icon;
                    $color = 'class="text-default"';
                    $data2['bidLog'][$key]['color'] = $color;
                    $bidder = $this->lang->line('online_bidder');
                    $data2['bidLog'][$key]['bidder'] = $bidder;
                    $bid_type = $this->lang->line('online_bid');
                    $data2['bidLog'][$key]['bidder_type'] = $bid_type;
                }

                if ($bidLogValue['bid_status'] == 'win') {
                    //$this->get_winner_status_model($bidLogValue['bid_amount'],$bidLogValue['user_id']);
                    $icon = '<i class="fa fa-check"></i>';
                    $data2['bidLog'][$key]['icon'] = $icon;
                    $color = 'style="color: green;"';
                    $data2['bidLog'][$key]['color'] = $color;
                    $item = $this->db->get_where('item', ['id' => $bidLogValue['item_id']])->row_array();
                    $data2['bidLog'][$key]['item_detail'] = $item;
                    $item_name = json_decode($item['name']);
                    $data2['bidLog'][$key]['item_name'] = $item_name;
                    //$output.= '<li class="'.$active.' '.$color.'">'.$icon.$item['name'].' sold to '.$bidder.' for AED '.$bidLogValue['bid_amount'].' at '.$bidLogValue['created_on'].'</li>';
                    if ($language == 'arabic'):

                        $_showListSold = $icon . ' ' . $this->lang->line('sold_to_new') . ' ' . $item_name->$language . ' ' . $bidder . ' ' . number_format($bidLogValue['bid_amount'], 0, ".", ",") . ' ' . $this->lang->line('aed');

                        $data2['bidLog'][$key]['show_data'] = $_showListSold;

                    else:
                        $_showListSold = $icon . $item_name->$language . ' ' . $this->lang->line('sold_to') . ' ' . $bidder . ' ' . $this->lang->line('for_aed_new') . ' ' . number_format($bidLogValue['bid_amount'], 0, ".", ",");
                        $data2['bidLog'][$key]['show_data'] = $_showListSold;
                    endif;

                    $output .= $color . date('h:i A', strtotime($bidLogValue['created_on'])) . $_showListSold;
                    $data2['bidLog'][$key]['show_data'] = $_showListSold;
                }

                //$output.= '<li class="'.$active.' '.$color.'">'.$icon.' '.$bid_type.' '.$bidLogValue['bid_increment_amount'].' '.$bidLogValue['created_on'].'</li>';
                if ($language == 'arabic'):

                    $_showList = $icon . $bid_type . ' ' . number_format($bidLogValue['bid_amount'], 0, ".", ",") . ' ' . $this->lang->line('aed');
                    $data2['bidLog'][$key]['show_data'] = $_showList;
                else:
                    $_showList = $icon . $bid_type . ' ' . $this->lang->line('aed') . ' ' . number_format($bidLogValue['bid_amount'], 0, ".", ",");
                    $data2['bidLog'][$key]['show_data'] = $_showList;
                endif;
                $output .= $color . date('h:i A', strtotime($bidLogValue['created_on'])) . $_showList;


            }

            return $data2['bidLog'];
        } else {

            return [];
        }
    }


    public function get_all_lots()
    {

        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {
            if ($data) {


                if ($data) {
                    $all_lots = $this->db->get_where('auction_items', ['auction_id' => $data['auctionId']]);
                    if ($all_lots->num_rows() > 0) {
                        $data2['all_lots'] = $all_lots->result_array();


                        if ($data2['all_lots']) {
                            foreach ($data2['all_lots'] as $key => $value) {

                                //Status
                                switch ($value['sold_status']) {
                                    case 'not':
                                        $sold_status = $this->lang->line('coming_up');
                                        $data2['all_lots'][$key]['sold_status'] = $sold_status;
                                        break;
                                    case 'sold':
                                        $sold_status = $this->lang->line('sold');
                                        $data2['all_lots'][$key]['sold_status'] = $sold_status;
                                        break;
                                    case 'not_sold':
                                        $sold_status = $this->lang->line('sold');
                                        $data2['all_lots'][$key]['sold_status'] = $sold_status;
                                        break;

                                    default:
                                        $sold_status = $this->lang->line('unknown');
                                        $data2['all_lots'][$key]['sold_status'] = $sold_status;
                                        break;
                                }

                                //Item Detail
                                $item_detail = $this->db->get_where('item', ['id' => $value['item_id']])->row_array();
                                $detail = json_decode($item_detail['detail']);

                                $item_images_ids = explode(',', $item_detail['item_images']);

                                $images = $this->db->where('id', $item_images_ids[0])->order_by('file_order', 'ASC')->get('files')->result_array();

                                if ($images) {
                                    $image_src = base_url('uploads/items_documents/') . $item_detail['id'] . '/' . $images[0]['name'];

                                    $data2['all_lots'][$key]['image_url'] = $image_src;
                                } else {
                                    $image_src = '';
                                    $data2['all_lots'][$key]['image_url'] = $image_src;
                                }

                                $data2['all_lots'][$key]['name_title'] = json_decode($item_detail['name'])->$language;
                                $data2['all_lots'][$key]['description'] = $detail->$language;;

                            }
                        }

                        $codee = http_response_code(200);
                        echo json_encode(array("status" => 'true', 'code' => $codee, 'msg' => 'All Lots', 'appVersion' => $version, 'result' => $data2));
                        exit();
                    } else {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                        exit();
                    }
                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                    exit();
                }
                echo json_encode($output);
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                exit();
            }
        } else {


            $codee = http_response_code(200);
            echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
            exit();

        }
    }

    public function get_all_lots2($auction_id, $id)
    {

        $language = "english";

        if ($auction_id) {

            $all_lots = $this->db->get_where('auction_items', ['auction_id' => $auction_id]);
            if ($all_lots->num_rows() > 0) {
                $data2['all_lots'] = $all_lots->result_array();


                if ($data2['all_lots']) {
                    foreach ($data2['all_lots'] as $key => $value) {

                        //Status
                        switch ($value['sold_status']) {
                            case 'not':
                                $sold_status = $this->lang->line('coming_up');
                                $data2['all_lots'][$key]['sold_status'] = $sold_status;
                                break;
                            case 'sold':
                                $sold_status = $this->lang->line('sold');
                                $data2['all_lots'][$key]['sold_status'] = $sold_status;
                                break;
                            case 'not_sold':
                                $sold_status = $this->lang->line('sold');
                                $data2['all_lots'][$key]['sold_status'] = $sold_status;
                                break;

                            default:
                                $sold_status = $this->lang->line('unknown');
                                $data2['all_lots'][$key]['sold_status'] = $sold_status;
                                break;
                        }

                        //Item Detail
                        $item_detail = $this->db->get_where('item', ['id' => $value['item_id']])->row_array();
                        $detail = json_decode($item_detail['detail']);

                        $item_images_ids = explode(',', $item_detail['item_images']);

                        $images = $this->db->where('id', $item_images_ids[0])->order_by('file_order', 'ASC')->get('files')->result_array();

                        if ($images) {
                            $image_src = base_url('uploads/items_documents/') . $item_detail['id'] . '/' . $images[0]['name'];

                            $data2['all_lots'][$key]['image_url'] = $image_src;
                        } else {
                            $image_src = '';
                            $data2['all_lots'][$key]['image_url'] = $image_src;
                        }

                        $data2['all_lots'][$key]['name_title'] = json_decode($item_detail['name'])->$language;
                        $data2['all_lots'][$key]['description'] = $detail->$language;;

                    }
                }
                return $data2['all_lots'];
            } else {
                return [];
            }
        } else {
            return [];
        }

    }

    public
    function get_winning_lots()
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {
            if ($data) {
                $all_lots = $this->db->get_where('auction_items', ['auction_id' => $data['auctionId'], 'sold_status' => 'sold']);
                if ($all_lots->num_rows() > 0) {
                    $data2['winning_lots'] = $all_lots->result_array();


                    if ($data2['winning_lots']) {
                        foreach ($data2['winning_lots'] as $key => $value) {

                            //Status
                            switch ($value['sold_status']) {
                                case 'not':
                                    $sold_status = $this->lang->line('coming_up');
                                    $data2['winning_lots'][$key]['sold_status'] = $sold_status;
                                    break;
                                case 'sold':
                                    $sold_status = $this->lang->line('sold');
                                    $data2['winning_lots'][$key]['sold_status'] = $sold_status;
                                    break;
                                case 'not_sold':
                                    $sold_status = $this->lang->line('sold');
                                    $data2['winning_lots'][$key]['sold_status'] = $sold_status;
                                    break;

                                default:
                                    $sold_status = $this->lang->line('unknown');
                                    $data2['winning_lots'][$key]['sold_status'] = $sold_status;
                                    break;
                            }

                            //Item Detail
                            $item_detail = $this->db->get_where('item', ['id' => $value['item_id']])->row_array();
                            $detail = json_decode($item_detail['detail']);

                            $item_images_ids = explode(',', $item_detail['item_images']);

                            $images = $this->db->where('id', $item_images_ids[0])->order_by('file_order', 'ASC')->get('files')->result_array();

                            if ($images) {
                                $image_src = base_url('uploads/items_documents/') . $item_detail['id'] . '/' . $images[0]['name'];

                                $data2['winning_lots'][$key]['image_url'] = $image_src;
                            } else {
                                $image_src = '';
                                $data2['winning_lots'][$key]['image_url'] = $image_src;
                            }
                            $data2['winning_lots'][$key]['name_title'] = json_decode($item_detail['name'])->$language;
                            $data2['winning_lots'][$key]['description'] = $detail->$language;;

                        }
                    }


                    $codee = http_response_code(200);
                    echo json_encode(array("status" => 'true', 'code' => $codee, 'msg' => 'Winning Lots', 'appVersion' => $version, 'result' => $data2));
                    exit();

                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                    exit();
                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                exit();
            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
            exit();
        }
    }

    public
    function get_winning_lots2($auction_id, $id)
    {
        $language = "english";
        if ($auction_id) {
            $all_lots = $this->db->get_where('auction_items', ['auction_id' => $auction_id, 'sold_status' => 'sold']);
            if ($all_lots->num_rows() > 0) {
                $data2['winning_lots'] = $all_lots->result_array();


                if ($data2['winning_lots']) {
                    foreach ($data2['winning_lots'] as $key => $value) {

                        //Status
                        switch ($value['sold_status']) {
                            case 'not':
                                $sold_status = $this->lang->line('coming_up');
                                $data2['winning_lots'][$key]['sold_status'] = $sold_status;
                                break;
                            case 'sold':
                                $sold_status = $this->lang->line('sold');
                                $data2['winning_lots'][$key]['sold_status'] = $sold_status;
                                break;
                            case 'not_sold':
                                $sold_status = $this->lang->line('sold');
                                $data2['winning_lots'][$key]['sold_status'] = $sold_status;
                                break;

                            default:
                                $sold_status = $this->lang->line('unknown');
                                $data2['winning_lots'][$key]['sold_status'] = $sold_status;
                                break;
                        }

                        //Item Detail
                        $item_detail = $this->db->get_where('item', ['id' => $value['item_id']])->row_array();
                        $detail = json_decode($item_detail['detail']);

                        $item_images_ids = explode(',', $item_detail['item_images']);

                        $images = $this->db->where('id', $item_images_ids[0])->order_by('file_order', 'ASC')->get('files')->result_array();

                        if ($images) {
                            $image_src = base_url('uploads/items_documents/') . $item_detail['id'] . '/' . $images[0]['name'];

                            $data2['winning_lots'][$key]['image_url'] = $image_src;
                        } else {
                            $image_src = '';
                            $data2['winning_lots'][$key]['image_url'] = $image_src;
                        }
                        $data2['winning_lots'][$key]['name_title'] = json_decode($item_detail['name'])->$language;
                        $data2['winning_lots'][$key]['description'] = $detail->$language;;

                    }
                }
                return $data2['winning_lots'];

            } else {
                return [];
            }
        } else {
            return [];
        }

    }

    public
    function get_hall_auto_bids()
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {
            if ($data) {
                $user = $id;
                if ($user) {
                    $auto_bids = $this->db->get_where('bid_auto', ['auction_id' => $data['auctionId'], 'user_id' => $user]);
                    if ($auto_bids->num_rows() > 0) {
                        $auto_bids = $auto_bids->result_array();

                        $codee = http_response_code(200);
                        echo json_encode(array("status" => 'true', 'code' => $codee, 'msg' => 'Auto Bids', 'appVersion' => $version, 'result' => $auto_bids));
                        exit();

                    } else {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                        exit();
                    }
                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
                    exit();
                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                exit();
            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
            exit();
        }
    }

    public
    function get_hall_auto_bids2($auction_id, $id)
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        if ($auction_id) {
            $user = $id;
            if ($user) {
                $auto_bids = $this->db->get_where('bid_auto', ['auction_id' => $auction_id, 'user_id' => $user]);
                if ($auto_bids->num_rows() > 0) {
                    $auto_bids = $auto_bids->result_array();

                    return $auto_bids;

                } else {
                    return [];
                }
            } else {
                return [];
            }
        } else {
            return [];
        }

    }

// show items fileds
    public
    function get_item_fields_data()
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $language = "english";
        if (isset($data['select_language']) && !empty($data['select_language'])) {
            $language = $data['select_language'];
        }


        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {
            if ($data) {
                $auction_id = $data['auctionId'];
                $item_id = $data['itemId'];


                //auction item details
                $data['auction_details'] = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();
                $item_data = $this->oam->get_items_details($item_id, $auction_id);
                $data['item'] = $this->get_items_details($item_id, $auction_id);

                $data['category_id'] = $item_data['category_id'];
                $data['category'] = $this->db->get_where('item_category', ['id' => $data['category_id']])->row_array();

                $odometer = "";
                $odometer_image = "";

                $spec_mileage = "";
                $spec_image = "";
                $specsType = "";


                foreach ($data['item'] as $key => $val) {


                    //specs data

                    if (($data['category']['include_make_model'] == 'yes')) {

                        $odometer = number_format($val['mileage']);
                        $odometer_image = base_url('assets_user') . "/new/images/detail-icons/odometer-icons.svg";

                    }

                    if (($data['category']['include_make_model'] == 'yes') && isset($val['specification'])) {

                        $spec_mileage = number_format($val['mileage']);
                        $spec_image = base_url('assets_user') . "/new/images/detail-icons/specification-icons.svg";

                        if ($val['specification'] == 'GCC') {
                            $specsType = 'GCC';
                        } else {
                            $specsType = 'IMPORTED';
                        }
                    }

                    if (empty($data['item'][$key]['additional_info'])) {
                        $additional_info = [
                            'english' => '',
                            'arabic' => '',
                        ];
                        $data['item'][$key]['additional_info'] = json_encode($additional_info);
                    }


                    $item_make = $this->db->get_where('item_makes', ['id' => $val['item_make']])->row_array();
                    $item_model = $this->db->get_where('item_models', ['id' => $val['item_model']])->row_array();

                    $make_name = $item_make['title'];
                    $model_name = $item_model['title'];
                    $data['item'][$key]['item_make_title'] = $make_name;
                    $data['item'][$key]['item_model_title'] = $model_name;
                    $data['item'][$key]['map'] = "https://www.google.com/maps/@" . $data['item'][$key]['item_lat'] . ',' . $data['item'][$key]['item_lng'] . ',15z';
                    $phonee = $this->db->select('phone')->get('contact_us')->row_array();
                    $data['item'][$key]['phone'] = $phonee['phone'];

                    $data['item'][$key]['report'] = base_url('inspection_report/') . $data['item'][$key]['id'];

                    $f = 0;
                    if (!empty($data['item'][$key]['item_test_report'])) {

                        $inspection_report = array();
                        $inspection_report_name = array();

                        $doc_ids = explode(',', $data['item'][$key]['item_test_report']);
                        while ($f < count($doc_ids)) {
                            $item_test_report = $this->db->where_in('id', $doc_ids[$f])->get('files')->row_array();

                            $inspection_report[$f] = base_url() . $item_test_report['path'] . $item_test_report['name'];
                            $inspection_report_name[$f] = $item_test_report['orignal_name'];

                            $data['item'][$key]['inspection_report'][] = array(
                                'url' => $inspection_report[$f],
                                'title' => $inspection_report_name[$f]
                            );
                            $f++;
                        }


                    } else {
                        $data['item'][$key]['inspection_report'] = null;
                    }


                    if (!empty($val['item_images'])) {
                        $item_images = explode(',', $val['item_images']);
                        $i = 0;
                        $image_get = array();
                        while ($i < count($item_images)) {
                            $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                            $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                            $i++;
                        }
                        $data['item'][$key]['item_images'] = $image_get;
                    }

                    $data['bid_place']['min_bid'] = $data['item'][$key]['minimum_bid_price'];
                    $data['bid_place']['data-auction-id'] = $data['item'][$key]['auction_id'];
                    $data['bid_place']['data-item-id'] = $data['item'][$key]['item_id'];
                    $data['bid_place']['data-seller-id'] = $data['item'][$key]['item_seller_id'];
                    $data['bid_place']['data-lot-no'] = $data['item'][$key]['auction_item_id'];
                    $data['bid_place']['data-start-price'] = $data['item'][$key]['bid_start_price'];
                    $data['bid_place']['auction_item_id'] = $data['item'][$key]['auction_item_id'];
                }

                //items category dynamic fields
                $datafields = $this->oam->fields_data($item_data['category_id']);
                $fdata = array();
                foreach ($datafields as $key => $fields) {
                    $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id, $fields['id']);
                    if (!empty($item_dynamic_fields_info)) {
                        $fields['values'] = json_decode($fields['values'], true);
                        $fields['data-id'] = $fields['id'];
                        if (!empty($fields['values'])) {
                            foreach ($fields['values'] as $key => $options) {
                                if ($options['value'] == $item_dynamic_fields_info['value']) {
                                    $fields['data-value'] = $options['label'];
                                }
                            }
                        } else {
                            $fields['data-value'] = $item_dynamic_fields_info['value'];
                        }
                        $fdata[] = $fields;
                    }
                }
                $j = 11;
                $i = 0;
                foreach ($fdata as $key => $value) {
                    if (!empty($value['data-value'])) {
                        $i++;
                        if ($i <= $j) {
                            $data['field']['label'][] = $this->make_dual($value['label'], $language);
                            $data['field']['value'][] = $this->make_dual($value['data-value'], $language);

                            if ($i == 1) {
                                if (($data['category']['include_make_model'] == 'yes')) {
                                    if ($language == "english") {

                                        $data['field']['label'][] = "Odometer";
                                        $data['field']['label'][] = "Specification";

                                        if ($specsType == 'GCC') {
                                            $specsType = 'GCC';
                                        } else {
                                            $specsType = 'IMPORTED';
                                        }

                                        $data['field']['value'][] = $odometer;
                                        $data['field']['value'][] = $specsType;
                                    } else {
                                        $data['field']['label'][] = 'عداد المركبة';
                                        $data['field']['label'][] = "المواصفات";
                                        if ($specsType == 'GCC') {
                                            $specsType = 'خليجية';
                                        } else {
                                            $specsType = 'وارد';
                                        }
                                        $data['field']['value'][] = $odometer;
                                        $data['field']['value'][] = $specsType;
                                    }
                                }
                            }
                        }
                    }
                }


                $codee = http_response_code(200);
                echo json_encode(array("status" => 'true', 'code' => $codee, 'msg' => 'success', 'appVersion' => $version, 'fields' => $data['field'], 'category' => $data['category'], 'item' => $data['item']));

                exit();
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                exit();

            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
            exit();
        }
    }

    public
    function get_item_fields_data2($auction_id, $item_id, $id, $language)
    {

        $version = $this->config->item('appVersion');


        if ($auction_id) {
            //auction item details
            $item_data = $this->oam->get_items_details($item_id, $auction_id);
            $data['item'] = $this->get_items_details($item_id, $auction_id);

            $data['category_id'] = $item_data['category_id'];
            $data['category'] = $this->db->get_where('item_category', ['id' => $data['category_id']])->row_array();
            $data['auction_details'] = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();

            $odometer = "";
            $odometer_image = "";

            $spec_mileage = "";
            $spec_image = "";
            $specsType = "";

            foreach ($data['item'] as $key => $val) {


                //specs data

                if (($data['category']['include_make_model'] == 'yes')) {

                    $odometer = number_format($val['mileage']);
                    $odometer_image = base_url('assets_user') . "/new/images/detail-icons/odometer-icons.svg";

                }

                if (($data['category']['include_make_model'] == 'yes') && isset($val['specification'])) {

                    $spec_mileage = number_format($val['mileage']);
                    $spec_image = base_url('assets_user') . "/new/images/detail-icons/specification-icons.svg";

                    if ($val['specification'] == 'GCC') {
                        $specsType = 'GCC';
                    } else {
                        $specsType = 'IMPORTED';
                    }
                }

                if (empty($data['item'][$key]['additional_info'])) {
                    $additional_info = [
                        'english' => '',
                        'arabic' => '',
                    ];
                    $data['item'][$key]['additional_info'] = json_encode($additional_info);
                }


                $item_make = $this->db->get_where('item_makes', ['id' => $val['item_make']])->row_array();
                $item_model = $this->db->get_where('item_models', ['id' => $val['item_model']])->row_array();

                $make_name = $item_make['title'];
                $model_name = $item_model['title'];
                $data['item'][$key]['item_make_title'] = $make_name;
                $data['item'][$key]['item_model_title'] = $model_name;
                $data['item'][$key]['map'] = "https://www.google.com/maps/@" . $data['item'][$key]['item_lat'] . ',' . $data['item'][$key]['item_lng'] . ',15z';
                $phonee = $this->db->select('phone')->get('contact_us')->row_array();
                $data['item'][$key]['phone'] = $phonee['phone'];

                $data['item'][$key]['report'] = base_url('inspection_report/') . $data['item'][$key]['id'];

                $f = 0;
                if (!empty($data['item'][$key]['item_test_report'])) {

                    $inspection_report = array();
                    $inspection_report_name = array();

                    $doc_ids = explode(',', $data['item'][$key]['item_test_report']);
                    while ($f < count($doc_ids)) {
                        $item_test_report = $this->db->where_in('id', $doc_ids[$f])->get('files')->row_array();

                        $inspection_report[$f] = base_url() . $item_test_report['path'] . $item_test_report['name'];
                        $inspection_report_name[$f] = $item_test_report['orignal_name'];

                        $data['item'][$key]['inspection_report'][] = array(
                            'url' => $inspection_report[$f],
                            'title' => $inspection_report_name[$f]
                        );
                        $f++;
                    }


                } else {
                    $data['item'][$key]['inspection_report'] = null;
                }


                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {
                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
                    $data['item'][$key]['item_images'] = $image_get;
                }

                $data['bid_place']['min_bid'] = $data['item'][$key]['minimum_bid_price'];
                $data['bid_place']['data-auction-id'] = $data['item'][$key]['auction_id'];
                $data['bid_place']['data-item-id'] = $data['item'][$key]['item_id'];
                $data['bid_place']['data-seller-id'] = $data['item'][$key]['item_seller_id'];
                $data['bid_place']['data-lot-no'] = $data['item'][$key]['auction_item_id'];
                $data['bid_place']['data-start-price'] = $data['item'][$key]['bid_start_price'];
                $data['bid_place']['auction_item_id'] = $data['item'][$key]['auction_item_id'];
            }

            //items category dynamic fields
            $datafields = $this->oam->fields_data($item_data['category_id']);
            $fdata = array();
            foreach ($datafields as $key => $fields) {
                $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id, $fields['id']);
                if (!empty($item_dynamic_fields_info)) {
                    $fields['values'] = json_decode($fields['values'], true);
                    $fields['data-id'] = $fields['id'];
                    if (!empty($fields['values'])) {
                        foreach ($fields['values'] as $key => $options) {
                            if ($options['value'] == $item_dynamic_fields_info['value']) {
                                $fields['data-value'] = $options['label'];
                            }
                        }
                    } else {
                        $fields['data-value'] = $item_dynamic_fields_info['value'];
                    }
                    $fdata[] = $fields;
                }
            }
            $j = 11;
            $i = 0;
            foreach ($fdata as $key => $value) {
                if (!empty($value['data-value'])) {
                    $i++;
                    if ($i <= $j) {
                        $data['field']['label'][] = $this->make_dual($value['label'], $language);
                        $data['field']['value'][] = $this->make_dual($value['data-value'], $language);

                        if ($i == 1) {
                            if (($data['category']['include_make_model'] == 'yes')) {
                                if ($language == "english") {

                                    $data['field']['label'][] = "Odometer";
                                    $data['field']['label'][] = "Specification";

                                    if ($specsType == 'GCC') {
                                        $specsType = 'GCC';
                                    } else {
                                        $specsType = 'IMPORTED';
                                    }

                                    $data['field']['value'][] = $odometer;
                                    $data['field']['value'][] = $specsType;
                                } else {
                                    $data['field']['label'][] = 'عداد المركبة';
                                    $data['field']['label'][] = "المواصفات";
                                    if ($specsType == 'GCC') {
                                        $specsType = 'خليجية';
                                    } else {
                                        $specsType = 'وارد';
                                    }
                                    $data['field']['value'][] = $odometer;
                                    $data['field']['value'][] = $specsType;
                                }
                            }
                        }
                    }
                }
            }


            return $data;

        } else {
            return [];

        }
    }

// end

    public
    function get_current_lot()
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {
            if ($data) {
                $current_lot = $this->oam->get_items_details($data['itemId'], $data['auctionId']);

                //print_r($specification);
                if ($current_lot['category_id'] == 1) {
                    $make = $this->db->get_where('item_makes', ['id' => $current_lot['item_make']])->row_array();
                    $model = $this->db->get_where('item_models', ['id' => $current_lot['item_model']])->row_array();
                    $specification = $this->db->get_where('item_models', ['id' => $current_lot['specification']])->row_array();

                    $current_lot['vehicle'] = true;
                    $current_lot['make'] = $make;
                    $current_lot['model'] = $model;
                    $current_lot['specification'] = $specification;
                }


                $codee = http_response_code(200);
                echo json_encode(array("status" => 'true', 'code' => $codee, 'msg' => 'Current Lot', 'appVersion' => $version, 'result' => $current_lot));
                exit();
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                exit();
            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
            exit();
        }
    }

    public
    function get_current_lot2($auction_id, $item_id, $id)
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        if ($auction_id) {
            $current_lot = $this->oam->get_items_details($item_id, $auction_id);
            //print_r($specification);
            if ($current_lot['category_id'] == 1) {
                $make = $this->db->get_where('item_makes', ['id' => $current_lot['item_make']])->row_array();
                $model = $this->db->get_where('item_models', ['id' => $current_lot['item_model']])->row_array();
                $specification = $this->db->get_where('item_models', ['id' => $current_lot['specification']])->row_array();

                $current_lot['vehicle'] = true;
                $current_lot['make'] = $make;
                $current_lot['model'] = $model;
                $current_lot['specification'] = $specification;
            }

            return $current_lot;
        } else {
            return [];
        }

    }

    public
    function get_upcoming_auctions()
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {
            if ($data) {
                $auction_items = $this->lam->get_live_auction_items($data['auctionId']);

                foreach ($auction_items as $key => $record) {

                    if (!empty($record['item_images'])) {
                        $item_images = explode(',', $record['item_images']);
                        $i = 0;
                        $image_get = array();
                        while ($i < count($item_images)) {

                            $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                            $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                            $i++;
                        }
                        $auction_items[$key]['item_images'] = $image_get;
                    }
                }

                $codee = http_response_code(200);
                echo json_encode(array("status" => 'true', 'code' => $codee, 'msg' => 'Upcoming Auction', 'appVersion' => $version, 'result' => $auction_items));
                exit();
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Data Not Found', 'appVersion' => $version, 'result' => 'false'));
                exit();
            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
            exit();
        }
    }

    public
    function get_upcoming_auctions2($auction_id, $id)
    {
        $language = "english";
        $version = $this->config->item('appVersion');


        if ($auction_id) {
            $auction_items = $this->lam->get_live_auction_items($auction_id);

            foreach ($auction_items as $key => $record) {

                if (!empty($record['item_images'])) {
                    $item_images = explode(',', $record['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {

                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
                    $auction_items[$key]['item_images'] = $image_get;
                }
            }

            return $auction_items;
        } else {
            return [];
        }
    }

//sold live

    public
    function get_winner_status_model()
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {


            $auction_id = $data['auctionId'];
            $item_id = $data['item_id'];

            $bidLog = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id])->row_array();


             $itemDetails = $this->db->select('auction_items.lot_no,item.name as item_name')
            ->db->from('auctions')
            ->db->where('auctions.id =', $auction_id)
            ->db->where('item.id =', $item_id)
            ->db->join('auction_items', 'auction_items.auction_id = auctions.id', 'left')
            ->db->join('item', 'item.id = auction_items.item_id', 'left')->db->limit(1)->get()->row_array();


            $bid_amount = $bidLog['bid_amount'];
            $user_id = $bidLog['user_id'];

            $lot_no = $itemDetails['lot_no'];
            $item_name = $itemDetails['item_name'];

            if ($bidLog['bid_status'] == 'win') {
               $output['status'] = true;
                $output['uid'] = $user_id;
                $output['bid_amount'] = $bid_amount;
                $output['winner_model_header_eng'] = 'Congratulation! You are the highest bidder.';                
                $output['winner_model_text_item_name_eng'] = 'You are the highest bidder for ';
                $output['winner_model_para_eng'] = 'Our team will contact you shortly.';
                // arabic
                $output['winner_model_header_arabic'] = 'تهنئة! أنت أعلى مزايد.';
                $output['winner_model_text_item_name_arabic'] = 'أنت أعلى مزايد ل  ';
                $output['winner_model_para_arabic'] = 'سيتصل بك فريقنا قريبًا. ';

                $p_noti = ['lot_no'=> $lot_no, 'item_name' =>  $item_name];
                $this->getAuctionWinnnerPushNotification($result3['fcm_token'], $p_noti);
            } else {
                $output['status'] = false;
            }


            $codee = http_response_code(200);
            echo json_encode(array("status" => 'true', 'code' => $codee, 'msg' => 'Winner Status Model', 'appVersion' => $version, 'result' => $output));
            exit();

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => 'failed', 'code' => $codee, 'msg' => 'Toke/User Not Found', 'appVersion' => $version, 'result' => 'false'));
            exit();
        }
    }

    public
    function get_winner_status_model2($auction_id, $item_id, $id)
    {
        $language = "english";
        $version = $this->config->item('appVersion');


        $output = array();
        $bidLog = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id])->row_array();

        $itemDetails = $this->db->select('auction_items.lot_no,item.name as item_name')
            ->db->from('auctions')
            ->db->where('auctions.id =', $auction_id)
            ->db->where('item.id =', $item_id)
            ->db->join('auction_items', 'auction_items.auction_id = auctions.id', 'left')
            ->db->join('item', 'item.id = auction_items.item_id', 'left')->db->limit(1)->get()->row_array();


        $bid_amount = $bidLog['bid_amount'];
        $user_id = $bidLog['user_id'];

        $lot_no = $itemDetails['lot_no'];
            $item_name = $itemDetails['item_name'];

        if ($bidLog['bid_status'] == 'win') {
           $output['status'] = true;
            $output['uid'] = $user_id;
            $output['bid_amount'] = $bid_amount;
            $output['winner_model_header_eng'] = 'Congratulation! You are the highest bidder.';                
            $output['winner_model_text_item_name_eng'] = 'You are the highest bidder for ';
            $output['winner_model_para_eng'] = 'Our team will contact you shortly.';
            // arabic
            $output['winner_model_header_arabic'] = 'تهنئة! أنت أعلى مزايد.';
            $output['winner_model_text_item_name_arabic'] = 'أنت أعلى مزايد ل  ';
            $output['winner_model_para_arabic'] = 'سيتصل بك فريقنا قريبًا. ';

             $p_noti = ['lot_no'=> $lot_no, 'item_name' =>  $item_name];
                $this->getAuctionWinnnerPushNotification($result3['fcm_token'], $p_noti);
        } else {
            $output['status'] = false;
        }


        return $output;

    }


    public function get_item_box_data2($auction_id, $item_id, $language)
    {

        $response = '';
        if ($auction_id) {

            //auction item details
            $item_data = $this->oam->get_items_details($item_id, $auction_id);
            $data['item'] = $this->get_items_details($item_id, $auction_id);

            $data['category_id'] = $item_data['category_id'];
            $data['category'] = $this->db->get_where('item_category', ['id' => $data['category_id']])->row_array();

            $odometer = "";
            $odometer_image = "";

            $spec_mileage = "";
            $spec_image = "";
            $specsType = "";

            foreach ($data['item'] as $key => $val) {


                //specs data

                if (($data['category']['include_make_model'] == 'yes')) {

                    $odometer = number_format($val['mileage']);
                    $odometer_image = base_url('assets_user') . "/new/images/detail-icons/odometer-icons.svg";

                }

                if (($data['category']['include_make_model'] == 'yes') && isset($val['specification'])) {

                    $spec_mileage = number_format($val['mileage']);
                    $spec_image = base_url('assets_user') . "/new/images/detail-icons/specification-icons.svg";

                    if ($val['specification'] == 'GCC') {
                        $specsType = 'GCC';
                    } else {
                        $specsType = 'IMPORTED';
                    }
                }

                if (empty($data['item'][$key]['additional_info'])) {
                    $additional_info = [
                        'english' => '',
                        'arabic' => '',
                    ];
                    $data['item'][$key]['additional_info'] = json_encode($additional_info);
                }


                $item_make = $this->db->get_where('item_makes', ['id' => $val['item_make']])->row_array();
                $item_model = $this->db->get_where('item_models', ['id' => $val['item_model']])->row_array();

                $make_name = $item_make['title'];
                $model_name = $item_model['title'];
                $data['item'][$key]['item_make_title'] = $make_name;
                $data['item'][$key]['item_model_title'] = $model_name;
                $data['item'][$key]['map'] = "https://www.google.com/maps/@" . $data['item'][$key]['item_lat'] . ',' . $data['item'][$key]['item_lng'] . ',15z';
                $phonee = $this->db->select('phone')->get('contact_us')->row_array();
                $data['item'][$key]['phone'] = $phonee['phone'];

                $data['item'][$key]['report'] = base_url('inspection_report/') . $data['item'][$key]['id'];

                $f = 0;
                if (!empty($data['item'][$key]['item_test_report'])) {

                    $inspection_report = array();
                    $inspection_report_name = array();

                    $doc_ids = explode(',', $data['item'][$key]['item_test_report']);
                    while ($f < count($doc_ids)) {
                        $item_test_report = $this->db->where_in('id', $doc_ids[$f])->get('files')->row_array();

                        $inspection_report[$f] = base_url() . $item_test_report['path'] . $item_test_report['name'];
                        $inspection_report_name[$f] = $item_test_report['orignal_name'];

                        $data['item'][$key]['inspection_report'][] = array(
                            'url' => $inspection_report[$f],
                            'title' => $inspection_report_name[$f]
                        );
                        $f++;
                    }


                } else {
                    $data['item'][$key]['inspection_report'] = null;
                }


                if (!empty($val['item_images'])) {
                    $item_images = explode(',', $val['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {
                        $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                        $image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        $i++;
                    }
                    $data['item'][$key]['item_images'] = $image_get;
                }

                $data['bid_place']['min_bid'] = $data['item'][$key]['minimum_bid_price'];
                $data['bid_place']['data-auction-id'] = $data['item'][$key]['auction_id'];
                $data['bid_place']['data-item-id'] = $data['item'][$key]['item_id'];
                $data['bid_place']['data-seller-id'] = $data['item'][$key]['item_seller_id'];
                $data['bid_place']['data-lot-no'] = $data['item'][$key]['auction_item_id'];
                $data['bid_place']['data-start-price'] = $data['item'][$key]['bid_start_price'];
                $data['bid_place']['auction_item_id'] = $data['item'][$key]['auction_item_id'];
            }

            //items category dynamic fields
            $datafields = $this->oam->fields_data($item_data['category_id']);
            $fdata = array();
            foreach ($datafields as $key => $fields) {
                $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id, $fields['id']);
                if (!empty($item_dynamic_fields_info)) {
                    $fields['values'] = json_decode($fields['values'], true);
                    $fields['data-id'] = $fields['id'];
                    if (!empty($fields['values'])) {
                        foreach ($fields['values'] as $key => $options) {
                            if ($options['value'] == $item_dynamic_fields_info['value']) {
                                $fields['data-value'] = $options['label'];
                            }
                        }
                    } else {
                        $fields['data-value'] = $item_dynamic_fields_info['value'];
                    }
                    $fdata[] = $fields;
                }
            }
            $j = 11;
            $i = 0;
            foreach ($fdata as $key => $value) {
                if (!empty($value['data-value'])) {
                    $i++;
                    if ($i <= $j) {
                        $data['field']['label'][] = $this->make_dual($value['label'], $language);
                        $data['field']['value'][] = $this->make_dual($value['data-value'], $language);

                        if ($i == 1) {
                            if (($data['category']['include_make_model'] == 'yes')) {
                                if ($language == "english") {

                                    $data['field']['label'][] = "Odometer";
                                    $data['field']['label'][] = "Specification";

                                    if ($specsType == 'GCC') {
                                        $specsType = 'GCC';
                                    } else {
                                        $specsType = 'IMPORTED';
                                    }

                                    $data['field']['value'][] = $odometer;
                                    $data['field']['value'][] = $specsType;
                                } else {
                                    $data['field']['label'][] = 'عداد المركبة';
                                    $data['field']['label'][] = "المواصفات";
                                    if ($specsType == 'GCC') {
                                        $specsType = 'خليجية';
                                    } else {
                                        $specsType = 'وارد';
                                    }
                                    $data['field']['value'][] = $odometer;
                                    $data['field']['value'][] = $specsType;
                                }
                            }
                        }
                    }
                }
            }


            return $data;
        } else {
            return [];

        }
        return [];
    }


//online auction detail page end


//Profile


    public function insert_doc_type()
    {

        $version = $this->config->item('appVersion');

        $data_array = json_decode(file_get_contents("php://input"), true);
        $posted_data = json_decode(file_get_contents("php://input"));


        if ($posted_data) {
            $item_name = [
                'english' => $data_array['english'],
                'arabic' => $data_array['arabic']
            ];


            $insert_data = [
                'name' => json_encode($item_name)
            ];

            $return = $this->db->insert('documents_type', $insert_data);
            if ($return) {
                echo "true";
            } else {
                echo "false";
            }
        }
    }

    public function update_doc_type()
    {

        $version = $this->config->item('appVersion');

        $data_array = json_decode(file_get_contents("php://input"), true);
        $posted_data = json_decode(file_get_contents("php://input"));


        if ($posted_data) {

            $id = $data_array['id'];
            $update_id = $data_array['update_id'];
            $this->db->where('id', $id);
            $return = $this->db->update('documents_type', array('id' => $update_id));
            return $this->db->affected_rows();

        }
    }

    public function select_doc_type()
    {

        $data['list'] = $this->db->get('documents_type')->result_array();
        echo json_encode(array("status" => true, "message" => validation_errors(), 'appVersion' => $data));
        exit();
    }

    public function delete_doc_type($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('documents_type');
    }

    public
    function profile()
    {

        $language = "english";
        $version = $this->config->item('appVersion');
        $prefer_lang = "english";

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {


            $data = array();
            $data['new'] = 'new';
            $data['user_id'] = $id;
            $data['profile_active'] = 'active';
            $data['categoryId'] = 1;
            // $data['list']=$this->customer_model->get_user_list($id);
            $data['list'] = $this->db->get_where('users', ['id' => $id])->row_array();
            if (empty($data['list']['prefered_language'])) {
                $data['list']['prefered_language'] = $prefer_lang;
            }

            $img = array();

            if (isset($data['list']['picture']) && !empty($data['list']['picture'])) {
                $data['image_path'] = $this->db->get_where('files', ['id' => $data['list']['picture']])->result_array();
                foreach ($data['image_path'] as $key => $val) {

                    if (!empty($val['name'])) {
                        $image_get = trim(base_url(), '/') . '/' . trim($val['path'], '.') . $val['name'];
                        $data['image_path'][$key]['full_path'] = $image_get;
                        $data['list']['profile_url'] = base_url() . 'uploads/profile_picture/' . $data['list']['id'] . '/' . $val['name'];

                    }
                }


            }


            //documents
            $data['docs'] = $this->db->select('user_documents.*, documents_type.name as document_type, documents_type.id as document_type_id')->from('user_documents')
                ->join('documents_type', 'user_documents.document_type_id = documents_type.id')
                ->where(['user_id' => $id])
                ->order_by('documents_type.id', 'ASC')
                ->get()->result_array();


            ///// End Auction list //////
            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Profile Details', 'english' => $english_l['profile_details'], 'arabic' => $arabic_l['profile_details'], 'appVersion' => $version, 'result' => $data));


        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User or Token Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

    public
    function check_user_number($number, $id)
    {
        $this->db->where('mobile', $number);
        $this->db->where('id !=', $id);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public
    function update_profile()
    {


        $language = "english";
        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $postData = json_decode(file_get_contents("php://input"));
        $data = json_decode(json_encode($postData), true);

        $this->form_validation->set_data($data);

        $rules = array(
            array(
                'field' => 'fname',
                'label' => 'First name',
                'rules' => 'required|min_length[2]|max_length[30]',
            ),
            array(
                'field' => 'lname',
                'label' => 'Last Name',
                'rules' => 'required|min_length[2]|max_length[30]',

            ),
            array(
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'required|min_length[2]|max_length[100]',

            ),
            array(
                'field' => 'city',
                'label' => 'City',
                'rules' => 'required|min_length[2]|max_length[25]',

            ),
            array(
                'field' => 'country',
                'label' => 'Country',
                'rules' => 'required|min_length[2]|max_length[25]',

            ),
            array(
                'field' => 'state',
                'label' => 'State',
                'rules' => 'required|min_length[2]|max_length[25]',

            ),
        );

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == FALSE) {

            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => validation_errors(), 'appVersion' => $version));
            exit();
        }


        $id = validateToken();


        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {

            $dial_code = $result3[0]['dial_code'];
            $mobile = $result3[0]['mobile'];


//            if (isset($postData->mobile) && isset($postData->dial_code)) {
//                $dial_code = $postData->dial_code;
//                $mobile = $postData->mobile;
//                $mobile = $dial_code . "" . $mobile;
//
//                $number = preg_replace('/\s/', '', $mobile);
//                $check_number = $this->check_user_number($number, $id);
//                if ($check_number == true) {
//                    $codee = http_response_code(200);
//                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Mobile Already Exist for Another User', 'appVersion' => $version));
//                    exit();
//                }
//            }


            if (isset($postData->mobile)) {
                $mobile = $postData->mobile;

                $number = preg_replace('/\s/', '', $mobile);
                $check_number = $this->check_user_number($number, $id);
                if ($check_number == true) {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Mobile Already Exist for Another User', 'english' => $english_l['phone_already_registered'], 'arabic' => $arabic_l['phone_already_registered'], 'appVersion' => $version));
                    exit();
                }
            }


            $city = $result3[0]['city'];
            $state = $result3[0]['state'];
            $country = $result3[0]['country'];
            $job_title = $result3[0]['job_title'];
            $id_number = $result3[0]['id_number'];
            $po_box = $result3[0]['po_box'];
            $company_name = $result3[0]['company_name'];
            $vat_number = $result3[0]['vat_number'];
            $remarks = $result3[0]['remarks'];
            $description = $result3[0]['description'];
            $vat = $result3[0]['vat'];
            $address = $result3[0]['address'];
            $fname = $result3[0]['fname'];
            $lname = $result3[0]['lname'];
            $phone2 = $result3[0]['phone'];
            $prefered_language = $result3[0]['prefered_language'];


            if (isset($postData->prefered_language)) {
                $prefered_language = $postData->prefered_language;
            }

            if (isset($postData->city)) {
                $city = $postData->city;
            }
            if (isset($postData->state)) {
                $state = $postData->state;
            }
            if (isset($postData->country)) {
                $country = $postData->country;
            }
            if (isset($postData->job_title)) {
                $job_title = $postData->job_title;
            }
            if (isset($postData->id_number)) {
                $id_number = $postData->id_number;
            }
            if (isset($postData->po_box)) {
                $po_box = $postData->po_box;
            }
            if (isset($postData->company_name)) {
                $company_name = $postData->company_name;
            }
            if (isset($postData->vat_number)) {
                $vat_number = $postData->vat_number;
            }
            if (isset($postData->remarks)) {
                $remarks = $postData->remarks;
            }
            if (isset($postData->description)) {
                $description = $postData->description;
            }
            if (isset($postData->vat)) {
                $vat = $postData->vat;
            }
            if (isset($postData->address)) {
                $address = $postData->address;
            }
            if (isset($postData->fname)) {
                $fname = $postData->fname;
            }
            if (isset($postData->lname)) {
                $lname = $postData->lname;
            }
            if (isset($postData->phone)) {
                $phone2 = $postData->phone;
            }
            if (isset($postData->dial_code)) {
                $dial_code = $postData->dial_code;
            }


            $data2 = array(
                'fname' => $fname,
                'lname' => $lname,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'job_title' => $job_title,
                'id_number' => $id_number,
                'address' => $address,
                'po_box' => $po_box,
                'company_name' => $company_name,
                'vat_number' => $vat_number,
                'remarks' => $remarks,
                'description' => $description,
                'vat' => $vat,
                'dial_code' => $dial_code,
                'mobile' => $mobile,
                'phone' => $phone2,
                'prefered_language' => $prefered_language,

            );
            if (!empty($data2)) {
                $result = $this->customer_model->update_profile($id, $data2);

                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Profile Updated Successfully', 'english' => $english_l['profile_updated_successfully'], 'arabic' => $arabic_l['profile_updated_successfully'], 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User or Token Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }

    }

    public function update_fcm()
    {

        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));
        $id = "";
        $status = "";


        if (isset($data->email) && isset($data->fcm_token)) {


            $username = $data->email;
            $fcm_token = $data->fcm_token;


            $result = $this->api_model->login_check4($username);
            if (!empty($result)) {

                $status = $result[0]->status;
                $id = $result[0]->id;

                if ($status == '1') {

                    $data2 = array(
                        'fcm_token' => $fcm_token
                    );

                    $msg = $this->api_model->update_user($id,$data2);

                    $codee = http_response_code(200);
                    echo json_encode(array('status' => true, 'code' => $codee, 'message' => 'Fcm Token Updated.', 'result' => $msg));

                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'User not Verified', 'appVersion' => $version, 'result' => ''));
                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'Incorrect Email or Password', 'appVersion' => $version, 'result' => ''));
                // echo "error";
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'Email or Token Not Found', 'appVersion' => $version, 'result' => ''));
        }
    }


    public function fcm_to_email()
    {
        $version = $this->config->item('appVersion');
        $data = json_decode(file_get_contents("php://input"));
        $id = "";
        $status = "";

        if (isset($data->email)) {


            $username = $data->email;

            $result = $this->api_model->login_check4($username);
            if (!empty($result)) {

                $status = $result[0]->status;
                $fcm_token = $result[0]->fcm_token;

                if ($status == '1') {
                    $this->sendPushNotification($fcm_token);
                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'User not Verified', 'appVersion' => $version, 'result' => ''));
                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'Incorrect Email', 'appVersion' => $version, 'result' => ''));
                // echo "error";
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array('status' => false, 'code' => $codee, 'message' => 'Email or Token Not Found', 'appVersion' => $version, 'result' => ''));
        }
    }


    public function sendPushNotification($to = "", $data=false)
    {

        $language = "english";
        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if(!empty($to)) {

            $title = $data['title'] ?? '';
            $body = $data['message'] ?? '';
            $image = '';
            $link = $data['link'] ?? 'pioneerauctions://home';

            $data = [
                "notification" => [
                    "body" => $body,
                    "title" => $title,
                    "image" => $image,
                    "deeplink" => $link 
                ],
                "priority" => "high",
                "data" => [
                    "deeplink" =>$link 
                ],
                "to" => $to
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            // $headers[] = 'Authorization: key=AAAAnpjDCgg:APA91bEgEVbKm_fMLUf5llOyuTQRZk_E_DtPXM9wKuaCkiednhG9hQIS1RhBk2lDjQIrcJ4_I1GNrftwSrG63U3ApbTT_J-fZ930XjN3m-1CFujgMqTI56Ne9SsOXYvvtw5ZbPC7i5C2';
        //   $headers[] = 'Authorization: key=AAAAma6uvDg:APA91bHNUAH7ENmEMozwRL837kM6HCmw-1bqWSD6VixA_KqihIoSqsF07K4sUNZR3dCAf3JfqXBOzvcVQOzjjQyjDNX0UrWFhMX725s4U81gCg7hYTO9ewLrAy9j7TvI0VbdT2K0PE9B';
          
           $headers[] = 'Authorization: key=AAAAASZ7Vwk:APA91bF6wZpCDm86aSx4HpBM6td3rvdytZqlViClzsqgy4OjdonZFlwpPxuZK0Ew1DLhwdGHPvcQ9ddZWI7hiaFfnTJWQIXkr6-VXR4p355fHnC1p-BPBo6j4GXj_Es3EJf4PP7Wyi84';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close($ch);

            $codee = http_response_code(200);
            $response = json_encode(array("status" => true, 'code' => $codee, "message" => 'Push Notification Send Successfully', 'english' => $english_l['push_notification'], 'arabic' => $arabic_l['push_notification'], 'appVersion' => $version));

        }
        return $response;

    }


    public
    function save_profile_image()
    {
        $language = "english";
        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $postData = json_decode(file_get_contents("php://input"));
        $id = validateToken();
        $result3 = $this->db->get_where('users', array('id' => $id))->result_array();


        if (!empty($result3)) {

            if (isset($_FILES['profile_picture']['name']) && !empty($_FILES['profile_picture']['name'])) {

                $image_size = getimagesize($_FILES["profile_picture"]['tmp_name']);
                $data_for_delete_files = $this->customer_model->users_listing_profile($id); // fetch data
                $get_file_name = $this->db->get_where('files', ['id' => $data_for_delete_files[0]['picture']])->row_array();
                $old_data = '';
                if (!empty($data_for_delete_files[0]['picture'])) {
                    $old_data = FCPATH . 'uploads/profile_picture/' . $id . '/' . $get_file_name['name'];
                    $picture = $data_for_delete_files[0]['picture'];
                }


                $path = "uploads/profile_picture/" . $id . "/";
                // make path
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'ico|png|jpg|jpeg';
//                $config['max_size'] = 2000;
//                $config['max_width'] = 1500;
//                $config['max_height'] = 1500;
                $sizes = array();


                if (!empty($_FILES)) {
                    // $files .= $this->uploadFiles_with_path($_FILES,$path);
                    $files = $this->getapimodel->upload2('profile_picture', $config, $sizes, $id);
                    $profile_pic_array['picture'] = implode(',', $files);
                    $user_array = $this->customer_model->update_user($id, $profile_pic_array);


                    //delete existing file
                    if (is_dir($old_data) && !empty($data_for_delete_files[0]['picture'])) {
                        if (unlink($old_data)) {

                        }
                    }
                    if ($user_array) {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Image Uploaded Successfully', 'english' => $english_l['image_uploaded_successfully'], 'arabic' => $arabic_l['image_uploaded_successfully'], 'appVersion' => $version));
                    }

                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Please Select Image', 'english' => $english_l['please_select_image'], 'arabic' => $arabic_l['please_select_image'], 'appVersion' => $version));

                }

            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Please Select Image', 'english' => $english_l['please_select_image'], 'arabic' => $arabic_l['please_select_image'], 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User or Token Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

    public
    function userBids()
    {

        $version = $this->config->item('appVersion');

        $id = validateToken();

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {

            $data = array();
            $output = array();
            $image = '';
            $base_url_img = base_url('uploads/items_documents/');
            $page = 1;
            $posted_data = json_decode(file_get_contents("php://input"), true);
            // $id= $data->id;
            if (isset($posted_data['page']) && !empty($posted_data['page'])) {
                $page = (int)$posted_data['page'];
            }

            $limit = 10;
            $offset = $limit * $page - $limit;

            $query = $this->db->query('Select bid.bid_time,bid.user_id,bid.item_id,bid.auction_id,bid.bid_amount, bid.bid_status ,item.name,item.created_on,item.make,item.model,item.item_images,item.item_attachments,item.category_id  from bid  
             inner join  ( Select max(bid_time) as LatestDate, item_id  from bid where user_id = ' . $id . '  Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id
             LEFT JOIN item ON item.id = bid.item_id  WHERE bid.user_id = ' . $id . ' LIMIT ' . $offset . ', ' . $limit . ';');
            foreach ($query->result_array() as $row) {

                // print_r($this->db->last_query());die();
                $item_id = $row['item_id'];
                $images_ids = explode(",", $row['item_images']);
                $item_detail_url = base_url('items/details/') . urlencode(base64_encode($row['item_id']));
                $make = $this->db->get_where('item_makes', ['id' => $row['make']])->row_array();
                $detail_data = $this->db->get_where('item_fields_data', ['item_id' => $row['item_id'], 'category_id' => $row['category_id']])->result_array();
                $view_count = $this->db->where('item_id', $row['item_id'])->where('auction_id', $row['auction_id'])->from('online_auction_item_visits')->count_all_results();
                $detail_data_str = implode(",", array_column($detail_data, 'value'));
                $model = $this->db->get_where('item_models', ['id' => $row['model']])->row_array();
                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                if (!empty($row['item_images']) || !empty($row['item_attachments'])) {
                    if (isset($files_array[0]['name']) && !empty($files_array[0]['name'])) {
                        $file_name = $files_array[0]['name'];
                        $base_url_img = base_url('uploads/items_documents/') . $item_id . '/' . $file_name;
                        $image = $base_url_img;
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
                    'make' => $make['title'],
                    'model' => $model['title'],
                    'bid_status' => $row['bid_status'],
                    'views_count' => $view_count,
                    'image' => $image,
                );
            }
            if (!empty($output)) {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User Bids', 'english' => $english_l['my_bids'], 'arabic' => $arabic_l['my_bids'], 'appVersion' => $version, 'result' => $output));


            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Bids Found', 'english' => $english_l['no_bids'], 'arabic' => $arabic_l['no_bids'], 'appVersion' => $version));

            }
        } else {

            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));


        }
    }


    public
    function userBids4()
    {

        $version = $this->config->item('appVersion');

        $id = validateToken();
        $result = $this->db->get_where('users', ['id' => $id])->row_array();


        $page = 1;
        $posted_data = json_decode(file_get_contents("php://input"), true);
        // $id= $data->id;
        if (isset($posted_data['page']) && !empty($posted_data['page'])) {
            $page = (int)$posted_data['page'];
        }

        $limit = 10;
        $offset = $limit * $page - $limit;


        if (!empty($result)) {

            $data = array();


            $this->db->select('bid.*,item.id as item_id,item.name,item.price,item.sold,item.item_images,item.category_id,item.feature');
            $this->db->from('bid');
            $this->db->join('item', 'item.id = bid.item_id', 'left');
            $this->db->where('bid.user_id', $id);
            $this->db->order_by('bid.id', 'ASC');
            if (isset($offset) && !empty($offset)) {
                $this->db->offset($offset);
            }
            if (isset($limit) && !empty($limit)) {
                $this->db->limit($limit);
            }
            $query = $this->db->get();
            $bids = $query->result_array();
            $data2 = array();

            foreach ($bids as $key => $record) {
                $images_ids = explode(",", $record['item_images']);
                $item_id = $record['item_id'];
                $last_bid = $this->db->order_by('bid_time', 'DESC')->get_where('bid', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();

                $this->db->select('*');
                $this->db->from('bid');
                $this->db->where('item_id', $record['item_id']);
                $this->db->where('auction_id', $record['auction_id']);
                $count_bids = $this->db->get()->num_rows();


                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                $auction_item = $this->db->select('bid_end_time, sold_status')->get_where('auction_items', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
                $href = 'javascript:void(0)';
                $status = $this->lang->line('not_available');
                if (!empty($auction_item)) {
                    $href = ((strtotime($auction_item['bid_end_time']) > strtotime(date('Y-m-d H:i:s'))) && ($auction_item['sold_status'] == 'not')) ? base_url('auction/online-auction/details/') . $last_bid['auction_id'] . '/' . $last_bid['item_id'] : "javascript:void(0)";
                    $status = ((strtotime($auction_item['bid_end_time']) > strtotime(date('Y-m-d H:i:s'))) && $auction_item['sold_status'] == 'not') ? $this->lang->line('available') : $this->lang->line('not_available');
                }


                if (!empty($record['item_images'])) {
                    $item_images = explode(',', $record['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {
                        if ($i == 0) {

                            $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                            $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        }
                        $i++;
                    }
                    $bids[$key]['item_images'] = $image_get;
                }
                $bids[$key]['total_bids'] = $count_bids;


                $bids[$key]['last_bid'] = $last_bid['bid_amount'];
                $bids[$key]['status_value'] = $status;
                // $bids[$key]['auction_type'] = $this->db->get_where('auctions', ['id' => $bids[$key]['auction_id']])->row_array();

                $heighest_bid_data = $this->oam->item_heighest_bid_data($bids[$key]['item_id'], $bids[$key]['auction_id']);
                $current_bid = "";
                $current_bid_user = "";
                if (!empty($heighest_bid_data)) {
                    $bids[$key]['current_bid'] = $heighest_bid_data['current_bid'];
                    $bids[$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                    $current_bid = $heighest_bid_data['current_bid'];
                    $current_bid_user = $heighest_bid_data['user_id'];
                }


                $bids[$key]['bid_count'] = $this->db->where('item_id', $record['item_id'])->where('auction_id', $record['auction_id'])->from('bid')->count_all_results();

                $auction_details = $this->oam->get_online_auction_items($bids[$key]['auction_id'], 10, 0, $bids[$key]['item_id']);
                $access_type = $this->db->get_where('auctions', ['id' => $record['auction_id']])->row_array();


                $bids[$key]['auction_access_type'] = $access_type['access_type'];
                $bids[$key]['auction_start_time'] = $access_type['start_time'];
                $bids[$key]['auction_expiry_time'] = $access_type['expiry_time'];
                $bids[$key]['bid_start_time'] = $auction_details[0]['bid_start_time'];
                $bids[$key]['bid_start_price'] = $auction_details[0]['bid_start_price'];
                $bids[$key]['bid_end_time'] = $auction_details[0]['bid_end_time'];
                $bids[$key]['lot_no'] = $auction_details[0]['lot_no'];
                $bids[$key]['order_lot_no'] = $auction_details[0]['order_lot_no'];
                $bids[$key]['feature'] = $auction_details[0]['item_feature'];

                $data2['bids_data'][$key] = array(
                    'title' => "",
                    'item_name' => $bids[$key]['name'],
                    'item_id' => $bids[$key]['item_id'],
                    'auction_id' => $bids[$key]['auction_id'],
                    'bid_amount' => $bids[$key]['bid_start_price'],
                    'current_bid' => $current_bid,
                    'current_bid_user' => $current_bid_user,
                    'lot' => $bids[$key]['order_lot_no'],
                    'auction_access_type' => $bids[$key]['auction_access_type'],
                    'bid_count' => $bids[$key]['bid_count'],
                    'image' => $bids[$key]['item_images'],
                    'category_id' => $bids[$key]['category_id'],
                    'item_feature' => $bids[$key]['feature'],
                    'start_time' => $bids[$key]['bid_start_time'],
                    'expiry_time' => $bids[$key]['bid_end_time'],
                    'created_time' => $bids[$key]['bid_start_time']
                );


            }
            $data['bids_data'] = $bids;

            $data2['new'] = 'new';
            $data2['account_active'] = 'active';
            $user_total_deposit = $this->customer_model->user_balance($id);
            $data2['percentage_settings'] = $this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();
            $data2['balance'] = $user_total_deposit['amount'];


            ///// End Auction list //////
            $data2['bids'] = $this->customer_model->user_bids($id);

            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'User Bids', 'appVersion' => $data2));


        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'appVersion' => $version));

        }
    }

    public
    function userBids2()
    {
        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $id = validateToken();
        $result = $this->db->get_where('users', ['id' => $id])->row_array();


        $page = 1;
        $posted_data = json_decode(file_get_contents("php://input"), true);
        // $id= $data->id;
        if (isset($posted_data['page']) && !empty($posted_data['page'])) {
            $page = (int)$posted_data['page'];
        }

        $limit = 300;
        $offset = $limit * $page - $limit;


        if (!empty($result)) {

            $data = array();
            $data2['bids_data'] = array();
            $data['new'] = 'new';
            $data['account_active'] = 'active';
            $user_total_deposit = $this->customer_model->user_balance($id);
            $data['percentage_settings'] = $this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();
            $data['balance'] = $user_total_deposit['amount'];

            $this->db->select('bid.*,item.id as item_id,item.name,item.price,item.sold,item.item_images,item.category_id,item.feature');
            $this->db->from('bid');
            $this->db->where('bid.user_id', $id);
            $this->db->join('item', 'item.id = bid.item_id', 'left');
            //$this->db->join('auction_items', 'auction_items.item_id = bid.item_id','left');
            $this->db->group_by(array("bid.item_id", "bid.auction_id"));
            $this->db->order_by('bid.id', 'desc');
            $query = $this->db->get();
            $num_rows = $query->num_rows();
            $bids = $query->result_array();


            foreach ($bids as $key => $record) {
                $ok = 0;
                $images_ids = explode(",", $record['item_images']);
                $item_id = $record['item_id'];
                $last_bid = $this->db->order_by('bid_time', 'DESC')->get_where('bid', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                $auction_item = $this->db->select('*')->get_where('auction_items', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
                $heighest_bid_data = $this->oam->item_heighest_bid_data($bids[$key]['item_id'], $bids[$key]['auction_id']);

                if ($auction_item['sold_status'] == "sold" && $heighest_bid_data['current_bid_user'] == $id) {
                    $ok = 1;
                }

                if ($auction_item['sold_status'] == "not") {
                    $ok = 1;
                }

                $href = 'javascript:void(0)';
                $status = $this->lang->line('not_available');
                if (!empty($auction_item)) {
                    $href = ((strtotime($auction_item['bid_end_time']) > strtotime(date('Y-m-d H:i:s'))) && ($auction_item['sold_status'] == 'not')) ? base_url('auction/online-auction/details/') . $last_bid['auction_id'] . '/' . $last_bid['item_id'] : "javascript:void(0)";
                    $status = ((strtotime($auction_item['bid_end_time']) > strtotime(date('Y-m-d H:i:s'))) && $auction_item['sold_status'] == 'not') ? $this->lang->line('available') : $this->lang->line('not_available');
                }
                if ($status == "Available") {
                    $ok = 1;
                } else {
                    $ok = 0;
                }


                if ($ok == 1) {


                    if (isset($files_array[0]['name']) && !empty($files_array[0]['name'])) {
                        $file_name = $files_array[0]['name'];

                        $base_url_img = base_url('uploads/items_documents/') . $item_id . '/' . $file_name;
                        $image = $base_url_img;
                    } else {
                        $base_url_img = base_url('assets_admin/images/product-default.jpg');
                        $image = $base_url_img;
                    }


                    $current_bid = "";
                    $current_bid_user = "";
                    if (!empty($heighest_bid_data)) {
                        $bids[$key]['current_bid'] = $heighest_bid_data['current_bid'];
                        $bids[$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                        $current_bid = $heighest_bid_data['current_bid'];
                        $current_bid_user = $heighest_bid_data['user_id'];
                    }


                    $bids[$key]['bid_count'] = $this->db->where('item_id', $record['item_id'])->where('auction_id', $record['auction_id'])->from('bid')->count_all_results();
                    $access_type = $this->db->get_where('auctions', ['id' => $record['auction_id']])->row_array();


                    $bids[$key]['image'] = $image;
                    $bids[$key]['last_bid'] = $last_bid['bid_amount'];
                    $bids[$key]['status_value'] = $status;

                    $bids[$key]['auction_access_type'] = $access_type['access_type'];
                    $bids[$key]['auction_start_time'] = $access_type['start_time'];
                    $bids[$key]['auction_expiry_time'] = $access_type['expiry_time'];

                    $bids[$key]['bid_start_time'] = $auction_item['bid_start_time'];
                    $bids[$key]['bid_start_price'] = $auction_item['bid_start_price'];
                    $bids[$key]['bid_end_time'] = $auction_item['bid_end_time'];
                    $bids[$key]['lot_no'] = $auction_item['lot_no'];
                    $bids[$key]['order_lot_no'] = $auction_item['order_lot_no'];
                    $bids[$key]['feature'] = $record['feature'];


                    $data3 = array(
                        'title' => "",
                        'item_name' => $bids[$key]['name'],
                        'item_id' => $bids[$key]['item_id'],
                        'auction_id' => $bids[$key]['auction_id'],
                        'bid_amount' => $bids[$key]['start_price'],
                        'current_bid' => $current_bid,
                        'current_bid_user' => $current_bid_user,
                        'lot' => $bids[$key]['order_lot_no'],
                        'auction_access_type' => $bids[$key]['auction_access_type'],
                        'bid_count' => $bids[$key]['bid_count'],
                        'image' => $bids[$key]['image'],
                        'category_id' => $bids[$key]['category_id'],
                        'item_feature' => $bids[$key]['feature'],
                        'start_time' => $bids[$key]['bid_start_time'],
                        'expiry_time' => $bids[$key]['bid_end_time'],
                        'created_time' => $bids[$key]['bid_start_time']
                    );

                    array_push($data2['bids_data'], $data3);
                }

            }


            $data['bids_data'] = $bids;

            ///// End Auction list //////
            $data['bids'] = $this->customer_model->user_bids($id);

            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'User Bids', 'english' => $english_l['my_bids'], 'arabic' => $arabic_l['my_bids'], 'appVersion' => $data2));


        } else {

            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }

    }

    public
    function wishList()
    {


        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $data_array = json_decode(file_get_contents("php://input"), true);
        $posted_data = json_decode(file_get_contents("php://input"));
        $this->form_validation->set_data($data_array);


        $this->load->library('form_validation');
        $rules = array(
            array(
                'field' => 'item_id',
                'label' => 'Item Id',
                'rules' => 'trim|required'),
            array(
                'field' => 'auction_id',
                'label' => 'Auction Id',
                'rules' => 'trim|required'),
            array(
                'field' => 'auction_item_id',
                'label' => 'Auction Item Id',
                'rules' => 'trim|required')
        );


        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Item id, Auction ID And Auction Item Id are Required', 'english' => $english_l['auction_id_or_item_id_not_found'], 'arabic' => $arabic_l['auction_id_or_item_id_not_found'], 'appVersion' => $version));

        } else {


            $id = validateToken();


            $result = $this->db->get_where('users', ['id' => $id])->row_array();

            if (!empty($result)) {

                $data['item_id'] = $posted_data->item_id;
                $data['user_id'] = $id;
                $data['auction_id'] = $posted_data->auction_id;
                $data['auction_item_id'] = $posted_data->auction_item_id;

                $data2 = $this->db->get_where('favorites_items', ['user_id' => $id, 'item_id' => $data['item_id']])->row_array();

                if (!empty($data2)) {
                    $result = $this->api_model->deleteWish($id, $data['item_id']);

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => true, 'code' => $codee, "message" => 'ITEM DELETED FROM WISH LIST', 'english' => $english_l['remove_favorite'], 'arabic' => $arabic_l['remove_favorite'], 'appVersion' => $version, 'result' => $data));

                } else {
                    // $id= validateToken();
                    $results = $this->api_model->add_wishlist($data);//update wish list

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => true, 'code' => $codee, "message" => 'ITEM ADDED TO WISH LIST', 'english' => $english_l['add_favorite'], 'arabic' => $arabic_l['add_favorite'], 'appVersion' => $version, 'result' => $data));

                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'appVersion' => $version));

            }
        }

    }

    public
    function getWishList()
    {
        $version = $this->config->item('appVersion');
        $id = validateToken();


        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {


            $page = 1;
            $file_name = "";
            $base_url_img = "";
            $posted_data = json_decode(file_get_contents("php://input"), true);
            if (isset($posted_data['page']) && !empty($posted_data['page'])) {
                $page = $posted_data['page'];
            }
            $date['time'] = date('Y-m-d H:i:s', time());
            $output = array();
            $auction_data = array();
            $limit = 10;
            $offset = $limit * $page - $limit;
            $this->db->join('item', 'item.id = favorites_items.item_id', 'left');
            // $this->db->join('auctions','auctions.category_id = item.category_id', 'left');
            $this->db->offset($offset);
            $this->db->limit($limit);
            $record = $this->db->get_where('favorites_items', ['user_id' => $id])->result_array();
            // print_r($record);die();
            foreach ($record as $value) {
                $item_id = $value['item_id'];
                $images_ids = explode(",", $value['item_images']);

                $item_detail_url = base_url('items/details/') . urlencode(base64_encode($value['item_id']));
                $make = $this->db->get_where('item_makes', ['id' => $value['make']])->row_array();
                $auction_items_row = $this->db->get_where('auction_items', ['item_id' => $value['item_id']])->row_array();
                $detail_data = $this->db->get_where('item_fields_data', ['item_id' => $value['item_id'], 'category_id' => $value['category_id']])->result_array();
                $detail_data_str = implode(",", array_column($detail_data, 'value'));
                $model = $this->db->get_where('item_models', ['id' => $value['model']])->row_array();
                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                // $image =  $base_url_img;
                // $image = 'No image Found';
                if (!empty($value['item_images']) || !empty($value['item_attachments'])) {
                    if (isset($files_array[0]['name']) && !empty($files_array[0]['name'])) {
                        $file_name = $files_array[0]['name'];
                        $base_url_img = base_url('uploads/items_documents/') . $item_id . '/' . $file_name;
                    } else {
                        // $image = base_url('assets_admin/images/product-default/');
                        // $image = 'No image Found';
                    }
                }
                if (isset($auction_items_row) && !empty($auction_items_row)) {

                    $auction_row = $this->db->get_where('auctions', ['id' => $auction_items_row['auction_id']])->row_array();
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
                    'auction_detail' => $auction_data,
                    'item_status' => $value['item_status'],
                    'image' => $file_name,
                    'complete_image' => $base_url_img,
                );

            }

            if (!empty($output)) {

                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Wish list found', 'appVersion' => $version, 'result' => $output, 'time' => $date));


            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Wish List Found', 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'appVersion' => $version));

        }
    }

    public
    function getWishList22()
    {
        $version = $this->config->item('appVersion');
        $id = validateToken();




        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {


            $page = 1;
            $file_name = "";
            $posted_data = json_decode(file_get_contents("php://input"), true);
            if (isset($posted_data['page']) && !empty($posted_data['page'])) {
                $page = $posted_data['page'];
            }
            $date['time'] = date('Y-m-d H:i:s', time());
            $output = array();
            $auction_data = array();
            $limit = 10;
            $offset = $limit * $page - $limit;
            $this->db->join('item', 'item.id = favorites_items.item_id', 'left');
//             $this->db->join('auctions','auctions.category_id = item.category_id', 'left');
            $this->db->offset($offset);
            $this->db->limit($limit);
            $data['record'] = $this->db->get_where('favorites_items', ['user_id' => $id])->result_array();

            foreach ($data['record'] as $key => $value) {
                $item_id = $value['item_id'];
                $auction_id = $value['auction_id'];
                $images_ids = explode(",", $value['item_images']);

                $item_detail_url = base_url('items/details/') . urlencode(base64_encode($value['item_id']));
                $make = $this->db->get_where('item_makes', ['id' => $value['make']])->row_array();
                $auction_items_row = $this->db->get_where('auction_items', ['item_id' => $value['item_id']])->row_array();
                $detail_data = $this->db->get_where('item_fields_data', ['item_id' => $value['item_id'], 'category_id' => $value['category_id']])->result_array();
                $detail_data_str = implode(",", array_column($detail_data, 'value'));
                $model = $this->db->get_where('item_models', ['id' => $value['model']])->row_array();
                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                // $image =  $base_url_img;
                // $image = 'No image Found';
                $current_lot = $this->get_items_details($item_id, $auction_id);

                if (count($current_lot) > 0) {
                    $data['record'][$key]['lot_no'] = $current_lot[0]['lot_no'];
                    $data['record'][$key]['order_lot_no'] = $current_lot[0]['order_lot_no'];
//                    $data['record'][$key]['detail'] = "";
//                    $data['record'][$key]['terms'] = "";
                    $data['record'][$key]['item_feature'] = $current_lot[0]['item_feature'];
                }


                $heighest_bid_data = $this->oam->item_heighest_bid_data($item_id, $auction_id);
                $current_bid = "";
                $current_bid_user = "";
                if (!empty($heighest_bid_data)) {
                    $data['record'][$key]['current_bid'] = $heighest_bid_data['current_bid'];
                    $data['record'][$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                    $current_bid = $heighest_bid_data['current_bid'];
                    $current_bid_user = $heighest_bid_data['user_id'];
                }


//                $access_type = $this->db->get_where('auctions', ['id' => $value['auction_id']])->row_array();
//
//                $data['record'][$key]['auction_access_type'] = $access_type['access_type'];
//                $data['record'][$key]['auction_start_time'] = $access_type['start_time'];
//                $data['record'][$key]['auction_expiry_time'] = $access_type['expiry_time'];
                $data['record'][$key]['item_start_time'] = $auction_items_row['bid_start_time'];
                $data['record'][$key]['item_end_time'] = $auction_items_row['bid_end_time'];


                $data['record'][$key]['bid_count'] = $this->db->where('item_id', $value['item_id'])->where('auction_id', $value['auction_id'])->from('bid')->count_all_results();


                if (!empty($value['item_images'])) {
                    $item_images = explode(',', $value['item_images']);
                    $i = 0;
                    $image_get = array();
                    while ($i < count($item_images)) {
                        if ($i == 0) {

                            $image = $this->db->get_where('files', ['id' => $item_images[$i]])->row_array();
                            //$image_get[$i] = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                            $image_get = trim(base_url(), '/') . trim($image['path'], '.') . $image['name'];
                        }
                        $i++;
                    }
                    $data['record'][$key]['item_images'] = $image_get;
                }


//                $data2[$key] = array(
//                    'title' => "",
//                    'item_name' => $data['record'][$key]['item_name'],
//                    'item_id' => $data['record'][$key]['item_id'],
//                    'auction_id' => $data['record'][$key]['auction_id'],
//                    'bid_amount' => $data['record'][$key]['bid_start_price'],
//                    'current_bid' => $current_bid,
//                    'current_bid_user' => $current_bid_user,
//                    'lot' => $data['record'][$key]['order_lot_no'],
//                    'auction_access_type' => $data['record'][$key]['access_type'],
//                    'bid_count' => $data['record'][$key]['bid_count'],
//                    'image' => $data['record'][$key]['image'],
//                    'category_id' => $data['record'][$key]['category_id'],
//                    'item_feature' => $data['record'][$key]['item_feature'],
//                    'start_time' => $data['record'][$key]['bid_start_time'],
//                    'expiry_time' => $data['record'][$key]['bid_end_time'],
//                    'created_time' => $data['record'][$key]['bid_start_time']
//                );


            }


            if (!empty($data)) {

                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Wish list found', 'appVersion' => $version, 'result' => $data, 'time' => $date));


            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Wish List Found', 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'appVersion' => $version));

        }
    }

    public
    function getWishList2()
    {
        $version = $this->config->item('appVersion');
        $id = validateToken();


        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {

            $userid = $id;
            $data = array();

            $page = 1;
            $posted_data = json_decode(file_get_contents("php://input"), true);
            if (isset($posted_data['page']) && !empty($posted_data['page'])) {
                $page = $posted_data['page'];
            }
            $limit = 10;
            $offset = $limit * $page - $limit;
            $data2 = array();


            $this->db->select('favorites_items.*,item.id as item_id,item.name,item.detail,item.price,item.sold,item.item_images,item.feature,item.category_id');
            $this->db->from('favorites_items');
            $this->db->join('item', 'item.id = favorites_items.item_id', 'left');
            //$this->db->offset($offset);
            //$this->db->limit($limit);
            $this->db->where('favorites_items.user_id', $userid);
            $this->db->where('item.sold', 'no');
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get();
            $data['favoriteItems'] = $query->result_array();
            foreach ($data['favoriteItems'] as $key => $record) {
                $status_value = $this->lang->line('not_available');
                $images_ids = explode(",", $record['item_images']);
                $item_id = $record['item_id'];
                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);

                $language = "english";
                if (isset($files_array[0]['name']) && !empty($files_array[0]['name'])) {
                    $file_name = $files_array[0]['name'];
                    $base_url_img = base_url('uploads/items_documents/') . $item_id . '/' . $file_name;
                    $image = $base_url_img;
                } else {
                    $base_url_img = base_url('assets_admin/images/product-default.jpg');
                    $image = $base_url_img;
                }
                $data['favoriteItems'][$key]['image'] = $image;
                $check_expired = $this->db->get_where('auction_items', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
                $item_expiry_date = @$check_expired['bid_end_time'];
                $action = '';
                if (($item_expiry_date > date('Y-m-d H:i:s')) && ($check_expired['sold_status'] == 'not')) {
                    $status_value = $this->lang->line('available');
                    $auction_id = $record['auction_id'];
                    $action .= base_url("api/v1/online-auction/details/$auction_id/$item_id");
                }

                $data['favoriteItems'][$key]['auction_item_status'] = $status_value;
                $data['favoriteItems'][$key]['action'] = $action;

                $data['favoriteItems'][$key]['bid_end_time'] = $check_expired['bid_end_time'];
                $data['favoriteItems'][$key]['bid_start_time'] = $check_expired['bid_start_time'];
                $data['favoriteItems'][$key]['bid_start_price'] = $check_expired['bid_start_price'];
                $data['favoriteItems'][$key]['lot_no'] = $check_expired['lot_no'];
                $data['favoriteItems'][$key]['order_lot_no'] = $check_expired['order_lot_no'];

                $data['favoriteItems'][$key]['bid_count'] = $this->db->where('item_id', $record['item_id'])->where('auction_id', $record['auction_id'])->from('bid')->count_all_results();
                $access_type = $this->db->get_where('auctions', ['id' => $record['auction_id']])->row_array();

                $data['favoriteItems'][$key]['access_type'] = $access_type['access_type'];
                $data['favoriteItems'][$key]['auction_start_time'] = $access_type['start_time'];
                $data['favoriteItems'][$key]['auction_expiry_time'] = $access_type['expiry_time'];


                $heighest_bid_data = $this->oam->item_heighest_bid_data($record['item_id'], $record['auction_id']);
                $current_bid = "";
                $current_bid_user = "";
                if (!empty($heighest_bid_data)) {
                    $data['favoriteItems'][$key]['current_bid'] = $heighest_bid_data['current_bid'];
                    $data['favoriteItems'][$key]['current_bid_user'] = $heighest_bid_data['user_id'];

                    $current_bid = $heighest_bid_data['current_bid'];
                    $current_bid_user = $heighest_bid_data['user_id'];
                }


                $data2[$key] = array(
                    'title' => "",
                    'item_name' => $data['favoriteItems'][$key]['name'],
                    'item_id' => $data['favoriteItems'][$key]['item_id'],
                    'auction_id' => $data['favoriteItems'][$key]['auction_id'],
                    'bid_amount' => $data['favoriteItems'][$key]['bid_start_price'],
                    'current_bid' => $current_bid,
                    'current_bid_user' => $current_bid_user,
                    'lot' => $data['favoriteItems'][$key]['order_lot_no'],
                    'auction_access_type' => $data['favoriteItems'][$key]['access_type'],
                    'bid_count' => $data['favoriteItems'][$key]['bid_count'],
                    'image' => $data['favoriteItems'][$key]['image'],
                    'category_id' => $data['favoriteItems'][$key]['category_id'],
                    'item_feature' => $data['favoriteItems'][$key]['feature'],
                    'start_time' => $data['favoriteItems'][$key]['bid_start_time'],
                    'expiry_time' => $data['favoriteItems'][$key]['bid_end_time'],
                    'created_time' => $data['favoriteItems'][$key]['bid_start_time'],
                    'auction_item_status' => $data['favoriteItems'][$key]['auction_item_status']
                );

            }

            if (!empty($data)) {

                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Wish list found', 'english' => $english_l['my_wishlist'], 'arabic' => $arabic_l['my_wishlist'], 'appVersion' => $version, 'result' => $data2));


            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Wish List Found', 'english' => $english_l['no_data_available'], 'arabic' => $arabic_l['no_data_available'], 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }


    public
    function docs()
    {

        $version = $this->config->item('appVersion');
        $id = validateToken();
        $result2 = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result2)) {

            $data['userId'] = $id;
            $data['docList'] = $this->db->order_by('name', 'ASC')->get('documents_type')->result_array();

            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Documents', 'appVersion' => $version, 'result' => $data));

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'File Not Found', 'appVersion' => $version));

        }
    }

    public
    function docsLoad()
    {

        $version = $this->config->item('appVersion');
        $userId = validateToken();
        $result2 = $this->db->get_where('users', ['id' => $userId])->row_array();
        $posted_data = json_decode(file_get_contents("php://input"));

        if (isset($posted_data->catId)) {
            $catId = $posted_data->catId;
        } else {
            $catId = null;
        }
        if (!empty($result2)) {


            if (!empty($catId)) {
                $docs = $this->db->get_where('user_documents', [
                    'user_id' => $userId,
                    'document_type_id' => $catId
                ])->result_array();
            } else {
                $docs = $this->db->get_where('user_documents', [
                    'user_id' => $userId
                ])->result_array();
            }


            $userDocs = [];
            if (!empty($docs)) {
                foreach ($docs as $doc_val) {

                    $fileIds = explode(",", $doc_val['file_id']);
                    $fileIds = array_filter($fileIds, 'is_numeric');
                    if (!empty($fileIds)) {
                        $userDocs['documents'] = $this->db->where_in('id', $fileIds)->get('files')->result_array();

                        foreach ($userDocs['documents'] as $key => $val) {

                            if (!empty($val['name'])) {
                                $image_get = trim(base_url(), '/') . trim($val['path'], '.') . $val['name'];
                                $userDocs['documents'][$key]['image_url'] = $image_get;

                            }
                        }

                    }
                }
                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Success', 'appVersion' => $version, 'result' => $userDocs));

            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Data Not Found', 'appVersion' => $version));

            }


        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'File Not Found', 'appVersion' => $version));

        }

    }


    public
    function save_user_documents()
    {
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);
        $version = $this->config->item('appVersion');
        $user_id = validateToken();
        $result2 = $this->db->get_where('users', ['id' => $user_id])->row_array();
        if (!empty($result2)) {
            if (!empty($_FILES['images']['name']) && !empty($this->input->post('catid'))) {
                $catid = $this->input->post('catid');


                $docIds_array = array();
                $ids_concate = '';
                $result_array = $this->items_model->get_customersDocs($user_id, $catid);
                if (isset($result_array) && !empty($result_array)) {
                    $docIds_array = explode(',', $result_array[0]['file_id']);
                    if (!empty($docIds_array) && !empty($result_array[0]['file_id'])) {
                        $ids_concate = $result_array[0]['file_id'] . ",";
                    }
                }

                $path = './uploads/users_documents/';
                if (!is_dir($path . $user_id)) {
                    mkdir($path . $user_id, 0777, TRUE);
                }

                $config['allowed_types'] = 'ico|png|jpg|jpeg|pdf|doc|docx|xlsx|xls|ppt|pptx|txt';
//                $config['max_size'] = 2000;

                $sizes = [];
                $path = $path . $user_id . '/';
                $config['upload_path'] = $path;
                $uploaded_file_ids = $this->getapimodel->multiUpload2('images', $config, $sizes, $user_id);
                if (isset($uploaded_file_ids['error'])) {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, 'appVersion' => $version, 'message' => $uploaded_file_ids['error']));


                } else {
                    // upload and save to database
                    $uploaded_file_ids = array_filter($uploaded_file_ids, 'is_numeric');
                    $uploaded_file_ids = implode(',', $uploaded_file_ids);
                    $update = [
                        'file_id' => $ids_concate . $uploaded_file_ids
                    ];
                    $update['user_Id'] = $user_id;
                    $update['document_type_id'] = $catid;
                    $saveDoc = $this->db->get_where('user_documents', ['user_Id' => $user_id, 'document_type_id' => $catid])->result_array();
                    if (!empty($saveDoc)) {
                        $result = $this->db->update('user_documents', $update, ['user_Id' => $user_id, 'document_type_id' => $catid]);
                        if ($result == 'true') {

                            $codee = http_response_code(200);
                            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Document Updated', 'english' => $english_l['uploaded_documents'], 'arabic' => $arabic_l['uploaded_documents'], 'appVersion' => $version));


                        }
                    } else {
                        $result = $this->db->insert('user_documents', $update);
                        if ($result == 'true') {

                            $codee = http_response_code(200);
                            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Document Added', 'english' => $english_l['uploaded_documents'], 'arabic' => $arabic_l['uploaded_documents'], 'appVersion' => $version));


                        }
                    }


                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'File Not Found', 'english' => $english_l['please_select_doc'], 'arabic' => $arabic_l['please_select_doc'], 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Select File Type', 'english' => $english_l['please_select_doc'], 'arabic' => $arabic_l['please_select_doc'], 'appVersion' => $version));

        }
    }


    public
    function delete_customerDocs()
    {

        $version = $this->config->item('appVersion');
        $user_id = validateToken();
        $result2 = $this->db->get_where('users', ['id' => $user_id])->row_array();
        $posted_data = json_decode(file_get_contents("php://input"));

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if ($posted_data) {
            if (!empty($result2)) {

                $d = $posted_data->catid;
                $attach_name = $posted_data->file_to_be_deleted;
                $file_array = $this->files_model->get_file_byName($attach_name);
                $userId = $user_id;
                $catid = $posted_data->catid;
                if (isset($file_array) && !empty($file_array)) {


                    $result_array = $this->items_model->get_customersDocs($userId, $catid);
                    if (isset($result_array) && !empty($result_array)) {
                        $docIds_array = explode(',', $result_array[0]['file_id']);
                        if (!empty($docIds_array)) {
                            $str = $result_array[0]['file_id'];
                        }
                    }

                    $updated_str = $this->removeItemString($str, $file_array[0]['id']);


                    $update = [
                        'file_id' => $updated_str,
                    ];
                    $result_update = $this->db->update('user_documents', $update, ['user_id' => $userId, 'document_type_id' => $d]);
                    $get_image = $this->db->get_where('files', ['id' => $result_array[0]['file_id']])->row_array();

                    $path = FCPATH . "uploads/users_documents/" . $userId . "/";

                    unlink(FCPATH . 'uploads/users_documents/' . $userId . "/" . $get_image['name']);

                    $result = $this->db->delete('files', ['name' => $attach_name]);
                    if ($result) {

                        $codee = http_response_code(200);
                        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Deleted Successfully', 'english' => $english_l['item_deleted'], 'arabic' => $arabic_l['item_deleted'], 'appVersion' => $version));

                    } else {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Cant Delete', 'english' => $english_l['error'], 'arabic' => $arabic_l['error'], 'appVersion' => $version));

                    }

                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'File Not Found', 'english' => $english_l['id_not_found'], 'arabic' => $arabic_l['id_not_found'], 'appVersion' => $version));

                }


            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Foound', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Foound', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

//Profile End


//Payments


    public
    function deposit()
    {

        $version = $this->config->item('appVersion');
        $id = validateToken();

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {


            $data = array();
            $data['deposit_active'] = 'active';
            $data['new'] = 'new';
            $user_total_deposit = $this->customer_model->user_balance($id);
            $data['balance'] = $user_total_deposit['amount'];
            $data['percentage_settings'] = $this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();
            $data['balance'] = $user_total_deposit['amount'];
            ///// End Auction list //////
            $data['bids'] = $this->customer_model->user_bids($id);
            $data['setting'] = $this->db->get_where('settings', ['code_key' => 'min_deposit'])->row_array();
            $data['lat'] = "25.238820";
            $data['long'] = "55.371237";
            ///// End Auction list //////

            $data['list'] = $this->db->get_where('users', ['id' => $id])->row_array();
//        print_r($data);

            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Deposit Result', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $data));


        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

    public
    function cradit_card()
    {
        $version = $this->config->item('appVersion');
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);
        $id = validateToken();
        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {


            $user = $this->db->get_where('users', ['id' => $id])->row_array();
            $data = json_decode(file_get_contents("php://input"));
            if (empty($user['mobile']) || empty($user['email']) || empty($user['address']) || empty($user['fname']) || empty($user['lname']) || empty($user['city']) || empty($user['state'])) {
                $redirect = (isset($_GET['r'])) ? urldecode($_GET['r']) : array();
                if (!empty($redirect)) {
                    $redirect = json_decode($redirect);
                }
                $rurl = strtok($data->rurl, '?');
                array_push($redirect, $rurl);
                $json_redirect = json_encode($redirect);
                $redirect = urlencode($json_redirect);

                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Please Complete Profile Details Before Making a transaction', 'english' => $english_l['please_update_your_profile'], 'arabic' => $arabic_l['please_update_your_profile'], 'appVersion' => $version, 'result' => $redirect));

            } else {

                $order_id = $id;
                $redirect = (isset($_GET['r'])) ? $_GET['r'] : array();


                if (!empty($redirect)) {
                    $this->session->set_flashdata('redirect', $redirect);
                }
                $lan = $this->language;
                $lan = ucfirst($lan);


                $invoice2 = [
                    "tran_type" => "auth",
                    "tran_class" => "ecom",
                    "cart_id" => 'TRN' . mt_rand(),
                    "cart_description" => "Auction Deposit",
                    "cart_currency" => "AED",
                    "cart_amount" => $data->amount,
                    "hide_shipping" => TRUE,
                    "customer_details" => [
                        "name" => $user['fname'] . ' ' . $user['lname'],
                        "email" => $user['email'],
                        "street1" => $user['address'],
                        "city" => $user['city'],
                        "state" => "DU",
                        "country" => "AE",
                        "ip" => $data->ip_address
                    ],
                    //"callback"=> base_url('customer/paytabsCallback'),
                    "return" => base_url('getapi/paytabsReturnURL')
                ];


//                $this->load->library('Paytabs3');
//                $response = $this->paytabs3->runPaytabs($invoice2);

                $this->load->library('Paytabs2');
                $response = $this->paytabs2->runPaytabs($invoice2);

                $response = json_decode($response);

                //print_r($response);return;

                if (isset($response->tran_ref) && !empty($response->tran_ref)) {
                    $transaction_data['transaction_id'] = $response->tran_ref;
                    $transaction_data['user_id'] = $id;
                    $transaction_data['deposit_type'] = 'permanent';
                    $transaction_data['payment_type'] = 'card';
                    $transaction_data['amount'] = $data->amount;
                    $transaction_data['created_on'] = date('Y-m-d H:i:s');
                    // $transaction_data['created_by'] = $this->loginUser->id;
                    $transaction_data['status'] = 'active';
                    $transaction_data['deleted'] = 'no';

                    $this->db->insert('auction_deposit', $transaction_data);

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => true, 'code' => $codee, "message" => 'package_recharge_success', 'english' => $english_l['package_recharge_success'], 'arabic' => $arabic_l['package_recharge_success'], 'appVersion' => $version, 'redirect' => $response->redirect_url, 'showModel' => false));


                } else {

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'payment_failed_to_make', 'english' => $english_l['payment_failed_to_make'], 'arabic' => $arabic_l['payment_failed_to_make'], 'appVersion' => $version, 'redirect' => base_url('customer/deposit')));

                }
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

    public
    function paytabsReturnURL()
    {

        $red = "";
        $resp_status = "";
        $tranRef = "";


        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if (isset($_POST)) {
            $data = $_POST;
            if ($data) {
                $resp_status = $data['respStatus'];
                $tranRef = $data['tranRef'];
            }
        } else {
            $data = json_decode(file_get_contents("php://input"));
            if ($data) {
                $resp_status = $data->respStatus;
                $tranRef = $data->tranRef;
            }
        }


        $version = $this->config->item('appVersion');


        if (!empty($tranRef)) {

            if ($resp_status == 'A') {
                $detail = json_encode($data);
                //if payment successful
                $deposit = $this->db->get_where('auction_deposit', ['transaction_id' => $tranRef]);
                if ($deposit->num_rows() > 0) {
                    // new deposit payment
                    $deposit = $deposit->row_array();
                    if ($deposit['transaction_id'] == $data['tranRef']) {
                        // update order payment status to successful
                        $this->db->update('auction_deposit', ['status' => 'approved', 'detail' => $detail], ['id' => $deposit['id']]);

                        $codee = http_response_code(200);
                        echo json_encode(array("status" => true, 'code' => $codee, 'appVersion' => $version, "message" => 'Paid Successfully', 'english' => $english_l['payment_made_successfully'], 'arabic' => $arabic_l['payment_made_successfully'], 'Transaction ID' => $data['cartId']));


                    } else {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => false, 'code' => $codee, 'appVersion' => $version, "message" => 'Transaction ID Not Found', 'english' => $english_l['id_not_found'], 'arabic' => $arabic_l['id_not_found'], 'Transaction ID' => $data['cartId']));


                    }
                } else {
                    // existing order payment
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, 'appVersion' => $version, "message" => 'Payment Already Found', 'english' => $english_l['payment_failed_to_make'], 'arabic' => $arabic_l['payment_failed_to_make'], 'Transaction ID' => $data['cartId']));


                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, 'appVersion' => $version, "message" => $data['respMessage'], 'Transaction ID' => $data['cartId']));

            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'HTTPS Request Not Found ', 'appVersion' => $version));
        }

    }

    public
    function add_bank_slip()
    {

        $version = $this->config->item('appVersion');
        $id = validateToken();
        $result2 = $this->db->get_where('users', ['id' => $id])->row_array();

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $posted_data = $this->input->post();


        if (!empty($result2)) {


            if (!empty($_FILES['slip']['name'])) {
                // print_r($_FILES['slip']);die();

                // make path
                $path = './uploads/bank_slips/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'ico|png|jpg|jpeg|pdf|doc|docx|txt|xls';
                $config['max_size'] = 2000;
                $uploaded_file_ids = $this->getapimodel->upload2('slip', $config, $sizes = array(), $id);

                // print_r($uploaded_file_ids);die();
                if (isset($uploaded_file_ids['error'])) {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Invalid File', 'english' => $english_l['please_select_doc'], 'arabic' => $arabic_l['please_select_doc'], 'appVersion' => $version, 'result' => $uploaded_file_ids['error']));

                } else {

                    $posted_data['user_id'] = $id;
                    $posted_data['slip'] = implode(',', $uploaded_file_ids);
                    $posted_data['created_on'] = date('Y-m-d H:i:s');
                    $result = $this->db->insert('bank_deposit_slip', $posted_data);
                    $inserted_id = $this->db->insert_id();
                    if ($result) {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Slip Added Successfully', 'english' => $english_l['slip_has_been_added_successfully'], 'arabic' => $arabic_l['slip_has_been_added_successfully'], 'appVersion' => $version));

                    } else {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Slip Failed To added', 'english' => $english_l['slip_has_een_failed_to_add'], 'arabic' => $arabic_l['slip_has_een_failed_to_add'], 'appVersion' => $version));

                    }
                }


            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'File Not Found', 'english' => $english_l['no_file_choosen'], 'arabic' => $arabic_l['no_file_choosen'], 'appVersion' => $version));

            }

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Token Not Valid', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

    public
    function item_deposit()
    {

        $version = $this->config->item('appVersion');
        $id = validateToken();

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {

            $data = array();
            $data['new'] = 'new';
            $data['security_active'] = 'active';
            $today = date('Y-m-d H:i:s');
            $data['auctions'] = $this->db->get_where('auctions', [
                'status' => 'active',
                'access_type' => 'online',
                'start_time <=' => $today,
                'expiry_time >=' => $today
            ])->result_array();

            $data['item_deposit_active'] = 'active';
            $data['setting'] = $this->db->get('settings')->row_array();

            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Deposit Result', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $data));

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Invalid User or Token', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

    public
    function get_ai_list()
    {


        $version = $this->config->item('appVersion');
        $id = validateToken();


        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {

            $data = json_decode(file_get_contents("php://input"));
            $language = $this->language;
            if ($data) {

                $user_id = $id;


                $item_array = array();
                $item_ids = $this->db->select('item_id')->get_where('auction_item_deposits', ['user_id' => $user_id])->result_array();
                foreach ($item_ids as $key => $value) {
                    array_push($item_array, $value['item_id']);
                }

                // print_r($item_array);die();
                $auction_id = $data->id;
                $this->db->select('auction_items.*, item.name as item_name');
                $this->db->from('auction_items');
                $this->db->join('item', 'auction_items.item_id = item.id', 'left');
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

                if ($auction_items->num_rows() > 0) {

                    $auction_items = $auction_items->result_array();

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Success', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'],'appVersion' => $version, 'result' => $auction_items));

                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Data Found', 'english' => $english_l['no_data_available'], 'arabic' => $arabic_l['no_data_available'], 'appVersion' => $version));

                }
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Invalid User or Token', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

    public
    function get_ai_deposit()
    {
        $version = $this->config->item('appVersion');
        $id = validateToken();

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $result = $this->db->get_where('users', ['id' => $id])->row_array();

        if (!empty($result)) {

            $data = json_decode(file_get_contents("php://input"));
            if ($data) {
                //return print_r($data);
                $auction_item_id = $data->id;
                $auction_item = $this->db->get_where('auction_items', [
                    'id' => $auction_item_id,
                    'status' => 'active',
                    'security' => 'yes',
                    'deposit !=' => null
                ])->row_array();

                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Success', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $auction_item));
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Id Not Found', 'english' => $english_l['no_data_available'], 'arabic' => $arabic_l['no_data_available'],'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Invalid Token or User', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }

    public
    function bankDetail()
    {
        $version = $this->config->item('appVersion');

        $data['bank_info'] = $this->db->get('bank_info')->row_array();
        $data['contact'] = $this->db->get('contact_us')->row_array();

        if (!empty($data)) {

            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Success', 'appVersion' => $version, 'result' => $data));


        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Data Found', 'appVersion' => $version));

        }
    }


//end payment

//inventory

    public
    function inventory()
    {
        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        $id = validateToken2();
        $result = $this->db->get_where('users', ['id' => $id])->row_array();


        if (!empty($result)) {

            $data = array();
            $userid = $id;
            $data['new'] = 'new';
            $data['inventory_active'] = 'active';
            $this->db->select('item.*');
            $this->db->from('item');
            $this->db->where('seller_id', $userid);
            $query = $this->db->get();
            $data['inventory'] = $query->result_array();
            foreach ($data['inventory'] as $key => $record) {
                $status_value = $this->lang->line('available');
                $images_ids = explode(",", $record['item_images']);
                $item_id = $record['id'];
                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                // print_r($files_array);die();
                $language = $this->language;
                if (isset($files_array[0]['name']) && !empty($files_array[0]['name'])) {
                    $file_name = $files_array[0]['name'];
                    $base_url_img = base_url('uploads/items_documents/') . $item_id . '/' . $file_name;
                    $image = $base_url_img;
                } else {
                    $base_url_img = base_url('assets_admin/images/product-default.jpg');
                    $image = $base_url_img;
                }
                if ($record['in_auction'] == 'no') {
                    $status_value = [
                        'english' =>  $english_l['under_process'],
                        'arabic' => $arabic_l['under_process']
                    ];

                    // $status_value = $this->lang->line('under_process');
                }
                if ($record['sold'] == 'return') {

                    $status_value = [
                        'english' =>  $english_l['returned'],
                        'arabic' => $arabic_l['returned']
                    ];

                    //$status_value = $this->lang->line('returned');
                }
                if ($record['sold'] == 'yes') {

                    $status_value = [
                        'english' =>  $english_l['sold'],
                        'arabic' => $arabic_l['sold']
                    ];

                    //$status_value = $this->lang->line('sold');
                    $sold_item = $this->db->get_where('sold_items', ['item_id' => $record['id']])->row_array();
                    $invoice = $this->db->get_where('invoices', ['type' => 'seller', 'sold_item_id' => $sold_item['id']])->row_array();
                    if ($invoice) {
                        $action = 'ID' . $record['id'] . 'SOLD ITEM ID' . $sold_item['id'] . 'EXPENSE';
                    } else {
                        $action = 'waiting_for_system_calculations';
                    }
                } else {
                    $action = '';
                }

                $data['inventory'][$key]['image'] = $image;
                $data['inventory'][$key]['current_status'] = json_encode($status_value);
                $data['inventory'][$key]['action'] = $action;
            }


            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'SUCCESS', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $data));

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Id Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }


    public
    function sell_item()
    {

        $version = $this->config->item('appVersion');
        $id = validateToken();

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $result = $this->db->get_where('users', ['id' => $id])->row_array();


        if (!empty($result)) {

            $data['sell_my_item'] = 'active';
            $data['new'] = 'new';
            $data['formaction_path'] = 'save_item';
            $data['category_list'] = $this->db->get_where('item_category', ['status' => 'active'])->result_array();


            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'SELL ITEM', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $data));

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Id Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }
    }


    public
    function get_item_fields()
    {

        $version = $this->config->item('appVersion');
        $id = validateToken();
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $result = $this->db->get_where('users', ['id' => $id])->row_array();
        $data2 = json_decode(file_get_contents("php://input"));


        if (!empty($result)) {

            if ($data2) {

                $data = array();
                $category_id = $data2->category_id;
                $datafields = $this->items_model->fields_data($category_id);
                $fdata = array();


                $data['category_id'] = $category_id;
                $data['fields_data'] = $datafields;

                foreach ($data['fields_data'] as $key => $fields) {
                    $data['fields_data'][$key]['input_name'] = $fields['id'] . '-' . $fields['name'];
                }


                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Item Fields', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $data));

            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Cat Id Not Found', 'english' => $english_l['id_not_found'], 'arabic' => $arabic_l['id_not_found'], 'appVersion' => $version));
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Id Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));

        }

    }

    public
    function get_makes_options()
    {

        $version = $this->config->item('appVersion');


        $data2 = json_decode(file_get_contents("php://input"));


        $data = array();
        $data_makes_array = $this->items_model->get_makes_list_active();
        $data['make_option'] = $data_makes_array;

        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Vehicle Model', 'appVersion' => $version, 'result' => $data));

    }

    public
    function get_makes_options2()
    {

        $version = $this->config->item('appVersion');


        $data2 = json_decode(file_get_contents("php://input"));


        $data = array();
        $data_makes_array = $this->items_model->get_makes_list_active();
        $data['make_option'] = $data_makes_array;

        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Vehicle Model', 'appVersion' => $version, 'result' => $data));

    }

    public
    function get_subcategories()
    {

        $version = $this->config->item('appVersion');
        $id = validateToken();


        $result = $this->db->get_where('users', ['id' => $id])->row_array();
        $data2 = json_decode(file_get_contents("php://input"));


        if (!empty($result)) {

            if ($data2) {

                $data = array();
                $data_subcategory_array = array();
                $category_id = $data2->category_id;
                if (!empty($category_id)) {
                    $data_subcategory_array = $this->customer_model->get_item_subcategory_list($category_id);
                    $data['sub_categories'] = $data_subcategory_array;
                }


                $codee = http_response_code(200);
                echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Sub Categories', 'appVersion' => $version, 'result' => $data));


            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Cat Id Not Found', 'appVersion' => $version));
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Id Not Found', 'appVersion' => $version));

        }
    }


    public
    function get_model_options()
    {
        $version = $this->config->item('appVersion');


        $data2 = json_decode(file_get_contents("php://input"));


        if ($data2) {
            $data = array();
            $make_id = $data2->make_id;
            $data_model_array = $this->items_model->get_item_model_list_active($make_id);

            $data['models'] = $data_model_array;
            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Vehicle Models', 'appVersion' => $version, 'result' => $data));

        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Model Id Not Found', 'appVersion' => $version));

        }
    }

    public
    function save_item()
    {


        $version = $this->config->item('appVersion');
        $id = validateToken();


        $result = $this->db->get_where('users', ['id' => $id])->row_array();
        $input = json_decode(file_get_contents("php://input"));
        $data_array = json_decode(file_get_contents("php://input"), true);


        if (!empty($result)) {


            if ($input) {


                $this->form_validation->set_data($data_array);
                $this->load->library('form_validation');
                $rules = array(
                    array(
                        'field' => 'category_id',
                        'label' => 'Category Id',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'subcategory_id',
                        'label' => 'Sub Category',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'name',
                        'label' => 'Name',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'location',
                        'label' => 'Location',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'name',
                        'label' => 'Name',
                        'rules' => 'trim|required')
                );
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {

                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => validation_errors(), 'appVersion' => $version));
                    exit();

                } else {


                    $items_attachment = array();
                    if (!empty($input->seller_id)) {
                        $seller_id = $input->seller_id;
                    } else {
                        $seller_id = $id;
                    }

                    $rand_str = '0123456789';
                    $item_name = [
                        'english' => $input->name,
                        'arabic' => $input->name_arabic
                    ];


                    $detail = [
                        'english' => $input->detail,
                        'arabic' => $input->detail_arabic
                    ];


                    $posted_data = array(
                        'name' => json_encode($item_name),
                        'location' => $input->location,
                        'detail' => json_encode($detail),
                        'status' => 'active',
                        'item_status' => 'created',
                        'inspected' => 'no',
                        'category_id' => $input->category_id,
                        'seller_id' => $id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $id
                    );


                    if (isset($input->subcategory_id) && !empty($input->subcategory_id)) {
                        $posted_data['subcategory_id'] = $input->subcategory_id;
                    }

                    if (isset($input->lat) && !empty($input->lat)) {
                        $posted_data['lat'] = $input->lat;
                    }
                    if (isset($input->lng) && !empty($input->lng)) {
                        $posted_data['lng'] = $input->lng;
                    }
                    if (isset($input->price) && !empty($input->price)) {
                        $posted_data['price'] = $input->price;
                    }
                    if (isset($input->vin_no) && !empty($input->vin_no)) {
                        $posted_data['vin_number'] = $input->vin_no;
                    }
                    if (isset($input->registration_no) && !empty($input->registration_no)) {
                        $posted_data['registration_no'] = $input->registration_no;
                    }


                    if (isset($input->make)) {
                        $posted_data['make'] = $input->make;
                        $posted_data['model'] = $input->model;
                        $posted_data['mileage'] = $input->mileage;
                        $posted_data['mileage_type'] = $input->mileage_type;
                        $posted_data['specification'] = $input->specification;
                    }


                    $posted_data['year'] = $input->year;
                    $result = $this->items_model->insert_item($posted_data);


                    $path = "uploads/items_documents/" . $result . "/qrcode/";

                    // make path
                    if (!is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }

                    $qrcode_name = $this->generate_code($result, $path, ['id' => $result]);
                    if (!empty($qrcode_name)) {
                        $barcode_array = array(
                            'barcode' => $qrcode_name
                        );
                        $this->items_model->update_item($result, $barcode_array);
                    }


                    foreach ($input as $dynamic_keys => $dynamic_values) {
                        $ids_arr = explode("-", $dynamic_keys);
                        if (is_array($dynamic_values)) {
                            $dynamic_values_new = "[" . implode(",", $dynamic_values) . "]";
                        } else {
                            $dynamic_values_new = $dynamic_values;
                        }

                        $dynaic_information = array(
                            'category_id' => $input->category_id,
                            'item_id' => $result,
                            'fields_id' => $ids_arr[0],
                            'value' => $dynamic_values_new,
                            'created_on' => date('Y-m-d H:i:s'),
                            'created_by' => $id
                        );

                        $result_info = $this->items_model->insert_item_fields_data($dynaic_information);

                    }


                    if (!empty($result)) {

                        $codee = http_response_code(200);
                        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Saved Successfully', 'appVersion' => $version, 'item_id' => $result));


                    } else {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Not Saved', 'appVersion' => $version));
                    }
                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Data Found', 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User/Token Not Found', 'appVersion' => $version));

        }


    }

    public
    function save_item2()
    {

        $version = $this->config->item('appVersion');
        $id = validateToken();

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $result = $this->db->get_where('users', ['id' => $id])->row_array();
        $input = json_decode(file_get_contents("php://input"));
        $data_array = json_decode(file_get_contents("php://input"), true);


        if (!empty($result)) {


            if ($this->input->post()) {


                if ($this->input->post()) {
                    $item_dynamic_data = $this->input->post();

                    $item_data = $this->input->post('item'); // get basic information

                    unset($item_dynamic_data['item']);  // remove basic information form data
                    // print_r($item_data);die();
                    $this->load->library('form_validation');
                    $rules = array(
                        array(
                            'field' => 'item[name]',
                            'label' => 'Name',
                            'rules' => 'trim|required')
                    );
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $codee = http_response_code(200);
                        echo json_encode(array("status" => false, 'code' => $codee, "message" => validation_errors(), 'appVersion' => $version));
                        exit();

                    } else {
                        $items_attachment = array();
                        if (!empty($item_data['seller_id'])) {
                            $seller_id = $item_data['seller_id'];
                        } else {
                            $seller_id = $id;
                        }
                        $rand_str = '0123456789';
                        $item_name = [
                            'english' => $item_data['name'],
                            'arabic' => $item_data['name_arabic']
                        ];
                        unset($item_data['name']);
                        unset($item_data['name_arabic']);

                        $detail = [
                            'english' => $item_data['detail'],
                            'arabic' => $item_data['detail_arabic']
                        ];
                        unset($item_data['detail']);
                        unset($item_data['detail_arabic']);

                        $posted_data = array(
                            'name' => json_encode($item_name),
                            'location' => $item_data['location'],
                            'detail' => json_encode($detail),
                            'status' => 'active',
                            'item_status' => 'created',
                            'inspected' => 'no',
                            'category_id' => $item_data['category_id'],
                            'seller_id' => $id,
                            'created_on' => date('Y-m-d H:i:s'),
                            'created_by' => $id
                        );

                        // a:

                        // $posted_data['registration_no'] = $this->generate_string($rand_str);
                        // $reg_no = $this->db->get_where('item', ['registration_no' => $posted_data['registration_no']]);
                        // if ($reg_no->num_rows() > 0) {
                        //     goto a;
                        // }

                        if (isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id'])) {
                            $posted_data['subcategory_id'] = $item_data['subcategory_id'];
                        }
                        // if(isset($item_data['keyword']) && !empty($item_data['keyword']))
                        // {
                        //     $posted_data['keyword'] = $item_data['keyword'];
                        // }
                        if (isset($item_data['lat']) && !empty($item_data['lat'])) {
                            $posted_data['lat'] = $item_data['lat'];
                        }
                        if (isset($item_data['lng']) && !empty($item_data['lng'])) {
                            $posted_data['lng'] = $item_data['lng'];
                        }
                        if (isset($item_data['price']) && !empty($item_data['price'])) {
                            $posted_data['price'] = $item_data['price'];
                        }
                        if (isset($item_data['vin_no']) && !empty($item_data['vin_no'])) {
                            $posted_data['vin_number'] = $item_data['vin_no'];
                        }
                        if (isset($item_data['registration_no']) && !empty($item_data['registration_no'])) {
                            $posted_data['registration_no'] = $item_data['registration_no'];
                        }

//                        $this->session->unset_userdata('items_images');
//                        $this->session->unset_userdata('items_documents');


                        if (isset($item_data['make'])) {
                            $posted_data['make'] = $item_data['make'];
                            $posted_data['model'] = $item_data['model'];
                            $posted_data['mileage'] = $item_data['mileage'];
                            $posted_data['mileage_type'] = $item_data['mileage_type'];
                            $posted_data['specification'] = $item_data['specification'];
                        }
                        // print_r($posted_data);die();
                        $posted_data['year'] = $item_data['year'];
                        $result = $this->items_model->insert_item($posted_data);

                        $path = "uploads/items_documents/" . $result . "/qrcode/";

                        // make path
                        if (!is_dir($path)) {
                            mkdir($path, 0777, TRUE);
                        }

                        $qrcode_name = $this->generate_code($result, $path, ['id' => $result]);
                        if (!empty($qrcode_name)) {
                            $barcode_array = array(
                                'barcode' => $qrcode_name
                            );
                            $this->items_model->update_item($result, $barcode_array);
                        }


                        foreach ($item_dynamic_data as $dynamic_keys => $dynamic_values) {
                            $ids_arr = explode("-", $dynamic_keys);
                            if (is_array($dynamic_values)) {
                                $dynamic_values_new = "[" . implode(",", $dynamic_values) . "]";


                            } else {
                                $dynamic_values_new = $dynamic_values;
                            }
                            $dynaic_information = array(
                                'category_id' => $item_data['category_id'],
                                'item_id' => $result,
                                'fields_id' => $ids_arr[0],
                                'value' => $dynamic_values_new,
                                'created_on' => date('Y-m-d H:i:s'),
                                'created_by' => $id
                            );

                            //$result_info = $this->items_model->insert_item_fields_data($dynaic_information);

                        }

                        if (!empty($result)) {
                            $codee = http_response_code(200);
                            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'success', 'english' => $english_l['success_'], 'arabic' => $arabic_l['success_'], 'appVersion' => $version, 'result' => $result));
                            exit();

                        } else {
                            $codee = http_response_code(200);
                            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Cant Save', 'appVersion' => $version));
                            exit();
                        }
                    }
                } else {
                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Data Found', 'appVersion' => $version));
                    exit();

                }
            } else {
                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No Data Found', 'appVersion' => $version));
                exit();
            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User/Token Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));
            exit();
        }

    }

    public
    function save_item_file_images()
    {


        $version = $this->config->item('appVersion');
        $id = validateToken();
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);


        $result = $this->db->get_where('users', ['id' => $id])->row_array();


        if (!empty($result)) {

            if (!empty($_FILES['images']['name'])) {

                $item_id = $_POST['item_id'];
                $itemsIds_array = array();
                $ids_concate = '';

                $result_array = $this->items_model->get_item_byid($item_id);
                if (isset($result_array) && !empty($result_array)) {
                    $itemsIds_array = explode(',', $result_array[0]['item_images']);

                    if (!empty($itemsIds_array) && !empty($result_array[0]['item_images'])) {
                        $ids_concate = $result_array[0]['item_images'] . ",";
                    }
                }

                // make path
                $path = './uploads/items_documents/';
                if (!is_dir($path . $item_id)) {
                    mkdir($path . $item_id, 0777, TRUE);
                }
                // $config['upload_path'] = $path;
                $config['allowed_types'] = 'ico|png|jpg|jpeg';
                $sizes = [
                    // ['width' => 1184, 'height' => 661],
                    // ['width' => 191, 'height' => 120], // for details page carusal icons
                    // ['width' => 305, 'height' => 180], // for gallery page
                    // ['width' => 294, 'height' => 204], // for auction page
                    // ['width' => 349, 'height' => 207], // for auction page
                    ['width' => 37, 'height' => 36] // for table listing
                ];
                // if ( ! is_dir($path.$item_id)) {
                //     mkdir($path.$item_id, 0777, TRUE);
                // }
                $path = $path . $item_id . '/';
                $config['upload_path'] = $path;
                // $config['allowed_types'] = 'ico|png|jpg|pdf|ai|psd|eps|indd|doc|docx|ppt|pptx|xlsx|xls';
                // $config['allowed_types'] = 'ico|png|jpg|jpeg';
                $uploaded_file_ids = $this->files_model->multiUpload('images', $config, $sizes); // upload and save to database

                // $uploaded_file_ids = $this->files->upload('images', $config, $sizes);
                // $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $update = [
                    'item_images' => $ids_concate . $uploaded_file_ids
                ];
                $result = ($this->items_model->update_item($item_id, $update)) ? 'true' : 'false';

                if ($result == 'true') {


                    $codee = http_response_code(200);
                    echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Uploaded Successfully.', 'english' => $english_l['image_uploaded_successfully'], 'arabic' => $arabic_l['image_uploaded_successfully'], 'appVersion' => $version));


                } else {


                    $codee = http_response_code(200);
                    echo json_encode(array("status" => false, 'code' => $codee, "message" => 'Uploading process has been failed.', 'english' => $english_l['please_select_image'], 'arabic' => $arabic_l['please_select_image'], 'appVersion' => $version));


                }
            } else {

                $codee = http_response_code(200);
                echo json_encode(array("status" => false, 'code' => $codee, "message" => 'No item found to upload.', 'english' => $english_l['please_select_image'], 'arabic' => $arabic_l['please_select_image'], 'appVersion' => $version));

            }
        } else {
            $codee = http_response_code(200);
            echo json_encode(array("status" => false, 'code' => $codee, "message" => 'User/Token Not Found', 'english' => $english_l['user_or_token_not_found'], 'arabic' => $arabic_l['user_or_token_not_found'], 'appVersion' => $version));
        }


    }


    public
    function terms_conditions()
    {
        $version = $this->config->item('appVersion');
        $data = array();
        $data['new'] = 'new';
        $data['terms_info'] = $this->db->get('terms_condition')->row_array();

        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Term Conditions.', 'appVersion' => $version, 'Result' => $data));


    }


    public
    function get_inventory()
    {
        $posted_data = $this->input->post();
        $userid = $this->loginUser->id;
        ## Read value
        $draw = $posted_data['draw'];

        // print_r($draw);die();
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
        ## Search
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            $search_arr[] = " item.name like '%" . $searchValue . "%'  ";
        }
        /////custom search/////////////
        $category_id = (isset($posted_data['inven_cat'])) ? $posted_data['inven_cat'] : '';
        $fav_id = (isset($posted_data['invn_search'])) ? $posted_data['invn_search'] : '';
        if ($category_id != '') {
            $search_arr[] = " item.category_id='" . $category_id . "' ";
        }
        if ($fav_id != '') {
            $search_arr[] = " item.name like '%" . $fav_id . "%'  ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = " (" . implode(" AND ", $search_arr) . ") ";
        }
        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('item');
        // $this->db->join('live_auction_inventory', 'live_auction_inventory.user_id = item.seller_id', 'left');
        // $this->db->where('live_auction_inventory.user_id', $userid);
        $this->db->where('seller_id', $userid);
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;
        // print_r($totalRecords);die();
        ## Total number of record with filtering

        $this->db->select('item.*');
        $this->db->from('item');
        // $this->db->join('live_auction_inventory', 'live_auction_inventory.user_id = item.seller_id', 'left');
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        // $this->db->where('live_auction_inventory.user_id', $userid);
        $this->db->where('seller_id', $userid);
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;

        // ## Fetch records
        // $this->db->select('live_auction_inventory.*');
        // $this->db->from('live_auction_inventory');
        $this->db->select('item.*');
        $this->db->from('item');
        // $this->db->join('live_auction_inventory', 'live_auction_inventory.user_id = item.seller_id', 'left');
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        // $this->db->where('live_auction_inventory.user_id', $userid);
        $this->db->where('seller_id', $userid);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();
        $data = array();
        // $have_documents = false;

        foreach ($records as $record) {
            $status_value = $this->lang->line('available');
            $images_ids = explode(",", $record->item_images);
            $item_id = $record->id;
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
            // print_r($files_array);die();
            $item_name = json_decode($record->name);
            $language = $this->language;
            if (isset($files_array[0]['name']) && !empty($files_array[0]['name'])) {
                $file_name = $files_array[0]['name'];
                $base_url_img = base_url('uploads/items_documents/') . $item_id . '/' . $file_name;
                $image = '<a class="text-success" href=""><img style="max-width: 50px" class="img-responsive avatar-view" src="' . $base_url_img . '" alt="Visa"></a>';
            } else {
                $base_url_img = base_url('assets_admin/images/product-default.jpg');
                $image = '<a class="text-success" href=""><img style="max-width: 50px" class="img-responsive avatar-view" src="' . $base_url_img . '" alt="Visa"></a>';
            }
            if ($record->sold == 'yes') {
                $status_value = $this->lang->line('sold');
                $sold_item = $this->db->get_where('sold_items', ['item_id' => $record->id])->row_array();
                $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item['id']])->row_array();
                if ($invoice) {
                    $action = '<button data-item-id="' . $record->id . '" data-sold-item-id="' . $sold_item['id'] . '" onclick="show_details(this)" class="btn btn-primary sm charges">' . $this->lang->line('expenses') . '</button>';
                } else {
                    $action = '<span> ' . $this->lang->line('waiting_for_system_calculations') . '</span>';
                }
            } else {
                $action = '<i class="fa fa-ellipsis-h"></i>';
            }

            $data[] = array(
                "id" => $record->id,
                "image" => $image,
                "name" => $item_name->$language,
                "price" => $record->price,
                "status" => $status_value,
                "action" => $action
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


    public
    function contact_us()
    {

        $version = $this->config->item('appVersion');


        $data = array();
        $data['new'] = 'new';
        $data['contact_us_info'] = $this->db->get('contact_us')->row_array();
        $data['our_team_info'] = $this->db->get('our_team')->result_array();

        $data['qualityPolicy'] = $this->db->get('quality_policy')->row_array();
        $data['about'] = $this->db->get('about_us')->row_array();
        $file = $this->db->get_where('files', ['id' => $data['about']['image']])->row_array();
        $data['about']['image'] = base_url($file['path'] . '1110x276_' . $file['name']);
        $data['about']['image2'] = base_url() . "assets_user/new/images/about-inner-item.png";

        $data['history'] = $this->db->get('about_us_history')->result_array();

        $data['privacy_policy'] = $this->db->get('privacy_policy')->row_array();


        $data['language'] = "english";

        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Contact Us.', 'appVersion' => $version, 'Result' => $data));


    }

    public
    function faq()
    {
        $data['contact_us_info'] = $this->db->get('contact_us')->row_array();
        $data['language'] = $this->language;
        $this->template->load_user('new/contact-us', $data);
    }

    public
    function our_team()
    {
        $data = array();
        $data['our_team_info'] = $this->db->get('our_team')->result_array();
        $data['our_team'] = $this->db->get('team_info')->row_array();
        // print_r($data['our_team_info']);die();
        $this->template->load_user('our_team', $data);
    }

    public
    function about_us()
    {
        $data = array();
        $data['new'] = 'new';
        $data['about'] = $this->db->get('about_us')->row_array();
        // print_r($data['about']);die();
        $data['history'] = $this->db->get('about_us_history')->result_array();
        $this->template->load_user('new/about-us', $data);
    }

    public
    function faqs()
    {
        $data = array();
        $data['new'] = 'new';
        $data['list'] = $this->db->get('ques_ans')->result_array();
        ///// Auction list //////
        $data['active_auction_categories'] = $this->home_model->get_active_auction_categories();
        foreach ($data['active_auction_categories'] as $key => $value) {
            $auctions_online = $this->home_model->get_online_auctions($value['id']);
            if (!empty($auctions_online)) {
                $data['active_auction_categories'][$key]['auction_id'] = $auctions_online['id'];
                $count = $this->db->select('*')->from('auction_items')->where('auction_id', $auctions_online['id'])->where('sold_status', 'not')->where('auction_items.bid_start_time <', date('Y-m-d H:i'))->get()->result_array();
                $count = count($count);
                $data['active_auction_categories'][$key]['item_count'] = $count;
            }
        }
        ///// End Auction list //////
        $this->template->load_user('new/faq_page', $data);
    }

    public
    function liveStreaming()
    {
        $data = array();
        $data['new'] = 'new';
        $this->template->load_user('live_streaming', $data);
    }

    public
    function qualityPolicy()
    {
        $data = array();
        $data['new'] = 'new';
        $data['terms_info'] = $this->db->get('privacy_policy')->row_array();
        $this->template->load_user('visitor/quality_policy', $data);
    }


    public
    function lang_arabic()
    {
        $version = $this->config->item('appVersion');

        $lang['acl_update_succes'] = 'تم تحديث الأذونات بنجاح';
        $lang['no_internet_connection_msg'] = 'يرجى التحديث للتحقق من الاتصال';

        $lang['select_language'] = 'اختار اللغة';

        $lang['please_login_to_see_live_auction'] = 'الرجاء تسجيل الدخول لمشاهدة المزاد الحي';
        $lang['invalid_request'] = 'طلب غير صالح';
        $lang['please_select_bank_slip'] = 'من فضلك قسيمة البنك';
        $lang['please_enter_deposit_amount'] = 'الرجاء إدخال مبلغ الإيداع';
        $lang['invalid_first_name'] = 'الاسم الأول غير صالح';
        $lang['invalid_last_name'] = 'اسم العائلة غير صالح';
        $lang['please_enter_mobile_number'] = 'الرجاء إدخال رقم الهاتف المحمول';
        $lang['please_enter_address'] = 'الرجاء إدخال العنوان.';
        $lang['please_enter_city'] = 'الرجاء إدخال المدينة.';
        $lang['please_enter_country'] = 'الرجاء تحديد الدولة.';
        $lang['please_enter_emirates'] = 'الرجاء إدخال الإمارات / الدولة.';
        $lang['unable_to_update_profile'] = 'تعذر تحديث الملف الشخصي';
        $lang['password_do_not_match'] = 'كلمة السر غير مطابقة.';
        $lang['no_result'] = 'لا نتيجة';


        $lang['current_item'] = 'العنصر الحالي';

        $lang['with_you'] = 'معك';
/////Main Home Page language
//        $lang['view_detail'] = 'عر ض التفاصيل!';
//        $lang['auction_catagories'] = 'أنواع المزادات';
        $lang['featured_items'] = 'المعروضات المميزة';
//        $lang['start_bidding'] = 'بدء المزايدة';
//        $lang['how_it_works'] = 'كيفية العمل';
//        $lang['seller'] = 'البائع';
//        $lang['buyer'] = 'المشتري';
        $lang['register'] = 'التسجيل';
        $lang['select'] = 'اختر';
//        $lang['bid'] = 'مزايدة';
//        $lang['win'] = 'اربح';
//        $lang['get_started_now'] = 'قم بالبدأ الآن!';
//        $lang['our_partners'] = 'شركاؤنا';
//        $lang['download_app'] = 'تحميل تطبيق بايونير';
//        $lang['ios_android'] = 'iOS or Android';
//        $lang['valuation_home'] = 'تقيم مجاني للمركبة';
//
/////// Footer for home page
////Auction sub link footer
//        $lang['auction_footer_link'] = 'المزادات';
        $lang['about_us'] = 'من نحن';
//        $lang['quality_policy_footer'] = 'سياسة الجودة';
//        $lang['faq_footer'] = "أسئلة شائعة";
//        $lang['our_partners_footer'] = "شركاؤنا";
//
////Quick Links sub link footer
//        $lang['quick_link'] = "روابط مهمة";
//        $lang['media'] = "الوسائط";
//        $lang['privacy'] = "سياسة الخصوصية";
//        $lang['terms'] = "الشروط و الأحكام";
        $lang['contact_us'] = "اتصل بنا";
//        $lang['gallery'] = "معرض الصور";
//
//////Our Address on home page footer
//        $lang['address_footer'] = "العنوان";
//        $lang['twitter'] = "Take your web design to new heights with elix";
//
//
//        $lang['live_streaming_header'] = 'بث مباشر';
//        $lang['live_streaming_header_cap'] = 'بث مباشر';
        $lang['could_not_live_streaming'] = 'تعذر تحميل البث المباشر';
//        $lang['seller_link'] = 'البائع';
//        $lang['buyer_link'] = 'المشتري';
//        $lang['auction_link'] = 'المزادات';
//        $lang['call'] = 'اتصل';
//
//
//        $lang['account_is_not_verified'] = 'لم يتم التحقق من الحساب.';
//        $lang['verify_now'] = 'تحقق الآن';
//        $lang['phone_already_registered'] = 'رقم الهاتف مسجّل مسبقاً!';
//        $lang['pioneer_verification_code_is'] = 'رمز التحقق الخاص بحسابك هو ';
//        $lang['please_login'] = 'الرجاء تسجيل الدخول';
        $lang['go_to_account'] = 'الذهاب إلى حسابي';
//        $lang['activate_your_account'] = 'تفعيل الحساب';
//        $lang['invalid_phone_number'] = 'رقم الهاتف غير صحيح.';
//        $lang['email_already_registered'] = 'اهذا البريد الإلكتروني مسجّل لدينا مسبقاً، الرجاء تسجيل الدخول أو طلب إعادة تعين كلمة المرور.';
//        $lang['Verification_code_sent_to_your_mobile'] = 'تم إرسال رمز التحقق الخاص بك إلى هاتفك المسجّل.';
//        $lang['mobile_number_not_registered'] = 'رقم الهاتف غير مسجل.';
//        $lang['email_verified'] = 'تم التحقق من البريد الإلكتروني.';
//        $lang['email_verification_failed'] = 'فشل التحقق من البريد الإلكتروني.';
//        $lang['verified_successfully_please_check_email'] = 'تم التحقق بنجاح.';
//        $lang['verification_failed_try_again'] = 'فشل التحقق، الرجاء المحاولة مرة أخرى!';
//        $lang['verification_code_is_invalid'] = 'رمز التحقق غير صحيح!';
//        $lang['email_has_been_sent_your_account'] = 'تم إرسال البريد الإلكتروني إلى حسابك.';
//        $lang['invalid_email_address_try_again'] = 'عنوان البريد الإلكتروني غير صحيح ،الرجاء المحاولة مرة أخرى!';
//        $lang['expired_link'] = 'انتهت صلاحية الرابط.';
//        $lang['password_length_should_be_long'] = 'يجب أن تكون كلمة المرور أكثر من 5 خانات.';
//        $lang['your_password_is_changed'] = 'تم تغير كلمة المرور.';
//        $lang['Password_confirm_not_matched'] = 'تأكيد كلمة المرور غير مطابق لكلمة المرور المدخلة مسبقاً، الرجاء المحاولة مرة أخرى.';
//
//
//        $lang['please_indicate_you_accept_policy'] = 'الرجاء قبول سياسة الخصوصية الخاصة بنا.';
//
//
////evaluation
//        $lang['please_select_make'] = 'الرجاء اختيار شركة الصنع';
//        $lang['please_select_model'] = 'الرجاء اختيار الموديل';
//        $lang['please_select_year'] = 'الرجاء اختيار السنة';
//        $lang['no_enginesize_available'] = 'غير متوفر سعة المحرك المختارة';
//        $lang['year_range_to'] = 'سنة الصنع من';
//        $lang['please_select_engine_size'] = 'الرجاء اختيار سعة المحرك.';
//        $lang['please_select_email'] = 'الرجاء اختيار البريد الإلكتروني.';
//        $lang['email_is_not_valid'] = 'البريد الإلكتروني غير صحيح.';
//        $lang['not_avilable_record'] = 'غير متوفر';
//        $lang['please_select_date'] = 'الرجاء اختيار التاريخ.';
//        $lang['select_date_greater_than_today'] = 'الرجاء اختيار تاريخ احدث';
        $lang['please_select_first_name'] = 'الرجاء اختيار الإسم الأول.';
//        $lang['please_select_number'] = 'الرجاء اختيار رقم الهاتف.';
//        $lang['appointment_request_sent'] = 'تم ارسال طلب الموعد!';
//        $lang['you_notified_by_email_shortly'] = 'سيتم اشعارك عبر البريد الالكتروني قريباً.';
//        $lang['appointment_request_failed'] = 'فشل في طلب الموعد!';
//        $lang['please_try_again_later'] = ' الرجاء المحاولة لاحقاً في وقت آخر.';
//
////visitor
//        $lang['thank_for_contacting_us'] = 'شنشكركم على التواصل معنا، سيقوم أحد أعضاء الفريق بالتواصل معكم قريباً.';
//
//
///////// Customer dashboard left bar
//        $lang['my_account'] = 'حسابي';
        $lang['my_bids'] = 'اجمالي عدد المعروضات التي يتم المزايدة عليها الآن';
//        $lang['my_docs'] = 'مستنداتي';
//        $lang['my_stock'] = 'معروضاتي';
//
//        $lang['payments'] = 'الدفع';
//        $lang['logout'] = 'الدفع';
//        $lang['pay_deposit'] = 'دفع التأمين';
//        $lang['invoice_payment'] = 'الفواتير/الدفع';
//        $lang['security_deposit'] = 'مبلغ التأمين';
//        $lang['favorite_items'] = 'المفضلة';
//        $lang['notification'] = 'الإشعارات';
//        $lang['faq_left'] = 'أسئلة شائعة';
        $lang['terms_condition'] = 'الشروط و الأحكام';
        $lang['sell_my_item'] = 'بيع المعروضات';
///// My Account page user dashboard
//        $lang['account'] = 'حسابي';
        $lang['available_balance'] = 'الرصيد المتوفر';
//        $lang['account_status'] = 'حالة الحساب:';
//        $lang['limit'] = 'زيادة الحد';
//        $lang['my_bids_cap'] = 'عروضي';
        $lang['bid_limit'] = 'حد المزايدة';
//        $lang['increase_balance'] = 'زيادة الرصيد';
//        $lang['bid_time'] = 'وقت المزايدة';
//        $lang['bid_name'] = 'الاسم';
//        $lang['your_bid'] = 'المزايدة الخاصة بك';
//        $lang['last_bid'] = 'اخر مزايدة';
//        $lang['bid_status'] = 'الحالة';
//
/////globals
//        $lang['name'] = 'الاسم';
//        $lang['created_on'] = 'تم إنشاؤه في';
//        $lang['status'] = 'الحالة';
//        $lang['status_cap'] = 'الحالة';
//        $lang['action'] = 'المزاد';
        $lang['price'] = 'السعر';
//        $lang['item'] = 'المعروضات';
        $lang['search'] = 'بحث';
//        $lang['amount'] = 'المبلغ';
//        $lang['account'] = 'الحساب';
//        $lang['payment'] = 'الدفع';
//        $lang['detail'] = 'التفاصيل';
        $lang['detail_english'] = 'التفاصيل باللغة الانجليزية';
        $lang['detail_arabic'] = 'التفاصيل باللغة العربية';
//        $lang['please_add_something'] = 'الرجاء الإضافة';
        $lang['year'] = 'السنة';
//        $lang['item_name'] = 'اسم المعروض';
        $lang['item_name_english'] = 'اسم المعروض باللغة الإنجليزية';
        $lang['item_name_arabic'] = 'اسم المعروض باللغة العربية';
        $lang['make'] = 'شركة الصنع';
        $lang['model'] = 'الموديل';
        $lang['select_make'] = 'جميع الموديلات ';
//        $lang['select_make_first'] = 'الرجاء اختيار شركة الصنع أولاً';
//        $lang['model_not_available'] = 'الموديل غير متوفر';
        $lang['select_model'] = 'الرجاء اختيار الموديل';
        $lang['submit'] = 'ارسال';
//        $lang['loading'] = 'جاري التحميل..';
//
/////// Uploaded Documents user dashboard
        $lang['uploaded_documents'] = 'تحميل المستندات';
//
///// Inventory page user dashboard
//        $lang['id'] = 'Id';
//        $lang['inventory'] = 'سجل المعروضات';
//        $lang['select_search'] = 'اختيار النوع';
//        $lang['expense_detail'] = 'تفاصيل المصاريف';
//
///// Deposit history page user dashboard
//        $lang['deposit_history'] = 'سجل الإيداعات';
//        $lang['add_deposit'] = 'أضف مبلغ تأميني';
//        $lang['deposit_date'] = 'تاريخ مبلغ التأمين';
//        $lang['payment_type'] = 'طريقة الدفع';
//
//
////// SECURITY HISTORY page user dashboard
//        $lang['security_history'] = 'سجل التأمين';
//
///// Invoices/Payments History page
//        $lang['invoices_heading'] = 'الفواتير/سجل الإيداعات';
//        $lang['win_date'] = 'تاريخ الفوز';
//        $lang['due_payment'] = 'تاريخ الاستحقاق';
//
////favourite items user dashboard
//        $lang['favourite'] = 'المفضلة';
//
////Notification user dash board
//        $lang['notification'] = 'الإشعارات';
//        $lang['no_record'] = 'لم يتم العثور على سجلات.';
        $lang['no_result'] = 'لا نتيجة';
//
/////FAQ
//        $lang['faq'] = 'أسئلة شائعة';
//
//// SELL MY ITEM user dashboard page
//        $lang['sell_item'] = 'بيع المعروضات';
        $lang['category'] = 'النوع';
        $lang['select_cat'] = 'اختيار النوع';
        $lang['vin_number'] = 'رقم القاعدة';
        $lang['reg_code'] = 'رقم التسجيل';
        $lang['sub_cat'] = 'النوع الفرعي للمعروض';
        $lang['reserve_price'] = 'السعر المتوقع';
//        $lang['keyword'] = 'الكلمة المفتاحية';
//        $lang['other_info'] = 'معلومات أخرى';
//
///// PROFILE customer dashboard
//        $lang['profile'] = 'الملف';
        $lang['f_name'] = 'الإسم الأول';
        $lang['l_name'] = 'الإسم الأخير';
        $lang['email'] = 'البريدالإلكتروني';
//        $lang['remarks'] = 'علامة';
        $lang['mobile'] = 'رقم الهاتف';
        $lang['address'] = 'العنوان';
        $lang['city'] = 'المدينة';
        $lang['states'] = 'الإمارة';
        $lang['country'] = 'البلد';
        $lang['enter_country_name'] = 'أدخل اسم الدولة';
        $lang['enter_state_name'] = 'أدخل اسم الولاية';
//        $lang['id_nmbr'] = 'رقم الهوية الإماراتية/جواز السفر';
//        $lang['po_box'] = 'صندوق البريد';
//        $lang['job_title'] = 'المسمى الوظيفي';
//        $lang['company_name'] = 'اسم الشركة';
        $lang['description'] = 'الوصف';
        $lang['update'] = 'تحديث';
//        $lang['save'] = 'حفظ';
        $lang['upload_photo'] = 'تحميل صورة جديدة';
//
///// Forgot Password page
        $lang['change_password'] = 'تغير كلمة المرور';
        $lang['current_password'] = 'كلمة المرور الحالية';
        $lang['new_password'] = 'كلمة المرور الجديدة';
//        $lang['confirm_password'] = 'تأكيد كلمة المرور';
//
///// Valuation process
//        $lang['free_valuation'] = 'تقيم مجاني للمركبات';
//        $lang['select_car'] = 'اخنر المركبة';
//        $lang['model_cndtion'] = 'الموديل و المواصفات';
//        $lang['book_appointment'] = 'اطلب موعد';
//        $lang['year_range'] = 'اختر السنة';
//        $lang['get_started'] = 'ابدأ الآن';
//        $lang['select_millage'] = 'اختر عدد الكيلومترات';
//        $lang['option'] = 'الخيارات';
//        $lang['basic'] = 'أساسي';
//        $lang['mid'] = 'متوسط';
//        $lang['full'] = 'كامل';
//        $lang['paint'] = 'الصبغ';
//        $lang['original'] = 'صبغة أصلية';
//        $lang['partial'] = 'بعض القطع مصبوغة';
//        $lang['total'] = 'مصبوغة بالكامل';
//        $lang['has_specs'] = 'هل لديها مواصفات خليجية';
//        $lang['gcc'] = 'مواصفات خليجية';
//        $lang['non_gcc'] = 'ليست مواصفات خليجية';
//        $lang['dont_know'] = "لا أعلم";
//        $lang['valuate_your_car'] = "قيّم مركبتك";
//        $lang['vehicle_market_price'] = "سعر المركبة حسب سوق السيارات";
//        $lang['date_time'] = "الوقت و التاريخ";
//
///// footer gallery
//        $lang['gallery'] = "الألبوم";
//        $lang['about'] = "من نحن";
//        $lang['values'] = "قيمنا";
//        $lang['commitment'] = "التزاماتنا";
//        $lang['quality'] = "الجودة";
//        $lang['passion'] = "الشغف";
//        $lang['head_office'] = "المكتب الرئيسي";
//        $lang['toll_free'] = "اتصل مجاناً";
//        $lang['write_us'] = "اكتب لنا";
//        $lang['subject'] = 'الموضوع';
//        $lang['how_can'] = 'كيف يمكننا مساعدتك؟';
//
/////online_auction_final
//        $lang['min'] = 'الحد الأدنى';
//        $lang['max'] = 'الحد الأعلى';
        $lang['millage'] = 'عداد المركبة';
        $lang['km'] = ' كيلومتر ';
        $lang['miles'] = 'ميل';
        $lang['specs'] = 'المواصفات';
        $lang['imported'] = 'وارد';
        $lang['lot'] = 'رقم التسلسل';
//        $lang['lot_cap'] = 'رقم التسلسل';
//        $lang['view_more'] = 'عرض المزيد';
//        $lang['view_less'] = 'عرض أقل';
//        $lang['ad_filter'] = "بحث متقدم";
//        $lang['apply'] = 'تطبيق';
        $lang['online_auction'] = 'المزاد الإلكتروني';
        $lang['live_auction'] = 'مزاد علني';
//        $lang['close_auction'] = 'مزاد مغلق';
        $lang['search_result'] = 'نتائج البحث';
//        $lang['please_login_first_to_item_into_favorite'] = 'الرجاء تسجيل الدخول أولاً لإضافة المعروض للمفضلة.';
        $lang['latest'] = 'الأحدث';
        $lang['high_price'] = 'أعلى سعر';
        $lang['low_price'] = 'أقل سعر';
//        $lang['view'] = 'عرض';
        $lang['from'] = 'من';
        $lang['to'] = 'إلى';
//
//
///// auction_items page for listing
//        $lang['see_detail'] = 'عرض التفاصيل';
//        $lang['c_price'] = 'السعر الحالي';
//        $lang['remain_time'] = 'الوقت المتبقي';
        $lang['min_increment'] = 'أقل مزايدة';
//        $lang['end_time'] = 'تنتهي في';
        $lang['odometer'] = 'عداد المركبة';
        $lang['bids'] = 'المزايدات';
//        $lang['add_list'] = 'إضافة قائمة';
//        $lang['remove_list'] = 'إزالة قائمة';
        $lang['bid_now'] = 'المزايدة الآن';
//        $lang['expired'] = 'منتهي الصلاحية';
//
///// item_detail_final page
//        $lang['bid_info'] = 'معلومات المزايدة';
//        $lang['month'] = 'الشهر';
        $lang['day'] = 'اليوم';
        $lang['days'] = 'اليوم';
//        $lang['d'] = ' ي ';
        $lang['hour'] = 'الساعة';
        $lang['hours'] = 'الساعة';
//        $lang['hrs'] = ' س ';
        $lang['minute'] = ' د ';
        $lang['place_bid'] = 'قم بالمزايدة';
//        $lang['auto_biding'] = 'مزايدة تلقائية';
//        $lang['item_required_extra_deposit'] = 'هذا المعروض يحتاج الى مبلغ تأميني أكبر';
//        $lang['deposit_now'] = 'أودع الآن';
//        $lang['you_need_make_security_deposit'] = 'الرجاء إيداع مبلغ تأميني،لإيداع مبلغ التأمين الرجاء النقر على الرابط التالي.';
//        $lang['start'] = 'ابدأ';
//        $lang['deposit_text'] = 'مبلغ التأمين مطلوب للمزايدة';
//        $lang['pay_deposit'] = 'ادفع التأمين';
        $lang['login_first'] = 'الرجاء تسجيل الدخول أولاً للمزايدة';
        $lang['login'] = 'تسجيل الدخول';
//        $lang['register'] = 'التسجيل';
//        $lang['registration_nmbr'] = 'رقم التسجيل';
//        $lang['registration_no'] = 'رقم التسجيل';
//
//        $lang['login_cap'] = 'تسجيل الدخول';
//        $lang['register_cap'] = 'التسجيل';
//        $lang['reg_type'] = 'نوع التسجيل';
//        $lang['indivisual'] = 'أفراد';
//        $lang['organization'] = 'مؤسسات';
//        $lang['how_did'] = 'كيف سمعت عنا؟';
//        $lang['web'] = 'الموقع';
//        $lang['social'] = 'مواقع التواصل الإجتماعي';
//        $lang['pref'] = 'اللغة المفضلة';
//        $lang['eng'] = 'الانجليزية';
//        $lang['arab'] = 'العربية';
//        $lang['read'] = 'لقد قرأت ووافقت على الشروط والأحكام';
//
//        $lang['description_small'] = 'الوصف';
        $lang['location'] = 'الموقع';
        $lang['test_report'] = '  فحص المركبة ';
        $lang['cond_report'] = 'حالة الفحص';
        $lang['inquire_further'] = 'إذا كنت بحاجة إلى مزيد من المساعدة ، الرجاء الاتصال بنا';
        $lang['enquire'] = 'استعلام';
        $lang['sale_terms'] = 'رسوم و شروط البيع';
//        $lang['related_products'] = 'معروضات متعلقة';
//        $lang['see_detail'] = 'عرض التفاصيل';
//
//// Live_auction_items page
//        $lang['auction_location'] = 'موقع المزاد';
        $lang['dubai'] = 'دبي';
//        $lang['date'] = 'التاريخ';
//        $lang['date_cap'] = 'التاريخ';
//        $lang['print_c'] = 'طباعة';
//        $lang['open_live'] = 'فتح المزاد العلني';
        $lang['specs_s'] = 'المواصفات';
//
/////deposit page for payment
//        $lang['deposit'] = 'مبلغ التأمين';
//        $lang['deposit_small'] = 'مبلغ التأمين';
//        $lang['increase_deposit'] = 'زيادة مبلغ التأمين';
        $lang['cheque'] = 'شيك';
//        $lang['credit_c'] = 'بطاقة الائتمان';
//        $lang['bank_transfer'] = 'تحويل بنكي';
//        $lang['pay_cheque'] = 'دفع شيك تأميني';
        $lang['visit_location'] = '  الرجاء زيارة احد فروعنا المذكورة ادناه لإنهاء عملية دفع شيك التأمين  ';
//        $lang['time'] = 'الوقت';
//        $lang['sun_thur'] = 'الأحد-الخميس';
//        $lang['cheque_location'] = 'لدفع الشيكات انظر إلى المواقع';
        $lang['card_pay'] = ' الدفع عن طريق البطائق الائتمانية  / بطائق سحب الصراف الآلي ';
//
////deposit page p element
        $lang['p1'] = 'يرجى العلم بأن أقل مبلغ تأمين مطلوب للمزايدة هو ';
        $lang['p1_2'] = 'نقبل البطائق الخاصة بفيزا و ماستر كارد. ';
        $lang['p2'] = '(ملاحظة لا نقبل البطائق الإلكترونية ، فقط البطائق المذكورة اعلاه)';
        $lang['p3'] = 'إذا قمت بتحديد هذا الخيار ، فسيُطلب منك إدخال تفاصيل بطاقة الائتمان الخاصة بك في صفحة الدفع الآمنة عبر الإنترنت الخاصة بالبنك. بمجرد الانتهاء من المعاملة ، ستتمكن من الدخول في المزاد وبدء المزايدة.';
        $lang['p4'] = 'سيتم خصم المبلغ من بطاقتك وسيظهر في كشف حسابك المصرفي ، بإمكانك إسترجاع مبلغ التأمين في أي وقت بالإتصال على 7466337 800 سيتم إعادة المبلغ إلى نفس البطاقة ، وعادة ما يستغرق الحصول عليه من 7 إلى 15 يوم عمل، طبقاً للبنك المُصدر للبطاقة.
 ';
        $lang['p5'] = 'يرجى العلم أنه لا يمكنك إستخدام مبلغ التأمين المدفوع عن طريق بطاقة الائتمان في السعر النهائي للمعروض، وهذا يعني أنه يتعين عليك دفع المبلغ بالكامل وسيتم رد مبلغ التأمين لك.';
//
//        $lang['deposit_amount'] = 'مبلغ التأمين';
        $lang['proceed'] = 'الدفع';
//        $lang['skip'] = 'تخطي';
        $lang['pay_by_bank_transfer'] = 'الدفع عن طريق تحويل بنكي';
        $lang['ac_name'] = 'اسم الحساب';
        $lang['bank_name'] = 'اسم البنك';
        $lang['iban'] = 'IBAN';
        $lang['ac_number'] = 'رقم الحساب';
        $lang['swift_code'] = 'Swift Code';
        $lang['routing_number'] = 'رقم التحويل';
        $lang['p6'] = 'بمجرد الانتهاء من عملية التحويل ، يرجى إرسال ايصال التحويل عبر البريد الإلكتروني ';
        $lang['p6_2'] = 'او ارسلها على الفاكس';
        $lang['p7'] = 'يرجى السماح بما يصل إلى 24 ساعة قبل أن تنعكس الإيداع على حسابك.';
        $lang['transfer_slip'] = 'إيصال تحويل مبلغ التأمين';
        $lang['d_date'] = 'تاريخ مبلغ التأمين';
        $lang['d_amount'] = 'قيمة مبلغ التأمين';
        $lang['submit_your_deposit'] = 'ارسل مبلغ التأمين';
        $lang['enter_deposit_amount'] = "الرجاء ادخال مبلغ التأمين ";
        $lang['enter_deposit_date'] = "أدخل تاريخ الإيداع";
//        $lang['security'] = 'تأمين';
//
///// live_auction_detail page
//        $lang['hall_date'] = 'تاريخ المزاد العلني';
//        $lang['start_time'] = 'يبدأ';
//        $lang['final_order'] = 'الحد الأعلى/الأخير';
//        $lang['multiple_bid'] = 'الرجاء المزايدة بمضاعفات';
//        $lang['hall_auto_bid'] = 'المزاد العلني للمركبات';
        $lang['cancel'] = 'إلغاء';
//
///// live_online page
        $lang['current_bid'] = 'قيمة المزايدة الحالية';
        $lang['quick_bids'] = 'مزايدة سريعة';
//        $lang['current_lot'] = 'رقم التسلسل الحالي';
        $lang['all_lots'] = 'جميع المعروضات';
        $lang['winning_lot'] = 'المعروضات الرابحة';
        $lang['log'] = 'تسجيل دخول';
        $lang['sort_to_recent'] = 'عرض الأحدث';
//        $lang['vehicle_not_listed'] = 'المركبة غير مدرجة';
//
//
////info messages
//        $lang['cancal_proxy_bid'] = 'إلغاء المزايدة التلقائية.';
//        $lang['you_sure_want_cancal'] = 'هل أنت متأكد من إلغاء المزايدة التلقائية.';
//        $lang['confirm'] = 'تأكيد!';
//        $lang['You_placing_auto_bid'] = 'لقد قمت بإختيار المزايدة التلقائية لـ';
//        $lang['sure_you_want_place_proxy_bid'] = 'هل أنت متأكد من القيام بمزايدة تلقائية';
//        $lang['press_edit_button_to_apply_changes'] = 'اخطأ! انقر على زر "تعديل" للقيام بأي تغير.';
//
////success messages
//        $lang['success_'] = 'تم الإرسال بنجاح !';
//        $lang['auto_bid_activated'] = 'تم تفعيل المزايدة التلقائية.';
//        $lang['bid_placed_successfully'] = 'تمت المزايدة بنجاح!';
//        $lang['auto_bid_updated'] = 'تم تحديث المزايدة التلقائية.';
//        $lang['auto_bid_cancelled_successfully'] = 'تم إالغاء المزايدة التلقائية بنجاح.';
//        $lang['logged_out_successfully'] = 'لقد قمت بتسجيل الخروج من حسابك بنجاح.';
//        $lang['profile_updated_successfully'] = 'لتم بنجاح! لقد تم تحديث الملف بنجاح.';
//        $lang['image_uploaded_successfully'] = 'تتم بنجاح! تم تحديث صورة العرض الخاصة بك بنجاح.';
//        $lang['password_updated_successfully'] = 'تتم بنجاح! تم تحديث كلمة المرور بنجاح.';
//        $lang['password_confirm_password'] = 'ككلمة المرور و تأكيد كلمة المرور غير متطابقان .';
//        $lang['invalid_current_password'] = 'كخطأ! كلمة المرور الحالية غير صحيحة.';
//        $lang['item_added_successfully'] = 'تمت إضافة المعروض بنجاح.';
//        $lang['db_error_found'] = 'هناك خطأ ما ، الرجاء المحاولة مرة أخرى.';
        $lang['please_update_your_profile'] = 'الرجاء تحديث الملف أولاً.';
//        $lang['payment_made_successfully'] = 'تتم بنجاح! تم الدفع بنجاح.';
//        $lang['payment_failed_to_make'] = 'فخطأ! فشلت عملية الدفع.';
//        $lang['user_documents_updated'] = 'تتم بنجاح! تم تحديث المستندات الخاصة بك بنجاح.';
//        $lang['user_documents_added'] = 'تمت إضافة مستندات المستخدم بنجاح.';
//        $lang['please_select_item'] = 'الرجاء اختيار المعروض.';
//        $lang['slip_has_been_added_successfully'] = 'تتم بنجاح! تمت إضافة الإيصال بنجاح.';
//        $lang['updated'] = 'تم التحديث!';
//        $lang['password_changed_successfully'] = 'تم تغير كلمة المرور بنجاح.';
//        $lang['user_documents_has_updated_successfully'] = 'تم تحديث مستندات المعروض بنجاح.';
//
////error messages
//        $lang['error'] = 'خطأ';
//        $lang['error_'] = 'خطأ!';
//        $lang['item_is_no_longer_available'] = 'المعروض لم يعد متاحاً.';
//        $lang['upload_documents'] = 'قم بتحميل المستندات';
//        $lang['what_auto_bidding'] = 'ماهي المزايدة التلقائية؟';
//        $lang['leave_your_maximum_bid'] = 'قم بوضع حد أقصى لمبلغ الشراء الخاص بك و سيقوم النظام بتقديم مزايدات بشكل منتظم و تصاعدي حتى يصل إلى مبلغ الحد الأقصى للشراء';
//        $lang['every_time_you_outbid_until'] = 'في كل مرة تقوم فيها بالمزايدة حتى تصل إلى الحد الأقصى لمبلغ المزايدة. يجب أن يكون الحد الأقصى للمبلغ الخاص بك أكبر من السعر الحالي. بعد وضع المبلغ اضغط على زر START.';
//        $lang['users_visit'] = 'عدد المشاهدات!';
//        $lang['add_favorite'] = 'إضافة إلى المفضلة!';
//        $lang['remove_favorite'] = 'إزالة من المفضلة!';
//        $lang['auto_bidding_started'] = 'تم بدء المزايدة التلقائية';
//        $lang['increment'] = 'قيمة الزيادة';
//        $lang['you_are_placing'] = 'أنت تقوم بمزايدة بمبلغ';
//        $lang['you_are_placing_new'] = 'انت تقوم الان بالمزايدة بـ';
//        $lang['your_bid_now'] = 'مبلغ المزايدة سيصبح';
//        $lang['increment_small'] = 'قيمة الزيادة⸮';
//        $lang['info'] = 'معلومات';
//        $lang['info_cap'] = 'معلومات';
//        $lang['insufficient_balance'] = 'لا يوجد رصيد كافي.';
//        $lang['item_time_expired'] = 'انتهت مدة عرض المعروض،لا يسمح بالمزيد من المزايدات.';
//        $lang['bid_successfully'] = 'تتم بنجاح! تمت المزايدة بنجاح.';
//        $lang['bid_failed'] = 'المزايدة غير متوفرة.';
//        $lang['deposit_exceeds'] = 'لقد تجاوزت حد مبلغ التأمين.';
//        $lang['auto_bid_not_activated'] = 'المزايدة التلقائية غير فعالة.';
//        $lang['bid_limit_should_greater_than'] = 'مبلغ المزايدة يجب أن يكون أكبر من';
//        $lang['you_not_highest_bidder'] = 'أنت لست صاحب أعلى مزايدة.';
//        $lang['auction_not_initialized'] = 'المزايدة على هذا المعروض غير جاهزة حالياً.';
//        $lang['item_sold_out'] = 'تم بيع المعروض.';
//        $lang['you_need_login'] = 'الرجاء تسجيل الدخول حتى تتمكن من المزايدة.';
//        $lang['some_information_missed'] = 'بعض المعلومات ناقصة، الرجاء المحاولة لاحقاً!';
//        $lang['account_blocked'] = 'تم حظر الحساب.';
//        $lang['only_user_login'] = 'فقط المسجلين لديهم الصلاحية للدخول.';
//        $lang['invalid_username_password'] = 'اسم المستخدم أو كلمة المرور غير صحيحة.';
//        $lang['your_bid_amount_must_multiple'] = 'مبلغ المزايدة يجب أن يكون مضاعفات';
//        $lang['your_account_blocked'] = 'تم حظر الحساب من قبل الإدارة.';
//        $lang['no_auction_available_try_again_later'] = 'لا يوجد مزاد علني ،الرجاء المحاولة لاحقاً.';
//        $lang['go_to_home'] = 'الذهاب للصفحة الرئيسية';
//        $lang['failed_cancel_auto_bid'] = 'فشل في إلغاء المزايدة التلقائية.';
//        $lang['please_select_image'] = 'الرجاء اختيار الصورة.';
//        $lang['slip_has_een_failed_to_add'] = 'فشل في تحميل الإيصال.';
        $lang['min_deposit_amount_is'] = 'الحد الأدنى لمبلغ التأمين هو';
//        $lang['invalid'] = 'غير صحيح!';
//
//
//        $lang['no_data_available'] = 'لا توجد معلومات.';
//
////status
//        $lang['approved'] = 'تمت الموافقة';
//        $lang['refund'] = 'إسترجاع';
//        $lang['rejected'] = 'تم الرفض';
//        $lang['adjusted'] = 'تم التعديل';
//        $lang['available'] = 'متوفر';
//        $lang['sold'] = ' تم البيع ';
//        $lang['returned'] = 'عاد';
        $lang['in_process'] = 'قيد الإجراء';
        $lang['complete_bidding_process'] = 'عملية تقديم العطاءات كاملة';
//        $lang['active'] = 'فعّال';
//        $lang['waiting_for_system_calculations'] = 'الرجاء الإنتظار بينما يقوم النظام بالحساب.';
//        $lang['expenses'] = 'المصاريف';
        $lang['sort_type'] = 'فرز حسب النوع';
        $lang['higher_bider'] = 'أنت صاحب أعلى مزايدة';
        $lang['not_higher_bider'] = 'أنت لست صاحب أعلى مزايدة';
//        $lang['enter_here'] = 'ادخل هنا';
//        $lang['your_limit'] = 'الحد الخاص بك';
//        $lang['coming_up'] = 'قادم';
//        $lang['unknown'] = 'غير متوفر';
//        $lang['pending'] = 'قيد الإنتظار';
//        $lang['not_available'] = 'غير متوفر';
//
///// Forgot password or send sms popups
//        $lang['enter_varification_code'] = 'الرجاء ادخال رمز التحقق';
//        $lang['otp'] = 'لقد تم ارسال كلمة المرور لمرة واحدة';
        $lang['otp_note'] = 'في حال عدم استلام رمز التحقق';
        $lang['request_new_code'] = 'طلب رمز تحقق جديد';
        $lang['enter_email'] = 'الرجاء ادخال البريد الإلكتروني لإعادة تعين كلمة المرور';
        $lang['results_for'] = "نتائج";
//        $lang['reset_link'] = 'سيتم إرسال رابط إعادة تعين كلمة المرور الى بريدك الإلكتروني';
//
///// header sub menu
//        $lang['dashboard'] = 'لوحة التحكم';
//        $lang['profile_small'] = 'الملف';
//        $lang['change_password_small'] = 'تغير كلمة المرور';
        $lang['log_out'] = 'تسجيل الخروج';
//
////login page
//        $lang['f_password'] = 'هل نسيت كلمة المرور؟';
        $lang['password'] = 'كلمة المرور';
//        $lang['password_capital'] = 'كلمة المرور';
//        $lang['why'] = 'لماذا بايونير للمزادات';
//        $lang['access'] = 'الوصول إلى البحث والمزايدة على المركبات كل يوم';
//        $lang['access_search'] = 'الوصول إلى البحث والمزايدة';
//        $lang['access_search_bid'] = 'الوصول إلى البحث والمزايدة على المركبات كل يوم';
//        $lang['select_auction'] = 'اختيار المزاد';
        $lang['select_item'] = 'اختيار المعروض';
//        $lang['select_auction_first'] = 'اخنر المزاد أولاً';
//        $lang['select_item_first'] = 'اختر المعروض أولاً';
//        $lang['with'] = 'مع';
//        $lang['bider'] = 'المزايد';
//
///// swal popup
//        $lang['you_are_placing'] = 'تمت المزايدة بـ';
//        $lang['bid_amount_now'] = ' مبلغ المزايدة سيصبح الآن ';
//        $lang['bid_amount_now_new'] = 'مبلغ المزايدة سيصبح الآن';
//        $lang['bid_success'] = 'تمت المزايدة بنجاح.';
//        $lang['limit_exceeds'] = 'تم تجاوز حد مبلغ التأمين.';
//        $lang['auto_bid_activated_not'] = 'المزايدة التلقائية غير فعّالة.';
//        $lang['bid_limit_should_greater_than'] = 'حد المزايدة يجب أن يكون أكبر من مبلغ المزايدة الحالي.';
        $lang['confirm'] = 'تأكيد';
        $lang['images'] = 'الصور';
//        $lang['sold_out'] = 'تم البيع';
//        $lang['sold_out_new'] = 'مباع! ';
//
//
///// live_online_detail page
//        $lang['placing_auto_bid'] = 'تمت المزايدة التلقائية بـ ';
//        $lang['sure_auto_bid'] = 'هل أنت متأكد من أنك تريد تقديم مزايدة تلقائية لـ
//';
//        $lang['sure_proxy_bid_cancel'] = 'هل أنت متأكد من إلغاء المزايدة التلقائية.';
//        $lang['multiple'] = 'مبلغ المزايدة يجب أن يكون مضاعفات';
//        $lang['auto_bid_activated_update'] = 'تم تحديث المزايدة التلقائية.';
//        $lang['cancel_proxy'] = 'إلغاء المزايدة التلقائية.';
//        $lang['time_over'] = 'تم انتهاء وقت المزايدة التلقائية.';
//
//        $lang['adjustment'] = 'تعديل';
//        $lang['make_payment'] = 'الدفع';
//        $lang['copyright'] = 'حقوق  الطبع و النشر';
//        $lang['rights'] = 'جميع حقوق الطبع و النشر محفوظة';
//        $lang['fav'] = 'إضافة إلى المفضلة';
//        $lang['r_fav'] = 'إزالة من المفضلة';
//        $lang['blnce'] = 'الرصيد';
//        $lang['c_bid_limit'] = 'حد المزايدة الحالي';
//        $lang['total_d'] = 'اجمالي مبلغ التأمين';
        $lang['reset_password'] = 'إعادة تعين كلمة المرور';
        $lang['confirm_password'] = 'تأكيد كلمة المرور';
//        $lang['password_six_digit_long'] = 'كلمة المرور يجب ان تكون اكثر من 6 خانات.';
//        $lang['not_now_thanks'] = 'ليس الآن ،شكراً لك!';
//        $lang['edit'] = 'تعديل';
//        $lang['site_title'] = 'بايونير للمزادات.';
        $lang['upcoming_auction'] = 'المزادات القادمة';
//        $lang['hall_auto_bids'] = 'مزاد المركبات القادم';
//
//
//        $lang['what_is_auto_biding'] = 'ماهي المزايدة التلقائية؟ قم بوضع حد أقصى لمبلغ الشراء الخاص بك و سيقوم النظام بتقديم مزايدات بشكل منتظم و تصاعدي حتى يصل إلى مبلغ الحد الأقصى للشراء';
//        $lang['what_is_auto_biding_text'] = 'في كل مرة تقوم فيها بالمزايدة حتى تصل إلى الحد الأقصى لمبلغ المزايدة. يجب أن يكون الحد الأقصى للمبلغ الخاص بك أكبر من السعر الحالي. بعد وضع المبلغ اضغط على زر START.';
///// deposit payment types key
//        $lang['cash'] = 'نقداً';
//        $lang['card'] = 'بطاقة ائتمانية';
        $lang['bank_transfer'] = 'تحويل بنكي';
//        $lang['cheque'] = 'شيك';
//        $lang['manual_deposit'] = 'ايداع يدوي';
//        $lang['adjustment'] = 'تعديل';
//
//////user_docs
//        $lang['reqired_upload'] = 'التحميلات المطلوبة';
//        $lang['docs'] = 'المستندات';
//
/////toltip for security history
//        $lang['add_security'] = 'اضافة تأمين';
//        $lang['business_hr'] = 'ساعات العمل:';
//        $lang['bid_amount_cap'] = 'مبلغ المزايدة';
//
//
//// latest integrations
//        $lang['get_free_valuation'] = 'قم بالحصول على تقيم مجاني <br>لمركبتك عبر بايونير للمزادات';
//        $lang['select_date_for_auction'] = 'قم بإختيار <br>تاريخ للمزاد';
//        $lang['sell_your_car'] = 'بيع سيارتك إلى<br> مزايدينا عبر الإنترنت!';
//        $lang['featured_bids'] = 'المعروضات المميزة';
//        $lang['latest_bids'] = 'احدث المعروضات';
//        $lang['popular_bids'] = 'المعروضات الأكثر دخولاً';
//        $lang['original_price'] = 'السعر الحالي درهم إماراتي';
//        $lang['checkout_available_auctions'] = 'تحقق من المزادات<br>المتوفرة الآن!';
        $lang['auctions_about_to_close'] = 'المعروضات التي على وشك الإنتهاء';
//        $lang['view_all'] = 'عرض الكل';
        $lang['view_details'] = 'عرض التفاصيل';
//        $lang['new_media'] = 'الصور';
//        $lang['sell_my_item_new'] = 'بيع المعروض الخاص بي';
//        $lang['register_now'] = 'سجّل الآن';
//        $lang['auction_guides'] = 'إرشادات المزاد';
//        $lang['auctions'] = 'المزادات';
//        $lang['about_new'] = 'من نحن';
//        $lang['contact_us_new'] = 'اتصل بنا';
//        $lang['contact_us_address'] = 'بناية مجموعة ارمز ،شارع المطار ،ام الرمول
//ص.ب. 666 ،دبي ، الإمارات العربية المتحدة';
        $lang['my_profile'] = 'ملفي';
//        $lang['history'] = 'المزايدات الخاصة بي';
//        $lang['logout'] = 'تسجيل خروج';
//        $lang['login_new'] = 'تسجيل دخول';
//        $lang['register_new'] = 'التسجيل';
        $lang['sign_in_account'] = 'قم بتسجيل الدخول او سجّل الآن';
        $lang['enter_email_address'] = 'ادخل عنوان البريد الإلكتروني';
        $lang['enter_password'] = 'ادخل كلمة المرور';
        $lang['sign_in'] = 'تسجيل الدخول';
        $lang['forgot_password'] = 'هل نسيت كلمة المرور؟';
        $lang['dont_have_account'] = 'ليس لديك حساب؟';
        $lang['sign_up_now'] = 'سجّل الآن';
        $lang['sign_up_agree'] = 'بمجرد التسجيل أنت توافق على';
        $lang['terms_and_conditions'] = 'الشروط و الأحكام';
//        $lang['enter_verification_code'] = 'الرجاء ادخال رمز التحقق';
//        $lang['code'] = 'رمز التحقق';
//        $lang['request_code'] = 'طلب رمز تحقق جديد';
//        $lang['enter_name'] = 'ادخل الإسم';
        $lang['enter_mobile_num'] = 'ادخل رقم الهاتف';
        $lang['create_password'] = 'إنشاء كلمة المرور';
        $lang['sign_up'] = 'التسجيل';
        $lang['already_have_account'] = 'هل لديك حساب؟';
        $lang['email_address'] = 'عنوان البريد الإلكتروني';
//        $lang['information'] = 'المعلومات';
//        $lang['guide_on_auction'] = 'كيفية المزايدة';
//        $lang['how_to_register'] = 'كيفية التسجيل';
//        $lang['how_to_deposite'] = 'كيفية الإيداع';
//        $lang['terms_and_conditions_new'] = 'الشروط و الأحكام';
//        $lang['about_us_new'] = 'عن الشركة / من نحن';
//        $lang['quality_policy'] = 'سياسة الجودة';
        $lang['privacy_policy'] = 'سياسة الخصوصية';
//        $lang['quick_links'] = 'روابط مهمة';
//        $lang['faq_new'] = 'أسئلة شائعة';
//        $lang['help_center'] = 'الدعم الفني';
        $lang['our_address'] = 'العنوان';
//        $lang['mobile_new'] = 'رقم الهاتف';
//        $lang['email_new'] = 'البريد الإلكتروني';
//        $lang['business_hours'] = 'ساعات العمل';
//        $lang['pioneer_auction_right_reserved'] = 'جميع حقوق الطبع و النشر محفوظة لبايونير للمزادات';
//        $lang['love_to_hear'] = 'نود السماع منك!';
//        $lang['reach_to_us'] = 'اتصل بنا';
//        $lang['any_questions_or_suggestions'] = 'هل لديك أي سؤال أو اقتراح لبايونير للمزادات ؟ الرجاء ملء الاستمارة التالية:';
//        $lang['last_name'] = 'الإسم الخير';
//        $lang['phone_num'] = 'رقم الهاتف';
//        $lang['message'] = 'الرسالة';
//        $lang['customer_care'] = 'خدمة العملاء';
//        $lang['get_in_touch'] = 'تواصل معنا.';
//        $lang['social_media'] = 'مواقع التواصل الإجتماعي';
//        $lang['be_the_first_to_find_auctions'] = 'كن أول من يعرف عن المزادات القادمة و الاحداث المتعلقة بالمزادات.';
//        $lang['like_us_on_facebook'] = 'قم بالإعجاب بصفحتنا في الفيس البوك';
//        $lang['today'] = 'اليوم';
//        $lang['follow_on_instagram'] = 'تابعنا في الانستقرام';
//        $lang['one_stop_platform'] = 'منصتك الشاملة للمزايدة بكل سهولة.';
//        $lang['fvrt_auction_platform'] = 'منصة المزاد المفضلة لك';
        $lang['our_services'] = 'خدماتنا';
        $lang['free_car_val'] = 'تقيم مجاني للمركبات';
        $lang['online_bidding'] = 'المزايدة الإلكترونية';
        $lang['live_bidding'] = 'المزاد العلني';
        $lang['our_mission'] = 'رسالتنا';
//        $lang['hear_from_us'] = 'اسمع عنّا';
//        $lang['yasir_khushi'] = 'ياسر كوشي';
//        $lang['executive_director'] = 'المدير التنفيذي لبايونير للمزادات';
        $lang['home'] = 'الرئيسية';
//        $lang['my_account_new'] = 'حسابي';
//        $lang['my_details'] = 'التفاصيل';
        $lang['personal_information'] = 'معلومات شخصية';
//        $lang['account_information'] = 'معلومات الحساب';
//        $lang['payments_new'] = 'الدفعات';
//        $lang['cheque_new'] = 'شيك';
        $lang['credit_card'] = 'بطاقة ائتمانية';
//        $lang['bank_transfer_new'] = 'تحويل بنكي';
        $lang['timings'] = 'التوقيت';
//        $lang['history_new'] = 'السجل';
        $lang['my_bids_new'] = 'المزايدات الخاصة بي';
        $lang['no_of_bids'] = 'عدد المزايدات';
        $lang['select_document'] = 'اختيار المستند';

//        $lang['bid_time'] = 'وقت المزايدة';
//        $lang['name_of_item'] = 'اسم المعروض';
//        $lang['your_bid_new'] = 'المزايدة الخاصة بك';
//        $lang['status_new'] = 'الحالة';
        $lang['my_wishlist'] = 'قائمتي المفضلة';
//        $lang['action_new'] = 'المزاد';
//        $lang['remove_selected'] = 'ازالة';
//        $lang['my_docs_new'] = 'مستنداتي';
        $lang['uploaded_documents_new'] = 'تحميل المستندات';
//        $lang['name_new'] = 'اسم المستند';
//        $lang['items'] = 'المستندات';
//        $lang['created_on_new'] = 'تم إنشاؤه في';
//        $lang['no_files_found'] = 'لم يتم العثور على الملف.';
        $lang['documents'] = 'المستندات';
//        $lang['req_upload'] = 'التحميلات المطلوبة';
//        $lang['item_details'] = 'تفاصيل المعروض';
//        $lang['mileage'] = 'عداد المركبة';
        $lang['mileage_type'] = 'نوع عداد المركبة';
//        $lang['km_new'] = ' كيلومتر ';
//        $lang['miles_new'] = ' ميل ';
        $lang['specifications'] = ' المواصفات';
        $lang['gcc_new'] = 'خليجية';
        $lang['imported_new'] = 'وارد';
        $lang['item_location'] = 'موقع المعروض';
//        $lang['more_info'] = 'المزيد من المعلومات';
//        $lang['share'] = 'مشاركة';
//        $lang['time_remaining_for_bid'] = 'الوقت المتبقي للمزايدة';
//        $lang['direct_offer'] = 'قيمة المزايدة';
        $lang['auto_bidding'] = 'المزايدة التلقائية';
        $lang['place_max_amount'] = 'قم بوضع الحد الأقصى للشراء و تفعيل زر المزايدة التلقائية في الأعلى ليقوم النظام بالمزايدة نيابة عنك بوضع أقل زيادة ممكنة حتى يصل الحد الأقصى للشراء.';
//        $lang['place_direct_offer'] = '';
//        $lang['available_for_live_auction'] = 'متاح لـ: المزاد العلني';
//        $lang['login_or_register'] = 'تسجيل الدخول أو التسجيل';
//        $lang['now_to_start_bidding'] = 'قم بالمزايدة الآن قبل انتهاء الوقت!';
//        $lang['vat'] = 'قيمة الضريبة المضافة';
//        $lang['applicable'] = 'تطبق';
        $lang['details'] = 'التفاصيل';
        $lang['enquiry'] = 'استعلام';
//        $lang['lot_location'] = 'موقع المعروض';
        $lang['open_google_maps'] = 'خرائط قوقل';
//        $lang['ad_space'] = 'مساحة اعلان';
//        $lang['you_are_placing_aed'] = 'انت تقوم بالمزايدة بـ درهم إماراتي';
        $lang['would_you_like_to_continue'] = 'ترغب بالمتابعة؟';
        $lang['dont_have_enough_balance'] = 'عذرا! ليس لديك الرصيد الكافي.';
        $lang['deposite_more_money'] = 'هل ترغب بايداع مبلغ التأمين';
//        $lang['take_me_to_the_payments'] = 'الذهاب إلى الدفعات';
//        $lang['more_info_new'] = 'المزية من المعلومات';
//        $lang['password_small'] = 'كلمة المرور';
//        $lang['confirm_password_small'] = 'تأكيد كلمة المرور';
//
//        $lang['about_us_info'] = '
//منذ 2008 ،كانت بايونير دار المزادات الرائدة في المنطقة و حافزاً لتقديم المبادرات الأولى في الصناعات المختلفة  بما في ذلك المزادات العلنية على المنصات الرقمية. و منذ ذلك الوقت لم تنمو بايونير للمزادات لتصبح دار المزادات الرائدة في الدولة فحسب بل تم الاعتراف بها كمزاد رائد في الصناعات المختلفة في بيع المركبات ، مواد البناء ، الأجهزة اللإلكترونية، المعدات البحرية، الآلات الثقيلة ، الخيول و أكثر من ذلك بكثير.
//يقدم بايونير  للمزادات أسهل و أسرع الطرق لشراء أو بيع المركبات ،مواد البناء و غيرها من المواد لشريحة كبيرة من المتعاملين من مختلف الأنحاء من دول مجلس التعاون و شبه القارة الهندية و إفريقيا و دول أخرى  من خلال منصاتنا الحديثة و المبتكرة عبر الانترنت و المزادات العلنية. نحن نفخر كوننا دار المزادات الوحيدة التي تقدم مزادات علنية من خلال مرافقنا الحديثة و الخاصة بنا.
//نستخدم احدث التقنيات و برامج المزادات في منصاتنا للمزايدة التي تتيح للمشترين و البائعين المشاركة بدون أية
//متاعب و الوصول إلى مختلف الأسواق في مختلف الأماكن.
//من خلال إداراة المزادات بشكل سلس تم إنشاء معايير صناعية لا مثيل لها تقدم خدمات مميزة في المزادات و تحرص على رضا المتعاملين . حيث أن لدينا قاعدة من المتعاملين من مختلف الصناعات و القطاعات الحكومية و الخاصة و التجارية الرائدة.
//';
        $lang['about_us_car_desc'] = 'تقييم مجاني للمركبات:
هل تريد أن تعرف أفضل سعر لمركبتك ؟ يساعدك موقعنا في الحصول على التقيم الصحيح لمركبتك . تحقق الآن.
';
        $lang['about_us_bidding_desc'] = 'قم بالمزايدة على المركبات وجميع المعروضات الأخرى بكل أريحية خلال الوقت المخصص باستخدام أنظمة المزاد السهلة الخاصة بنا.
';
        $lang['about_us_live_bidding_desc'] = '
قم بالمزايدة مباشرةً عبر الإنترنت و بدون أية متاعب في المزادات العلنية القائمة في قاعة بايونير من مكتبك أو منزلك بإستخدام منصات البث المباشرة الأكثر تقدماً و سهولة ،على المعروضات المفضلة لديك.
';
        $lang['about_us_our_mission_desc'] = 'مساعدة المتعاملين في زيادة أرباح الأصول بطرق فعالة من خلال خدماتنا المميزة في المزادات الخاصة بنا.
توفير أعلى معايير خدمات المزاد العلني و تسهيل وصول المشترين إلى البائعين من خلال تقديم تجربة فريدة و سلسة للمزاد.
تقديم الخدمات الأكثر ابتكاراً و  ملائمة و فعالية في المنطقة.
توفير مركبات و مواد ذات جودة عالية بسعر تنافسي في السوق من خلال خدماتنا المميزة في المزادات الخاصة بنا.
';
        $lang['about_us_our_vision_desc'] = 'إنشاء بايونير للمزادات كشركة مزاد رائدة من خلال احداث تأثير في قطاع المزادات و تجسيد قيمنا الأساسية المتمثلة في الصدق و النزاهة.
كشركة رائدة و مسؤولة في قطاع المزادات نقدم بإستمرار مستويات إستثنائية من الخدمات لمتعاملينا و جعل بايونير الخيار الأول لخدمات المزاد.
نسعى جاهدين لإبتكار أنظمة و طرح عروضات مميزة بهدف توفير خدمات فعالة في المزاد و توسيع نطاق المزاد في جميع أنحاء العالم.
';
        $lang['about_us_our_vision'] = 'رؤیتنا';
//
///////////
        $lang['tel'] = 'هاتف';
        $lang['search_here'] = 'ابحث هنا';
//        $lang['explore_now'] = 'اكتشف الآن';
//        $lang['sub_to_email'] = 'اشترك في إشعارات البريد الإلكتروني';
//        $lang['back_to_result'] = 'رجوع إلى النتائج';
        $lang['back_to_auction'] = 'العودة إلى المزاد';
        $lang['sale_location'] = 'موقع البيع';
//        $lang['end_date'] = 'تاريخ الانتهاء';
//        $lang['sales_info_terms'] = 'رسوم وشروط معلومات البيع';
        $lang['succrss_bid_text'] = ' لقد قمت بالمزايدة بمبلغ درهم إماراتي';
//        $lang['after_successs_bid_text'] = ' بنجاح!';
//
        $lang['invalid_attempt'] = 'نجاح! لقد قمت بوضع درهم إماراتي';
//        $lang['add_to_wish_list'] = 'أضف إلى قائمة الامنيات';
//
//        $lang['dont_enogh_balance'] = "اه اه. يبدو أنه ليس لديك رصيد كافٍ في حسابك";
//        $lang['would_you_like_deposit'] = "هل ترغب في إيداع المزيد من الأموال في رصيدك؟";
        $lang['start_date_time'] = "تاريخ ووقت البدء";
//        $lang['catalog_small'] = "فهرس";
//        $lang['live_hall_auction'] = " المزاد العلني";
        $lang['filter'] = "البحث";
        $lang['clear'] = "مسح";
//        $lang['apply_filter'] = "تطبيق البحث المتقدم";
//        $lang['advance_filter'] = "بحث متقدم";
//        $lang['views'] = "المشاهدات";
        $lang['advanced'] = "المتقدمة";
        $lang['apply_filters'] = "تطبيق المرشحات";
        $lang['upload_images'] = "تحميل الصور";
        $lang['more_information'] = "معلومات اكثر";
        $lang['or'] = "أو";
        $lang['featured'] = "متميز";
//        $lang['my_payable'] = 'ذممى الدائنة';
//        $lang['my_payable_new'] = 'ذممى الدائنة';
        $lang['payments'] = "المدفوعات";
//        $lang['item_security'] = "تأمين إضافي";
        $lang['enter_first_name'] = "أدخل الاسم الأول";
        $lang['enter_last_name'] = 'ادخل الإسم الأخير';
//        $lang['write_your_msg_here'] = "اكتب رسالتك هنا";
//        $lang['stay_updated_text'] = "ابق على اطلاع بآخر مزايداتنا وتابعنا مباشرة على انستقرام";
//        $lang['showing'] = "عرض";
        $lang['of'] = "من";
        $lang['payment_status'] = "حالة الدفع";
//        $lang['similar'] = "معروضات مشابهة";
//        $lang['sale_date'] = "تاريخ البيع";
        $lang['vin'] = 'فين';
//        $lang['sold_out'] = 'نفذ';
//        $lang['log_sm'] = 'سجل';
//        $lang['please_wait'] = 'انتظر من فضلك';
//        $lang['waiting_for_initial_bid'] = 'في انتظار مبلغ العطاء الأولي';
        $lang['hall_bidder'] = 'مع عارض القاعة';
//        $lang['hall_bid'] = ' مزايدة في قاعة بايونير ';
//        $lang['online_bidder'] = 'عن طريق الانترنت بقيمة';
//        $lang['online_bid'] = ' مزايدة عن طريق الانترنت ';
//        $lang['sold_to'] = ' تم بيع ';
//        $lang['for_aed'] = 'مقابلدرهم إماراتي';
        $lang['aed'] = 'درهم إماراتي';
//        $lang['aed_small'] = 'درهم';
        $lang['add_item'] = 'إضافة عنصر';
        $lang['default_increment'] = 'قيمة المزايدة الافتراضية درهم.';
        $lang['your_total_bid_will_now_be_AED'] = 'مبلغ المزايدة سيصبح الآن  درهم إماراتي';

        $lang['you_have_started_auto_bidding'] = 'لقد قمت بالمزايدة التلقائية حتى مبلغ  درهم ';
        $lang['success_you_have_place_increment'] = 'النجاح! لقد قمت بوضع زيادة درهم إماراتي';
        $lang['you_are_placing_increment_aed'] = 'انت تقوم الان بالمزايدة بـ  درهم إماراتي';
        $lang['you_are_placing_autobid_aed'] = 'أنت تقوم بتقديم مزايدة آلية مقابل درهم إماراتي';
        $lang['the_system_will_place_minimum'] = 'سيقوم النظام بالمزايدة التلقائية بالحد الأدنى نيابة عنك حتى يصل إلى الحد الأقصى للشراء ';


//        $lang['ok'] = 'موافق';
        $lang['success_you_have_place_increment'] = 'النجاح! لقد قمت بوضع زيادة درهم إماراتي';

//        $lang['maximum_amount'] = 'الحد الأقصى للشراء';
        $lang['enter_city'] = 'أدخل المدينة';
        $lang['enter_current_password'] = 'ادخل كلمة المرور الحالية';
        $lang['enter_new_password'] = 'أدخل كلمة المرور جديدة';
        $lang['confirm_new_password'] = 'تأكيد كلمة المرور الجديدة';
//        $lang['choose'] = 'أختر';
//        $lang['ends_in'] = 'ينتهي في';
//        $lang['ends_in_new'] = 'ينتهي في ';
//        $lang['drag_drop_image_here'] = 'قم بسحب الملف و وضعه هُنا';
//        $lang['docs_items'] = 'المستندات';
//        $lang['docs_name'] = 'اسم المستند';
//        $lang['am'] = 'صباحاً ';
//        $lang['pm'] = 'مساءً';
//        $lang['no_file_choosen'] = 'لم يتم اختيار الملف';
        $lang['chose_file'] = 'اختر ملف';
//        $lang['Stay_tuned_for_upcoming_auctions'] = 'ابقوا معنا لمعرفة المزادات القادمة!';
//        $lang['Get_best_deals_on_vehicles_general_material_more'] = 'احصل على أفضل العروض الخاصة بالمركبات ، المواد العامة، مواد البناء ، المعدات البحرية ، العقارات و الكثير';
        $lang['enter_lot'] = ' ادخل رقم التسلسل ';
//        $lang['bid_not_placed_try_again'] = 'المزايدة لم توضع. حاول مرة أخرى.';
//        $lang['bid_live_on_this_item'] = 'قم بالمزايدة علنياً على البضاعة التالية في';
//        $lang['for_more_details'] = 'لمزيد من التفاصيل اتصل على  6337 746 800';
//        $lang['you_have_reached_your_bid_limit'] = 'لقد تم الوصول إلى الحد الأقصى للمزايدة .
//الرجاء إضافة مبلغ تأمين لزيادة الحد الأقصى للمزايدة. ';
        $lang['place_auto_bid_amount'] = ' سيقوم النظام بالمزايدة التلقائية نيابة عنك بوضع أقل زيادة ممكنة حتى يصل الحد الأقصى للشراء. ';
//        $lang['you_placing_bid'] = '  تقوم الآن بالمزايدة التلقائية ليصبح الحد الأقصى لمبلغ الشراء  ';
        $lang['switch_on_auto_bid'] = ' تفعيل المزايدة التلقائية ';
//        $lang['comma'] = '،';
//        $lang['what_all_people_saying'] = 'ماذا يقول الناس عن بايونير للمزادات';
//
//        $lang['testimonial_name1'] = 'أرول داس';
//        $lang['testimonial_name2'] = 'راجا زهير';
//        $lang['testimonial_name3'] = 'فادي';
//        $lang['testimonial_pa_customer'] = ' متعامل لدى بايونير للمزادات';
//
//        $lang['testimonial1'] = 'أتعامل مع بايونير للمزادات منذ تأسيسها. كانت تجربتي رائعة لأنني راضٍ عن جودة الخدمة التي يتم تقديمها.';
//        $lang['testimonial2'] = 'الفريق رائع جداً ، وقد جعلوا من عملية المشاركة في المزاد تجربة ممتعة! يقومون بكل شيء من أجلك و يعتنون بك ';
//        $lang['testimonial3'] = 'لقد كنت ولا زلت متعامل لدى بايونير للمزادات لعدة سنين، و خلالها قمت بشراء العديد من السيارات بأفضل الأسعار. أنا سعيد جداً بالخدمة و الدعم المقدم من خلالهم';
//        $lang['subscribe_success_message'] = 'نشكركم على الاشتراك، لقد تم إرسال بريد إلكتروني للتأكيد.';
//
//        $lang['subscribe_empty_error'] = 'الرجاء ادخال البريد الإلكتروني';
//
//        $lang['aed_small_new'] = 'د.إ ';
//        $lang['you_placing_bid_new'] = 'تقوم الآن بالمزايدة التلقائية ليصبح الحد الأقصى لمبلغ الشراء';
//
//        $lang['testimonial_name4'] = 'دينكار';
//        $lang['testimonial4'] = 'لتتمكن من معاينة حوالي 100 مركبة و مشاهدة الفحوصات الخاصة بها و المزايدة عليها في ليلة واحدة ! كل ذلك في بايونير، لقد بسطت بايونير للمزادات عملية بيع و شراء المركبات';
//
//        $lang['sold_to_new'] = ' تم بيع  ';
//
//        $lang['for_aed_new'] = 'في قاعة بايونير بقيمة  ';
//
        $lang['ends_day'] = 'ي ';
        $lang['ends_hours'] = 'س ';
        $lang['ends_mins'] = 'د ';
        $lang['ends_sec'] = 'ث ';
//        $lang['time_expired'] = 'انتهى المزاد ';
//
//
//        $lang['upcoming_new'] = ' القادمة  ';
//
//        $lang['all_model'] = 'كل الأصناف ';
//
//        $lang['no_record_new'] = 'لا يوجد سجلات! ';
//
//        $lang['similar_new'] = 'مشابهة ';
//
//
//        $lang['testimonial5'] = 'أود القول بأن المزادات جداً مذهلة. إنها فرصة عظيمة لأي شخص يريد الحصول على صفقات ممتازة على المركبات، مواد البناء، أجهزة إلكترونية و مواد أخرى في بايونير للمزادات  ';
//
//        $lang['testimonial_name5'] = 'داس  ';
//
//        $lang['select_yearTo'] = 'حدد ل ';
//
//        $lang['register_full_name'] = 'يرجى ادخال الإسم بالكامل كما هو في الهوية الإمارتية ';
//
//        $lang['cond_report_new'] = 'تقرير المثمن ';
        $lang['report'] = 'تقرير ';
        $lang['additional_info'] = 'معلومات إضافية ';
//
////winner model box text
//        $lang['winner_model_line_1'] = 'تهنئة ! أنت أعلى مزايد على ';
//        $lang['winner_model_line_2'] = '. يرجى التحقق من بريدك الإلكتروني لمزيد من التفاصيل. ';
//        $lang['winner_model_line_3'] = 'انقر فوق الزر أدناه لعرض التفاصيل. ';
//
//
        $lang['Pioneer_Auctions'] = 'بايونير للمزادات';
        $lang['Browse_All_Auctions'] = 'تصفح جميع المزادات';
        $lang['Bidding_over'] = 'العطاءات';
        $lang['welcome'] = 'أهلا بك';
        $lang['email_used'] = 'سيتم استخدام عنوان البريد الإلكتروني هذا لإنشاء كلمة مرور جديدة';
        $lang['enter_otp'] = 'أدخل رمز OTP';
//        $lang['otp_code'] = 'رمز OTP';
        $lang['otpp'] = 'OTP';
        $lang['one_time_password'] = 'كلمة السر لمرة واحدة';
        $lang['enter_one_pass'] = 'أدخل كلمة المرور لمرة واحدة';
//        $lang['view_live_bid'] = 'مشاهدة العطاءات الحية';
        $lang['auction_on'] = 'المزاد على';
        $lang['view_live_bidding'] = 'عرض العطاءات الحية';
//        $lang['placee_bid'] = 'مكان العطاء';
        $lang['photos'] = 'الصور';
        $lang['time_left'] = 'الوقت المتبقي';
        $lang['Auction_details'] = 'تفاصيل المزاد';

        $lang['sold_to'] = 'بيعت ل';
//        $lang['for_aed'] = 'for AED';
        $lang['for_aed_new'] = 'إلى عن عل ';


        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Arabic Labels', 'appVersion' => $version, 'Result' => $lang));

    }

    public
    function lang_english()
    {

        $version = $this->config->item('appVersion');


        $lang['acl_update_succes'] = 'Permissions has been updated successfully!';
        $lang['no_internet_connection_msg'] = 'Please refresh to check connection';

        $lang['current_item'] = 'Current Item';

        $lang['please_login_to_see_live_auction'] = 'Please login to see live auction';
        $lang['invalid_request'] = 'Invalid Request';
        $lang['please_select_bank_slip'] = 'Please Bank Slip';
        $lang['please_enter_deposit_amount'] = 'Please enter deposit amount';
        $lang['invalid_first_name'] = 'Invalid First Name';
        $lang['invalid_last_name'] = 'Invalid Last Name';
        $lang['please_enter_mobile_number'] = 'Please enter mobile number';
        $lang['please_enter_address'] = 'Please enter address.';
        $lang['please_eneter_city'] = 'Please enter city.';
        $lang['please_enter_country'] = 'Please select country.';
        $lang['please_enter_emirates'] = 'Please enter Emirates/State.';
        $lang['unable_to_update_profile'] = 'Unable to update profile';
        $lang['password_do_not_match'] = 'Password do not match.';
        $lang['no_result'] = 'no result';




        $lang['with_you'] = 'With You';
        $lang['ends_mins'] = "m";
        $lang['ends_sec'] = "s";

        $lang['ends_day'] = 'd';
        $lang['ends_hours'] = 'h';

        $lang['select_language'] = 'Select Language';

/////Main Home Page language
//        $lang['view_detail'] = 'View detail!';        // home controller view detail on slider button.
//        $lang['auction_catagories'] = 'Auction Categories';    // home page "Auction Catagories" heading.
        $lang['featured_items'] = 'Featured Items';     // home page "Featured Items" heading.
//        $lang['start_bidding'] = 'Start Bidding';    // home page "Stat Bidding" button for featured item.
//        $lang['how_it_works'] = 'How It Works';    // home page "SHow It Works" heading on home page.
//        $lang['seller'] = 'Seller';       // home page "seller" option on home page.
//        $lang['buyer'] = 'Buyer';          // home page "Buyer" option on home page.
        $lang['register'] = 'Register'; // home page "Buyer Seller" steps on home page.
        $lang['select'] = 'Select'; // home page "Buyer Seller" steps on home page.
//        $lang['bid'] = 'Bid';     // home page "Buyer Seller" steps on home page.
//        $lang['win'] = 'Win';  // home page "Buyer Seller" steps on home page.
//        $lang['get_started_now'] = 'Get Started Now!'; // home page "Get started button" on buyer seller steps on home page.
//        $lang['our_partners'] = 'Our Partner'; // home page "Our Partner" on home page.
//        $lang['download_app'] = 'Download the Pioneer app for'; // Download the Pioneer app text on home page.
//        $lang['ios_android'] = 'iOS or Android'; // iOS or Android text on home page.
//        $lang['valuation_home'] = 'Free Car Valuation'; // iOS or Android text on home page.
//
/////// Footer for home page
////Auction sub link footer
//        $lang['auction_footer_link'] = 'Auctions'; // Auction link on footer on home page.
        $lang['about_us'] = 'About Us'; // About us link on footer on home page.
//        $lang['quality_policy_footer'] = 'Quality Policy'; // Quality Policy link on footer on home page.
//        $lang['faq_footer'] = "Faq's"; // faq sub menu footer on home page.
//        $lang['our_partners_footer'] = "Our Partners"; //Our Partners sub menu footer on home page.
//
////Quick Links sub link footer
//        $lang['quick_link'] = "Quick Links"; //Quick Links sub link footer.
//        $lang['media'] = "Media"; //Quick Links sub link footer.
//        $lang['privacy'] = "Privacy Policy"; //Quick Links sub link footer.
//        $lang['terms'] = "Terms and Conditions"; //Quick Links sub link footer.
        $lang['contact_us'] = "Contact us"; //Quick Links sub link footer.
//        $lang['gallery'] = "Gallery"; //Quick Links sub link footer.
//
//////Our Address on home page footer
//        $lang['address_footer'] = "Our Address"; //Our Address sub link footer.
//        $lang['twitter'] = "Take your web design to new heights with elix"; // Twitter pop up on  footer.
//
///// Header Home page
//        $lang['live_streaming_header'] = 'Live Streaming';
        $lang['could_not_live_streaming'] = 'Could not load live stream';
//        $lang['live_streaming_header_cap'] = 'LIVE STREAMING';
//        $lang['seller_link'] = 'Seller';      // home page header
//        $lang['buyer_link'] = 'Buyer';     // home page header.
//        $lang['auction_link'] = 'Auctions'; // home page header.
//        $lang['call'] = 'Call'; // home page header.
//
///// Home Controllers
//        $lang['account_is_not_verified'] = 'Account is not verified.';
//        $lang['verify_now'] = 'verify now';
//        $lang['phone_already_registered'] = 'Phone already registered!';
//        $lang['pioneer_verification_code_is'] = 'Your Pioneer account verification code is ';
//        $lang['please_login'] = 'Please login';
        $lang['go_to_account'] = 'Go to My Account';
//        $lang['activate_your_account'] = 'ACTIVATE YOUR ACCOUNT';
//        $lang['invalid_phone_number'] = 'Invalid phone number.';
//        $lang['email_already_registered'] = 'You already have an account with this email. Try logging in or request for a new password.';
//        $lang['Verification_code_sent_to_your_mobile'] = 'Verification code has been sent to your mobile.';
//        $lang['mobile_number_not_registered'] = 'Mobile number is not registered.';
//        $lang['email_verified'] = 'Email is verified.';
//        $lang['email_verification_failed'] = 'Email verification failed.';
//        $lang['verified_successfully_please_check_email'] = 'Verified successfully.';
//        $lang['verification_failed_try_again'] = 'Verification failed. Try again!';
//        $lang['verification_code_is_invalid'] = 'Verification code is invalid!';
//        $lang['email_has_been_sent_your_account'] = 'Email has been sent to your email account.';
//        $lang['invalid_email_address_try_again'] = 'Invalid email address .Try again!';
//        $lang['expired_link'] = 'Expired link.';
//        $lang['password_length_should_be_long'] = 'Password length should be 5 or long.';
//        $lang['your_password_is_changed'] = 'Your password is changed.';
//        $lang['Password_confirm_not_matched'] = 'Password and confirm password not matched! Try again.';
//
//
//        $lang['please_indicate_you_accept_policy'] = 'Please indicate that you accept our privacy policy.';
//
//
////evaluation
//        $lang['please_select_make'] = 'Please select make';
//        $lang['please_select_model'] = 'Please select model';
//        $lang['please_select_year'] = 'Please select year';
//        $lang['no_enginesize_available'] = 'No Engine size available';
//        $lang['year_range_to'] = 'Year range to';
//        $lang['please_select_engine_size'] = 'Please select Engine size.';
//        $lang['please_select_email'] = 'Please select email.';
//        $lang['email_is_not_valid'] = 'Email is not valid.';
//        $lang['not_avilable_record'] = 'Not Aailable Record';
//        $lang['please_select_date'] = 'Please select date.';
//        $lang['select_date_greater_than_today'] = 'select date greater than today date';
        $lang['please_select_first_name'] = 'Please select first name.';
//        $lang['please_select_number'] = 'Please select number.';
//        $lang['appointment_request_sent'] = 'Appointment Request Sent!';
//        $lang['you_notified_by_email_shortly'] = 'You will be notified by email shortly.';
//        $lang['appointment_request_failed'] = 'Appointment Request Failed!';
//        $lang['please_try_again_later'] = ' Please try again later.';
//
////visitor
//        $lang['thank_for_contacting_us'] = 'Thank you for contacting us. Our team will revert shortly.';
//
//
///////// Customer dashboard left bar
//        $lang['my_account'] = 'My Account';   // Customer dashboard left baar account sub menu.
        $lang['my_bids'] = 'My Bids';        // Customer dashboard left baar account sub menu.
//        $lang['my_docs'] = 'My Documents'; // Customer dashboard left baar account sub menu.
//        $lang['my_stock'] = 'My Stock';  // Customer dashboard left baar account sub menu.
//
        $lang['payments'] = 'Payments';   // Customer dashboard left baar "Payment" sub menu.
//        $lang['pay_deposit'] = 'Pay Deposit';        // Customer dashboard left baar "Payment" sub menu.
//        $lang['invoice_payment'] = 'Invoices/payments';    // Customer dashboard left baar "Payment" sub menu.
//        $lang['security_deposit'] = 'Security Desposit';  // Customer dashboard left baar "Payment" sub menu.
//        $lang['favorite_items'] = 'Favourites';         // Customer dashboard left baar Favourites.
//        $lang['notification'] = 'Notifications';       // Customer dashboard left baar "Notifications".
//        $lang['faq_left'] = 'FAQ';  // Customer dashboard left baar "FAQ".
        $lang['terms_condition'] = 'Terms & Conditions';  // Customer dashboard left baar "FAQ".
        $lang['sell_my_item'] = 'Sell My Item';  // Customer dashboard left baar "FAQ".
//
///// My Account page user dashboard
//        $lang['account'] = 'MY ACCOUNT';
        $lang['available_balance'] = 'Available Balance';
//        $lang['account_status'] = 'Account Status:';
//        $lang['limit'] = 'Increase limit';
//        $lang['my_bids_cap'] = 'MY BIDS';
        $lang['bid_limit'] = 'Bidding Limit';
//        $lang['increase_balance'] = 'Increase balance';
//        $lang['bid_time'] = 'Bid Time';
//        $lang['bid_name'] = 'Name';
//        $lang['your_bid'] = 'Your Bid';
//        $lang['last_bid'] = 'Last Bid';
//        $lang['bid_status'] = 'Status';
//
/////globals
//        $lang['name'] = 'Name';
//        $lang['created_on'] = 'Created On';
//        $lang['status'] = 'Status';
//        $lang['status_cap'] = 'STATUS';
//        $lang['action'] = 'Action';
        $lang['price'] = 'Price';
//        $lang['item'] = 'Items';
        $lang['search'] = 'Search';
//        $lang['amount'] = 'Amount';
//        $lang['account'] = 'Account';
//        $lang['payment'] = 'Payment';
//        $lang['detail'] = 'Detail';
        $lang['detail_english'] = 'Detail English';
        $lang['detail_arabic'] = 'Detail Arabic';
//        $lang['please_add_something'] = 'please add something';
        $lang['year'] = 'Year';
//        $lang['item_name'] = 'ITEM NAME';
        $lang['item_name_english'] = 'Item Name English';
        $lang['item_name_arabic'] = 'Item Name Arabic';
        $lang['make'] = 'Make';
        $lang['model'] = 'Model';
        $lang['select_make'] = 'Select Make';
//        $lang['select_make_first'] = 'Please Select Make First';
//        $lang['model_not_available'] = 'Model Not Available';
        $lang['select_model'] = 'Select Model';
        $lang['submit'] = 'Submit';
//        $lang['loading'] = 'Loading..';
//
/////// Uploaded Documents user dashboard
        $lang['uploaded_documents'] = 'Upload Documents';
//
///// Inventory page user dashboard
//        $lang['id'] = 'Id';
//        $lang['inventory'] = 'Inventory';
//        $lang['select_search'] = 'Select Catagory';
//        $lang['expense_detail'] = 'Expense Details';
//
///// Deposit history page user dashboard
//        $lang['deposit_history'] = 'DEPOSIT HISTORY';
//        $lang['add_deposit'] = 'Add Deposit';
//        $lang['deposit_date'] = 'Deposit Date';
//        $lang['payment_type'] = 'Payment Type';
//
//
////// SECURITY HISTORY page user dashboard
//        $lang['security_history'] = 'SECURITY HISTORY';
//
///// Invoices/Payments History page
//        $lang['invoices_heading'] = 'Invoices/Payments History';
//        $lang['win_date'] = 'Win Date';
//        $lang['due_payment'] = 'Due Payment';
//
////favourite items user dashboard
//        $lang['favourite'] = 'FAVOURITES';
//
////Notification user dash board
//        $lang['notification'] = 'Notification';
//        $lang['no_record'] = 'Coming soon!';
//
/////FAQ
//        $lang['faq'] = 'FAQs';
//
//// SELL MY ITEM user dashboard page
//        $lang['sell_item'] = 'SELL MY ITEM';
        $lang['category'] = 'Category';
        $lang['select_cat'] = 'Select Category';
        $lang['vin_number'] = 'VIN / Information Number';
        $lang['reg_code'] = 'Registration Code';
        $lang['sub_cat'] = 'Item Sub Category';
        $lang['reserve_price'] = 'Reserve Price';
//        $lang['keyword'] = 'Keyword';
//        $lang['other_info'] = 'Other Info';
//
///// PROFILE customer dashboard
//        $lang['profile'] = 'PROFILE';
        $lang['f_name'] = 'First Name';
        $lang['l_name'] = 'Last Name';
        $lang['email'] = 'Email';
//        $lang['remarks'] = 'Remarks';
        $lang['mobile'] = 'Mobile';
        $lang['address'] = 'Address';
        $lang['city'] = 'City';
        $lang['states'] = 'Emirates/States';
        $lang['country'] = 'Country';
        $lang['enter_country_name'] = 'Enter country name';
        $lang['enter_state_name'] = 'Enter state name';
//        $lang['id_nmbr'] = 'Id Number(E-Id/Passport)';
//        $lang['po_box'] = 'Po Box';
//        $lang['job_title'] = 'Job Title';
//        $lang['company_name'] = 'Company Name';
        $lang['description'] = 'Description';
        $lang['update'] = 'Update';
//        $lang['save'] = 'Save';
        $lang['upload_photo'] = 'Upload New Photo';
//
///// Forgot Password page
        $lang['change_password'] = 'Change Password';
        $lang['current_password'] = 'Current Password';
        $lang['new_password'] = 'New Password';
        $lang['confirm_password'] = 'Confirm Password';
//
///// Valuation process
//        $lang['free_valuation'] = 'FREE CAR VALUATION';
//        $lang['select_car'] = 'Select Car';
//        $lang['model_cndtion'] = 'Model and Conditions';
//        $lang['book_appointment'] = 'Book an Appointment';
//        $lang['year_range'] = 'select year';
//        $lang['get_started'] = 'GET STARTED NOW';
//        $lang['select_millage'] = 'Select Millage';
//        $lang['option'] = 'Option';
//        $lang['basic'] = 'Basic';
//        $lang['mid'] = 'Mid';
//        $lang['full'] = 'Full';
//        $lang['paint'] = 'Paints';
//        $lang['original'] = 'Original Paint';
//        $lang['partial'] = 'Partial paint';
//        $lang['total'] = 'Total Repaint';
//        $lang['has_specs'] = 'Has Gcc Specs?';
//        $lang['gcc'] = 'GCC Specs';
//        $lang['non_gcc'] = 'Non GCC Specs';
//        $lang['dont_know'] = "I don't know";
//        $lang['valuate_your_car'] = "VALUATE YOUR CAR";
//        $lang['vehicle_market_price'] = "Your Vehicle  Market Price";
//        $lang['date_time'] = "Date and time";
//
///// footer gallery
//        $lang['gallery'] = "GALLERY";
//        $lang['about'] = "ABOUT US";
//        $lang['values'] = "OUR VALUES";
//        $lang['commitment'] = "COMMITMENT";
//        $lang['quality'] = "QUALITY";
//        $lang['passion'] = "PASSION";
//        $lang['head_office'] = "HEAD OFFICE";
//        $lang['toll_free'] = "Toll Free";
//        $lang['write_us'] = "WRITE TO US";
//        $lang['write_your_msg_here'] = "Write your message here";
//        $lang['subject'] = 'SUBJECT';
//        $lang['how_can'] = 'HOW CAN WE HELP YOU?';
//
/////online_auction_final
//        $lang['min'] = 'Min';
//        $lang['max'] = 'Max';
        $lang['millage'] = 'Mileage';
        $lang['km'] = 'Km';
        $lang['miles'] = 'Miles';
        $lang['specs'] = 'Specifications';
//        $lang['imported'] = 'Imported';
        $lang['lot'] = 'Lot';
//        $lang['lot_cap'] = 'LOT';
//        $lang['view_more'] = 'View More';
//        $lang['view_less'] = 'View Less';
//        $lang['ad_filter'] = "Advance Filter's";
//        $lang['apply'] = 'Apply';
        $lang['online_auction'] = 'Online Auction';
        $lang['live_auction'] = 'Live Hall Auction';
//        $lang['close_auction'] = 'Closed Auction';
        $lang['search_result'] = 'Search Results';
//        $lang['please_login_first_to_item_into_favorite'] = 'Please login first to add item into favorite.';
        $lang['latest'] = 'Latest';
        $lang['high_price'] = 'Highest to Lowest';
        $lang['low_price'] = 'Lowest to Highest';
//        $lang['view'] = 'view';
        $lang['from'] = 'From';
        $lang['to'] = 'To';
//
//
///// auction_items page for listing
//        $lang['see_detail'] = 'See Details';
//        $lang['c_price'] = 'Current Price';
//        $lang['remain_time'] = 'Time Remaining';
        $lang['min_increment'] = 'Min Increment';
//        $lang['end_time'] = 'End Time';
        $lang['odometer'] = 'Odometer';
        $lang['bids'] = 'Bids';
//        $lang['add_list'] = 'Add List';
//        $lang['remove_list'] = 'Remove List';
        $lang['bid_now'] = 'Bid Now';
//        $lang['expired'] = 'Expired';
//
///// item_detail_final page
//        $lang['bid_info'] = 'Bid Information';
//        $lang['month'] = 'month';
        $lang['day'] = 'day';
        $lang['days'] = 'days';
//        $lang['d'] = 'd';
        $lang['hour'] = 'hour';
        $lang['hours'] = 'hours';
//        $lang['hrs'] = 'hrs';
        $lang['minute'] = 'minutes';
        $lang['place_bid'] = 'Place Bid';
//        $lang['auto_biding'] = 'Auto Biding';
//        $lang['item_required_extra_deposit'] = 'This item required extra deposit of amount';
//        $lang['deposit_now'] = 'Deposit Now';
//        $lang['you_need_make_security_deposit'] = 'Kindly note that you need to make a security deposit. To make a deposit please click on the following link.';
//        $lang['start'] = 'Start';
//        $lang['deposit_text'] = 'A deposit is required to place a bid';
//        $lang['pay_deposit'] = 'Pay Deposit';
        $lang['login_first'] = 'Please login first to place a bid';
        $lang['login'] = 'Login';
        $lang['register'] = 'Register';
//        $lang['registration_nmbr'] = 'Registration No';
//
//        $lang['login_cap'] = 'LOGIN';
//        $lang['logout'] = 'LogOut';
//        $lang['register_cap'] = 'REGISTER';
//        $lang['reg_type'] = 'REGISTRATION TYPE';
//        $lang['indivisual'] = 'individuals';
//        $lang['organization'] = 'organization';
//        $lang['how_did'] = 'How did u hear about us?';
//        $lang['web'] = 'Website';
//        $lang['social'] = 'Social Media';
//        $lang['pref'] = 'Preferred Language';
//        $lang['eng'] = 'English';
//        $lang['arab'] = 'Arabic';
//        $lang['read'] = 'I have read and agree to the';
//
//        $lang['description_small'] = 'Description';
        $lang['location'] = 'Location';
        $lang['test_report'] = 'Download Report';
        $lang['cond_report'] = 'Condition Report';
        $lang['inquire_further'] = 'If you require further assistance, please contact us';
        $lang['enquire'] = 'Enquire';
        $lang['sale_terms'] = 'Sales Information fees & Terms';
//        $lang['related_products'] = 'Related Products';
//        $lang['see_detail'] = 'See Detail';
//
//// Live_auction_items page
//        $lang['auction_location'] = 'Auction Location';
        $lang['dubai'] = 'Dubai';
//        $lang['date'] = 'Date';
//        $lang['date_cap'] = 'DATE';
//        $lang['print_c'] = 'Print Catalogue';
//        $lang['open_live'] = 'Open LiveBid';
        $lang['specs_s'] = 'Specs';
//
/////deposit page for payment
//        $lang['deposit'] = 'DEPOSIT';
//        $lang['deposit_small'] = 'Deposit';
//        $lang['increase_deposit'] = 'Increase your deposit';
        $lang['cheque'] = 'CHEQUE';
//        $lang['credit_c'] = 'CREDIT CARD';
        $lang['bank_transfer'] = 'BANK TRANSFER';
//        $lang['pay_cheque'] = 'Pay Cheque Deposit';
        $lang['visit_location'] = 'Please visit any of our office location below to finalize your cheque deposit';
//        $lang['time'] = 'Time';
//        $lang['sun_thur'] = 'Sun-Thur';
//        $lang['cheque_location'] = 'For cheque payments visit location';
        $lang['card_pay'] = 'Pay by Credit Card/Debit Card';
//
////deposit page p element
        $lang['p1'] = 'Please note that the minimum required amount is';
        $lang['p1_2'] = 'We accept Visa and Mastercard only';
        $lang['p2'] = '(Note: We accept Visa and Mastercard only. We do NOT accept Electronic cards.)';
        $lang['p3'] = 'If you select this option, you will be asked to enter your Credit Card details in the bank’s Secure Online Payment page. Once your transaction has been processed, you will be able to enter an auction and start bidding.';
        $lang['p4'] = 'The Amount will be deducted from your card and the same shall reflect on your bank statement. You can request to refund your refund anytime by calling us on 800 7466337. Refund will be credited to the same card and it usually takes 7-15 working days to get your amount back, depending on your card’s issuing bank.';
        $lang['p5'] = 'Also please note that the Security Deposit paid by Credit Card is not included in the car’s final price. Therefore, you have to pay the full amount and the Security Deposit shall be refunded to you.';
//
//        $lang['deposit_amount'] = 'Deposit Amount';
        $lang['proceed'] = 'Proceed';
//        $lang['skip'] = 'Skip';
        $lang['pay_by_bank_transfer'] = 'Pay by Bank transfer';
        $lang['ac_name'] = 'Account Name';
        $lang['bank_name'] = 'Bank Name';
        $lang['iban'] = 'IBAN';
        $lang['ac_number'] = 'Account Number';
        $lang['swift_code'] = 'Swift Code';
        $lang['routing_number'] = 'Routing Number';
        $lang['p6'] = 'Once your transfer is completed, please submit the transfer slip using the form below, or attach by email on';
        $lang['p6_2'] = 'or fax to';
        $lang['p7'] = 'Please allow up to 24hrs before the deposit is reflected on your account.';
        $lang['transfer_slip'] = 'Deposit Transfer Slip';
        $lang['d_date'] = 'Deposit Date';
        $lang['d_amount'] = 'Deposit Amount';
        $lang['submit_your_deposit'] = 'Submit your Deposit';
//
///// live_auction_detail page
//        $lang['hall_date'] = 'Hall auction date';
//        $lang['start_time'] = 'Start time';
//        $lang['final_order'] = 'MAXIMUM/FINAL ORDER';
//        $lang['multiple_bid'] = 'Please bid of multiples of';
//        $lang['hall_auto_bid'] = 'Hall auto Bid';
        $lang['cancel'] = 'Cancel';
//
///// live_online page
        $lang['current_bid'] = 'Current Bid';
        $lang['quick_bids'] = 'Quick Bids';
//        $lang['current_lot'] = 'Current Lot';
        $lang['all_lots'] = 'All Lots';
        $lang['winning_lot'] = 'Winning Lots';
        $lang['log'] = 'LOG';
        $lang['sort_to_recent'] = 'Sort to Recent';

//        $lang['vehicle_not_listed'] = 'Vehicle not listed';
//
//
////info messages
//        $lang['cancal_proxy_bid'] = 'Cancel proxy bid.';
//        $lang['you_sure_want_cancal'] = 'Are you sure you want to cancel proxy bid.';
        $lang['confirm'] = 'Confirm!';
        $lang['images'] = 'Images';
//        $lang['You_placing_auto_bid'] = 'You are placing the auto bid of';
//        $lang['sure_you_want_place_proxy_bid'] = 'Are you sure you want to place proxy bid of';
//        $lang['press_edit_button_to_apply_changes'] = 'Press "Edit" button to apply changes.';
//
////success messages
//        $lang['success_'] = 'Success!';
//        $lang['auto_bid_activated'] = 'Auto bid is activated.';
//        $lang['bid_placed_successfully'] = 'Bid placed successfully!';
//        $lang['auto_bid_updated'] = 'Auto bid is updated.';
//        $lang['auto_bid_cancelled_successfully'] = 'Auto bid cancelled successfully.';
//        $lang['logged_out_successfully'] = 'You have successfully logged out of your account.';
//        $lang['profile_updated_successfully'] = 'Profile updated successfully.';
//        $lang['image_uploaded_successfully'] = 'Image uploaded successfully.';
//        $lang['password_updated_successfully'] = 'Password updated successfully.';
//        $lang['password_confirm_password'] = 'New password and confirm password do not match.';
//        $lang['invalid_current_password'] = 'Current password is incorrect.';
//        $lang['item_added_successfully'] = 'Item Added Successfully.';
//        $lang['db_error_found'] = 'DB error found. Please try again.';
        $lang['please_update_your_profile'] = 'Please update your profile first.';
//        $lang['payment_made_successfully'] = 'Payment has been made successfully.';
//        $lang['payment_failed_to_make'] = 'Payment has been failed to make.';
//        $lang['user_documents_updated'] = 'Your documents have been updated successfully.';
//        $lang['user_documents_added'] = 'User Documents Added Successfully.';
//        $lang['please_select_item'] = 'please select item.';
//        $lang['slip_has_been_added_successfully'] = 'Slip has been added successfully.';
//        $lang['updated'] = 'Updated!';
//        $lang['password_changed_successfully'] = 'Password changed successfully.';
//        $lang['user_documents_has_updated_successfully'] = 'Item documents has been updated successfully.';
//
////error messages
//        $lang['error'] = 'Error';
//        $lang['error_'] = 'Error!';
//        $lang['item_is_no_longer_available'] = 'Item is no longer available.';
//        $lang['upload_documents'] = 'Upload Documents';
//        $lang['what_auto_bidding'] = 'What is auto bidding?';
//        $lang['leave_your_maximum_bid'] = 'Leave your maximum bid amount and system will place an incremental bid of';
//        $lang['every_time_you_outbid_until'] = 'every time you outbid until you reach your maximum bid amount. Your maximum amount should be greater than the current price. After placing the amount press the START button.';
//        $lang['users_visit'] = 'users visit this item!';
//        $lang['add_favorite'] = 'Add to favorite!';
//        $lang['remove_favorite'] = 'Remove from favorite!';
//        $lang['auto_bidding_started'] = 'Auto Bidding Has Started';
//        $lang['increment'] = 'Increment';
//        $lang['increment_small'] = 'increment';
//        $lang['you_are_placing'] = 'You are placing';
//        $lang['your_bid_now'] = 'Your bid amount Will be now AED';
//        $lang['increment'] = 'increment?';
//        $lang['info'] = 'info';
//        $lang['info_cap'] = 'Info';
//        $lang['insufficient_balance'] = 'Insufficient Balance! Please Recharge.';
//        $lang['item_time_expired'] = 'Item time is expired. No more bids are allowed.';
//        $lang['bid_successfully'] = 'Bid successfully.';
//        $lang['bid_failed'] = 'Bid not available.';
//        $lang['deposit_exceeds'] = 'Your Deposit Limit Exceeds.';
//        $lang['auto_bid_not_activated'] = 'Auto bid is not activated.';
//        $lang['bid_limit_should_greater_than'] = 'Bid limit should be greater than';
//        $lang['you_not_highest_bidder'] = 'You are not the highest bidder.';
//        $lang['auction_not_initialized'] = 'Auction on this item is not initialized yet.';
//        $lang['item_sold_out'] = 'item is sold out.';
//        $lang['you_need_login'] = 'You need to login for placing bid.';
//        $lang['some_information_missed'] = 'Some information is missed. Try again!';
//        $lang['account_blocked'] = 'Your account has been blocked.';
//        $lang['only_user_login'] = 'Only user can login.';
//        $lang['invalid_username_password'] = 'Invalid username or password.';
//        $lang['your_bid_amount_must_multiple'] = 'Your bid Amount must be multiple of';
//        $lang['your_account_blocked'] = 'Your account has been blocked by Admin.';
//        $lang['no_auction_available_try_again_later'] = 'No live auction is available. Please try again later.';
//        $lang['go_to_home'] = 'Go to Home';
//        $lang['failed_cancel_auto_bid'] = 'Failed to cancel auto bid.';
//        $lang['please_select_image'] = 'Please select image.';
//        $lang['slip_has_een_failed_to_add'] = 'Slip has been failed to add.';
        $lang['min_deposit_amount_is'] = 'Min deposit amount is';
//        $lang['invalid'] = 'Invalid!';
//
//
//        $lang['no_data_available'] = 'No data available.';
//
////status
//        $lang['approved'] = 'Approved';
//        $lang['refund'] = 'Refund';
//        $lang['rejected'] = 'Rejected';
//        $lang['adjusted'] = 'Adjusted';
//        $lang['available'] = 'Available';
//        $lang['sold'] = 'Sold';
//        $lang['returned'] = 'Returned';
        $lang['in_process'] = 'In process';
        $lang['complete_bidding_process'] = 'Complete bidding process';
//        $lang['active'] = 'Active';
//        $lang['waiting_for_system_calculations'] = 'Waiting for system calculations.';
//        $lang['expenses'] = 'Expenses';
        $lang['sort_type'] = 'Sort by type';
        $lang['higher_bider'] = 'You are the highest bidder';
        $lang['not_higher_bider'] = 'You are not the highest bidder';
//        $lang['enter_here'] = 'Enter here';
//        $lang['your_limit'] = 'Your Limit';
//        $lang['coming_up'] = 'Coming Up';
//        $lang['unknown'] = 'Unknown';
//        $lang['pending'] = 'Pending';
//        $lang['not_available'] = 'N/A';
//
///// Forgot password or send sms popups
//        $lang['enter_varification_code'] = 'Enter the verification code';
//        $lang['otp'] = 'A One Time Password has been sent to';
        $lang['otp_note'] = 'Note: If you did not receive a code';
        $lang['request_new_code'] = 'Request new code';
        $lang['enter_email'] = 'Enter your email for reset password';
        $lang['results_for'] = "results for";
//        $lang['reset_link'] = 'A reset link will sent to your Email';
//
///// header sub menu
//        $lang['dashboard'] = 'Dashboard';
//        $lang['profile_small'] = 'Profile';
//        $lang['change_password_small'] = 'Change Password';
        $lang['log_out'] = 'Logout';
//
////login page
//        $lang['f_password'] = 'Forgot Password?';
        $lang['password'] = 'Password';
//        $lang['password_capital'] = 'PASSWORD';
//        $lang['why'] = 'Why Pioneer auctions';
//        $lang['access'] = 'Access to search and bid of vehicles each day';
//        $lang['access_search'] = 'Access to search and bid';
//        $lang['access_search_bid'] = 'Access to search and bid of vehicles each day';
//        $lang['select_auction'] = 'Select Auction';
        $lang['select_item'] = 'Select Item';
//        $lang['select_auction_first'] = 'Select auction first';
//        $lang['select_item_first'] = 'Select item first';
//        $lang['with'] = 'With';
//        $lang['bider'] = 'Bidder';
//
///// swal popup
//        $lang['you_are_placing'] = 'You are placing';
//        $lang['bid_amount_now'] = ' Your bid amount will be now AED ';
//        $lang['bid_amount_now_new'] = ' Your bid amount will be now ';
//        $lang['bid_success'] = 'Bid successfully.';
//        $lang['limit_exceeds'] = 'Your Deposit Limit Exceeds.';
//        $lang['auto_bid_activated_not'] = 'Auto bid not activated.';
//        $lang['bid_limit_should_greater_than'] = 'Bid limit should be greater than current bid amount.';
//        $lang['confirm'] = 'Confirm';
//        $lang['sold_out'] = 'item is sold out';
//
///// live_online_detail page
//        $lang['placing_auto_bid'] = 'You are placing the auto bid of ';
//        $lang['sure_auto_bid'] = 'Are you sure you want to place proxy bid of ';
//        $lang['sure_proxy_bid_cancel'] = 'Are you sure to cancel proxy bid.';
//        $lang['multiple'] = 'Your bid Amount must be multiple of';
//        $lang['auto_bid_activated_update'] = 'Auto bid is updated.';
//        $lang['cancel_proxy'] = 'Cancel proxy bid.';
//        $lang['time_over'] = 'Auto bid time is over.';
//
//        $lang['adjustment'] = 'Adjustments';
//        $lang['make_payment'] = 'Make Payment';
//        $lang['copyright'] = 'Copyright';
//        $lang['rights'] = 'All Rights Reserved';
//        $lang['fav'] = 'Add to favorite';
//        $lang['r_fav'] = 'Remove from favorite';
//        $lang['blnce'] = 'BALANCE';
//        $lang['c_bid_limit'] = 'CURRENT BIDDING LIMIT';
//        $lang['total_d'] = 'Total deposit';
        $lang['reset_password'] = 'RESET PASSWORD';
        $lang['confirm_password'] = 'Confirm Password';
//        $lang['password_six_digit_long'] = 'Password must be 6 digits long.';
//        $lang['not_now_thanks'] = 'Not now,thank you!';
//        $lang['edit'] = 'Edit';
//        $lang['site_title'] = 'Pioneerauctions.';
        $lang['upcoming_auction'] = 'UPCOMING AUCTIONS';
//        $lang['hall_auto_bids'] = 'Hall Auto bids';
//
//
//        $lang['what_is_auto_biding'] = 'What is auto bidding? Leave your maximum bid amount and system will place an incremental bid of';
//        $lang['what_is_auto_biding_text'] = 'every time you outbid until you reach your maximum bid amount. Your maximum amount should be greater than the current price. After placing the amount press the START button.';
///// deposit payment types key
//        $lang['cash'] = 'Cash';
//        $lang['card'] = 'Card';
        $lang['bank_transfer'] = 'Bank Transfer';
        $lang['cheque'] = 'Cheque';
//        $lang['manual_deposit'] = 'Manual Deposit';
//        $lang['adjustment'] = 'Adjustment';
//
//////user_docs
//        $lang['reqired_upload'] = 'Required Uploads';
//        $lang['docs'] = 'Documents';
//
/////toltip for security history
//        $lang['add_security'] = 'Add Security';
//        $lang['business_hr'] = 'Business Hours:';
//        $lang['bid_amount_cap'] = 'BID AMOUNT';
//        $lang['security'] = 'Security';
//
////new design keys --------------------------------------------------------------------------------------->>>>>>>>
//
//        $lang['get_free_valuation'] = 'Get a free valuation <br> at Pioneer Auctions';
//        $lang['select_date_for_auction'] = 'Select a date for<br> your auction';
//        $lang['sell_your_car'] = 'Sell your car to<br> our online bidders!';
//        $lang['featured_bids'] = 'Featured Bids';
//        $lang['latest_bids'] = 'Latest Bids';
//        $lang['popular_bids'] = 'Popular Bids';
//        $lang['original_price'] = 'Current Price AED';
//        $lang['checkout_available_auctions'] = 'Check out auctions that<br>are available right now!';
        $lang['auctions_about_to_close'] = 'Auctions About to Close';
//        $lang['view_all'] = 'View All';
        $lang['view_details'] = 'View Details';
//        $lang['new_media'] = 'Media';
//        $lang['sell_my_item_new'] = 'Sell my item';
//        $lang['register_now'] = 'Register Now';
//        $lang['auction_guides'] = 'Auction Guides';
//        $lang['auctions'] = 'Auctions';
//        $lang['about_new'] = 'About Us';
//        $lang['contact_us_new'] = 'Contact us';
//        $lang['contact_us_address'] = 'ARMS Group Building,Airport Road, Umm Ramool<br>P.O. Box 666, Dubai United Arab Emirates';
        $lang['my_profile'] = 'My Profile';
//        $lang['history'] = 'History';
//        $lang['logout'] = 'LogOut';
//        $lang['login_new'] = 'Login';
//        $lang['register_new'] = 'Register';
        $lang['sign_in_account'] = 'Sign in or Create an account';
        $lang['enter_email_address'] = 'Enter Email Address';
        $lang['enter_password'] = 'Enter Password';
        $lang['sign_in'] = 'Sign in';
        $lang['forgot_password'] = 'Forgot Password?';
        $lang['dont_have_account'] = 'Don’t have an account?';
        $lang['sign_up_now'] = 'Sign up now';
        $lang['sign_up_agree'] = 'By signing up, you agree to our';
        $lang['terms_and_conditions'] = 'Terms & Conditions';
//        $lang['enter_verification_code'] = 'Enter your verification code';
//        $lang['code'] = 'Code';
//        $lang['request_code'] = 'request new code';
//        $lang['enter_name'] = 'Enter Name';
        $lang['enter_mobile_num'] = 'Enter Mobile Number';
        $lang['create_password'] = 'Create Password';
        $lang['sign_up'] = 'Sign up';
        $lang['already_have_account'] = 'Already have an account?';
        $lang['email_address'] = 'Email address';
//        $lang['information'] = 'Information';
//        $lang['guide_on_auction'] = 'How To Bid';
//        $lang['how_to_register'] = 'How to register';
//        $lang['how_to_deposite'] = 'How to deposit';
//        $lang['terms_and_conditions_new'] = 'Terms and conditions';
//        $lang['about_us_new'] = 'About Us';
//        $lang['quality_policy'] = 'Quality policy';
        $lang['privacy_policy'] = 'Privacy Policy';
//        $lang['quick_links'] = 'Quick Links';
//        $lang['faq_new'] = 'FAQ';
//        $lang['help_center'] = 'Help Center';
        $lang['our_address'] = 'Enter Address';
//        $lang['mobile_new'] = 'Mobile';
//        $lang['email_new'] = 'Email';
//        $lang['business_hours'] = 'Business Hours';
//        $lang['pioneer_auction_right_reserved'] = 'Pioneer Auctions L.L.C. All Rights Reserved';
//        $lang['love_to_hear'] = 'We would love to hear from you!';
//        $lang['reach_to_us'] = 'Reach out to us';
//        $lang['any_questions_or_suggestions'] = 'Got a question about Pioneer Auctions? Do you have any <br> suggestions or just a query? Fill out the form below:';
//        $lang['last_name'] = 'Last Name';
//        $lang['phone_num'] = 'Phone Number';
//        $lang['message'] = 'Message';
//        $lang['customer_care'] = 'Customer Care';
//        $lang['get_in_touch'] = 'Get in touch with us.';
//        $lang['social_media'] = 'Social Media';
//        $lang['be_the_first_to_find_auctions'] = 'Be the first to find out about new auctions <br> and live events and announcements.';
//        $lang['like_us_on_facebook'] = 'Like us on Facebook';
//        $lang['today'] = 'today';
//        $lang['follow_on_instagram'] = 'Follow us on Instagram';
//        $lang['one_stop_platform'] = 'Your one-stop platform for easy bidding.';
//        $lang['fvrt_auction_platform'] = 'We Are Your Favorite Auction Platform';
        $lang['our_services'] = 'Our Services';
        $lang['free_car_val'] = 'Free Car Valuation';
        $lang['online_bidding'] = 'Online Bidding';
        $lang['live_bidding'] = 'Live Bidding';
        $lang['our_mission'] = 'Our Mission';
//        $lang['hear_from_us'] = 'Hear From Us';
//        $lang['yasir_khushi'] = 'Yasir Khushi';
//        $lang['executive_director'] = 'Executive Director, Pioneer Auctions';
        $lang['home'] = 'Home';
//        $lang['my_account_new'] = 'My Account';
//        $lang['my_details'] = 'My Details';
        $lang['personal_information'] = 'Personal Information';
//        $lang['account_information'] = 'Account Information';
//        $lang['payments_new'] = 'Payments';
//        $lang['cheque_new'] = 'Cheque';
        $lang['credit_card'] = 'Credit Card';
//        $lang['bank_transfer_new'] = 'Bank Transfer';
        $lang['timings'] = 'Timings';
//        $lang['history_new'] = 'History';
        $lang['my_bids_new'] = 'My Bids';
        $lang['no_of_bids'] = 'No of Bids';
        $lang['select_document'] = 'Select document';

//        $lang['bid_time'] = 'Bid Time';
//        $lang['name_of_item'] = 'Name of Item';
//        $lang['your_bid_new'] = 'Your Bid';
//        $lang['status_new'] = 'Status';
        $lang['my_wishlist'] = 'My Wishlist';
//        $lang['action_new'] = 'Action';
//        $lang['remove_selected'] = 'Remove Selected';
//        $lang['my_docs_new'] = 'My Documents';
        $lang['uploaded_documents_new'] = 'Add Documents';
//        $lang['name_new'] = 'Name';
//        $lang['items'] = 'Items';
//        $lang['created_on_new'] = 'Created On';
//        $lang['no_files_found'] = 'No files found.';
        $lang['documents'] = 'Documents';
//        $lang['req_upload'] = 'Required Uploads';
//        $lang['item_details'] = 'Item Details';
//        $lang['mileage'] = 'Mileage';
        $lang['mileage_type'] = 'Mileage Type';
//        $lang['km_new'] = 'KM';
//        $lang['miles_new'] = 'Miles';
        $lang['specifications'] = 'Specification';
        $lang['gcc_new'] = 'GCC';
        $lang['imported_new'] = 'Imported';
        $lang['item_location'] = 'Item Location';
//        $lang['more_info'] = 'More Information';
//        $lang['share'] = 'Share';
//        $lang['time_remaining_for_bid'] = 'Time Remaining for Bidding';
//        $lang['direct_offer'] = 'Direct Offer';
        $lang['auto_bidding'] = 'Auto Bidding';
        $lang['place_max_amount'] = 'Place your maximum offer for this lot and switch ON the above button for us to place the minimum increments on your behalf. ';
//        $lang['place_direct_offer'] = '';
//        $lang['available_for_live_auction'] = 'Available for : Live Auction';
//        $lang['login_or_register'] = 'Login or Register';
//        $lang['now_to_start_bidding'] = 'now to start bidding before time runs out!';
//        $lang['vat'] = 'VAT';
//        $lang['applicable'] = 'Applicable';
        $lang['details'] = 'Details';
        $lang['enquiry'] = 'Enquiry';
//        $lang['lot_location'] = 'Lot Location';
        $lang['open_google_maps'] = 'Open Google Maps';
//        $lang['ad_space'] = 'Ad Space';
//        $lang['you_are_placing_aed'] = 'You are placing AED';
        $lang['would_you_like_to_continue'] = 'Would you like to continue?';
        $lang['dont_have_enough_balance'] = 'Uh oh. Seems like you don’t have enough balance in your account.';
        $lang['deposite_more_money'] = 'Would you like to deposit more money in your balance';
//        $lang['deposite_money'] = 'Would you like to deposit money in your balance?';
//        $lang['take_me_to_the_payments'] = 'Take me to payments';
//        $lang['more_info_new'] = 'More Info';
//        $lang['password_small'] = 'Password';
//        $lang['confirm_password_small'] = 'Confirm Password';
//
//
//// ==================== new keys ============================
//
//        $lang['my_payable'] = 'My Payables';
//        $lang['my_payable_new'] = 'My Payable';
//        $lang['payable'] = 'Payable';
//
//        $lang['Payables_history'] = 'Payables History';
//
//        $lang['about_us_info'] = 'Since its inception in 2008, Pioneer Auctions has been a leader in the industry, living up to its name and introducing many firsts. It was among the first auction houses in the UAE to allow its customers to bid real-time, through live-streaming services. This has evolved into a seamless ‘click of a button’ auction experience that is available 24/7 via our website and our mobile app, and weekly via our auction hall. Our esteemed clientele includes leading public and private sector entities across various sectors in the UAE and end-users who want to get the best deals in the market. Our auction categories are diverse and they include everything from automobiles, to building and construction, to marine equipment and more.';
//
        $lang['about_us_car_desc'] = 'Want to know the best price for your car?  Our free car valuation tool will help you in getting the right estimate. Check Now.';
        $lang['about_us_bidding_desc'] = 'Bid on vehicles and all other listed items at your convenience during the allotted time using our user-friendly auction system.Looking for pre-owned cars or want to buy building construction or general material? Now bid online on the listed items at your own convenience.';
        $lang['about_us_live_bidding_desc'] = 'Bid online hassle free in real time at our auction hall or bid live from the comfort of your home or office. Use the built-in live stream platform integrated with our most advanced auction system to bid on your favorite items.';
        $lang['about_us_our_vision_desc'] = 'To become the go-to auction house in the MENA region by providing our customers with exceptional services and the best deals in the market.';
        $lang['about_us_our_mission_desc'] = 'To provide the highest standards of auctioneering services and bridge the gap between buyers and sellers by offering a quick, easy and reliable auction experience.';
        $lang['about_us_our_vision'] = 'Our Vision';
//
///////////////
//
        $lang['tel'] = 'Tel';
        $lang['search_here'] = 'Search here';
//        $lang['explore_now'] = 'Explore now';
//        $lang['sub_to_email'] = 'Subscribe to email notifications';
//        $lang['back_to_result'] = 'Back to results';
        $lang['back_to_auction'] = 'Back to Auction Item';
        $lang['sale_location'] = 'Sale Location';
        //$lang['end_date'] = 'End Date';
//        $lang['sales_info_terms'] = 'Sale Information fees & Terms';
        $lang['succrss_bid_text'] = 'Success! You have placed a bid for AED';
        $lang['invalid_attempt'] = 'Invalid attempt! minimum bid increment is';
//        $lang['add_to_wish_list'] = 'Add to wishlist';
//        $lang['dont_enogh_balance'] = "Uh oh. Seems like you don't have enough balance in your account";
//        $lang['would_you_like_deposit'] = "Would you like deposit more money in your balance?";
        $lang['start_date_time'] = "Start Date & Time";
//        $lang['catalog_small'] = "Catalogue";
//        $lang['live_hall_auction'] = "Live Hall Auction";
        $lang['filter'] = "Filters";
        $lang['clear'] = "Clear";
        $lang['upload_images'] = "Upload Images";
        $lang['more_information'] = "More Information";

//        $lang['advance_filter'] = "Advanced Filters";
//        $lang['views'] = "Views";
        $lang['or'] = "or";
        $lang['advanced'] = "Advanced Filters";
        $lang['featured'] = "Featured";
        $lang['apply_filters'] = "Apply Filters";
//        $lang['payments'] = "Payments";
//        $lang['item_security'] = "Item Security";
        $lang['enter_deposit_amount'] = "Enter Deposit Amount";
        $lang['enter_deposit_date'] = "Enter Deposit Date";
        $lang['enter_first_name'] = "Enter First Name";
        $lang['enter_last_name'] = "Enter Last Name";
//        $lang['stay_updated_text'] = "Stay updated with our latest bids and <br> catch us live on Instagram.";
//        $lang['showing'] = "Showing";
        $lang['of'] = "of";
        $lang['payment_status'] = "Payment Status";
//        $lang['similar'] = "Similar";
//        $lang['sale_date'] = "Sale Date";
        $lang['vin'] = 'VIN';
//        $lang['sold_out'] = 'Sold Out';
//        $lang['sold_out_new'] = 'Sold!';
//        $lang['log_sm'] = 'Log';
//        $lang['please_wait'] = 'Please Wait';
//        $lang['waiting_for_initial_bid'] = 'Waiting for initial bid amount';
//
        $lang['hall_bidder'] = 'With Hall Bidder';
//        $lang['hall_bid'] = 'Hall Bid';
//        $lang['online_bidder'] = 'Online Bidder';
//        $lang['online_bid'] = 'Online Bid';
        $lang['sold_to'] = 'sold to';
//        $lang['for_aed'] = 'for AED';
        $lang['for_aed_new'] = 'for ';
        $lang['aed'] = 'AED';
//        $lang['aed_small'] = 'AED';
        $lang['add_item'] = 'Add Item';
//        $lang['ok'] = 'OK';

        $lang['your_total_bid_will_now_be_AED'] = 'Your total bid will now be AED';
        $lang['default_increment'] = 'and default increment of AED';
        $lang['you_have_started_auto_bidding'] = 'You have started Auto Bidding with limit AED';
        $lang['success_you_have_place_increment'] = 'Success! You have placed an increment of AED';
        $lang['you_are_placing_increment_aed'] = 'You are placing an increment of AED';
        $lang['you_are_placing_autobid_aed'] = 'You are placing an Auto Bid for AED';
        $lang['the_system_will_place_minimum'] = 'The system will place the minimum increments on your behalf till it reaches your maximum bid limit.';

//        $lang['maximum_amount'] = 'Max. Bid Amount';
        $lang['enter_city'] = 'Enter city';
        $lang['enter_current_password'] = 'Enter current password';
        $lang['enter_new_password'] = 'Enter new password';
        $lang['confirm_new_password'] = 'Confirm new password';
//        $lang['choose'] = 'Choose';
//        $lang['ends_in'] = 'End Time';
//        $lang['drag_drop_image_here'] = 'Drag & drop image here';
//        $lang['docs_items'] = 'Items';
//        $lang['docs_name'] = 'Name';
//        $lang['am'] = 'am';
//        $lang['pm'] = 'pm';
//        $lang['no_file_choosen'] = 'No file chosen';
        $lang['chose_file'] = 'Choose File';
//        $lang['Stay_tuned_for_upcoming_auctions'] = 'Stay tuned for the upcoming auctions!';
//        $lang['Get_best_deals_on_vehicles_general_material_more'] = 'Get the best deals on vehicles, General Material, Building & construction material, Marine, Real Estate and more';
        $lang['enter_lot'] = 'Enter Lot';
//        $lang['bid_not_placed_try_again'] = 'Bid not placed. Try again.';
//        $lang['bid_live_on_this_item'] = 'Bid on this item live or at our auction hall on';
//        $lang['for_more_details'] = 'For more details call 800 746 6337.';
//        $lang['you_have_reached_your_bid_limit'] = 'You have reached your bid limit. Please top-up your account to increase your bid limit. Your current bid limit is ';
//
        $lang['place_auto_bid_amount'] = 'The system will place minimum increment on your behalf.';
//        $lang['you_placing_bid'] = 'You are placing an Auto Bid for ';
//        $lang['after_successs_bid_text'] = '';
        $lang['switch_on_auto_bid'] = 'Switch on auto bidding for this.';
//        $lang['comma'] = ',';
//        $lang['what_all_people_saying'] = 'What people are saying';
//        $lang['testimonial_name1'] = 'Mr Arul Das';
//        $lang['testimonial_name2'] = 'Mr Raja Zaheer ';
//        $lang['testimonial_name3'] = 'Mr Fadi';
//        $lang['testimonial_pa_customer'] = 'Pioneer Auctions Customer';
//        $lang['testimonial1'] = 'I have been dealing with Pioneer Auctions since they started. My experience has been impressive as I am satisfied with the quality of service provided by them.';
//        $lang['testimonial2'] = 'The team is wonderful, and they made the whole process of participating in the auction a pleasant experience! They did everything to make sure that we are thoroughly taken care of.';
//        $lang['testimonial3'] = 'I have been a customer of Pioneer Auctions for many years. Over the years I have bought many cars at a very good price. I am extremely happy with their service and support.';
//
//
//        $lang['subscribe_success_message'] = 'Thank you for subscribing. We have sent you a confirmation email.';
//
//
//        $lang['subscribe_empty_error'] = 'Please enter a value.';
//
//        $lang['aed_small_new'] = 'AED';
//
//        $lang['testimonial4'] = 'To be able to view about 100 cars, check inspection reports and bid on them - all in a matter of one evening! Pioneer Auctions has truly simplified the Buying/ Selling process of a car.';
//        $lang['testimonial_name4'] = 'Dhinakar';
//        $lang['time_expired'] = 'Auction Ended';
//
//
//        $lang['testimonial5'] = 'I just want to say that the auctions are awesome. It’s great for anyone wanting a good deal on vehicles, building material, construction items, IT equipment and various other items auctioned by Pioneer Auctions.';
//
//        $lang['testimonial_name5'] = 'Mr Nan Qu';
//
//
//        $lang['upcoming_new'] = 'Upcoming ';
//
//        $lang['all_model'] = 'All Models';
//
//        $lang['no_record_new'] = 'No record found!';
//
//        $lang['similar_new'] = "Similar";
//
//        $lang['select_yearTo'] = 'Select to';
//
//        $lang['register_full_name'] = 'Name as per your Emirates ID';
//
//        $lang['cond_report_new'] = 'Appraiser Report';
        $lang['report'] = 'Report';
        $lang['additional_info'] = 'Additional Info';
//        $lang['winner_model_line_1'] = 'Congratulation ! You are the highest bidder on ';
//        $lang['winner_model_line_2'] = '. Please check your email for further details.';
//        $lang['winner_model_line_3'] = 'Click the button below to view the details.';
//
//
        $lang['Pioneer_Auctions'] = 'Pioneer Auctions';
        $lang['Browse_All_Auctions'] = 'Browse All Auctions';
        $lang['Bidding_over'] = 'Bidding over';
        $lang['welcome'] = 'Welcome';
        $lang['email_used'] = 'this email address will be used to create a new password';
        $lang['enter_otp'] = 'Enter OTP Code';
//        $lang['otp_code'] = 'OTP Code';
        $lang['otpp'] = 'OTP';
        $lang['one_time_password'] = 'One Time Password';
        $lang['enter_one_pass'] = 'Enter the One Time Password';
//        $lang['view_live_bid'] = 'View Live Bidding';
        $lang['auction_on'] = 'Auction on';
//
        $lang['view_live_bidding'] = 'View Live Bidding';
//        $lang['placee_bid'] = 'Place Bid';
        $lang['photos'] = 'Photos';
        $lang['time_left'] = 'Time Left';
        $lang['Auction_details'] = 'Auction Details';

        $lang['server_error'] = 'Server Error';
        $lang['something_went_wrong'] = 'Something went wrong';
        $lang['insufficient_balance'] = 'Insufficient Balance! Please Recharge.';
        $lang['maximum_deposit_amount_is_aed'] = 'Maximum Deposit Amount is AED';

        $lang['select_slip'] = 'Select Slip';


        $codee = http_response_code(200);
        echo json_encode(array("status" => true, 'code' => $codee, "message" => 'English Labels', 'appVersion' => $version, 'Result' => $lang));

    }


//inventory end


    public
    function save_user()
    {
        $data = array(
            'first_name' => $this->input->get('first_name'),
            'last_name' => $this->input->get('last_name'),
            'email' => $this->input->get('email'),
            'phone' => $this->input->get('phone'),
        );
        if (!empty($data)) {
            $r = $this->api_model->insert($data);
            echo "successful";
        } else {
            echo "put some data";
        }

    }

    public
    function upcomingAuctions()
    {
        $date = date('Y-m-d');
        $datee['time'] = date('Y-m-d H:i:s', time());
        $data = $this->api_model->upcoming_auctions($date, 'time');
        if (!empty($data)) {

            foreach ($data as $key => $value) {
                $title = json_decode($value['title']);
                $data[$key]['title'] = $title;
            }
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $data));
        }
        // // $result=json_encode($data);
        // if (!empty($data)) {

        // }
        else {
            http_response_code(200);
            echo json_encode(array('status' => true, 'result' => ''));
        }
    }


    public
    function userAccount()
    {
        $userId = validateToken();
        // $info=$this->api_model->user_account($userId);
        $info = $this->db->get_where('crm_buyer_detail', ['id' => $userId])->row_array();
        $infoo = $this->db->get_where('live_auction_customers', ['id' => $userId])->row_array();
        $user_total_deposit = $this->api_model->user_balance($userId);

        $info['amount'] = $user_total_deposit['amount'];


        $query = $this->db->query("SELECT COUNT(DISTINCT(item_id)) as count FROM `bid` WHERE user_id = ' . $userId . '");
        $bid_count = $query->row_array();
        if (!empty($info)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'amount' => $info['amount'], 'count' => $bid_count['count']));
        } elseif (empty($info)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'amount' => 0, 'count' => 0));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'Failed'));
        }
    }


    public
    function user_detail()
    {
        $userId = validateToken();
        // $info=$this->api_model->user_account($userId);
        $info = $this->db->get_where('users', ['id' => $userId])->row_array();
        if (!empty($info)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $info));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'Failed'));
        }
    }


    public
    function product_detail()
    {
        $data = $this->api_model->product_detail();
        if (!empty($data)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $data));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'No Record Found'));
        }
    }


//     public function verify_forgot_password($email_code2)
//     {

//         $data = json_decode(file_get_contents("php://input"));

//         if(empty($email_code2)){
//             $email_code = $data->email_code;
//         }else{
//             $email_code = $email_code2;
//         }


//         $email_codes = base64_decode(urldecode($email_code));

//         $user = $this->db->get_where('users', ['reset_password_code' => $email_codes])->row_array();
//         if (!empty($user)) {

// //            $this->template->load_user('api/forgot-password');

//             http_response_code(200);
//             echo json_encode(array('status' => true, 'code' =>  $data->email_code , 'message' =>' Code is verified.'));

//         } else {
//             $data = array();
//             // echo json_encode(array('status'=>true,'message'=>'email not verified'));

//             http_response_code(400);
//             echo json_encode(array('status' => false, 'message' => ' Code is not verified.'));


// //            $this->session->set_flashdata('error', 'You are not allowed to Access URL!');
// //            $data['active_login'] = 'active';
// //            $data['login_show'] = ' show';
// //            $this->template->load_user('home/login', $data);
//             // redirect(base_url('home/login'));
//         }
//     }


    public
    function register_User_email()
    {
        $email = 'it@armsgroup.ae';
        $user = $this->db->get_where('users', ['id' => '446']);
        $user = $user->row_array();
        $user['email_verification_code'] = '1122';
        if (!empty($user)) {
            $link_activate = base_url() . 'home/verify_email/' . $user['email_verification_code'];
            $vars = [
                '{username}' => $user['fname'],
                '{email}' => $user['email'],
                '{link_activation}' => $link_activate,
                '{btn_link}' => $link_activate,
                '{btn_text}' => 'Verify'
            ];
        }
        echo $send = $this->email_template($email, $vars, true, 'register');
        print_r($send);
        die();
        exit();
    }


    public
    function RandomStringGenerator($n)
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

    public
    function get_auction_cat()
    {
        $data = $this->api_model->get_auction_cat();
        if (!empty($data)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $data));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'No record found'));
        }
    }

    public
    function detail_of_auctions()
    {
        $data = $this->api_model->detail_of_auctions();
        if (!empty($data)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $data));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'No Record Found'));
        }
    }

    public
    function editProfile()
    {
        $data_array = json_decode(file_get_contents("php://input"), true);
        $data = json_decode(file_get_contents("php://input"));

        $userId = validateToken();
        $payload_info = getPayload();
        $number = $data->mobile;

        // if ($payload_info->mobile  !=$number)
        // {
        $check_number = $this->api_model->check_user_number($number, $userId);
        if ($check_number) {
            echo json_encode(array("status" => false, "message" => 'Number already exist'));
            exit();
        } else {
            $check_mobile = $this->api_model->update_mobile($userId, $data);

        }

        // $email = $data->email;
        // if($payload_info->email != $email)
        // {
        // $result = $this->api_model->check_email_user($email,$userId);
        $result = $this->api_model->check_email_user($userId);
        if ($result) {

            echo json_encode(array("status" => false, "message" => 'Email already exist'));
            exit();
        } else {
            $check = $this->api_model->update_user($userId, $data);
        }
        $data = array(
            // 'id' => $id,
            'fname' => $data->fname,
            'lname' => $data->lname,
            // 'email' => $data->email,
            'mobile' => $data->mobile,
            'city' => $data->city,
            'reg_type' => $data->reg_type,
            'job_title' => $data->job_title,
            'id_number' => $data->id_number,
            'address' => $data->address,
            'po_box' => $data->po_box,
            'company_name' => $data->company_name,
            'vat_number' => $data->vat_number,
            'remarks' => $data->remarks,
            'description' => $data->description,
            'vat' => $data->vat,
        );

        $get_password = $this->db->get_where('users', ['id' => $userId])->row_array();
        $token = Jwt::encode($payload_info, SECRETE_KEY);
        $data1 = array(
            'user_id' => $get_password['id'],
            'token' => $token,
            'data' => $get_password
        );

        $password = hash("sha256", $get_password['password']);

        if (!empty($data)) {
            $result = $this->api_model->edit_profile($userId, $data);
            echo json_encode(array('status' => true, 'message' => 'Profile updated successfully.', 'result' => $data1, 'password' => $password));

        } else {
            echo json_encode(array('status' => false, 'message' => 'No change found.'));
            exit();
        }

    }


/// get list of user documents
    public
    function getDocuments()
    {
        $id = validateToken();
        $documents = array();
        $doc_type = $this->db->get('documents_type')->result_array();
        $details = $this->db->get_where('users', ['id' => $id])->row_array();
        $user_documents = json_decode($details['documents'], true);
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
            echo json_encode(array("status" => true, 'result' => $documents, 'document_type' => $doc_type));
        } else {
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'No document found.', 'document_type' => $doc_type));
        }
    }


//// save user documents
    public
    function saveUserDocuments()
    {
        $userId = validateToken();
        $documents_array = array();
        $data_for_delete_files = $this->api_model->users_listing($userId);
        if (isset($data_for_delete_files) && !empty($data_for_delete_files[0]['documents'])) {
            $uploaded_file_ids = json_decode($data_for_delete_files[0]['documents'], true);
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

        if (isset($_FILES) && !empty($_FILES)) {
            $path = './uploads/users_documents/';
            if (!is_dir($path . $userId)) {
                mkdir($path . $userId, 0777, TRUE);
            }
            $path = $path . $userId . '/';
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'ico|png|jpg|jpeg';
            if (isset($_FILES['id_card']['name'])) {
                $id_card = $this->files_model->upload('id_card', $config);
                if (isset($id_card['id'])) {
                    $documents_array['id_card'] = $id_card['id'];
                } else {
                    echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
                    exit();
                }
            }
            if (isset($_FILES['passport']['name'])) {
                $passport = $this->files_model->upload('passport', $config);
                if (isset($passport['id'])) {
                    $documents_array['passport'] = $passport['id'];
                } else {
                    echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
                    exit();
                }
            }
            if (isset($_FILES['driver_license']['name'])) {
                $driver_license = $this->files_model->upload('driver_license', $config);
                if (isset($driver_license['id'])) {
                    $documents_array['driver_license'] = $driver_license['id'];
                } else {
                    echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
                    exit();
                }
            }
            if (isset($_FILES['trade_license']['name'])) {
                $trade_license = $this->files_model->upload('trade_license', $config);
                if (isset($trade_license['id'])) {
                    $documents_array['trade_license'] = $trade_license['id'];
                } else {
                    echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
                    exit();
                }
            }
            $files_ids['documents'] = json_encode($documents_array);
            $user = $this->api_model->update_user($userId, $files_ids);
            if ($user) {
                echo json_encode(array("status" => true, "message" => 'Documents uploaded successfully.'));
            } else {
                echo json_encode(array("status" => false, "message" => 'Documents not uploaded.'));
            }

        } else {
            return false;
        }

    }

///delete user documents/////
    public
    function deleteDocuments()
    {
        $data = json_decode(file_get_contents("php://input"));
        $doc_id = $data->id;
        // $attach_name = $this->input->post('file_to_be_deleted');
        $attach_name = $data->name;
        $user_id = $data->user_id;
        $file_array = $this->files_model->get_file_byName($attach_name);
        if (isset($file_array) && !empty($file_array)) {
            $result_array = $this->items_model->get_customersDocs_new($user_id);
            $user_documents = json_decode($result_array['documents'], true);
            $docs_id = $user_documents['id_card'];
            if (isset($docs_id) && !empty($docs_id)) {
                $docIds_array = explode(',', $docs_id[0]['file_id']);
                if (!empty($docIds_array)) {
                    $str = $docs_id[0]['file_id'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'file_id' => $updated_str,
            ];
            print_r($updated_str);
            die();
            $result_update = $this->db->update('users', $update, ['id' => $user_id, 'document_type_id' => $d]);
            $get_image = $this->db->get_where('files', ['id' => $result_array['file_id']])->row_array();
        }
        $path = FCPATH . "uploads/users_documents/" . $user_id . "/";

        unlink(FCPATH . 'uploads/users_documents/' . $user_id . "/" . $get_image . "/");
        // $this->load->helper("file");
        // delete_files($path);

        $result = $this->files_model->delete_by_name($attach_name, $path);
        if ($result) {
            echo 'success';
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


    public
    function uploadPicture()
    {
        $userId = validateToken();
        if (isset($_FILES['image']['name'])) {
            $data_for_delete_files = $this->api_model->users_listing($userId); // fetch data
            if (!empty($data_for_delete_files)) {
                $old_data = FCPATH . 'uploads/profile_picture/' . $userId . '/' . $data_for_delete_files[0]['picture'];
                $picture = $data_for_delete_files[0]['picture'];
                $path = "uploads/profile_picture/" . $userId . "/";
                // make path
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                // If profile picture is selected
                $files = '';

                if (!empty($_FILES)) {
                    $files .= $this->uploadFiles_with_path($_FILES, $path);
                    $profile_pic_array = array(
                        'picture' => $files
                    );
                    echo json_encode(array("status" => true, "message" => 'Image updated successfully.', 'picture' => $files));
                    $user_array = $this->api_model->update_user($userId, $profile_pic_array);
                    if (is_dir($path) && file_exists($picture)) {
                        unlink($old_data);
                    }
                } else {
                    echo json_encode(array("status" => false, "message" => 'Image Not updated.'));
                }

            } else {
                return false;
            }

        } else {
            echo json_encode(array("status" => false, "message" => 'Image Not updated.'));
        }
    }

    public
    static function uploadFiles_with_path($files, $path)
    {
        if (isset($files['image']['name']) && !empty($files['image']['name'])) {
            $images = '';
            $target_path = $path; //Declaring Path for uploaded images
            $ext = explode('.', basename($files['image']['name']));//explode file name from dot(.)
            $file_extension = end($ext); //store extensions in the variable
            $new_name = md5(uniqid()) . "." . $file_extension;
            $tmpFilePath = $files['image']['tmp_name'];
            $newFilePath = $target_path . $new_name;//set the target path with a new name of image
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $images .= $new_name . ',';
            }
            $images = trim($images, ",");
            return $images;
        }
    }

    public
    static function uploadFiles_with_path_docs($files, $path)
    {
        if (isset($files['documents']['name']) && !empty($files['documents']['name'])) {
            $images = '';
            $target_path = $path; //Declaring Path for uploaded images
            $ext = explode('.', basename($files['documents']['name']));//explode file name from dot(.)
            $file_extension = end($ext); //store extensions in the variable
            $new_name = md5(uniqid()) . "." . $file_extension;
            $tmpFilePath = $files['documents']['tmp_name'];
            $newFilePath = $target_path . $new_name;//set the target path with a new name of image
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $images .= $new_name . ',';
            }
            $images = trim($images, ",");
            return $images;
        }
    }

    public
    function userAuctions()
    {
        $id = validateToken();
        $date['time'] = date('Y-m-d H:i:s', time());
        $data = $this->api_model->user_history_auctions($id);
        // $count =0;
        foreach ($data as $key => $value) {
            $data[$key]['view_count'] = $this->db->where('item_id', $value['itm_id'])->where('auction_id', $id)->from('online_auction_item_visits')->count_all_results();
            $arr = array();
            $arr[$key] = $value;
            // $count++;
        }
        if (!empty($data)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $data, 'time' => $date));
        } else {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => ''));
        }
    }


    public
    function getItems()    //get items against cat
    {
        $result1 = '';
        $result2 = '';
        $data = json_decode(file_get_contents("php://input"));
        $date['time'] = date('Y-m-d H:i:s', time());
        $id = $data->item_id;

        $headers = getAuthorizationHeader();
        if (!empty($headers)) {
            $user_id = validateToken();
            // print_r($user_id);die();

            $favorites_items = $this->db->get_where('favorites_items', ['user_id' => $user_id, 'item_id' => $id])->row_array();
            $result = $this->api_model->item_details_cat($id);
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
                $result1 = explode(',', $result['item_images']);
                foreach ($result1 as $key => $value) {
                    $result3[] = $this->db->select('name as file_name')->get_where('files', ['id' => $value])->row_array();
                }
            }
            $datafields = $this->oam->fields_data($result['category_id']);
            $fdata = array();
            foreach ($datafields as $key => $fields) {
                $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($id, $fields['id']);
                $fields['values'] = json_decode($fields['values'], true);
                $fields['data-id'] = $fields['id'];
                $item_dynamic_fields_info['value'] = str_replace(array('[', ']'), '', $item_dynamic_fields_info['value']);
                if (!empty($fields['values'])) {
                    $fields['data_value'] = $item_dynamic_fields_info['value'];
                    foreach ($fields['values'] as $key => $values) {
                        if ($values['value'] == $item_dynamic_fields_info['value']) {
                            $exclude_lable = explode('|', $values['label']);
                            $fields['data_value'] = $exclude_lable[0];
                        }
                    }
                } else {
                    $fields['data_value'] = $item_dynamic_fields_info['value'];
                }
                $fdata[] = $fields;
            }
            $fields = $fdata;
            // $dynamic_fields = $this->db->get_where('item_fields_data'['']);
            if (!empty($result['bid_options'])) {
                $result2 = explode(',', $result['bid_options']);
            }
            if (!empty($result)) {

                $balance = $this->api_model->user_balance($user_id);
                $balance['amount'] = $balance['amount'];
                echo json_encode(array(
                    'status' => true,
                    'result' => $result,
                    'time' => $date,
                    'images' => $result3,
                    'options' => $result2,
                    'user_balance' => $balance,
                    'fields' => $fields,
                    'favorites' => $favorites_items
                ));
            } else {
                echo json_encode(array('status' => true, 'result' => ''));
            }
        } //without token
        else {
            $result = $this->api_model->item_details_cat($id);
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
                $result1 = explode(',', $result['item_images']);
                foreach ($result1 as $key => $value) {
                    $result3[] = $this->db->select('name as file_name')->get_where('files', ['id' => $value])->row_array();
                }
            }
            $datafields = $this->oam->fields_data($result['category_id']);
            $fdata = array();
            foreach ($datafields as $key => $fields) {
                $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($id, $fields['id']);
                $fields['values'] = json_decode($fields['values'], true);
                $fields['data-id'] = $fields['id'];
                $item_dynamic_fields_info['value'] = str_replace(array('[', ']'), '', $item_dynamic_fields_info['value']);
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
                echo json_encode(array('status' => true, 'result' => $result, 'time' => $date, 'images' => $result3, 'fields' => $fields));
            } else {
                echo json_encode(array('status' => true, 'result' => ''));
            }

        }
    }

    public
    function getNotification()
    {
        $userId = validateToken();
        // $data = json_decode(file_get_contents("php://input"));
        $info = $this->db->get_where('notification', ['receiver_id' => $userId])->result_array();
        foreach ($info as $key => $detail) {
            $info[$key]['message'] = ucfirst($detail['message']);
            $info[$key]['type'] = ucfirst($detail['type']);
        }
        $arr = array();
        if (!empty($info)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'result' => $info));
        } else {
            http_response_code(200);
            echo json_encode(array('status' => true, 'result' => $arr));
        }
    }

    public
    function notificationStatus()
    {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $status = 'read';
        $result = $this->api_model->notificationStatus($id, $status);

        if (!empty($result)) {
            echo json_encode(array("status" => true));
        } else {
            echo json_encode(array("status" => false));
        }

    }


    public
    function Logout()
    {
        $id = $this->input->post('id');
        $data = $this->api_model->get_users($id);
        if (!empty($id)) {
            if ($id == $data['id']) {
                $this->session->sess_destroy();
                echo "user logout successfully";
            } else {
                echo "user not exist";
            }
        }
    }

    public
    function contact()
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
        if ($result == true) {
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $result));
            exit();
        }
        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'user_id' => $this->input->post('user_id')
        );
        $result = $this->api_model->contact($data);
        if (!empty($result)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $result));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'error'));
        }
    }

    public
    function car_valuation()
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
            if (isset($result) && !empty($result) && count($result) > 0) {

                $orig_price = $result['price'];

                // check year depreciation
                $get_year_depre = $this->db->query("select year_depreciation from valuation_years where  (`make_id` = '" . $valuation_make_id .
                    "' AND `model_id` = '" . $valuation_model_id . "') AND (year = '" . $year_to . "') ");
                $get_year_depre = $get_year_depre->row_array();


                if (count($get_year_depre) > 0) {
                    $get_year_depre['year_depreciation'] = (float)$get_year_depre['year_depreciation'];
                    if ($get_year_depre['year_depreciation'] > 0) {
                        $year_valuate_price = $orig_price * ($get_year_depre['year_depreciation'] / 100);
                        $orig_price = $orig_price - $year_valuate_price;
                    }
                }

                // check mileage depreciation
                $get_mileage_depre = $this->db->query("select millage_depreciation from valuation_millage where mileage_id = '" . $valuation_milleage_id . "' ");
                $get_mileage_depre = $get_mileage_depre->row_array();
                if (count($get_mileage_depre) > 0) {
                    if ($get_mileage_depre['millage_depreciation'] > 0) {

                    }
                }
                $id = 1;
                // check global config depreciation
                $get_global_depre = $this->db->query("select * from valuation_config_setting where created_by='" . $id . "'");
                $get_global_depre = $get_global_depre->row_array();
                if (count($get_global_depre) > 0) {
                    // check paint depreciation
                    if ($get_global_depre['config_setting_paint'] != "" && $valuate_paint != "") {
                        $config_setting_paint = json_decode($get_global_depre['config_setting_paint'], 1);
                        //print_r($config_setting_paint);
                        $config_setting_paint = $config_setting_paint[$valuate_paint];
                        if ($config_setting_paint > 0) {
                            $config_setting_paint = (float)$config_setting_paint;

                        }

                    }
                    $config_setting_specs = '';
                    $config_option = '';

                    if (!empty($valuate_gc)) {
                        // check paint depreciation
                        if ($get_global_depre['config_setting_specs'] != "") {
                            $config_setting_specs = json_decode($get_global_depre['config_setting_specs'], 1);
                            $config_setting_specs = $config_setting_specs[$valuate_gc];
                            if ($config_setting_specs > 0) {

                            }

                        }
                    }

                    if (!empty($valuate_Opt)) {
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
                $data = number_format($orig_price, 2);
                // email send setting

                $to = $email;
                $subject = "Your Car Evaluation Result";
                // $txt = "Your Email is : ".$email."\n\n Your Password is :".$password;

                $template_name = "user registration";
                $q = $this->db->query('select * from crm_email_template where slug = "car-evaluation"');
                $email_message = $q->row_array();

                if (!empty($email_message)) {
                    $make = $this->db->get_where('valuation_make', ['id' => $valuation_make_id])->row_array();
                    $model = $this->db->get_where('valuation_model', ['id' => $valuation_model_id])->row_array();

                    $milleage = $this->db->get_where('milleage', ['id' => $valuation_milleage_id])->row_array();
                    $make = $this->db->get_where('valuation_make', ['id' => $valuation_make_id])->row_array();
                    $engine_size = $this->db->get_where('valuation_enginesize', ['id' => $engine_size_id])->row_array();
                    $option = $this->db->get_where('valuate_cars_options', ['id' => $valuate_option])->row_array();
                    // print_r($email_message);die('asaaaa');


                    $email_messagee = str_replace("{valuation_make}", $make['title'], $email_message['body']);
                    $email_messagee = str_replace("{valuation_model}", $model['title'], $email_messagee);
                    $email_messagee = str_replace("{valuation_year}", $year_to, $email_messagee);
                    $email_messagee = str_replace("{valuation_enginesize}", $engine_size['title'], $email_messagee);
                    $email_messagee = str_replace("{option}", $option['title'], $email_messagee);
                    $email_messagee = str_replace("{paint}", $valuate_paint, $email_messagee);
                    // $email_messagee = str_replace("{valuate_gc}", $valuate_gc,$email_message['body']);
                    $email_messagee = str_replace("{valuation_price}", $data, $email_messagee);
                    // print_r($email_messagee);die('aaaaa');
                    $to = $this->input->post('email');
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
            } else {
                echo json_encode(array("status" => TRUE, 'message' => 'Not Aailable Record', 'price' => $data));
                // redirect(base_url().'cars/car_valuation');
            }
            // echo json_encode(array("status" => TRUE, "message" =>'success','result'=>$result));
        }
    }

    public
    function email_template($email = '', $vars = array(), $btn = false, $register = 'register')
    {
        // if(!empty($slug)){
        $notification = 'welcome';
        $data = array();
        $data = [
            'btn' => $btn,
            'notification' => $notification
        ];
        $email_message = $this->load->view('email_templates/register', $data, true);
        if ($vars) {
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

    public
    function getNumber($n)
    {
        $characters = '0123456789';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }

    public
    function getTerms()
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
            echo json_encode(array("status" => true, "message" => 'terms and condition', 'result' => $data));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'terms and condition not available'));
        }
    }

    public
    function getInventory()
    {
        $id = validateToken();
        $terms_info = $this->api_model->inventory_list($id);
        $record = 0;
        $output = array();
        $response = array();
        $base_url_img = base_url('assets_admin/images/product-default.jpg');
        $image = $base_url_img;
        if (isset($terms_info) && !empty($terms_info)) {
            foreach ($terms_info as $value) {
                $item_id = '';
                $file_name = '';
                $image = $base_url_img;
                $item_id = $value['id'];
                $images_ids = explode(",", $value['item_images']);
                $item_detail_url = base_url('items/details/') . urlencode(base64_encode($value['id']));
                $make = $this->db->get_where('item_makes', ['id' => $value['make']])->row_array();
                $detail_data = $this->db->get_where('item_fields_data', ['item_id' => $value['id'], 'category_id' => $value['category_id']])->result_array();
                $detail_data_str = implode(",", array_column($detail_data, 'value'));
                $model = $this->db->get_where('item_models', ['id' => $value['model']])->row_array();
                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                if (!empty($value['item_images']) || !empty($value['item_attachments'])) {
                    if (isset($files_array[0]['name']) && !empty($files_array[0]['name'])) {
                        $file_name = $files_array[0]['name'];
                        $image = base_url('uploads/items_documents/') . $item_id . '/' . $file_name;
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
                echo json_encode(array("status" => true, "message" => 'inventory data', 'result' => $output));
            }
        } else {
            http_response_code(200);
            echo json_encode(array("status" => true, "message" => '', 'result' => ''));
        }
    }


    public
    function inventry_item_detail()    //get items against cat
    {
        $user_id = validateToken();
        // print_r($user_id);die();
        $result3 = array();
        $data = json_decode(file_get_contents("php://input"));
        $date['time'] = date('Y-m-d H:i:s', time());
        $id = $data->item_id;
        $result = $this->db->get_where('item', ['id' => $id])->row_array();

        if (!empty($result['item_images'])) {
            $result1 = explode(',', $result['item_images']);
            foreach ($result1 as $key => $value) {
                $result3[] = $this->db->select('name as file_name')->get_where('files', ['id' => $value])->row_array();
            }
        }
        $datafields = $this->oam->fields_data($result['category_id']);
        $fdata = array();
        foreach ($datafields as $key => $fields) {
            $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($id, $fields['id']);
            $fields['values'] = json_decode($fields['values'], true);
            $fields['data-id'] = $fields['id'];
            $item_dynamic_fields_info['value'] = str_replace(array('[', ']'), '', $item_dynamic_fields_info['value']);
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

            echo json_encode(array('status' => true, 'result' => $result, 'time' => $date, 'images' => $result3, 'fields' => $fields));
        } else {
            echo json_encode(array('status' => true, 'result' => ''));
        }
    }

    public
    function getInventoryByItems()
    {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->item_id;
        $terms_info = $this->api_model->inventory_listByItems($id);
        $record = 0;
        $output = array();
        $response = array();
        $base_url_img = base_url('assets_admin/images/product-default.jpg');
        $image = $base_url_img;
        if (isset($terms_info) && !empty($terms_info)) {
            foreach ($terms_info as $value) {
                $item_id = $value['id'];
                $images_ids = explode(",", $value['item_images']);
                $item_detail_url = base_url('items/details/') . urlencode(base64_encode($value['id']));
                $make = $this->db->get_where('item_makes', ['id' => $value['make']])->row_array();
                $detail_data = $this->db->get_where('item_fields_data', ['item_id' => $value['id'], 'category_id' => $value['category_id']])->result_array();
                $detail_data_str = implode(",", array_column($detail_data, 'value'));
                $model = $this->db->get_where('item_models', ['id' => $value['model']])->row_array();
                $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                if (!empty($value['item_images']) || !empty($value['item_attachments'])) {
                    if (isset($files_array[0]['name']) && !empty($files_array[0]['name'])) {
                        $file_name = $files_array[0]['name'];
                        $base_url_img = base_url('uploads/items_documents/') . $item_id . '/' . $file_name;
                        $image = $base_url_img;
                    } else {
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
                    'make' => $make['title'],
                    'model' => $model['title'],
                    'item_status' => $value['item_status'],
                    'image' => $image,
                );

            }
            if (!empty($terms_info)) {
                http_response_code(200);
                echo json_encode(array("status" => true, "message" => 'inventory data', 'result' => $output));
            }
        } else {
            http_response_code(200);
            echo json_encode(array("status" => true, "message" => '', 'result' => ''));
        }
    }

    public
    function carValuation()
    {
        $data = json_decode(file_get_contents("php://input"));
        $valuation_make_id = $data->valuation_make_id;
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
        if (isset($result) && !empty($result) && count($result) > 0) {

            $orig_price = $result['price'];

            // check year depreciation
            $get_year_depre = $this->db->query("select year_depreciation from valuation_years where  (`make_id` = '" . $valuation_make_id .
                "' AND `model_id` = '" . $valuation_model_id . "') AND (year = '" . $year_to . "') ");
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
            $get_mileage_depre = $this->db->query("select millage_depreciation from valuation_millage where mileage_id = '" . $valuation_milleage_id . "' ");
            $get_mileage_depre = $get_mileage_depre->row_array();
            if (count($get_mileage_depre) > 0) {
                if ($get_mileage_depre['millage_depreciation'] > 0) {

                }
            }
            $id = 1;
            // check global config depreciation
            $get_global_depre = $this->db->query("select * from valuation_config_setting where created_by='" . $id . "'");
            $get_global_depre = $get_global_depre->row_array();
            if (count($get_global_depre) > 0) {
                // check paint depreciation
                if ($get_global_depre['config_setting_paint'] != "" && $valuate_paint != "") {
                    $config_setting_paint = json_decode($get_global_depre['config_setting_paint'], 1);
                    //print_r($config_setting_paint);
                    $config_setting_paint = $config_setting_paint[$valuate_paint];
                    if ($config_setting_paint > 0) {
                        $config_setting_paint = (float)$config_setting_paint;

                    }

                }
                $config_setting_specs = '';
                $config_option = '';

                if (!empty($valuate_gc)) {
                    // check paint depreciation
                    if ($get_global_depre['config_setting_specs'] != "") {
                        $config_setting_specs = json_decode($get_global_depre['config_setting_specs'], 1);
                        $config_setting_specs = $config_setting_specs[$valuate_gc];
                        if ($config_setting_specs > 0) {

                        }

                    }
                }

                if (!empty($valuate_Opt)) {
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
            $data = number_format($orig_price, 2);
            // email send setting

            $to = $email;
            $subject = "Your Car Evaluation Result";
            // $txt = "Your Email is : ".$email."\n\n Your Password is :".$password;

            $template_name = "user registration";
            $q = $this->db->query('select * from crm_email_template where slug = "car-evaluation"');
            $email_message = $q->row_array();

            if (!empty($email_message)) {
                $make = $this->db->get_where('valuation_make', ['id' => $valuation_make_id])->row_array();
                $model = $this->db->get_where('valuation_model', ['id' => $valuation_model_id])->row_array();

                $milleage = $this->db->get_where('milleage', ['id' => $valuation_milleage_id])->row_array();
                $make = $this->db->get_where('valuation_make', ['id' => $valuation_make_id])->row_array();
                $engine_size = $this->db->get_where('valuation_enginesize', ['id' => $engine_size_id])->row_array();
                $option = $this->db->get_where('valuate_cars_options', ['id' => $valuate_option])->row_array();
                // $paint = $this->db->get('item_category_fields')->row_array();
                // $paint_dcode = json_decode($paint['values']);
                // $paint_result =  $this->db->get_where('item_category_fields',['values'=>$valuate_paint])->row_array();
                // $option =  $this->db->get_where('valuate_cars_options',['id'=>$valuate_option])->row_array();
                // print_r($email_message);die('asaaaa');
                $email_messagee = str_replace("{valuation_make}", $make['title'], $email_message['body']);
                $email_messagee = str_replace("{valuation_model}", $model['title'], $email_messagee);
                $email_messagee = str_replace("{valuation_year}", $year_to, $email_messagee);
                $email_messagee = str_replace("{valuation_enginesize}", $engine_size['title'], $email_messagee);
                $email_messagee = str_replace("{option}", $valuate_option, $email_messagee);
                $email_messagee = str_replace("{paint}", $valuate_paint, $email_messagee);
                // $email_messagee2 = $valuate_gc;
                // $email_messagee = str_replace("{valuate_gc}", $valuate_gc,$email_messagee);
                $email_messagee = str_replace("{valuation_price}", $data, $email_messagee);
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
            echo json_encode(array('message' => 'error', 'result' => 'Valuation failed.'));
        }
    }

    public
    function getMakes()
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->db->select('title,valuation_make.id');
        $this->db->from('valuation_make');
        $this->db->order_by('title', 'asc');
        $valuation_make = $this->db->get()->result_array();

        if (!empty($valuation_make)) {
            echo json_encode(array('status' => true, 'result' => $valuation_make));
        } else {
            echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
        }
    }


//// get the make when save item /////
    public
    function get_makes_for_inspection()
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->db->select('*');
        $this->db->from('item_makes');
        // $this->db->where('id',);
        $inspection = $this->db->get()->result_array();
        foreach ($inspection as $key => $inspections) {
            $item_name = json_decode($inspections['title']);
            $inspection[$key]['title'] = $item_name;
        }
        if (!empty($inspection)) {
            echo json_encode(array('status' => true, 'result' => $inspection));
        } else {
            echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
        }
    }

//// get the models when save item /////
    public
    function get_models_for_inspection()
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->db->select('*');
        $this->db->from('item_models');
        $this->db->where('make_id', $data->make_id);
        $inspection = $this->db->get()->result_array();
        foreach ($inspection as $key => $inspections) {
            $item_name = json_decode($inspections['title']);
            $inspection[$key]['title'] = $item_name;
        }
        if (!empty($inspection)) {
            echo json_encode(array('status' => true, 'result' => $inspection));
        } else {
            echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
        }
    }

    public
    function getModels()
    {
        $data = json_decode(file_get_contents("php://input"));
        $d = $data->make_id;
        $this->db->select('valuation_make_id,valuation_model.title,valuation_model.id');
        $this->db->from('valuation_model');
        $this->db->where('valuation_make_id', $data->make_id);
        $this->db->join('valuation_make', 'valuation_make.id=valuation_model.valuation_make_id', 'inner');
        $this->db->order_by('valuation_model.title', 'asc');
        $valuation_make = $this->db->get()->result_array();
        if (!empty($valuation_make)) {
            echo json_encode(array('status' => true, 'result' => $valuation_make));
        } else {
            echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
        }
    }

    public
    function getEngine()
    {
        $data = json_decode(file_get_contents("php://input"));
        $model_id = $data->model_id;
        $make_id = $data->make_id;
        $year = $data->year;
        $query = $this->db->query('select engine_size_id from valuation_price where valuation_make_id = "' . $make_id . '" and valuation_model_id = "' . $model_id . '"and year = "' . $year . '"')->result_array();
        foreach ($query as $value) {
            $result = array();
            $result[] = $this->db->query('select id, title from valuation_enginesize where id= "' . $value['engine_size_id'] . '"')->row_array();
        }
        if (!empty($result)) {
            echo json_encode(array('error' => false, 'result' => $result));
        } else {
            echo json_encode(array('error' => true,));
        }
    }

    public
    function getYear()
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->db->select('valuation_years.id,valuation_years.year');
        $this->db->from('valuation_years');
        $this->db->where('make_id', $data->make_id);
        $this->db->where('model_id', $data->model_id);
        // $this->db->where('model_id',$data->model_id);
        $valuation_make = $this->db->get()->result_array();
        if (!empty($valuation_make)) {
            echo json_encode(array('status' => true, 'result' => $valuation_make));
        } else {
            echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found.'));
        }
    }

    public
    function getMillage()
    {
        $this->db->select('mileage_label,milleage.id');
        $this->db->from('milleage');
        $this->db->order_by('milleage', 'ASC');
        $valuation_make = $this->db->get()->result_array();
        if (!empty($valuation_make)) {
            echo json_encode(array('status' => true, 'result' => $valuation_make));
        } else {
            echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
        }
    }

    public
    function getOptions()
    {
        $this->db->select('title,valuate_cars_options.id');
        $this->db->from('valuate_cars_options');
        $valuation_make = $this->db->get()->result_array();
        if (!empty($valuation_make)) {
            echo json_encode(array('status' => true, 'result' => $valuation_make));
        } else {
            echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found'));
        }
    }

    public
    function getPaint()
    {
        $this->db->select('config_setting_paint,valuation_config_setting.id');
        $this->db->from('valuation_config_setting');
        $valuation_make = $this->db->get()->result_array();
        if (!empty($valuation_make)) {
            $newArr = array();
            foreach ($valuation_make as $key => $value) {
                $json = $value['config_setting_paint'];
                if (!empty($json)) {
                    $arr = json_decode($json);
                    foreach ($arr as $key1 => $a) {
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

    public
    function getSpecs()
    {
        $this->db->select('config_setting_specs');
        $this->db->from('valuation_config_setting');
        $valuation = $this->db->get()->result_array();
        if (!empty($valuation)) {
            $newArr = array();
            foreach ($valuation as $key => $value) {
                $json = $value['config_setting_specs'];
                if (!empty($json)) {
                    $arr = json_decode($json);
                    foreach ($arr as $key1 => $a) {
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

    public
    function bookAppointment()
    {
        $data = json_decode(file_get_contents("php://input"));
        $result = array(
            'date' => $data->date,
            'time' => $data->time,
            'fname' => $data->fname,
            'lname' => $data->lname,
            'number' => $data->number
        );
        if (!empty($result)) {
            $email_message['subject'] = 'Book Appointment';
            $email_messagee = '"' . $result['fname'] . ' ' . $result['lname'] . '"Your Appointment is schdule :"' . $result['date'] . '"at "' . $result['time'] . '""' . $result['date'] . '"';
            $to = $data->email;
            $this->email->to($to);
            $this->email->subject($email_message['subject']);
            $this->email->message($email_messagee);
            $this->email->send();
            echo json_encode(array('error' => false, 'message' => 'Appointment booked ! check you email'));
        } else {
            echo json_encode(array('error' => true, 'message' => 'error'));
        }
    }

    public
    function contactUs()
    {
        $data = $this->db->get('contact_us')->row_array();
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

    public
    function contactEmailSend()
    {
        $data = json_decode(file_get_contents("php://input"));
        $data2 = $this->api_model->contactUs();
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
    public
    function get_item_categories()
    {
        $data = array();
        $category_list = $this->api_model->get_item_category_active();
        if ($category_list) {
            foreach ($category_list as $key => $value) {
                $title = json_decode($value['title']);
                $category_list[$key]['title'] = $title;
            }
            echo json_encode(array('status' => true, 'result' => $category_list));
        } else {
            echo json_encode(array('status' => true, 'result' => '', 'message' => 'No record found.'));
        }
    }

//item makes


//item subcategories


///// Millage type against item_id /////
    public
    function getMillageType()
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->db->select('item.id,item.mileage_type,item.make,item.model');
        $this->db->from('item');
        $this->db->where('make', $data->make_id);
        $this->db->where('model', $data->model_id);
        $valuation_make = $this->db->get()->result_array();
        if (!empty($valuation_make)) {
            echo json_encode(array('status' => true, 'result' => $valuation_make));
        } else {
            echo json_encode(array('status' => true, 'result' => array(), 'message' => 'no result found.'));
        }
    }

////////// End Millage Type //////////


//item model


//category dynamic fields


    public
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    public
    function sellItems()
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
            if (isset($item_data->registrationCode) && !empty($item_data->registrationCode)) {
                $posted_data['registration_no'] = $item_data->registrationCode;
            }
            if (isset($item_data->subCategory) && !empty($item_data->subCategory)) {
                $posted_data['subcategory_id'] = $item_data->subCategory;
            }
            if (isset($item_data->keyword) && !empty($item_data->keyword)) {
                $posted_data['keyword'] = $item_data->keyword;
            }
            if (isset($item_data->price) && !empty($item_data->price)) {
                $posted_data['price'] = $item_data->price;
            }
            if (isset($item_data->vinNo) && !empty($item_data->vinNo)) {
                $posted_data['vin_number'] = $item_data->vinNo;
            }
            if (isset($item_data->year) && !empty($item_data->year)) {
                $posted_data['year'] = $item_data->year;
            }
            if (isset($item_data->makeId)) {
                $posted_data['make'] = $item_data->makeId;
                $posted_data['model'] = $item_data->modelId;
            }
            $result = $this->api_model->insert_item($posted_data);
            $path = "uploads/items_documents/" . $result . "/qrcode/";
            // print_r($posted_data);die();
            // make path
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $qrcode_name = $this->generate_code($result, $path, $posted_data);
            if (!empty($qrcode_name)) {
                $barcode_array = array(
                    'barcode' => $qrcode_name
                );
                $this->api_model->update_item($result, $barcode_array);
            }
            foreach ($item_dynamic_data->dynamic as $dynamic_keys => $dynamic_values) {
                $ids_arr[0] = $dynamic_values->id;
                if (isset($dynamic_values->newvalue)) {
                    $dynamic_values_new = $dynamic_values->newvalue;
                } else {
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
            } else {
                echo json_encode(array('status' => false, 'message' => 'Item failed to add.'));
            }

        } else {
            echo json_encode(array('status' => false, 'message' => 'Item failed to add.'));
        }
    }

    public
    function getVehicle()  //// select model and type againt vehicle
    {
        $id = $this->input->post('id');
        $result = $this->api_model->catList($id);
        if (!empty($result)) {
            echo json_encode(array('error' => false, 'result' => $result));
        } else {
            echo json_encode(array('error' => true, 'message' => 'no data found'));
        }
    }

    public
    function getGeneral()  //// select category general
    {
        $id = $this->input->post('id');
        $result = $this->api_model->GeneralList($id);
        if (!empty($result)) {
            echo json_encode(array('error' => false, 'result' => $result));
        } else {
            echo json_encode(array('error' => true, 'message' => 'No Data Found'));
        }
    }

    public
    function getFaq()  //// get the faq list
    {
        $faq_info = $this->api_model->getFaq();
        if (!empty($faq_info)) #
        {
            $faqs = array();
            foreach ($faq_info as $key => $value) {
                $title = json_decode($value['question']);
                $description = json_decode($value['answer']);
                $faqs[$key]['id'] = $value['id'];
                $faqs[$key]['question']['english'] = $title->english;
                $faqs[$key]['question']['arabic'] = $title->arabic;
                $faqs[$key]['answer']['english'] = $description->english;
                $faqs[$key]['answer']['arabic'] = $description->arabic;
            }
            echo json_encode(array("status" => true, "message" => 'Faq', 'result' => $faqs));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'Faq not available'));
        }
    }

//// get the faq list
    public
    function socialLinks()
    {
        $social = $this->db->get('social_links')->row_array();
        if (!empty($social)) #
        {
            echo json_encode(array("status" => true, "message" => 'social links', 'result' => $social));
        } else {
            http_response_code(400);
            echo json_encode(array('status' => false, 'message' => 'Socail media not available'));
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


    public
    function BankTransfer()  //// upload the deposit slip and admin will receive the email
    {
        $userId = validateToken();
        $payload_info = getPayload();
        if (isset($_FILES['documents']['name'])) {
            $data_for_delete_files = $this->api_model->users_listing($userId); // fetch data
            if (!empty($data_for_delete_files)) {
                $old_data = FCPATH . 'uploads/users_documents/' . $userId . '/' . $data_for_delete_files[0]['picture'];
                $picture = $data_for_delete_files[0]['picture'];
                $path = "uploads/users_documents/" . $userId . "/";
                // make path
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                // If profile picture is selected
                $files = '';
                if (!empty($_FILES)) {
                    $files .= $this->uploadFiles_with_path_docs($_FILES, $path);
                    $profile_pic_array = array(
                        'documents' => $files
                    );
                    echo json_encode(array("status" => true, "message" => 'Deposit slip uploaded'));
                    $user_array = $this->api_model->update_user($userId, $profile_pic_array);
                    if (is_dir($path) && file_exists($picture)) {
                        unlink($old_data);
                    }
                    $email_message = 'Deposit';
                    $email_messagee = 'deposit slip uploaded by user';
                    $to = 'it@armsgroup.ae';
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

    public
    function categoryCount()
    {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $data = 0;
        $result = $this->api_model->categoryCount($id);
        if (!empty($result)) {
            echo json_encode(array("status" => true, 'result' => $result));
        } else {
            echo json_encode(array("status" => false, 'result' => $data));
        }
    }

    public
    function palceBids()
    {
        $id = validateToken();
        $posted_data = json_decode(file_get_contents("php://input"), true);
        $data = array();
        $output = array();
        $item = $this->db->get_where('auction_items', ['item_id' => $posted_data['item_id']])->row_array();
        $data['user_id'] = $id;
        $data['date'] = date('Y-m-d', time());
        $data['item_id'] = $posted_data['item_id'];
        // $data['item_id']     = $item['item_id'];
        $data['start_price'] = $item['bid_start_price'];
        $data['end_price'] = $posted_data['bid_amount'];
        $data['auction_id'] = $item['auction_id'];
        $data['buyer_id'] = $id;
        $data['bid_amount'] = $posted_data['bid_amount'];
        $data['bid_time'] = date('Y-m-d H:i:s', time());
        $data['bid_status'] = 'pending';
        $insert = $this->db->insert('bid', $data);

        if ($insert) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Bid successful.'));
        } else {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Bid failed'));
        }
    }

    public
    function userDeposite()
    {
        $id = validateToken();
        $data = json_decode(file_get_contents("php://input"));
        $date = $data->date;
        $user = $this->db->get_where('users', ['id' => $id])->row_array();
        if (empty($user['mobile']) || empty($user['email']) || empty($user['address']) || empty($user['po_box']) || empty($user['fname']) || empty($user['lname']) || empty($user['city']) || empty($user['city'])) {
            echo json_encode(array("status" => true, 'msg' => 'please update your profile first'));
            echo json_encode(array('msg' => 'success', 'response' => 'package_payment_shipping_failed'));
        } else {
            // 3. load PayTabs library
            $merchant_email = 'it@armsgroup.ae';
            $secret_key = '1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
            $merchant_id = '10050328';

            $params = [
                'merchant_email' => $merchant_email,
                'merchant_id' => $merchant_id,
                'secret_key' => $secret_key
            ];
            $this->load->library('Paytabs', $params);
            $order_id = $id;
            $invoice = [
                "site_url" => "https://pa.yourvteams.com",
                "return_url" => base_url('customer/dashboard'),
                "title" => 'customer',
                "cc_first_name" => 'customer',
                "cc_last_name" => 'customer',
                "cc_phone_number" => $user['mobile'],
                "phone_number" => $user['mobile'],
                "email" => $user['email'],
                //"products_per_title" => "MobilePhone || Charger || Camera",
                "products_per_title" => 'pioneer auctoin deposit', //$order['project_name'],
                //"unit_price" => "12.123 || 21.345 || 35.678 ",
                "unit_price" => $data->amount,
                //"quantity" => "2 || 3 || 1",
                "quantity" => "1",
                "other_charges" => "0.0",
                "payment_type" => "mada",
                "amount" => $data->amount,
                "discount" => "0.0",
                "currency" => "SAR",
                "reference_no" => $id,
                "billing_address" => $user['address'],
                // "billing_address" => 'UAE',
                "city" => $user['city'],
                "state" => $user['city'],
                // "state" => 'UAE',
                // "postal_code" =>'54000',
                "postal_code" => $user['po_box'],
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
            if (isset($response->response_code) && $response->response_code == "4012") {
                $transaction_data['transaction_id'] = $response->p_id;
                $transaction_data['user_id'] = $id;
                $transaction_data['amount'] = $data->amount;
                $transaction_data['status'] = 'active';
                $transaction_data['payment_type'] = 'card';
                $transaction_data['deposit_type'] = 'permanent';
                $transaction_data['created_on'] = date('Y-m-d H:i:s');
                $this->db->insert('auction_deposit', $transaction_data);
                echo json_encode(array('msg' => 'success', 'response' => 'package_recharge_success', 'redirect' => $response->payment_url, 'date' => $date));
            } else {
                // $this->session->set_flashdata('error', $this->lang->line('package_payment_shipping_failed'));
                echo json_encode(array("status" => false, 'msg' => 'package_payment_shipping_failed'));
                // echo json_encode(array('msg'=>'error','response' => $this->lang->line('package_payment_shipping_failed'),'redirect'=> base_url('customer/deposit')));
            }
        }
    }

    public
    function addBankSlip()
    {
        $id = validateToken();
        // $posted_data = $this->input->post('deposit_date');
        if ($this->input->post()) {
            $posted_data = $this->input->post();
            if (!empty($_FILES['slip']['name'])) {
                $path = './uploads/bank_slips/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'ico|png|jpg|jpeg|pdf|doc|docx|txt|xls';
                $uploaded_file_ids = $this->files_model->upload('slip', $config);
                if (isset($uploaded_file_ids['error'])) {
                    $this->session->set_flashdata('error', $uploaded_file_ids['error']);
                    echo json_encode(array('msg' => 'error', 'message' => $uploaded_file_ids['error']));
                    exit();
                }
                $posted_data['user_id'] = $id;
                $posted_data['slip'] = implode(',', $uploaded_file_ids);
                $posted_data['created_on'] = date('Y-m-d H:i:s');
                $result = $this->db->insert('bank_deposit_slip', $posted_data);
                $inserted_id = $this->db->insert_id();
                if ($result) {
                    echo json_encode(array('status' => true, 'message' => 'Slip has been added seccessfully.'));
                } else {
                    echo json_encode(array('status' => false, 'message' => 'Slip has been failed to add.'));
                }
            }
        } else {
            echo json_encode(array('status' => false, 'message' => 'slip not added'));
        }
    }


    private
    function generate_string($input, $strength = 6)
    {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }

    public
    function generate_code($item_id, $path, $item_details_array = array())
    {
        $this->load->library('ciqrcode');
        // how to save PNG codes to server
        $tempDir = FCPATH . $path;
        $tempDir_url = base_url() . "uploads/items_documents/";
        $codeContents = json_encode($item_details_array);

        // we need to generate filename somehow,
        // with md5 or with database ID used to obtains $codeContents...
        $fileName = $item_id . '_' . md5($codeContents) . '.png';
        $pngAbsoluteFilePath = $tempDir . $fileName;
        $urlRelativeFilePath = $tempDir_url . $fileName;
        // generating
        if (!file_exists($pngAbsoluteFilePath)) {
            QRcode::png($codeContents, $pngAbsoluteFilePath);
            return $fileName;
        } else {
            return $fileName;
        }

    }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////inscpectoin app apis //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public
    function loginAppraiser()
    {
        // $config['csrf_protection'] = TRUE;
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->username) && !empty($data->password)) {
            $username = $data->username;
            $dataa = $data->password;
            // $password = htmlspecialchars(strip_tags(md5($data->password)));
            $password = hash("sha256", $data->password);;
            $result = $this->api_model->appraise_login_check($username, $password);
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
                        'exp' => time() + (120 * 60 * 24),
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
                    echo json_encode(array('status' => true, 'message' => 'Logged in successfully.', 'result' => $data));
                }
            } else {
                http_response_code(404);
                echo json_encode(array('status' => false, 'message' => 'Unauthorized user.'));
                // echo "error";
            }
        } else {
            echo json_encode(array('status' => false, 'message' => 'Data is missing.'));
        }
    }

    public
    function insertItem()
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
                    } else {
                        $dataValue = $value->newvalue;
                    }
                    if (!empty($dataValues)) {
                        foreach ($dataValues as $key => $dataVal) {
                            $exp_str = explode('|', $dataVal);
                            $engDescription[] = $exp_str[0];
                            if (isset($exp_str[1])) {
                                $arabicDescription[] = $exp_str[1];
                            }
                        }
                    } else {
                        $exp_str = explode('|', $dataValue);
                        $engDescription[] = $exp_str[0];
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
        } else {
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

        $insert_item = array(
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
        $result = $this->db->insert('item', $insert_item);
        if ($result) {
            $item_id = $this->db->insert_id();
            $category_id = $insert_item['category_id'];

            // QR Code
            $path = "uploads/items_documents/" . $item_id . "/qrcode/";
            // make path
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $qrcode_name = $this->generate_code($item_id, $path, ['id' => $item_id]);
            if (!empty($qrcode_name)) {
                $barcode_array = array(
                    'barcode' => $qrcode_name
                );
                $this->db->update('item', $barcode_array, ['id' => $item_id]);
            }


            //Handle dynamic fields
            if ($dynamic_feilds) {
                foreach ($dynamic_feilds as $key => $field) {
                    $field_id = $field->id;
                    $val = $field->newvalue;
                    if (empty($val)) {
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
                    $this->db->insert('item_fields_data', $insert_dynamic_field);
                }

            }
            $output = ['status' => true, 'message' => 'Item added successfully.', 'item_id' => $item_id];
        } else {
            $output = ['status' => false, 'message' => 'Item is failed to add.'];
        }
        echo json_encode($output);

        //// Dynamic feilds

    }

    public
    function updateItem()
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
                    } else {
                        $dataValue = $value->selected_value;
                    }
                    if (!empty($dataValues)) {
                        foreach ($dataValues as $key => $dataVal) {
                            $exp_str = explode('|', $dataVal);
                            $engDescription[] = $exp_str[0];
                            if (isset($exp_str[1])) {
                                $arabicDescription[] = $exp_str[1];
                            }
                        }
                    } else {
                        $exp_str = explode('|', $dataValue);
                        $engDescription[] = $exp_str[0];
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
        } else {
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
        $update_item = array(
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

        $result = $this->db->update('item', $update_item, ['id' => $data->item_id]);
        if ($result) {
            $item_id = $data->item_id;
            // $category_id = $update_item['category_id'];
            // QR Code
            $path = "uploads/items_documents/" . $item_id . "/qrcode/";
            // make path
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $qrcode_name = $this->generate_code($item_id, $path, ['id' => $item_id]);
            if (!empty($qrcode_name)) {
                $barcode_array = array(
                    'barcode' => $qrcode_name
                );
                $this->db->update('item', $barcode_array, ['id' => $item_id]);
            }
            //Handle dynamic fields
            if ($dynamic_feilds) {
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
            $output = ['status' => true, 'message' => 'Item updated successfully.', 'item_id' => $item_id];
        } else {
            $output = ['status' => false, 'message' => 'Item is failed to update.'];
        }
        echo json_encode($output);
    }


    public
    function pagination_items()
    {
        $this->load->library('pagination');
        $data = json_decode(file_get_contents("php://input"));
        $index = $data->index;
        $limit = 10;
        // $total = count($this->api->get_item_list($data['auction_id'], 0, 0, $item_ids));
        $total = $this->db->select('count(*) as allcount')->limit($limit)->get('item')->result();;
        print_r($total);
        die();
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
    public
    function upload_images()
    {
        $data = json_decode(file_get_contents("php://input"));
        $result = $this->input->post('item_id');
        // $result = $_GET['item_id'];
        if (isset($_FILES['images']['name']) && !empty($result)) {
            $result_array = $this->api_model->get_item_byid($result);
            if (isset($result_array) && !empty($result_array)) {
                $ids_concate = '';
                $itemsIds_array = explode(',', $result_array[0]['item_images']);
                if (!empty($itemsIds_array) && !empty($result_array[0]['item_images'])) {
                    $ids_concate = $result_array[0]['item_images'] . ",";
                }
            }
            $path = "uploads/items_documents/" . $result . "/";
            // make path
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            // If profile picture is selected
            $files = '';
            if (!empty($_FILES)) {
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
                        'item_images' => $ids_concate . $uploaded_file_ids
                    );
                    $item_array = $this->api_model->update_item($result, $item_attachments_array);
                    $img_msg = 'Images uploaded successfully.';
                    echo json_encode(array('status' => true, 'message' => $img_msg, 'item_id' => $result));
                }
            } else {
                $img_msg = 'Images not uploaded';
                echo json_encode(array('status' => false, 'message' => $img_msg));
            }

        } else {
            echo json_encode(array('status' => false, 'message' => 'Image not selected.'));

        }
    }

//update item document
    public
    function upload_item_documents()
    {
        //upload documents
        // $result = $this->input->post('item_id');
        $result = $_GET['item_id'];
        if (isset($_FILES['documents']['name']) && !empty($result)) {

            $result_array = $this->api_model->get_item_byid($result);
            if (isset($result_array) && !empty($result_array)) {
                $ids_concate = '';
                $itemsIds_array = explode(',', $result_array[0]['item_attachments']);
                if (!empty($itemsIds_array) && !empty($result_array[0]['item_attachments'])) {
                    $ids_concate = $result_array[0]['item_attachments'] . ",";
                }
            }
            $path = "uploads/items_documents/" . $result . "/";
            // make path
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $files = '';
            if (!empty($_FILES)) {
                $config['allowed_types'] = 'ico|png|jpg|pdf|ai|psd|eps|indd|doc|docx|ppt|pptx|xlsx|xls';
                $config['upload_path'] = $path;
                $uploaded_file_ids = $this->files_model->upload('documents', $config);
                if (isset($uploaded_file_ids['error'])) {
                    $doc_msg = $uploaded_file_ids['error'];
                } else {
                    $uploaded_file_ids = implode(',', $uploaded_file_ids);
                    $item_attachments_array = [
                        'item_attachments' => $ids_concate . $uploaded_file_ids
                    ];
                    $item_array = $this->api_model->update_item($result, $item_attachments_array);
                    $doc_msg = 'Document uploaded successfully.';
                    echo json_encode(array('status' => true, 'message' => $doc_msg, 'item_id' => $result));
                }
            } else {
                $doc_msg = 'Document not uploaded';
                echo json_encode(array('status' => false, 'message' => $doc_msg));
            }

        } else {
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


    public
    function items_multiple_docs()
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
            $output_file = $_SERVER['DOCUMENT_ROOT'] . "/uploads/items_documents/" . $itemId . "/";
            if (file_exists($output_file . 'sig.png')) {
                unlink($output_file . 'sig.png');
            }
            // Get server path
            if (!is_dir($output_file)) {
                mkdir($output_file, 0777, TRUE);
            }
            file_put_contents($output_file . 'sig.png', $sig_img);
        }

        // condition report
        if (isset($condition) && !empty($condition)) {
            $condition_str = explode(',', $condition);
            $condition_img = base64_decode($condition_str[1]);
            $output_file = $_SERVER['DOCUMENT_ROOT'] . "/uploads/items_documents/" . $itemId . "/";
            if (file_exists($output_file . 'condition.png')) {
                unlink($output_file . 'condition.png');
            }
            if (!is_dir($output_file)) {
                mkdir($output_file, 0777, TRUE);
            }
            file_put_contents($output_file . 'condition.png', $condition_img);
        }


        $item_docs_array = [];
        /// for images
        if (isset($data['images']) && !empty($data['images'])) {
            //upload multiple base64 images through files model
            $path64 = "/uploads/items_documents/" . $itemId . "/";
            $sizes = [
                ['width' => 37, 'height' => 36]
            ];

            $image_ids = $this->files_model->base64_multiupload($data['images'], $path64, $sizes);
            $old_img_ids = $this->db->get_where('item', ['id' => $itemId])->row_array();
            if (isset($image_ids['error'])) {
                $error = $image_ids['error'];
            } else {
                $imgs_ids = implode(',', $image_ids);
                $item_docs_array['item_images'] = $imgs_ids;
                if (empty($old_img_ids['item_images'])) {
                    $item_docs_array['item_images'] = $imgs_ids;
                } else {
                    $item_docs_array['item_images'] = $old_img_ids['item_images'] . "," . $imgs_ids;
                }
            }
        }
        ///for documents
        if (isset($_FILES['otherDocuments']) && strlen($_FILES['otherDocuments']['name'][0]) > 0) {
            // print_r($_FILES['otherDocuments']);die();
            $result_array = $this->api_model->get_list_item($itemId);
            if (isset($result_array) && !empty($result_array)) {
                if (empty($result_array[0]['item_attachments'])) {
                    $ids_concate = $result_array[0]['item_attachments'];
                } else {
                    $ids_concate = $result_array[0]['item_attachments'] . ",";
                }
            }
            $path = "uploads/items_documents/" . $itemId . "/";
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $config['upload_path'] = $path;
            $config['allowed_types'] = '*';
            $type = 'otherDocuments';
            $uploaded_file_ids = $this->files_model->multiUpload($type, $config);
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
            } else {
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                // print_r($item_docs_array['item_attachments']);die('other');
                $item_docs_array['item_attachments'] = $ids_concate . $uploaded_file_ids;
            }
        }
        /// for item test report
        if (isset($_FILES['testDocument']) && strlen($_FILES['testDocument']['name'][0]) > 0) {
            $result_array = $this->api_model->get_list_item($itemId);

            if (isset($result_array) && !empty($result_array)) {
                //$docIds_array = explode(',' ,$result_array[0][$column_name]);
                if (empty($result_array[0]['item_test_report'])) {
                    $ids_concate = $result_array[0]['item_test_report'];
                } else {
                    $ids_concate = $result_array[0]['item_test_report'] . ",";
                }
            }

            $ids_concate = '';
            $path = "uploads/items_documents/" . $itemId . "/";
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $config['upload_path'] = $path;
            // $config['allowed_types'] ='gif|png|jpg|jpeg|pdf|doc|docx|ppt|pptx|xlsx|xls|txt|word';
            $config['allowed_types'] = '*';
            $type = 'testDocument';
            $uploaded_file_ids = $this->files_model->multiUpload($type, $config);
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
            } else {
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $item_docs_array['item_test_report'] = $ids_concate . $uploaded_file_ids;
            }
        }
        if (!empty($item_docs_array['item_test_report']) || !empty($item_docs_array['item_images']) || !empty($item_docs_array['item_attachments'])) {
            $this->db->update('item', $item_docs_array, ['id' => $itemId]);
            echo json_encode(array("status" => true, "message" => 'Documents added successfully.'));
        } else {
            echo json_encode(array("status" => false, "message" => $error));
        }
    }


//delete item documents
    public
    function delete_item_documents()
    {
        $userid = validateToken();
        $data = json_decode(file_get_contents("php://input"));
        $itemId = $data->itemId;
        $attach_name = $data->file_to_be_deleted;
        // $attach_name = $this->input->post('file_to_be_deleted');
        $file_array = $this->files_model->get_file_byName($attach_name);
        if (isset($file_array) && !empty($file_array)) {
            $result_array = $this->api_model->get_item_byid($itemId);
            if (isset($result_array) && !empty($result_array)) {
                $itemsIds_array = explode(',', $result_array[0]['item_attachments']);
                if (!empty($itemsIds_array)) {
                    $str = $result_array[0]['item_attachments'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'item_attachments' => $updated_str,
                'updated_by' => $userid
            ];
            $update_item_row = ($this->api_model->update_item($itemId, $update)) ? 'true' : 'false';
        }
        $path = FCPATH . "uploads/items_documents/" . $itemId . "/";
        $result = $this->files_model->delete_by_name($attach_name, $path);
        if ($result) {
            echo json_encode(array('status' => true, 'item_id' => $itemId, 'message' => 'File has been deleted successfully.'));
            echo json_encode(array('status' => false, 'message' => 'File has been failed to delete.'));
        }

    }

    public
    function delete_item_image($itemId)
    {
        $userid = validateToken();
        $data = json_decode(file_get_contents("php://input"));
        $itemId = $data->itemId;
        $attach_name = $data->file_to_be_deleted;
        $file_array = $this->files_model->get_file_byName($attach_name);
        if (isset($file_array) && !empty($file_array)) {

            $result_array = $this->api_model->get_item_byid($itemId);
            if (isset($result_array) && !empty($result_array)) {
                $itemsIds_array = explode(',', $result_array[0]['item_images']);

                if (!empty($itemsIds_array)) {
                    $str = $result_array[0]['item_images'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'item_images' => $updated_str,
                'updated_by' => $userid
            ];
            $result_update = ($this->api_model->update_item($itemId, $update)) ? 'true' : 'false';
        }
        $path = FCPATH . "uploads/items_documents/" . $itemId . "/";
        $result = $this->files_model->delete_by_name($attach_name, $path);
        if ($result) {
            echo 'success';
        }
    }

    public
    function delete_item_docs()
    {
        $userid = validateToken();
        $data = json_decode(file_get_contents("php://input"));
        $itemId = $data->itemId;
        $fileId = $data->file_id;
        $column_name = $data->column;
        $attach_name = $data->file_name;
        $file_array = $this->files_model->get_file_byName($attach_name);
        if (isset($file_array) && !empty($file_array)) {
            $result_array = $this->items_model->get_item_byid($itemId);
            if (isset($result_array) && !empty($result_array)) {
                $itemsIds_array = explode(',', $result_array[0][$column_name]);
                if (!empty($itemsIds_array)) {
                    $str = $result_array[0][$column_name];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                $column_name => $updated_str,
                'updated_by' => $userid
            ];
            $update_item_row = ($this->items_model->update_item($itemId, $update)) ? 'true' : 'false';
        } else {
            echo json_encode(array("status" => false, "message" => 'Something went wrong.'));
        }

        $path = FCPATH . "uploads/items_documents/" . $itemId . "/";
        $result = $this->files_model->delete_by_name($attach_name, $path);
        if ($result) {
            echo json_encode(array("status" => true, "message" => 'File deleted successfully.'));
        }
    }

    public
    function itemList()
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
        } else {
            $list_info = $this->api_model->item_list_pagination($offset, $limit);
        }
        foreach ($list_info as $key => $value) {
            $new1 = array();
            $e = explode(',', $value['item_images']);
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
        if (!empty($list_info)) {
            echo json_encode(array("status" => true, "message" => 'Items List.', 'result' => $list_info));
        } else {
            $list_info = array();
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Items not available.', 'result' => $list_info));
        }
    }


    public
    function updateProfile()
    {
        $data_array = json_decode(file_get_contents("php://input"), true);
        $data = json_decode(file_get_contents("php://input"));
        $userId = validateToken();
        $payload_info = getPayload();
        $data = array(
            // 'id' => $id,
            'fname' => $data->fname,
            'lname' => $data->lname,
        );

        if (!empty($data)) {
            $result = $this->api_model->edit_profile($userId, $data);
            echo json_encode(array('status' => true, 'message' => 'Profile updated successfully.', 'data' => $data));
            exit();
        } else {
            echo json_encode(array('status' => false, 'message' => 'No change found.'));
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
    public
    function updateProfilePic()
    {
        $userId = validateToken();
        $data = $this->input->post();
        if (isset($data['image'])) {
            $data_for_delete_files = $this->api_model->users_listing($userId); // fetch data
            if (!empty($data_for_delete_files)) {
                $old_data = FCPATH . '/uploads/profile_picture/' . $userId . '/' . $data_for_delete_files[0]['picture'];
            }
            $picture = $data_for_delete_files[0]['picture'];
            $path = "/uploads/profile_picture/" . $userId . "/";
            // if (!is_dir($path)) {
            //     mkdir($path, 0777, TRUE);
            // }
            $sizes = [
                ['width' => 37, 'height' => 36]
            ];
            $item_docs_array = [];
            $image_ids = $this->files_model->base64_upload($data['image'], $path, $sizes);
            $imgs_ids = implode(',', $image_ids);
            $image_name = $this->db->get_where('files', ['id' => $imgs_ids])->row_array();
            $item_docs_array['picture'] = $imgs_ids;
            echo json_encode(array("status" => true, "message" => 'Image updated successfully.', 'picture' => $image_name['name']));
            $user_array = $this->api_model->update_user($userId, $item_docs_array);
            if (is_dir($path) && file_exists($picture)) {
                unlink($old_data);
            }
        } else {
            echo json_encode(array("error" => true, "message" => 'Image Not uploaded.'));
        }
    }

////// users result expect one user/////
    public
    function all_sellers()
    {
        $users_lists = array();
        $data = json_decode(file_get_contents("php://input"));
        // check if token exists.
        $headers = getAuthorizationHeader();
        if (!empty($headers)) {
            $userId = validateToken();
            $users_lists = $this->db->select('*')->from('users')->where('id !=', $userId)->where('role', 4)->get()->result_array();
        } else {
            $users_lists = $this->db->select('*')->from('users')->where('role', 4)->get()->result_array();
        }
        if ($users_lists) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'error' => false, 'message' => 'success.', 'result' => $users_lists));
        } else {
            echo json_encode(array('status' => true, 'error' => false, 'message' => 'No record found.'));
        }
    }

//////// Item detail against id ////////
    public
    function itemDetailId()
    {
        $data = json_decode(file_get_contents("php://input"));
        //check signature available or not
        $check_file = $_SERVER['DOCUMENT_ROOT'] . "/uploads/items_documents/" . $data->item_id . "/" . 'sig.png';
        if (file_exists($check_file)) {
            $signature = true;
        } else {
            $signature = false;
        }
        //check condition
        $check_file2 = $_SERVER['DOCUMENT_ROOT'] . "/uploads/items_documents/" . $data->item_id . "/" . 'condition.png';
        if (file_exists($check_file2)) {
            $condition = true;
        } else {
            $condition = false;
        }
        $list_info = $this->db->select('*')->from('item')->where('id', $data->item_id)->order_by('created_on', 'DESC')->get()->row_array();
        $item_name = json_decode($list_info['name']);
        $list_info['name'] = $item_name;

        $item_name = json_decode($list_info['detail']);
        $list_info['detail'] = $item_name;

        $item_name = json_decode($list_info['terms']);
        $list_info['terms'] = $item_name;

        $item_name = json_decode($list_info['comment']);
        $list_info['comment'] = $item_name;

        if (isset($list_info['item_images']) && !empty($list_info['item_images'])) {
            $e = explode(',', $list_info['item_images']);
        } else {
            $e = array();
        }
        if (isset($list_info['item_attachments']) && !empty($list_info['item_attachments'])) {
            $attach = explode(',', $list_info['item_attachments']);
        } else {
            $attach = array();
        }
        $attach_test_docs = explode(',', $list_info['item_test_report']);
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
        } else {
            $list_info2 = array();
        }
        if (isset($data_attach_testDocs) && !empty($data_attach_testDocs)) {
            foreach ($data_attach_testDocs as $key => $attachTest) {
                $list_info3[] = $attachTest['name'];
                $list_info4[] = $attachTest['id'];
            }
        } else {
            $list_info3 = array();
            $list_info4 = array();
        }
        if (isset($data2) && !empty($data2)) {
            foreach ($data2 as $key => $d) {
                $list_info1[] = $d['name'];
            }
        } else {
            $list_info1 = array();
        }
        if ($list_info) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'error' => false, 'message' => 'Success.', 'result' => $list_info, 'item_attachments_name' => $list_info2, 'item_test_doc' => $list_info3, 'item_test_doc_id' => $list_info4, 'item_images_name' => $list_info1, 'item_images' => $e, 'item_attachments' => $attach, 'signature' => $signature, 'condition' => $condition));
        } else {
            echo json_encode(array('status' => true, 'error' => false, 'message' => 'No record found.'));
        }
    }

////////////////////////////////////***********************///////////////////////////////////
    function removeItemString($str, $item)
    {
        $parts = explode(',', $str);
        while (($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }
        return implode(',', $parts);
    }


/////////////////Api After ///////////////
    public
    function aboutUs()
    {
        $data = $this->db->get('about_us')->row_array();
        $description_decode = json_decode($data['description']);
        $title_decode = json_decode($data['title']);
        ///get image name from files model
        $result = array(
            'title' => $title_decode,
            'description' => $description_decode
        );
        $files = $this->db->get_where('files', ['id' => $data['policy_image']])->row_array();
        if (isset($data) && !empty($data)) {
            echo json_encode(array('status' => true, 'error' => false, 'result' => $result, 'image' => $files));
        }
    }

///////APIS new//////
    public
    function auctionCategories()
    {
        $data = $this->api_model->home_cats(); //get catagories for home with count
        foreach ($data as $key => $value) {
            $data[$key]['icon'] = $this->db->get_where('files', ['id' => $value['category_icon']])->row_array();
            $data[$key]['cat_title'] = json_decode($value['title']);
            $auctions_online = $this->api_model->get_auctions_home($value['cat_id']);
            $data[$key]['auction_id'] = $auctions_online['id'];
            if (!empty($auctions_online)) {
                $count = $this->db->select('*')->from('auction_items')->where('auction_id', $auctions_online['id'])->where('sold_status', 'not')->where('auction_items.bid_start_time <', date('Y-m-d H:i'))->get()->result_array();
                $count = count($count);
                $data[$key]['item_count'] = $count;
            }
        }
        $data2 = $this->home_model->featured_item();
        foreach ($data2 as $key => $img) {
            $data2[$key]['featured_images'] = $this->db->get_where('files', ['id' => $img['item_images']])->row_array();
        }
        if (!empty($result)) {
            echo json_encode(array('status' => true, 'error' => false, 'categories' => $data, 'featured_item' => $data2));
        } else {
            echo json_encode(array('status' => true, 'error' => false, 'categories' => $data, 'featured_item' => $data2));
        }
    }


/// API for items list against categories auction type and pagination ///
    public
    function categoryItems()
    {
        $data1 = json_decode(file_get_contents("php://input"));
        $id = $data1->id;
        $type = $data1->type; //auction type
        $page = 1;
        if (isset($data1->page) && !empty($data1->page)) {
            $page = $data1->page;
        }
        $limit = 10;
        $offset = $limit * $page - $limit;
        $date['time'] = date('Y-m-d H:i:s', time());
        $get_item_list_against_cat = $this->api_model->get_item_list_against_cat($id, $type, $offset, $limit);///get data from auction table against cat_id
        $auction_item_data = $this->db->get_where('auction_items', ['sold_status' => 'not', 'auction_id' => $get_item_list_against_cat['id']])->result_array();////get the data from auction_items against auction id and sold status
        if (!empty($auction_item_data)) {
            foreach ($auction_item_data as $key => $img) {
                $title = $data1->title;
                /// get data from item table against item_id in auction item ///
                $items_data[$key] = $this->db->get_where('item', ['id' => $img['item_id']])->row_array();
            }
        } else {
            $res = array();
            echo json_encode(array('status' => true, 'result' => $res, 'message' => 'No item against this search.'));
            exit();
        }
        foreach ($items_data as $keys => $img_id) {
            /// get images from files table against item_images ///
            $items_data[$keys]['item_images'] = $this->db->get_where('files', ['id' => $img_id['item_images']])->row_array();
        }
        $data = $items_data;
        if (!empty($data)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'success', 'result' => $data, 'time' => $date));
        } else {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'No data'));
        }
    }

//// For search in inspection app ////

    public
    function inspection_search()
    {
        $data = json_decode(file_get_contents("php://input"));
        $filter = $data->filter;
        $search_data = $this->db->select('*')
            ->from('item')
            ->like('registration_no', $filter, 'BOTH')
            ->or_like('vin_number', $filter, 'BOTH')
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

            $image_ids = explode(',', $item['item_images']);
            $item['img'] = $this->db->where_in('id', $image_ids)->get('files')->result_array();//get item images multiple

            array_push($output, $item);
        }
        if ($search_data) {
            echo json_encode(array('status' => true, 'result' => $output));
        } else {
            echo json_encode(array('status' => false, 'result' => 'No record found.'));
        }
    }

    private function user_balance($id)
     {
        $dr_balance = 0;
        $cr_balance = 0;
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'permanent');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('account', 'DR');
        $this->db->where('user_id',$id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            $dr_balance = $query->row_array();
        }
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'permanent');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('account', 'CR');
        $this->db->where('user_id',$id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            $cr_balance = $query->row_array();
        }
        $amount['amount'] = $dr_balance['amount'] - $cr_balance['amount'];
        return $amount;
     } 


     private function getRegPushNotification($_id, $_data){
         $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

     //   if($this->language=="en"){
            $title = 'You are now registered!'; //$english_l['push_register_title'];
            $message = 'Thank you for registering. Your Username is '.$_data['email']; //$english_l['push_register_message'].$_data['email'];
        // }else{
        //     $title = $arabic_l['push_register_title'];
        //     $message = $_data['email'].$arabic_l['push_register_message'];
        // }
           $arr = [
            'title'=> $title,
           'message'=>$message
       ];
        return $this->sendPushNotification($_id, $arr);
     }
     private function getOutBidPushNotification($_id, $_data){
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

      //  if($this->language=="en"){
            $title = 'You have been outbid!';//$english_l['push_out_bit_title'];
            $message = "You've been outbid on the Lot No.: ".$_data['item_detail'];
      //  }else{
       //     $title = $arabic_l['push_out_bit_title'];
      //      $message = $_data['item_detail'].$arabic_l['push_out_bit_message'];
      //  }
           $arr = [
            'title'=> $title,
           'message'=>$message,
           'link' => 'pioneerauctions://auctionDetails/'.$_data["itemId"].'/'.$_data["auctionId"].'/online'
       ];

        return $this->sendPushNotification($_id, $arr);
     }
     private function getItemRejectPushNotification($_id, $_data){
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if($this->language=="en"){
            $title = $english_l['push_item_reject_title'];
            $message = $english_l['push_item_reject_message_1'].$_data['item_detail'].$english_l['push_item_reject_message_2'];
        }else{
            $title = $arabic_l['push_item_reject_title'];
            $message = $arabic_l['push_item_reject_message_2'].$_data['item_detail'].$arabic_l['push_item_reject_message_1'];
        }
           $arr = [
            'title'=> $title,
           'message'=>$message
       ];
        return $this->sendPushNotification($_id, $arr);
     }
     private function getAuctionLIvePushNotification($_id){
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if($this->language=="en"){
            $title = $english_l['push_auction_live_title'];
            $message = $english_l['push_auction_live_message'];
        }else{
            $title = $arabic_l['push_auction_live_title'];
            $message = $arabic_l['push_auction_live_message'];
        }
           $arr = [
            'title'=> $title,
           'message'=>$message
       ];

        return $this->sendPushNotification($_id, $arr);
     }
     private function getOnlineLiveHallAuctionPushNotification($_id){
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if($this->language=="en"){
            $title = $english_l['push_online_auction_hall_title'];
            $message = $english_l['push_online_auction_hall_message'];
        }else{
            $title = $arabic_l['push_online_auction_hall_title'];
            $message = $arabic_l['push_online_auction_hall_message'];
        }
           $arr = [
            'title'=> $title,
           'message'=>$message
       ];
       
        return $this->sendPushNotification($_id, $arr);
     }
     private function getExpiringItemPushNotification($_id){
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if($this->language=="en"){
            $title = $english_l['push_item_expire_title'];
            $message = $english_l['push_item_expire_message'];
        }else{
            $title = $arabic_l['push_item_expire_title'];
            $message = $arabic_l['push_item_expire_message'];
        }
           $arr = [
            'title'=> $title,
           'message'=>$message
       ];

        return $this->sendPushNotification($_id, $arr);
     }
     private function getDepositPushNotification($_id, $_data){
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if($this->language=="en"){
            $title = $english_l['push_deposit_title'];
            $message = $english_l['push_deposit_message_1'].$_data['amount'].$english_l['push_deposit_message_2'];
        }else{
            $title = $arabic_l['push_deposit_title'];
            $message = $arabic_l['push_deposit_message_2'].$_data['amount'].$arabic_l['push_deposit_message_1'];
        }
           $arr = [
            'title'=> $title,
           'message'=>$message
       ];

        return $this->sendPushNotification($_id, $arr);
     }

     private function getAuctionWinnnerPushNotification($_id, $_data){
        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if($this->language=="en"){
            $title = $english_l['push_winner_title'];
            $message = $english_l['push_winner_message_1'].$_data['lot_no'].' - '.$_data['item_name'].$english_l['push_winner_message_2'];
        }else{
            $title = $arabic_l['push_winner_title'];
            $message = $arabic_l['push_winner_message_2'].$_data['lot_no'].' - '.$_data['item_name'].$arabic_l['push_winner_message_1'];
        }
           $arr = [
            'title'=> $title,
           'message'=>$message
       ];

    
        return $this->sendPushNotification($_id, $arr);
     }
     private function getCustomePushNotification($_id, $_data){
           $arr = [
            'title'=> $_data['title'],
           'message'=>$_data['message']
       ];
        return $this->sendPushNotification($_id, $arr);

        // $notificationparam['token'] = [$_id];
        // $notificationparam['title'] = $_data['title'];
        // $notificationparam['message'] = $_data['message'];
        // $notificationparam['type'] = 1;
        // $notificationparam['flag'] = 1;

       // return $this->fcm->sendNotification($notificationparam);
     }


     public function sendPushNotificationTest($to = "")
    {

        $language = "english";
        $version = $this->config->item('appVersion');

        $english_l = $this->lang->load('getapi_lang', 'english');
        $arabic_l = $this->lang->load('getapi_lang', 'arabic', true);

        if(!empty($to)) {

            $title =  'Test Title';
            $body = 'test message body';
            $image = '';
            $link = 'hello';

            $data = [
                "notification" => [
                    "body" => $body,
                    "title" => $title,
                    "image" => $image,
                    "deeplink" => 'pioneerauctions://Onlineauction/1/0'
                ],
                "priority" => "high",
                "data" => [
                    "deeplink" => 'pioneerauctions://Onlineauction/1/0'
                ],
                "to" => $to
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Content-Type: application/json';          
           $headers[] = 'Authorization: key=AAAAASZ7Vwk:APA91bF6wZpCDm86aSx4HpBM6td3rvdytZqlViClzsqgy4OjdonZFlwpPxuZK0Ew1DLhwdGHPvcQ9ddZWI7hiaFfnTJWQIXkr6-VXR4p355fHnC1p-BPBo6j4GXj_Es3EJf4PP7Wyi84';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close($ch);

            $codee = http_response_code(200);
            echo json_encode(array("status" => true, 'code' => $codee, "message" => 'Push Notification Send Successfully', 'english' => $english_l['push_notification'], 'arabic' => $arabic_l['push_notification'], 'appVersion' => $version));

        }

    }


}

