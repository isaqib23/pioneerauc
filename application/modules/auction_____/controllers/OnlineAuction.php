<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once('vendor/autoload.php');

class OnlineAuction extends MY_Controller {
    //public $language = 'english';
    //test
    function __construct()
    {

        parent::__construct();
        $this->load->model('Online_auction_model', 'oam');
        $this->load->model('Live_auction_model', 'lam');
        $this->load->model('customer/Customer_model', 'customer_model');
        $this->load->model('home/Home_model', 'home_model');
        $this->load->model('users/Users_model', 'users_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('files/files_model', 'files');
        $this->load->library('pagination');
    }

    public function testsms()
    {
        $this->load->library('SendSmart');
        //$res = $this->sendsmart->sms('923336395306', 'Test sms by zain');
        //$res = $this->sendsmart->sms('971544403642', 'Test sms by AppliconSoft');
        $res = $this->sendsmart->sms('971561387755', 'Test sms by AppliconSoft');
        print_r($res);
    }
    
    public function index($id)
    {
        //echo "xxxx";die();
        // $data = [];
        $this->output->enable_profiler(TRUE);
        $cat_id = $this->uri->segment(3);
        $data['new'] = 'new';
        $auctions_online = $this->home_model->get_online_auctions($cat_id);
        $id = $auctions_online['id'];
        $data['auction_id'] = $id;
        // $data['sold']= $this->db->get_where('auction_items', ['auction_id' => $id])->row_array();
        $data['online_auctions'] = $this->oam->get_online_auctions();
        //// for catagories for header and count ////

        ////// End for catagories for header and count ///////
        $data['selected_auction'] = $this->db->get_where('auctions', ['id' => $cat_id])->row_array();
        $data['selected_category'] = $this->db->get_where('item_category', ['id' => $cat_id])->row_array();
        $data['item_makes'] = $this->db->get_where('item_makes', ['status' => 'active'])->result_array();
        $data['item_category_fields'] = $this->db->get_where('item_category_fields', ['category_id' => $cat_id])->result_array();

        $data['category_id'] = $cat_id;

        $data['limit'] = $this->db->get_where('auction_items',['auction_id'=>$id])->result_array();
        // print_r($data['limit']);die();
        // $this->template->load_user('auction/online_auction/online_auction_new', $data);
        $this->template->load_user('auction/online_auction/online_auction_final', $data);
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
            $option_data.= '<option value="'.$value['id'].'">'.$title->english.'</option>';
        } 
        $msg = (isset($option_data) && !empty($option_data) ? 'success' : 'error');
        echo json_encode(array('msg' => $msg , 'data' => $option_data));  
    }

    public function getAuctionItemDetail(){
        $item_detail = array();
        $auctionItemDetail = array();
        $response = array();
        $id = $this->input->post('id');
        $auction_id = $this->input->post('auction_id');
        $auctionItemDetail = $this->livecontroller_model->getAuctionItemsDetail($id);
        $item_detail = $this->livecontroller_model->getItemDetail($auctionItemDetail['item_id']);
        if(!empty($auctionItemDetail)){
            $response = array(
                'order_lot_no' => $auctionItemDetail['order_lot_no'],
                'name' => $item_detail['name'],
                'reserve_price' => $item_detail['price'],
                'item_id' => $item_detail['id'],
                'auction_id' => $auction_id,
            );
            echo json_encode(array('error'=>false,'response'=>$response));
        }else{
            echo json_encode(array('error'=>true,'response'=>$auctionItemDetail,'message'=>'Invalid Item found.'));
        }

    }

    public function live_online_item_detail($auction_id, $item_id)
    {
        if(empty($auction_id) || empty($auction_id)) {
            show_404();
            exit;
        }

        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ret = strtr(rawurlencode($actual_link), $revert);
        $data['rurl'] = $ret;
        $data['new'] = 'new';
        $data['auction_id'] = $auction_id;
        $data['item_id'] = $item_id;

        // Get online auctions
        $data['online_auctions'] = $this->oam->get_online_auctions();

        //auction item details
        $data['item'] = $this->oam->get_items_details($item_id,$auction_id);
        $data['category'] = $this->db->get_where('item_category', ['id' => $data['item']['category_id']])->row_array();
        $data['auction_details'] = $this->db->get_where('auctions',['id' => $auction_id])->row_array();
        // print_r($data['item']);die();
        //items category dynamic fields
        $datafields = $this->oam->fields_data($data['item']['category_id']);
        $fdata = array();
        foreach ($datafields as $key => $fields)
        {
            $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id,$fields['id']);
            $fields['values'] = json_decode($fields['values'],true);  
            $fields['data-id'] = $fields['id'];
            if (!empty($fields['values'])) {
                foreach ($fields['values'] as $key => $options) {
                    if ($options['value'] == $item_dynamic_fields_info['value']) {
                        $fields['data-value'] = $options['label'];
                    }
                }
            }else{
                $fields['data-value'] = $item_dynamic_fields_info['value'];
            }
            $fdata[] = $fields;
        }
        $data['fields'] = $fdata;

        // get contact us details
        $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();

        //highest bid data
        $data['heighest_bid_data'] =  $this->oam->item_heighest_bid_data($item_id,$auction_id);

        //related items
        $item_ids = [];
        $related_items = $this->db->where('item_id !=', $item_id)->get_where('auction_items',['auction_id' => $auction_id])->result_array();
        foreach ($related_items as $key => $v) {
            array_push($item_ids, $v['item_id']);
        }
        if (empty($item_ids)) {
            array_push($item_ids, 0);
        }
        $data['related_items'] = $this->lam->get_live_auction_items($auction_id, 10, 0, $item_ids);
        //get total bid count of this item
        $data['bid_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('bid')->count_all_results();
          
        //count total users view this item
        $data['visit_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('online_auction_item_visits')->count_all_results();
          
        $data['user_id'] ='';
        $data['balance']='N/A';
        $data['hide_auto_bid'] = 'N/A';
        $data['user'] = NULL;   
        $data['fav_item'] = NULL;

        $user = $this->session->userdata('logged_in');
        if ($user) {
            $user_id = $user->id;
            $data['user'] = (array)$user;
            $data['user_id'] = $user_id;

            //check user is blocked or not
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            if ($user['status'] == 0) {
                session_destroy();
                $this->session->set_flashdata('error', $this->lang->line('your_account_blocked'));
                redirect(base_url('home/login'));
                exit;
            }

            //get user balance
            $user_total_deposit = $this->customer_model->user_balance($user_id);
            $data['balance'] = $user_total_deposit['amount'];
            
            $visit_data = array(
                'user_id' => $user_id,
                'auction_id' => $auction_id,
                'item_id' => $item_id
            );

            //check user already visit this item or not
            $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
            if (!$visit_info) {
                $this->db->insert('online_auction_item_visits', $visit_data);
            }
         
            //user is auto bidder or not
            $data['hide_auto_bid'] = $this->db->get_where('bid_auto',['user_id' =>$user_id,'item_id' => $item_id, 'auto_status'=> 'start'])->row_array();

            // User favourite item 
            $data['fav_item'] = $this->db->get_where('favorites_items',['user_id'=> $user_id,'item_id' => $item_id])->row_array();
                 // print_r($u);die();
            $data['auto_bid_data'] = $this->db->get_where('bid_auto', ['user_id'=> $user_id,'item_id' => $item_id,'auction_id' => $auction_id])->row_array();
              
        }
        $data['min_increment'] = $this->db->select('min_increment')->get('auction_live_settings')->row_array();


        $this->template->load_user('auction/online_auction/new/itemDescriptionLive', $data);
        // $this->template->load_user('auction/online_auction/live_online_detail', $data);
    }

    public function live_online_auction_view($auction_id)
    {
        if(empty($auction_id)) {
            show_404();
            exit;
        }

        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        
        // Return URL functionality
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ret = strtr(rawurlencode($actual_link), $revert);
        $data['rurl'] = $ret;

        $data['new'] = 'new';
        $data['auction_id'] = $auction_id;
        $data['auction'] = $this->db->get_where('auctions', ['id' => $data['auction_id']])->row_array();

        $last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id]);
        if($last_bid->num_rows() > 0){
            $last_bid = $last_bid->row_array();
            $item_id = $last_bid['item_id'];
            $data['item_id'] = $item_id;
            $data['last_bid'] = $last_bid;
        }else{
            $message = $this->lang->line('no_auction_available_try_again_later')." <a href='".base_url()."'> ".$this->lang->line('go_to_home')."</a>";
            $this->session->set_flashdata('error', $message);
            redirect(base_url('search/'.$data['auction']['category_id']));
            //show_error($message, 404, $heading = 'Live Auction');
        }

        //auction item details
        $data['item'] = $this->oam->get_items_details($data['item_id'], $data['auction_id']);
        $data['item_category_fields'] = $this->db->get_where('item_category_fields', ['category_id' => $data['auction']['category_id']])->result_array();
        $data['category_id'] = $data['auction']['category_id'];
        $data['item_category'] = $this->db->get_where('item_category', ['id' => $data['auction']['category_id']])->row_array();

