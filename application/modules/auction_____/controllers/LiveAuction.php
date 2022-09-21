<?php defined('BASEPATH') or exit('No direct script access allowed');
class LiveAuction extends MY_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Live_auction_model', 'lam');
        $this->load->model('customer/Customer_model', 'customer_model');
    }

    public function index()
    {
        $data = [];
        // $data['auction_id'] = $id;
        $data['live_auctions'] = $this->lam->get_live_auctions();
        foreach ($data['live_auctions'] as $key => $value) {
            $count = $this->db->where('auction_id',$value['id'])->from('auction_items')->count_all_results();
            $data['live_auctions'][$key]['item_count'] = $count;
        }
        // print_r($data['live_auctions']);die();
        // $data['selected_auction'] = $this->db->get_where('auctions', ['id' => $id])->row_array();
        // $data['item_category_fields'] = $this->db->get_where('item_category_fields', ['category_id' => $data['selected_auction']['category_id']])->result_array();
        $this->template->load_user('auction/live_auction/auction-page', $data);
    }

    public function items($id)
    {
        //echo "xxxx";die();
        $data = [];
        $data['auction_id'] = $id;
        $data['live_auctions'] = $this->lam->get_live_auctions();
        $data['selected_auction'] = $this->db->get_where('auctions', ['id' => $id])->row_array();
        $data['item_category_fields'] = $this->db->get_where('item_category_fields', ['category_id' => $data['selected_auction']['category_id']])->result_array();
        //$data['auction_items'] = $this->oam->get_online_auction_items($id,2);
        //$data['count'] = count($data['auction_items']);
        //$data['auction_items_count'] = count($this->oam->get_online_auction_items($id));


        // add custom 

        $datafields = $this->lam->fields_data($id, $data['selected_auction']['category_id']);
        $fdata = array();
        foreach ($datafields as $key => $fields)
        {
            $item_dynamic_fields_info = $this->lam->get_itemfields_byItemid($id,$fields['id']);
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

        //
        

        //print_r($data);die();
        $this->template->load_user('auction/live_auction/live-auction', $data);
    }

    public function item_detail($auction_id,$item_id)
    {
        //head offce contact details
        $item_ids = array();
        $user = $this->session->userdata('logged_in');
        if ($user) {
            $visit_data = array(
                'user_id' => $user->id,
                'auction_id' => $auction_id,
                'item_id' => $item_id
            );
            $visit_info = $this->db->get_where('online_auction_item_visits', $visit_data)->row_array();
            if (empty($visit_info)) {
                $visit = $this->db->insert('online_auction_item_visits', $visit_data);
            }
            $data['balance']=$this->customer_model->user_balance($user->id);
            $data['contact'] = $this->db->select('mobile,phone')->get('contact_us')->row_array();
            // get item details
            $data['item'] = $this->oam->get_items_details($item_id,$auction_id);
            $related_items = $this->db->where('item_id !=', $item_id)->limit(4)->get_where('auction_items',['auction_id' => $auction_id])->result_array();
            foreach ($related_items as $key => $v) {
                    array_push($item_ids, $v['item_id']);
                }
            $data['related_items'] = $this->oam->get_online_auction_items($auction_id, 0, 0, $item_ids);
            // print_r($data['related_items']);die();
            //get bid log count
            $data['bid_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('bid')->count_all_results();
            $data['visit_count'] = $this->db->where('item_id',$item_id)->where('auction_id',$auction_id)->from('online_auction_item_visits')->count_all_results();
            $this->template->load_user('auction/live_auction/item_detail', $data);
        }else{
            redirect(base_url().'home/login');
        }
    }

    public function placebid()
    {
        $data = $this->input->post();
        if($data){
            $user = $this->session->userdata('logged_in');
            if($user){
                $data['user_id'] = $user->id;
                $data['buyer_id'] = $user->id;
                $bid_amount = $this->db->order_by('id', 'desc')->get_where('bid', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id']])->row_array();
                if (! empty($bid_amount)) {
                    $data['bid_amount'] = $bid_amount['bid_amount'] + $data['bid_amount'];
                }else{
                    $a_item = $this->db->get_where('auction_items', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id']])->row_array();
                    $data['bid_amount'] = $a_item['bid_start_price'] + $data['bid_amount'];
                }
                $data['end_price'] = $data['bid_amount'];
                $data['date'] = date('Y-m-d');
                $data['bid_status'] = 'pending';
                // print_r($data);die();
                $result = $this->db->insert('bid', $data);
                if ($result) {
                    $output = json_encode([
                        'status' => 'success',
                        'msg' => 'Bid successfully.',
                        'current_bid' => $data['bid_amount']
                    ]);
                }else{
                    $output = json_encode([
                        'status' => 'fail',
                        'msg' => 'Bid failed. Try again'
                    ]);
                }
                return print_r($output);
            }else{
                echo '0';
            }
        }
    }

    public function do_favt()
    {
        $data = $this->input->post();
        if($data){
            //print_r($data);
            $user = $this->session->userdata('logged_in');
            if($user){
                $alread_favt = $this->db->get_where('favorites_items', ['user_id' => $user->id, 'item_id' => $data['item_id']]);
                if ($alread_favt->num_rows()) {
                    $this->db->delete('favorites_items', ['user_id' => $user->id, 'item_id' => $data['item_id']]);
                    $msg = 'remove_heart';
                }else{
                    //print_r($user);
                    $this->db->insert('favorites_items', ['user_id' => $user->id, 'item_id' => $data['item_id']]);
                    $msg = 'do_heart';
                }
                echo $msg;
            }else{
                echo '0';
            }
        }
    }

    public function ai_load_more()
    {
        $data = $this->input->post();
        if($data){
            $order_by = '';
            $id = $data['auction_id'];
            $search = $data['search'];
            if (!empty($data['sort'])) {
                $order_by = $data['sort'];
            }

            $item_ids = [];
            if (!empty($search)) {
                $this->db->select('item.id');
                $this->db->from('item');
                $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                $this->db->where(" item.name like '%".$search."%' or item.price like '%".$search."%' or item.detail like '%".$search."%' or make like '%".$search."%' or model like '%".$search."%' ");
                // $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids, $v['id']);
                }
                $this->db->reset_query();
            }
            //total records
            $total = count($this->lam->get_online_auction_items($data['auction_id'], 0, 0, $item_ids));

            //offset records
            $limit = 2;
            $offset = (int)$data['offset'];
            
            $auction_items = $this->lam->get_online_auction_items($id,$limit,$offset,$item_ids,$order_by);
            $next_offset = $offset + $limit;
            if(count($auction_items) > 0){
                $items = $this->load->view('auction/live_auction/auction_items', ['auction_items' => $auction_items], true);
                //$media = $this->template->load_user_ajax('media-load-more', ['list' => $list], true);
                $btn = ($total > $next_offset);
                $output = json_encode([
                    'status' => 'success',
                    'items' => $items, 
                    'total' => $total, 
                    'offset' => $next_offset,
                    'btn' => $btn]);
                return print_r($output);
            }else{
                $output = json_encode(['status' => 'failed']);
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

    public function apply_filters()
    {
        $data = $this->input->post();
        if($data){
            $auction_id = $data['auction_id'];
            $min = $data['min'];
            $max = $data['max'];
            unset($data['auction_id']);
            unset($data['min']);
            unset($data['max']);

            $item_ids = [];
            $counter = 0;
            foreach ($data as $key => $value){
                if (!empty($value)) {
                    $counter++;
                }
            }
            if ($counter > 0) {
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
                $this->db->reset_query();
                if (isset($min) || isset($max)) {
                    $this->db->select('item.id,auction_items.auction_id');
                    $this->db->from('item');
                    $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                    $this->db->where('item.price >=', $min);
                    $this->db->where('price <=', $max);
                    $this->db->where('auction_items.auction_id', $auction_id);
                    $query = $this->db->get();
                    $query = $query->result_array();
                    foreach ($query as $key => $v) {
                        array_push($item_ids, $v['id']);
                    }
                    $this->db->reset_query();
                }

                $limit = 0;
                $offset = 0;
                $next_offset = $offset + $limit;
                $total = 0;
                $auction_items =array();
                if (!empty($item_ids)) {
                    $auction_items = $this->lam->get_online_auction_items($auction_id,$limit,$offset,$item_ids);
                }
            }else{
                if (isset($min) || isset($max)) {
                    $this->db->select('item.id,auction_items.auction_id');
                    $this->db->from('item');
                    $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
                    $this->db->where('item.price >=', $min);
                    $this->db->where('price <=', $max);
                    $this->db->where('auction_items.auction_id', $auction_id);
                    $query = $this->db->get();
                    $query = $query->result_array();
                    foreach ($query as $key => $v) {
                        array_push($item_ids, $v['id']);
                    }
                    $this->db->reset_query();
                }

                $limit = 0;
                $offset = 0;
                $next_offset = $offset + $limit;
                $total = 0;
                $auction_items =array();
                $auction_items = $this->lam->get_online_auction_items($auction_id,$limit,$offset,$item_ids);

            }
            

            //print_r($auction_items);

            if(count($auction_items) > 0){
                $items = $this->load->view('auction/live_auction/auction_items', ['auction_items' => $auction_items], true);
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
                $output = json_encode(['status' => 'failed']);
                return print_r($output);
            }

        }
    }

    public function search_online_items()
    {
        $data = $this->input->post();
        if($data){
            $order_by = '';
            $auction_id = $data['auction_id'];
            $search = $data['search'];
            if (!empty($data['sort'])) {
                $order_by = $data['sort'];
            }
            // unset($data['auction_id']);

            $item_ids = [];
            if (!empty($search)) {
                $this->db->select('item.id');
                $this->db->from('item');
                $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                $this->db->where(" item.name like '%".$search."%' or item.price like '%".$search."%' or item.detail like '%".$search."%' or item_makes.title like '%".$search."%' or item_models.title like '%".$search."%' ");
                // $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids, $v['id']);
                }
                $this->db->reset_query();
                if (empty($item_ids)) {
                    $output = json_encode(['status' => 'failed']);
                    return print_r($output);
                    exit();
                }
            }

            $total = count($this->lam->get_online_auction_items($data['auction_id'], 0, 0,$item_ids));
            $limit = 2;
            $offset = 0;
            $next_offset = $offset + $limit;
            // $total = 0;
            $auction_items = $this->lam->get_online_auction_items($auction_id,$limit,$offset,$item_ids,$order_by);
            // print_r($total);die();

            //print_r($auction_items);

            if(count($auction_items) > 0){
                $items = $this->load->view('auction/live_auction/auction_items', ['auction_items' => $auction_items], true);
                // $btn = false;
                $btn = ($total > $next_offset);
                // print_r($btn);die();
                $output = json_encode([
                    'status' => 'success',
                    'items' => $items, 
                    'total' => $total, 
                    'offset' => $next_offset,
                    'btn' => $btn]);
                return print_r($output);
            }else{
                $output = json_encode(['status' => 'failed']);
                return print_r($output);
            }

        }
    }

    public function ai_load_more_restricted()
    {
        $data = $this->input->post();
        if($data){
            $order_by = '';
            $id = $data['auction_id'];
            $search = $data['search'];
            if (!empty($data['sort'])) {
                $order_by = $data['sort'];
            }

            $item_ids = [];
            if (!empty($search)) {
                $this->db->select('item.id');
                $this->db->from('item');
                $this->db->join('item_makes', 'item_makes.id = item.make', 'LEFT');
                $this->db->join('item_models', 'item_models.id = item.model', 'LEFT');
                $this->db->where(" item.name like '%".$search."%' or item.price like '%".$search."%' or item.detail like '%".$search."%' or make like '%".$search."%' or model like '%".$search."%' ");
                // $this->db->where('auction_items.auction_id', $auction_id);
                $query = $this->db->get();
                $query = $query->result_array();
                foreach ($query as $key => $v) {
                    array_push($item_ids, $v['id']);
                }
                $this->db->reset_query();
            }
            //total records
            $total = count($this->lam->get_online_auction_items($data['auction_id'], 0, 0, $item_ids));

            //offset records
            $limit = 2;
            $offset = (int)$data['offset'];
            
            $auction_items = $this->lam->get_online_auction_items($id,$limit,$offset,$item_ids,$order_by);
            $next_offset = $offset + $limit;
            if(count($auction_items) > 0){
                $items = $this->load->view('auction/live_auction/auction_items', ['auction_items' => $auction_items], true);
                //$media = $this->template->load_user_ajax('media-load-more', ['list' => $list], true);
                $btn = ($total > $next_offset);
                $output = json_encode([
                    'status' => 'success',
                    'items' => $items, 
                    'total' => $total, 
                    'offset' => $next_offset,
                    'btn' => $btn]);
                return print_r($output);
            }else{
                $output = json_encode(['status' => 'failed']);
                return print_r($output);
            }
        }
    }
    
}
