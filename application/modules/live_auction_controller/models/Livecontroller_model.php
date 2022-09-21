<?php

class Livecontroller_model extends CI_Model
{

  	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
   
   public function getAuction(){
   		$this->db->where('status', 'active');
   		$this->db->where('access_type', 'live');
		$time_check = ' expiry_time >= NOW()';
		$this->db->where($time_check);
		$all_list = $this->db->get('auctions')->result_array();
		if($all_list){
			return $all_list;
		}else{
			return array();
		}
   }
   public function getAuctionDetail($id){
		$this->db->where('id',$id);
		$all_list = $this->db->get('auctions')->row_array();
		if($all_list){
			return $all_list;
		}else{
			return array();
		}
   }

   public function getAuctionItems($auction_id){
   	$this->db->where('auction_id', $auction_id);
   	$this->db->group_by('item_id');
   	$response = $this->db->get('auction_items')->result_array();
	if($response){
		return $response;
	}else {
		return array();
	}
   }

   public function getAuctionItemsActive($auction_id, $search){
   	$this->db->where('auction_id', $auction_id);
   	if (!empty($search)) {
   		$this->db->where('order_lot_no', $search);   		
   	}
   	$this->db->where('sold_status', 'not');
   	$this->db->group_by('item_id');
   	$this->db->order_by('order_lot_no', 'ASC');
   	$response = $this->db->get('auction_items')->result_array();
	if($response){
		return $response;
	}else {
		return array();
	}
   }

   public function getAuctionItemsSoldActive($auction_id){
   	$this->db->where('auction_id', $auction_id);
   	$this->db->where_in('sold_status', array('sold','approval'));
   	$this->db->group_by('item_id');
    $this->db->order_by('order_lot_no', 'ASC');
   	$response = $this->db->get('auction_items')->result_array();
	if($response){
		return $response;
	}else {
		return array();
	}
   }

   public function getAuctionItemsDetail($id){ 
   	$this->db->where('id', $id);
   	$response = $this->db->get('auction_items')->row_array();
	if($response){
		return $response;
	}else {
		return array();
	}
   }

   public function getItemDetail($item_id){
   	$this->db->where('id', $item_id);
   	$response = $this->db->get('item')->row_array();
	if($response){
		return $response;
	}else {
		return array();
	}
   }


	public function get_all_customer_active($user_id){
		$this->db->select('*');
        $this->db->from('users');
        $this->db->where('role','4');
        $this->db->where('id',$user_id);
        $this->db->where('status','1');
        $result = $this->db->get()->row_array();
        return $result;
		
	}

   public function getControllerButtons(){
		$response = $this->db->get('auction_live_settings')->row_array();
		if($response){
			return $response;
		}else {
			return array();
		}
   }

   public function bidLog($auction_id,$item_id){
   	
   	//live_auction_bid_log

   }
}