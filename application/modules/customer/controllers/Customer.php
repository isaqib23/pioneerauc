<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Customer_Controller {
    function __construct() {
        parent::__construct();              
        $this->load->model('Customer_model', 'customer_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('home/Home_model', 'home_model');
        $this->load->model('auction/Live_auction_model', 'lam');
        $this->load->model('users/Users_model', 'users_model');
        $this->load->model('footer_content/Terms_condition_model', 'terms_condition_model');
    }

    public function index()
    {
        $data =array();     
        $data['account_active'] = 'active';

        ///// End Auction list //////
        $this->template->load_user('dashboard', $data);
    }


    public function logout()
    { 
        $this->session->unset_userdata('logged_in', array());
        $this->session->set_flashdata('success', $this->lang->line('logged_out_successfully'));
        redirect(base_url().'home/login');

    }

    public function dashboard()
    {
        $data =array();
        $data['new'] = 'new';
        $id = $this->loginUser->id;
        $data['account_active'] = 'active';
        $user_total_deposit = $this->customer_model->user_balance($id);
        $data['percentage_settings']=$this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();
        $data['balance']=$user_total_deposit['amount'];

        $this->db->select('bid.*,item.id as item_id,item.name,item.price,item.sold,item.item_images');
        $this->db->from('bid');
        $this->db->join('item', 'item.id = bid.item_id', 'left');
        $this->db->where('bid.user_id', $id);
        $this->db->order_by('bid.id', 'ASC');
        $query = $this->db->get();
        $bids = $query->result_array();

        foreach($bids as $key => $record ){
            $images_ids = explode(",",$record['item_images']);
            $item_id = $record['item_id'];
            $last_bid = $this->db->order_by('bid_time', 'DESC')->get_where('bid', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);   
            $auction_item = $this->db->select('bid_end_time, sold_status')->get_where('auction_items', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
            $href = 'javascript:void(0)';
            $status = $this->lang->line('not_available');
            if (!empty($auction_item)) {
                $href = ((strtotime($auction_item['bid_end_time']) > strtotime(date('Y-m-d H:i:s'))) && ($auction_item['sold_status'] == 'not')) ? base_url('auction/online-auction/details/').$last_bid['auction_id'].'/'.$last_bid['item_id'] : "javascript:void(0)";
                $status = ((strtotime($auction_item['bid_end_time']) > strtotime(date('Y-m-d H:i:s'))) && $auction_item['sold_status'] == 'not') ? $this->lang->line('available') : $this->lang->line('not_available');
            }

            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                $style = '';
                if ($href =="javascript:void(0)" ) {
                    $style = 'cursor: auto';
                }
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                $image = '<a class="text-success" style="'.$style.'"  onclick="itemExpire(this)" href="'.$href.'"><img src="'.$base_url_img.'" alt="Visa"></a>';
            }else{
                $base_url_img =base_url('assets_admin/images/product-default.jpg');
                $image = '<a class="text-success" href="'.$href.'"><img src="'.$base_url_img.'" alt="Visa"></a>';
            }
            $bids[$key]['image'] = $image;
            $bids[$key]['last_bid'] = $last_bid['bid_amount'];
            $bids[$key]['status_value'] = $status;
        }
        $data['bids_data'] = $bids;

        ///// End Auction list //////
        $data['bids']=$this->customer_model->user_bids($id);

        $this->template->load_user('new/my-bids', $data);
        // $status = $this->db->get_where('users',['id' => $id],['status'=>1])->row_array();
        // if ($status) {
        //     if( $htis->session->loginUser('id')){
        //     }

        // }else
        // {
        //     $session->stop();
        //     redirect(base_url().'home/login');
        // }
    }

    public function faq()
    {
        $data =array();
        $id = $this->loginUser->id;
        $data['faq_active'] = 'active';
        $data['list'] = $this->db->get('ques_ans')->result_array();
        $this->template->load_user('faq', $data);
    }


    public function profile()
    {
        // $this->output->enable_profiler(TRUE);
        $data =array();
        $data['new'] = 'new';
        $id = $this->loginUser->id;
        $data['user_id'] = $this->loginUser->id;
        $data['profile_active'] = 'active';
        $data['categoryId'] = 1;
        // $data['list']=$this->customer_model->get_user_list($id);
        $data['list']=$this->db->get_where('users',['id' => $id])->row_array();



        //documents
        $data['docs'] = $this->db->select('user_documents.*, documents_type.name as document_type, documents_type.id as document_type_id')->from('user_documents')
            ->join('documents_type', 'user_documents.document_type_id = documents_type.id')
            ->where(['user_id' => $id])
            ->order_by('documents_type.id', 'ASC')
            ->get()->result_array();
            // print_r($data['docs']);die();

        ///// End Auction list //////
        $this->template->load_user('new/profile', $data);
        //$this->output->enable_profiler(FALSE);
    }

    public function update_profile()
    {
        
        $id = $this->loginUser->id;    
        $data_session = json_decode(json_encode($this->session->userdata('logged_in')), true);
        $this->session->unset_userdata('logged_in', array());
        $data_session['fname'] = $this->input->post('fname');
        $data_session = (object) $data_session;
        $this->session->set_userdata('logged_in', $data_session);

        $data = array(
            // 'id' => $id,
            'fname' => $this->input->post('fname'),
            'lname' =>$this->input->post('lname'),
            'lname' =>$this->input->post('lname'),
            'city'=>$this->input->post('city'),
            'state'=>$this->input->post('state'),
            'country'=>$this->input->post('country'),
            'job_title'=>$this->input->post('job_title'),
            'id_number'=>$this->input->post('id_number'),
            'address' => $this->input->post('address'),  
            'po_box' => $this->input->post('po_box'),   
            'company_name' => $this->input->post('company_name'),
            'vat_number' => $this->input->post('vat_number'),  
            'remarks' => $this->input->post('remarks'), 
            'description'=>$this->input->post('description'),
            'vat'=> $this->input->post('vat')                 
        ); 
        if (!empty($data)) {
            $result= $this->customer_model->update_profile($id, $data);
            echo json_encode(array("status" => TRUE,'error'=>false,"msg" => $this->lang->line('profile_updated_successfully')));
            exit();
        }  

           // else
           // {
           //   echo json_encode(array('status'=>false,'message'=>'No change found'));
           //   exit();
           // }

    }


    public function save_profile_image()
    {
        $id = $this->loginUser->id;
        // print_r($_FILES);die('ll');
        // print_r($id);die();
                // $data['all_users'] = $this->users_model->get_user_by_id($id);
        if(isset($_FILES['profile_picture']['name']) && !empty($_FILES['profile_picture']['name'])){
                    $data_for_delete_files = $this->customer_model->users_listing_profile($id); // fetch data 
                    $get_file_name = $this->db->get_where('files',['id' => $data_for_delete_files[0]['picture']])->row_array();
                    $old_data = '';
                    if(!empty($data_for_delete_files))
                    {
                        $old_data = FCPATH .'uploads/profile_picture/'.$id.'/'.$get_file_name['name'];
                        $picture = $data_for_delete_files[0]['picture'];
                    }
                    
                    $path = "uploads/profile_picture/".$id."/";
                    // make path
                    if ( !is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                      // If profile picture is selected
                    // $files = '';                                 
                    
                    if(!empty($_FILES))
                    {
                        // $files .= $this->uploadFiles_with_path($_FILES,$path);
                        $files = $this->files_model->upload('profile_picture', $config);
                        $profile_pic_array['picture'] = implode(',', $files);
                        $user_array =  $this->customer_model->update_user($id,$profile_pic_array);
                        
                        //change session
                        $data_session = json_decode(json_encode($this->session->userdata('logged_in')), true);
                        $this->session->unset_userdata('logged_in', array());
                        $data_session['picture'] = $profile_pic_array['picture'];
                        $data_session = (object) $data_session;
                        $this->session->set_userdata('logged_in', $data_session);
                        //delete existing file
                        if(is_dir($old_data) && !empty($data_for_delete_files[0]['picture'])){
                            unlink($old_data); 
                        } 
                        if($user_array)
                        {
                            echo json_encode(array("status" => true, "message" => $this->lang->line('image_uploaded_successfully')));
                        }

                    }
                    else
                    {
                         echo json_encode(array("status" => false, "message" => $this->lang->line('please_select_image')));
                    }

                }
                else
                {
                 echo json_encode(array("status" => false, "message" => $this->lang->line('please_select_image')));
             }  
         }

    public function bid()
    {
        $data =array();
        $userid = $this->loginUser->id;
        $data['bid_active'] = 'active';
        $data['category'] = $this->db->get('item_category')->result_array();
        $this->db->select('bid.*,item.id as item_id,item.name,item.price,item.sold,item.item_images');
        $this->db->from('bid');
        $this->db->join('item', 'item.id = bid.item_id', 'left');
        $this->db->where('bid.user_id', $userid);
        $query = $this->db->get();
        $bids = $query->result_array();

        foreach($bids as $key => $record ){
            $bids[$key]['status_value'] = $this->lang->line('available');
            $images_ids = explode(",",$record['item_images']);
            $item_id = $record['item_id'];
            $last_bid = $this->db->order_by('bid_time', 'DESC')->get_where('bid', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);   
            $auction_item = $this->db->select('bid_end_time')->get_where('auction_items', ['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
            $href = 'javascript:void(0)';
            if ($auction_item) {
                $href = (strtotime($auction_item['bid_end_time']) > strtotime(date('Y-m-d H:i:s'))) ? base_url('auction/online-auction/details/').$last_bid['auction_id'].'/'.$last_bid['item_id'] : "javascript:void(0)";
            }

            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                $image = '<a class="text-success" onclick="itemExpire(this)" href="'.$href.'"><img src="'.$base_url_img.'" alt="Visa"></a>';
            }else{
                $base_url_img =base_url('assets_admin/images/product-default.jpg');
                $image = '<a class="text-success" href="'.$href.'"><img src="'.$base_url_img.'" alt="Visa"></a>';
            }
            $bids[$key]['image'] = $image;
            if($record['sold'] == 'yes'){
                $bids[$key]['status_value'] = $this->lang->line('sold');
            }
        }
        $data['bids'] = $bids;
        print_r($data['bids']);die();
        ///// End Auction list //////
        $this->template->load_user('bids', $data);
    }
    
    public function get_bids()
    {

        $posted_data = $this->input->post();
        $userid = $this->loginUser->id;
        ## Read value
        $draw = $posted_data['draw'];

        // print_r($draw);die();
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $search_arr[] = " item.name like '%".$searchValue."%'  ";
        }
        /////custom search/////////////
        $category_id = (isset($posted_data['bids_cat'])) ? $posted_data['bids_cat'] : '';
        $fav_id = (isset($posted_data['bids'])) ? $posted_data['bids'] : '';
        if($category_id != ''){
            $search_arr[] = " item.category_id='".$category_id."' ";
        }
        if($fav_id != ''){
            $search_arr[] = " item.name like '%".$fav_id."%'  ";

        }

        if(count($search_arr) > 0){
            $searchQuery = " (".implode(" AND ",$search_arr).") ";
        }
            ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('bid.user_id', $userid);
        $records = $this->db->get('bid')->result();
        $totalRecords = $records[0]->allcount;
        // print_r($totalRecords);die();
         ## Total number of record with filtering
        
        
        
        $this->db->select('bid.*,item.id as item_id,item.name,item.price,item.sold,item.item_images');
        $this->db->from('bid');
        $this->db->join('item', 'item.id = bid.item_id', 'left');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('bid.user_id', $userid);
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;

        // ## Fetch records
        // $this->db->select('favorites_items.*');
        // $this->db->from('favorites_items');
        $this->db->select('bid.*,item.id as item_id,item.name,item.price,item.sold,item.item_images');
        $this->db->from('bid');
        $this->db->join('item', 'item.id = bid.item_id', 'left');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('bid.user_id', $userid);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();
        // print_r($this->db->last_query());die();
        $data = array();
        // $have_documents = false;
        $status_value = $this->lang->line('available');

        foreach($records as $record ){
            $images_ids = explode(",",$record->item_images);
            $item_id = $record->item_id;
            $last_bid = $this->db->order_by('bid_time', 'DESC')->get_where('bid', ['item_id' => $record->item_id, 'auction_id' => $record->auction_id])->row_array();
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);   
            $auction_item = $this->db->select('bid_end_time')->get_where('auction_items', ['item_id' => $record->item_id, 'auction_id' => $record->auction_id])->row_array();
            $href = '#';
            if ($auction_item) {
                $href = (strtotime($auction_item['bid_end_time']) > strtotime(date('Y-m-d H:i:s'))) ? base_url('auction/online-auction/details/').$last_bid['auction_id'].'/'.$last_bid['item_id'] : "#";
            }

            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                $image = '<a class="text-success" onclick="itemExpire(this)" href="'.$href.'"><img src="'.$base_url_img.'" alt="Visa"></a>';
            }else{
                $base_url_img =base_url('assets_admin/images/product-default.jpg');
                $image = '<a class="text-success" href="'.$href.'"><img src="'.$base_url_img.'" alt="Visa"></a>';
            }
            if($record->sold == 'yes'){
                $status_value = $this->lang->line('sold');
            }

            $bid_date = date('d/m/Y', strtotime($record->bid_time));
            $bid_time = date('h:i A', strtotime($record->bid_time));
            $bid_date_time ='<p class="date-time"><span>'.$bid_date.'</span> '.$bid_time.'</p>';
            $item_name = json_decode($record->name);
            $language = $this->language;
            $name = '<div class="item">
                        <div class="image">
                            '.$image.'
                        </div>
                        <div class="desc">
                            <h6>'.@$item_name->$language.'</h6>
                        </div>
                    </div>';
           
            $data[] = array( 
                "bid_time" => $bid_date_time,
                "image" => $image,
                "name" => @$name,
                "bid_amount" => $record->bid_amount,
                "last_bid" => $last_bid['bid_amount'],
                // "type" => $query->title,
                // "type" => '',
                "status" => $status_value
            ); 
        }
            ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);    
            /////////

    }

    public function inventory()
    {
        $data =array();
        $userid = $this->loginUser->id;
        $data['new'] = 'new';
        $data['inventory_active'] = 'active';
        $this->db->select('item.*');
        $this->db->from('item');
        $this->db->where('seller_id', $userid);
        $query = $this->db->get();
        $data['inventory'] = $query->result_array();
        foreach($data['inventory'] as $key => $record ){
            $status_value = $this->lang->line('available');
            $images_ids = explode(",",$record['item_images']);
            $item_id = $record['id'];
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);   
                // print_r($files_array);die();
            $language = $this->language;
            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                $image = '<a class="text-success" href=""><img style="max-width: 50px" class="img-responsive avatar-view" src="'.$base_url_img.'" alt="Visa"></a>';
            }else{
                $base_url_img =base_url('assets_admin/images/product-default.jpg');
                $image = '<a class="text-success" href=""><img style="max-width: 50px" class="img-responsive avatar-view" src="'.$base_url_img.'" alt="Visa"></a>';
            }
            if ($record['in_auction'] == 'no') {
                $status_value = $this->lang->line('under_process');
            }
            if($record['sold'] == 'return'){
                $status_value = $this->lang->line('returned');
            }
            if($record['sold'] == 'yes'){
                $status_value = $this->lang->line('sold');
                $sold_item = $this->db->get_where('sold_items', ['item_id' => $record['id']])->row_array();
                $invoice = $this->db->get_where('invoices', ['type' => 'seller','sold_item_id' => $sold_item['id']])->row_array();
                if ($invoice) {
                    $action = '<button data-item-id="'.$record['id'].'" data-sold-item-id="'.$sold_item['id'].'" onclick="show_details(this)" class="btn btn-primary sm charges">'.$this->lang->line('expenses').'</button>';
                } else {
                    $action = '<span> '.$this->lang->line('waiting_for_system_calculations').'</span>';
                }
            } else {
                $action = '<i class="fa fa-ellipsis-h"></i>';
            }

            $data['inventory'][$key]['image'] = $image;
            $data['inventory'][$key]['current_status'] = $status_value;
            $data['inventory'][$key]['action'] = $action;
        }

        ///// End Auction list //////
        $this->template->load_user('new/inventory', $data);
    }

    public function get_inventory()
    {
        $posted_data = $this->input->post();
        $userid = $this->loginUser->id;
        ## Read value
        $draw = $posted_data['draw'];

        // print_r($draw);die();
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $search_arr[] = " item.name like '%".$searchValue."%'  ";
        }
        /////custom search/////////////
        $category_id = (isset($posted_data['inven_cat'])) ? $posted_data['inven_cat'] : '';
        $fav_id = (isset($posted_data['invn_search'])) ? $posted_data['invn_search'] : '';
        if($category_id != ''){
            $search_arr[] = " item.category_id='".$category_id."' ";
        }
        if($fav_id != ''){
            $search_arr[] = " item.name like '%".$fav_id."%'  ";
        }

        if(count($search_arr) > 0){
            $searchQuery = " (".implode(" AND ",$search_arr).") ";
        }
            ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('item');
            // $this->db->join('live_auction_inventory', 'live_auction_inventory.user_id = item.seller_id', 'left');
            // $this->db->where('live_auction_inventory.user_id', $userid);
        $this->db->where('seller_id', $userid);
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;
            // print_r($totalRecords);die();
             ## Total number of record with filtering

        $this->db->select('item.*');
        $this->db->from('item');
            // $this->db->join('live_auction_inventory', 'live_auction_inventory.user_id = item.seller_id', 'left');
        if($searchQuery != '')
            $this->db->where($searchQuery);
            // $this->db->where('live_auction_inventory.user_id', $userid);
        $this->db->where('seller_id', $userid);
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;

            // ## Fetch records
            // $this->db->select('live_auction_inventory.*');
            // $this->db->from('live_auction_inventory');
        $this->db->select('item.*');
        $this->db->from('item');
            // $this->db->join('live_auction_inventory', 'live_auction_inventory.user_id = item.seller_id', 'left');
        if($searchQuery != '')
            $this->db->where($searchQuery);
            // $this->db->where('live_auction_inventory.user_id', $userid);
        $this->db->where('seller_id', $userid);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();
        $data = array();
            // $have_documents = false;

        foreach($records as $record ){
            $status_value = $this->lang->line('available');
            $images_ids = explode(",",$record->item_images);
            $item_id = $record->id;
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);   
                // print_r($files_array);die();
            $item_name = json_decode($record->name);
            $language = $this->language;
            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                $image = '<a class="text-success" href=""><img style="max-width: 50px" class="img-responsive avatar-view" src="'.$base_url_img.'" alt="Visa"></a>';
            }else{
                $base_url_img =base_url('assets_admin/images/product-default.jpg');
                $image = '<a class="text-success" href=""><img style="max-width: 50px" class="img-responsive avatar-view" src="'.$base_url_img.'" alt="Visa"></a>';
            }
            if($record->sold == 'yes'){
                $status_value = $this->lang->line('sold');
                $sold_item = $this->db->get_where('sold_items', ['item_id' => $record->id])->row_array();
                $invoice = $this->db->get_where('invoices', ['sold_item_id' => $sold_item['id']])->row_array();
                if ($invoice) {
                    $action = '<button data-item-id="'.$record->id.'" data-sold-item-id="'.$sold_item['id'].'" onclick="show_details(this)" class="btn btn-primary sm charges">'.$this->lang->line('expenses').'</button>';
                } else {
                    $action = '<span> '.$this->lang->line('waiting_for_system_calculations').'</span>';
                }
            } else {
                $action = '<i class="fa fa-ellipsis-h"></i>';
            }

            $data[] = array( 
                "id" => $record->id,
                "image" => $image,
                "name" => $item_name->$language,
                "price" => $record->price,
                "status" => $status_value,
                "action"=> $action
            ); 
        }
                ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);    
    }

    // public function seller_invoice($item_id)
    // {
    //     echo $sold_item_id;
    //     die();
    //     $data = array();
    //     $data['small_title'] = 'Seller Invoice';
    //     if($sold_item_id){
    //         $sold_item = $this->db->get_where('sold_items', ['item_id' => $item_id])->row_array();
    //         $item = $this->db->get_where('item', ['id' => $sold_item['item_id']])->row_array();
    //         $seller = $this->db->get_where('users', ['id' => $sold_item['seller_id']])->row_array();
    //         $auction = $this->db->get_where('auctions', ['id' => $sold_item['auction_id']])->row_array();
    //         $auction_item = $this->db->get_where('auction_items', ['id' => $sold_item['auction_item_id']])->row_array();
    //         $bank_list = array();
    //         $bank_info = $this->db->get('bank_info')->result_array();
    //         $receipt = date('yM', strtotime($sold_item['updated_on']));
    //         $receipt = $receipt.$sold_item['id'].'-02';

    //         $amount_in_words = '';
    //         $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    //         $amount_in_words = $spellout->format($sold_item['payable_amount']);

    //         $this->template->load_admin('users/receipt', [
    //             'current_page_customers' => 'current-page',
    //             'sold_item' => $sold_item,
    //             'data' => $data,
    //             'item' => $item,
    //             'buyer' => $buyer,
    //             'auction' => $auction,
    //             'auction_item' => $auction_item,
    //             'receipt' => $receipt,
    //             'amount_in_words' => $amount_in_words,
    //             'bank_info' => $bank_info,
    //             'bank_list' => $bank_list
    //         ]);
    //     }else{
    //         show_404();
    //     }
    // }

