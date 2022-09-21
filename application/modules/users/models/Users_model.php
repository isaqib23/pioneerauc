<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users_Model extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function login_check($username, $password)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $username);
		$this->db->where('password', $password);
		$this->db->where('status', '1');
		$query = $this->db->get();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
 

    public function get_cheque_data($id='')
    {
        $this->db->select('*');
        $this->db->from('transaction');
        $this->db->where('payment_type','cheque');
        $this->db->where('delete_status =',0);
        if(!empty($id))
        {
            $this->db->where('user_id', $id);
        }

        $result = $this->db->get()->result_array();
        return $result;
    }

   

    public function delete_cheque_row($id)
    {
        $this->db->select('*');
        $this->db->from('transaction');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('transaction');
            return true;
        } else {
            return false;
        }
    }

    public function update_cheque($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('transaction', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

     public function get_id()
    {
        $this->db->select('transaction.id');
        $this->db->from('transaction');
        $result = $this->db->get()->result_array();
        return $result;
    }

     public function get_manuall_detail($id='')
    {
        $this->db->select('*');
        $this->db->from('transaction');
        $this->db->where('payment_type','manuall');
        $this->db->where('delete_status =',0);
        if (!empty($id)) {
            $this->db->where('user_id =',$id);
        }
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function update_transaction($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('transaction', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }
     public function get_transaction($id='')
    {
        $this->db->select('transaction.transaction_info,transaction.payment_type');
        $this->db->from('transaction');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function get_cheque_ids($id='')
    {
        $this->db->select('id');
        $this->db->from('transaction');
        if(!empty($id))
        {
            $this->db->where('id', $id);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

      public function insert_cheque($data)
    {
        $this->db->insert('transaction', $data);
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

     public function get_all_sales_user(){
        $this->db->select('users.*,acl_roles.name as role_name');
        $this->db->from('users');
        $this->db->join('acl_roles',' acl_roles.id = users.role');
        $this->db->where('users.status',1);
        $this->db->where('users.role',4);
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
     }

     public function get_all_customer_user(){
        $this->db->select('users.*,acl_roles.name as role_name');
        $this->db->from('users');
        $this->db->join('acl_roles',' acl_roles.id = users.role');
        $this->db->where('users.status',1);
        $this->db->where('users.role',4);
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
     }
     
	public function update_user($id, $data){
		$this->db->where('id', $id);
        // echo "pre";
        // print_r($data);
		$return=$this->db->update('users', $data);	
        if($return)
        {
            return $return;
        }
        else
        {
            return false;
        }	
		// return $return;
	}
     
    public function insert_user_documents($data){
        // $this->db->where('id', $id);
        // echo "pre";
        // print_r($data);
        $return=$this->db->insert('user_documents', $data);  
        if($return)
        {
            return $return;
        }
        else
        {
            return false;
        }   
        // return $return;
    }
	public function insert_user_details($data)
    {
        $this->db->insert('users', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

    public function get_max_id()
    {
         $query= $this->db->query('select max(id) from users');
        return $query->result_array();

    }
    public function insert_user_bank_details($data)
    {
        $this->db->insert('user_bank_detail', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
    public function update_user_bank_detail($id, $data){
        $this->db->where('id', $id);
        $return=$this->db->update('user_bank_detail', $data);       
        return $return;
    }
    public function bank_detail_list($id)
    {
        $this->db->select('user_bank_detail.*');
        $this->db->from('user_bank_detail');
        $this->db->where('user_id', $id);
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    // public function deposit_detail_list($id)
    // {
    //     $this->db->select('user_deposit_detail.*,item_category.title as category_name');
    //     $this->db->from('user_deposit_detail');
    //     $this->db->join('item_category','user_deposit_detail.category_id = item_category.id');
    //     $this->db->where('user_deposit_detail.user_id', $id);
    //     // $this->db->order_by('id', 'desc');
    //     $query = $this->db->get();
    //     if ($query->num_rows() > 0) {
    //         return $query->result_array();
    //     }
    //     return array();
    // }
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

     public function get_security_detail($id="")
    {
        if($id){
        $this->db->select('*');
        $this->db->from('security');
        $this->db->where('user_id', $id);
        }else
        {
        $this->db->select('*');
        $this->db->from('security');
        }
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

     public function transaction_detail($id="")
    {
        if($id){
        $this->db->select('*');
        $this->db->from('auction_deposit');
        $this->db->where('id', $id);
        }else
        {
        $this->db->select('*');
        $this->db->from('auction_deposit');
        }
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

      public function transaction_detail_for_user($id="")
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
    public function insert_user_deposit_details($data)
    {
        $this->db->insert('user_deposit_detail', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
    public function update_user_deposit_detail($id, $data){
        $this->db->where('id', $id);
        $return=$this->db->update('user_deposit_detail', $data);       
        return $return;
    }
	public function users_list($id = 0)
    {
        if($id == 1){
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->where('role != ', 4);
        // if($id != 0)
        // {
        // 	$this->db->where('id',$id);
        // }
        }
        if($id == 2){
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->where('role ', 2);

        }
        if($id == 3){
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->where('role ', 3);
        }
        if($id == 0){
        $this->db->select('users.*');
        $this->db->from('users');
        } 
         if($id > 3){
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->where('id',$id);
        }  
        // $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    
    public function delete_user_row($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('users');
            return true;
        } else {
            return false;
        }
    }
         public function delete_select_row($id,$table)
    {
        $this->db->where('id', $id);
        $query = $this->db->delete($table);
  
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    public function delete_user_bank_detail($id)
    {
        $this->db->select('*');
        $this->db->from('user_bank_detail');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('user_bank_detail');
            return true;
        } else {
            return false;
        }
    }
    public function delete_user_deposit_detail($id,$table)
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
    public function get_deposit_detail_by_userid($userid){
        
    }
    public function check_email($email)
    {
    $this->db->where('email',$email);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0){
        return true;
    }
    else{
        return false;
    }
}

//      public function check_login_password($password)
//     {
//     $this->db->where('password',$password);
//     $query = $this->db->get('users');
//     if ($query->num_rows() > 0){
//         return true;
//     }
//     else{
//         return false;
//     }
// }


    public function users_listing($id = 0)
    {
        $this->db->select('users.*');
        $this->db->from('users');
         if($id != 0)
        {
            $this->db->where('id',$id);
        }
         $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function get_roles(){
        $this->db->select('*');
        $this->db->from('acl_roles');
        $this->db->where('id >',1);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_application_user_roles(){
        $app_user = array(2,3,5,7,8,9,10);
        $this->db->select('*');
        $this->db->from('acl_roles');
        $this->db->where_in('id',$app_user);
        // $this->db->where('id',3);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_user_roles_for_sale_manager(){
        $app_user = array(3);
        $this->db->select('*');
        $this->db->from('acl_roles');
        $this->db->where_in('id',$app_user);
        // $this->db->where('id',3);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    public function check_user_number($number)
        {
        $this->db->where('mobile',$number);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    public function get_all_application_users(){
        $app_user = array(2,3,5,7,8,9,10);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where_in('role',$app_user);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_application_users(){
        $app_user = array(2,3);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where_in('role',$app_user);
        $this->db->where('status',1);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_customer_roles(){
        $this->db->select('*');
        $this->db->from('acl_roles');
        $this->db->where('id',4);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function users_filter_list($where)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($where);
          $query = $this->db->get();
    
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function filter_for_deposite($where)
    {
        $this->db->select('*');
        $this->db->from('auction_deposit');
        $this->db->where($where);
        $this->db->where_in('status', ['approved', 'refund']);
          $query = $this->db->get();
    
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function users_sellerbuyer_filter_list($where)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($where);
        $this->db->where('role',4);
          $query = $this->db->get();
    
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function get_deposite_amount()
    {
        $this->db->select('*');
        $this->db->from('settings');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_users_list_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('role',$id);
        $query = $this->db->get();
         if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_user_byid($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_users_record_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('user_bank_detail');
        $this->db->where('id',$id);
        $query = $this->db->get();
         if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function get_deposit_detail_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('user_deposit_detail');
        $this->db->where('id',$id);
        $query = $this->db->get();
         if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function chahge_status($status_id,$user_id)
    {
        $this->db->where('id',$user_id);
        $return = $this->db->update('users',$status_id);

        return $return;
            }   

    public function users_sellers_buyers()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('role', 4);    
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        return array();
    }
    public function sellers_list()
    {
        $seller = "seller";
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('type', $seller);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        return array();
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
    public function insert_manually_deposite($data)
    {
        $this->db->insert('transaction', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
     public function get_transaction_max_id()
    {
         $query= $this->db->query('select max(id) from transaction');
        return $query->result_array();

    }
    public function get_amount_sum($deposite_user_id)
    {
        $this->db->select_sum('amount');
        $this->db->from('transaction');
        $this->db->where('user_id',$deposite_user_id);
        $query = $this->db->get();
        return $query->row();
    }
    public function update_user_deposite($id,$data)
    {
        // print_r($data);die('aaaaaaaaaaaa');
        $this->db->select('total_deposite');
        $this->db->from('users');
        $this->db->where('id',$id);
        $q=$this->db->get();
        $old_total_deposite = $q->result_array();
        if(!empty($old_total_deposite))
        {
            $data['total_deposite'] = $old_total_deposite[0]['total_deposite'] + $data['total_deposite'];
        }
        else
        {
        $this->db->where('id',$id);
        $this->db->insert('users',$data);
        }
        $this->db->where('id',$id);
        $this->db->update('users',$data);
    }

    public function get_user_payments($user_id)
    {
        $select = '
            sold_items.id as sold_item_id,
            sold_items.item_id as item_id,
            sold_items.auction_id as auction_id,
            sold_items.auction_item_id as auction_item_id,
            sold_items.buyer_id as buyer_id,
            sold_items.seller_id as seller_id,
            sold_items.buyer_charges as buyer_charges,
            sold_items.seller_charges as seller_charges,
            sold_items.price as win_bid_price,
            sold_items.adjusted_security as adjusted_security,
            sold_items.adjusted_deposit as adjusted_deposit,
            sold_items.payable_amount as payable_amount,
            sold_items.payment_status as payment_status,
            sold_items.created_by as created_by,
            sold_items.updated_by as updated_by,
            sold_items.created_on as created_on,
            sold_items.updated_on as updated_on,
            sold_items.sale_type as sale_type,

            item.name as item_name,
            item.registration_no as registration_no,

            auctions.id as auction_id,
            auctions.title as auction_title,
            auctions.access_type as auction_type,

            auction_items.security as item_security,
            auction_items.deposit as item_security_amount,
            auction_items.sold_status as item_sold_status,

        ';

        $where = [
            'sold_items.buyer_id' => $user_id,
        ];

        $this->db->select($select);
        $this->db->from('sold_items');
        $this->db->join('item', 'sold_items.item_id = item.id', 'LEFT');
        $this->db->join('auctions', 'sold_items.auction_id = auctions.id', 'LEFT');
        $this->db->join('auction_items', 'sold_items.auction_item_id = auction_items.id', 'LEFT');
        $this->db->where($where);
        $this->db->order_by('sold_items.created_on', 'DESC');
        $query = $this->db->get();
        $user_payments = $query->result_array();
        return $user_payments;
    }

    public function get_user_payables($user_id)
    {
        $select = '
            sold_items.id as sold_item_id,
            sold_items.item_id as item_id,
            sold_items.auction_id as auction_id,
            sold_items.auction_item_id as auction_item_id,
            sold_items.buyer_id as buyer_id,
            sold_items.seller_id as seller_id,
            sold_items.price as win_bid_price,
            sold_items.payable_amount as payable_amount,
            sold_items.payment_status as payment_status,
            sold_items.seller_payment_status as seller_payment_status,
            sold_items.created_by as created_by,
            sold_items.updated_by as updated_by,
            sold_items.created_on as created_on,
            sold_items.updated_on as updated_on,
            sold_items.sale_type as sale_type,

            item.name as item_name,
            item.registration_no as registration_no,

            auctions.id as auction_id,
            auctions.title as auction_title,
            auctions.access_type as auction_type,

            auction_items.security as item_security,
            auction_items.deposit as item_security_amount,
            auction_items.sold_status as item_sold_status,

        ';

        $where = [
            'sold_items.seller_id' => $user_id,
        ];

        $this->db->select($select);
        $this->db->from('sold_items');
        $this->db->join('item', 'sold_items.item_id = item.id', 'LEFT');
        $this->db->join('auctions', 'sold_items.auction_id = auctions.id', 'LEFT');
        $this->db->join('auction_items', 'sold_items.auction_item_id = auction_items.id', 'LEFT');
        $this->db->where($where);
        $this->db->order_by('sold_items.created_on', 'DESC');
        $query = $this->db->get();
        $user_payments = $query->result_array();
        return $user_payments;
    }

}//end model