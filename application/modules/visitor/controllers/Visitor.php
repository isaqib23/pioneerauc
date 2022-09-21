<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitor extends MX_Controller {
	public $language = 'english';
	function __construct() {
		parent::__construct();		 		
        $this->load->model('Visitor_model', 'visitor_model');
        $this->load->model('files/Files_model', 'files_model');
        $this->load->model('home/Home_model', 'home_model');
        $this->load->model('email/Email_model','email_model');
        $this->load->model('auction/Live_auction_model', 'lam');
        $this->load->library('TestUnifonic');
        $language = ($this->session->userdata('site_lang')) ? $this->session->userdata('site_lang') : 'english';
	}

	public function index()
	{
		$data =array();
		$data['account_active'] = 'active';
		$this->template->load_user('dashboard', $data);
	}

	public function contact_us()
	{ 	
		$data = array();
		$data['page'] = 'contact_us';
		$data['new'] = 'new';
		$data['contact_us_info'] = $this->db->get('contact_us')->row_array();
		$data['language'] = $this->language;
		$this->template->load_user('new/contact-us', $data);		
	}
	public function faq()
	{ 	
		$data['contact_us_info'] = $this->db->get('contact_us')->row_array();
		$data['language'] = $this->language;
		$this->template->load_user('new/contact-us', $data);		
	}

	public function our_team()
	{ 	
		$data = array();
		$data['our_team_info'] = $this->db->get('our_team')->result_array();
		$data['our_team'] = $this->db->get('team_info')->row_array();
		// print_r($data['our_team_info']);die();
		$this->template->load_user('our_team', $data);		
	}

	public function livestream()
	{ 	
		$data = array();
		$data['page'] = 'livestream';
		$data['new'] = 'new';
		$lvl = $this->db->get('auction_live_settings')->row_array();
        $data['lvl'] = $lvl['live_video_link'];
		$data['language'] = $this->language;
		$this->template->load_user('new/live_broadcasting', $data);	
	}

	public function about_us()
	{ 	
		$data = array();
		$data['new'] = 'new';
		$data['about'] = $this->db->get('about_us')->row_array();
		// print_r($data['about']);die();
		$data['history'] = $this->db->get('about_us_history')->result_array();
		$this->template->load_user('new/about-us', $data);		
	}

	public function faqs()
	{ 	
		$data = array();
		$data['new'] = 'new';
		$data['list'] = $this->db->get('ques_ans')->result_array();
		///// Auction list //////
	    $data['active_auction_categories'] = $this->home_model->get_active_auction_categories();
	     foreach ($data['active_auction_categories'] as $key => $value) {
	        $auctions_online = $this->home_model->get_online_auctions($value['id']);
	        if (!empty($auctions_online)) {
	        	$data['active_auction_categories'][$key]['auction_id'] =  $auctions_online['id'];
	            $count= $this->db->select('*')->from('auction_items')->where('auction_id',$auctions_online['id'])->where('sold_status','not')->where('auction_items.bid_start_time <',date('Y-m-d H:i'))->get()->result_array();
	            $count = count($count);
	            $data['active_auction_categories'][$key]['item_count'] =  $count;
	        }
	    }
        ///// End Auction list //////
		$this->template->load_user('new/faq_page', $data);		
	}
	public function liveStreaming()
	{ 	
		$data = array();
		$data['new'] = 'new';
		$this->template->load_user('live_streaming', $data);		
	}
	  public function qualityPolicy()
    {
        $data = array();
        $data['new'] = 'new';
        $data['terms_info'] = $this->db->get('privacy_policy')->row_array();
        $this->template->load_user('visitor/quality_policy', $data);
    }

	public function gallery($id='')
	{ 	
		// $this->load->library('email');
		// $email_message ='dummy message ';
		// 	 $subject='dummy subject ';
	 //        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	 //        <html xmlns="http://www.w3.org/1999/xhtml">
	 //        <head>
	 //            <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
	 //            <title>' . html_escape($subject) . '</title>
	 //            <style type="text/css">
	 //                body {
	 //                    font-family: Arial, Verdana, Helvetica, sans-serif;
	 //                    font-size: 16px;
	 //                }
	 //            </style>
	 //        </head>
	 //        <body>
	 //        ' . $email_message . '
	 //        </body>
	 //        </html>';
	 // 		$send = $this->email->from($this->config->item('sending_email'))->to("muhammadqadeer661@gmail.com")->subject($subject)->message($body)->send();
			
			
		// 	echo $send;die;
		
		$data = array();	
		$data['new'] = 'new';	
		$data['total'] = $this->visitor_model->count_item($id);
		$data['category'] = $this->db->get('item_category')->result_array();
		$data['list'] = $this->visitor_model->items($id);
		foreach ($data['list'] as $key => $value) {
	        $images_ids = explode(",",$value['item_images']);
	        $data['list'][$key]['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
		}
		// print_r($data['list']);die();
		$data['count'] = count($data['list']);
		$data['id'] = $id;
		$data['language'] = $this->language;
		// $data['list'] = $this->db->get('item')->result_array();
		// $data['history'] = $this->db->get('about_us_history')->result_array();
		$this->template->load_user('gallery-page', $data);		
	}

	public function gallery_order($id='')
	{ 	
		$post_data = $this->input->post();
		$data = array();	
		$order = '';
		if (!empty($post_data['sort'])) {
			$order = $post_data['sort'];
		}
		$total = $this->visitor_model->count_item($id);
		$list = $this->visitor_model->items($id,$order);
		foreach ($list as $key => $value) {
	        $images_ids = explode(",",$value['item_images']);
	        $list[$key]['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
		}
		$data['count'] = count($list);
		$data['id'] = $id;

		if(count($list) > 0){
			$btn = ($total > 8);
			$gallery = $this->load->view('gallery-load-more', ['list'	=> $list], true);
			$output = json_encode([
				'status' => 'success', 
				'galleryArticles' => $gallery, 
				'total' => $total, 
				'offset' => 8,
				'btn' => $btn]);
			 print_r($output);
		}else{
			$output = json_encode(['status' => 'failed']);
			return print_r($output);
		}	
	}

	public function gallery_load_more($id='')
	{ 	
		$post_data = $this->input->post();
		if($post_data){
			//total records
			$total = $this->visitor_model->count_item($id);

			//offset records$order = '';
			$limit = 4;
			$order ='';
			if (!empty($post_data['sort'])) {
				$order = $post_data['sort'];
			}
			$offset = (int)$post_data['offset'];
			$next_offset = $offset + $limit;
			$list = $this->visitor_model->items($id,$order,$offset,$limit);
			foreach ($list as $key => $value) {
		        $images_ids = explode(",",$value['item_images']);
		        $list[$key]['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
			}
			if(count($list) > 0){
				//$media = $this->load->view('user/media-load-more', ['list'	=> $list], true);
				$gallery = $this->load->view('gallery-load-more', ['list'	=> $list], true);
				$btn = ($total > $next_offset);
				$output = json_encode([
					'status' => 'success', 
					'galleryArticles' => $gallery, 
					'total' => $total, 
					'offset' => $next_offset,
					'btn' => $btn]);
				return print_r($output);
			}else{
				$btn = ($total > $next_offset);
				$output = json_encode(['status' => 'failed','btn' => $btn]);
				return print_r($output);
			}
		}	
	}

	public function gallery_detail($id='')
	{ 	
		$data = array();
		$data['new'] = 'new';
		$data['list'] = $this->db->get_where('item', ['id' => $id])->row_array();
        $images_ids = explode(",",$data['list']['item_images']);
        $data['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        $data['language'] = $this->language;
		$this->template->load_user('gallery-detail', $data);		
	}
  		
	public function contactUs_visitor()
	{   
		$data1 = $this->input->post();
        // $email_message='New visitor';
        $data=$this->db->get('contact_us')->row_array();
        $email = $data['email'];
		// $link_activate = base_url() . 'home/index/';
	        $vars = [
				'{username}' => $data1['fname'],
				'{email}' => $data1['emailContact'],
				'{mobile}' => $data1['mobileContact'],
				// '{subject}' => $data1['subject'],
				'{text}' => $data1['text'],
			];
	 $this->email_model->visitor_template($email,$vars,false,'visitor_template');
     $this->session->set_flashdata('msg', $this->lang->line("thank_for_contacting_us"));
	 redirect(base_url().'contact-us');
	}

}