public function get_seller_charges()
{
    $item_id = $this->input->post('item_id');
    $sold_item_id = $this->input->post('sold_item_id');
    $data =array();
    $data['new'] = 'new';
    $data['balance_active'] = 'active';
    $data['item_data']=$this->db->get_where('item', ['id' => $item_id])->row_array();
    $data['invoices']=$this->db->get_where('invoices', ['type' => 'seller','sold_item_id' => $sold_item_id])->row_array();
    $data_view = $this->load->view('customer/seller_charges', $data, true);
    // print_r($data_view);die();
    // print_r($data_view);die('aaaass');
    $response = array('msg' => 'success','data' => $data_view);
    echo json_encode($response);
}

public function balance()
{
    $id = $this->loginUser->id;
    $data =array();
    $data['new'] = 'new';
    $data['balance_active'] = 'active';
    $data['balance']=$this->customer_model->user_balance($id);
    $data['percentage_settings']=$this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();

        ///// End Auction list //////
    $this->template->load_user('balance-page', $data);
}

public function deposits()
{
    $id = $this->loginUser->id;
    $data =array();
    $data['new'] = 'new';
    $data['deposits_active'] = 'active';

        ///// End Auction list //////
        // $data['balance']=$this->customer_model->user_balance($id);
    $this->template->load_user('user-deposits', $data);
}

