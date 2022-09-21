<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Screen_model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}  

	public function auction_items()
	{
		$this->db->select('auction_items.*,item.* , live_auction_bid_log.*,item_models.*,milleage.*');
		$this->db->from('auction_items');
		$this->db->join('item' ,'item.id = auction_items.item_id');
		$this->db->join('item_models' ,'item_models.make_id = item.make');

		$this->db->join('milleage' ,'milleage.item_id = item.id');
		$this->db->join(' live_auction_bid_log' ,' live_auction_bid_log.auction_id = auction_items.auction_id');
		$this->db->where('live_status =','yes');
		$result = $this->db->get();
		if ($result->num_rows() > 0 ){
			return $result->row_array();
		}
		else
		{
			return false;
		}

	}


  
}	