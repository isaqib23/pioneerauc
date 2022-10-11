<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Items_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    // commented on 2nd sep 
    public function get_item_category_old()
    {
        $this->db->select('*');
        $this->db->from('item_category');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_item_category()
    {
        $this->db->select('*,count(item.category_id) as item_count,item_category.id as id');
        $this->db->from('item_category');
        $this->db->join('item','item_category.id = item.category_id','left');
        $this->db->group_by('item.category_id');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_category_list()
    {
        $this->db->select('*');
        $this->db->from('item_category');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_subcategory_list($category_id='')
    {
        $this->db->select('*');
        if($category_id != '')
        {
            $this->db->where('category_id', $category_id);
        }
        $this->db->from('item_subcategories');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_model_list($make_id='')
    {
        $this->db->select('*');
        if($make_id != '')
        {
            $this->db->where('make_id', $make_id);
        }
        $this->db->from('item_models');
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function check_registration_no($registration_no)
    {
        $this->db->where('registration_no',$registration_no);
        $query = $this->db->get('item');
        if ($query->num_rows() > 0){
            return true;
        }
        else
        {
            return false;
        }
    }
    public function get_item_model_list_active($make_id='')
    {
        $this->db->select('*');
        if($make_id != '')
        {
            $this->db->where('make_id', $make_id);
        }
            $this->db->where('status', 'active');
        $this->db->from('item_models');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_subcategory_row($subcategory_id='')
    {
        $this->db->select('*');
        if($subcategory_id != '')
        {
            $this->db->where('id', $subcategory_id);
        }
        $this->db->from('item_subcategories');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_model_row($model_id='')
    {
        $this->db->select('*');
        if($model_id != '')
        {
            $this->db->where('id', $model_id);
        }
        $this->db->from('item_models');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_items()
    {
        $this->db->select('*');
        $this->db->from('item');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function makes_list()
    {
        $this->db->select('*');
        $this->db->from('item_makes');
        $result = $this->db->get()->result_array();
        return $result;
    }

  	public function get_makes_list_active()
    {
        $this->db->select('*');
        $this->db->from('item_makes');
        $this->db->where('status', 'active');
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function get_max_id()
    {
         $query= $this->db->query('select max(id) from item');
        return $query->result_array();

    }

    public function get_item_list()
    {
        $this->db->select('item.*,item_category.title as category_name,item_makes.title as make_name,item_models.title as model_name,users.code as seller_code');
        $this->db->from('item');
        $this->db->join('item_category','item_category.id = item.category_id','left');
        $this->db->join('item_makes','item_makes.id = item.make','left');
        $this->db->join('item_models','item_models.id = item.model','left');
        $this->db->join('users','users.id = item.seller_id','left');
        $this->db->order_by('created_on', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_list_active()
    {
        $this->db->select('item.*,item_category.title as category_name');
        $this->db->from('item');
        $this->db->join('item_category','item_category.id = item.category_id','left');
        $this->db->order_by('created_on', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_active_item_list($where='', $notSold=false, $auction_id='')
    {
        $this->db->select('item.*,item_category.title as category_name,item_makes.title as make_name,item_models.title as model_name,users.code as seller_code');
        $this->db->from('item');
        $this->db->join('item_category','item_category.id = item.category_id','left');
        $this->db->join('users', 'item.seller_id = users.id', 'left');
        $this->db->join('item_makes','item_makes.id = item.make','left');
        $this->db->join('item_models','item_models.id = item.model','left');
        if (!empty($auction_id)) {
            $this->db->join('auction_items', 'auction_items.item_id = item.id', 'left');
        }
        if($where!='')
        {
            $this->db->where_in('item.id', $where);
        }
        if($notSold == true)
        {
            $this->db->where('item.sold', 'no');
        }
        if (!empty($auction_id)) {
            $this->db->where('auction_items.auction_id', $auction_id);
            $this->db->where('auction_items.sold_status', 'not');
        }
        $result = $this->db->get()->result_array(); 
        return $result;
    }



    // public function items_filter_list($where = '')
    // {
    //     $this->db->select('crm_detail.*,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
    //     $this->db->from('crm_detail');
    //     $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
    //     $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
    //     $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
    //     $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
    //     if ($where != '') {
    //         $this->db->where($where);
    //     }
    //     // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
    //     $this->db->order_by('crm_detail.id', 'desc');
    //     $query = $this->db->get();
    
    //     // echo $this->db->last_query();
    //     if ($query->num_rows() > 0) {
    //         return $query->result_array();
    //     }
    //     return array();
    // }

    public function bidding_rule_list($item_id)
    {
        $this->db->select('*');
        $this->db->from('item_bidding_setting');
        $this->db->where('item_id',$item_id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_by_categoryid($id)
  	{
		$this->db->select('*');
        $this->db->from('item');
		$this->db->where('category_id',$id);
		$result = $this->db->get()->result_array();
		return $result;
	}


  	public function get_item_category_active()
  	{
		$this->db->select('*');
		$this->db->from('item_category');
		$this->db->where('status', 'active');
		$result = $this->db->get()->result_array();
		return $result;
	}

    // Get DataTable data
   function getItems($postData=null){

     $response = array();

     ## Read value
     $draw = $postData['draw'];
     $start = $postData['start'];
     $rowperpage = $postData['length']; // Rows display per page
     $columnIndex = $postData['order'][0]['column']; // Column index
     $columnName = $postData['columns'][$columnIndex]['data']; // Column name
     $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
     $searchValue = $postData['search']['value']; // Search value

     // Custom search filter 
     $category_id = $postData['category_id'];
     $searchGender = $postData['searchGender'];
     $searchName = $postData['searchName'];

     ## Search 
     $search_arr = array();
     $searchQuery = "";
     if($searchValue != ''){
        $search_arr[] = " (name like '%".$searchValue."%' or 
         email like '%".$searchValue."%' or 
         city like'%".$searchValue."%' ) ";
     }
     if($category_id != ''){
        $search_arr[] = " category_id='".$category_id."' ";
     }
     if($searchGender != ''){
        $search_arr[] = " gender='".$searchGender."' ";
     }
     if($searchName != ''){
        $search_arr[] = " name like '%".$searchName."%' ";
     }
     if(count($search_arr) > 0){
        $searchQuery = implode(" and ",$search_arr);
     }

     ## Total number of records without filtering
     $this->db->select('count(*) as allcount');
     $records = $this->db->get('users')->result();
     $totalRecords = $records[0]->allcount;

     ## Total number of record with filtering
     $this->db->select('count(*) as allcount');
     if($searchQuery != '')
     $this->db->where($searchQuery);
     $records = $this->db->get('users')->result();
     $totalRecordwithFilter = $records[0]->allcount;

     ## Fetch records
     $this->db->select('*');
     if($searchQuery != '')
     $this->db->where($searchQuery);
     $this->db->order_by($columnName, $columnSortOrder);
     $this->db->limit($rowperpage, $start);
     $records = $this->db->get('users')->result();

     $data = array();

     foreach($records as $record ){
     
       $data[] = array( 
         "username"=>$record->username,
         "name"=>$record->name,
         "email"=>$record->email,
         "gender"=>$record->gender,
         "city"=>$record->city
       ); 
     }

     ## Response
     $response = array(
       "draw" => intval($draw),
       "iTotalRecords" => $totalRecords,
       "iTotalDisplayRecords" => $totalRecordwithFilter,
       "aaData" => $data
     ); 
     return $response; 
     // echo json_encode($response);
   }

   // Get cities array
   public function getCities(){

     ## Fetch records
     $this->db->distinct();
     $this->db->select('city');
     $this->db->order_by('city','asc');
     $records = $this->db->get('users')->result();

     $data = array();

     foreach($records as $record ){
        $data[] = $record->city;
     }

     return $data;
   }
    public function items_filter_list($where = '')
    {
        $this->db->select('item.*,users.fname,users.lname,item_category.title as category_name,item_makes.title as make_name,item_models.title as model_name,users.code as seller_code');
        $this->db->from('item');
        $this->db->join('users', 'item.seller_id = users.id', 'left');
        $this->db->join('item_category','item.category_id = item_category.id','left');
        $this->db->join('item_makes','item_makes.id = item.make','left');
        $this->db->join('item_models','item_models.id = item.model','left');
        if ($where != '') {
            $this->db->where($where);
        }
        $this->db->order_by('item.id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function auction_items_filter_list($item_ids_list = array(),$where = '')
    {
        $this->db->select('item.*,users.fname,users.lname,item_category.title as category_name,item_makes.title as make_name,item_models.title as model_name,users.code as seller_code');
        $this->db->from('item');
        $this->db->join('users', 'item.seller_id = users.id', 'left');
        $this->db->join('item_category','item.category_id = item_category.id','left');
        $this->db->join('item_makes','item_makes.id = item.make','left');
        $this->db->join('item_models','item_models.id = item.model','left');
        if ($where != '') {
            $this->db->where($where);
        }
        if(!empty($item_ids_list))
        {
        $this->db->where_in('item.id', $item_ids_list);
        }
        $this->db->order_by('item.id', 'desc');
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function items_filter_limit_list($where = '')
    {
        $this->db->select('item.*,users.fname,users.lname,item_category.title as category_name');
        $this->db->from('item');
        $this->db->join('users', 'item.seller_id = users.id', 'left');
        $this->db->join('item_category','item.category_id = item_category.id','left');
        if ($where != '') {
            $this->db->where($where);
        }
        $this->db->order_by('item.id', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function items_filter_limit_list_active($where = '')
    {
        $this->db->select('item.*,users.fname,users.lname,item_category.title as category_name');
        $this->db->from('item');
        $this->db->join('users', 'item.seller_id = users.id', 'left');
        $this->db->join('item_category','item.category_id = item_category.id','left');
        if (!empty($where)) {
            $this->db->where($where);
            // $this->db->where_in('item.id',$ids_array);
            // $this->db->where('item.in_auction', 'no');
            // $this->db->where('item.item_status', 'completed');
            // $this->db->where('item.status', 'active');
        }
        $this->db->order_by('item.id', 'desc');
        $this->db->limit(500);
		//$this->db->where("created_at >= DATE(NOW()) - INTERVAL 30 DAY");
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function active_items_filter_list($item_ids)
    {
        $this->db->select('item.*,users.fname,users.lname,item_category.title as category_name');
        $this->db->from('item');
        $this->db->join('users', 'item.seller_id = users.id', 'left');
        $this->db->join('item_category','item.category_id = item_category.id','left');
        $this->db->where_in('item.id', $item_ids);
        $this->db->order_by('item.id', 'desc');
        // $this->db->limit(10);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function items_filter_list_rules($where = '')
    {
        $this->db->select('item.*,item_category.title as category_name');
        $this->db->from('item');
        $this->db->join('item_category','item.category_id = item_category.id','left');
        if ($where != '') {
            $this->db->where($where);
        }
        $this->db->order_by('item.id', 'desc');
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_item_categorybyid($id)
    {
        $this->db->select('*');
        $this->db->from('item_category');
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_makebyid($id)
    {
        $this->db->select('*');
        $this->db->from('item_makes');
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_category_title_byid($id)
    {
        $this->db->select('title');
        $this->db->from('item_category');
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result[0];
    }

    public function getAll_item_documents_byid($id)
    {
        $this->db->select('*');
        $this->db->from('item_attachments');
        $this->db->where('item_id',$id);
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

    public function get_item_three_d_images($ids)
    {
        $this->db->select('*');
        $this->db->from('files');
        $this->db->where_in('id',$ids);
        $result = $this->db->get()->result_array();
        return $result;
    }

    // public function get_item_documents_byid_customer($ids)
    // {
    //     $this->db->select('*');
    //     $this->db->from('files');
    //     $this->db->where_in('id',$ids);
    //     $result = $this->db->get()->result_array();
    //     return $result;
    // }

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
    public function get_docsBy_id($ids)
    {
        $this->db->select('*');
        $this->db->from('files');
        // $this->db->where('id',$ids);
        $this->db->where_in('id',$ids);
        // $this->db->where('status', 'active');
        $result = $this->db->get()->result_array();
        // echo $this->db->last_query();
        return $result;
    }

    public function get_item_documents_title_byid($id)
    {
        $this->db->select('attach_name');
        $this->db->from('item_attachments');
        $this->db->where('item_id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function remove_item_document_row($item_id,$attach_name)
    {
        $this->db->select('*');
        $this->db->from('item_attachments');
        $this->db->where('item_id', $item_id);
        $this->db->where('attach_name', $attach_name);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('attach_name', $attach_name);
            $this->db->delete('item_attachments');
            return true;
        } else {
            return false;
        }
    }

    public function get_item_byid($id)
    {
        $this->db->select('*');
        $this->db->from('item');
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }

     public function get_customersDocs($id,$catid='')
    {

        $this->db->select('*');
        $this->db->from('user_documents');
        $this->db->where('user_id',$id);
        if ($catid !='') {
        $this->db->where('document_type_id',$catid);
        }
        // $this->db->where('document_type_id',$ids);
        $result = $this->db->get()->result_array();
        return $result;
    }

     public function get_customersDocs_new($id)
    {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id',$id);
        // $this->db->where('document_type_id',$ids);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function get_item_details_by_id($id)
    {
        $this->db->select('item.*,users.fname,users.lname,item_category.title as category_name,item_makes.title as make_name,item_models.title as model_name,users.code as seller_code');
        // ,auction_items.item_id,auction_items.lot_no,auction_items.order_lot_no
        $this->db->from('item');

        $this->db->join('users', 'item.seller_id = users.id', 'left');
        $this->db->join('item_category','item.category_id = item_category.id','left');
        $this->db->join('item_makes','item_makes.id = item.make','left');
        $this->db->join('item_models','item_models.id = item.model','left');
        // $this->db->join('auction_items',' auction_items.item_id = item.id', 'left');

        $this->db->where('item.id',$id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_item_bycode($unique_code)
    {
        $this->db->select('seller_id');
        $this->db->from('item');
        $this->db->where('unique_code',$unique_code);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_itemfields_byCategoryid($category_id)
    {
        $this->db->select('*');
        $this->db->from('item_fields_data');
        $this->db->where('category_id',$category_id);
        $result = $this->db->get()->result_array();
        return $result;
    }

  	public function get_itemfields_byItemid($item_id)
  	{
		$this->db->select('*');
		$this->db->from('item_fields_data');
		$this->db->where('item_id',$item_id);
		$result = $this->db->get()->result_array();
		return $result;
	}

    public function insert_category($data)
    {
        $this->db->insert('item_category', $data);
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

    public function insert_make($data)
    {
        $this->db->insert('item_makes', $data);
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

    public function insert_subcategory($data)
    {
        $this->db->insert('item_subcategories', $data);
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

    public function insert_model($data)
    {
        $this->db->insert('item_models', $data);
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

    public function insert_item($data)
    {
        $this->db->insert('item', $data);
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

    public function insert_bidding_rules($data)
    {
        $this->db->insert('item_bidding_setting', $data);
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

    public function insert_item_attachments($data)
    {
        $this->db->insert('item_attachments', $data);
        $inserted_id = $this->db->insert_id();
        if($inserted_id)
        {
            // echo $this->db->last_query();
            return $inserted_id;
        }
        else
        {
            return false;
        }
    }

	public function insert_item_fields_data($data)
	{
		$this->db->insert('item_fields_data', $data);
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

    public function update_category($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('item_category', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

    public function update_make($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('item_makes', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

    public function update_model($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('item_models', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

    public function update_subcategory($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('item_subcategories', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

    public function update_item($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('item', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

     public function update_customerDocs($id,$ids,$data)
    {
        $this->db->where('user_id', $id);
        $this->db->where('document_type_id', $ids);
        $query = $this->db->update('user_documents', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

    public function update_bidding_rules($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('item_bidding_setting', $data);
        if($query)
        {
        	return $query;
        }
        else
        {
        	return false;
        }
    }


    
    public function update_item_fields_data($item_id,$category_id,$field_id, $data)
    {
        $this->db->where('category_id', $category_id);
        $this->db->where('item_id', $item_id);
        $this->db->where('fields_id', $field_id);
        $query = $this->db->update('item_fields_data', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }



    public function check_items_field_data($item_id,$category_id,$field_id)
    {
        $this->db->select('*');
        $this->db->from('item_fields_data');
        $this->db->where('category_id', $category_id);
        $this->db->where('item_id', $item_id);
        $this->db->where('fields_id', $field_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function delete_item_category_row($id)
    {
        $this->db->select('*');
        $this->db->from('item_category');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('item_category');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_make_row($id)
    {
        $this->db->select('*');
        $this->db->from('item_makes');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('item_makes');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_model_row($id)
    {
        $this->db->select('*');
        $this->db->from('item_models');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('item_models');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_model_row_by_make($make_id)
    {
        $this->db->select('*');
        $this->db->from('item_models');
        $this->db->where('make_id', $make_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('make_id', $make_id);
            $this->db->delete('item_models');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_subcategory_row($id)
    {
        $this->db->select('*');
        $this->db->from('item_subcategories');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('item_subcategories');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_subcategory_row_by_category($id)
    {
        $this->db->select('*');
        $this->db->from('item_subcategories');
        $this->db->where('category_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('category_id', $id);
            $this->db->delete('item_subcategories');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_row($id)
    {
        $this->db->select('*');
        $this->db->from('item');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('item');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_category_MultipleRow($ids_array)
    {
        $this->db->select('*');
        $this->db->from('item_category');
        $this->db->where_in('id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('id', $ids_array);
            $this->db->delete('item_category');
            return true;
        } else {
            return false;
        }
    }
    
    public function delete_item_subcategory_by_category($ids_array)
    {
        $this->db->select('*');
        $this->db->from('item_subcategories');
        $this->db->where_in('category_id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('category_id', $ids_array);
            $this->db->delete('item_subcategories');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_fields_by_category($ids_array)
    {
        $this->db->select('*');
        $this->db->from('item_category_fields');
        $this->db->where_in('category_id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('category_id', $ids_array);
            $this->db->delete('item_category_fields');
            return true;
        } 
        else
        {
            return false;
        }
    }

    public function delete_item_subcategory_MultipleRow($ids_array)
    {
        $this->db->select('*');
        $this->db->from('item_subcategories');
        $this->db->where_in('id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('id', $ids_array);
            $this->db->delete('item_subcategories');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_makes_MultipleRow($ids_array)
    {
        $this->db->select('*');
        $this->db->from('item_makes');
        $this->db->where_in('id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            $this->db->where_in('id', $ids_array);
            $this->db->delete('item_makes');
            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function delete_item_model_MultipleRow($ids_array)
    {
        $this->db->select('*');
        $this->db->from('item_models');
        $this->db->where_in('id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            $this->db->where_in('id', $ids_array);
            $this->db->delete('item_models');
            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function fields_data($id) 
    {
        $this->db->select('*');
        $this->db->from('item_category_fields');
        $this->db->where('category_id',$id);
        $query=$this->db->get();
        return $query->result_array();
         
    }
    public function fields_data_by_id($id) 
    {
        $this->db->select('*');
        $this->db->from('item_category_fields');
        $this->db->where('id',$id);
        $query=$this->db->get();
        return $query->result_array();
         
    }

    public function fields_multiple_info($field_id) 
    {
        $this->db->select('id,type,multiple');
        $this->db->from('item_category_fields');
        $this->db->where('id',$field_id);
        $query=$this->db->get();
        return $query->result_array();
         
    }



    public function fields_ids_data($id) 
    {
        $this->db->select('id');
        $this->db->from('item_category_fields');
        $this->db->where('category_id',$id);
        $query=$this->db->get();
        return $query->result_array();
         
    }



    public function update_fields_data($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('item_category_fields', $data);
        if($query)
        {
            return $query;
        }
        else
        {
            return false;
        }
    }

    public function delete_item_category_fields_rows($ids_array)
    {
        $this->db->select('*');
        $this->db->from('item_category_fields');
        $this->db->where_in('id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('id', $ids_array);
            $this->db->delete('item_category_fields');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_fields_data_rows($ids_array)
    {
        $this->db->select('*');
        $this->db->from('item_fields_data');
        $this->db->where_in('fields_id', $ids_array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where_in('fields_id', $ids_array);
            $this->db->delete('item_fields_data');
            return true;
        } else {
            return false;
        }
    }

    public function delete_item_fields_data_by_item($item_id)
    {
        $this->db->select('*');
        $this->db->from('item_fields_data');
        $this->db->where('item_id', $item_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('item_id', $item_id);
            $this->db->delete('item_fields_data');
            return true;
        } else {
            return false;
        }
    }

     public function insert_transaction_data($data)
    {
        $this->db->insert('transaction',$data);
        $inserted_id = $this->db->insert_id();
        
        return $inserted_id;
    }

public function fields_data_new($id) 
  {
    $this->db->select('*');
    $this->db->from('item_category_fields');
    $this->db->where('category_id',$id);
    $query=$this->db->get();
    return $query->result_array();
       
  }

  public function get_itemfields_byItemid_new($item_id,$fields_id='')
    {
    $this->db->select('*');
    $this->db->from('item_fields_data');
    $this->db->where('item_id',$item_id);
    $this->db->where('fields_id',$fields_id);
    $this->db->order_by('id','asc');
    $result = $this->db->get()->row_array();
    return $result;
  }

}