public function get_deposits()
{

    $posted_data = $this->input->post();
    $userid = $this->loginUser->id;
        ## Read value
    $draw = $posted_data['draw'];

        // print_r($draw);die();
    $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $search_arr[] = " item.name like '%".$searchValue."%'  ";
        }
        /////custom search/////////////
        $search = (isset($posted_data['searchval'])) ? $posted_data['searchval'] : '';
        if($search != ''){
            $search_arr[] = " transaction_id like '%".$search."%' OR payment_type like '%".$search."%' OR amount like '%".$search."%' OR account like '%".$search."%' OR status like '%".$search."%'  OR created_on like '%".$search."%' ";

        }

        if(count($search_arr) > 0){
            $searchQuery = " (".implode(" AND ",$search_arr).") ";
        }
            ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('auction_deposit.user_id', $userid);
        $this->db->where('auction_deposit.status !=', 'active');
        $records = $this->db->get('auction_deposit')->result();
        $totalRecords = $records[0]->allcount;
        // print_r($totalRecords);die();
         ## Total number of record with filtering
        
        
        
        $this->db->select('auction_deposit.*');
        $this->db->from('auction_deposit');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('auction_deposit.user_id', $userid);
        $this->db->where('auction_deposit.status !=', 'active');
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;

        // ## Fetch records
        // $this->db->select('favorites_items.*');
        // $this->db->from('favorites_items');
        $this->db->select('auction_deposit.*');
        $this->db->from('auction_deposit');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('auction_deposit.user_id', $userid);
        $this->db->where('auction_deposit.status !=', 'active');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();
        
        // print_r($posted_data['length']);die();
        $data = array();
        // $have_documents = false;
        $status_value = 'available';
        $j = '';
        foreach($records as $record){
            $j++;
            $action = '';
            $action = '<i class="fa fa-ellipsis-h"></i>';
            $status = $record->status;
            // print_r($record);die();
            $payment_type = $record->payment_type;

            $data[] = array( 
                "id" => $j,
                // "transaction_id" => $record->transaction_id,
                // "payment_type" => ucwords($payment_type),
                "payment_type" => $this->lang->line($payment_type),
                // "type" => $query->title,
                // "type" => '',
                "amount" => $record->amount,
                "account" => $record->account,
                "created_on"=> date('Y-m-d',strtotime($record->created_on)),
                "status" => $this->lang->line($status),
            ); 
        }
            ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);    
            /////////

    }


    public function security()
    {
        $id = $this->loginUser->id;
        $data =array();
        $data['new'] = 'new';
        $data['security_active'] = 'active';

        ///// End Auction list //////
        // $data['balance']=$this->customer_model->user_balance($id);
        $this->template->load_user('security-new', $data);
    }

    public function get_security()
    {

        $posted_data = $this->input->post();
        $userid = $this->loginUser->id;
        ## Read value
        $draw = $posted_data['draw'];

        // print_r($draw);die();
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $search_arr[] = " item.name like '%".$searchValue."%'  ";
        }
        /////custom search/////////////
        $search = (isset($posted_data['searchval'])) ? $posted_data['searchval'] : '';
        if($search != ''){
            $search_arr[] = " transaction_id like '%".$search."%' OR payment_type like '%".$search."%' OR amount like '%".$search."%' ";

        }

        if(count($search_arr) > 0){
            $searchQuery = " (".implode(" AND ",$search_arr).") ";
        }
            ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('bank_deposit_slip.user_id', $userid);
        $this->db->where('bank_deposit_slip.status !=', '1');
        $records = $this->db->get('bank_deposit_slip')->result();
        $totalslipRecords = $records[0]->allcount;

        $this->db->select('count(*) as allcount');
        $this->db->where('auction_item_deposits.user_id', $userid);
        $this->db->where('auction_item_deposits.status !=', 'active');
        $records = $this->db->get('auction_item_deposits')->result();
        $totalRecords = $records[0]->allcount + $totalslipRecords;
        // print_r($totalRecords);die();
         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('bank_deposit_slip.user_id', $userid);
        $this->db->where('bank_deposit_slip.status !=', '1');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $this->db->get('bank_deposit_slip')->result();
        $totalslipRecords = $records[0]->allcount;
        
        $this->db->select('auction_item_deposits.*');
        $this->db->from('auction_item_deposits');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('auction_item_deposits.user_id', $userid);
        $this->db->where('auction_item_deposits.status !=', 'active');
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) + $totalslipRecords : 0;

        // ## Fetch records
        // $this->db->select('favorites_items.*');
        // $this->db->from('favorites_items');

        $this->db->select('bank_deposit_slip.*');
        $this->db->where('bank_deposit_slip.user_id', $userid);
        $this->db->where('bank_deposit_slip.status !=', '1');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $sliprecords = $this->db->get('bank_deposit_slip')->result();

        $this->db->select('auction_item_deposits.*');
        $this->db->from('auction_item_deposits');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where('auction_item_deposits.user_id', $userid);
        $this->db->where('auction_item_deposits.status !=', 'active');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();
        
        // print_r($posted_data['length']);die();
        $data = array();
        // $have_documents = false;
        $status_value = $this->lang->line('available');
        $j = 0;
        foreach($sliprecords as $record){
            $j++;
            $action = '<i class="fa fa-ellipsis-h"></i>';
            // print_r($record);die();

            switch ($record->status){
                case '0':
                $status = $this->lang->line('pending');
                break;
                case '2':
                $status = $this->lang->line('rejected');
                break;

                default:
                $status = $this->lang->line('not_available');
                break;
            }
            $data[] = array( 
                "id" => $j,
                // "transaction_id" => $record->transaction_id,
                "deposit_mode" => $this->lang->line('bank_transfer'),
                // "type" => $query->title,
                // "type" => '',
                "deposit" => $record->deposit_amount,
                "created_on"=> date('Y-m-d',strtotime($record->created_on)),
                "status" => $status,
            ); 
        }
        foreach($records as $record){
            $j++;
            $action = '';
            $action = '<i class="fa fa-ellipsis-h"></i>';
            $status_deposit = $record->status;
            $deposit = $record->deposit_mode;
            // print_r($record);die();
            $data[] = array( 
                "id" => $j,
                // "transaction_id" => $record->transaction_id,
                "deposit_mode" => $this->lang->line($deposit),
                // "type" => $query->title,
                // "type" => '',
                "deposit" => $record->deposit,
                "created_on"=> date('Y-m-d',strtotime($record->created_on)),
                "status" => $this->lang->line($status_deposit),
            ); 
        }
            ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);    
            /////////

    }

    public function payments()
    {
        $user_id = $this->loginUser->id;
        $data =array();
        $data['new'] = 'new';
        $data['payments_active'] = 'active';
        $data['won_items'] = $this->users_model->get_user_payments($user_id);
        
        $status_value = 'available';
        foreach($data['won_items'] as $key => $record){
            $action = '';
            $btn = $this->lang->line('make_payment');
            if ($record['payment_status'] == '0') {
                $action = '<a href="'.base_url().'customer/payment_cradit_card/'.$record['payable_amount'].'/'.$record['sold_item_id'].'" class="btn btn-primary sm">'.$btn.'</a>';
            } else {
                $action = 'Paid';

            }
            $data['won_items'][$key]['action'] = $action;
        }

        ///// End Auction list //////
        $this->template->load_user('new/payable', $data);
    }

    public function get_payments()
    {

        $posted_data = $this->input->post();
        $userid = $this->loginUser->id;
        ## Read value
        $draw = $posted_data['draw'];

        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $search_arr[] = " item.name like '%".$searchValue."%'  ";
        }
        /////custom search/////////////
        $search = (isset($posted_data['searchval'])) ? $posted_data['searchval'] : '';
        if($search != ''){
            $search_arr[] = " transaction_id like '%".$search."%' OR payment_type like '%".$search."%' OR amount like '%".$search."%' ";

        }
        $user_id = $this->loginUser->id;

        if(count($search_arr) > 0){
            $searchQuery = " (".implode(" AND ",$search_arr).") ";
        }
        ## Total number of records without filtering
        $sold_items = $this->users_model->get_user_payments($user_id);
        // print_r($sold_items);die();
        $totalRecords = (isset($sold_items) && !empty($sold_items)) ? count($sold_items) : 0;
         ## Total number of record with filtering
        
        
        
        $records = $this->users_model->get_user_payments($user_id);
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;

        // ## Fetch records
        $records = $this->users_model->get_user_payments($user_id);
        
        $data = array();
        $status_value = 'available';
        $j = '';
        foreach($records as $record){
            $j++;
            $action = '';
            $btn = $this->lang->line('make_payment');
            if ($record['payment_status'] == '0') {
                $action = '<a href="'.base_url().'customer/payment_cradit_card/'.$record['payable_amount'].'/'.$record['sold_item_id'].'" class="btn btn-primary sm">'.$btn.'</a>';
            } else {
                $action = 'Paid';

            }
            $item_security = (!empty($record['adjusted_security'])) ? $record['adjusted_security'] : '0';
            $deposit = (!empty($record['adjusted_deposit'])) ? $record['adjusted_deposit'] : '0';
            $due_payment = $item_security + $deposit;
            // print_r($record);die();
            $lan = $this->language;  
            $item_name = json_decode($record['item_name']);
            $data[] = array(
                "item_name" => $item_name->$lan,
                "win_date" => date('Y-m-d',strtotime($record['created_on'])),
                "win_bid_price" => $record['win_bid_price'],
                "due_payment" => $due_payment,
                "payable_amount" => $record['payable_amount'],
                "adjustment" => 'Security: '.$record['adjusted_security'].'<br>Deposit: '.$record['adjusted_deposit'],
                "action" => $action
            );
        }
            ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);   
    }

    public function favorite()
    {
        $userid = $this->loginUser->id;
        $data =array();
        $data['favorite_active'] = 'active';
        $data['category'] = $this->db->get('item_category')->result_array();
        $this->db->select('favorites_items.*,item.id as item_id,item.name,item.detail,item.price,item.sold,item.item_images');
        $this->db->from('favorites_items');
        $this->db->join('item', 'item.id = favorites_items.item_id', 'left');
        $this->db->where('favorites_items.user_id', $userid);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $data['favoriteItems'] = $query->result_array();
        foreach($data['favoriteItems'] as $key => $record ){
            $status_value = $this->lang->line('not_available');
            $images_ids = explode(",",$record['item_images']);
            $item_id = $record['item_id'];
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);   

            $language = $this->language;
            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                $image = '<div class="text-success" href=""><img class="img-responsive avatar-view" src="'.$base_url_img.'" alt="'.@$item_name->$language.'"></div>';
            }else{
               $base_url_img =base_url('assets_admin/images/product-default.jpg');
               $image = '<div class="text-success" href=""><img class="img-responsive avatar-view" src="'.$base_url_img.'" alt="'.@$item_name->$language.'"></div>';
            }
            $data['favoriteItems'][$key]['image'] = $image;
            if($record['sold'] == 'no'){
                $check_expired = $this->db->get_where('auction_items',['item_id' => $record['item_id'], 'auction_id' => $record['auction_id']])->row_array();
                $item_expiry_date = @$check_expired['bid_end_time'];
                $action = '';
                if (($item_expiry_date > date('Y-m-d H:i:s')) && ($check_expired['sold_status'] == 'not')) {
                    $status_value = $this->lang->line('available');
                    $auction_id = $record['auction_id'];
                    $action .= '<a class="btn btn-primary sm" href="'.base_url("auction/online-auction/details/$auction_id/$item_id").'">'.$this->lang->line('bid_now').'</a>';
                }
                $data['favoriteItems'][$key]['auction_item_status'] = $status_value;
                $data['favoriteItems'][$key]['action'] = $action;
            }
        }
        ///// End Auction list //////
        $this->template->load_user('new/wishlist', $data);
    }

    
    public function getFavorite()
    {
        $posted_data = $this->input->post();
        $userid = $this->loginUser->id;
        ## Read value
        $draw = $posted_data['draw'];

        // print_r($draw);die();
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page         
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $search_arr[] = " item.name like '%".$searchValue."%'  ";
        }
        /////custom search/////////////
        $category_id = (isset($posted_data['item_cat'])) ? $posted_data['item_cat'] : '';
        $fav_id = (isset($posted_data['search_fav'])) ? $posted_data['search_fav'] : '';
        if($category_id != ''){
            $search_arr[] = " item.category_id='".$category_id."' ";
        }
        if($fav_id != ''){
            $search_arr[] = " item.name like '%".$fav_id."%' OR item.name like '%".$fav_id."%' ";

        }

        if(count($search_arr) > 0){
            $searchQuery = " (".implode(" AND ",$search_arr).") ";
        }
            ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('favorites_items.user_id', $userid);
        $records = $this->db->get('favorites_items')->result();
        $totalRecords = $records[0]->allcount;
         ## Total number of record with filtering
        
        $this->db->select('favorites_items.*,item.id as item_id,item.name,item.price,item.sold,item.item_images');
        $this->db->from('favorites_items');
        $this->db->join('item', 'item.id = favorites_items.item_id', 'left');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('favorites_items.user_id', $userid);
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;

        // ## Fetch records
        // $this->db->select('favorites_items.*');
        // $this->db->from('favorites_items');
        $this->db->select('favorites_items.*,item.id as item_id,item.name,item.detail,item.price,item.sold,item.item_images');
        $this->db->from('favorites_items');
        $this->db->join('item', 'item.id = favorites_items.item_id', 'left');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('favorites_items.user_id', $userid);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();
        $data = array();
        // $have_documents = false;
        $status_value = $this->lang->line('available');
        foreach($records as $record ){
            $images_ids = explode(",",$record->item_images);
            $item_id = $record->item_id;
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);   

            $item_name = json_decode($record->name);
            $item_detail = json_decode($record->detail);
            $language = $this->language;
            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                $image = '<div class="text-success" href=""><img class="img-responsive avatar-view" src="'.$base_url_img.'" alt="'.@$item_name->$language.'"></div>';
            }else{
               $base_url_img =base_url('assets_admin/images/product-default.jpg');
               $image = '<div class="text-success" href=""><img class="img-responsive avatar-view" src="'.$base_url_img.'" alt="'.@$item_name->$language.'"></div>';
           }
           if($record->sold == 'no'){
            $check_expired = $this->db->get_where('auction_items',['item_id' => $record->item_id])->row_array();
            $item_expiry_date = @$check_expired['bid_end_time'];
            $action = '';
            if ($item_expiry_date > date('Y-m-d H:i:s')) {
            $action .= '<a class="btn btn-primary sm" href="'.base_url("auction/online-auction/details/$record->auction_id/$record->item_id").'">'.$this->lang->line('bid_now').'</a>';
            }
            $iName = '<div class="item">
                        <div class="image">
                            '.$image.'
                        </div>
                        <div class="desc">
                            <h6>'.$item_name->$language.'</h6>
                        </div>
                    </div>';
            $data[] = array( 
                "checkbox" => 1,
                "id" => $record->id,
                "name" => @$iName,
                "detail" => @$item_detail->$language,
                "status" => $status_value,
                "action"=> $action
            );
            
        }
    }
            ## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );
    echo json_encode($response);    
}

public function notification()
{

    $id = $this->loginUser->id;
    $data =array();
    $data['new'] = 'new';
    $data['notification_active'] = 'active';
        // print_r($data['count']);die();
    $data['list'] =$this->db->get_where('notification', ['receiver_id' => $id])->result_array();
    $this->db->update('notification', ['status' => 'read'], ['receiver_id' => $id]);

        ///// End Auction list //////
    $this->template->load_user('notification-new', $data);
}

public function items()
{
    $data =array();
    $data['my_item_active'] = 'active';

        ///// End Auction list //////
    $this->template->load_user('terms', $data);

}

public function change_password()
{
    $data['new'] = 'new';
    $this->template->load_user('forgot_password',$data);
}

