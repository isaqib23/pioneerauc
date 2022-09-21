<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sales_model extends CI_Model
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

    public function get_auction_item_ids($id='')
    {
        $this->db->select('item_id');
        $this->db->from('auction_items');
        if(!empty($id))
        {
            $this->db->where('auction_id', $id);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function check_registration_no($registration_no)
    {
        $this->db->where('registration_no',$registration_no);
        $query = $this->db->get('auctions');
        if ($query->num_rows() > 0){
            return true;
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


    public function get_active_item_list($where='')
    {
        $this->db->select('item.*,item_category.title as category_name,item_makes.title as make_name,item_models.title as model_name,users.code as seller_code');
        $this->db->from('item');
        $this->db->join('item_category','item_category.id = item.category_id','left');
        $this->db->join('users', 'item.seller_id = users.id', 'left');
        $this->db->join('item_makes','item_makes.id = item.make','left');
        $this->db->join('item_models','item_models.id = item.model','left');
        if($where!='')
        {
            $this->db->where_in('item.id', $where);
        }
        $result = $this->db->get()->result_array(); 
        return $result;
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

    public function get_item_detail($id)
    {
        $this->db->select('*');
        $this->db->from('item');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } 
        
    }
    public function check_transaction($id)
    {
        $this->db->select_sum('amount');
        $this->db->from('transaction');
        $this->db->where('user_id',$id);
        $result = $this->db->get();
        if($result)
        {
            return $result->result_array();
        }
    }
    public function get_deposite($id)
    {
        $this->db->select('total_deposite');
        $this->db->from('users');
        $this->db->where('id',$id);
        $result = $this->db->get();
        if($result)
        {
            return $result->result_array();
        }
    }
    public function check_item_amount($id)
    {
        $this->db->select('price');
        $this->db->from('item');
        $this->db->where('id',$id);
        $result = $this->db->get();
        return $result->result_array();
    }
    public function check_security($id)
    {
        $this->db->select('security');
        $this->db->from('auction_items');
        $this->db->where('item_id',$id);
        $result = $this->db->get();

        return $result->result_array();
    }
    public function check_security_amount($id)
    {
        $this->db->select('*');
        $this->db->from('security');
        $this->db->where('item_id',$id);
        $result = $this->db->get();

        return $result->result_array();
    }
    public function chech_transaction_from_configuration()
    {
        $this->db->select('deposite');
        $this->db->from('settings');
        $result = $this->db->get();
        return $result->result_array();
    }
    public function get_minimum_bidding_amount()
    {
        $this->db->select('*');
        $this->db->from('auction_items');
        $result = $this->db->get();
        return $result->result_array();
    }
    public function insert_bid_data($id ,$data)
    {
        $this->db->select_max('bid_amount');
        $this->db->from('bid');
        $this->db->where('item_id',$id);
        $q = $this->db->get();
        if(!empty($q->result_array()))
        {
        $oldQty =  $q->result_array()[0]['bid_amount'];
        if(!empty($oldQty)){
        $data['bid_amount']  =   $oldQty + $data['bid_amount']; 
        } 
        }
        if(empty($oldQty)){
        $this->db->select('bid_start_price');
        $this->db->from('auction_items');
        $this->db->where('item_id',$id);
        $q = $this->db->get();
        $bid_start_price = $q->result_array();
        if($bid_start_price[0]['bid_start_price'] == 0)
        {
            $this->db->select('price');
            $this->db->from('item');
            $this->db->where('id',$id);
            $q = $this->db->get();
            $bid_start_price = $q->result_array();
            $data['bid_amount']  =   $bid_start_price[0]['price'] + $data['bid_amount'];
        }
        else{
        $data['bid_amount']  =   $bid_start_price[0]['bid_start_price'] + $data['bid_amount']; 
        }
    }
        $this->db->insert('bid', $data);
        $inserted_id = $this->db->insert_id();
        if($inserted_id)
        {
            return $inserted_id;
        } 
    }
    public function remaining_time($id)
    {
        $this->db->select('*');
        $this->db->from('auction_items');
        $this->db->where('item_id',$id);
        $q = $this->db->get();
        return $q->result_array();

    }
     public function get_item_byid($id)
    {
        $this->db->select('*');
        $this->db->from('item');
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }
      public function get_item_documents_byid($ids)
    {
        $this->db->select('*');
        $this->db->from('files');
        $this->db->where_in('id',$ids);
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    public function get_item_images_byid($ids)
    {
        $this->db->select('*');
        $this->db->from('files');
        $this->db->where_in('id',$ids);
        // $this->db->where('status', 'active');
        $result = $this->db->get()->result_array();
        // echo $this->db->last_query();
        return $result;
    }
    public function get_total_bids($id)
    {
        $this->db->select('*');
        $this->db->from('bid');
        $this->db->where('item_id',$id);
        $result = $this->db->get();
        return $result->num_rows();
    }
    public function sum_bid_price($id)
    {
        $this->db->select_sum('bid_amount');
        $this->db->from('bid');
        $this->db->where('item_id',$id);
        $result = $this->db->get();
        return  $result->result_array();
    }
    public function last_bid_amount($id)
    {
        $this->db->select_max('bid_amount');
        $this->db->from('bid');
        $this->db->where('item_id',$id);
         $q = $this->db->get();
        $result = $q->result_array();
        return $result;
    }


     public function update_user_deposite($id,$data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('users', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }
    public function max_bid_amount($item_id , $auction_id)
    {
        $this->db->select_max('bid_amount');
        $this->db->from('bid');
        // $this->db->where('user_id', $user_id);
        $this->db->where('item_id' , $item_id);
        $this->db->where('auction_id' , $auction_id);
        $result = $this->db->get();
        return $result->result_array();
    }
     public function current_bid_amount($item_id , $auction_id)
    {
        $this->db->select_max('bid_amount');
        $this->db->from('bid');
        // $this->db->where('user_id', $user_id);
        $this->db->where('item_id' , $item_id);
        $this->db->where('auction_id' , $auction_id);
        $result = $this->db->get();
        return $result->result_array();
    }
     public function user_max_bid_amount($user_id ,$item_id , $auction_id)
    {
        $this->db->select_max('bid_amount');
        $this->db->from('bid');
        $this->db->where('user_id', $user_id);
        $this->db->where('item_id' , $item_id);
        $this->db->where('auction_id' , $auction_id);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function second_last_bid_record()
    {
        $this->db->select('*');
        $this->db->from('bid');
        $this->db->order_by('id',"DESC");
        $this->db->limit(1,1);
        $result = $this->db->get('');
        return $result->result_array();

    }  
    public function last_bid_record()
    {
        $this->db->select('*');
        $this->db->from('bid');
        $this->db->order_by('id',"DESC");
        $this->db->limit(1);
        $result = $this->db->get('');
        return $result->result_array();

    }
    public function get_total_deposite($id)
    {
        $this->db->select('total_deposite');
        $this->db->from('users');
        $this->db->where('id',$id);
        $q = $this->db->get();
        return $q->result_array()[0];
    }
    public function start_bid_amount($id)
    {
         $this->db->select('bid_start_price');
        $this->db->from('auction_items');
        $this->db->where('item_id',$id);
        $q = $this->db->get();
        return $q->result_array();   
    }
    public function get_user_email($id)
    {
        $this->db->select('email');
        $this->db->from('users');
        $this->db->where('id',$id);
        $q = $this->db->get();
        $result = $q->result_array()[0];
        return $result;

    }
    public function get_item_price($id)
    {
        $this->db->select('price');
        $this->db->from('item');
        $this->db->where('id',$id);
        $q = $this->db->get();
        $result = $q->result_array()[0];
        return $result;

    }

    public function change_item_status($id)
    {
        $data['status'] = "cancel";
        $this->db->where('id',$id);
        $this->db->update('item',$data);

    }


}