        //items category dynamic fields
        $datafields = $this->oam->fields_data($data['auction']['category_id']);
        $fdata = array();
        foreach ($datafields as $key => $fields)
        {
            $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id,$fields['id']);
            $fields['values'] = json_decode($fields['values'],true);  
            $fields['data-id'] = $fields['id'];
            if (!empty($fields['values'])) {
                foreach ($fields['values'] as $key => $options) {
                    if ($options['value'] == $item_dynamic_fields_info['value']) {
                        $fields['data-value'] = $options['label'];
                    }
                }
            }else{
                $fields['data-value'] = $item_dynamic_fields_info['value'];
            }
            $fdata[] = $fields;
        }
        $data['fields'] = $fdata;

        // get contact us details
        $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();

        //highest bid data
        //$data['heighest_bid_data'] =  $this->oam->item_heighest_bid_data($item_id,$auction_id);

        //related items
        $item_ids = [];
        $related_items = $this->db->where('item_id !=', $item_id)->limit(10)->get_where('auction_items',['auction_id' => $auction_id])->result_array();
        foreach ($related_items as $key => $v) {
            array_push($item_ids, $v['item_id']);
        }
        $data['related_items'] = $this->oam->get_online_auction_items($auction_id, 10, 0, $item_ids);

        //bid buttons from admin panel
        $data['auction_live_settings'] = $this->db->get('auction_live_settings')->row_array();

        //get total bid count of this item
        //$data['bid_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('bid')->count_all_results();
          
        //count total users view this item
        //$data['visit_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('online_auction_item_visits')->count_all_results();
        
        $data['user_id'] ='';
        $data['balance']='N/A';
        $data['hide_auto_bid'] = 'N/A';
        $data['user'] = NULL;
        $data['fav_item'] = NULL;
        //live video link
        $lvl = $this->db->get('auction_live_settings')->row_array();
        $data['lvl'] = $lvl['live_video_link'];

        $user = $this->session->userdata('logged_in');
        if ($user) {
            $user_id = $user->id;
            $data['user'] = (array)$user;
            $data['user_id'] = $user_id;

            //check user is blocked or not
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            if ($user['status'] == 0) {
                session_destroy();
                $this->session->set_flashdata('error', $this->lang->line('your_account_blocked'));
                redirect(base_url('home/login'));
                exit;
            }

            //get user balance
            $user_total_deposit = $this->customer_model->user_balance($user_id);
            $data['balance'] = $user_total_deposit['amount'];

            $percentage_amount = $this->db->get_where('settings', ['code_key' => 'p_amount'])->row_array();
            $data['percentage_amount'] = $percentage_amount['value'];
        
            $data['max_bid_limit'] = (float)$data['percentage_amount'] * (float)$data['balance'];
            
            $visit_data = array(
                'user_id' => $user_id,
                'auction_id' => $auction_id,
                'item_id' => $item_id
            );

            //check user already visit this item or not
            $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
            if (!$visit_info) {
                $this->db->insert('online_auction_item_visits', $visit_data);
            }
         
            //user is auto bidder or not
            //$data['hide_auto_bid'] = $this->db->get_where('bid_auto',['user_id' =>$user_id,'item_id' => $item_id, 'auto_status'=> 'start'])->row_array();

            // User favourite item 
            //$data['fav_item'] = $this->db->get_where('favorites_items',['user_id'=> $user_id,'item_id' => $item_id])->row_array();
                 // print_r($u);die();
              
        }

        //$data['limit'] = $this->db->get_where('auction_items',['auction_id'=>$live_auction_id])->result_array();

        //print_r($data);die();
        $this->template->load_user('auction/online_auction/new/liveOnline',$data);
    }

    public function item_detail($auction_id='',$item_id='')
    {
        if(empty($auction_id) || empty($item_id)){
            show_404();
            exit;
        }

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        
        // Return URL functionality
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ret = strtr(rawurlencode($actual_link), $revert);
        $data['rurl'] = $ret;
        $data['new'] = 'new';
        $data['auction_id'] = $auction_id;
        $data['item_id'] = $item_id;

        // Get online auctions
        $data['online_auctions'] = $this->oam->get_online_auctions();

        //auction item details
        $data['item'] = $this->oam->get_items_details($item_id,$auction_id);
        $data['category_id'] = $data['item']['category_id'];
        $data['category'] = $this->db->get_where('item_category', ['id' => $data['category_id']])->row_array();
        // print_r($data['category']);die();

        //items category dynamic fields
        $datafields = $this->oam->fields_data($data['item']['category_id']);
        $fdata = array();
        foreach ($datafields as $key => $fields)
        {
            $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id,$fields['id']);
            if (!empty($item_dynamic_fields_info)) {
                $fields['values'] = json_decode($fields['values'],true);  
                $fields['data-id'] = $fields['id'];
                if (!empty($fields['values'])) {
                    foreach ($fields['values'] as $key => $options) {
                        if ($options['value'] == $item_dynamic_fields_info['value']) {
                            $fields['data-value'] = $options['label'];
                        }
                    }
                }else{
                    $fields['data-value'] = $item_dynamic_fields_info['value'];
                }
                $fdata[] = $fields;
            }
        }
        $data['fields'] = $fdata;

        // get contact us details
        $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();

        //highest bid data
        $data['heighest_bid_data'] =  $this->oam->item_heighest_bid_data($item_id,$auction_id);

        //related items
        $item_ids = [];
        $related_items = $this->db->where('item_id !=', $item_id)->get_where('auction_items',['auction_id' => $auction_id])->result_array();
        if (!empty($related_items)) {
            foreach ($related_items as $key => $v) {
                array_push($item_ids, $v['item_id']);
            }
            if (empty($item_ids)) {
                array_push($item_ids, 0);
            }
            $data['related_items'] = $this->oam->get_online_auction_items($auction_id,10,0, $item_ids); 
        }else{
            $data['related_items'] =array();
        }      
        //get total bid count of this item
        $data['bid_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('bid')->count_all_results();
          
        //count total users view this item
        $data['visit_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('online_auction_item_visits')->count_all_results();
          
        $data['user_id'] ='';
        $data['balance']='N/A';
        $data['hide_auto_bid'] = 'N/A';
        $data['user'] = NULL;   
        $data['fav_item'] = NULL;

        $user = $this->session->userdata('logged_in');
        if ($user) {
            $user_id = $user->id;
            $data['user'] = (array)$user;
            $data['user_id'] = $user_id;

            //check user is blocked or not
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            if ($user['status'] == 0) {
                session_destroy();
                $this->session->set_flashdata('error', $this->lang->line('your_account_blocked'));
                redirect(base_url('home/login'));
                exit;
            }

            //get user balance
            $user_total_deposit = $this->customer_model->user_balance($user_id);
            $data['balance'] = $user_total_deposit['amount'];
            
            $visit_data = array(
                'user_id' => $user_id,
                'auction_id' => $auction_id,
                'item_id' => $item_id
            );

            //check user already visit this item or not
            $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
            if (!$visit_info) {
                $this->db->insert('online_auction_item_visits', $visit_data);
            }
         
            //user is auto bidder or not
            $data['hide_auto_bid'] = $this->db->get_where('bid_auto',['user_id' =>$user_id,'item_id' => $item_id, 'auto_status'=> 'start'])->row_array();

            // User favourite item 
            $data['fav_item'] = $this->db->get_where('favorites_items',['user_id'=> $user_id,'item_id' => $item_id, 'auction_id' => $auction_id])->row_array();
                 // print_r($u);die();
              
        }

        // print_r($data['item']);die();

        //$this->template->load_user('auction/online_auction/item_detail_new', $data);
        $this->template->load_user('auction/online_auction/new/item-detail', $data);
    }

    public function do_favt()
    {
        $data = $this->input->post();
        if($data){
            //print_r($data);
            $user = $this->session->userdata('logged_in');
            if($user){
                $alread_favt = $this->db->get_where('favorites_items', ['user_id' => $user->id, 'item_id' => $data['item_id'], 'auction_id' => $data['auction_id']]);
                if ($alread_favt->num_rows() > 0) {
                    $this->db->delete('favorites_items', ['user_id' => $user->id, 'item_id' => $data['item_id'], 'auction_id' => $data['auction_id']]);
                    $msg = 'remove_heart';
                }else{
                    //print_r($user);
                    $this->db->insert('favorites_items', ['user_id' => $user->id, 'item_id' => $data['item_id'], 'auction_id' => $data['auction_id'], 'auction_item_id' => $data['auction_item_id']]);
                    $msg = 'do_heart';
                }
                echo $msg;
            }else{
                // $this->session->set_flashdata('error', $this->lang->line('please_login_first_to_item_into_favorite'));
                echo '0';
            }
        }
    }

    public function ai_load_more()
    {
        $data = $this->input->post();
        if($data){
            $data = array_filter($data);
            if (isset($data['auction_id'])) {
                $auction_id = $data['auction_id'];
                unset($data['auction_id']);
            } else {
                $auction_id = '0';
            }
            if (isset($data['search'])) {
                $search = $data['search'];
                unset($data['search']);
            }
            $order_by = '';
            if (isset($data['online_sort_by'])) {
                $order_by = $data['online_sort_by'];
                unset($data['online_sort_by']);
            }
            if (isset($data['min'])) {
                $min = $data['min'];
                unset($data['min']);
            }
            if (isset($data['max'])) {
                $max = $data['max'];
                unset($data['max']);
            }

            if (isset($data['make'])) {
                $make = $data['make'];
                unset($data['make']);
            }
            if (isset($data['model'])) {
                $model = $data['model'];
                unset($data['model']);
            }
            if (isset($data['specification'])) {
                $specification = $data['specification'];
                unset($data['specification']);
            }

            if (isset($data['min_year'])) {
                $min_year = $data['min_year'];
                unset($data['min_year']);
            }
            if (isset($data['max_year'])) {
                $max_year = $data['max_year'];
                unset($data['max_year']);
            }

            if (isset($data['min_milage'])) {
                $min_milage = $data['min_milage'];
                unset($data['min_milage']);
            }

            if (isset($data['max_milage'])) {
                $max_milage = $data['max_milage'];
                unset($data['max_milage']);
            }
            if (isset($data['milage_type'])) {
                $milage_type = $data['milage_type'];
                unset($data['milage_type']);
            }

            if (isset($data['lot_no'])) {
                $lot_no = $data['lot_no'];
                unset($data['lot_no']);
            }
             
            $counter = 0;
            foreach ($data as $key => $value){
                if (!empty($value)) {
                    $counter++;
                }
            }
            if ($counter > 0) {
                $item_ids = [];
                foreach ($data as $key => $value) {
                    $this->db->select('item_id');
                    $this->db->from('item_fields_data');
                    $this->db->where('fields_id', $key);
                    $this->db->where_in('value', $value);
                    $query = $this->db->get();
                    $query = $query->result_array();
                    foreach ($query as $k => $v) {
                        array_push($item_ids, $v['item_id']);
                    }
                    $this->db->reset_query();
                }
            }
            $this->db->reset_query();

            if (isset($search) || isset($min_year) || isset($max_year) || isset($make) || isset($model) || isset($specification) || isset($lot_no)) {
                $item_ids3 = [];
                $this->db->select('item.id');
                $this->db->from('item');
                $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                $this->db->join('auction_items', 'item.id = auction_items.item_id', 'LEFT');
                $where = '';
                if (isset($search) && !empty($search)) {
                    $language = $this->language;
                    $where .= ' JSON_EXTRACT(item.name, "$.'.$language.'") like "%'.$search.'%" or price like "%'.$search.'%" or JSON_EXTRACT(item.detail, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_makes.title, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_models.title, "$.'.$language.'") like "%'.$search.'%" ';
                }
                if (isset($min_year) && !empty($min_year)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= " item.year >= ".$min_year;
                }
                if (isset($max_year) && !empty($max_year)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= " item.year <= ".$max_year;
                }
                if (isset($make) && !empty($make)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item_makes.id = ".$make;
                }
                if (isset($model) && !empty($model)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item_models.id = ".$model;
                }
                if (isset($specification) && !empty($specification)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item.specification = '".$specification."'";
                }
                if (isset($lot_no) && !empty($lot_no)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "auction_items.order_lot_no = '".$lot_no."'";
                }

                $this->db->where($where);
                $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids3, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids3)) {
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids3);
                } else {
                    $item_ids = $item_ids3;
                }
                unset($item_ids3);
            // print_r($item_ids);die();
            }

            if (isset($min) || isset($max)) {
                $item_ids1 = [];
                $this->db->select('item.id,auction_items.auction_id');
                $this->db->from('item');
                $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                if(isset($min)){
                    $this->db->where('item.price >=', $min);
                }
                if(isset($max)){
                    $this->db->where('price <=', $max);
                }
                if(isset($milage_type)){
                    $this->db->where('item.mileage_type =', $milage_type);
                }
                $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids1, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids1)) {
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids1);
                } else {
                    $item_ids = $item_ids1;
                }
            }

            if (isset($min_milage) || isset($max_milage)) {
                $item_ids2 = [];
                $this->db->select('item.id,auction_items.auction_id');
                $this->db->from('item');
                $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                if(isset($min_milage)){
                    $this->db->where('item.mileage >=', $min_milage);
                }
                if(isset($max_milage)){
                    $this->db->where('item.mileage <=', $max_milage);
                }
                if(isset($milage_type)){
                    $this->db->where('item.mileage_type =', $milage_type);
                }
                $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids2, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids2)) {
                // print_r($item_ids2);
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids2);
                } else {
                    $item_ids = $item_ids2;
                }
            }
            if (!isset($item_ids)) {
                $item_ids = array();
            }
            //total records
            $total = count($this->oam->get_online_auction_items($auction_id, 0, 0, $item_ids));
            $limit = 12;


            $config['base_url'] = '#';
            $config['total_rows'] = $total;
            $config['per_page'] = $limit;
            // $config['reuse_query_string'] = FALSE;
            // $config["uri_segment"] = 4;
            $config['full_tag_open'] = '<ul class="list link pagination">';
            $config['full_tag_close'] = '</ul">';

            $config['next_tag_open'] = '<li class="pagination_link">';
            $config['next_tag_close'] = '</li>';

            $config['prev_tag_open'] = '<li class="pagination_link">';
            $config['prev_tag_close'] = '</li>';

            $config['last_tag_open'] = '<li class="link pagination_link">';
            $config['last_tag_close'] = '</li>';

            $config['first_tag_open'] = '<li class="link pagination_link">';
            $config['first_tag_close'] = '</li>';

            $config['next_link'] = 'Next &gt;';
            $config['prev_link'] = '&lt Prev';
            
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
            
            $config['cur_tag_open'] = '<li class="link active"><a>';
            $config['cur_tag_close'] = '</a></li>';
            
            $config['num_tag_open'] = '<li class="link pagination_link">';
            $config['num_tag_close'] = '</li>';
            $config['num_links'] = 2;

           $this->pagination->initialize($config); // pagination created 
           $pagination_links = $this->pagination->create_links();

            //offset records
            $page = $this->uri->segment(4);
            // print_r($page);die();
            if(empty($page)){ 
                $offset = 0;
            }else{ 
                $offset = $page; 
            }

            $auction_items = $this->oam->get_online_auction_items($auction_id,$limit,$offset,$item_ids,$order_by);
            $next_offset = $offset + $limit;
            if(count($auction_items) > 0){
                $items = $this->template->load_user_ajax('auction/online_auction/auction_items', ['auction_items' => $auction_items, 'pagination_links' => $pagination_links]);
                //$media = $this->template->load_user_ajax('media-load-more', ['list' => $list], true);
                // print_r($items);die();
                // $items_string = htmlentities($items);
                $output = json_encode([
                    'status' => 'success',
                    'items' => $items, 
                    'total' => $total, 
                    'offset' => $next_offset
                    ]);
                return print_r($output);
            }else{
                $output = json_encode(['status' => 'failed', 'total' => 0]);
                return print_r($output);
            }
        }
    }

    public function live_auction_items_list()
    {
        $data = $this->input->post();
        if($data){
            $order_by = '';
            $cat_id = $data['category_id'];
            $search = $data['search'];
            if (!empty($data['sort'])) {
                $order_by = $data['sort'];
            }

            $live_auctions = $this->db->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'live','category_id' => $cat_id])->result_array();  
            if ($live_auctions) {
                foreach ($live_auctions as $key => $live_auction) {
                    $item_ids = [];
                    if (isset($search)) {
                        $this->db->select('item.id');
                        $this->db->from('item');
                        $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                        $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                        $language = $this->language;
                        $this->db->where(' JSON_EXTRACT(item.name, "$.'.$language.'") like "%'.$search.'%" or price like "%'.$search.'%" or JSON_EXTRACT(item.detail, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_makes.title, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_models.title, "$.'.$language.'") like "%'.$search.'%" ');

                        // $this->db->where('auction_items.auction_id', $auction_id);
                        $query = $this->db->get();
                        $query = $query->result_array();
                        foreach ($query as $k => $v) {
                            array_push($item_ids, $v['id']);
                        }
                        $this->db->reset_query();
                    }
                    //total records
                    $total = count($this->lam->get_live_auction_items($live_auction['id'], 0, 0, $item_ids));
                    if (isset($data['rows_per_page'])) {
                        $limit = $data['rows_per_page'];
                    } else {
                        $limit = 12;
                    }

                    $config['base_url'] = '#';
                    $config['total_rows'] = $total;
                    $config['per_page'] = $limit;
                    // $config['reuse_query_string'] = FALSE;
                    // $config["uri_segment"] = 4;
                    $live_auction_id = $live_auction['id'];
                    $config['full_tag_open'] = '<ul class="list link pagination">';
                    $config['full_tag_close'] = '</ul">';

                    $config['next_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="live_pagination_link">';
                    $config['next_tag_close'] = '</li>';

                    $config['prev_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="live_pagination_link">';
                    $config['prev_tag_close'] = '</li>';

                    $config['last_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="link live_pagination_link">';
                    $config['last_tag_close'] = '</li>';

                    $config['first_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="link live_pagination_link">';
                    $config['first_tag_close'] = '</li>';

                    $config['next_link'] = 'Next &gt;';
                    $config['prev_link'] = '&lt Prev';
                    
                    $config['first_link'] = '<i data-auc-id="'.$live_auction_id.'" class="fa fa-angle-double-left"></i>';
                    $config['last_link'] = '<i data-auc-id="'.$live_auction_id.'" class="fa fa-angle-double-right"></i>';
                    
                    $config['cur_tag_open'] = '<li class="link active"><a>';
                    $config['cur_tag_close'] = '</a></li>';
                    
                    $config['num_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="link live_pagination_link">';
                    $config['num_tag_close'] = '</li>';
                    $config['num_links'] = 2;

                   $this->pagination->initialize($config); // pagination created 
                   $pagination_links = $this->pagination->create_links();

                    if (isset($data['auction_id']) && $data['auction_id'] == $live_auction['id']) {
                        //offset records
                        $page = $this->uri->segment(4);
                        // print_r($page);die();
                        if(empty($page)){ 
                            $offset = 0; 
                        }else{ 
                            $offset = $page; 
                        }
                    } else {
                        $offset = 0;
                    }

                    $auction_items = $this->lam->get_live_auction_items($live_auction['id'],$limit,$offset,$item_ids,$order_by);
                    // print_r($auction_items);die();
                    $next_offset = $offset + $limit;
                    $live_auctions[$key]['auction_items'] = $auction_items;
                    $live_auctions[$key]['pagination_links'] = $pagination_links;
                }
                if(count($live_auctions) > 0){
                    $items = $this->template->load_user_ajax('auction/online_auction/live_auction_items', ['live_auctions' => $live_auctions]);
                    //$media = $this->template->load_user_ajax('media-load-more', ['list' => $list], true);
                   
                    $output = json_encode([
                        'status' => 'success',
                        'items' => $items, 
                        'total' => $total, 
                        'offset' => $next_offset
                        ]);
                    return print_r($output);
                } else {http://pioneerauction/auction/OnlineAuction/item_detail/1/3
                    $output = json_encode(['status' => 'failed']);
                    return print_r($output);
                }
            } else {
                $output = json_encode(['status' => 'failed']);
                return print_r($output);
            }
        }
    }

    public function close_auction_items_list()
    {
        $data = $this->input->post();
        if($data){
            $data = array_filter($data);
            // print_r($data);die();
            $all_items = array();
            $close_auction_ids = array();
            $total = 0;
            $order_by = '';

            $cat_id = $data['category_id'];
            unset($data['category_id']);
            if (isset($data['auction_id'])) {
                $auction_id = $data['auction_id'];
                unset($data['auction_id']);
            }
            if (isset($data['search'])) {
                $search = $data['search'];
                unset($data['search']);
            }
            $order_by = '';
            if (isset($data['close_sort_by'])) {
                $order_by = $data['close_sort_by'];
                unset($data['close_sort_by']);
            }
            if (isset($data['min'])) {
                $min = $data['min'];
                unset($data['min']);
            }
            if (isset($data['max'])) {
                $max = $data['max'];
                unset($data['max']);
            }

            if (isset($data['make'])) {
                $make = $data['make'];
                unset($data['make']);
            }
            if (isset($data['model'])) {
                $model = $data['model'];
                unset($data['model']);
            }
            if (isset($data['specification'])) {
                $specification = $data['specification'];
                unset($data['specification']);
            }

            if (isset($data['min_year'])) {
                $min_year = $data['min_year'];
                unset($data['min_year']);
            }
            if (isset($data['max_year'])) {
                $max_year = $data['max_year'];
                unset($data['max_year']);
            }

            if (isset($data['min_milage'])) {
                $min_milage = $data['min_milage'];
                unset($data['min_milage']);
            }

            if (isset($data['max_milage'])) {
                $max_milage = $data['max_milage'];
                unset($data['max_milage']);
            }
            if (isset($data['milage_type'])) {
                $milage_type = $data['milage_type'];
                unset($data['milage_type']);
            }

            if (isset($data['lot_no'])) {
                $lot_no = $data['lot_no'];
                unset($data['lot_no']);
            }

            if ($this->session->userdata('logged_in')) {
                $u_id = $this->session->userdata('logged_in')->id;
                $close_auctions = $this->db->where("FIND_IN_SET('".$u_id."', close_auction_users)")->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'closed','category_id' => $cat_id])->result_array(); 
                if ($close_auctions) {
                    $sum_item_ids = array();
                    foreach ($close_auctions as $key => $close_auction) {
                        $counter = 0;
                        foreach ($data as $key1 => $value){
                            if (!empty($value)) {
                                $counter++;
                            }
                        }
                        if ($counter > 0) {
                            $item_ids = [];
                            foreach ($data as $key2 => $value) {
                                $this->db->select('item_id');
                                $this->db->from('item_fields_data');
                                $this->db->where('fields_id', $key);
                                $this->db->where_in('value', $value);
                                $query = $this->db->get();
                                $query = $query->result_array();
                                foreach ($query as $k => $v) {
                                    array_push($item_ids, $v['item_id']);
                                }
                                $this->db->reset_query();
                            }
                        }
                        $this->db->reset_query();

                        $close_auction_ids[] = $close_auction['id'];
                        if (isset($search) || isset($min_year) || isset($max_year) || isset($make) || isset($model) || isset($specification) || isset($lot_no)) {
                            $item_ids3 = array();
                            $this->db->select('item.id');
                            $this->db->from('item');
                            $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                            $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                            $this->db->join('auction_items', 'item.id = auction_items.item_id', 'LEFT');
                            $where = '';
                            if (isset($search) && !empty($search)) {
                                $language = $this->language;
                                $where .= ' JSON_EXTRACT(item.name, "$.'.$language.'") like "%'.$search.'%" or price like "%'.$search.'%" or JSON_EXTRACT(item.detail, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_makes.title, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_models.title, "$.'.$language.'") like "%'.$search.'%" ';
                            }
                            if (isset($min_year) && !empty($min_year)) {
                                if (!empty($where)) {
                                    $where .= " AND ";
                                }
                                $where .= " item.year >= ".$min_year;
                            }
                            if (isset($max_year) && !empty($max_year)) {
                                if (!empty($where)) {
                                    $where .= " AND ";
                                }
                                $where .= " item.year <= ".$max_year;
                            }
                            if (isset($make) && !empty($make)) {
                                if (!empty($where)) {
                                    $where .= " AND ";
                                }
                                $where .= "item_makes.id = ".$make;
                            }
                            if (isset($model) && !empty($model)) {
                                if (!empty($where)) {
                                    $where .= " AND ";
                                }
                                $where .= "item_models.id = ".$model;
                            }
                            if (isset($specification) && !empty($specification)) {
                                if (!empty($where)) {
                                    $where .= " AND ";
                                }
                                $where .= "item.specification = '".$specification."'";
                            }
                            if (isset($lot_no) && !empty($lot_no)) {
                                if (!empty($where)) {
                                    $where .= " AND ";
                                }
                                $where .= "auction_items.order_lot_no = '".$lot_no."'";
                            }

                            $this->db->where($where);
                            // $this->db->where('auction_items.auction_id', $auction_id);
                            $query = $this->db->get();
                            $query = $query->result_array();
                            foreach ($query as $k1 => $v) {
                                array_push($item_ids3, $v['id']);
                            }
                            $this->db->reset_query();
                        }
                        if (isset($item_ids3)) {
                            if (isset($item_ids)) {
                                $item_ids = array_intersect($item_ids,$item_ids3);
                            } else {
                                $item_ids = $item_ids3;
                            }
                            unset($item_ids3);
                        }
                        if (isset($min) || isset($max)) {
                            $item_ids1 = [];
                            $this->db->select('item.id,auction_items.auction_id');
                            $this->db->from('item');
                            $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                            if(isset($min)){
                                $this->db->where('item.price >=', $min);
                            }
                            if(isset($max)){
                                $this->db->where('price <=', $max);
                            }
                            if(isset($milage_type)){
                                $this->db->where('item.mileage_type =', $milage_type);
                            }
                            $this->db->where('auction_items.auction_id', $close_auction['id']);
                            $query = $this->db->get();
                            $query = $query->result_array();
                            foreach ($query as $key => $v) {
                                array_push($item_ids1, $v['id']);
                            }
                            $this->db->reset_query();
                        }
                        if (isset($item_ids1)) {
                            if (isset($item_ids)) {
                                $item_ids = array_intersect($item_ids,$item_ids1);
                            } else {
                                $item_ids = $item_ids1;
                            }
                        }

                        if (isset($min_milage) || isset($max_milage)) {
                            $item_ids2 = [];
                            $this->db->select('item.id,auction_items.auction_id');
                            $this->db->from('item');
                            $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                            if(isset($min_milage)){
                                $this->db->where('item.mileage >=', $min_milage);
                            }
                            if(isset($max_milage)){
                                $this->db->where('item.mileage <=', $max_milage);
                            }
                            if(isset($milage_type)){
                                $this->db->where('item.mileage_type =', $milage_type);
                            }
                            $this->db->where('auction_items.auction_id', $close_auction['id']);
                            $query = $this->db->get();
                            $query = $query->result_array();
                            foreach ($query as $key => $v) {
                                array_push($item_ids2, $v['id']);
                            }
                            $this->db->reset_query();
                        }
                        if (isset($item_ids2)) {
                            // print_r($item_ids2);
                            if (isset($item_ids)) {
                                $item_ids = array_intersect($item_ids,$item_ids2);
                            } else {
                                $item_ids = $item_ids2;
                            }
                        }
                        if (isset($item_ids)) {
                            if (empty($item_ids)) {
                                array_push($item_ids, '0');
                            }
                        } else {
                            $item_ids = array();
                        }


                        // if (isset($item_ids) || empty($item_ids)) {
                        //     array_push($item_ids, '0');
                        // }
                        // if (!isset($item_ids)) {
                        //     $item_ids = array();
                        // }
                        $sub_total = count($this->lam->get_live_auction_items($close_auction['id'], 0, 0, $item_ids));
                        $total = $total + $sub_total;
                        $sum_item_ids = array_merge(
                            array_intersect($sum_item_ids, $item_ids),
                            array_diff($sum_item_ids, $item_ids),     
                            array_diff($item_ids, $sum_item_ids)      
                        );
                        unset($item_ids);
                    }
                    $limit = 12;
                    // print_r($total);
                    //total records
                    if (isset($data['auction_id']) && $data['auction_id'] == $close_auction['id']) {
                        //offset records
                        $page = $this->uri->segment(4);
                        // print_r($page);die();
                        if(empty($page)){ 
                            $offset = 0; 
                        }else{ 
                            $offset = $page; 
                        }
                    } else {
                        $offset = 0;
                    }
                    $auction_items = $this->lam->get_close_auction_items($close_auction_ids, $limit, $offset, $sum_item_ids, $order_by);

                    // print_r($auction_items);die();
                    $config['base_url'] = '#';
                    $config['total_rows'] = $total;
                    $config['per_page'] = $limit;
                    // $config['reuse_query_string'] = FALSE;
                    // $config["uri_segment"] = 4;
                    $config['full_tag_open'] = '<ul class="list link pagination">';
                    $config['full_tag_close'] = '</ul">';

                    $config['next_tag_open'] = '<li class="close_pagination_link">';
                    $config['next_tag_close'] = '</li>';

                    $config['prev_tag_open'] = '<li class="close_pagination_link">';
                    $config['prev_tag_close'] = '</li>';

                    $config['last_tag_open'] = '<li class="link close_pagination_link">';
                    $config['last_tag_close'] = '</li>';

                    $config['first_tag_open'] = '<li class="link close_pagination_link">';
                    $config['first_tag_close'] = '</li>';

                    $config['next_link'] = 'Next &gt;';
                    $config['prev_link'] = '&lt Prev';
                    
                    $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
                    $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
                    
                    $config['cur_tag_open'] = '<li class="link active"><a>';
                    $config['cur_tag_close'] = '</a></li>';
                    
                    $config['num_tag_open'] = '<li class="link close_pagination_link">';
                    $config['num_tag_close'] = '</li>';
                    $config['num_links'] = 2;

                    $this->pagination->initialize($config); // pagination created 
                    $pagination_links = $this->pagination->create_links();

                    
                    $next_offset = $offset + $limit;
                    $live_auctions[$key]['auction_items'] = $auction_items;
                    $live_auctions[$key]['pagination_links'] = $pagination_links;
                    if(count($auction_items) > 0){
                        $items = $this->template->load_user_ajax('auction/online_auction/auction_items', ['auction_items' => $auction_items, 'pagination_links' => $pagination_links]);
                        //$media = $this->template->load_user_ajax('media-load-more', ['list' => $list], true);
                       
                        $output = json_encode([
                            'status' => 'success',
                            'items' => $items, 
                            'total' => $total, 
                            'offset' => $next_offset
                            ]);
                        return print_r($output);
                    } else {
                        $output = json_encode(['status' => 'failed', 'total' => $total]);
                        return print_r($output);
                    }
                } else {
                    $output = json_encode(['status' => 'failed', 'msg' => 'hide_auction']);
                    return print_r($output);
                }
            } else {
                $output = json_encode(['status' => 'failed', 'msg' => 'hide_auction']);
                return print_r($output);
            }
        }
    }

    public function live_auction_items_pagination()
    {
        $data = $this->input->post();
        if($data){
            $data = array_filter($data);
            $auction_id = $data['auction_id'];
            unset($data['auction_id']);
            if (isset($data['search'])) {
                $search = $data['search'];
                unset($data['search']);
            }
            $order_by = '';
            if (isset($data['live_sort_by'])) {
                $order_by = $data['live_sort_by'];
                unset($data['live_sort_by']);
            }
            if (isset($data['min'])) {
                $min = $data['min'];
                unset($data['min']);
            }
            if (isset($data['max'])) {
                $max = $data['max'];
                unset($data['max']);
            }
            
            if (isset($data['make'])) {
                $make = $data['make'];
                unset($data['make']);
            }
            if (isset($data['model'])) {
                $model = $data['model'];
                unset($data['model']);
            }
            if (isset($data['specification'])) {
                $specification = $data['specification'];
                unset($data['specification']);
            }

            if (isset($data['min_year'])) {
                $min_year = $data['min_year'];
                unset($data['min_year']);
            }
            if (isset($data['max_year'])) {
                $max_year = $data['max_year'];
                unset($data['max_year']);
            }

            if (isset($data['min_milage'])) {
                $min_milage = $data['min_milage'];
                unset($data['min_milage']);
            }

            if (isset($data['max_milage'])) {
                $max_milage = $data['max_milage'];
                unset($data['max_milage']);
            }
            if (isset($data['milage_type'])) {
                $milage_type = $data['milage_type'];
                unset($data['milage_type']);
            }

            if (isset($data['lot_no'])) {
                $lot_no = $data['lot_no'];
                unset($data['lot_no']);
            }
             
            $counter = 0;
            foreach ($data as $key => $value){
                if (!empty($value)) {
                    $counter++;
                }
            }
            if ($counter > 0) {
                $item_ids = [];
                foreach ($data as $key => $value) {
                    $this->db->select('item_id');
                    $this->db->from('item_fields_data');
                    $this->db->where('fields_id', $key);
                    $this->db->where_in('value', $value);
                    $query = $this->db->get();
                    $query = $query->result_array();
                    foreach ($query as $k => $v) {
                        array_push($item_ids, $v['item_id']);
                    }
                    $this->db->reset_query();
                }
            }
            $this->db->reset_query();

            if (isset($search) || isset($min_year) || isset($max_year) || isset($make) || isset($model) || isset($specification) || isset($lot_no)) {
                $item_ids3 = [];
                $this->db->select('item.id');
                $this->db->from('item');
                $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                $where = '';
                if (isset($search) && !empty($search)) {
                    $language = $this->language;
                    $where .= ' JSON_EXTRACT(item.name, "$.'.$language.'") like "%'.$search.'%" or price like "%'.$search.'%" or JSON_EXTRACT(item.detail, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_makes.title, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_models.title, "$.'.$language.'") like "%'.$search.'%" ';
                }
                if (isset($min_year) && !empty($min_year)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= " item.year >= ".$min_year;
                }
                if (isset($max_year) && !empty($max_year)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= " item.year <= ".$max_year;
                }
                if (isset($make) && !empty($make)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item_makes.id = ".$make;
                }
                if (isset($model) && !empty($model)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item_models.id = ".$model;
                }
                if (isset($specification) && !empty($specification)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item.specification = '".$specification."'";
                }
                if (isset($lot_no) && !empty($lot_no)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "auction_items.order_lot_no = '".$lot_no."'";
                }

                $this->db->where($where);
                // $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $k => $v) {
                    array_push($item_ids3, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids3)) {
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids3);
                } else {
                    $item_ids = $item_ids3;
                }
                unset($item_ids3);
            // print_r($item_ids);die();
            }
            if (isset($min) || isset($max)) {
                $item_ids1 = [];
                $this->db->select('item.id,auction_items.auction_id');
                $this->db->from('item');
                $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                if(isset($min)){
                    $this->db->where('item.price >= ', $min);
                }
                if(isset($max)){
                    $this->db->where('price <= ', $max);
                }
                if(isset($milage_type)){
                    $this->db->where('item.mileage_type =', $milage_type);
                }
                $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids1, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids1)) {
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids1);
                } else {
                    $item_ids = $item_ids1;
                }
            }

            if (isset($min_milage) || isset($max_milage)) {
                $item_ids2 = [];
                $this->db->select('item.id,auction_items.auction_id');
                $this->db->from('item');
                $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                if(isset($min_milage)){
                    $this->db->where('item.mileage >= ', $min_milage);
                }
                if(isset($max_milage)){
                    $this->db->where('item.mileage <= ', $max_milage);
                }
                if(isset($milage_type)){
                    $this->db->where('item.mileage_type = ', $milage_type);
                }
                $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids2, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids2)) {
                // print_r($item_ids2);
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids2);
                } else {
                    $item_ids = $item_ids2;
                }
            }
            if (!isset($item_ids)) {
                $item_ids = array();
            }
            //total records
            $total = count($this->lam->get_live_auction_items($auction_id, 0, 0, $item_ids));
            
            $limit = 12;

            $config['base_url'] = '#';
            $config['total_rows'] = $total;
            $config['per_page'] = $limit;
            // $config['reuse_query_string'] = FALSE;
            // $config["uri_segment"] = 4;
            $live_auction_id = $auction_id;
            $config['full_tag_open'] = '<ul class="list link pagination">';
            $config['full_tag_close'] = '</ul">';

            $config['next_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="live_pagination_link">';
            $config['next_tag_close'] = '</li>';

            $config['prev_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="live_pagination_link">';
            $config['prev_tag_close'] = '</li>';

            $config['last_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="link live_pagination_link">';
            $config['last_tag_close'] = '</li>';

            $config['first_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="link live_pagination_link">';
            $config['first_tag_close'] = '</li>';

            $config['next_link'] = 'Next &gt;';
            $config['prev_link'] = '&lt Prev';
            
            $config['first_link'] = '<i data-auc-id="'.$live_auction_id.'" class="fa fa-angle-double-left"></i>';
            $config['last_link'] = '<i data-auc-id="'.$live_auction_id.'" class="fa fa-angle-double-right"></i>';
            
            $config['cur_tag_open'] = '<li class="link active"><a>';
            $config['cur_tag_close'] = '</a></li>';
            
            $config['num_tag_open'] = '<li data-auc-id="'.$live_auction_id.'" class="link live_pagination_link">';
            $config['num_tag_close'] = '</li>';
            $config['num_links'] = 2;

           $this->pagination->initialize($config); // pagination created 
           $pagination_links = $this->pagination->create_links();

            //offset records
            $page = $this->uri->segment(4);
            // print_r($page);die();
            if(empty($page)){ 
                $offset = 0; 
            }else{ 
                $offset = $page; 
            }
            $auction_items = $this->lam->get_live_auction_items($auction_id,$limit,$offset,$item_ids,$order_by);
            $auction = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();
            $start_time = $auction['start_time'];
            $next_offset = $offset + $limit;
            // $live_auctions[$key]['auction_items'] = $auction_items;
            // $live_auctions[$key]['pagination_links'] = $pagination_links;

            if(count($auction_items) > 0){
                $items = $this->load->view('auction/online_auction/live_auction_items_pagination', ['live_items' => $auction_items, 'pagination_links' => $pagination_links, 'auction' => $auction], true);
                //$media = $this->template->load_user_ajax('media-load-more', ['list' => $list], true);
               
                $output = json_encode([
                    'status' => 'success',
                    'items' => $items, 
                    'total' => $total, 
                    'offset' => $next_offset
                    ]);
                return print_r($output);
            } else {
                $output = json_encode(['status' => 'failed']);
                return print_r($output);
            }
        }
    }

    public function placebid()
    {         
        // if ($this->input->post('bid_limit')) {
        //     $this->autoBid();
        // }
        
        $language = $this->language;
        $data = $this->input->post();
        if($data){
            $auto_bid_msg = '';

            $query = $this->db->query('Select bid.bid_amount, bid.bid_status ,bid.user_id ,item.category_id,item.name,item.year,item.registration_no, users.username, users.email, users.fname  from bid  
               inner join  ( Select max(bid_time) as LatestDate, item_id  from bid  Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id
                   LEFT JOIN item ON item.id = bid.item_id LEFT JOIN users ON users.id = bid.user_id  WHERE bid.item_id = '.$data['item_id'].' AND bid.auction_id = '.$data['auction_id'].';');
                $bid_data =  $query->row_array();
            if (isset($bid_data) && $bid_data['bid_amount'] > $data['current_price']) {
                // $sold_out_item_msg = 'item is sold out';
                $sold_out_item_msg = $this->lang->line('bid_not_placed_try_again');
                echo json_encode(array('result' =>'false','status' => 'bidAmountChanged','msg'=>$sold_out_item_msg ));
                exit(); 
            }
            if (!empty($bid_data['bid_status']) && $bid_data['bid_status'] == 'won') {
                // $sold_out_item_msg = 'item is sold out';
                $sold_out_item_msg = $this->lang->line('sold_out_new');
                echo json_encode(array('result' =>'false','status' => 'soldout','msg'=>$sold_out_item_msg ));
                exit(); 
            }
            $auction_item_data = $this->db->get_where('auction_items', ['item_id' => $data['item_id'],'auction_id' => $data['auction_id']]);
            if ($auction_item_data->num_rows() > 0) {
                $auction_item_data = $auction_item_data->row_array();
                $today = date('Y-m-d H:i:s');
                $date = strtotime($today);
                $bid_end_time = strtotime($auction_item_data['bid_end_time']);
                if ($bid_end_time < $date) {
                    echo json_encode(array('result' =>'false','status' => 'soldout','msg'=> $this->lang->line('item_time_expired')));
                    exit(); 
                }
                if ($auction_item_data['sold_status'] != 'not') {
                    echo json_encode(array('result' =>'false','status' => 'soldout','msg'=> 'Item is sold out.'));
                    exit();
                }
            }
            $user = $this->session->userdata('logged_in');
            if($user){
                $heighest_bids = $this->oam->used_bid_limit($user->id, $data['item_id']);
                $currentBalance = $this->customer_model->user_balance($user->id);
                $new_bid_amount = $bid_data['bid_amount'] + $data['bid_amount'];
                $new_bid_limit = $new_bid_amount;
                $totalBalanceRequiredToPlaceBid = (int)($new_bid_limit) + (!empty($heighest_bids['total_bid']) ? (int)$heighest_bids['total_bid'] : 0);
                $currentBidAmount = $currentBalance['amount']*10;
                // print_r($heighest_bids);
                // echo '<br>';
                // print_r($totalBalanceRequiredToPlaceBid);
                // print_r($currentBidAmount);
                // die();
                // echo '<br>';
                // print_r($currentBalance['amount']*10);die();
                if ($totalBalanceRequiredToPlaceBid > $currentBidAmount) {
                    $refundableBalance = ((int)$currentBidAmount - (int)$heighest_bids['total_bid']);
                    if ($bid_data['user_id'] == $user->id) {
                        $refundableBalance = $refundableBalance - $data['current_price'];
                    }
                    $refundableBalance = ($refundableBalance > 0) ? $refundableBalance : 0;
                    // print_r($refundableBalance);die();
                    // $this->session->set_flashdata('error', $this->lang->line('you_have_reached_your_bid_limit').$refundableBalance);
                    echo json_encode(array('result' =>'false','status' => 'limitExceed','msg'=> $this->lang->line('you_have_reached_your_bid_limit').$refundableBalance.'.'));
                    exit();
                } else {
                    $this->load->model('email/Email_model', 'email_model');
                    $bid_amount = $this->db->order_by('id', 'desc')->get_where('bid', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id']])->row_array();
                    if (isset($data['bid_limit']) && $bid_amount['user_id'] == $user->id) {
                        $result = false;
                    } else {
                        if (! empty($bid_amount)) {
                            $bid_data_to_insert['bid_amount'] = $bid_amount['bid_amount'] + $data['bid_amount'];
                        }else{
                            $a_item = $this->db->get_where('auction_items', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id']])->row_array();
                            $bid_data_to_insert['bid_amount'] = $a_item['bid_start_price'] + $data['bid_amount'];
                        }
                        $bid_data_to_insert['start_price'] = $data['start_price'];
                        $bid_data_to_insert['end_price'] = $bid_data_to_insert['bid_amount'];
                        $bid_data_to_insert['date'] = date('Y-m-d');
                        $bid_data_to_insert['user_id'] = $user->id;
                        $bid_data_to_insert['item_id'] = $data['item_id'];
                        $bid_data_to_insert['auction_id'] = $data['auction_id'];
                        $bid_data_to_insert['buyer_id'] = $user->id;
                        $bid_data_to_insert['seller_id'] = $data['seller_id'];
                        $bid_data_to_insert['bid_status'] = 'pending';

                        // print_r($data);die();
                        $result = $this->db->insert('bid', $bid_data_to_insert);
                    }

                    if (isset($data['bid_limit'])) {
                        $auto_bid = array();
                        $auto_bid['item_id'] = $data['item_id'];
                        $auto_bid['auction_item_id'] = $data['auction_item_id'];
                        $auto_bid['auction_id'] = $data['auction_id'];
                        $user = $this->session->userdata('logged_in');
                        $auto_bid['user_id'] = $user->id;
                        $auto_bid['bid_limit'] = $data['bid_limit'];
                        $auto_bid['bid_increment'] = $data['bid_amount'];
                        $auto_bid['auto_status'] = 'start';
                        $auto_bid['date'] = date('Y-m-d H:i:s');
                       
                        if ($auto_bid)
                        {
                            $users = $this->db->insert('bid_auto',$auto_bid);
                            $auto_bid_msg = 'true';
                        }
                    }

                    if ($result) {
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
                        $username = $this->session->userdata('logged_in')->username;
                        $push['item_id'] = $data['item_id'];
                        $push['user_id'] = $user->id;
                        $push['buyer_id'] = $user->id;
                        $push['username'] = $username;
                        $push['bid_amount'] = $bid_data_to_insert['bid_amount'];
                        $pusher->trigger('ci_pusher', 'my-event', $push);
                        if (!empty($bid_amount) && $bid_amount['user_id'] != $user->id) {
                            if (!empty($bid_data)) {
                                //Send eamil
                                $auto_bidder = $this->db->get_where('bid_auto', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id'],'user_id' =>$bid_data['user_id']])->row_array();
                                if (!$auto_bidder) {
                                    $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                    $vars = array(
                                        '{username}' => $bid_data['fname'],
                                        '{item_name}' => '<a href="'.$item_link.'">'.ucwords(json_decode($bid_data['name'])->english).'</a>',
                                        '{year}' => $bid_data['year'],
                                        '{bid_price}' => $bid_amount['bid_amount'],
                                        '{lot_number}' => $auction_item_data['order_lot_no'],
                                        '{registration_number}' => $bid_data['registration_no'],
                                        '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                    );
                                    $email = $bid_data['email'];
                                    $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                }
                            }
                        }

                        //goto loop
                        auto_bid_loop:
                        $j = 0;
                        $auto_bidders = $this->db->get_where('bid_auto', ['item_id' => $data['item_id'], 'auction_id' => $data['auction_id'], 'auto_status' => 'start'])->result_array();
                        // print_r($auto_bidders);die();
                        if (!empty($auto_bidders)) {
                            foreach ($auto_bidders as $key => $value) {
                                
                                $bid_price = $bid_data_to_insert['bid_amount'] + $value['bid_increment'];
                                if ($value['bid_limit'] >= $bid_price) {
                                    $bid_data_to_insert['user_id'] = $value['user_id'];
                                    $bid_data_to_insert['buyer_id'] = $value['user_id'];
                                    $bid_amount = $this->db->order_by('id', 'desc')->get_where('bid', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id']])->row_array();
                                    if ($value['user_id'] != $bid_amount['user_id']) {
                                        if (! empty($bid_amount)) {
                                            $bid_data_to_insert['bid_amount'] = $bid_amount['bid_amount'] + $value['bid_increment'];
                                        }else{
                                            $a_item = $this->db->get_where('auction_items', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id']])->row_array();
                                            $bid_data_to_insert['bid_amount'] = $a_item['bid_start_price'] + $value['bid_increment'];
                                        }
                                        $j++;
                                        $bid_data_to_insert['end_price'] = $data['bid_amount'];
                                        $bid_data_to_insert['date'] = date('Y-m-d');
                                        $bid_data_to_insert['bid_status'] = 'pending';
                                        $result = $this->db->insert('bid', $bid_data_to_insert);
                                        if ($result) {
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
                                            $userdata = $this->db->select('username')->get_where('users', ['id' => $value['user_id']])->row_array();
                                            $push['item_id'] = $data['item_id'];
                                            $push['username'] = $userdata['username'];
                                            $push['user_id'] = $bid_data_to_insert['user_id'];
                                            $push['buyer_id'] = $user->id;
                                            $push['bid_amount'] = $bid_data_to_insert['bid_amount'];
                                            $pusher->trigger('ci_pusher', 'my-event', $push);
                                        }
                                        $auto_bidder = $this->db->get_where('bid_auto', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id'],'user_id' =>$bid_amount['user_id']])->row_array();
                                        if (!$auto_bidder) {
                                            //Send eamil  
                                            $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                            $out_user = $this->db->select('email,fname')->get_where('users', ['id' => $bid_amount['user_id']])->row_array();
                                            $vars = array(
                                                '{username}' => $out_user['fname'],
                                                '{item_name}' => '<a href="'.$item_link.'">'.ucwords(json_decode($bid_data['name'])->english).'</a>',
                                                '{year}' => $bid_data['year'],
                                                '{bid_price}' => $bid_amount['bid_amount'],
                                                '{lot_number}' => $auction_item_data['order_lot_no'],
                                                '{registration_number}' => $bid_data['registration_no'],
                                                '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                            );
                                            $email = $out_user['email'];
                                            $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                        }
                                    }
                                }else{
                                    $update = $this->db->update('bid_auto', ['auto_status' => 'stop'],['id' => $value['id']] );

                                    //email to auto bidder of out bid
                                    if ($update) {
                                        //Send eamil  
                                        $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                        $out_user = $this->db->select('email,fname')->get_where('users', ['id' => $value['user_id']])->row_array();
                                        $bid_data_name = json_decode($bid_data['name']);
                                        $lan = $this->language;
                                        $vars = array(
                                            '{username}' => $out_user['fname'],
                                            '{item_name}' => '<a href="'.$item_link.'">'.ucwords($bid_data_name->english).'</a>',
                                            '{year}' => $bid_data['year'],
                                            '{bid_price}' => $bid_amount['bid_amount'],
                                            '{lot_number}' => $auction_item_data['order_lot_no'],
                                            '{registration_number}' => $bid_data['registration_no'],
                                            '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                        );
                                        $email = $out_user['email'];
                                        
                                        $email = $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                    }

                                    //pusher
                                    $options = array(
                                        'cluster' => 'ap1',
                                        'useTLS' => true
                                    );
                                    $pusher = new Pusher\Pusher(
                                        $this->config->item('pusher_app_key'),
                                        $this->config->item('pusher_app_secret'),
                                        $this->config->item('pusher_app_id'),
                                        // 'ce5fe3c21179ff69c27a',
                                        // '9144aece744d52060fc7',
                                        // '1001119',
                                        $options
                                    );
                                    $push['item_id'] = $data['item_id'];
                                    $push['user_id'] = $value['user_id'];
                                    $push['status'] = 'stop';
                                    $pusher->trigger('ci_pusher', 'my-event', $push);
                                }
                            }

                        }
                        if ($j >= 1) {
                            goto auto_bid_loop;
                        }
                       
                        $output = json_encode([
                            'status' => 'success',
                            'msg' => $this->lang->line('bid_successfully'),
                            'auto_bid_msg' => $auto_bid_msg,
                            'current_bid' => $bid_data_to_insert['bid_amount']
                        ]);
                    } elseif($users) {
                        $output = json_encode([
                            'status' => 'success',
                            'auto_bid_msg' => $auto_bid_msg
                        ]);
                    } else {
                        $output = json_encode([
                            'status' => 'fail',
                            'msg' => $this->lang->line('bid_failed')
                        ]);
                    }
                }
                return print_r($output);
            }else{
                echo '0';
            }
        } 
    }

    public function online_live_auto_bid()
    {
        $date = date('Y-m-d H:i:s');
        $posted_data =$this->input->post();
        $bid_auto_id = $posted_data['b_a_id'];
        unset($posted_data['b_a_id']);
        $user = $this->session->userdata('logged_in');
        $posted_data['user_id'] = $user->id;
        $posted_data['date'] = $date;
        $posted_data['auto_status'] = 'start';
        // print_r($posted_data);die();
        if ($posted_data)
        {
            if (!empty($bid_auto_id)) {
                $users = $this->db->update('bid_auto',$posted_data, ['id' => $bid_auto_id, 'user_id' => $user->id]);
                echo json_encode(array('success' =>'true','msg'=>'updated'));
            } else {
                $users = $this->db->insert('bid_auto',$posted_data);
                echo json_encode(array('success' =>'true','msg'=>'activated'));
            }
        } else {
            echo json_encode(array('success' =>'false','msg'=>'not activated'));
        }

    }
    public function autoBid()
    {
        $date = date('Y-m-d H:i:s');
        $posted_data =$this->input->post();
        $posted_data['auction_id'] = $posted_data['auction_id'];
        $posted_data['item_id'] = $posted_data['item_id'];
        $posted_data['auction_item_id'] = $posted_data['auction_item_id'];
        $posted_data['date'] = $date;
        $user = $this->session->userdata('logged_in');
        $posted_data['user_id'] = $user->id;
       
        if ($posted_data)
        {
            $users = $this->db->insert('bid_auto',$posted_data);
            echo json_encode(array('msgs' =>'true','msg'=>'activated'));
        }
       // if ($users) {
       //      $options = array(
       //          'cluster' => 'ap1',
       //          'useTLS' => true
       //      );
       //      $pusher = new Pusher\Pusher(
       //           $this->config->item('pusher_app_key'),
       //            $this->config->item('pusher_app_secret'),
       //             $this->config->item('pusher_app_id'),
       //          $options
       //      );
       //      $push['item_id'] = $data['item_id'];
       //      $push['username'] = $userdata['username'];
       //      $push['user_id'] = $data['user_id'];
       //      $push['bid_amount'] = $data['bid_amount'];
       //      $pusher->trigger('ci_pusher', 'my-event', $push);
       //  }
        else
        {
            echo json_encode(array('result' =>'false','msg'=>'not activated'));
        }

    }



    public function cancel_auto_bid()
    {
        $posted_data =$this->input->post();
        $bid_auto_id = $posted_data['b_a_id'];
        $user = $this->session->userdata('logged_in');
        if ($user)
        {
            $users = $this->db->delete('bid_auto', ['id' => $bid_auto_id, 'user_id' => $user->id]);
            echo json_encode(array('success' =>'true','msg'=> $this->lang->line('auto_bid_cancelled_successfully')));
        } else {
            echo json_encode(array('success' =>'false','msg'=> $this->lang->line('failed_cancel_auto_bid')));
        }
    }

    public function apply_filters()
    {
        $data = $this->input->post();
        if($data){
            $data = array_filter($data);
            // print_r($data);die();
            if (isset($data['auction_id'])) {
                $auction_id = $data['auction_id'];
                unset($data['auction_id']);
            } else {
                $auction_id = '0';
            }
            if (isset($data['search'])) {
                $search = $data['search'];
                unset($data['search']);
            }
            $order_by = '';
            if (isset($data['online_sort_by'])) {
                $order_by = $data['online_sort_by'];
                unset($data['online_sort_by']);
            }
            if (isset($data['min'])) {
                $min = $data['min'];
                unset($data['min']);
            }
            if (isset($data['max'])) {
                $max = $data['max'];
                unset($data['max']);
            }

            if (isset($data['make'])) {
                $make = $data['make'];
                unset($data['make']);
            }
            if (isset($data['model'])) {
                $model = $data['model'];
                unset($data['model']);
            }
            if (isset($data['specification'])) {
                $specification = $data['specification'];
                unset($data['specification']);
            }

            if (isset($data['min_year'])) {
                $min_year = $data['min_year'];
                unset($data['min_year']);
            }
            if (isset($data['max_year'])) {
                $max_year = $data['max_year'];
                unset($data['max_year']);
            }

            if (isset($data['min_milage'])) {
                $min_milage = $data['min_milage'];
                unset($data['min_milage']);
            }

            if (isset($data['max_milage'])) {
                $max_milage = $data['max_milage'];
                unset($data['max_milage']);
            }
            if (isset($data['milage_type'])) {
                $milage_type = $data['milage_type'];
                unset($data['milage_type']);
            }

            if (isset($data['lot_no'])) {
                $lot_no = $data['lot_no'];
                unset($data['lot_no']);
            }
             
            $counter = 0;
            foreach ($data as $key => $value){
                if (!empty($value)) {
                    $counter++;
                }
            }
            if ($counter > 0) {
                $item_ids = [];
                foreach ($data as $key => $value) {
                    $this->db->select('item_id');
                    $this->db->from('item_fields_data');
                    $this->db->where('fields_id', $key);
                    $this->db->where_in('value', $value);
                    $query = $this->db->get();
                    $query = $query->result_array();
                    foreach ($query as $k => $v) {
                        array_push($item_ids, $v['item_id']);
                    }
                    $this->db->reset_query();
                }
            }
            $this->db->reset_query();
            if (isset($search) || isset($min_year) || isset($max_year) || isset($make) || isset($model) || isset($specification) || isset($lot_no)) {
                $item_ids3 = [];
                $this->db->select('item.id');
                $this->db->from('item');
                $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                $this->db->join('auction_items', 'item.id = auction_items.item_id', 'LEFT');
                $where = '';
                if (isset($search) && !empty($search)) {
                    $language = $this->language;
                    $where .= ' JSON_EXTRACT(item.name, "$.'.$language.'") like "%'.$search.'%" or price like "%'.$search.'%" or JSON_EXTRACT(item.detail, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_makes.title, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_models.title, "$.'.$language.'") like "%'.$search.'%" ';
                }
                if (isset($min_year) && !empty($min_year)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= " item.year >= ".$min_year;
                }
                if (isset($max_year) && !empty($max_year)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= " item.year <= ".$max_year;
                }
                if (isset($make) && !empty($make)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item_makes.id = ".$make;
                }
                if (isset($model) && !empty($model)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item_models.id = ".$model;
                }
                if (isset($specification) && !empty($specification)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "item.specification = '".$specification."'";
                }
                if (isset($lot_no) && !empty($lot_no)) {
                    if (!empty($where)) {
                        $where .= " AND ";
                    }
                    $where .= "auction_items.order_lot_no = '".$lot_no."'";
                }
                $this->db->where($where);
                $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids3, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids3)) {
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids3);
                } else {
                    $item_ids = $item_ids3;
                }
                unset($item_ids3);
            }

            if (isset($min) || isset($max)) {
                $item_ids1 = [];
                $this->db->select('item.id,auction_items.auction_id');
                $this->db->from('item');
                $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                if(isset($min)){
                    $this->db->where('item.price >= ', $min);
                }
                if(isset($max)){
                    $this->db->where('price <= ', $max);
                }
                if(isset($milage_type)){
                    $this->db->where('item.mileage_type =', $milage_type);
                }
                $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids1, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids1)) {
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids1);
                } else {
                    $item_ids = $item_ids1;
                }
            }

            if (isset($min_milage) || isset($max_milage)) {
                $item_ids2 = [];
                $this->db->select('item.id,auction_items.auction_id');
                $this->db->from('item');
                $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                if(isset($min_milage)){
                    $this->db->where('item.mileage >= ', $min_milage);
                }
                if(isset($max_milage)){
                    $this->db->where('item.mileage <= ', $max_milage);
                }
                if(isset($milage_type)){
                    $this->db->where('item.mileage_type =', $milage_type);
                }
                $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids2, $v['id']);
                }
                $this->db->reset_query();
            }
            if (isset($item_ids2)) {
                // print_r($item_ids2);
                if (isset($item_ids)) {
                    $item_ids = array_intersect($item_ids,$item_ids2);
                } else {
                    $item_ids = $item_ids2;
                }
            }
            // print_r($item_ids);die();
            $total = 0;
            $auction_items = array();
            // if (!empty($item_ids)) {
            if (isset($item_ids) && empty($item_ids)) {
                array_push($item_ids, '0');
            }
            if (!isset($item_ids)){
                $item_ids = array();
            }

            $total = count($this->oam->get_online_auction_items($auction_id, 0, 0,$item_ids));
            
            $limit = 12;


            $config['base_url'] = '#';
            $config['total_rows'] = $total;
            $config['per_page'] = $limit;
            // $config['reuse_query_string'] = FALSE;
            // $config["uri_segment"] = 4;
            $config['full_tag_open'] = '<ul class="list link pagination">';
            $config['full_tag_close'] = '</ul">';

            $config['next_tag_open'] = '<li class="pagination_link">';
            $config['next_tag_close'] = '</li>';

            $config['prev_tag_open'] = '<li class="pagination_link">';
            $config['prev_tag_close'] = '</li>';

            $config['last_tag_open'] = '<li class="link pagination_link">';
            $config['last_tag_close'] = '</li>';

            $config['first_tag_open'] = '<li class="link pagination_link">';
            $config['first_tag_close'] = '</li>';

            $config['next_link'] = 'Next &gt;';
            $config['prev_link'] = '&lt Prev';
            
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
            
            $config['cur_tag_open'] = '<li class="link active"><a>';
            $config['cur_tag_close'] = '</a></li>';
            
            $config['num_tag_open'] = '<li class="link pagination_link">';
            $config['num_tag_close'] = '</li>';
            $config['num_links'] = 2;

            $this->pagination->initialize($config); // pagination created 
            $pagination_links = $this->pagination->create_links();

            //offset records
            $page = $this->uri->segment(4);
            // print_r($page);die();
            if(empty($page)){ 
                $offset = 0; 
            }else{ 
                $offset = $page; 
            }
            $next_offset = $offset + $limit;

                $auction_items = $this->oam->get_online_auction_items($auction_id,$limit,$offset,$item_ids,$order_by);
            // }

            if(count($auction_items) > 0){
                $items = $this->template->load_user_ajax('auction/online_auction/auction_items', ['auction_items' => $auction_items,'pagination_links' => $pagination_links], true);
                $btn = false;
                // $btn = ($total > $next_offset);
                // print_r($btn);die();
                $output = json_encode([
                    'status' => 'success',
                    'items' => $items, 
                    'total' => $total, 
                    'offset' => $next_offset,
                    'btn' => $btn]);
                return print_r($output);
            }else{
                $output = json_encode(['status' => 'failed', 'total' => $total, 'btn' => false]);
                return print_r($output);
            }

        }
    }

    public function apply_filters_live()
    {

        $data = $this->input->post();
        if($data){
            $data = array_filter($data);
            // print_r($data);die();
            if (isset($data['auction_id'])) {
                $auction_id = $data['auction_id'];
                unset($data['auction_id']);
            }
            $category_id = $data['category_id'];
            unset($data['category_id']);

            if (isset($data['search'])) {
                $search = $data['search'];
                unset($data['search']);
            }
            $order_by = '';
            if (isset($data['live_sort_by'])) {
                $order_by = $data['live_sort_by'];
                unset($data['live_sort_by']);
            }
            if (isset($data['min'])) {
                $min = $data['min'];
                unset($data['min']);
            }
            if (isset($data['max'])) {
                $max = $data['max'];
                unset($data['max']);
            }

            if (isset($data['make'])) {
                $make = $data['make'];
                unset($data['make']);
            }
            if (isset($data['model'])) {
                $model = $data['model'];
                unset($data['model']);
            }
            if (isset($data['specification'])) {
                $specification = $data['specification'];
                unset($data['specification']);
            }

            if (isset($data['min_year'])) {
                $min_year = $data['min_year'];
                unset($data['min_year']);
            }
            if (isset($data['max_year'])) {
                $max_year = $data['max_year'];
                unset($data['max_year']);
            }

            if (isset($data['min_milage'])) {
                $min_milage = $data['min_milage'];
                unset($data['min_milage']);
            }

            if (isset($data['max_milage'])) {
                $max_milage = $data['max_milage'];
                unset($data['max_milage']);
            }
            if (isset($data['milage_type'])) {
                $milage_type = $data['milage_type'];
                unset($data['milage_type']);
            }

            if (isset($data['lot_no'])) {
                $lot_no = $data['lot_no'];
                unset($data['lot_no']);
            }

            $live_auctions = $this->db->get_where('auctions', ['expiry_time >= ' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'live','category_id' => $category_id])->result_array();  
            if ($live_auctions) {
                foreach ($live_auctions as $key => $live_auction) {
             
                    $counter = 0;
                    foreach ($data as $k => $value){
                        if (!empty($value)) {
                            $counter++;
                        }
                    }
                    if ($counter > 0) {
                        $item_ids = [];
                        foreach ($data as $keyy => $value) {
                            $this->db->select('item_id');
                            $this->db->from('item_fields_data');
                            $this->db->where('fields_id', $keyy);
                            $this->db->where_in('value', $value);
                            $query = $this->db->get();
                            $query = $query->result_array();
                            foreach ($query as $k => $v) {
                                array_push($item_ids, $v['item_id']);
                            }
                            $this->db->reset_query();
                        }
                    }
                    $this->db->reset_query();
                    // print_r($item_ids);
                    if (isset($search) || isset($min_year) || isset($max_year) || isset($make) || isset($model) || isset($specification) || isset($lot_no)) {
                        $item_ids3 = [];
                        $this->db->select('item.id');
                        $this->db->from('item');
                        $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                        $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                        $this->db->join('auction_items', 'item.id = auction_items.item_id', 'LEFT');
                        $where = '';
                        if (isset($search) && !empty($search)) {
                            $language = $this->language;
                            $where .= ' JSON_EXTRACT(item.name, "$.'.$language.'") like "%'.$search.'%" or price like "%'.$search.'%" or JSON_EXTRACT(item.detail, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_makes.title, "$.'.$language.'") like "%'.$search.'%" or JSON_EXTRACT(item_models.title, "$.'.$language.'") like "%'.$search.'%" ';
                        }
                        if (isset($min_year) && !empty($min_year)) {
                            if (!empty($where)) {
                                $where .= " AND ";
                            }
                            $where .= " item.year >= ".$min_year;
                        }
                        if (isset($max_year) && !empty($max_year)) {
                            if (!empty($where)) {
                                $where .= " AND ";
                            }
                            $where .= " item.year <= ".$max_year;
                        }
                        if (isset($make) && !empty($make)) {
                            if (!empty($where)) {
                                $where .= " AND ";
                            }
                            $where .= "item_makes.id = ".$make;
                        }
                        if (isset($model) && !empty($model)) {
                            if (!empty($where)) {
                                $where .= " AND ";
                            }
                            $where .= "item_models.id = ".$model;
                        }
                        if (isset($specification) && !empty($specification)) {
                            if (!empty($where)) {
                                $where .= " AND ";
                            }
                            $where .= "item.specification = '".$specification."'";
                        }
                        if (isset($lot_no) && !empty($lot_no)) {
                            if (!empty($where)) {
                                $where .= " AND ";
                            }
                            $where .= "auction_items.order_lot_no = '".$lot_no."'";
                        }

                        $this->db->where($where);
                        // $this->db->where('auction_items.auction_id', $auction_id);
                        $query = $this->db->get();
                        $query = $query->result_array();
                        foreach ($query as $key1 => $v) {
                            array_push($item_ids3, $v['id']);
                        }
                        $this->db->reset_query();
                    }
                    if (isset($item_ids3)) {
                        if (isset($item_ids)) {
                            $item_ids = array_intersect($item_ids,$item_ids3);
                        } else {
                            $item_ids = $item_ids3;
                        }
                        unset($item_ids3);
                    // print_r($item_ids);die();
                    }

                    if (isset($min) || isset($max)) {
                        $item_ids1 = [];
                        $this->db->select('item.id,auction_items.auction_id');
                        $this->db->from('item');
                        $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                        if(isset($min)){
                            $this->db->where('item.price >= ', $min);
                        }
                        if(isset($max)){
                            $this->db->where('price <= ', $max);
                        }
                        if(isset($milage_type)){
                            $this->db->where('item.mileage_type =', $milage_type);
                        }
                        $this->db->where('auction_items.auction_id', $live_auction['id']);
                        $query = $this->db->get();
                        $query = $query->result_array();
                        foreach ($query as $key2 => $v) {
                            array_push($item_ids1, $v['id']);
                        }
                        $this->db->reset_query();
                    }
                    if (isset($item_ids1)) {
                        if (isset($item_ids)) {
                            $item_ids = array_intersect($item_ids,$item_ids1);
                        } else {
                            $item_ids = $item_ids1;
                        }
                        unset($item_ids1);
                    }

                    if (isset($min_milage) || isset($max_milage)) {
                        $item_ids2 = [];
                        $this->db->select('item.id,auction_items.auction_id');
                        $this->db->from('item');
                        $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                        if(isset($min_milage)){
                            $this->db->where('item.mileage >= ', $min_milage);
                        }
                        if(isset($max_milage)){
                            $this->db->where('item.mileage <= ', $max_milage);
                        }
                        if(isset($milage_type)){
                            $this->db->where('item.mileage_type =', $milage_type);
                        }
                        $this->db->where('auction_items.auction_id', $live_auction['id']);
                        $query = $this->db->get();
                        $query = $query->result_array();
                        foreach ($query as $k => $v) {
                            array_push($item_ids2, $v['id']);
                        }
                        $this->db->reset_query();
                    }
                    if (isset($item_ids2)) {
                        // print_r($item_ids2);
                        if (isset($item_ids)) {
                            $item_ids = array_intersect($item_ids,$item_ids2);
                        } else {
                            $item_ids = $item_ids2;
                        }
                        unset($item_ids2);
                    }
                    // print_r($item_ids);die();

                    $offset = 0;
                    $limit = 3;
                    $next_offset = $offset + $limit;
                    $total = 0;
                    $auction_items = array();
                    // if (!empty($item_ids)) {
                    if (isset($item_ids) && empty($item_ids)) {
                        array_push($item_ids, '0');
                    }
                    if (!isset($item_ids)) {
                        $item_ids = array();
                    }


                    $total = count($this->lam->get_live_auction_items($live_auction['id'], 0, 0,$item_ids));

                    $limit = 12;


                    $config['base_url'] = '#';
                    $config['total_rows'] = $total;
                    $config['per_page'] = $limit;
                    // $config['reuse_query_string'] = FALSE;
                    // $config["uri_segment"] = 4;
                    $config['full_tag_open'] = '<ul class="list link pagination">';
                    $config['full_tag_close'] = '</ul">';

                    $config['next_tag_open'] = '<li class="pagination_link">';
                    $config['next_tag_close'] = '</li>';

                    $config['prev_tag_open'] = '<li class="pagination_link">';
                    $config['prev_tag_close'] = '</li>';

                    $config['last_tag_open'] = '<li class="link pagination_link">';
                    $config['last_tag_close'] = '</li>';

                    $config['first_tag_open'] = '<li class="link pagination_link">';
                    $config['first_tag_close'] = '</li>';

                    $config['next_link'] = 'Next &gt;';
                    $config['prev_link'] = '&lt Prev';
                    
                    $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
                    $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
                    
                    $config['cur_tag_open'] = '<li class="link active"><a>';
                    $config['cur_tag_close'] = '</a></li>';
                    
                    $config['num_tag_open'] = '<li class="link pagination_link">';
                    $config['num_tag_close'] = '</li>';
                    $config['num_links'] = 2;

                    $this->pagination->initialize($config); // pagination created 
                    $pagination_links = $this->pagination->create_links();

                    //offset records
                    $page = $this->uri->segment(4);
                    // print_r($page);die();
                    if(empty($page)){ 
                        $offset = 0; 
                    }else{ 
                        $offset = $page; 
                    }
                    $next_offset = $offset + $limit;

                    $auction_items = $this->lam->get_live_auction_items($live_auction['id'],$limit,$offset,$item_ids,$order_by);
                    unset($item_ids);
                    $live_auctions[$key]['auction_items'] = $auction_items;
                    $live_auctions[$key]['pagination_links'] = $pagination_links;
                }
            }

            if(count($live_auctions) > 0){
                $items = $this->template->load_user_ajax('auction/online_auction/live_auction_items', ['live_auctions' => $live_auctions], true);
                $btn = false;
                // $btn = ($total > $next_offset);
                // print_r($btn);die();
                $output = json_encode([
                    'status' => 'success',
                    'items' => $items, 
                    'total' => $total, 
                    'offset' => $next_offset,
                    'btn' => $btn]);
                return print_r($output);
            }else{
                $output = json_encode(['status' => 'failed','btn' => false]);
                return print_r($output);
            }

        }
    }

    public function ratings()
    {
        $data = $this->input->post();
        if($data){
            //print_r($data);

            $user = $this->session->userdata('logged_in');
            if($user){
                $insert = [
                    'user_id' => $user->id,
                    'auction_item_id' => $data['auction_item_id'],
                    'ratings' => $data['ratings']
                ];
                $this->db->insert('auction_item_ratings', $insert);
                echo '1';
            }else{
                echo '0';
            }
        }
    }

    function removeItemString($str, $item) {
        $parts = explode(',', $str);
        while(($i = array_search($item, $parts)) !== false) {
        unset($parts[$i]);
        }
        return implode(',', $parts);
    }
    
    public function inspection_report(){
        $item_id = $this->uri->segment(2);
        $item_data['data'] = $this->db->get_where('item',['id' => $item_id])->row_array();
        // $item_data['cats_data'] = $this->db->get_where('item_category_fields',['category_id' => 1])->result_array();
        ///dynamic fields 
        $cat_id = 1;
        $datafields = $this->items_model->fields_data_new($cat_id);
        $fdata = array();
        foreach ($datafields as $key => $fields)
        { 
            $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid_new($item_id,$fields['id']);
            $fields['values'] = json_decode($fields['values'],true);  
            $fields['data-id'] = $fields['id'];
            if (!empty($fields['values'])) {
                foreach ($fields['values'] as $key => $options) {
                    if ($options['value'] == $item_dynamic_fields_info['value']) {
                        $fields['data-value'] = $options['label'];
                    }
                }
            }else{
                $fields['data-value'] = $item_dynamic_fields_info['value'];
            }
            $fdata[] = $fields;
        }
        $item_data['fields'] = $fdata;


        // print_r($item_data['cats_data']);die();
        $condition_report = $_SERVER['DOCUMENT_ROOT']."/uploads/items_documents/".$item_id."/";
            if (file_exists($condition_report.'condition.png')) {
                // unlink($output_file.'condition.png');
                // $img_base64 = base64_decode($condition_report.'condition.png');
                $item_data['condition_img'] = base_url()."/uploads/items_documents/".$item_id."/".'condition.png';
            }else{
                $item_data['condition_img_text'] = 'Inspection is pending.';
            }
        $this->load->view('items/inspection_report',$item_data);
    }


    

/////  found extra code////////////////////////////////////////////////////

    public function find_user()
    {
        $data = array();
        $search = $this->input->post('search');
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("fname", $search);
        $this->db->or_where("mobile", $search);
        $query = $this->db->get();
        $user = $query->row_array();
        if ($user) {
            $response = array(
               "status" => 'success',
               "user" => $user
            );
        }else{
            $response = array(
               "status" => 'error',
               "msg" => 'User not exist'
            );
        }

        echo json_encode($response); 
    }

    public function update_deposit()
    {
        $data = array();
        $posted_data = $this->input->post();
        // print_r($posted_data);die();
        if (isset($posted_data['id']) && !empty($posted_data['id'])) {
            $id = $posted_data['id'];
            unset($posted_data['id']);
            unset($posted_data['user_name']);
            $result = $this->db->update('auction_deposit', $posted_data, ['id' => $id]);
        }else{
            $posted_data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->db->insert('auction_deposit', $posted_data);
        }

        if ($result) {
            $response = array(
               "status" => 'success',
               "result" => $result,
               "msg" => 'Success! Deposit successfully.'
            );
        }else{
            $response = array(
               "status" => 'error',
               "msg" => 'Error! deposit failed please try again.'
            );
        }

        echo json_encode($response);
    }

    public function filter_auction_items()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 
  
            if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) 
            {
                $sql_cat = "  auctions.category_id = ".$posted_data['category_id']."  ";
                
            }
            if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
                $start_date = $posted_data['datefrom'];
                $end_date = $posted_data['dateto'];
                $sql[] = " (DATE(auctions.created_on) between '$start_date' and '$end_date') ";
            }
            $query = "";
            if (!empty($sql)) {
                $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
            }
            else
            {
                 $query .= $sql_cat;
            }

            $data['auction_list'] = $this->auction_model->auction_filter_list($query);
            
            $data_view = $this->load->view('auction/ajax_auction_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function auctionDeposit()
    {
        $data = array();
        $data['small_title'] = 'Live Deposit List';
        $data['current_page_live_auction'] = 'current-page';
        $data['formaction_path'] = 'filter_live_auction_items';
        $data['auction_id'] = $this->uri->segment(3);
        // print_r($data['auction_id']);
        $data['users_list'] = $this->db->get_where('users',['role' => 4])->result_array();
        $this->template->load_admin('auction/auction_deposit', $data);
    }

    //deposit users
    public function deposit_users_list()
    {
        $posted_data = $this->input->post();
        $data = array();
        // return ;
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value

        $keyword = (isset($posted_data['searchby']))? $posted_data['searchby'] : '';
        $search_arr = array();
         $searchQuery = "";
        if($keyword != ''){
            $search_arr[] = " fname like '%".$keyword."%' or mobile like '%".$keyword."%' ";
        }

        if(count($search_arr) > 0){
            $searchQuery = implode(" AND ",$search_arr);
        }
        $auction_id = $this->uri->segment(3);

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
   
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $this->db->select('auction_deposit.*,users.fname,users.mobile,users.email');
   
        
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $this->db->limit($rowperpage, $start);
        // $this->db->order_by($columnName, $columnSortOrder);
        $users_list = $this->db->get()->result_array();

        $action = '';
        // $users_list = $this->db->get_where('users', ['role' => 4])->result_array();

        foreach ($users_list as $key => $value) {
            $action = '<a href="#" onclick="myfunc(this)" data-id="'.$value['id'].'" data-amount="'.$value['amount'].'" data-user_id="'.$value['user_id'].'" data-name="'.$value['fname'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            $data[] = array( 
             "id"=>$value['id'],
             "email"=>$value['email'],
             "amount"=>$value['amount'],
             "fname"=>$value['fname'],
             "mobile"=>$value['mobile'],
             "created_on"=>$value['created_on'],
            "action"=> $action
            ); 
        }

        $response = array(
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "draw" => intval($draw),
            "aaData" => $data
        );

        echo json_encode($response); 

    }

    public function get_stock_list_inner()
    {
        $auction_id = $this->input->post('id');
        $auction_id = base64_decode(urldecode($auction_id));
        $data = array();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['auction_id'] = $auction_id;
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];

        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids_by_auction_category($auction_id,$data['category_id']);
        // print_r($item_ids_list_multi_array);
        $data['item_ids_list'] = array_column($item_ids_list_multi_array,"item_id");
        // $already_ids = implode( ",", $data['item_ids_list']);
        if(isset($auction_data_array[0]['category_id']) && !empty($auction_data_array[0]['category_id'])){

        $sql[] = " item.category_id = ".$auction_data_array[0]['category_id']." ";
        $sql[] = " item.in_auction = 'no' ";
        $sql[] = " item.item_status = 'completed' ";
        $sql[] = " item.status = 'active' ";
        }

         $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' AND ', $sql);
        }
        // $data_items_list = $this->items_model->items_filter_limit_list_active($query);
        $data['items_list'] = $this->items_model->items_filter_limit_list_active($query);

        $data['formaction_path'] = 'filter_items_by_category';
        $data['formaction_path2'] = 'search_items';
        $data_view = $this->load->view('auction/auction_items/auction_items_list_inner', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));

    }

    public function auctionDepositList()
    {
        $data = array();
        $data['current_page_live_auction'] = 'current-page';
        $data['auction_id'] = $this->uri->segment(3);
        $data['small_title'] = 'Live Deposit List';
        $data['formaction_path'] = 'filter_live_auction_items';
        // print_r($data['auction_id']);
        $data['users_list'] = $this->db->get_where('users',['role' => 4])->result_array();
        $this->template->load_admin('auction/auction_deposit', $data);
    }


    public function filter_items()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 

        if (isset($posted_data['sale_person_id']) && !empty($posted_data['sale_person_id'])) {
 
            $sales_person_id = implode(",",$posted_data['sale_person_id']);
            $sql[] = " item.created_by IN ($sales_person_id) ";
        }

        if (isset($posted_data['keyword']) && !empty($posted_data['keyword'])) {
 
            $sql[] = " item.keyword like '%".$posted_data['keyword']."%' ";
        }

        if (isset($posted_data['item_status']) && !empty($posted_data['item_status'])) {
 
            $sql[] = " item.item_status = '".$posted_data['item_status']."' ";
        }

        if (isset($posted_data['registration_no']) && !empty($posted_data['registration_no'])) {
 
            $sql[] = " item.registration_no = '".$posted_data['registration_no']."' ";
        }

        // if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
        //     $sql[] = " item.category_id = ".$posted_data['category_id']." ";
        // }
        
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) 
        {
            $sql_cat = "  item.category_id = ".$posted_data['category_id']."  ";
            // $sql_cat .= " AND item.in_auction = 'no' ";
            $sql_cat .= " AND item.item_status = 'completed' ";
            $sql_cat .= " AND item.status = 'active' ";
        }


        if (isset($posted_data['seller_id']) && !empty($posted_data['seller_id'])) {
            $seller_id = implode(",",$posted_data['seller_id']);
            $sql[] = " item.seller_id IN ($seller_id) ";
        }

        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $sql[] = " (DATE(item.created_on) between '$start_date' and '$end_date') ";
        }

        
        if (isset($posted_data['days_filter']) && !empty($posted_data['days_filter'])) {
          
            $interval = (($posted_data['days_filter'] == 'today')) ? 0 : $posted_data['days_filter'];

            $sql[] = " (DATE(item.created_on)  >= DATE(NOW()) + INTERVAL ".$interval." DAY  AND DATE(item.created_on) <  NOW() + INTERVAL  0 DAY) ";
             
        }
            $query = "";
            if (!empty($sql)) {
                $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
            }
            else
            {
                 $query .= $sql_cat;
            }

            $auction_id = $this->input->post('auction_id');
            $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
            if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
            {
            $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
            }
            else
            {
                $item_ids_list = array();
            }

            $data['items_list'] = $this->items_model->auction_items_filter_list($item_ids_list,$query);
            $data['auction_id'] = $auction_id;
            $data_view = $this->load->view('auction/auction_items/ajax_items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function filter_items_by_properties()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 

        if (isset($posted_data['sale_person_id']) && !empty($posted_data['sale_person_id'])) {
 
            $sales_person_id = implode(",",$posted_data['sale_person_id']);
            $sql[] = " item.created_by IN ($sales_person_id) ";
        }

        if (isset($posted_data['keyword']) && !empty($posted_data['keyword'])) {
 
            $sql[] = " item.keyword like '%".$posted_data['keyword']."%' ";
        }

        if (isset($posted_data['item_status']) && !empty($posted_data['item_status'])) {
 
            $sql[] = " item.item_status = '".$posted_data['item_status']."' ";
        }

        if (isset($posted_data['registration_no']) && !empty($posted_data['registration_no'])) {
 
            $sql[] = " item.registration_no = '".$posted_data['registration_no']."' ";
        }

        // if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
        //     $sql[] = " item.category_id = ".$posted_data['category_id']." ";
        // }
        
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) 
        {
            $sql_cat = "  item.category_id = ".$posted_data['category_id']."  ";
            // $sql_cat .= " AND item.in_auction = 'no' ";
            $sql_cat .= " AND item.item_status = 'completed' ";
            $sql_cat .= " AND item.status = 'active' ";
        }


        if (isset($posted_data['seller_id']) && !empty($posted_data['seller_id'])) {
            $seller_id = implode(",",$posted_data['seller_id']);
            $sql[] = " item.seller_id IN ($seller_id) ";
        }

        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $sql[] = " (DATE(item.created_on) between '$start_date' and '$end_date') ";
        }

        
        if (isset($posted_data['days_filter']) && !empty($posted_data['days_filter'])) {
          
            $interval = (($posted_data['days_filter'] == 'today')) ? 0 : $posted_data['days_filter'];

            $sql[] = " (DATE(item.created_on)  >= DATE(NOW()) + INTERVAL ".$interval." DAY  AND DATE(item.created_on) <  NOW() + INTERVAL  0 DAY) ";
             
        }
            $query = "";
            if (!empty($sql)) {
                $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
            }
            else
            {
                 $query .= $sql_cat;
            }

            $auction_id = $this->input->post('auction_id');
            $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
            if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
            {
            $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
            }
            else
            {
                $item_ids_list = array();
            }

            $data['items_list'] = $this->items_model->auction_items_filter_list($item_ids_list,$query);
            $data['auction_id'] = $auction_id;
            $data_view = $this->load->view('auction/auction_items/ajax_items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function filterauction_items()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 
  
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) 
        {
            $sql_cat = "  auctions.category_id = ".$posted_data['category_id']."  ";
            
        }
        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = date('Y-m-d',strtotime($posted_data['datefrom']));
            $end_date = date('Y-m-d',strtotime($posted_data['dateto']));
            $sql[] = " (DATE(auctions.created_on) between '$start_date' and '$end_date') ";
        }
            $query = "";
            if (!empty($sql) && !empty($sql_cat)) {
                $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
            }elseif (!empty($sql) && empty($sql_cat)) {
                $query .= ' (' . implode(' OR ', $sql).' ) ';
            }
            else
            {
                 $query .= $sql_cat;
            }

            $data['auction_list'] = $this->auction_model->live_auction_filter_list($query);
            
            $data_view = $this->load->view('auction/ajax_live_auction_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }


    public function validate_check_registration_no()
    {
        $check_number = $this->auction_model->check_registration_no($this->uri->segment(3));
        if(isset($check_number) && !empty($check_number))
        {
            echo '404';
        }
        else
        {
            echo '200';
        }
    }

    public function get_stock_list()
    {
        $auction_id = $this->input->post('id');
        $data = array();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['auction_id'] = $auction_id;
        // $auction_id = base64_decode(urldecode($auction_id));
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];

        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        $data['item_ids_list'] = array_column($item_ids_list_multi_array,"item_id");
        
        if(isset($auction_data_array[0]['category_id']) && !empty($auction_data_array[0]['category_id']))
        {

        $sql[] = " item.category_id = ".$auction_data_array[0]['category_id']." ";
        $sql[] = " item.in_auction = 'no' ";
        $sql[] = " item.item_status = 'completed' ";
        $sql[] = " item.status = 'active' ";
        }

        $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' AND ', $sql);
        }
        $data['items_list'] = $this->items_model->items_filter_limit_list_active($query);

        $data['formaction_path2'] = 'search_items';
        $data['formaction_path'] = 'filter_items_by_category';
        $data_view = $this->load->view('auction/auction_items/auction_items_list', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));

    }


    // Update a auction Item

    public function save_item_test_report()
    {

        if( ! empty($_FILES['test_documents']['name'])){
 
            $item_id = $_POST['item_id'];
            
            $itemsIds_array = array();
            $ids_concate = '';

            $result_array = $this->items_model->get_item_byid($item_id);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_test_report']);

                if(!empty($itemsIds_array) && !empty($result_array[0]['item_test_report']))
                {
                    $ids_concate = $result_array[0]['item_test_report'].",";
                }
            }

            // make path
            $path = './uploads/items_documents/';
            if ( ! is_dir($path.$item_id)) {
                mkdir($path.$item_id, 0777, TRUE);
            }
            $path = $path.$item_id.'/'; 
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls';
            $uploaded_file_ids = $this->files_model->multiUpload('test_documents', $config); // upload and save to database

            $uploaded_file_ids = implode(',', $uploaded_file_ids);
            $update = [
                'item_test_report' => $ids_concate.$uploaded_file_ids,
                // 'item_status' => 'completed',
            ];
            $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';
            if($result == 'true')
            {
                $this->session->set_flashdata('msg', 'Item Test Report Added Successfully');
                echo json_encode(array('msg'=>'Item Test Report Added Successfully'));
            }
        }
    }

    public function view_documents()
    {
        $data = array();
        $data['small_title'] = 'Documents ';
        $data['formaction_path'] = 'update_item';
        $data['current_page_auction'] = 'current-page';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);     

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            // print_r($item_images);
            foreach ($item_images as $value) 
            {
                $data['item_images'][] = array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'file_order' => $value['file_order'],
                    'status' => $value['status']
                );
            }
            foreach ($item_documents as $value) 
            {
                $data['item_documents'][] = array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'file_order' => $value['file_order'],
                    'status' => $value['status']
                );
            }
        }

        if ($this->input->post()) 
        {

        }
        else
        {
              $this->template->load_admin('auction/auction_items/documents_list', $data);
        }
    }

    public function update_auction_item()
    {
        $data = array();
        $data['small_title'] = 'Update Item';
        $data['formaction_path'] = 'update_auction_item';
        $data['current_page_auction'] = 'current-page';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);
     
        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid($item_id);
        // $data['seller_list'] = $this->users_model->users_list(3);
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        // print_r($item_dynamic_fields_info);
        if($item_dynamic_fields_info)
        {
          foreach ($item_dynamic_fields_info as $value) 
          {
            $multiple_info = $this->items_model->fields_multiple_info($value['fields_id']);
            $field_ids[] = $value['fields_id'];
            $field_values[] = $value['value'];
            $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
            // print_r($value['fields_id'].'  - '.$multiple_info[0]['multiple'].' ');
          }
            $data['field_ids'] = $field_ids;
            $data['field_values'] = $field_ids_list;
        }

         // print_r($field_ids_list);

        $data['category_list'] = $this->items_model->get_item_category_active();
        if ($this->input->post()) {

        $item_dynamic_data = $this->input->post();
         
        $item_data = $this->input->post('item'); // get basic information 
        unset($item_dynamic_data['item']);  // remove basic information form data
   

        $this->load->library('form_validation');
          $rules = array(
                array(
                    'field' => 'item[name]',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'item[keyword]',
                //     'label' => 'Keyword',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'item[status]',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'item[item_status]',
                    'label' => 'Item Status',
                    'rules' => 'trim|required')
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $id = $item_data['id'];
                 if(!empty($item_data['seller_id']))
                {
                    $seller_id = $item_data['seller_id'];
                }
                else
                {
                 $seller_id = $this->loginUser->id;   
                }

                $posted_data = array(
                'name' => $item_data['name'],
                'detail' => $item_data['detail'],
                'status' => $item_data['status'],
                'item_status' => $item_data['item_status'],
                'price' => $item_data['price'],
                // 'keyword' => $item_data['keyword'],
                'category_id' => $item_data['category_id'],
                'seller_id' => $seller_id,
                // 'auction_type' => implode(",", $item_data['auction_type']),
                'updated_by' => $this->loginUser->id
                );

                if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $item_data['subcategory_id'];
                }
                
                if(isset($item_data['keyword']) && !empty($item_data['keyword']))
                {
                    $posted_data['keyword'] = $item_data['keyword'];
                }

                if(isset($item_data['price']) && !empty($item_data['price']))
                {
                    $posted_data['price'] = $item_data['price'];
                }

                if(isset($item_data['registration_no']) && !empty($item_data['registration_no']))
                {
                    $posted_data['registration_no'] = $item_data['registration_no'];
                }

                if(isset($item_data['make']))
                {
                    $posted_data['make'] = $item_data['make'];
                    $posted_data['model'] = $item_data['model'];    
                }
                

                $result = $this->items_model->update_item($id,$posted_data);

                $item_row_array = $this->items_model->get_item_byid($id);

                if(empty($item_row_array[0]['barcode']))
                {
                    $path = "uploads/items_documents/".$id."/qrcode/";

                    // make path
                    if ( !is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    } 

                    $qrcode_name = $this->generate_code($id,$path, ['id'=>$id]);
                    if(!empty($qrcode_name))
                    {
                        $barcode_array = array(
                        'barcode' => $qrcode_name
                        );
                        $this->items_model->update_item($id,$barcode_array);
                    }
                }
               



                $result_attachments = array();
  

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
                    $category_id = $item_data['category_id'];
                    $fields_id = $ids_arr[0];
                    $check_if = $this->items_model->check_items_field_data($id,$item_data['category_id'],$ids_arr[0]);
                    // check if fields already exist or not then insert or update accordingly 
                    if($check_if)
                    {
                        $dynaic_information2 = array(
                        'category_id' => $category_id,
                        'item_id' => $id,
                        'fields_id' => $fields_id,
                        'value' => $dynamic_values_new,
                        'updated_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->update_item_fields_data($id,$category_id,$fields_id,$dynaic_information2);
                    }
                    else
                    {
                        $dynaic_information = array(
                        'category_id' => $item_data['category_id'],
                        'item_id' => $id,
                        'fields_id' => $ids_arr[0],
                        'value' => $dynamic_values_new,
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->insert_item_fields_data($dynaic_information);
                    }   
                }

                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result, 'attach' => $result_attachments));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }else{

            $this->template->load_admin('auction/auction_items/auction_item_form', $data);
        }

    }

    public function getAuctionWithUsersList(){
        $auction_id = $this->input->post('auction_id');
        $userArray = $this->db->group_by('user_id')->get_where('auction_user_list', ['auction_id' => $auction_id,'status' => 'in'])->result_array();
        $output = '';
        if($userArray){ 
            foreach ($userArray as $uservalue) {
                $user_id = $uservalue['user_id'];
                $output .= '<tr>';
                $user_detail = $this->livecontroller_model->get_all_customer_active($user_id);
                if(!empty($user_detail)){
                    $output .= '<td>';
                    $output .= $user_detail['fname'];
                    $output .= '</td>';
                    $output .= '<td>';
                    $output .= $user_detail['lname'];
                    $output .= '</td>';
                    $output .= '<td>';
                    $output .= $user_detail['code'];
                    $output .= '</td>';
                    $output .= '<td>';
                    $output .= $user_detail['mobile'];
                    $output .= '</td>';
                }
                $output .= '</tr>';
                echo json_encode(array('error'=>false,'response'=>$output));

            }
            
        }else{
        $output = '<tr class="empty"><td colspan="6">Online Conected use will<br> display here - we can block user<br> for bulding.</td></tr>';
        echo json_encode(array('error'=>true,'response'=>$output)); 
        }
    }

    public function documents()
    {
        $data = array();
        $data['small_title'] = 'Manage Documents';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'update_item';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);     

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $test_report_ids = explode(",",$data['item_info'][0]['item_test_report']);
            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
             $item_test_report_documents = $this->items_model->get_item_documents_byid($test_report_ids);
            // print_r($item_images);

            foreach ($item_images as $value) 
            {
                $data['item_images'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type']
                );
            }

            foreach ($item_documents as $value) 
            {
                $data['item_documents'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type']
                );
            }
              foreach ($item_test_report_documents as $value) {
                $data['item_test_documents'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type']
                );
            }
        }

        if ($this->input->post()) 
        {

        }
        else
        {
              $this->template->load_admin('auction/auction_items/documents_form', $data);
        }
    }

    public function delete_item_test_documents($itemId)
    {
        $attach_name = $this->input->post('file_to_be_deleted');

        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {
            $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_test_report']);

                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0]['item_test_report'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                    'item_test_report' => $updated_str,
                    'updated_by' => $this->loginUser->id
                ];
            $update_item_row = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
       

        $path = FCPATH .  "uploads/items_documents/".$itemId."/";
        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';    
        }
        
    }


    public function save_item_file_images()
    {

        if( ! empty($_FILES['images']['name'])){


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
            if ( ! is_dir($path.$item_id)) {
                mkdir($path.$item_id, 0777, TRUE);
            }
            $path = $path.$item_id.'/'; 
            $config['upload_path'] = $path;
            // $config['allowed_types'] = 'ico|png|jpg|pdf|ai|psd|eps|indd|doc|docx|ppt|pptx|xlsx|xls';
            $config['allowed_types'] = 'ico|png|jpg|jpeg';
            $uploaded_file_ids = $this->files_model->multiUpload('images', $config); // upload and save to database
            $uploaded_file_ids = implode(',', $uploaded_file_ids);
            $update = [
                'item_images' => $ids_concate.$uploaded_file_ids
            ];
            echo $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';

             if($result == 'true')
            {
                $this->session->set_flashdata('msg', 'Item Documents Added Successfully');
            }
        }
    }

    public function save_item_file_documents()
    {

        if( ! empty($_FILES['documents']['name'])){

            $item_id = $_POST['item_id'];
            
            $itemsIds_array = array();
            $ids_concate = '';

            $result_array = $this->items_model->get_item_byid($item_id);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_attachments']);

                if(!empty($itemsIds_array) && !empty($result_array[0]['item_attachments']))
                {
                    $ids_concate = $result_array[0]['item_attachments'].",";
                }
            }

            // make path
            $path = './uploads/items_documents/';
            if ( ! is_dir($path.$item_id)) {
                mkdir($path.$item_id, 0777, TRUE);
            }
            $path = $path.$item_id.'/'; 
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls';
            $uploaded_file_ids = $this->files_model->multiUpload('documents', $config); // upload and save to database

            $uploaded_file_ids = implode(',', $uploaded_file_ids);
            $update = [
                'item_attachments' => $ids_concate.$uploaded_file_ids,
                'item_status' => 'completed',
            ];
            echo $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';
            if($result == 'true')
            {
                $this->session->set_flashdata('msg', 'Item Documents Added Successfully');
            }
        }
    }

      public function delete_item_image($itemId)
    {
        $attach_name = $this->input->post('file_to_be_deleted');
        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {

        $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_images']);

                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0]['item_images'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'item_images' => $updated_str,
                'updated_by' => $this->loginUser->id
            ];
            $result_update = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
        $path = FCPATH .  "uploads/items_documents/".$itemId."/";
        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';    
        }
        
    }
    public function delete_item_documents($itemId)
    {
        $attach_name = $this->input->post('file_to_be_deleted');

        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {
            $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_attachments']);

                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0]['item_attachments'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                    'item_attachments' => $updated_str,
                    'updated_by' => $this->loginUser->id
                ];
            $update_item_row = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
       

        $path = FCPATH .  "uploads/items_documents/".$itemId."/";
        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';    
        }
        
    }

     public function filter_items_by_category()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql);  

        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
            $sql[] = " item.category_id = ".$posted_data['category_id']." ";
            $sql[] = " item.in_auction = 'no' ";
        }
  
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' AND ', $sql);
        }

            $data['items_list'] = $this->items_model->items_filter_limit_list($query);
            $data['auction_id'] =  $this->input->post('auction_id');
            $data_view = $this->load->view('auction/auction_items/items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

   public function get_lotting()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['auction_id'] = $this->input->post('auction_id');
        $data['bidding_info'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        $auction_bid_list_array = $this->auction_model->get_auctions($data['auction_id']); 
        $data['auction_bid_list'] = explode(",",$auction_bid_list_array[0]['bid_options']); 
         $data['auction_start_time'] = $auction_bid_list_array[0]['start_time'];
         $data['auction_expiry_time'] = $auction_bid_list_array[0]['expiry_time'];
        $data_view = $this->load->view('auction/auction_items/lotting', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function live_item_detail()
    {
        $data = array();
        $item_id = $this->uri->segment(3);
        $data['item_id'] = $item_id;
        $data['back_navigate'] = 'liveitems';
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
        {
            $images_ids = explode(",",$data['item_row'][0]['item_images']);
           $data['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        }
        else
        {
            $data['files_array'] = array();
        }

        if(isset($data['item_row'][0]['item_attachments']) && !empty($data['item_row'][0]['item_attachments']))
        {
            $documents_ids = explode(",",$data['item_row'][0]['item_attachments']);
           $data['documents_array'] = $this->files_model->get_multiple_files_by_ids_orders($documents_ids);
        }
        else
        {
            $data['documents_array'] = array();
        }


        if(isset($data['item_row'][0]['item_test_report']) && !empty($data['item_row'][0]['item_test_report']))
        {
            $test_documents_ids = explode(",",$data['item_row'][0]['item_test_report']);
            $data['test_documents_array'] = $this->files_model->get_multiple_files_by_ids_orders($test_documents_ids);
        }
        else
        {
            $data['test_documents_array'] = array();
        }
        
        $this->template->load_admin('auction/auction_items/items_detail', $data);
    }

    public function get_banner_details()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['auction_id'] = $this->input->post('auction_id');
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        $data['auction_item_row'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
        {
            $images_ids = explode(",",$data['item_row'][0]['item_images']);
           $data['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        }
        else
        {
            $data['files_array'] = array();
        }

        $data_view = $this->load->view('auction/auction_items/items_details_content', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    // Update an Item in popup model
    public function edit_item_detail_view()
    {
        $data = array();
        $data['small_title'] = 'Update Item';
        $data['formaction_path'] = 'update_item_detail_view';
        $data['current_page_list'] = 'current-page';
        $data['item_id'] = $this->input->post('id');
        $item_id = $this->input->post('id');
     
        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid($item_id);
        // $data['seller_list'] = $this->users_model->users_list(3);
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        // print_r($item_dynamic_fields_info);
        if($item_dynamic_fields_info)
        {
          foreach ($item_dynamic_fields_info as $value) 
          {
            $multiple_info = $this->items_model->fields_multiple_info($value['fields_id']);
            $field_ids[] = $value['fields_id'];
            $field_values[] = $value['value'];
            $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
            // print_r($value['fields_id'].'  - '.$multiple_info[0]['multiple'].' ');
          }
            $data['field_ids'] = $field_ids;
            $data['field_values'] = $field_ids_list;
        }

            $data['category_list'] = $this->items_model->get_item_category_active();


            $data_view = $this->load->view('auction/auction_items/item_detail_form', $data, true);
            echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
            // $response = array('msg' => 'success','data' => $data_view);
    }

    public function get_b_details()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['auction_id'] = $this->input->post('auction_id');
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        $data['auction_item_row'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
        {
            $images_ids = explode(",",$data['item_row'][0]['item_images']);
           $data['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        }
        else
        {
            $data['files_array'] = array();
        }

        $data_view = $this->load->view('auction/auction_items/items_details_content', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    // Update an Item in popup model
    public function edit_item_w()
    {
        $data = array();
        $data['small_title'] = 'Update Item';
        $data['formaction_path'] = 'update_item_detail_view';
        $data['current_page_list'] = 'current-page';
        $data['item_id'] = $this->input->post('id');
        $item_id = $this->input->post('id');
     
        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid($item_id);
        // $data['seller_list'] = $this->users_model->users_list(3);
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        // print_r($item_dynamic_fields_info);
        if($item_dynamic_fields_info)
        {
          foreach ($item_dynamic_fields_info as $value) 
          {
            $multiple_info = $this->items_model->fields_multiple_info($value['fields_id']);
            $field_ids[] = $value['fields_id'];
            $field_values[] = $value['value'];
            $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
            // print_r($value['fields_id'].'  - '.$multiple_info[0]['multiple'].' ');
          }
            $data['field_ids'] = $field_ids;
            $data['field_values'] = $field_ids_list;
        }

            $data['category_list'] = $this->items_model->get_item_category_active();


            $data_view = $this->load->view('auction/auction_items/item_detail_form', $data, true);
            echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
            // $response = array('msg' => 'success','data' => $data_view);
    }


     public function item_details_documents()
    {
        $data = array();
        $data['small_title'] = 'Manage Documents';
        $data['current_page_list'] = 'current-page';
        $data['formaction_path'] = 'update_item';
        $data['item_id'] = $this->input->post('id');
        $item_id = $this->input->post('id');     

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            // print_r($item_images);

            foreach ($item_images as $value) 
            {
                $data['item_images'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'type' => $value['type']
                );
            }

            foreach ($item_documents as $value) 
            {
                $data['item_documents'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'type' => $value['type']
                );
            }
        }
              // $this->template->load_admin('items/documents_form', $data);
            $data_view = $this->load->view('auction/auction_items/item_detail_documents_form', $data, true);
            echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
        
    }

    public function update_item_detail_view()
    {
    
            if ($this->input->post()) {

        $item_dynamic_data = $this->input->post();
         
        $item_data = $this->input->post('item'); // get basic information 
        unset($item_dynamic_data['item']);  // remove basic information form data
   

        $this->load->library('form_validation');
          $rules = array(
                array(
                    'field' => 'item[name]',
                    'label' => 'Name',
                    'rules' => 'trim|required'), 
                array(
                    'field' => 'item[status]',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'item[item_status]',
                    'label' => 'Item Status',
                    'rules' => 'trim|required')
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors(),'response'=>validation_errors()));
            } 
            else
            {   
                $id = $item_data['id'];
                 if(!empty($item_data['seller_id']))
                {
                    $seller_id = $item_data['seller_id'];
                }
                else
                {
                 $seller_id = $this->loginUser->id;   
                }

                $posted_data = array(
                'name' => $item_data['name'],
                'detail' => $item_data['detail'],
                'status' => $item_data['status'],
                'item_status' => $item_data['item_status'],
                'price' => $item_data['price'],
                'category_id' => $item_data['category_id'],
                'seller_id' => $seller_id,
                // 'auction_type' => implode(",", $item_data['auction_type']),
                'updated_by' => $this->loginUser->id
                );

                if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $item_data['subcategory_id'];
                }

                if(isset($item_data['keyword']) && !empty($item_data['keyword']))
                {
                    $posted_data['keyword'] = $item_data['keyword'];
                }
                
                if(isset($item_data['price']) && !empty($item_data['price']))
                {
                    $posted_data['price'] = $item_data['price'];
                }

                if(isset($item_data['registration_no']) && !empty($item_data['registration_no']))
                {
                    $posted_data['registration_no'] = $item_data['registration_no'];
                }

                if(isset($item_data['make']))
                {
                    $posted_data['make'] = $item_data['make'];
                    $posted_data['model'] = $item_data['model'];    
                }
                

                $result = $this->items_model->update_item($id,$posted_data);

                $item_row_array = $this->items_model->get_item_byid($id);

                if(empty($item_row_array[0]['barcode']))
                {
                    $path = "uploads/items_documents/".$id."/qrcode/";

                    // make path
                    if ( !is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    } 

                    $qrcode_name = $this->generate_code($id,$path, ['id'=>$id]);
                    if(!empty($qrcode_name))
                    {
                        $barcode_array = array(
                        'barcode' => $qrcode_name
                        );
                        $this->items_model->update_item($id,$barcode_array);
                    }
                }
               



                $result_attachments = array();
  

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
                    $category_id = $item_data['category_id'];
                    $fields_id = $ids_arr[0];
                    $check_if = $this->items_model->check_items_field_data($id,$item_data['category_id'],$ids_arr[0]);
                    // check if fields already exist or not then insert or update accordingly 
                    if($check_if)
                    {
                        $dynaic_information2 = array(
                        'category_id' => $category_id,
                        'item_id' => $id,
                        'fields_id' => $fields_id,
                        'value' => $dynamic_values_new,
                        'updated_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->update_item_fields_data($id,$category_id,$fields_id,$dynaic_information2);
                    }
                    else
                    {
                        $dynaic_information = array(
                        'category_id' => $item_data['category_id'],
                        'item_id' => $id,
                        'fields_id' => $ids_arr[0],
                        'value' => $dynamic_values_new,
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->insert_item_fields_data($dynaic_information);
                    }
                    
                }

                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Updated Successfully');
                  $msg = 'success';
                  $response = 'Item Updated Successfully';
                  echo json_encode(array('msg' => $msg,'response'=>$response, 'in_id' => $result, 'attach' => $result_attachments));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    $response = 'DB Error found.';
                    echo json_encode(array('msg' => $msg,'response'=>$response, 'error' => $result));
                }
            }
        }else{
            $msg = 'DB Error found.';
                    $response = 'DB Error found.';
                    echo json_encode(array('msg' => $msg,'response'=>$response));
        }
    }

    public function update_lotting()
    {
      $data = array();
      $id = '';
    if ($this->input->post()) 
    {
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'item_id',
                    'label' => 'Valid Item',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'auction_id',
                    'label' => 'Valid Auction',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'lot_no',
                    'label' => 'Lot No',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

             echo json_encode(array( 'msg'=>'error', 'data' => validation_errors()));
        } 
        else
        { 

        
        $item_id = $this->input->post('item_id');
        $auction_id = $this->input->post('auction_id');
        
        if(!empty($item_id))
        {
           $posted_data = array(
                   'lot_no' => $this->input->post('lot_no'),
                    'updated_by' => $this->loginUser->id
                    );
            $result = $this->auction_model->update_auction_item_bidding_rules($item_id,$auction_id,$posted_data);
            $msg_ = 'Item Lotting No Updated Successfully';
        }
          
          if($result)
          {
            // $this->session->set_flashdata('msg', $msg_);
            echo json_encode(array( 'msg'=>'success', 'data' => $msg_));
          }
          else
          {
            echo json_encode(array( 'msg'=>'error', 'data' => 'Error Found'));
          }
        }
      }
    

    }


    // Add a new Auction
    public function save_live_auction()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Auction';
        $data['current_page_live_auction'] = 'current-page';
        $data['formaction_path'] = 'save_live_auction';
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();
        if ($this->input->post()) {
        $auction_data = $this->input->post();
        

        $this->load->library('form_validation');
            $rules = array(
                   array(
                    'field' => 'title_english',
                    'label' => 'Title English',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'title_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required')

            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                

                $posted_data = array(
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'], 
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'], 
                'start_time' => date('Y-m-d H:i',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i',strtotime($auction_data['expiry_time'])),
                'created_on' => date('Y-m-d H:i'),
                'created_by' => $this->loginUser->id
                ); 

                $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);

                if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                    $posted_data['detail'] = $auction_data['detail'];
                }

                // if(isset($auction_data['security']) && !empty($auction_data['security'])){
                //     $posted_data['security'] = $auction_data['security'];
                // }

                if(isset($auction_data['category_id']) && !empty($auction_data['category_id'])){
                    $posted_data['category_id'] = $auction_data['category_id'];
                    
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id'])){
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];

                }
                
                $result = $this->auction_model->insert_auction($posted_data);
                if (!empty($result))
                {
                  $this->session->set_flashdata('msg', 'Auction Added Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }
        else
        {
            $this->template->load_admin('auction/live_auction_form', $data);
        }

    }

    public function item_documents_online_auction()
    {
        $data = array();
        $data['small_title'] = 'Manage Documents';
        $data['current_page_list'] = 'current-page';
        $data['formaction_path'] = 'update_item';
        $data['item_id'] = $this->input->post('id');
        $item_id = $this->input->post('id');     

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            // print_r($item_images);

            foreach ($item_images as $value) 
            {
                $data['item_images'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'type' => $value['type']
                );
            }

            foreach ($item_documents as $value) 
            {
                $data['item_documents'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'type' => $value['type']
                );
            }
        }
              // $this->template->load_admin('items/documents_form', $data);
            $data_view = $this->load->view('auction/auction_items/item_detail_documents_form', $data, true);
            echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
        
    }

    public function live_auction_controller(){
        
        $data = array();
        $data['small_title'] = 'Live Auction Setting';
        $data['current_page_live_setting_auction'] = 'current-page';
        $data['formaction_path'] = 'live_auction_controller';
        $data['auction_live_setting'] = $this->db->get('auction_live_settings')->row_array();
        $data['button_status'] = (isset($data['auction_live_setting']) && !empty($data['auction_live_setting']))? 'Update' : 'Save';
        if($this->input->post()){       
            $result = array();
            $live_auction_setting_data = $this->input->post();  
            $this->load->library('form_validation');
             $rules = array( 
                array(
                    'field' => 'buttons',
                    'label' => 'Buttons',
                    'rules' => 'trim|required'), 
                // array(
                //     'field' => 'allow_online',
                //     'label' => 'Allow Online',
                //     'rules' => 'trim|required')

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false){
                echo json_encode(array('msg' => validation_errors()));

            }else{


                $posted_data = array( 
                    'buttons' => $live_auction_setting_data['buttons'],
                    // 'allow_online' => $live_auction_setting_data['allow_online']
                );

                if(isset($live_auction_setting_data['description']) && !empty($live_auction_setting_data['description'])){
                    $posted_data['description'] = $live_auction_setting_data['description'];
                }

                if(isset($live_auction_setting_data['id']) && !empty($live_auction_setting_data['id'])){

                    // update record
                    $id = $live_auction_setting_data['id'];
                    $posted_data['updated_by'] =  $this->loginUser->id;
                    $result = $this->db->update('auction_live_settings', $posted_data,['id'=>$id]);

                }else{
                    // save new record 
                    $posted_data['created_by'] =  $this->loginUser->id;
                    $posted_data['created_on'] =  date('Y-m-d');
                    $result = $this->db->insert('auction_live_settings', $posted_data);
                }

                if($result){
                    $this->session->set_flashdata('success', 'Auction setting has been updated successfully');
                    echo json_encode(array('error'=>false, 'message'=>'Setting has been updated'));
                }else{
                    echo json_encode(array('error'=>true, 'message'=>'record has not been updated'));
                }
            }
        }else{
            
            $this->template->load_admin('auction/live_auction_settings', $data);
        }
    }

    public function controller_view()
    {
        $data = array();

        // $this->load->view('auction/auction_controller/auction_controller', $data);
        $this->template->loadControllerView('auction/auction_controller/auction_controller', $data);
    }
    // Update a Auction

    public function update_bidding_rules()
    {
      $data = array();
      $id = '';
    if ($this->input->post()) 
    {
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'item_id',
                    'label' => 'Valid Item',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'auction_id',
                    'label' => 'Valid Auction',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'bid_start_price',
                    'label' => 'Bid Start Price',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'allowed_bids[]',
                    'label' => 'Bids Options',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'bid_start_time',
                    'label' => 'Start End Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'bid_end_time',
                    'label' => 'Bidding End Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'security',
                    'label' => 'Security',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

             echo json_encode(array( 'msg'=>'error', 'data' => validation_errors()));
        } 
        else
        { 

        
        $item_id = $this->input->post('item_id');
        $auction_id = $this->input->post('auction_id');
        
        if(!empty($item_id))
        {
           $posted_data = array(
                    // 'allowed_bids' => implode(",", $this->input->post('allowed_bids')),
                    'allowed_bids' => $this->input->post('allowed_bids'),
                    'bid_start_price' => $this->input->post('bid_start_price'),
                    'bid_start_time' => date('Y-m-d h:i:s',strtotime($this->input->post('bid_start_time'))),
                    'bid_end_time' => date('Y-m-d h:i:s',strtotime($this->input->post('bid_end_time'))),
                    'security' => $this->input->post('security'),
                    'status' => $this->input->post('status'),
                    'updated_by' => $this->loginUser->id
                    );
            $result = $this->auction_model->update_auction_item_bidding_rules($item_id,$auction_id,$posted_data);
            $msg_ = 'Item Bidding Rules Updated Successfully';
        }
          
          if($result)
          {
            // $this->session->set_flashdata('msg', $msg_);
            echo json_encode(array( 'msg'=>'success', 'data' => $msg_));
          }
          else
          {
            echo json_encode(array( 'msg'=>'error', 'data' => 'Error Found'));
          }
        }
      }
    

    }

    public function search_items()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 
        
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
            $sql_cat = "  item.category_id = ".$posted_data['category_id']."  ";

            $sql_cat .= " AND item.in_auction = 'no' ";
            $sql_cat .= " AND item.item_status = 'completed' ";
            $sql_cat .= " AND item.status = 'active' ";
        }
        
        if (isset($posted_data['days_filter']) && !empty($posted_data['days_filter'])) {
          
            $interval = (($posted_data['days_filter'] == 'today')) ? 0 : $posted_data['days_filter'];

            $sql[] = " (DATE(item.created_on)  >= DATE(NOW()) + INTERVAL ".$interval." DAY  AND DATE(item.created_on) <  NOW() + INTERVAL  0 DAY) ";
             
        }

        if (isset($posted_data['search']) && !empty($posted_data['search'])) {
 
            $sql[] = " item.lot_id like '%".$posted_data['search']."%' ";
            
            $sql[] = " item.registration_no like '%".$posted_data['search']."%' ";
            
            $sql[] = " item.name like '%".$posted_data['search']."%' ";

            $sql[] = " item.keyword like '%".$posted_data['search']."%' "; 
        }

        $query = "";
        if (!empty($sql)) {
            $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
        }
        else
        {
             $query .= $sql_cat;
        }


            $data['items_list'] = $this->items_model->items_filter_list_rules($query);
            $data['auction_id'] =  $this->input->post('auction_id');
            $data_view = $this->load->view('auction/auction_items/items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function view_auction_items($b64_uid)
    {
        $data = array();
        $auction_id = base64_decode(urldecode($b64_uid));
        $data['small_title'] = 'Items List';
        $data['current_page_auction'] = 'current-page';
        
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];

        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
        {
        $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
        $data['items_list'] = $this->items_model->get_active_item_list($item_ids_list);
        }
        else
        {
            $data['items_list'] = array();
        }
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['formaction_path'] = 'filter_items';
        $data['auction_id'] = $auction_id;
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $data['application_user_list'] = $this->users_model->get_application_users();
        $this->template->load_admin('auction/auction_items/items_list', $data);
    }

    public function view_live_auction_items($b64_uid)
    {
        $data = array();
        $auction_id = base64_decode(urldecode($b64_uid));
        $data['small_title'] = 'Items List';
        $data['current_page_live_auction'] = 'current-page';
        
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];

        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
        {
        $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
        $data['items_list'] = $this->items_model->get_active_item_list($item_ids_list);
        }
        else
        {
            $data['items_list'] = array();
        }
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['formaction_path'] = 'filter_items';
        $data['auction_id'] = $auction_id;
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $data['application_user_list'] = $this->users_model->get_application_users();
        $this->template->load_admin('auction/auction_items/live_items_list', $data);
    }

    public function search_items_oa()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 
        
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
            $sql_cat = "  item.category_id = ".$posted_data['category_id']."  ";

            $sql_cat .= " AND item.in_auction = 'no' ";
            $sql_cat .= " AND item.item_status = 'completed' ";
            $sql_cat .= " AND item.status = 'active' ";
        }
        
        if (isset($posted_data['days_filter']) && !empty($posted_data['days_filter'])) {
          
            $interval = (($posted_data['days_filter'] == 'today')) ? 0 : $posted_data['days_filter'];

            $sql[] = " (DATE(item.created_on)  >= DATE(NOW()) + INTERVAL ".$interval." DAY  AND DATE(item.created_on) <  NOW() + INTERVAL  0 DAY) ";
             
        }

        if (isset($posted_data['search']) && !empty($posted_data['search'])) {
 
            $sql[] = " item.lot_id like '%".$posted_data['search']."%' ";
            
            $sql[] = " item.registration_no like '%".$posted_data['search']."%' ";
            
            $sql[] = " item.name like '%".$posted_data['search']."%' ";

            $sql[] = " item.keyword like '%".$posted_data['search']."%' "; 
        }

        $query = "";
        if (!empty($sql)) {
            $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
        }
        else
        {
             $query .= $sql_cat;
        }


            $data['items_list'] = $this->items_model->items_filter_list_rules($query);
            $data['auction_id'] =  $this->input->post('auction_id');
            $data_view = $this->load->view('auction/auction_items/items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }


    // Update a Auction
    public function update_auction()
    {
        $data = array();
        $data['small_title'] = 'Update Auction';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'update_auction';
        $auction_id = $this->uri->segment(3);
        $data['auction_info'] = $this->auction_model->get_auctions($auction_id);  
        // print_r( $data['auction_info']);die(); 
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();

        if ($this->input->post()) {         
            $auction_data = $this->input->post();  
            if(isset($auction_data['access_type']) && $auction_data['access_type'] == 'closed')
            {
                $close_auction_users_rule =  array(
                                'field' => 'close_auction_users[]',
                                'label' => 'Buyer List',
                                'rules' => 'trim|required');         
            }
            else
            {

                $close_auction_users_rule = '';
            }
            $this->load->library('form_validation');
             $rules = array( 
                array(
                    'field' => 'title_english',
                    'label' => 'Title English',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'title_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                $close_auction_users_rule

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) 
            {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if(isset($auction_data['close_auction_users']) && !empty($auction_data['close_auction_users']))
                {
                $close_auction_users = implode(",", $auction_data['close_auction_users']);
                }
                else
                {
                $close_auction_users = '';
                }

                $id = $auction_data['id'];
                $posted_data = array( 
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'],
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'],
                'close_auction_users' => $close_auction_users,
                'start_time' => date('Y-m-d H:i:s',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i:s',strtotime($auction_data['expiry_time'])),
                'updated_by' => $this->loginUser->id
                );

                $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);


                if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                    $posted_data['detail'] = $auction_data['detail'];
                }

                if(isset($auction_data['security']) && !empty($auction_data['security']))
                {
                    $posted_data['security'] = $auction_data['security'];
                }
                if(isset($auction_data['category_id']) && !empty($auction_data['category_id']))
                {
                    $posted_data['category_id'] = $auction_data['category_id'];
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];
                }

                $result = $this->auction_model->update_auction($id,$posted_data);

                if (!empty($result)) 
                {
                  $this->session->set_flashdata('msg', 'Auction Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }
        else
        {

            $this->template->load_admin('auction/auction_form', $data);
        }

    }

    public function update__auction()
    {
        $data = array();
        $data['small_title'] = 'Update Live Auction';
        $data['current_page_live_auction'] = 'current-page';
        $data['formaction_path'] = 'update_live_auction';
        $auction_id = $this->uri->segment(3);
        $data['auction_info'] = $this->auction_model->get_auctions($auction_id);
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();

        if ($this->input->post()) {         
            $auction_data = $this->input->post();  
            
            $this->load->library('form_validation');
             $rules = array( 
                 array(
                    'field' => 'title_english',
                    'label' => 'Title English',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'title_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required')

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) 
            {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if(isset($auction_data['close_auction_users']) && !empty($auction_data['close_auction_users']))
                {
                $close_auction_users = implode(",", $auction_data['close_auction_users']);
                }
                else
                {
                $close_auction_users = '';
                }

                $id = $auction_data['id'];
                $posted_data = array( 
                // 'title' => $auction_data['title'],
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'],
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'],
                'close_auction_users' => $close_auction_users,
                'start_time' => date('Y-m-d H:i',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i',strtotime($auction_data['expiry_time'])),
                'updated_by' => $this->loginUser->id
                );

                 $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);


                if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                    $posted_data['detail'] = $auction_data['detail'];
                }
                
                // if(isset($auction_data['security']) && !empty($auction_data['security']))
                // {
                //     $posted_data['security'] = $auction_data['security'];
                // }
                if(isset($auction_data['category_id']) && !empty($auction_data['category_id']))
                {
                    $posted_data['category_id'] = $auction_data['category_id'];
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];
                }

                $result = $this->auction_model->update_auction($id,$posted_data);

                if (!empty($result)) 
                {
                  $this->session->set_flashdata('msg', 'Auction Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }
        else
        {

            $this->template->load_admin('auction/live_auction_form', $data);
        }

    }
 

    public function get_bidding_rules()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['auction_id'] = $this->input->post('auction_id');
        $data['bidding_info'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        $auction_bid_list_array = $this->auction_model->get_auctions($data['auction_id']); 
        $data['auction_bid_list'] = explode(",",$auction_bid_list_array[0]['bid_options']); 
         $data['auction_start_time'] = $auction_bid_list_array[0]['start_time'];
         $data['auction_expiry_time'] = $auction_bid_list_array[0]['expiry_time'];
        $data_view = $this->load->view('auction/auction_items/bidding_rules', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }
 

    // other_charges list
    public function other_charges($id)
    {
        $data = array();
        $data['small_title'] = 'Other Charges';
        $data['current_page_live_auction'] = 'current-page';
        // $data['formaction_path'] = 'update_auction';
        $auction_id = $this->uri->segment(3);
        $data['auction_id'] = $this->db->select('auction_id,item_id')->from('auction_items')->where('item_id', $id)->get()->row_array(); 
        // print_r($data);die();
        $data['list'] = $this->db->get_where('item_other_charges',['item_id' => $id])->result_array(); 

        $this->template->load_admin('auction/auction_items/other_charges', $data);

    }
    // add list
    public function add_other_details()
    {
        $data = array();
        if ($this->input->post()) {
            $posted_data = $this->input->post();
            $this->load->library('form_validation');
            $rules = array( 
                array(
                    'field' => 'description',
                    'label' => 'Detail',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'amount',
                    'label' => 'Amount',
                    'rules' => 'trim|required'),

            );  
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) 
            {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if (isset($posted_data['id']) && !empty($posted_data['id'])) {
                    $id = $posted_data['id'];
                    unset($posted_data['id']);
                    $result = $this->db->update('item_other_charges',$posted_data, ['id' => $id]);
                    if ($result) {
                        echo json_encode(array('msg'=>'success'));
                    }else{
                        echo json_encode(array('msg'=>'Error! fail to update please try again.'));
                    }
                }else{   
                    unset($posted_data['id']);
                    $posted_data['created_on'] = date('Y-m-d H:i:s');
                    $result = $this->db->insert('item_other_charges',$posted_data);
                    if ($result) {
                        echo json_encode(array('msg'=>'success', 'data' => ''));
                    }else{
                        echo json_encode(array('msg'=>'Error! fail to add please try again.', 'data' => ''));
                    }
                }
            }   

        }

        // $this->template->load_admin('auction/auction_items/other_charges', $data);

    }
    // add list
    public function get_other_details()
    {
        $id = $this->input->post('id');
        $result = $this->db->get_where('item_other_charges',['id' => $id])->row_array();
        if ($result) {
            echo json_encode(array('msg'=>'success', 'data' => $result));
        }else{
            echo json_encode(array('msg'=>'Error!', 'data' => ''));
        }

        // $this->template->load_admin('auction/auction_items/other_charges', $data);

    }
 
    //Delete Single Row
    public function delete( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "auctions") {
            $res = $this->auction_model->delete_auction_row($id);
            $res2 = $this->auction_model->delete_auction_item_rows($id);
        }

        if ($table == "auction_items") {
            $id_arr = explode("-",$id);
            $res = $this->auction_model->delete_item_row_from_auctionitems($id_arr[1],$id_arr[0]);
            $in_auction_array = array(
                    'in_auction' => 'no',
                    'updated_by' => $this->loginUser->id
                );
            $result_item_in_auction = $this->items_model->update_item($id_arr[0],$in_auction_array);
        } 

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

        // Update a auction Item
    public function update_live_auction_item()
    {
        $data = array();
        $data['small_title'] = 'Update Item';
        $data['formaction_path'] = 'update_live_auction_item';
        $data['current_page_live_auction'] = 'current-page';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);
     
        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid($item_id);
        // $data['seller_list'] = $this->users_model->users_list(3);
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        // print_r($item_dynamic_fields_info);
        if($item_dynamic_fields_info)
        {
          foreach ($item_dynamic_fields_info as $value) 
          {
            $multiple_info = $this->items_model->fields_multiple_info($value['fields_id']);
            $field_ids[] = $value['fields_id'];
            $field_values[] = $value['value'];
            $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
            // print_r($value['fields_id'].'  - '.$multiple_info[0]['multiple'].' ');
          }
            $data['field_ids'] = $field_ids;
            $data['field_values'] = $field_ids_list;
        }

         // print_r($field_ids_list);

        $data['category_list'] = $this->items_model->get_item_category_active();
        if ($this->input->post()) {

        $item_dynamic_data = $this->input->post();
         
        $item_data = $this->input->post('item'); // get basic information 
        unset($item_dynamic_data['item']);  // remove basic information form data
   

        $this->load->library('form_validation');
          $rules = array(
                array(
                    'field' => 'item[name]',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'item[keyword]',
                //     'label' => 'Keyword',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'item[status]',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'item[item_status]',
                    'label' => 'Item Status',
                    'rules' => 'trim|required')
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $id = $item_data['id'];
                 if(!empty($item_data['seller_id']))
                {
                    $seller_id = $item_data['seller_id'];
                }
                else
                {
                 $seller_id = $this->loginUser->id;   
                }

                $posted_data = array(
                'name' => $item_data['name'],
                'detail' => $item_data['detail'],
                'status' => $item_data['status'],
                'item_status' => $item_data['item_status'],
                'price' => $item_data['price'],
                // 'keyword' => $item_data['keyword'],
                'category_id' => $item_data['category_id'],
                'seller_id' => $seller_id,
                // 'auction_type' => implode(",", $item_data['auction_type']),
                'updated_by' => $this->loginUser->id
                );

                if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $item_data['subcategory_id'];
                }

                if(isset($item_data['keyword']) && !empty($item_data['keyword']))
                {
                    $posted_data['keyword'] = $item_data['keyword'];
                }

                if(isset($item_data['price']) && !empty($item_data['price']))
                {
                    $posted_data['price'] = $item_data['price'];
                }

                if(isset($item_data['registration_no']) && !empty($item_data['registration_no']))
                {
                    $posted_data['registration_no'] = $item_data['registration_no'];
                }

                if(isset($item_data['make']))
                {
                    $posted_data['make'] = $item_data['make'];
                    $posted_data['model'] = $item_data['model'];    
                }
                

                $result = $this->items_model->update_item($id,$posted_data);

                $item_row_array = $this->items_model->get_item_byid($id);

                if(empty($item_row_array[0]['barcode']))
                {
                    $path = "uploads/items_documents/".$id."/qrcode/";

                    // make path
                    if ( !is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    } 

                    $qrcode_name = $this->generate_code($id,$path, ['id'=>$id]);
                    if(!empty($qrcode_name))
                    {
                        $barcode_array = array(
                        'barcode' => $qrcode_name
                        );
                        $this->items_model->update_item($id,$barcode_array);
                    }
                }
               



                $result_attachments = array();
  

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
                    $category_id = $item_data['category_id'];
                    $fields_id = $ids_arr[0];
                    $check_if = $this->items_model->check_items_field_data($id,$item_data['category_id'],$ids_arr[0]);
                    // check if fields already exist or not then insert or update accordingly 
                    if($check_if)
                    {
                        $dynaic_information2 = array(
                        'category_id' => $category_id,
                        'item_id' => $id,
                        'fields_id' => $fields_id,
                        'value' => $dynamic_values_new,
                        'updated_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->update_item_fields_data($id,$category_id,$fields_id,$dynaic_information2);
                    }
                    else
                    {
                        $dynaic_information = array(
                        'category_id' => $item_data['category_id'],
                        'item_id' => $id,
                        'fields_id' => $ids_arr[0],
                        'value' => $dynamic_values_new,
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->insert_item_fields_data($dynaic_information);
                    }
                    
                }

                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result, 'attach' => $result_attachments));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }else{

            $this->template->load_admin('auction/auction_items/live_auction_item_form', $data);
        }

    }

    public function update_auction_with_users()
    {
        $data = array();
        $data['small_title'] = 'Update Auction';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'update_auction';
        $auction_id = $this->uri->segment(3);
        $data['auction_info'] = $this->auction_model->get_auctions($auction_id);  
        // print_r( $data['auction_info']);die(); 
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();

        if ($this->input->post()) {         
            $auction_data = $this->input->post();  
            if(isset($auction_data['access_type']) && $auction_data['access_type'] == 'closed')
            {
                $close_auction_users_rule =  array(
                                'field' => 'close_auction_users[]',
                                'label' => 'Buyer List',
                                'rules' => 'trim|required');         
            }
            else
            {

                $close_auction_users_rule = '';
            }
            $this->load->library('form_validation');
             $rules = array( 
                array(
                    'field' => 'title_english',
                    'label' => 'Title English',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'title_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                $close_auction_users_rule

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) 
            {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if(isset($auction_data['close_auction_users']) && !empty($auction_data['close_auction_users']))
                {
                $close_auction_users = implode(",", $auction_data['close_auction_users']);
                }
                else
                {
                $close_auction_users = '';
                }

                $id = $auction_data['id'];
                $posted_data = array( 
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'],
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'],
                'close_auction_users' => $close_auction_users,
                'start_time' => date('Y-m-d H:i',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i',strtotime($auction_data['expiry_time'])),
                'updated_by' => $this->loginUser->id
                );

                $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);


                if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                    $posted_data['detail'] = $auction_data['detail'];
                }

                if(isset($auction_data['security']) && !empty($auction_data['security']))
                {
                    $posted_data['security'] = $auction_data['security'];
                }
                if(isset($auction_data['category_id']) && !empty($auction_data['category_id']))
                {
                    $posted_data['category_id'] = $auction_data['category_id'];
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];
                }

                $result = $this->auction_model->update_auction($id,$posted_data);

                if ($result) 
                {
                    // echo($result);die();
                  $this->session->set_flashdata('msg', 'Auction Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  exit();
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                    exit();
                }
            }
        }
        else
        {

            $this->template->load_admin('auction/auction_form', $data);
        }

    }

    public function deposit_users_list_restricted()
    {
        $posted_data = $this->input->post();
        $data = array();
        // return ;
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value

        $keyword = (isset($posted_data['searchby']))? $posted_data['searchby'] : '';
        $search_arr = array();
         $searchQuery = "";
        if($keyword != ''){
            $search_arr[] = " fname like '%".$keyword."%' or mobile like '%".$keyword."%' ";
        }

        if(count($search_arr) > 0){
            $searchQuery = implode(" AND ",$search_arr);
        }
        $auction_id = $this->uri->segment(3);

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
   
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $this->db->select('auction_deposit.*,users.fname,users.mobile,users.email');
   
        
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $this->db->limit($rowperpage, $start);
        // $this->db->order_by($columnName, $columnSortOrder);
        $users_list = $this->db->get()->result_array();

        $action = '';
        // $users_list = $this->db->get_where('users', ['role' => 4])->result_array();

        foreach ($users_list as $key => $value) {
            $action = '<a href="#" onclick="myfunc(this)" data-id="'.$value['id'].'" data-amount="'.$value['amount'].'" data-user_id="'.$value['user_id'].'" data-name="'.$value['fname'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            $data[] = array( 
             "id"=>$value['id'],
             "email"=>$value['email'],
             "amount"=>$value['amount'],
             "fname"=>$value['fname'],
             "mobile"=>$value['mobile'],
             "created_on"=>$value['created_on'],
            "action"=> $action
            ); 
        }

        $response = array(
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "draw" => intval($draw),
            "aaData" => $data
        );

        echo json_encode($response); 

    }


    // Add a new Auction
    public function save_auction()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Auction';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'save_auction';
        // $auction_id = $this->uri->segment(3);
        // $data['auction_info'] = $this->auction_model->get_auctions($auction_id);
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();

        if ($this->input->post()) {
        $auction_data = $this->input->post();
        
        if(isset($auction_data['access_type']) && $auction_data['access_type'] == 'closed')
        {
            $close_auction_users_rule =  array(
                            'field' => 'close_auction_users[]',
                            'label' => 'Buyer List',
                            'rules' => 'trim|required');         
        }
        else
        {

            $close_auction_users_rule = '';
        }

        $this->load->library('form_validation');
            $rules = array(
               array(
                    'field' => 'title_english',
                    'label' => 'Title English',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'title_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'security',
                //     'label' => 'Security',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                $close_auction_users_rule

            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if(isset($auction_data['close_auction_users']) && !empty($auction_data['close_auction_users']))
                {
                    $close_auction_users = implode(",", $auction_data['close_auction_users']);
                }
                else
                {
                    $close_auction_users = '';
                }

                $posted_data = array(
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'],
                // 'access_type' => implode(",", $auction_data['access_type']),
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'],
                // 'security' => $auction_data['security'],
                // 'view_only' => $auction_data['view_only'],
                'close_auction_users' => $close_auction_users,
                'start_time' => date('Y-m-d H:i:s',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i:s',strtotime($auction_data['expiry_time'])),
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->loginUser->id
                );

                   $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);


                if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                    $posted_data['detail'] = $auction_data['detail'];
                }

                if(isset($auction_data['security']) && !empty($auction_data['security'])){
                    $posted_data['security'] = $auction_data['security'];
                }

                if(isset($auction_data['category_id']) && !empty($auction_data['category_id'])){
                    $posted_data['category_id'] = $auction_data['category_id'];
                    
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id'])){
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];

                }

                $result = $this->auction_model->insert_auction($posted_data);
                if (!empty($result))
                {
                  $this->session->set_flashdata('msg', 'Auction Added Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }
        else
        {
            $this->template->load_admin('auction/auction_form', $data);
        }

    }
    // Add a new items to Auction
    public function add_auction_items()
    {
        $data = array();
        // $LottingNumberRand  = getNumber(4); 
        $topAuctionItem = $this->db->order_by('id','desc')->get('auction_items', 1)->row_array();
        $LottingNumberRand = $topAuctionItem['lot_no'] +  1;
        $dynaic_information = array();
        if ($this->input->post()) {
        $auction_data = $this->input->post();
                
                $items_array = explode(",",$auction_data['ids_list']);
                foreach ($items_array as $value) {
                $posted_data = array(
                'auction_id' => $auction_data['auction_id'],
                'category_id' => $auction_data['category_id'],
                'item_id' => $value,
                'lot_no' => $LottingNumberRand,
                'created_on' => date('Y-m-d h:i:s'),
                'created_by' => $this->loginUser->id
                );
                $result = $this->auction_model->insert_auction_items($posted_data);
                
                $in_auction_array = array(
                    'in_auction' => 'yes',
                    'updated_by' => $this->loginUser->id
                );
                
                $result_item_in_auction = $this->items_model->update_item($value,$in_auction_array);

                }
                
                if (!empty($result))
                {

                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'data' => 'Items added to auction successfully'));
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'data' => $result));
                }
        }
        else
        {
            $this->template->load_admin('auction/auction_form', $data);
        }

    } 


    public function loa_item_data($item_id)
    {
         //auction item details
        $data['item'] = $this->oam->get_items_details($item_id,$auction_id);

        //items category dynamic fields
        $datafields = $this->oam->fields_data($data['item']['category_id']);
        $fdata = array();
        foreach ($datafields as $key => $fields)
        {
            $item_dynamic_fields_info = $this->oam->get_itemfields_byItemid($item_id,$fields['id']);
            $fields['values'] = json_decode($fields['values'],true);  
            $fields['data-id'] = $fields['id'];
            if (!empty($fields['values'])) {
                foreach ($fields['values'] as $key => $options) {
                    if ($options['value'] == $item_dynamic_fields_info['value']) {
                        $fields['data-value'] = $options['label'];
                    }
                }
            }else{
                $fields['data-value'] = $item_dynamic_fields_info['value'];
            }
            $fdata[] = $fields;
        }
        $data['fields'] = $fdata;

        // get contact us details
        $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();

        //highest bid data
        $data['heighest_bid_data'] =  $this->oam->item_heighest_bid_data($item_id,$auction_id);

        //related items
        $item_ids = [];
        $related_items = $this->db->where('item_id !=', $item_id)->limit(10)->get_where('auction_items',['auction_id' => $auction_id])->result_array();
        foreach ($related_items as $key => $v) {
            array_push($item_ids, $v['item_id']);
        }
        $data['related_items'] = $this->oam->get_online_auction_items($auction_id, 10, 0, $item_ids);
        
        //get total bid count of this item
        $data['bid_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('bid')->count_all_results();
          
        //count total users view this item
        $data['visit_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('online_auction_item_visits')->count_all_results();
       
    }


    public function get_qrcode()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $item_row = $this->items_model->get_item_byid($data['item_id']); 
        $image = base_url()."uploads/items_documents/".$data['item_id']."/qrcode/".$item_row[0]['barcode'];
        $data_view = '<div class="row" style="margin-left:10px;"><h3 class="col-md-10">'.json_decode($item_row[0]['name'])->english.'</h3><img src="'.$image.'" alt="" title="QR Code"/></div>';
         $data_view .= '<div class="product_price col-md-4" style="margin-left:10px;"><h2 class=""> '.$item_row[0]['registration_no'].' </h2></div>';
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function update_files_order()
    {
        $data = array();
        if($this->input->post('images_orders'))
        {
        $data_array = $this->input->post('images_orders');
        foreach ($data_array as $file_id_key => $file_order_value) 
        {
            
            $update_data = array(
                'file_order' => $file_order_value
            );
            $result = $this->files_model->update_file($file_id_key,$update_data);
            
        }
        }
        if($this->input->post('documents_order'))
        {
        $data_array = $this->input->post('documents_order');
        foreach ($data_array as $file_id_key => $file_order_value) 
        {
            
            $update_data = array(
                'file_order' => $file_order_value
            );
            $result = $this->files_model->update_file($file_id_key,$update_data);
            
        }
        }
        if($result)
        {
        $data_message = "Files Order has been updated";
        echo json_encode(array( 'msg'=>'success', 'response' => $data_message));
        }
        else
        {
        $data_message = "Some Error Found";
        echo json_encode(array( 'msg'=>'error', 'response' => $data_message));   
        }
    }

    //Delete Single Row
    public function delete_bulk( $ids_array = array() )
    {
        $ids_array = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "auctions") {
            $res = $this->auction_model->delete_auction_MultipleRow($ids_array);
            $res2 = $this->auction_model->delete_auction_items_multiple_row($ids_array);
        }

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }
 

      public function load_user_popup()
    {
        // echo "kdjcnskdnc";
        $posted_data = $this->input->post();
        $users_list['deposite_list'] = $this->db->get_where('users', ['role' => 4])->result_array();
        $opup_data = $this->load->view('deposite/deposite_list',  $users_list, true);
        // print_r($opup_data);die();
        if ($opup_data) {
            $output = json_encode([
                'success' => true,
                'opup_data' => $opup_data,
                'msg' => 'User data get successfully.' ]);
            return print_r($output);
        }
        else
        {
            echo "sdvk";
        }

    }

     //users
    public function load_user()
    {
        $posted_data = $this->input->post();
        $data = array();
        // return ;
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value

        $keyword = (isset($posted_data['searchby1']))? $posted_data['searchby1'] : '';
        $search_arr = array();
         $searchQuery = "";
        if($keyword != ''){
            $search_arr[] = " fname like '%".$keyword."%' or mobile like '%".$keyword."%' ";
        }
        // print_r($rowperpage);die();
        if(count($search_arr) > 0){
            $searchQuery = implode(" AND ",$search_arr);
        }

                ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->where('role', 4);
         $records = $this->db->get('users')->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != ''){
            $this->db->where($searchQuery);
        }
         $this->db->where('role', 4);
        $records = $this->db->get('users')->result();
        $totalRecordwithFilter = $records[0]->allcount;
        if($searchQuery != ''){
        $this->db->where($searchQuery);
        }
         $this->db->where('role',4);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $users_list = $this->db->get('users')->result_array();
        foreach ($users_list as $key => $value) {
 
        $data[] = array( 
         "id"=>$value['id'],
         "fname"=>$value['fname'],
         "mobile"=>$value['mobile']
       ); 
        }

        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordwithFilter,
           "aaData" => $data
        );

        echo json_encode($response); 
    }

    public function get_bid_log(){
        $output = '';
        $language = $this->language;
        $auction_id = $this->input->post('auctionId');
        $item_id = $this->input->post('itemId');
        // $bid_amount = $this->input->post('bid_amount');

        $bidLog = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id])->result_array();

        $i=0;
        $output .= '<ul>';
        foreach ($bidLog as $bidLogValue) {
            $i++;

            $active = '';
            if ($i == 1) {
                $active = 'active';
            }

            $icon = '<i><img style="width: 14px;" src="'.base_url("assets_admin/images/law-gray.svg").'"></i>';
            $color = 'class="text-primary"';
            
            $bidder = $this->lang->line('hall_bidder');
            $bid_type = $this->lang->line('hall_bid');
            if ($bidLogValue['bid_type'] == 'online') {
                $icon = '<i><img style="width: 14px;" src="'.base_url("assets_admin/images/law-red.svg").'"></i>';
                $color = 'class="text-default"'; 
                $bidder = $this->lang->line('online_bidder');
                $bid_type = $this->lang->line('online_bid');
            }

            if ($bidLogValue['bid_status'] == 'win') {
                $icon = '<i class="fa fa-check"></i>';
                $color = 'style="color: green;"';
                $item = $this->db->get_where('item', ['id' => $bidLogValue['item_id']])->row_array();
                $item_name = json_decode($item['name']);
                //$output.= '<li class="'.$active.' '.$color.'">'.$icon.$item['name'].' sold to '.$bidder.' for AED '.$bidLogValue['bid_amount'].' at '.$bidLogValue['created_on'].'</li>';
                if($language=='arabic'):

                $_showListSold = $icon.' '.$this->lang->line('sold_to_new').' '.$item_name->$language.' '.$bidder.' '.number_format($bidLogValue['bid_amount'] , 0, ".", ",").' '.$this->lang->line('aed');


            else:
                $_showListSold = $icon.$item_name->$language.' '.$this->lang->line('sold_to').' '.$bidder.' '.$this->lang->line('for_aed_new').' '.number_format($bidLogValue['bid_amount'] , 0, ".", ",");

            endif;

                $output .= '<li '.$color.'><span>'.date('h:i A', strtotime($bidLogValue['created_on'])).'</span>'.$_showListSold.'</li>';
            }
            
            //$output.= '<li class="'.$active.' '.$color.'">'.$icon.' '.$bid_type.' '.$bidLogValue['bid_increment_amount'].' '.$bidLogValue['created_on'].'</li>';
            if($language=='arabic'):

                $_showList = $icon.$bid_type.' '.number_format($bidLogValue['bid_amount'] , 0, ".", ",").' '.$this->lang->line('aed');

            else:
                $_showList = $icon.$bid_type.' '.$this->lang->line('aed').' '.number_format($bidLogValue['bid_amount'] , 0, ".", ",");

            endif;
            $output .= '<li '.$color.'><span>'.date('h:i A', strtotime($bidLogValue['created_on'])).'</span>'.$_showList.'</li>';
        }
        $output .= '</ul>';
        echo json_encode(array('error'=>false,'response'=>$output));
    }

    public function get_all_lots()
    {
        $data = $this->input->post();
        if($data){
            $all_lots = $this->db->get_where('auction_items', ['auction_id' => $data['auctionId']]);
            if($all_lots->num_rows() > 0){
                $all_lots = $all_lots->result_array();
                $response = $this->load->view('auction/online_auction/tblAllLots', 
                    ['all_lots' => $all_lots, 'language' => $this->language], true);

                $output = ['status' => true, 'response' => $response];
            }else{
                $output = ['status' => false];
            }
        }else{
            $output = ['status' => false];
        }
        echo json_encode($output);
    }

    public function get_winning_lots()
    {
        $data = $this->input->post();
        if($data){
            $all_lots = $this->db->get_where('auction_items', ['auction_id' => $data['auctionId'], 'sold_status' => 'sold']);
            if($all_lots->num_rows() > 0){
                $all_lots = $all_lots->result_array();
                $response = $this->load->view('auction/online_auction/tblAllLots', ['all_lots' => $all_lots, 'language' => $this->language], true);
                $output = ['status' => true, 'response' => $response];
            }else{
                $output = ['status' => false];
            }
        }else{
            $output = ['status' => false];
        }
        echo json_encode($output);
    }

    public function get_hall_auto_bids()
    {
        $data = $this->input->post();
        if($data){
            $user = $this->session->userdata('logged_in');
            if($user){
                $auto_bids = $this->db->get_where('bid_auto', ['auction_id' => $data['auctionId'], 'user_id' => $user->id]);
                if($auto_bids->num_rows() > 0){
                    $auto_bids = $auto_bids->result_array();
                    $response = $this->load->view('auction/online_auction/hallAutoBids', ['auto_bids' => $auto_bids, 'language' => $this->language], true);
                    $output = ['status' => true, 'response' => $response];
                }else{
                    $output = ['status' => false];
                }
            }else{
                $output = ['status' => false];
            }
        }else{
            $output = ['status' => false];
        }
        echo json_encode($output);
    }

    public function get_current_lot()
    {
        $data = $this->input->post();
        if($data){
            $current_lot = $this->oam->get_items_details($data['itemId'], $data['auctionId']);
            if($current_lot['category_id'] == 1){
                $make = $this->db->get_where('item_makes', ['id' => $current_lot['item_make']])->row_array();
                $model = $this->db->get_where('item_models', ['id' => $current_lot['item_model']])->row_array();
                $current_lot['vehicle'] = true;
                $current_lot['make'] = $make;
                $current_lot['model'] = $model;
            }
            $output = ['status' => true, 'response' => $current_lot];
        }else{
            $output = ['status' => false];
        }
        echo json_encode($output);
    }

    public function get_upcoming_auctions()
    {
        $data = $this->input->post();
        if($data){
            $auction_items = $this->lam->get_live_auction_items($data['auctionId']);
            $response = $this->template->load_user_ajax('auction/online_auction/new/sliderUpcomingAuction', ['auction_items' => $auction_items], true);
            $output = ['status' => true, 'response' => $response];
        }else{
            $output = ['status' => false];
        }
        echo json_encode($output);
    }

    public function place_bid_live()
    {
        $data = $this->input->post();
        if($data){
            $check_auction = $this->db->get_where('auctions', ['id' => $data['auction_id']])->row_array();
            if ($check_auction['start_status'] == 'stop' || $check_auction['expiry_time'] < date('Y-m-d H:i:s')) {
                echo json_encode(array('result' =>'false','status' => false,'message' => 'Bidding is stoped by admin.'));
                exit(); 
            }

            //return print_r($data);

            //check user is logged in or not
            $user = $this->session->userdata('logged_in');
            if($user){

                //last bid data
                $last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $data['auction_id'], 'item_id' => $data['item_id']])->row_array();
                //return print_r($last_bid);
                $heighest_bids = $this->oam->used_bid_limit($user->id, $data['item_id']);
                $currentBalance = $this->customer_model->user_balance($user->id);
                $new_bid_amount = $last_bid['bid_amount'] + $data['bid_amount'];
                $new_bid_limit = $new_bid_amount;
                $totalBalanceRequiredToPlaceBid = (int)($new_bid_limit) + (!empty($heighest_bids['total_bid']) ? (int)$heighest_bids['total_bid'] : 0);
                $currentBidAmount = $currentBalance['amount']*10;
                // print_r($heighest_bids);
                // echo '<br>';
                // print_r($totalBalanceRequiredToPlaceBid);
                // print_r($currentBidAmount);
                // die();
                // echo '<br>';
                // print_r($currentBalance['amount']*10);die();
                if ($totalBalanceRequiredToPlaceBid > $currentBidAmount) {
                    $refundableBalance = ((int)$currentBidAmount - (int)$heighest_bids['total_bid']);
                    if ($last_bid['user_id'] == $user->id) {
                        $refundableBalance = $refundableBalance - $last_bid['bid_amount'];
                    }
                    $refundableBalance = ($refundableBalance > 0) ? $refundableBalance : 0;
                    // print_r($refundableBalance);die();
                    // $this->session->set_flashdata('error', $this->lang->line('you_have_reached_your_bid_limit').$refundableBalance);
                    echo json_encode(array('result' =>'false','status' => 'limitExceed','msg'=> $this->lang->line('you_have_reached_your_bid_limit').$refundableBalance.'.'));
                    exit();
                } else {

                    //if controller not added initial price yet
                    if (empty($last_bid)) {
                        $output = ['status' => 'not_initialized', 'message' => $this->lang->line('auction_not_initialized')];
                        echo json_encode($output);
                        exit();
                    }
                    
                    //if item is already sold out
                    if (in_array($last_bid['bid_status'], ['win','not_sold'])) {
                        $output = ['status' => 'soldout', 'message' => $this->lang->line('item_sold_out')];
                        echo json_encode($output);
                        exit();
                    }

                    //calculate bid total amount and increment amount
                    $bid_total_amount = (float)$last_bid['bid_amount'] + (float)$data['bid_amount'];
                    //return print_r($bid_total_amount);

                    if($bid_total_amount > $data['max_bid_limit']){
                        $output = ['status' => 'limitCross', 'message' => $this->lang->line('insufficient_balance')];
                        echo json_encode($output);
                        exit();
                    }

                    //get item data
                    $auction_item = $this->lam->get_live_auction_items($data['auction_id'],'','',[$data['item_id']]);
                    if($auction_item){
                        $auction_item = $auction_item[0];

                        //create bid data
                        $bid = [
                            'auction_id' => $data['auction_id'],
                            'item_id' => $data['item_id'],
                            'lot_no' => $auction_item['order_lot_no'],
                            'bid_type' => 'online',
                            'initial_priority_type' => 'no',
                            'user_id' => $user->id,
                            'bid_amount' => $bid_total_amount,
                            'bid_status' => 'bid',
                            'bid_increment_amount' => $data['bid_amount'],
                            'seller_id' => $auction_item['item_seller_id'],
                            'created_on' => date('Y-m-d H:i:s')
                        ];
                        $result = $this->db->insert('live_auction_bid_log', $bid);
                    }else{
                        $result = [];
                    }

                    if($result){

                        // $pusher_data = $this->broadcast_pusher($bid['item_id'], $bid['auction_id']);
                        //return print_r($pusher_data);
                        $pusher_data['item_id'] = $bid['item_id'];
                        $pusher_data['auction_id'] =$bid['auction_id'];
                        
                        //broadcast pusher data
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
                        $pusher->trigger('ci_pusher', 'live-event', $pusher_data);

                        //make auto_status to stop in bid_auto table for all those entries 
                        //which has less limit than current bid amount.
                        $this->db->update('bid_auto', ['auto_status' => 'stop'],
                                            ['auction_id' => $data['auction_id'],
                                                'item_id' => $data['item_id'],
                                                'bid_limit <' => $bid_total_amount
                                            ]
                                        );

                        //auto bidders loop
                        do {
                            //get all auto bidders
                            $auto_bidders = $this->db->get_where('bid_auto', ['item_id' => $data['item_id'], 'auction_id' => $data['auction_id'], 'auto_status' => 'start']);
                            //get count of all auto bidders
                            $count = $auto_bidders->num_rows();
                            if($count > 0){
                                $auto_bidders = $auto_bidders->result_array();
                                //loop on all auto bidders
                                foreach ($auto_bidders as $key => $value) {
                                    //get latest bid data
                                    $bid_amount = $this->db->order_by('id', 'desc')->get_where('live_auction_bid_log', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id']])->row_array();
                                    $bid_price = $bid_amount['bid_amount'] + $value['bid_increment'];
                                    //if current auto bidder limit is greater or equal than current bid price
                                    if ($value['bid_limit'] >= $bid_price) {
                                        //if last bid user not equal to current bid user
                                        if ($value['user_id'] != $bid_amount['user_id']) {
                                            
                                            $auto_bid = [
                                                'auction_id' => $data['auction_id'],
                                                'item_id' => $data['item_id'],
                                                'lot_no' => $bid['lot_no'],
                                                'bid_type' => 'online',
                                                'initial_priority_type' => 'no',
                                                'user_id' => $user->id,
                                                'bid_amount' => $bid_price,
                                                'bid_status' => 'bid',
                                                'bid_increment_amount' => $value['bid_increment'],
                                                'seller_id' => $bid['seller_id'],
                                                'created_on' => date('Y-m-d H:i:s')
                                            ];

                                            $res = $this->db->insert('live_auction_bid_log', $auto_bid);
                                            if ($res) {
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

                                                // $pusher_data = $this->broadcast_pusher($auto_bid['item_id'], $auto_bid['auction_id']);

                                                $pusher_data['item_id'] = $auto_bid['item_id'];
                                                $pusher_data['auction_id'] =$auto_bid['auction_id'];
                                                $userdata = $this->db->select('username')->get_where('users', ['id' => $value['user_id']])->row_array();
                                                $pusher_data['username'] = $userdata['username'];
                                                $pusher_data['user_id'] = $bid['user_id'];
                                                $pusher_data['buyer_id'] = $user->id;
                                                $pusher->trigger('ci_pusher', 'live-event', $pusher_data);
                                            }


                                            /*$auto_bidder = $this->db->get_where('bid_auto', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id'],'user_id' =>$bid_amount['user_id']])->row_array();
                                            if (!$auto_bidder) {
                                                //Send eamil  
                                                $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                                $out_user = $this->db->select('email,fname')->get_where('users', ['id' => $bid_amount['user_id']])->row_array();
                                                $vars = array(
                                                    '{username}' => $out_user['fname'],
                                                    '{item_name}' => '<a href="'.$item_link.'">'.ucwords($bid_data['name']).'</a>',
                                                    '{year}' => $bid_data['year'],
                                                    '{bid_price}' => $bid_amount['bid_amount'],
                                                    '{lot_number}' => $auction_item_data['order_lot_no'],
                                                    '{registration_number}' => $bid_data['registration_no'],
                                                    '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                                );
                                                $email = $out_user['email'];
                                                $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                            }*/
                                        }
                                    }else{
                                        //update status of auto bidder to stop if bid cross the bidder limit.
                                        $update = $this->db->update('bid_auto', ['auto_status' => 'stop'],['id' => $value['id']] );

                                        //email to auto bidder of out bid
                                        /*if ($update) {
                                            //Send eamil  
                                            $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                            $out_user = $this->db->select('email,fname')->get_where('users', ['id' => $value['user_id']])->row_array();
                                            $vars = array(
                                                '{username}' => $out_user['fname'],
                                                '{item_name}' => '<a href="'.$item_link.'">'.ucwords($bid_data['name']).'</a>',
                                                '{year}' => $bid_data['year'],
                                                '{bid_price}' => $bid_amount['bid_amount'],
                                                '{lot_number}' => $auction_item_data['order_lot_no'],
                                                '{registration_number}' => $bid_data['registration_no'],
                                                '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                            );
                                            $email = $out_user['email'];
                                            
                                            $email = $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                        }*/

                                        //pusher
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
                                        $pusher_data['auction_id'] =$data['auction_id'];
                                        $push['item_id'] = $data['item_id'];
                                        $push['user_id'] = $value['user_id'];
                                        $push['status'] = 'stop';
                                        $pusher->trigger('ci_pusher', 'live-event', $push);
                                    }
                                }
                            }
                        } while ($count > 1);

                        $bid_success = $this->lang->line('bid_success');
                        $output = ['status' => true, 'message' => $bid_success];
                    }else{
                        $output = ['status' => 'info', 'message' => $this->lang->line('bid_failed')];
                    }
                }
            }else{
                //user not logged in
                $output = [
                    'status' => false, 
                    'userNotLogin' => true, 
                    'rurl' => base_url('live-online/'.$data['auction_id']),
                    'message' => $this->lang->line('you_need_login')
                ];
            }
        }else{
            //data not received from user end
            $output = ['status' => false, 'message' => $this->lang->line('some_information_missed')];
        }
        echo json_encode($output);
    }

    public function broadcast_pusher($item_id, $auction_id){
        
        //item data
        $item = $this->lam->get_live_auction_items($auction_id,'','',[$item_id]);
        $item = $item[0];
        
        if($item['item_make_model'] == 'yes'){
            $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array();
            $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array();
            $item['vehicle'] = true;
            $item['make'] = $make;
            $item['model'] = $model;
        }
        
        //item images
        $images = explode(',', $item['item_images']);
        $item_images = $this->files->get_multiple_files_by_ids($images);

        //current bid data
        $current_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id, 'item_id' => $item_id])->row_array();
        
        // //item category fields
        // $item_category_fields = $this->db->get_where('item_category_fields',['category_id' => $item['category_id'],'name' => 'transmission'])->result_array(); 
        // $item_fields_data = array();
        // if ($item_category_fields) {
        //     $item_fields_data = $this->db->get_where('item_fields_data',['fields_id' =>$item_category_fields[0]['id']])->row_array();
        // }

        $select = "sort_catagories.sort_id AS sortId, item_category_fields.label AS fieldName, item_fields_data.value AS fieldValue";
        $sortedCateFields = $this->db->select($select)->from('sort_catagories')
            ->join('item_category_fields', 'sort_catagories.field_id = item_category_fields.id')
            ->join('item_fields_data', 'item_category_fields.id = item_fields_data.fields_id')
            ->where(['sort_catagories.category_id' => $item['category_id'], 'item_fields_data.item_id' => $item_id])
            ->order_by('sort_catagories.sort_id', 'ASC')
            ->get()->result_array();
        $item_fields_data = array();
        if($sortedCateFields){ 
            foreach ($sortedCateFields as $key => $field) {
                $fieldNames = explode("|", $field['fieldName']);
                $item_fields_data[$key]['fieldName'] = ($fieldNames[0]) ? $fieldNames[0] : '';
                $fieldValues = explode("|", $field['fieldValue']);
                $item_fields_data[$key]['fieldValue'] = ($fieldValues[0]) ? $fieldValues[0] : '';
            } 
        }

        //auction data
        $auction = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();

        $push['item_id'] = $item_id;
        $push['auction_id'] =$auction_id;
        $push['auction'] = $auction;
        $push['cb_amount'] = $current_bid['bid_amount'];
        $push['current_bid'] = $current_bid;
        $push['data'] = $item;
        $push['item_fields_data'] = $item_fields_data;
        $push['item_images'] = $item_images;
        $push['lot_number'] = $item['order_lot_no'];

        return $push;
    }

}