public function update_password()
{
    $password = hash("sha256",$this->input->post('current'));
    $id=$this->session->userdata('logged_in');
    $result=$this->db->get_where('users', ['id' => $id->id , 'password' => $password])->row_array();
    if($result)
    { 
        $this->load->library('form_validation');
        $rules = array(
            array(
                'field' => 'new_password',
                'label' => 'new password',
                'rules' => 'required|min_length[5]|max_length[50]',
            ),
            array(
                'field' => 'c_password',
                'label' => 'confirm password',
                'rules' => 'required|min_length[5]|max_length[50]',
            ),
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == FALSE) {
                // echo validation_errors();
            echo json_encode(array("error" => true, "responce" => validation_errors()));
            exit();
        } 
          // $this->form_validation->set_rules('new_password', 'new_password', 'required|min_length[4]|max_length[20]');
          // $this->form_validation->set_rules('c_password', 'c_password', 'required|min_length[4]|max_length[20]');
        $new_password=hash("sha256", $this->input->post('new_password'));
        $c_password=hash("sha256", $this->input->post('c_password'));
        if ($new_password == $c_password) 
        {
           $this->db->update('users',['password' => $new_password], ['id' => $result['id']]);
           echo json_encode(array('error' => false, "msg" => $this->lang->line('password_updated_successfully')));
       }
       else
       {
        echo json_encode(array('error' => true,"status" => true, "responce" => $this->lang->line('password_confirm_password')));
    }
}
else
{
    echo json_encode(array('error' => true,"status" => false, "responce" => $this->lang->line('invalid_current_password')));
}
}

public function sell_item()
{
    $data['sell_my_item'] = 'active';
    $data['new'] = 'new';
    $data['formaction_path'] = 'save_item';
    $data['category_list'] = $this->db->get_where('item_category',['status' => 'active'])->result_array();

    ///// End Auction list //////
    // $password=md5($this->input->post('current'));
    // $id=$this->session->userdata('logged_in');
    // $result=$this->db->get_where('users', ['id' => $id->id , 'password' => $password])->row_array();
    $this->template->load_user('new/sellMyItem', $data);
}

public function get_item_fields()
{
    $data = array();
    $category_id = $this->input->post('category_id');
    $datafields = $this->items_model->fields_data($category_id);
    $fdata = array();
    $language = $this->language;
    foreach ($datafields as $key1 => $fields)
    {   
        // print_r($datafields[1]);die();
        if (isset($fields['placeholder']) && !empty($fields['placeholder'])) {
            $fields['placeholder'] = $this->lang->line('choose');
        }
        $label_array = explode('|', $fields['label']);
        $fields['label'] = ($language == 'english') ? (isset($label_array[0]) ? $label_array[0] : '') : (isset($label_array[1]) ? $label_array[1] : '');
        $val_data = explode('|', $fields['value']);
        $fields['value'] = ($language == 'english') ? (isset($val_data[0]) ? $val_data[0] : '') : (isset($val_data[1]) ? $val_data[1] : '');
        $fields['values'] = json_decode($fields['values'],true);
        if (!empty($fields['values'])) {
            foreach ($fields['values'] as $key => $value) {
                $val_arraya = explode('|', $value['label']);
                $fields['values'][$key]['label'] = ($language == 'english') ? (isset($val_arraya[0]) ? $val_arraya[0] : '') : (isset($val_arraya[1]) ? $val_arraya[1] : '');
                // print_r($value['label']);die();
            }
        }
        $fields['data-id'] = $fields['id'];
        $fdata[] = $fields;
    }

    $data['category_id'] = $category_id;   
    $data['fields_data'] = $fdata; 
        // print_r($fdata);die();

    echo json_encode($fdata);  

}

public function get_makes_options()
{
    $data = array();
    $data_makes_array = $this->items_model->get_makes_list_active();
    $option_data = '';
    $language = $this->language;
    $option_data = '<option value="">'.$this->lang->line('select_make').'</option>';
    foreach ($data_makes_array as $value) {
        $title = json_decode($value['title']);
        $option_data.= '<option value="'.$value['id'].'">'.$title->$language.'</option>';
    } 
    $msg = (isset($option_data) && !empty($option_data) ? 'success' : 'error');
    echo json_encode(array('msg' => $msg , 'data' => $option_data));  
}

public function get_model_options()
{
    $data = array();
    $make_id = $this->input->post('make_id');
    $data_model_array = $this->items_model->get_item_model_list_active($make_id);
    $option_data = '';
    $language = $this->language;
    foreach ($data_model_array as $value) {
        $title = json_decode($value['title']);
        $option_data.= '<option value="'.$value['id'].'">'.$title->$language.'</option>';
    } 
    $msg = (isset($option_data) && !empty($option_data) ? 'success' : 'error');
    echo json_encode(array('msg' => $msg , 'data' => $option_data));  
}

public function get_subcategories()
{
    $data = array();
    $data_subcategory_array = array();
    $category_id = $this->input->post('category_id');
    if (!empty($category_id)) {
        $data_subcategory_array = $this->customer_model->get_item_subcategory_list($category_id);
    }
    $option_data = '';
    $language = $this->language;
    foreach ($data_subcategory_array as $value) {
        $cat_title = json_decode($value['title']);
        $option_data.= '<option value="'.$value['id'].'">'.@$cat_title->$language.'</option>';
    } 
    $msg = (isset($option_data) && !empty($option_data) ? 'success' : 'error');
    echo json_encode(array('msg' => $msg , 'data' => $option_data));  
}

public function save_item()
{
    if ($this->input->post()) {
        $item_dynamic_data = $this->input->post();

        $item_data = $this->input->post('item'); // get basic information 
        
        unset($item_dynamic_data['item']);  // remove basic information form data
        // print_r($item_data);die();
        $this->load->library('form_validation');
        $rules = array(
            array(
                'field' => 'item[name]',
                'label' => 'Name',
                'rules' => 'trim|required')
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

            echo json_encode(array('msg' => validation_errors()));
        } 
        else
        {   
            $items_attachment = array();
            if(!empty($item_data['seller_id']))
            {
                $seller_id = $item_data['seller_id'];
            }
            else
            {
                $seller_id = $this->loginUser->id;   
            }
            $rand_str = '0123456789';
            $item_name = [
                'english' => $item_data['name'],
                'arabic' => $item_data['name_arabic']
            ];
            unset($item_data['name']);
            unset($item_data['name_arabic']);

            $detail = [
                'english' => $item_data['detail'],
                'arabic' => $item_data['detail_arabic']
            ];
            unset($item_data['detail']);
            unset($item_data['detail_arabic']);

           $posted_data = array(
            'name' => json_encode($item_name),
            'location' => $item_data['location'],
            'detail' => json_encode($detail),
            'status' => 'active',
            'item_status' => 'created',
            'inspected' => 'no',
            'category_id' => $item_data['category_id'],
            'seller_id' => $this->loginUser->id,
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $this->loginUser->id
            );

            // a:

            // $posted_data['registration_no'] = $this->generate_string($rand_str);
            // $reg_no = $this->db->get_where('item', ['registration_no' => $posted_data['registration_no']]);
            // if ($reg_no->num_rows() > 0) {
            //     goto a;
            // }

        if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
        {
            $posted_data['subcategory_id'] = $item_data['subcategory_id'];
        }
        // if(isset($item_data['keyword']) && !empty($item_data['keyword']))
        // {
        //     $posted_data['keyword'] = $item_data['keyword'];
        // }
        if(isset($item_data['lat']) && !empty($item_data['lat']))
        {
            $posted_data['lat'] = $item_data['lat'];
        }
        if(isset($item_data['lng']) && !empty($item_data['lng']))
        {
            $posted_data['lng'] = $item_data['lng'];
        }
        if(isset($item_data['price']) && !empty($item_data['price']))
        {
            $posted_data['price'] = $item_data['price'];
        }
        if(isset($item_data['vin_no']) && !empty($item_data['vin_no']))
        {
            $posted_data['vin_number'] = $item_data['vin_no'];
        }
        if(isset($item_data['registration_no']) && !empty($item_data['registration_no']))
        {
            $posted_data['registration_no'] = $item_data['registration_no'];
        }

        $this->session->unset_userdata('items_images');
        $this->session->unset_userdata('items_documents');


        if(isset($item_data['make']))
        {
            $posted_data['make'] = $item_data['make'];
            $posted_data['model'] = $item_data['model'];
            $posted_data['mileage'] = $item_data['mileage'];
            $posted_data['mileage_type'] = $item_data['mileage_type'];
            $posted_data['specification'] = $item_data['specification'];
        }
                // print_r($posted_data);die();
        $posted_data['year'] = $item_data['year'];
        $result = $this->items_model->insert_item($posted_data);

        $path = "uploads/items_documents/".$result."/qrcode/";

                // make path
        if ( !is_dir($path)) {
            mkdir($path, 0777, TRUE);
        } 

        $qrcode_name = $this->generate_code($result,$path, ['id'=>$result]);
        if(!empty($qrcode_name))
        {
            $barcode_array = array(
                'barcode' => $qrcode_name
            );
            $this->items_model->update_item($result,$barcode_array);
        }


        foreach ($item_dynamic_data as $dynamic_keys => $dynamic_values) {
            $ids_arr = explode("-", $dynamic_keys);
            if(is_array($dynamic_values))
            {
                $dynamic_values_new = "[".implode(",",$dynamic_values)."]";
            }
            else
            {
                $dynamic_values_new = $dynamic_values;
            }
            $dynaic_information = array(
                'category_id' => $item_data['category_id'],
                'item_id' => $result,
                'fields_id' => $ids_arr[0],
                'value' => $dynamic_values_new,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->loginUser->id
            );

            $result_info = $this->items_model->insert_item_fields_data($dynaic_information);

        }

        if (!empty($result)) {
          $this->session->set_flashdata('msg', $this->lang->line('item_added_successfully'));
          $msg = 'success';
          echo json_encode(array('msg' => $msg, 'message' => $this->lang->line('item_added_successfully') , 'in_id' => $result));
        } else {
            $msg = $this->lang->line('db_error_found');
            echo json_encode(array('msg' => $msg, 'error' => $result));
        }
    }
    }else{

    }

}

public function save_item_file_images()
{

    if( ! empty($_FILES['images']['name'])){
        // print_r($_FILES);
        $item_id = $_POST['item_id'];
        $itemsIds_array = array();
        $ids_concate = '';

        $result_array = $this->items_model->get_item_byid($item_id);
        if(isset($result_array) && !empty($result_array))
        {
            $itemsIds_array = explode(',' ,$result_array[0]['item_images']);

            if(!empty($itemsIds_array) && !empty($result_array[0]['item_images']))
            {
                $ids_concate = $result_array[0]['item_images'].",";
            }
        }

        // make path
        $path = './uploads/items_documents/';
        if ( ! is_dir($path.$item_id)){
            mkdir($path.$item_id, 0777, TRUE);
        }
        // $config['upload_path'] = $path;
        $config['allowed_types'] = 'ico|png|jpg|jpeg';
        $sizes = [
        // ['width' => 1184, 'height' => 661],
        // ['width' => 191, 'height' => 120], // for details page carusal icons 
        // ['width' => 305, 'height' => 180], // for gallery page 
        // ['width' => 294, 'height' => 204], // for auction page
        // ['width' => 349, 'height' => 207], // for auction page
        ['width' => 37, 'height' => 36] // for table listing
        ];
        // if ( ! is_dir($path.$item_id)) {
        //     mkdir($path.$item_id, 0777, TRUE);
        // }
        $path = $path.$item_id.'/'; 
        $config['upload_path'] = $path;
        // $config['allowed_types'] = 'ico|png|jpg|pdf|ai|psd|eps|indd|doc|docx|ppt|pptx|xlsx|xls';
        // $config['allowed_types'] = 'ico|png|jpg|jpeg';
        $uploaded_file_ids = $this->files_model->multiUpload('images', $config, $sizes); // upload and save to database

        // $uploaded_file_ids = $this->files->upload('images', $config, $sizes);
        // $uploaded_file_ids = implode(',', $uploaded_file_ids);
         $uploaded_file_ids = implode(',', $uploaded_file_ids);
        $update = [
            'item_images' => $ids_concate.$uploaded_file_ids
        ];
        $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';

         if($result == 'true')
        {
            $this->session->set_flashdata('msg', 'Item Documents Added Successfully');
            echo json_encode(array("error"=> false,'message' => "Item Documents Added Successfully"));
        }
        else
        {
            echo json_encode(array("error"=> true,'message' => "Uploading process has been failed."));
        }
    }
    else
    {
        echo json_encode(array("error"=> true,'message' => "No item found to upload."));
    }
}

public function deposit()
{
    // $this->output->enable_profiler(TRUE);
    
    $data = array();
    $data['deposit_active'] = 'active';
    $data['new'] = 'new';
    $id = $this->loginUser->id;
    // $data['formaction_path'] = 'add-deposit';
    $user_total_deposit = $this->customer_model->user_balance($id);
    $data['balance']=$user_total_deposit['amount'];
    $data['percentage_settings']=$this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();
    $data['balance']=$user_total_deposit['amount'];
    ///// End Auction list //////
    $data['bids']=$this->customer_model->user_bids_count($id);
    $data['setting'] = $this->db->get_where('settings',['code_key' => 'min_deposit'])->row_array();
    ///// End Auction list //////

     $data['list']=$this->db->get_where('users',['id' => $id])->row_array();
//        print_r($data);

    // $data['category_list'] = $this->db->get('item_category')->result_array();
    $this->template->load_user('new/deposit-page', $data);
} 

