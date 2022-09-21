<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auction_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    

    public function get_auctions($id='')
    {
        $this->db->select('*');
        $this->db->from('auctions');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }
     public function get_live_auction_items_banner_detail($auction_id)
  {
    $select = '
      ai.id as auction_item_id,
      ai.item_id as item_id,
      ai.sold_status,
      ai.auction_id as auction_id,
      ai.bid_start_price as bid_start_price,
      ai.allowed_bids as allowed_bids,
      ai.minimum_bid_price as minimum_bid_price,
      ai.bid_start_time as bid_start_time,
      ai.security as security,
      ai.bid_end_time as bid_end_time,
      ai.lot_no as lot_no,
      ai.order_lot_no as order_lot_no,

      i.name as item_name,
      i.price as item_price,
      i.year as year,
      i.mileage as mileage,
      i.mileage_type as mileage_type,
      i.specification as specification,
      i.lot_id as item_lot_id,
      i.category_id as category_id,
      i.subcategory_id as subcategory_id,
      i.detail as item_detail,
      i.feature as item_feature,
      i.unique_code as item_unique_code,
      i.registration_no as item_registration_no,
      i.barcode as item_barcode,
      i.unique_code as item_unique_code,
      i.vin_number as item_vin_number,
      i.make as item_make,
      i.item_images as item_images,
      i.model as item_model,
      i.item_attachments as item_attachments,
      i.keyword as item_keyword,
      i.item_test_report as item_test_report,
      i.seller_id as item_seller_id,
      i.item_status as item_status,
      i.sold as item_sold,
      i.in_auction as item_in_auction,
      i.other_charges as item_other_charges,
      ic.include_make_model as item_make_model
      ';

    $where = [
      'ai.auction_id' => $auction_id,
      'i.sold' => 'no',
      'ai.sold_status' => 'not'
    ];

    $this->db->select($select);
    $this->db->from('auction_items ai');
    $this->db->join('item i', 'i.id = ai.item_id', 'LEFT');
    $this->db->join('item_category ic', 'i.category_id = ic.id', 'LEFT');
    $this->db->where($where);
    $this->db->order_by('ai.id',"desc");
    $auction_items = $this->db->get();
    $auction_items = $auction_items->result_array();
    // echo $this->db->last_query();die();
    return $auction_items;
  }

    public function get_auction_item_ids($id='')
    {
        $this->db->select('item_id');
        $this->db->from('auction_items');
        if(!empty($id))
        {
            $this->db->where('auction_id', $id);
        }
        $this->db->group_by('auction_items.item_id');
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function check_registration_no($registration_no)
    {
        // $this->db->where();
        $query = $this->db->get_where('auctions', ['registration_no' =>$registration_no]);
        if ($query->num_rows() > 0){
            return true;
            // return $this->db->last_query();
            // return $query->num_rows();
        }
        else
        {
            return false;
        }
    }

  	public function get_auction_item_ids_by_auction_category($auction_id='',$category_id='')
    {
        $this->db->select('item_id');
        $this->db->from('auction_items');
        if(!empty($auction_id))
        {
            $this->db->where('auction_id', $auction_id);
            $this->db->where('category_id', $category_id);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getActiveAuctions(){
        $this->db->select('*');
        $this->db->from('auctions');
        $this->db->where(' expiry_time >= NOW() ');
        $this->db->where('status', 'active');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }else{
            return array();
        }
    }

    public function getActiveAuctionItems($auction_id){
        $this->db->select('*');
        $this->db->from('auction_items');
        $this->db->where('status', 'active');
        $this->db->where('sold_status !=', 'not_sold');
        $this->db->where_in('auction_id', $auction_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }else{
            return array();
        }
    }

    public function getsoldItems(){
        $this->db->select('*');
        $this->db->from('auction_items');
        $this->db->where('sold_status', 'sold');
        $this->db->or_where('sold_status', 'approvel');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }else{
            return array();
        }
    }

    public function update_auction_item_bidding_rules($item_id,$auction_id, $data)
    {
        $this->db->where('item_id', $item_id);
        $this->db->where('auction_id', $auction_id);
        $query = $this->db->update('auction_items', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

    public function get_auction_list()
    {
        $this->db->select('auctions.*,item_category.title as category_name');
        $this->db->from('auctions');
        $this->db->join('item_category','item_category.id = auctions.category_id','left');
        $this->db->where('access_type !=', 'live');
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function auction_filter_list($where = '')
    {
        $this->db->select('auctions.*,item_category.title as category_name');
        $this->db->from('auctions');
        $this->db->join('item_category','item_category.id = auctions.category_id','left');
        if($where != '')
        {
            $this->db->where($where);
        }
        $this->db->where('access_type !=', 'live');
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        // echo $this->db->last_query();
        return $result;
    }

    public function get_live_auction_list()
    {
        $this->db->select('auctions.*,item_category.title as category_name');
        $this->db->from('auctions');
        $this->db->join('item_category','item_category.id = auctions.category_id','left');
        $this->db->where('access_type', 'live');
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function live_auction_filter_list($where = '')
    {
        $this->db->select('auctions.*,item_category.title as category_name');
        $this->db->from('auctions');
        $this->db->join('item_category','item_category.id = auctions.category_id','left');
        if($where != '')
        {
            $this->db->where($where);
        }
        $this->db->where('access_type', 'live');
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

  	public function get_auction_list_active()
  	{
		$this->db->select('*');
		$this->db->from('auctions');
		$this->db->where('status', 'active');
		$result = $this->db->get()->result_array();
		return $result;
	}


    public function insert_auction($data)
    {
        $this->db->insert('auctions', $data);
        $inserted_id = $this->db->insert_id();
        if($inserted_id)
        {
            return $inserted_id;
        }
        else
        {
            return false;
        }
    }

    public function insert_auction_items($data)
    {
        $this->db->insert('auction_items', $data);
        $inserted_id = $this->db->insert_id();
        if($inserted_id)
        {
            return $inserted_id;
        }
        else
        {
            return false;
        }
    }

    public function update_auction($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('auctions', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }
    

    public function auction_item_bidding_rule_list($auction_id,$item_id)
    {
        $this->db->select('*');
        $this->db->from('auction_items');
        $this->db->where('item_id',$item_id);
        $this->db->where('auction_id',$auction_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function multi_auction_item_bidding_rule_list($auction_id_arr,$item_id)
    {
        $this->db->select('*');
        $this->db->from('auction_items');
        $this->db->where('item_id',$item_id);
        $this->db->where_in('auction_id',$auction_id_arr);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }


    public function delete_auction_row($id)
    {
        $this->db->select('*');
        $this->db->from('auctions');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('auctions');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_row_from_auctionitems($auction_id,$item_id)
    {
        $this->db->select('*');
        $this->db->from('auction_items');
        $this->db->where('item_id', $item_id);
        $this->db->where('auction_id', $auction_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('item_id', $item_id);
            $this->db->where('auction_id', $auction_id);
            $this->db->delete('auction_items');
            return true;
        } else {
            return false;
        }
    }

    public function delete_auction_item_rows($id)
    {
        $this->db->select('*');
        $this->db->from('auction_items');
        $this->db->where('auction_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('auction_id', $id);
            $this->db->delete('auction_items');
            return true;
        } else {
            return false;
        }
    }

    public function delete_auction_MultipleRow($ids_array)
    {
        $ids_array = explode(",",$ids_array);
        $this->db->select('*');
        $this->db->from('auctions');
        $this->db->where_in('id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('id', $ids_array);
            $this->db->delete('auctions');
            return true;
        } else {
            return false;
        }
    }

    public function delete_auction_items_multiple_row($ids_array)
    {
        $ids_array = explode(",",$ids_array);
        $this->db->select('*');
        $this->db->from('auction_items');
        $this->db->where_in('auction_id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('auction_id', $ids_array);
            $this->db->delete('auction_items');
            return true;
        } else {
            return false;
        }
    }
 
}
