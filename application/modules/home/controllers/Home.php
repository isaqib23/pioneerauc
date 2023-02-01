<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
	
	function __construct() {
		parent::__construct();		 
		$this->load->model('Home_model','home_model');
        $this->load->model('email/Email_model','email_model');
        $this->load->model('auction/Online_auction_model', 'oam');
        $this->load->model('auction/Live_auction_model', 'lam');
        $this->load->model('customer/Customer_model', 'customer_model');
		$this->load->library('TestUnifonic');
        $this->load->helper('general_helper');
        $this->load->library('instagram_api');
        $this->load->library('user_agent');
        $this->load->library('Mobile_Detect');
        $this->instagram_api->access_token = $this->session->userdata('instagram-token');
		
	}
	public function index($category_id='') 
	{
		  $detect = new Mobile_Detect();
		  // $this->output->enable_profiler(TRUE);
        $a= $this->uri->segment(2);
		$data = array();
        $data['home_class'] = 'home_class';
        $data['page'] = 'home_class';
        //slider
        $current_date = date('Y-m-d H:i:s');
        $data['slider'] = $this->home_model->get_home_slider();
        //$this->db->select('*')->from('home_slider')->where('start_date <=', $current_date)->where('status', 'active')->where('end_date >=', $current_date)->order_by('sort_order', 'ASC')->get()->result_array();


        $data['popup_data2'] = $this->db->select('*')->from('popup')->get()->row_array();

        if (empty($category_id)) {
            $category_data = $this->db->select('*')->order_by('sort_order', 'ASC')->get_where('item_category', ['status' => 'active', 'show_web' => 'yes'])->row_array();
            $category_id = $category_data['id'];
            $data['category'] = $category_data;
        } else {
            $data['category'] = $this->db->get_where('item_category', ['id' => $category_id])->row_array();
        }
        $this->session->set_userdata('categoryId',$category_id);
        $data['categoryId'] = $category_id;
        $auction = $this->db->order_by('start_time', 'ASC')->get_where('auctions', ['category_id' => $category_id, 'access_type' => 'online','auctions.status' => 'active','auctions.expiry_time >' => date('Y-m-d H:i:s')])->row_array();
        // print_r($category_id); die();
        // featured items
     /*   $resultcategory=$this->home_model->get_items_categories();
       // $data['featured_items']=$this->home_model->featured_item($category_id, (isset($auction['id']) ? $auction['id'] : 0));


        foreach ($resultcategory as $resultcat) {
        	
                $data['featured_items_category'][] = array(
                    'title' => json_decode($resultcat['title'])->english,
                    'category_id' => $resultcat['id']
                );
                $category_id     = $resultcat['id'];
                $featured_items=$this->home_model->featured_item($category_id, (isset($auction['id']) ? $auction['id'] : 0));
                foreach($featured_items as $_list){
                   $data['featured_items_list'][$category_id][] = array(
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
                    'item_datail' => $_list['item_datail']
               );
                }
            }
            */


        // echo '<pre>';
        // print_r($data['featured_items']);
        // echo '</pre>';
        // Latest bids
        $data['latestBids']=$this->home_model->latestBidsOnItems($category_id, (isset($auction['id']) ? $auction['id'] : 0));
        // Popular bids
        $data['popularBids']=$this->home_model->popularBidsOnItems($category_id, (isset($auction['id']) ? $auction['id'] : 0));
        if (!empty($data['popularBids'])) {
	        foreach ($data['popularBids'] as $key => $popularBid) {
	        	$visist_count  = $this->db->select('COUNT(id) as visits')->get_where('online_auction_item_visits', ['auction_id' => $popularBid['auctionId'], 'item_id' => $popularBid['itemId']])->row_array();
	        	$data['popularBids'][$key]['visits'] = $visist_count['visits'];
	        }
        }
        // Avaialable auctions
        $date=date('Y-m-d');
        $datee['time'] =date('Y-m-d H:i:s', time());
        $data['live_auctions']=$this->home_model->upcoming_auctions_home($date,'time');
        $data['online_auctions']=$this->home_model->singleOnlineAuctionsWithItem();

        //auctions about to close
     //   $data['nearlyCloseAuctions']=$this->home_model->nearlyCloseAuctions($category_id, (isset($auction['id']) ? $auction['id'] : 0));
        $data['nearlyCloseAuctions']=$this->home_model->nearlyCloseAuctions();
        // echo date('Y-m-d H:i:s', time());
        // echo '<br/>';
        // print_r($data['nearlyCloseAuctions']);



        $data['media'] = $this->db->order_by('id', 'DESC')->limit(3)->get_where('item', ['item_status' => 'completed'])->result_array();

        $data['faqs'] = $this->db->get('ques_ans')->result_array();
        // $data['itemCategories'] = $this->home_model->getItemCategory();
        // print_r($data['slider']);die();
        @$auction_id ='';
        $data['active_auction_categories'] = $this->home_model->get_items_categories();
        
        foreach ($data['active_auction_categories'] as $key => $value) {
            $count = 0;
            $auctions_online = $this->home_model->get_online_auctions($value['id']);
            if (!empty($auctions_online)) {
                $data['active_auction_categories'][$key]['auction_id'] =  $auctions_online['id'];
                $data['active_auction_categories'][$key]['expiry_time'] =  $auctions_online['expiry_time'];
                $count= $this->db->select('*')->from('auction_items')->where('auction_id',$auctions_online['id'])->where('sold_status','not')->where('auction_items.bid_start_time <',date('Y-m-d H:i'))->get()->result_array();
                $count = count($count);
            }
            if ($this->session->userdata('logged_in')) {
                $u_id = $this->session->userdata('logged_in')->id;
                $close_auctions = $this->db->where("FIND_IN_SET('".$u_id."', close_auction_users)")->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'closed','category_id' => $value['id']])->result_array(); 
                if ($close_auctions) {
                    foreach ($close_auctions as $key1 => $close_auction) {
                        $item_ids = array();
                        $sub_total = count($this->lam->get_live_auction_items($close_auction['id'], 0, 0, $item_ids));
                        $count = $count + $sub_total;
                    }
                }
            }
            $live_auctions = $this->db->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'live','category_id' => $value['id']])->result_array();  
            if ($live_auctions) {
                foreach ($live_auctions as $key2 => $live_auction) {
                    $live_item_ids = array();
                    $total = count($this->lam->get_live_auction_items($live_auction['id'], 0, 0, $live_item_ids));
                    $count = $count + $total;
                }
            }
            $data['active_auction_categories'][$key]['item_count'] =  $count;

            $catID = $value['id'];
           @$auction_id = $data['active_auction_categories'][$key]['auction_id'];


            $featured_items=$this->home_model->featured_item($catID, $auction_id);
                foreach($featured_items as $_list){
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
		/*$mobile=$this->agent->is_mobile();
		if($mobile){
			$data['device'] = 'mobile';
		}else{
			$data['device'] = 'desktop';
		}*/

		if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
        	$data['device'] = 'mobile';
    	}else{
    		$data['device'] = 'desktop';
    	}


        $this->load->vars($data);
		$this->template->load_user('new/home', $data);
	}

	public function login()
	{
        if ($this->session->userdata('logged_in')) {
            redirect(base_url('customer/dashboard'));
        } else {
        	$link = base_url().'?loginUrl=loginFirst';
        	if(isset($_GET['rurl'])){
        		$link = base_url().'?loginUrl=loginFirst&rurl='.$_GET['rurl'];
        	}
            redirect($link);
        }
	}

	public function login_user()
	{
		$data['login_info']=array();
		$this->template->load_user('login_new',$data);
	}
	public function login_process()
	{	
		$email = $this->input->post('email');
		$password = $this->input->post('password1');
		$password = hash("sha256", $password);
		$result = $this->home_model->login_check($email, $password);
		if ($result) {
			if ($result[0]->role == '4') {
				if ($result[0]->code_status == '0') {
					$verify_link = '<p style="color: red">' . $this->lang->line('account_is_not_verified') . ' <a href="javascript:void(0)" onclick="varify(this)" "data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-mobile=' . $result[0]->mobile . '>' . $this->lang->line('verify_now') . '</a></p>';
					// echo json_encode(array('error' => true,"status" => false, "msg" => $verify_link));
					echo json_encode(array("status" => false, "msg" => $verify_link));
					exit();
				}
				if ($result[0]->status == '0') {
					echo json_encode(array('error' => true, "msg" => $this->lang->line('account_blocked')));
					exit();
				}
				$this->session->set_userdata('logged_in', $result[0]);
				echo json_encode(array('error' => false, "status" => true, "msg" => 'success'));
				// redirect('customer/dashboard/ ');
			} else {
				echo json_encode(array('error' => true, "msg" => $this->lang->line('only_user_login')));
				exit();
			}
		} else {
			echo json_encode(array('error' => true, "msg" => $this->lang->line('invalid_username_password')));
		}
    }

	public function register()
	{
		$data = array();
        $data['new'] = 'new';
		$data['active_register'] = 'active';
		$data['register_show'] = ' show ';

        if ($this->session->userdata('logged_in')) {
            redirect(base_url('deposit'));
        }else{
            $this->template->load_user('home/register', $data);
        }
	}

	public function register_user()
	{
		$this->load->library('form_validation');
		if ($this->input->post()) {
			$users_attachment = array();
			$rules = array(
				array(
					'field' => 'username',
					'label' => 'User Name',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'email1',
					'label' => 'Email',
					'rules' => 'required|valid_email',
				),
				array(
					'field' => 'mobile',
					'label' => 'Mobile ',
					'rules' => 'trim|required',
				)

			);

			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				// echo validation_errors();
				echo json_encode(array('error' => true, 'msg' => validation_errors()));
				exit();
			}

			if ($this->input->post()) {

				// $number2 = '+971561387755';
				$number = $this->input->post('mobile');
				$check_number = $this->home_model->check_numbers($number);
				$email = $this->input->post('email1');
				$result = $this->home_model->check_email($email);

				if ($check_number == true) {
					echo json_encode(array('error' => true, 'msg' => $this->lang->line('phone_already_registered')));
					exit();
				} elseif ($result == true) {
					echo json_encode(array('error' => true, 'msg' => $this->lang->line('email_already_registered')));
					exit();
				}

				$mobile_verification_code = $this->getNumber(4);
				$email_verification_code = $this->getNumber(4); //getNumber(4);

				//SMS verification process start
				$this->load->library('SendSmart');
				$sms = $this->lang->line('pioneer_verification_code_is') . $mobile_verification_code;
				// $number = '971561387755';

				$number2 = '971561387755';
				$isUAENumber = substr($number, 0, 3);
				$sms_response = 'Sent.';
				if ($isUAENumber != "971") {
					$sms_response = $this->sendsmart->sms($number, $sms);
				}
				//$sms_response = $this->testunifonic->sendMessage($number, $sms);
				// echo json_encode(array('error' => false,'msg' => $sms_response));
				//     exit();
				//if(isset($sms_response->MessageID)){
				if ($sms_response == 'Sent.') {
					$this->db->insert('sms_history', ['ip_address' => $this->input->ip_address(), 'created_on' => date('Y-m-d H:i:s')]);
					//$sms_response = json_encode($sms_response);

					// split username into first and last name
						$_username = $this->input->post('username');
						$_fullname = trim($_username); // remove double space
						$fname = substr($_fullname, 0, strpos($_fullname, ' '));
						$lname = substr($_fullname, strpos($_fullname, ' '), strlen($_fullname));

						if($fname=='') {
							$fname = $lname;
						}else{
							$fname = $fname;

						}


						// end 
					$data = array(
						'fname' => $fname,
						//'fname' => $this->input->post('username'),
						'lname' => $lname,
						//'lname' => $this->input->post('username'),
						// 'username' => $this->input->post('fname').' '.$this->input->post('lname'),
						'username' => $this->input->post('username'),
						'mobile' => $this->input->post('mobile'),
						'email' => $this->input->post('email1'),
						// 'reg_type'     => $this->input->post('type'),
						'reg_type' => 'individual',
						'country' => 'dubai',
						'password' => hash("sha256", $this->input->post('password')),
						// 'social'   => $this->input->post('social'),
						'prefered_language' => 'english',
						'code' => $mobile_verification_code,
						'email_verification_code' => $email_verification_code,
						'role' => 4,
						'status' => 0
						// 'email_verification'=>0
					);
					$data['created_on'] = date('Y-m-d H:i:s');
					$data['updated_on'] = date('Y-m-d H:i:s');
					$result = $this->home_model->insert_user($data);
					$user = $this->db->get_where('users', ['id' => $result]);
					$phone = $this->db->get_where('users', ['id' => $result])->row_array();
					$phone['mobile'] = $phone['mobile'];
					$this->session->set_userdata('phone', $phone['mobile']);
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
						$send = $this->email_model->email_template($email, 'user_registration', $vars, true);
						echo json_encode(array(
							'error' => false,
							"status" => true,
							"msg" => "Successfully Registered.",
							"email" => $send,
							"number" => $phone['mobile']
						));
						exit();
					} else {
						echo json_encode(array('error' => true, "status" => FALSE, "msg" => $sms_response));
					}
				} else {
					echo json_encode(array('error' => true, "status" => FALSE, "msg" => $this->lang->line('invalid_phone_number')));
				}
			}
		}
	}

    public function email_check()    //email verification check 
    { 
        $email = $this->input->post('email1');
        $result = $this->home_model->check_email($email);
        if($result)
        {   
            // $this->session->set_flashdata('error', 'Number already exist');
            // $msg = 'duplicate';
            echo json_encode(array('error' => false,'msg' => ' success' , ));
            exit();
        }
        else
        {   
            // $this->session->set_flashdata('error', 'Email already exist');
            // $msg = 'duplicate';
            echo json_encode(array('error' => true,'msg' => $this->lang->line('email_already_registered')));
            exit();
        }
    }

    public function resend_mobile_code()
    {
    //    $user = $this->db->get_where('users',['id' => $id]);

        
        $phone = $this->input->post('phone');
        $phone2 = '+971561387755';
        if (!empty($phone)) { 
            $mobile_existed = $this->db->get_where('users', ['mobile' => $phone]);

            if($mobile_existed->num_rows() > 0){

            	$currentTime = date('Y-m-d H:i:s');
            	$oneDay = date('Y-m-d H:i:s', strtotime($currentTime . " -1 days"));
            	$smsCount = $this->db->select('count(id) as total')->get_where('sms_history', ['ip_address' => $this->input->ip_address(), 'created_on <=' =>  $currentTime, 'created_on >' =>  $oneDay])->row_array();
            	$sms_limit = $this->config->item('sms_limit');
            if ($smsCount['total'] <= $sms_limit) {
            	//if ($smsCount['total'] ) {
	                $mobile_existed = $mobile_existed->row_array();
	                $mobile_verification_code = $this->getNumber(4);
	               
	                $code_status = 0;
	                //SMS verification process start
	                $this->load->library('SendSmart');
	                $sms = $this->lang->line('pioneer_verification_code_is') . $mobile_verification_code;
	                // $sms_response = $this->testunifonic->sendMessage($phone, $sms);

	                $sms_response = $this->sendsmart->sms($phone, $sms);

	                $user = $mobile_existed;

	                //return print_r($sms_response);
	                if($sms_response == 'Sent.'){
						$this->db->insert('sms_history', ['ip_address' => $this->input->ip_address(), 'created_on' => date('Y-m-d H:i:s')]);
	                    $sms_response = json_encode($sms_response);
	                    $this->db->update('users', [
	                        // 'sms_response' => $sms_response,
	                        'code' => $mobile_verification_code,
	                        'code_status' => $code_status,
	                    ], ['id' => $mobile_existed['id']]);


	                    // send email template 

	                //      print_r($mobile_existed);
	                // echo '<br/>';
	                // print_r($mobile_verification_code);
	                    
						$vars = [
							'{username}' => $user['fname'],
							'{email}' => $user['email'],
							'{password}' => '',
							'{login_link}' => $this->lang->line("please_login") . ':<a href="'.base_url().'?loginUrl=loginFirst" > ' . $this->lang->line("go_to_account") . ' </a>',
							 '{email_verification_code}'=>$mobile_verification_code,
							'{activation_link}' => '',
							'{btn_text}' => $this->lang->line('activate_your_account')
						];
						 $this->email_model->email_template($mobile_existed['email'], 'send_code', $vars, true);
						// end
	                }
	                $output = json_encode([
	                    'success' => true,
	                    'codeGenerated' => true,
	                    'msg' => $this->lang->line('Verification_code_sent_to_your_mobile')]);
	                return print_r($output);
            	} else {
            		$output = json_encode([
                    'error' => true,
                    'codeGenerated' => false,
                    'msg' => 'SMS limit exceeded. Please try again later.']);
                	return print_r($output);
            	}
            }else{
                $output = json_encode([
                    'error' => true,
                    'codeGenerated' => false,
                    'msg' => $this->lang->line('mobile_number_not_registered')]);
                return print_r($output);
            }
        }
    }

    public function verify_email($code='')
	{
        if (!$code) {
            show_404();die();
        }
        $data['new'] = 'new';
		$codes = $code;
        $query = $this->db->get_where('users', ['email_verification_code' => $codes]);
        // print_r($query);die('kkk');
        if ($query->num_rows() > 0) {
            $user=$query->row_array();
            // $this->session->set_userdata('logged_in',$user);
            $this->db->update('users', ['email_verification_code' => 0,'email_verification' => 1,'status' => 1]);
        	$this->session->set_flashdata('success', $this->lang->line('email_verified'));
        	//$this->template->load_user('template/page_user_thankyou',$data);
        	redirect(base_url('/?loginUrl=loginFirst'));
        } 
        else {
            $this->session->set_flashdata('error', $this->lang->line('email_verification_failed'));
	        redirect(base_url('/?loginUrl=loginFirst'));
        }
	}  


   public function verify_code()
	{
		$data = $this->input->post();
		if($data){
            $code = $data['code'];
			$phone = $data['verifyPhone'];

			// print_r($phone);die();
			$check = $this->db->get_where('users', ['code' => $code , 'mobile' =>$phone]);
			if($check->num_rows() > 0){
				$user = $check->row_array();
				$up = $this->db->update('users', ['code_status' => 1, 'code' => '0', 'status' => '1'], ['id' => $user['id']]);
				if($up){
					$user = (object)$user;
					// $this->session->set_userdata('logged_in', $user);
					echo json_encode([ 'success' => true, 'status' => 'verified', 'msg' => $this->lang->line('verified_successfully_please_check_email'), 'role' => $user->role, 'user_id' => $user->id]);
					// echo $output;
				}else{
					echo json_encode(['error' => true, 'status' => 'unverified', 'msg' => $this->lang->line('verification_failed_try_again')]);
					// echo $output;
				}
			} else {
                echo json_encode(['error' => true, 'status' => 'unverified', 'msg' => $this->lang->line('verification_code_is_invalid')]);
            }
		} 
	}


    public function forgot_password()
    {
	    $email=$this->input->post('email2');
	    $checkemail=$this->home_model->check_user_email_reset($email);
	    if (!empty($checkemail)) {
		    $email_code=$this->getNumber(4);
		    $this->db->update('users',['reset_password_code'=>$email_code],['email'=>$email]);
		    $link_activate = base_url() . 'home/verify_forgot_password/' . urlencode(base64_encode($email_code));
            $vars = [
                '{link_activation}' => $link_activate,
                '{btn_link}' => $link_activate,
                '{email}' => $email
            ];

		    $this->email_model->email_template_forgot($email,$vars,true,'reset_password');
		    echo json_encode(array('error' => false,"status" => true, "msg" => $this->lang->line('email_has_been_sent_your_account')));
	    }else{
	    	echo json_encode(array('error' => true,"status" => false, "msg" => $this->lang->line('invalid_email_address_try_again')));
	    }
    }


    public function verify_forgot_password($email_code)
    {
        $this->load->library('session');
        $data['new'] = 'new';
        $this->session->set_flashdata('item',$email_code);
        $email_codes = base64_decode(urldecode($email_code));
        $query = $this->db->get_where('users', ['reset_password_code' => $email_codes]);
        if ($query->num_rows() > 0) {
            $user = $query->row_array();
            $this->template->load_user('template/forgot-password',$data);
        } 
        else {
            $this->session->set_flashdata('error', $this->lang->line('expired_link'));
            redirect(base_url('user-login'));
        }     
 
    }


    public function forgot_screen()
    {
        $data['new'] = 'new';
        $this->template->load_user('template/forgot-password',$data);
    }


    public function update_password()
    {
     
        $this->load->library('form_validation');
        $rules = array(
            array(
                'field' => 'first_pass',
                'label' => 'new password',
                // 'rules' => 'required|min_length[5]|max_length[50]',
                'rules' => 'required',
            ),
            array(
                'field' => 'second_pass',
                'label' => 'confirm password',
                // 'rules' => 'required|min_length[5]|max_length[50]',
                'rules' => 'required',
            ),
        );
         $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "message" => validation_errors()));
            exit();
        } 
        $password_len =$this->input->post('first_pass');
        $password_len2 =$this->input->post('second_pass');

        // $code = $this->session->flashdata('item');

        $base64_code = $this->input->post('code');
        $code = base64_decode(urldecode($base64_code));
        $password = hash("sha256",$this->input->post('first_pass'));
        $cpassword=hash("sha256",$this->input->post('second_pass'));

        if ($password == $cpassword)
        {
            $result= $this->db->get_where('users', ['reset_password_code' => $code])->row_array();
            $this->db->update('users',['password'=>$password,'reset_password_code'=>0],['id'=>$result['id']]);
            $email = $result['email'];
            $link_activate = base_url() . 'home/verify_forgot_password/' . urlencode(base64_encode($email_code));
            $vars = [
                '{link_activation}' => $link_activate,
                '{btn_link}' => $link_activate,
                '{email}' => $email
            ];

		    $this->email_model->email_template_update_password_success($email,$vars,false,'update_password_success');
            $this->session->set_flashdata('success', $this->lang->line('your_password_is_changed'));
            redirect(base_url('user-login'));
            // echo json_encode(array('status'=>true, "success" => "Your password is changed"));
            exit();   
        }
        else
        {
            $this->session->set_flashdata('error', $this->lang->line('Password_confirm_not_matched'));
            redirect(base_url('user-login'));
            exit();
        }     
    }

	public function about_mission()
	{
		$data = array();
		$this->template->load_user('home/our_mission', $data);
	}

	public function about_team()
	{
		$data = array();
		$this->template->load_user('home/our_team', $data);
	}

	public function terms_conditions()
	{
		$data = array();
        $data['new'] = 'new';
        $data['terms_info'] = $this->db->get('terms_condition')->row_array();
        ///// End Auction list //////
		$this->template->load_user('home/new/terms_condition', $data);
	}

    public function quality_policy()
    {
        $data = array();
        $data['new'] = 'new';
        $data['qualityPolicy'] = $this->db->get('quality_policy')->row_array();
        ///// End Auction list //////
        $this->template->load_user('home/new/quality_policy', $data);
    }

    public function how_to_register()
    {
        $data = array();
        $data['new'] = 'new';
        $data['howToRegister'] = $this->db->get('how_to_register')->row_array();
        ///// End Auction list //////
        $this->template->load_user('home/new/how_to_register', $data);
    }
    public function how_to_deposit()
    {
        $data = array();
        $data['new'] = 'new';
        $data['howToDeposit'] = $this->db->get('how_to_deposit')->row_array();
        ///// End Auction list //////
        $this->template->load_user('home/new/how_to_deposit', $data);
    }
     public function auction_guide()
    {
        $data = array();
        $data['new'] = 'new';
        $data['auctionGuide'] = $this->db->get('auction_guide')->row_array();
        ///// End Auction list //////
        $this->template->load_user('home/new/auction_guide', $data);
    }
    public function policy()
    {
        $data = array();
         $data['new'] = 'new';
        $data['terms_info'] = $this->db->get('privacy_policy')->row_array();
        $this->template->load_user('home/new/privacy_policy', $data);
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

        public function email_template_($email='', $content=array(), $vars=array(), $btn=false){
        if(!empty($content)){
            
            $data = [
            	'btn' => $btn,
            	'notification' => $content
            ];
            
            // $email_message = $this->load->view('email_templates/common_template', $data, true);
            $email_message = "Pioneer auction verification";

            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }

            //return print_r($email_message);
            $this->email->to($email);
            $this->email->subject('pioneer');
            $this->email->message($email_message);
            $is_send = $this->email->send();
            return ($is_send) ? true : false;
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

    public function instagram_feedback($n) { 
        $user_data = $this->instagram_api->get_user($this->session->userdata('instagram-user-id'));
        // Get the user feed
        $user_feed = $this->instagram_api->get_user_feed();
        // print_r($user_feed);die('qadeer');
    }

    public function instagram_token() { 

        $ch = curl_init();
        $post = array(
            'client_id' => '1136665713336405',
            'client_secret' => 'f978d5aea1472b0e8bb165dd0d7d4e7d',
            'grant_type' => 'authorization_code ',
            'redirect_uri' => 'https://pa.yourvteams.com/',
            'code' => 'AQA__Zs2y7jB8a1u-1XdVprKoCZfLBrf2sIsXIi57R7BKVayXtp9iCqJ0srs1HyEi55vLt-QcZNHmfPLIgTPl1_TEl_0UL5-e3OunGIaGDOnefpPe25NeHED3_-LI95qOY0v3pHNFvfohO25jqSQKof_zZkSCLDbld3KG2nASci-In-u4uZEyKE4LJCXQqGSq7L65a80HBq2jdnbg305zRPvetlUQ5snA7D77r-PjnlYCw#_'
        );
        curl_setopt($ch, CURLOPT_URL, 'https://api.instagram.com/oauth/access_token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        $headers   = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        print_r($result);
        echo "\n";
        echo "------------------------------------";
        // die('Table Create');
        die();
    }
}//end controller




