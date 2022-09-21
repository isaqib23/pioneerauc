<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('vendor/autoload.php');
class Screens extends MY_Controller {
	
	function __construct() {
		parent::__construct();		 
		// $this->load->model('Screen_model','screen_model');
		
	}
		public function main_screen(){
		$this->load->view('screen_index');
	}



	public function screen_three_default(){
		$this->load->view('screen_three');	

	}

		public function screen_three(){
			return 'fgfgf';
		// $item_id = $this->uri->segment(2);	
		$item_id = $this->input->post('item_id');	
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
		// $bid_data = $this->uri->segment(3);
		$data = $this->db->get_where('item',['id' => $item_id])->row_array();
		$lot_number = $this->db->get_where('auction_items',['item_id' => $data['id']])->row_array();
		$images = explode(',', $data['item_images']);
  		//get data from "item_category_fields" against item categorie id where name is transmission
		$item_category_fields = $this->db->
		get_where('item_category_fields',['category_id' => $data['category_id'],'name' => 'transmission'])
		->result_array();
		///match "item_category_fields" id with "item_fields_data" fields_id to get value of transmission
		$item_fields_data = $this->db->get_where('item_fields_data',['fields_id' =>$item_category_fields[0]['id']])->row_array();
		$item_images = $this->files_model->get_multiple_files_by_ids($images);

		//current bid amount
		//$cb_amount = $this->db->select_sum('bid_increment_amount')->where(['auction_id' => $lot_number['auction_id'], 'item_id' => $item_id])->get('live_auction_bid_log')->row_array();

		//current bid data
		$current_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $lot_number['auction_id'], 'item_id' => $item_id])->row_array();

		$push['item_id'] = $data['id'];
		$push['auction_id'] = $lot_number['auction_id'];
		$push['cb_amount'] = $current_bid['bid_amount'];
		$push['current_bid'] = $current_bid;
		$push['data'] = $data;
		$push['item_fields_data'] = $item_fields_data;
		$push['item_images'] = $item_images;
		// $push['bid_data'] = $bid_data;
		$push['lot_number'] = $lot_number;
		print_r($push);
		// $pusher->trigger('ci_pusher', 'my-event', $push);
	}

	public function screen_two(){
		$this->load->view('screen_two');
	}
}




