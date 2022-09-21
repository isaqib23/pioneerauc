<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Search extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Search_model', 'sm');
		$this->load->library('pagination');
	}

    public function searchItems($catId=''){
        if(!empty($catId)){
            $categoryId = $catId;
        }elseif(isset($_GET['categoryId']) && !empty($_GET['categoryId'])){
            $categoryId = $_GET['categoryId'];
        }else{
            show_404();
        }
        $searchTerm = "";
        if(isset($_GET['query']) && !empty($_GET['query'])){
            $searchTerm = $_GET['query'];
        }
        $data['searchTerm'] = $searchTerm;
        if(isset($searchTerm)) :
            $not_found_message = $this->lang->line("no_record_new");
        else:
            $not_found_message = $this->lang->line('no_record');
        endif;
        
        
        $language = $this->language;
        
        $data['categoryId'] = $categoryId;
        //print_r($data);die();
        $data['selectedCategory'] = $this->db->get_where('item_category', ['id' => $categoryId])->row_array();
        if(empty($data['selectedCategory'])){show_404();}
        
        $selectedCategoryName = json_decode($data['selectedCategory']['title']);
        $data['selectedCategoryName'] = $selectedCategoryName->$language;

     //  $_cat_data = $this->db->get_where('item_category', ['slug' => $slug])->row_array();
        $data['itemCategoryFields'] = $this->db->get_where('item_category_fields', ['category_id' => $categoryId])->result_array();
        $data['itemMakes'] = $this->db->get_where('item_makes', ['status' => 'active'])->result_array();

        $data['hasClosedAuctions'] = 0;
        if ($this->session->userdata('logged_in')) {
            $user = $this->session->userdata('logged_in');
            $closedAuctions = $this->db->where("FIND_IN_SET('{$user->id}', close_auction_users)")
                ->where('expiry_time >= CURDATE()')
                ->where([
                    'status' => 'active',
                    'access_type' => 'closed',
                    'category_id' => $categoryId
                ])->get('auctions');
            $data['hasClosedAuctions'] = $closedAuctions->num_rows();
        }

        $this->template->load_user('search/search', $data);
    }

	public function index($slug='')
	{
       $id =  $this->uri->segment(2);       

       if(is_numeric($id) && is_numeric($id) > 0){
            $cat_data = $this->db->get_where('item_category', ['id' => $id])->row_array();
            redirect('/search/'.$cat_data['slug'], 'refresh');
        }else{
            if(!empty($slug)){
            $categoryId = $slug;
        }elseif(isset($_GET['categoryId']) && !empty($_GET['categoryId'])){
            $categoryId = $_GET['categoryId'];
        }else{
            show_404();
        }
    }
    $_cat_data = $this->db->get_where('item_category', ['slug' => $slug])->row_array();
            
        $searchTerm = "";
        if(isset($_GET['query']) && !empty($_GET['query'])){
            $searchTerm = htmlspecialchars($_GET['query']);
        }
        $data['searchTerm'] = $searchTerm;

         if(isset($searchTerm)) :
            $not_found_message = $this->lang->line("no_record_new");
        else:
            $not_found_message = $this->lang->line('no_record');
        endif;




		$language = $this->language;
		
		$data['slug'] = $slug;
        
		$data['selectedCategory'] = $this->db->get_where('item_category', ['slug' => $slug])->row_array();
		if(empty($data['selectedCategory'])){show_404();}


        $data['categoryId'] = $data['selectedCategory']['id'];
        
        $selectedCategoryName = json_decode($data['selectedCategory']['title']);
        $data['selectedCategoryName'] = $selectedCategoryName->$language;

        //print_r($data);die();

        $data['itemCategoryFields'] = $this->db->get_where('item_category_fields', ['category_id' => $_cat_data['id']])->result_array();

        $data['itemMakes'] = $this->db->order_by('title', 'ASC')->get_where('item_makes', ['status' => 'active'])->result_array();

        $data['hasClosedAuctions'] = 0;
		if ($this->session->userdata('logged_in')) {
            $user = $this->session->userdata('logged_in');
            $closedAuctions = $this->db->where("FIND_IN_SET('{$user->id}', close_auction_users)")
                ->where('expiry_time >= CURDATE()')
                ->where([
                    'status' => 'active',
                    'access_type' => 'closed',
                    'category_id' => $categoryId
                ])->get('auctions');
            $data['hasClosedAuctions'] = $closedAuctions->num_rows();
        }

        //auction selection for displaying record
            $this->db->where([
                    'access_type' => 'live', 
                    'category_id' => $data['categoryId'],
                    'status' => 'active'
                ]);

                $this->db->where("(`expiry_time` >= CURRENT_TIMESTAMP() OR `start_status` = 'start')");
                // $this->db->or_where('`start_status`', 'start');
                $this->db->order_by('`start_time`', 'ASC');

                $this->db->limit(1);

            @$availableAuctions = $this->db->get('auctions')->result_array();


            @$comeDay =  date("Y-m-d" ,strtotime($availableAuctions[0]['start_time'] . ' +1 day'));
            @$lessDay =  date("Y-m-d" ,strtotime($availableAuctions[0]['start_time']. ' -1 day'));
            @$toDayl =  date("Y-m-d" ,time());

            @$clasLive ='';
            if($toDayl == $lessDay || $toDayl < $comeDay){
                @$clasLive = ' blink bg_blink ';
            }else{
               @$clasLive = ' ';
            }
           @$data['clasLive'] = $clasLive;
           @$data['popup_message'] = json_decode($availableAuctions[0]['popup_message'])->$language;

        $this->template->load_user('search/search', $data);
	}

	public function getAuctionStatus()
    {
       $this->db->where([
                    'access_type' => 'live', 
                    'status' => 'active',
                    'start_status' => 'start'
                ]);

               //Edit by mohsin 
//                $this->db->where("(`expiry_time` >= CURRENT_TIMESTAMP() OR `start_status` = 'start')");
//                 $this->db->where('start_status', 'start');
                $this->db->order_by('`start_time`', 'ASC');

                $this->db->limit(1);
        $availableAuctions = $this->db->get('auctions')->result_array();

        @$output = ['status' => TRUE, 'data' => $availableAuctions[0]['start_status']];
        echo json_encode($output);
    }

    public function modelsByMake()
    {
        $data = $this->input->post();
        if($data){
            $itemModels =  $this->db->order_by('title', 'ASC')->get_where('item_models', ['make_id' => $data['makeId']])->result_array();
          //  $itemModels = sort($itemModels);
            if($itemModels){
                $output = ['status' => TRUE, 'data' => $itemModels];
            }else{
                $output = ['status' => FALSE];
            }
        }else{
            $output = ['status' => FALSE];
        }
        echo json_encode($output);
    }

	public function getAuctionItems()
    {
        $data = $this->input->post();

        if($data){
            $minMil = $data['itemMilageMin'] ?? 0;
            $maxMil = $data['itemMilageMax'] ?? 0;
            $milageType = $data['milageType'] ?? '';

        	$filterSQL = [];
        	$filterQuery = '';
        	
        	// remove all empty value's keys
            $data = array_filter($data);


            //auction selection for displaying record
            $this->db->where([
                    'access_type' => $data['auctionType'], 
                    'category_id' => $data['categoryId'],
                    'status' => 'active'
                ]);

            if(in_array($data['auctionType'], ['online', 'closed'])){
                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `start_time` AND `expiry_time`');
            }

            if(in_array($data['auctionType'], ['live'])){
                $this->db->where("(`expiry_time` >= CURRENT_TIMESTAMP() OR `start_status` = 'start')");
                // $this->db->or_where('`start_status`', 'start');
                $this->db->order_by('`start_time`', 'ASC');
            }else{
                $this->db->order_by('id', 'ASC');
            }
            
            if(in_array($data['auctionType'], ['online','live'])){
                $this->db->limit(1);
            }

            $availableAuctions = $this->db->get('auctions')->result_array();





            $auctionIds = [];
            foreach ($availableAuctions as $key => $availableAuction) {
                array_push($auctionIds, $availableAuction['id']);
            }
            if (empty($auctionIds)) {
                array_push($auctionIds, 0);
            }

            if(empty($auctionIds)){
                //$output = json_encode(['status' => 'failed']);
                //return print_r($output);
            }
        	
            // echo $this->db->last_query();return;
            
            //offset and limit for records
            $limit = 15;
            $offset = (isset($data['offset']) && !empty($data['offset'])) ? (int)$data['offset'] : 0;
            //$next_offset = (int)$offset + $limit;


           $searching = 0;
            //get item ids from category fields data
            $itemIds = [];
            if(isset($data['fields']) && !empty($data['fields'])){
            	$fieldIds = $fieldValues = [];
            	$fields = array_filter($data['fields']);
            	unset($data['fields']);

            	if($fields){
		            foreach ($fields as $key => $value) {
		            	array_push($fieldIds, $key);
                        if(is_array($value)){
    		            	foreach ($value as $k => $v) {
    		            		array_push($fieldValues, $v);
    		            	}
                        }else{
                            array_push($fieldValues, $value);
                        }
		            }

	            	$fieldItemIds = $this->db->distinct('item_id')->select('item_id')
	            		->from('item_fields_data')
	            		->where('category_id', $data['categoryId'])
	            		->where_in('fields_id', $fieldIds)
	            		->where_in('value', $fieldValues)
	            		->get();

	            	if($fieldItemIds->num_rows() > 0){
	            		$fieldItemIds = $fieldItemIds->result_array();
	            		foreach ($fieldItemIds as $key => $value) {
	            			array_push($itemIds, $value['item_id']);
	            		}
	            	}
            	}
                 $searching = 1;
            }

            /*if (isset($data['itemPriceMin']) && !empty($data['itemPriceMin']) 
                && isset($data['itemPriceMax']) && !empty($data['itemPriceMax'])) 
            {
            	$min = $data['itemPriceMin'];
            	$max = $data['itemPriceMax'];
	            $filterSQL[] = " ( `i`.`price` BETWEEN '{$min}' AND '{$max}') ";
	        }*/

	        if (isset($data['itemYearFrom'])&& !empty($data['itemYearFrom']) 
                && isset($data['itemYearTo']) && !empty($data['itemYearTo'])) 
            {
            	$from = $data['itemYearFrom'];
            	$to = $data['itemYearTo'];
	            $filterSQL[] = " ( `i`.`year` BETWEEN '{$from}' AND '{$to}') ";
                $searching = 1;
	        }

            if(($minMil >= 0) && ($maxMil >0)){
             $filterSQL[] = " ( `i`.`mileage` BETWEEN '{$minMil}' AND '{$maxMil}') ";
             $searching = 1;
            }


            if(!empty($milageType))
            {
                $milageType = $data['milageType'];
                $filterSQL[] = " ( `i`.`mileage_type` = '{$milageType}') ";
             $searching = 1;
         }

	        if (isset($data['itemMake']) && !empty($data['itemMake'])) {
            	$itemMake = $data['itemMake'];
	            $filterSQL[] = " ( `i`.`make` = '{$itemMake}') ";
	         $searching = 1;
         }

	        if (isset($data['itemModel']) && !empty($data['itemModel'])) {
            	$itemModel = $data['itemModel'];
	            $filterSQL[] = " ( `i`.`model` = '{$itemModel}') ";
	         $searching = 1;
         }

	        if (isset($data['specification']) && !empty($data['specification'])) {
            	$specification = $data['specification'];
	            $filterSQL[] = " ( `i`.`specification` = '{$specification}') ";
	         $searching = 1;
         }

            if (isset($data['itemLot']) && !empty($data['itemLot'])) {
                $itemLot = $data['itemLot'];
                $filterSQL[] = " ( `ai`.`order_lot_no` = '{$itemLot}') ";
             $searching = 1;
         }

	        if (!empty($filterSQL)) {
	            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
	        }


            $select = $this->sm->getItemSelect($data['auctionType']);

            $this->db->reset_query();
        	$this->db->flush_cache();
        	$this->db->start_cache();

        	$this->db->select($select);
        	$this->db->from('auction_items ai');
		    $this->db->join('item i', 'i.id = ai.item_id');
            $this->db->join('item_makes imake', 'i.make = imake.id', 'LEFT');
            $this->db->join('item_models imodel', 'i.model = imodel.id', 'LEFT');

            if($data['auctionType'] != 'live'){
                $this->db->join('bid b', 'ai.auction_id = b.auction_id AND ai.item_id = b.item_id', 'LEFT');  
            }
		    //$this->db->where('a.access_type', $data['auctionType']);
            $this->db->where('ai.sold_status','not');
		    $this->db->where('i.sold','no');
            $this->db->where('ai.category_id', $data['categoryId']);

            if($data['auctionType'] != 'live'){
                $this->db->where('CURRENT_TIMESTAMP() BETWEEN `ai`.`bid_start_time` AND `ai`.`bid_end_time`');
            }

            if(!empty($auctionIds)){
                $this->db->where_in('ai.auction_id', $auctionIds);
            }

		    if(!empty($filterQuery)){
		    	$this->db->where($filterQuery);
		    }

		    if(!empty($itemIds)){
		    	// $this->db->or_where_in('ai.item_id', $itemIds);
                $this->db->where_in('ai.item_id', $itemIds);
		    }

		    if (isset($data['searchTerm']) && !empty($data['searchTerm'])) {
		    	$searchTerm = strtolower($data['searchTerm']);
                $language = $this->language;
                $searchTermSQL = ' LOWER(JSON_EXTRACT(item_name, "$.'.$language.'")) LIKE "%'.$searchTerm.'%" 
                	OR LOWER(JSON_EXTRACT(item_detail, "$.'.$language.'")) LIKE "%'.$searchTerm.'%" 
                	OR LOWER(JSON_EXTRACT(item_make_title, "$.'.$language.'")) LIKE "%'.$searchTerm.'%" 
                	OR LOWER(JSON_EXTRACT(item_model_title, "$.'.$language.'")) LIKE "%'.$searchTerm.'%" ';

                $this->db->having($searchTermSQL);

                $searching = 1;

            }




            if (isset($data['itemPriceMin']) && !empty($data['itemPriceMin']) 
                && isset($data['itemPriceMax']) && !empty($data['itemPriceMax'])) 
            {
                $min = $data['itemPriceMin'];
                $max = $data['itemPriceMax'];
                $this->db->having(" (final_price BETWEEN '{$min}' AND '{$max}') ");
             $searching = 1;
         }

            if($data['auctionType'] != 'live'){
                $this->db->group_by('auction_item_id');
            }
            
            $this->db->stop_cache();

         ///   echo $this->db->get_compiled_select();return;

            $totalRecords = $this->db->get()->num_rows();





            $this->db->start_cache();
            $this->db->limit($limit, $offset);


            


            //order by
            $orderBy = isset($data['sortBy']) ? $data['sortBy'] : "";
			if (! empty($orderBy)) {
                switch ($orderBy) {
                    case 'hp':
                        $this->db->order_by('final_price',"DESC");
                        break;
                    case 'lp':
                        $this->db->order_by('final_price',"ASC");
                        break;
                    case 'latest':
                        $this->db->order_by('ai.id',"DESC");
                        break;
                    case 'featured':
                        $this->db->order_by('item_feature',"ASC");
                        break;
                    
                    default:
                        $this->db->order_by('item_feature',"ASC");
                        break;
                }
			}else{
				$this->db->order_by('order_lot_no',"ASC");
			}

            $this->db->stop_cache();

		    //echo $this->db->get_compiled_select();return;

           /* print_r($data['searchTerm']);
             echo '<br>';
              print_r($searchTermSQL);
              echo '<br>';
              print_r($filterQuery);
               die;
               */
		    
		    //Execute Query
		    $query = $this->db->get();
		    $totalDisplayRecords = $query->num_rows();


        	$this->db->flush_cache();
		    $this->db->reset_query();

		    //Make pagination
            $config['base_url'] = '#';
            $config['total_rows'] = $totalRecords;
            $config['per_page'] = $limit;
            // $config['reuse_query_string'] = FALSE;
            // $config["uri_segment"] = 4;
            $config['full_tag_open'] = '<ul class="pagination d-flex">';
            $config['full_tag_close'] = '</ul">';

            $config['next_tag_open'] = '<li class="pagination_link page-item">';
            $config['next_tag_close'] = '</li>';

            $config['prev_tag_open'] = '<li class="pagination_link page-item">';
            $config['prev_tag_close'] = '</li>';

            $config['last_tag_open'] = '';
            $config['last_tag_close'] = '';

            $config['first_tag_open'] = '';
            $config['first_tag_close'] = '';

            $config['next_link'] = '<div class="page-link"><span class="material-icons">keyboard_arrow_right</span></div>';
            $config['prev_link'] = '<div class="page-link"><span class="material-icons">keyboard_arrow_left</span></div>';
            
            // $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            // $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
            
            $config['cur_tag_open'] = '<li class=" current page-item active"><div class="page-link">';
            $config['cur_tag_close'] = '</div></li>';
            $config["cur_page"] = $offset;
            
            $config['num_tag_open'] = '<li class="pagination_link page-item"><div class="page-link">';
            $config['num_tag_close'] = '</div></li>';

            $config['num_links'] = 15;



            $this->pagination->initialize($config); 
            $paginationLinks = $this->pagination->create_links();

            if($searching==1):
                $not_found_message = $this->lang->line("no_record_new");
            else:
                $not_found_message = $this->lang->line('no_record');
            endif;

            if ($this->session->userdata('logged_in')) {
                $balance = $this->user_balance($this->session->userdata('logged_in')->id);
                $data['balance'] = $balance['amount'] ?? 0;
            }else{
                $data['balance'] =  0;
            }


            if($totalRecords > 0 || !empty($availableAuctions)){
            	$items = $query->result_array();

                $resultPage = ($data['auctionType'] == 'live') ? 'search/results' : 'search/results';
                $itemsResult = $this->template->load_user_ajax($resultPage, [
                	'items' => $items,
                	'paginationLinks' => $paginationLinks,
                	'totalRecords' => $totalRecords, 
                    'totalDisplayRecords' => $totalDisplayRecords,
                    'offset' => $offset,
                    'orderBy' => $orderBy,
                    'auctionType' => $data['auctionType'],
                    'auctionDetail' => $availableAuctions,
                    'categoryId' => $data['categoryId'],
                     'not_found_message' => $not_found_message,
                     'balance' => $data['balance'] 
                ], true);

                $output = json_encode([
                    'status' => 'success',
                    'items' => $itemsResult, 
                    'totalRecords' => $totalRecords, 
                    'totalDisplayRecords' => $totalDisplayRecords,
                    'typeCss' => $data['typeCss'],
                    'not_found_message' => $not_found_message
                ]);
                return print_r($output);
            }else{
                $output = json_encode(['status' => 'failed', 'totalRecords' => $totalRecords]);
                return print_r($output);
            }

        }
    }

    public function printCatalog($auctionId='')
    {
        if(empty($auctionId)){
            show_404();
        }

        $language = $this->language;

        $auction = $this->db->get_where('auctions', ['id' => $auctionId])->row_array();

        $select = $this->sm->getItemCatalog();

        $this->db->select($select);
        $this->db->from('auction_items ai');
        $this->db->join('item i', 'i.id = ai.item_id');
        //$this->db->join('auctions a', 'a.id = ai.auction_id');
        $this->db->join('item_makes imake', 'imake.id = i.make', 'LEFT');
        $this->db->join('item_models imodel', 'imodel.id = i.model', 'LEFT');

        $this->db->where('ai.auction_id', $auctionId);

        $this->db->order_by('ai.order_lot_no', 'ASC');

        $catalog = $this->db->get()->result_array();

        //print_r($catalog);
        $this->load->view('search/catalog', [
            'catalog' => $catalog, 
            'auction' => $auction, 
            'language' => $language
        ]);
    }

    public function getYearsSelected()
    {
        $data = $this->input->post();
        if($data){

            $i =  $data['yearFrom']-1;
            while($i > 1970) {
               $i--;
            }
            $output = ['status' => TRUE, 'data' => $i];

           
        }else{
            $output = ['status' => FALSE];
        }
        echo json_encode($output);
    }

    public function user_balance($id)
     {
        $dr_balance = 0;
        $cr_balance = 0;
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'permanent');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('account', 'DR');
        $this->db->where('user_id',$id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            $dr_balance = $query->row_array();
        }
        $this->db->select_sum('amount');
        // $this->db->select('total_deposite');
        $this->db->from('auction_deposit');
        $this->db->where('deposit_type', 'permanent');
        $this->db->where('status', 'approved');
        $this->db->where('deleted', 'no');
        $this->db->where('account', 'CR');
        $this->db->where('user_id',$id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            $cr_balance = $query->row_array();
        }
        $amount['amount'] = $dr_balance['amount'] - $cr_balance['amount'];
        return $amount;
     } 


}//end controller




