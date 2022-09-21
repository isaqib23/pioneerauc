<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Transaction_Model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	 public function deposit_detail_list($id="")
    {
        if($id){
        $this->db->select('*');
        $this->db->from('transaction');
        $this->db->where('user_id', $id);
        }else
        {
        $this->db->select('*');
        $this->db->from('transaction');
        }
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function permanent_deposit($id='')
     {
        $this->db->select('*');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'permanent');
        if ($id) {
            $this->db->where('user_id',$id);
        }
        $this->db->where_in('status',['approved','refund']);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
     }
    public function get_transactiom_detail($id)
    {
        $this->db->select('*');
        $this->db->from('transaction');
        $this->db->where('user_id', $id);
        $q = $this->db->get();
        $query = $q->result_array();
        
            return $query;
     
    }

    public function user_bid_detail($id='')
    {
        $this->db->select('bid.*,users.fname,users.lname,item.name as item_name,item.price as reserve_price,item.category_id as item_category,item.subcategory_id as item_subcategory,item.lot_id as item_lot_id,item.registration_no as item_registration_no,item.barcode,item.make,item.model,item.item_images,item.item_attachments,item.item_status,item.sold,item.in_auction,item.seller_id');
        $this->db->from('bid');
        $this->db->join('users', 'bid.user_id = users.id', 'left');
        $this->db->join('item', 'bid.item_id = item.id', 'left');
        if ($id) {
            $this->db->where('bid.user_id', $id);
        }
        $this->db->where('bid.bid_status', 'won');
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
     
    }


    public function filter_bid_history($where)
    {
        $this->db->select('bid.*,users.fname,users.lname,item.name as item_name,item.price as reserve_price,item.category_id as item_category,item.subcategory_id as item_subcategory,item.lot_id as item_lot_id,item.registration_no as item_registration_no,item.barcode,item.make,item.model,item.item_images,item.item_attachments,item.item_status,item.sold,item.in_auction,item.seller_id');
        $this->db->from('bid');
        $this->db->join('users', 'bid.user_id = users.id', 'left');
        $this->db->join('item', 'bid.item_id = item.id', 'left');
        
        // $this->db->select('*');
        // $this->db->from('bid');
        $this->db->where($where);
          $query = $this->db->get(); 
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }


    public function get_security_detail($id="")
    {
        if($id){
        $this->db->select('*');
        $this->db->from('auction_item_deposits');
        $this->db->where('user_id', $id);
        }else
        {
        $this->db->select('*');
        $this->db->from('auction_item_deposits');
        }
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

     public function delete_transection($id, $table='')
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete($table);
            return true;
        } else {
            return false;
        }
    }


}