public function item_deposit()
{
    $data = array();
    $data['new'] = 'new';
    $data['security_active'] = 'active';
    $today = date('Y-m-d H:i:s');
    $data['auctions'] = $this->db->get_where('auctions', [
        'status' => 'active',
        'access_type' => 'online',
        'start_time <=' => $today,
        'expiry_time >=' => $today
    ])->result_array();
        // foreach ($auctions as $key => $value) {
        //     $title = json_decode($value['title']);
        //     $options .= '<option value="'.$value['id'].'">'.$title->english.'</option>';
        // }
    $data['item_deposit_active'] = 'active';
    $data['setting'] = $this->db->get('settings')->row_array();

        ///// End Auction list //////
    $this->template->load_user('new/item-deposit-page', $data);
} 

public function cradit_card(){
    $id = $this->session->userdata('logged_in')->id;
    $user = $this->db->get_where('users', ['id' => $id])->row_array();
    $data = $this->input->post();
    if (empty($user['mobile']) 
        || empty($user['email']) 
        || empty($user['address']) 
        || empty($user['fname']) 
        || empty($user['lname']) 
        || empty($user['city']) 
        || empty($user['state'])
    ) {
        $redirect = (isset($_GET['r'])) ? urldecode($_GET['r']) : array();
        if(!empty($redirect)){
            $redirect = json_decode($redirect);
        }
        $rurl = strtok($data['rurl'], '?');
        array_push($redirect,$rurl);
        $json_redirect = json_encode($redirect);
        $redirect = urlencode($json_redirect);
        $this->session->set_flashdata('error', $this->lang->line('please_update_your_profile'));
            // redirect(base_url('visitor/packages/show')); 
        echo json_encode(array('msg'=>'success','response' => $this->lang->line('payment_failed_to_make'),'redirect'=> base_url('customer/profile?r=').$redirect,'showModel'=> true));
    }else{
        // 3. load PayTabs library
        //testing account
        /*$merchant_email='it@armsgroup.ae';
        $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
        $merchant_id='10050328';*/

        //pioneer account
        // $merchant_email='paytab@pioneerauctions.ae';
        // $secret_key='NIyG2xOULAVbZslkmP37UozperfYy0ZmIy2L5QFRrA3r2oLodOw8c5yqxNo5NoswWuh2lXTNyXeBlZZzNwIZCgq94OfSdHp9bGbx';
        // $merchant_id='10064570';

        /*$params = [
            'merchant_email'=>$merchant_email,
            'merchant_id'=>$merchant_id,
            'secret_key'=>$secret_key
        ];

        $this->load->library('Paytabs',$params);*/

        // 4. make a payment component through SADAD account and credit card

        $order_id = $id;
        $redirect = (isset($_GET['r'])) ? $_GET['r'] : array();
        if(!empty($redirect)){
            $this->session->set_flashdata('redirect', $redirect);
        }
        $lan = $this->language;
        $lan = ucfirst($lan);
        /*$invoice = [
            // "site_url" => "www.pioneerauctions.ae",
            "site_url" => "https://pa.yourvteams.com",
            "return_url" => base_url('customer/return_paytab'),
            "title" => $user['fname'].' '.$user['lname'],
            "cc_first_name" => $user['fname'],
            "cc_last_name" => $user['lname'],
            "cc_phone_number" => $user['mobile'],
            "phone_number" => $user['mobile'],
            "email" => $user['email'],
            //"products_per_title" => "MobilePhone || Charger || Camera",
            "products_per_title" => 'Auction Deposit',
            //"unit_price" => "12.123 || 21.345 || 35.678 ",
            "unit_price" => "1.0", //$data['amount'],
            //"quantity" => "2 || 3 || 1",
            "quantity" => "1",
            "other_charges" => "0.0",
            "payment_type" => "creditcard",
            "amount" => "1.0", //$data['amount'],
            "discount" => "0.0",
            "currency" => "AED",
            "reference_no" => $id,

            "billing_address" => $user['address'],
            "city" =>$user['city'],
            "state" => $user['state'],
            "postal_code" =>$user['po_box'],
            "country" => 'ARE',
            "shipping_first_name" => $user['fname'],
            "shipping_last_name" => $user['lname'],
            // "address_shipping" => $user['address'],
            "address_shipping" => 'ARE',
            "city_shipping" => $user['city'],
            "state_shipping" => $user['state'],
            "postal_code_shipping" => $user['po_box'],
            "country_shipping" => 'ARE',
            "msg_lang" => $lan,
            // "is_preauth" => 1,
            "cms_with_version" => "pioneer deposit"
        ];*/

            //return $invoice;

        $invoice2 = [
            "tran_type" => "auth",
            "tran_class"=> "ecom",
            "cart_id"=> 'TRN'.mt_rand(),
            "cart_description"=> "Auction Deposit",
            "cart_currency"=> "AED",
            "cart_amount"=> $data['amount'],
            "hide_shipping"=> TRUE,
            "customer_details" => [
                "name" => $user['fname'].' '.$user['lname'],
                "email"=> $user['email'],
                "street1"=> $user['address'],
                "city"=> $user['city'],
                "state"=> "DU",
                "country"=> "AE",
                "ip"=> $this->input->ip_address()
            ],
            //"callback"=> base_url('customer/paytabsCallback'),
            "return"=> base_url('customer/paytabsReturnURL')
        ];

            $this->load->library('Paytabs2');
            $response = $this->paytabs2->runPaytabs($invoice2);
            $response = json_decode($response);

            //print_r($response);return;

            if(isset($response->tran_ref) && !empty($response->tran_ref)){
                $transaction_data['transaction_id'] = $response->tran_ref;
                $transaction_data['user_id'] = $id;
                $transaction_data['deposit_type'] = 'permanent';
                $transaction_data['payment_type'] = 'card';
                $transaction_data['amount'] = $data['amount'];
                $transaction_data['created_on'] = date('Y-m-d H:i:s');
               // $transaction_data['created_by'] = $this->loginUser->id;
                $transaction_data['status'] = 'active';
                $transaction_data['deleted'] = 'no';

                $this->db->insert('auction_deposit', $transaction_data);
                echo json_encode(array('msg'=>'success','response' => $this->lang->line('package_recharge_success'),'redirect'=> $response->redirect_url, 'showModel'=> false));
            }else{
                 $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                echo json_encode(array('msg'=>'error','response' => $this->lang->line('payment_failed_to_make'),'redirect'=> base_url('customer/deposit')));
            }
        }
    }

    public function paytabsReturnURL()
    {
        $data = $_POST;
        if($data){
            if($data['respStatus'] == 'A'){
                $detail = json_encode($data);
                //if payment successful
                $deposit = $this->db->get_where('auction_deposit', ['transaction_id' => $data['tranRef']]);
                if($deposit->num_rows() > 0){
                    // new deposit payment
                    $deposit = $deposit->row_array();
                    if($deposit['transaction_id'] == $data['tranRef']){
                        // update order payment status to successful
                        $this->db->update('auction_deposit', ['status' => 'approved','detail' => $detail], ['id' => $deposit['id']]);

                        $this->session->set_flashdata('success', $this->lang->line('payment_made_successfully'));
                        if($this->session->flashdata('redirect')){
                            $redirect =  $this->session->flashdata('redirect');
                            $url = $this->redirect_model->custom_redirect($redirect);
                            redirect(base_url().$url);
                        } else {
                            redirect(base_url('customer/deposit'));
                        }
                    }else{
                        //if trans id not matched
                        $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                        redirect(base_url('customer/deposit'));
                    }
                }else{
                    // existing order payment
                    $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                    redirect(base_url('customer/deposit'));
                }
            }else{
                $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                redirect(base_url('customer/deposit'));
            }

        }else{
            $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
            redirect(base_url('customer/deposit'));
        }
    }

    public function paytabsCallback()
    {
        print_r($_POST);

        /*$payrefs = $_POST['payRefs'];
        $auctions = $this->db->get_where('auctions', ['access_type' => 'online'])->result_array();
        foreach ($auctions as $key => $auction) {
            $today_date = new DateTime(date('Y-m-d H:i:s'));
            $expiry_date_obj = new DateTime(date('Y-m-d H:i:s', strtotime($auction['expiry_time'])));
            $difference = $today_date->diff($expiry_date_obj);
            // print_r($difference);die();
            $remaning_minuts = ($difference->days * 24 *60) + ($difference->h * 60) + $difference->i;
            if($difference->invert == 1){
                $remaning_minuts = '-'.$remaning_minuts;
            }
                // print_r($remaning_minuts.',');
            if ($remaning_minuts == '-1') {

                $auction_items = $this->db->get_where('auction_items', ['auction_id' => $auction['id']])->result_array();
                foreach ($auction_items as $key => $auction_item) {
                    // print_r($auction_item);die();
                    $item = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                    if (!empty($item)) {
                        $query = $this->db->query('Select bid.buyer_id,bid.id,bid.bid_amount, bid.bid_status from bid inner join  ( Select bid.buyer_id, bid.id, bid.bid_amount, bid.bid_status from bid inner join  ( Select max(bid.id) as LatestId, item_id  from bid Group by bid.item_id  ) SubMax on bid.id = SubMax.LatestId and bid.item_id = SubMax.item_id WHERE bid.item_id = '.$auction_item['item_id'].' AND bid.auction_id = '.$auction['id'].';');
                        $buyer = $query->row_array();
                        // print_r($auction_item);die();
                        $sold = $this->db->get_where('sold_items', ['item_id' => $auction_item['item_id'],'buyer_id' => $buyer['buyer_id'], 'seller_id' => $item['seller_id']]);
                        
                        if ($sold->num_rows() == 0 && $buyer['bid_amount'] >= $item['price']) {

                            $this->db->update('bid', ['bid_status' => 'won'], ['id' => $buyer['id']]);
                            $sold_item = array(
                                'item_id' => $item['id'],
                                'auction_id' => $auction['id'],
                                'auction_item_id' => $auction_item['id'],
                                'buyer_id' => $buyer['buyer_id'],
                                'seller_id' => $item['seller_id'],
                                'price' => $buyer['bid_amount'],
                                'payrefs' => $buyer['payrefs'],
                                'payable_amount' => $buyer['bid_amount'],
                                'created_by' => '1',
                                'updated_by' => '1',
                                'created_on' => date('Y-m-d H:i'),
                                'sale_type' => 'online'
                            );
                            $insert = $this->db->insert('sold_items', $sold_item);
                            $result = $this->db->update('item', ['sold' => 'yes','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['id' => $item['id']]);
                            $this->db->update('auction_items', ['sold_status' => 'sold','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                            if ($insert) {

                                //send email to heighest bidder on sold item 
                                $this->load->model('email/Email_model', 'email_model');
                                $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                                $item_data = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                                $item_link = base_url('auction/online-auction/details/').$auction['id'].'/'.$auction_item['item_id'];
                                $email = $buyer_data['email'];
                                $itm_name_language = json_decode($item_data['name']);
                                $lan = $this->language;
                                $vars = array(
                                    '{username}' => $buyer_data['fname'],
                                    '{item_name}' => '<a href="'.$item_link.'">'.$itm_name_language->$lan.'</a>',
                                    '{year}' => $item_data['year'],
                                    '{bid_price}' => $buyer['bid_amount'],
                                    '{lot_number}' => $auction_item['order_lot_no'],
                                    '{registration_number}' => $item_data['registration_no'],
                                    '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                                );

                                $this->email_model->email_template($email, 'item-win-email', $vars, false);
                                // $this->sale_out_email($sold_item['item_id'], $sold_item['buyer_id']);

                                print_r('auction_success');
                            } else {
                                print_r('auction_fail');
                            }
                        } elseif ($sold->num_rows() == '0') {
                            $this->db->update('auction_items', ['sold_status' => 'approval','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);

                            //send email to heighest bidder on approval 
                            $this->load->model('email/Email_model', 'email_model');
                            $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                            $item_data = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                            $item_link = base_url('auction/online-auction/details/').$auction['id'].'/'.$auction_item['item_id'];
                            $email = $buyer_data['email'];
                            $itm_name_lan = json_decode($item_data['name']);
                            $lang = $this->language;
                            $vars = array(
                                '{username}' => $buyer_data['fname'],
                                '{item_name}' => '<a href="'.$item_link.'">'.$itm_name_lan->$lang.'</a>',
                                '{year}' => $item_data['year'],
                                '{bid_price}' => $buyer['bid_amount'],
                                '{lot_number}' => $auction_item['order_lot_no'],
                                '{registration_number}' => $item_data['registration_no'],
                                '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                            );

                            $this->email_model->email_template($email, 'need-approval-from-seller-email', $vars, false);
                            // $this->email_model->common_template($email, 'Approval Email', $body, $vars, false);
                        }
                    }
                }
            } elseif ($auction['expiry_time'] > date('Y-m-d H:i:s') && $auction['start_time'] < date('Y-m-d H:i:s')) {

                $auction_items = $this->db->get_where('auction_items', ['auction_id' => $auction['id'],'sold_status' => 'not','bid_end_time <' => date('Y-m-d H:i')])->result_array();
                foreach ($auction_items as $key => $auction_item) {
                    $item = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                    if (!empty($item)) {
                        $query = $this->db->query('Select bid.buyer_id, bid.id, bid.bid_amount, bid.bid_status from bid inner join  ( Select max(bid.id) as LatestId, item_id  from bid Group by bid.item_id  ) SubMax on bid.id = SubMax.LatestId and bid.item_id = SubMax.item_id WHERE bid.item_id = '.$item['id'].' AND bid.auction_id = '.$auction['id'].';');
                        $buyer = $query->row_array();
                        // print_r($auction_item);die();
                        $sold = $this->db->get_where('sold_items', ['item_id' => $auction_item['item_id'],'buyer_id' => $buyer['buyer_id'], 'seller_id' => $item['seller_id']]);
                        // print_r($sold->num_rows());
                        if ($sold->num_rows() == 0 && $buyer['bid_amount'] >= $item['price']) {

                            $this->db->update('bid', ['bid_status' => 'won'], ['id' => $buyer['id']]);
                            $sold_item = array(
                                'item_id' => $item['id'],
                                'auction_id' => $auction['id'],
                                'auction_item_id' => $auction_item['id'],
                                'buyer_id' => $buyer['buyer_id'],
                                'seller_id' => $item['seller_id'],
                                'price' => $buyer['bid_amount'],
                                'payable_amount' => $buyer['bid_amount'],
                                'created_by' => '1',
                                'updated_by' => '1',
                                'created_on' => date('Y-m-d H:i'),
                                'sale_type' => 'online',
                            );
                            $insert = $this->db->insert('sold_items', $sold_item);
                            $result = $this->db->update('item', ['sold' => 'yes','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['id' => $item['id']]);
                            $this->db->update('auction_items', ['sold_status' => 'sold','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                            if ($insert) {
                                //send email to heighest bidder on sold item 
                                $this->load->model('email/Email_model', 'email_model');
                                $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                                $item_data = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                                $item_link = base_url('auction/online-auction/details/').$auction['id'].'/'.$auction_item['item_id'];
                                $email = $buyer_data['email'];
                                $itm_name = json_decode($item_data['name']);
                                $lang = $this->language;
                                $vars = array(
                                    '{username}' => $buyer_data['fname'],
                                    '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lang.'</a>',
                                    '{year}' => $item_data['year'],
                                    '{bid_price}' => $buyer['bid_amount'],
                                    '{lot_number}' => $auction_item['order_lot_no'],
                                    '{registration_number}' => $item_data['registration_no'],
                                    '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                                );

                                $this->email_model->email_template($email, 'item-win-email', $vars, false);

                                // $this->sale_out_email($sold_item['item_id'], $sold_item['buyer_id']);

                                print_r('item_success');
                            } else {
                                print_r('item_fail');
                            }
                        } elseif ($sold->num_rows() == '0') {
                            $this->db->update('auction_items', ['sold_status' => 'approval','updated_by' => '1','updated_on' => date('Y-m-d H:i')], ['item_id' => $item['id'], 'auction_id' => $auction['id']]);
                            
                            //send email to heighest bidder on approval 
                            $this->load->model('email/Email_model', 'email_model');
                            $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                            $item_data = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                            $item_link = base_url('auction/online-auction/details/').$auction['id'].'/'.$auction_item['item_id'];
                            $email = $buyer_data['email'];
                            $itm_name = json_decode($item_data['name']);
                            $lang = $this->language;
                            $vars = array(
                                '{username}' => $buyer_data['fname'],
                                '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lang.'</a>',
                                '{year}' => $item_data['year'],
                                '{bid_price}' => $buyer['bid_amount'],
                                '{lot_number}' => $auction_item['order_lot_no'],
                                '{registration_number}' => $item_data['registration_no'],
                                '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                            );

                            $this->email_model->email_template($email, 'need-approval-from-seller-email', $vars, false);
                            // $this->email_model->common_template($email, 'Approval Email', $body, $vars, false);
                        }
                    }
                }
            }
        }*/
    }

    public function payment_hold()
    {
        //print_r($_REQUEST);die();
        if($_REQUEST['payment_reference']){
            // 3. load PayTabs library
            $merchant_email='it@armsgroup.ae';
            $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
            // 'nOiZ1L2lukUrIRPq3tsxKMQn653rsLqQAJQCvSYZdSqVKgmNJOltAcRGhV8KHyBihrUMkTLxMctxsPlcEHGeTyVbt4VaCwsUYnHi';
            $merchant_id='10050328';
            // '10041761';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];

            $this->load->library('Paytabs',$params);
            
            $response = $this->paytabs->capture_verify_payment($_REQUEST['payment_reference']);
            print_r($response);die();

            // if($response->response_code == 100){
            //     $detail = json_encode($response);
            //     //if payment successful
            //     $deposit = $this->db->get_where('auction_deposit', ['transaction_id' => $_REQUEST['payment_reference']]);
            //     if($deposit->num_rows() > 0){
            //         // new deposit payment
            //         $deposit = $deposit->row_array();
            //         if($deposit['transaction_id'] == $_REQUEST['payment_reference']){
            //             // update order payment status to successful
            //             $this->db->update('auction_deposit', ['status' => 'approved','detail' => $detail], ['id' => $deposit['id']]);

            //             $this->session->set_flashdata('success', 'Payment has been made successfully.');
            //             if($this->session->flashdata('redirect')){
            //                 $redirect =  $this->session->flashdata('redirect');
            //                 $url = $this->redirect_model->custom_redirect($redirect);
            //                 redirect(base_url().$url);
            //             } else {
            //                 redirect(base_url('customer/balance'));
            //             }
            //         }else{
            //             //if trans id not matched
            //             $this->session->set_flashdata('error', 'Payment has been failed to make.');
            //             redirect(base_url('customer/deposit'));
            //         }
            //     }else{
            //         // existing order payment
            //         $this->session->set_flashdata('error', 'Payment has been failed to make.');
            //         redirect(base_url('customer/deposit'));
            //     }
            // }else{
            //     $this->session->set_flashdata('error', 'Payment has been failed to make.');
            //     redirect(base_url('customer/deposit'));
            // }

        }else{
            $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
            redirect(base_url('customer/deposit'));
        }
    }

    public function return_paytab()
    {
        //print_r($_REQUEST);die();
        if($_REQUEST['payment_reference']){
            // 3. load PayTabs library
            $merchant_email='it@armsgroup.ae';
            $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
            // 'nOiZ1L2lukUrIRPq3tsxKMQn653rsLqQAJQCvSYZdSqVKgmNJOltAcRGhV8KHyBihrUMkTLxMctxsPlcEHGeTyVbt4VaCwsUYnHi';
            $merchant_id='10050328';
            // '10041761';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];

            $this->load->library('Paytabs',$params);
            
            $response = $this->paytabs->verify_payment($_REQUEST['payment_reference']);
            //print_r($response);die();

            if($response->response_code == 100){
                $detail = json_encode($response);
                //if payment successful
                $deposit = $this->db->get_where('auction_deposit', ['transaction_id' => $_REQUEST['payment_reference']]);
                if($deposit->num_rows() > 0){
                    // new deposit payment
                    $deposit = $deposit->row_array();
                    if($deposit['transaction_id'] == $_REQUEST['payment_reference']){
                        // update order payment status to successful
                        $this->db->update('auction_deposit', ['status' => 'approved','detail' => $detail], ['id' => $deposit['id']]);

                        $this->session->set_flashdata('success', $this->lang->line('payment_made_successfully'));
                        if($this->session->flashdata('redirect')){
                            $redirect =  $this->session->flashdata('redirect');
                            $url = $this->redirect_model->custom_redirect($redirect);
                            redirect(base_url().$url);
                        } else {
                            redirect(base_url('customer/balance'));
                        }
                    }else{
                        //if trans id not matched
                        $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                        redirect(base_url('customer/deposit'));
                    }
                }else{
                    // existing order payment
                    $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                    redirect(base_url('customer/deposit'));
                }
            }else{
                $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                redirect(base_url('customer/deposit'));
            }

        }else{
            $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
            redirect(base_url('customer/deposit'));
        }
    }


    public function item_cradit_card(){
        $rurl = '';
        $id = $this->session->userdata('logged_in')->id;
        $user = $this->db->get_where('users', ['id' => $id])->row_array();
        $data = $this->input->post();
        if (empty($data['auction_item_id'])){
            echo json_encode(array('msg'=>'fail','response' => 'please select item.' ,'redirect'=> base_url('customer/deposit')));
            exit();
        }
        if (empty($user['mobile']) 
        || empty($user['email']) 
        || empty($user['address']) 
        || empty($user['fname']) 
        || empty($user['lname']) 
        || empty($user['city']) 
        || empty($user['state'])) {

            $this->session->set_flashdata('error', $this->lang->line('please_update_your_profile'));
            // redirect(base_url('visitor/packages/show'));
            echo json_encode(array('msg'=>'success','response' => $this->lang->line('payment_failed_to_make'),'redirect'=> base_url('customer/profile')));
        }else{
            if ($this->input->post('rurl')) {
                $rurl = $this->input->post('rurl');
                $this->session->set_flashdata('rurl', $rurl);
            }
            // 3. load PayTabs library
            // $merchant_email='it@armsgroup.ae';
            // $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
            // // 'nOiZ1L2lukUrIRPq3tsxKMQn653rsLqQAJQCvSYZdSqVKgmNJOltAcRGhV8KHyBihrUMkTLxMctxsPlcEHGeTyVbt4VaCwsUYnHi';
            // $merchant_id='10050328';
            // // '10041761';

            // $params = [
            //     'merchant_email'=>$merchant_email,
            //     'merchant_id'=>$merchant_id,
            //     'secret_key'=>$secret_key
            // ];
            $lan = $this->language;
            $lan = ucfirst($lan);

            // $this->load->library('Paytabs',$params);

            // 4. make a payment component through SADAD account and credit card
                // $invoice = [
          //       "site_url" => "https://pa.yourvteams.com",
          //       "return_url" => base_url('return'),
          //       "title" => 'username',
          //       "cc_first_name" => $user['fname'],
          //       "cc_last_name" => $user['lname'],
          //       "cc_phone_number" => str_replace('+', '00', $user['mobile']),
          //       "phone_number" => $user['mobile'],
          //       "email" => $user['email'],
          //       //"products_per_title" => "MobilePhone || Charger || Camera",
          //       "products_per_title" => 'BundlDesigns Products', //$order['project_name'],
          //       //"unit_price" => "12.123 || 21.345 || 35.678 ",
          //       "unit_price" => $data['amount'],
          //       // $this->session->userdata('cart_total'),
          //       //"quantity" => "2 || 3 || 1",
          //       "quantity" => "1",
          //       "other_charges" => "0.0",
          //       //"amount" => "13.082",
          //       "amount" => $data['amount'],
          //       // $this->session->userdata('cart_total'),
          //       "discount" => "0.0",
          //       "currency" => "SAR",
          //       "reference_no" => $order_id,

          //       "payment_type" => "mada",

          //       "billing_address" => $user['city'],
          //       "city" => $user['city'],
          //       "state" => $user['city'],
          //       "postal_code" => $user['po_box'],
          //       "country" => $user['country'],
          //       "shipping_first_name" => $user['fname'],
          //       "shipping_last_name" => $user['lname'],
          //       "address_shipping" => $user['city'],
          //       "city_shipping" => $user['city'],
          //       "state_shipping" => $user['city'],
          //       "postal_code_shipping" => $user['po_box'],
          //       "country_shipping" => $user['country_shipping'],
          //       "msg_lang" => "English",
          //       "cms_with_version" => "bundldesigns"
          //   ];

            $order_id = $id;

            $invoice2 = [
                "tran_type" => "auth",
                "tran_class"=> "ecom",
                "cart_id"=> $id,
                "cart_description"=> "Auction Deposit",
                "cart_currency"=> "AED",
                "cart_amount"=> $data['amount'],
                //"cart_amount"=> 1,
                "hide_shipping"=> TRUE,
                "customer_details" => [
                    "name" => $user['username'],
                    "email"=> $user['email'],
                    "street1"=> $user['address'],
                    "city"=> $user['city'],
                    "state"=> "DU",
                    "country"=> "AE",
                    "ip"=> $this->input->ip_address()
                ],
                //"callback"=> base_url('customer/paytabsCallback'),
                "return"=> base_url('customer/item_return_paytab')
            ];

            $this->load->library('Paytabs2');
            $response = $this->paytabs2->runPaytabs($invoice2);
            $response = json_decode($response);

            if(isset($response->tran_ref) && !empty($response->tran_ref)){
                $auction_item = $this->db->select('item_id')->get_where('auction_items' ,['id' => $data['auction_item_id']])->row_array();

                $transaction_data['transaction_id'] = $response->tran_ref;
                $transaction_data['user_id'] = $id;
                $transaction_data['item_id'] = $auction_item['item_id'];
                $transaction_data['auction_id'] = $data['auction_id'];
                $transaction_data['auction_item_id'] = $data['auction_item_id'];
                $transaction_data['deposit'] = $data['amount'];
                // $transaction_data['deposit_type'] = 'permanent';
                $transaction_data['deposit_mode'] = 'card';
                $transaction_data['created_on'] = date('Y-m-d H:i:s');
                $transaction_data['created_by'] = $id;
                $transaction_data['status'] = 'active';

                $this->db->insert('auction_item_deposits', $transaction_data);
                // redirect($response->payment_url);
                echo json_encode(array('msg'=>'success','response' => $this->lang->line('package_recharge_success'),'redirect'=> $response->redirect_url));
            }else{
               $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                // redirect(base_url('visitor/packages/show'));
               echo json_encode(array('msg'=>'error','response' => $this->lang->line('payment_failed_to_make'),'redirect'=> base_url('customer/deposit')));
           }
       }


   }

   public function item_return_paytab()
   {
        $data = $_POST;
        if($data){

            if($data['respStatus'] == 'A'){
                $detail = json_encode($response);
                    //if payment successful
                $deposit = $this->db->get_where('auction_item_deposits', ['transaction_id' => $_REQUEST['payment_reference']]);
                if($deposit->num_rows() > 0){
                        // new deposit payment
                    $deposit = $deposit->row_array();
                    if($deposit['transaction_id'] == $data['tranRef']){
                            // update order payment status to successful
                        $this->db->update('auction_item_deposits', ['status' => 'approved','deposit_detail' => $detail], ['id' => $deposit['id']]);
                        $rual = base_url('customer/security');
                        if ($this->session->flashdata('rurl') && !empty($this->session->flashdata('rurl'))) {
                            $rual = $this->session->flashdata('rurl');
                        }

                        $this->session->set_flashdata('success', $this->lang->line('payment_made_successfully'));
                        redirect($rual);
                    }else{
                            //if trans id not matched
                        $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                        redirect(base_url('customer/item_deposit'));
                    }
                }else{
                        // existing order payment
                    $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                    redirect(base_url('customer/item_deposit'));
                }
            }else{
                $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                redirect(base_url('customer/item_deposit'));
            }

        }else{
            $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
            redirect(base_url('customer/item_deposit'));
        }
    }

    public function payment_cradit_card($amount, $sold_item_id){
        $data = array();
        $id = $this->session->userdata('logged_in')->id;
        $user = $this->db->get_where('users', ['id' => $id])->row_array();
        $data = $this->input->post();
        $data['amount'] = $amount;
        if (empty($user['mobile']) || empty($user['email']) || empty($user['address']) || empty($user['po_box']) || empty($user['fname']) || empty($user['lname']) || empty($user['city']) || empty($user['state'])) {

            $this->session->set_flashdata('error', $this->lang->line('please_update_your_profile'));
            redirect(base_url('customer/profile'));
        }else{
                // 3. load PayTabs library
            $merchant_email='it@armsgroup.ae';
            $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
                // 'nOiZ1L2lukUrIRPq3tsxKMQn653rsLqQAJQCvSYZdSqVKgmNJOltAcRGhV8KHyBihrUMkTLxMctxsPlcEHGeTyVbt4VaCwsUYnHi';
            $merchant_id='10050328';
                // '10041761';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];

            $this->load->library('Paytabs',$params);

            $order_id = $id;

            $lan = $this->language;
            $lan = ucfirst($lan);
            $invoice = [
                "site_url" => "https://pa.yourvteams.com",
                "return_url" => base_url('customer/payment_return_paytab'),
                "title" =>'customer',
                "cc_first_name" => $user['fname'],
                "cc_last_name" => $user['lname'],
                "cc_phone_number" => $user['mobile'],
                "phone_number" => $user['mobile'],
                "email" => $user['email'],
                //"products_per_title" => "MobilePhone || Charger || Camera",
                "products_per_title" => 'pioneer auctoin deposit', //$order['project_name'],
                //"unit_price" => "12.123 || 21.345 || 35.678 ",
                "unit_price" =>$data['amount'],
                //"quantity" => "2 || 3 || 1",
                "quantity" => "1",
                "other_charges" => "0.0",
                "payment_type" => "mada",
                "amount" =>$data['amount'],
                "discount" => "0.0",
                "currency" => "AED",
                "reference_no" => $id,

                "billing_address" => $user['address'],
                // "billing_address" => 'UAE',
                "city" =>$user['city'],
                "state" => $user['state'],
                // "state" => 'UAE',
                // "postal_code" =>'54000',
                "postal_code" =>$user['po_box'],
                "country" => 'ARE',
                "shipping_first_name" => $user['fname'],
                "shipping_last_name" => $user['lname'],
                // "address_shipping" => $user['address'],
                "address_shipping" => 'ARE',
                "city_shipping" => $user['city'],
                // "city_shipping" => 'Lahore',
                "state_shipping" => $user['state'],
                // "state_shipping" => 'UAE',
                "postal_code_shipping" => $user['po_box'],
                // "postal_code_shipping" => '54000',
                "country_shipping" => 'ARE',
                // "country_shipping" => 'SAU',
                "msg_lang" => $lan,
                "cms_with_version" => "pioneer deposit"
            ];

            $response = $this->paytabs->create_pay_page($invoice);
            if(isset($response->response_code) && $response->response_code == "4012"){
                $this->db->update('sold_items', ['payment_details' => $response->p_id], ['id' => $sold_item_id]);
                redirect($response->payment_url);
            }else{
                $this->session->set_flashdata('error', $response->result);
                redirect(base_url('user-payment'));
            }
        }
        
    }

    public function payment_return_paytab()
    {
        // print_r($_REQUEST);die();
        if($_REQUEST['payment_reference']){
            // 3. load PayTabs library
            $merchant_email='it@armsgroup.ae';
            $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
            // 'nOiZ1L2lukUrIRPq3tsxKMQn653rsLqQAJQCvSYZdSqVKgmNJOltAcRGhV8KHyBihrUMkTLxMctxsPlcEHGeTyVbt4VaCwsUYnHi';
            $merchant_id='10050328';
            // '10041761';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];

            $this->load->library('Paytabs',$params);
            
            $response = $this->paytabs->verify_payment($_REQUEST['payment_reference']);

            if($response->response_code == 100){
                $detail = json_encode($response);
                //if payment successful
                $deposit = $this->db->get_where('sold_items', ['payment_details' => $_REQUEST['payment_reference']]);
                if($deposit->num_rows() > 0){
                    // new deposit payment
                    $deposit = $deposit->row_array();
                    if($deposit['payment_details'] == $_REQUEST['payment_reference']){
                        // update order payment status to successful
                        $this->db->update('sold_items', ['payment_status' => '1','payment_details' => $detail,'payment_mode' => 'card'], ['id' => $deposit['id']]);

                        $this->session->set_flashdata('success', $this->lang->line('payment_made_successfully'));
                        redirect(base_url('user-payment'));
                    }else{
                        //if trans id not matched
                        $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                        redirect(base_url('user-payment'));
                    }
                }else{
                    // existing order payment
                    $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                    redirect(base_url('user-payment'));
                }
            }else{
                $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                redirect(base_url('user-payment'));
            }

        }else{
            $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
            redirect(base_url('user-payment'));
        }
    }


    public function add_bank_slip()
    {
        if ($this->input->post()) {
            $posted_data = $this->input->post();
            if(!empty($_FILES['slip']['name']))
            {
                // print_r($_FILES['slip']);die();

                // make path
                $path = './uploads/bank_slips/';
                if ( ! is_dir($path)) 
                {
                    mkdir($path, 0777, TRUE);
                }
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'ico|png|jpg|jpeg|pdf|doc|docx|txt|xls';
                $uploaded_file_ids = $this->files_model->upload('slip', $config);

                // print_r($uploaded_file_ids);die();
                if (isset($uploaded_file_ids['error'])) {
                    $this->session->set_flashdata('error', $uploaded_file_ids['error']);
                    echo json_encode(array('msg'=>'error','response' => $uploaded_file_ids['error']));
                    exit();
                }

                $posted_data['user_id'] = $this->session->userdata('logged_in')->id;
                $posted_data['slip'] = implode(',', $uploaded_file_ids);
                $posted_data['created_on'] = date('Y-m-d H:i:s');
                $result = $this->db->insert('bank_deposit_slip', $posted_data);
                $inserted_id = $this->db->insert_id();
                if ($result) {
                    echo json_encode(array('msg'=>'success','response' => $this->lang->line('slip_has_been_added_successfully')));
                }else {
                    echo json_encode(array('msg'=>'error','response' => $this->lang->line('slip_has_een_failed_to_add')));
                }

                // $result_array = ($this->db->update('press',['image' => $uploaded_file_ids],['id' => $inserted_id])) ? 'true' : 'false';
            }

        }

        // $this->template->load_user('deposit-page', $data);
    } 

    public function uploaded_docs()
    {
        $data =array();
        $data['new'] = 'new';
        $data['document_active'] = 'active';
        $data['user_id'] = $this->loginUser->id;

        $data['docs'] = $this->db->select('user_documents.*, documents_type.name as document_type, documents_type.id as document_type_id')->from('user_documents')
            ->join('documents_type', 'user_documents.document_type_id = documents_type.id')
            ->where(['user_id' => $this->loginUser->id])
            ->order_by('documents_type.id', 'ASC')
            ->get()->result_array();

        $this->template->load_user('new/documentListing', $data);
    }

    public function docs()
    {
        $data['userId'] = $this->loginUser->id; 
        $data['docList'] = $this->db->get('documents_type')->result_array();

        $this->template->load_user('new/documentUpload', $data);
    }

    public function docsLoad()
    {
        $userId = $this->loginUser->id; 
        $catId = $this->input->post('catId');
        
        $docs = $this->db->get_where('user_documents', [
            'user_id'=> $userId,
            'document_type_id'=> $catId
        ])->row_array();

        //echo $this->db->last_query();
           
        $userDocs = [];
        if(!empty($docs)) {
            $fileIds = explode(",", $docs['file_id']);
            $fileIds = array_filter($fileIds, 'is_numeric');
            if (!empty($fileIds)) {
                $files = $this->db->where_in('id', $fileIds)->get('files')->result_array();
                foreach ($files as $file) {
                    $userDocs[] = [
                        'name' => $file['name'],
                        'size' => $file['size'],
                        'url' => $file['name'],
                        'type' => $file['type'],
                        'id' => $file['id']
                    ];
                }
            }
        }
        echo json_encode($userDocs);
    }

    ////// new code dropzone feilds ////////
    public function save_user_documents()
    {
        if( ! empty($_FILES['images']['name'])){
            $catid = $this->input->post('catid');
            $user_id = $this->loginUser->id;
            $docIds_array = array();
            $ids_concate = '';
            $result_array = $this->items_model->get_customersDocs($user_id,$catid);
            if(isset($result_array) && !empty($result_array))
            {
                $docIds_array = explode(',' ,$result_array[0]['file_id']);
                if(!empty($docIds_array) && !empty($result_array[0]['file_id']))
                {
                    $ids_concate = $result_array[0]['file_id'].",";
                }
            }
            // make path
            $path = './uploads/users_documents/';
            if ( ! is_dir($path.$user_id)){
                mkdir($path.$user_id, 0777, TRUE);
            }
            $config['allowed_types'] = 'ico|png|jpg|jpeg|pdf|doc|docx|xlsx|xls|ppt|pptx|txt';
        //     $sizes = [
        //     ['width' => 37, 'height' => 36] // for table listing
        // ];
        $sizes = [];
        $path = $path.$user_id.'/'; 
        $config['upload_path'] = $path;
            $uploaded_file_ids = $this->files_model->multiUpload('images', $config, $sizes); 
            if (isset($uploaded_file_ids['error'])) {
                echo json_encode(array("error"=> false, 'status'=>"error", 'message' => $uploaded_file_ids['error']));
            } else{
                // upload and save to database
                $uploaded_file_ids = array_filter($uploaded_file_ids, 'is_numeric');
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $update = [
                    'file_id' => $ids_concate.$uploaded_file_ids
                ];
                $update['user_Id']= $this->loginUser->id;
                $update['document_type_id'] = $catid;
                $saveDoc = $this->db->get_where('user_documents',['user_Id'=>$user_id,'document_type_id'=>$catid])->result_array();
                if (!empty($saveDoc)) 
                {
                    $result = $this->db->update('user_documents',$update,['user_Id' =>$user_id,'document_type_id' =>$catid]);
                    if($result == 'true')
                    {
                        $this->session->set_flashdata('msg', $this->lang->line('user_documents_updated'));
                        echo json_encode(array("error"=> false, 'status'=>"success", 'message' => $this->lang->line('user_documents_updated')));
                    }
                }
                else{
                    $result = $this->db->insert('user_documents',$update);
                    if($result == 'true')
                    {
                        $this->session->set_flashdata('msg', $this->lang->line('user_documents_added'));
                        echo json_encode(array("error"=> false, 'status'=>"success", 'message' => $this->lang->line('user_documents_added')));
                    }
                }
                

            }
        }
    }
      ////////// end drop zone /////////////

     ///////// Dropzone  delete process  /////////
    public function delete_customerDocs()
    {
        $d= $this->input->post('catid');
        $attach_name = $this->input->post('file_to_be_deleted');
        $file_array = $this->files_model->get_file_byName($attach_name);
        $userId =$this->loginUser->id;
        $catid =$this->input->post('catid');
        if(isset($file_array) && !empty($file_array))
        {
            $result_array = $this->items_model->get_customersDocs($userId,$catid);
            if(isset($result_array) && !empty($result_array))
            {
                $docIds_array = explode(',' ,$result_array[0]['file_id']);
                if(!empty($docIds_array))
                {
                    $str = $result_array[0]['file_id'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'file_id' => $updated_str,
            ];
            $result_update = $this->db->update('user_documents',$update,['user_id' => $userId,'document_type_id'=>$d]);
            $get_image = $this->db->get_where('files',['id'=>$result_array['file_id']])->row_array();
        }
        $path = FCPATH .  "uploads/users_documents/".$userId."/";

        unlink(FCPATH.'uploads/users_documents/'.$userId."/".$get_image."/");
        // $this->load->helper("file");
        // delete_files($path);

        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';    
        }
    }

    ///////// End Dropzone  delete process  /////////

    function removeItemString($str, $item) {
        $parts = explode(',', $str);
        while(($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }
        return implode(',', $parts);
    }

    private function generate_string($input, $strength = 6) 
    {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) 
        {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }

    public function generate_code($item_id,$path, $item_details_array= array())
    {
        $this->load->library('ciqrcode');

           // how to save PNG codes to server 
        $tempDir = FCPATH .  $path;
        $tempDir_url = base_url()."uploads/items_documents/";

        $codeContents = json_encode($item_details_array); 

            // we need to generate filename somehow,  
            // with md5 or with database ID used to obtains $codeContents... 
        $fileName = $item_id.'_'.md5($codeContents).'.png'; 

        $pngAbsoluteFilePath = $tempDir.$fileName; 
        $urlRelativeFilePath = $tempDir_url.$fileName; 

            // generating 
        if (!file_exists($pngAbsoluteFilePath)) { 
            QRcode::png($codeContents, $pngAbsoluteFilePath); 
            return $fileName;
        } else { 
            return $fileName;
        } 

    }


    public function get_ai_list()
    {
        $data = $this->input->post();
        $language = $this->language;
        if($data){

            $user_id = $data['user_id'];
            unset($data['user_id']);
            
            if(isset($data['item_id'])){
                $item_id = $data['item_id'];
                unset($data['item_id']);
            }else{
                $item_id = '';
            }

            $item_array = array();
            $item_ids = $this->db->select('item_id')->get_where('auction_item_deposits',['user_id' => $user_id])->result_array();
            foreach ($item_ids as $key => $value) {
                array_push($item_array, $value['item_id']);
            }
            // print_r($item_array);die();
            $auction_id = $data['id'];
            $this->db->select('auction_items.*, item.name as item_name');
            $this->db->from('auction_items');
            $this->db->join('item','auction_items.item_id = item.id', 'left');
            $this->db->where([
                'auction_items.auction_id' => $auction_id,
                'auction_items.status' => 'active',
                'auction_items.security' => 'yes',
                'auction_items.sold_status' => 'not',
                'auction_items.deposit !=' => null
            ]);
            if (!empty($item_array)) {
                $this->db->where_not_in('auction_items.item_id', $item_array);
            }
            $auction_items = $this->db->get();

            //echo $this->db->last_query();

            if($auction_items->num_rows() > 0){
                $auction_items = $auction_items->result_array();

                $options = '<option value="">Choose</option>';
                foreach ($auction_items as $key => $item) {
                    $selected = (!empty($item_id) && $item_id == $item['id']) ? 'selected' : ''; 
                // print_r($selected);die();
                    $item_name_multi = json_decode($item['item_name']);
                    $options .= '<option '.$selected.' value="'.$item['id'].'">'.$item_name_multi->$language.'</option>';
                }

                echo json_encode(['status' => true, 'data' => $options]);
            }else{
                echo json_encode(['status' => false]);
            }
        }
    }

    public function get_ai_deposit()
    {
        $data = $this->input->post();
        if($data){
            //return print_r($data);
            $auction_item_id = $data['id'];
            $auction_item = $this->db->get_where('auction_items', [
                'id' => $auction_item_id,
                'status' => 'active',
                'security' => 'yes',
                'deposit !=' => null
            ])->row_array();

            echo json_encode(['status' => true, 'deposit' => $auction_item['deposit'], 'item_id' => $auction_item['item_id']]);
        }
    }

    public function upload()
    {
        if ( ! empty($_FILES)) 
        {
            $config["upload_path"]   = $this->upload_path;
            $config["allowed_types"] = "gif|jpg|png";
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload("file")) {
                echo "failed to upload file(s)";
            }
        }
    }

    public function remove()
    {
        $file = $this->input->post("file");
        if ($file && file_exists($this->upload_path . "/" . $file)) {
            unlink($this->upload_path . "/" . $file);
        }
    }

    public function list_files()
    {
        $this->load->helper("file");
        $files = get_filenames($this->upload_path);
        // we need name and size for dropzone mockfile
        foreach ($files as &$file) {
            $file = array(
                'name' => $file,
                'size' => filesize($this->upload_path . "/" . $file)
            );
        }

        header("Content-type: text/json");
        header("Content-type: application/json");
        echo json_encode($files);
    }

    public function void_cradit_card_test()
    {
        $rurl = '';
        $id = $this->session->userdata('logged_in')->id;
        $user = $this->db->get_where('users', ['id' => $id])->row_array();
        $data = $this->input->post();
        if (empty($data['auction_item_id'])){
            echo json_encode(array('msg'=>'fail','response' => $this->lang->line('please_select_item') ,'redirect'=> base_url('customer/deposit')));
            exit();
        }
        if (empty($user['mobile']) || empty($user['email']) || empty($user['address']) || empty($user['po_box']) || empty($user['fname']) || empty($user['lname']) || empty($user['city']) || empty($user['state'])) {

            $this->session->set_flashdata('error', $this->lang->line('please_update_your_profile'));
            // redirect(base_url('visitor/packages/show'));
            echo json_encode(array('msg'=>'success','response' => $this->lang->line('payment_failed_to_make'),'redirect'=> base_url('customer/profile')));
        }else{
            if ($this->input->post('rurl')) {
                $rurl = $this->input->post('rurl');
                $this->session->set_flashdata('rurl', $rurl);
            }
            // 3. load PayTabs library
            $merchant_email='it@armsgroup.ae';
            $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
            // 'nOiZ1L2lukUrIRPq3tsxKMQn653rsLqQAJQCvSYZdSqVKgmNJOltAcRGhV8KHyBihrUMkTLxMctxsPlcEHGeTyVbt4VaCwsUYnHi';
            $merchant_id='10050328';
            // '10041761';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];

            $this->load->library('Paytabs',$params);

            $order_id = $id;
            $invoice = [
                "site_url" => "https://pa.yourvteams.com",
                "return_url" => base_url('customer/item_return_paytab'),
                "title" =>'customer',
                "cc_first_name" => $user['fname'],
                "cc_last_name" => $user['lname'],
                "cc_phone_number" => $user['mobile'],
                "phone_number" => $user['mobile'],
                "email" => $user['email'],
                //"products_per_title" => "MobilePhone || Charger || Camera",
                "products_per_title" => 'pioneer auctoin deposit', //$order['project_name'],
                //"unit_price" => "12.123 || 21.345 || 35.678 ",
                "unit_price" =>$data['amount'],
                //"quantity" => "2 || 3 || 1",
                "quantity" => "1",
                "other_charges" => "0.0",
                "payment_type" => "mada",
                "amount" =>$data['amount'],
                "discount" => "0.0",
                "currency" => "AED",
                "reference_no" => $id,

                "billing_address" => $user['address'],
                // "billing_address" => 'UAE',
                "city" =>$user['city'],
                "state" => $user['state'],
                // "state" => 'UAE',
                // "postal_code" =>'54000',
                "postal_code" =>$user['po_box'],
                "country" => 'ARE',
                "shipping_first_name" => $user['fname'],
                "shipping_last_name" => $user['lname'],
                // "address_shipping" => $user['address'],
                "address_shipping" => 'ARE',
                "city_shipping" => $user['city'],
                // "city_shipping" => 'Lahore',
                "state_shipping" => $user['state'],
                // "state_shipping" => 'UAE',
                "postal_code_shipping" => $user['po_box'],
                // "postal_code_shipping" => '54000',
                "country_shipping" => 'ARE',
                // "country_shipping" => 'SAU',
                "msg_lang" => "English",
                "cms_with_version" => "pioneer deposit"
            ];

            $response = $this->paytabs->create_pay_page($invoice);
            if(isset($response->response_code) && $response->response_code == "4012"){
                $auction_item = $this->db->select('item_id')->get_where('auction_items' ,['id' => $data['auction_item_id']])->row_array();

                $transaction_data['transaction_id'] = $response->p_id;
                $transaction_data['user_id'] = $id;
                $transaction_data['item_id'] = $auction_item['item_id'];
                $transaction_data['auction_id'] = $data['auction_id'];
                $transaction_data['auction_item_id'] = $data['auction_item_id'];
                $transaction_data['deposit'] = $data['amount'];
                // $transaction_data['deposit_type'] = 'permanent';
                $transaction_data['deposit_mode'] = 'card';
                $transaction_data['created_on'] = date('Y-m-d H:i:s');
                $transaction_data['created_by'] = $id;
                $transaction_data['status'] = 'active';

                $this->db->insert('auction_item_deposits', $transaction_data);
                // redirect($response->payment_url);
                echo json_encode(array('msg'=>'success','response' => $this->lang->line('package_recharge_success'),'redirect'=> $response->payment_url));
            }else{
               $this->session->set_flashdata('error', $response->result);
                // redirect(base_url('visitor/packages/show'));
               echo json_encode(array('msg'=>'error','response' => $this->lang->line('payment_failed_to_make'),'redirect'=> base_url('customer/deposit')));
            }
        }

    }

    public function void_return_paytab_test()
    {
        //print_r($_REQUEST);die();
        if($_REQUEST['payment_reference']){
                // 3. load PayTabs library
            $merchant_email='it@armsgroup.ae';
            $secret_key='1LTg7LHeKXOiZo6bPoHrKPVsKJfyaHKbtDrBp9Pa6Es52yhRXYjIOfbzZq898TlCxNdWW262Gz8J8pB5oVNdaYM9pUjyNEalrnhn';
                // 'nOiZ1L2lukUrIRPq3tsxKMQn653rsLqQAJQCvSYZdSqVKgmNJOltAcRGhV8KHyBihrUMkTLxMctxsPlcEHGeTyVbt4VaCwsUYnHi';
            $merchant_id='10050328';
                // '10041761';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];

            $this->load->library('Paytabs',$params);

            $response = $this->paytabs->verify_payment($_REQUEST['payment_reference']);

            if($response->response_code == 100){
                $detail = json_encode($response);
                    //if payment successful
                $deposit = $this->db->get_where('auction_item_deposits', ['transaction_id' => $_REQUEST['payment_reference']]);
                if($deposit->num_rows() > 0){
                        // new deposit payment
                    $deposit = $deposit->row_array();
                    if($deposit['transaction_id'] == $_REQUEST['payment_reference']){
                            // update order payment status to successful
                        $this->db->update('auction_item_deposits', ['status' => 'approved','deposit_detail' => $detail], ['id' => $deposit['id']]);
                        $rual = base_url('customer/security');
                        if ($this->session->flashdata('rurl') && !empty($this->session->flashdata('rurl'))) {
                            $rual = $this->session->flashdata('rurl');
                        }

                        $this->session->set_flashdata('success', $this->lang->line('payment_made_successfully'));
                        redirect($rual);                   
                    }else{
                            //if trans id not matched
                        $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                        redirect(base_url('customer/item_deposit'));
                    }
                }else{
                        // existing order payment
                    $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                    redirect(base_url('customer/item_deposit'));
                }
            }else{
                $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
                redirect(base_url('customer/item_deposit'));
            }

        }else{
            $this->session->set_flashdata('error', $this->lang->line('payment_failed_to_make'));
            redirect(base_url('customer/item_deposit'));
        }
    }

    public function removeFavorite()
    {
        $data = $this->input->post();
        if($data){
            //print_r($data);
            $user = $this->session->userdata('logged_in');
            if($user){
                $this->db->where_in('id', $data['selected_ids']);
                $this->db->delete('favorites_items', ['user_id' => $user->id]);
                    $msg = 'success';
                echo $msg;
                exit();
            }else{
                $this->session->set_flashdata('error', $this->lang->line('please_login_first_to_item_into_favorite'));
                echo '0';
            }
        }
    }

}