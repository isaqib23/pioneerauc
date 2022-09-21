<?php

/************************************** 
Roles Description

role == 1 = Admin 
role == 2 = Sales Manager 
role == 3 = Sales Person 
role == 4 = Customers 
role == 5 = Operational Department 
role == 6 = Tasker 
role == 7 = Live Auction Controller 
role == 8 = Cashier 
role == 9 = Appraiser

**************************************/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports extends Loggedin_Controller {
 function __construct() {
  parent::__construct();
        $this->load->model('Reports_model','reports_model');
        $this->load->model('customer/Customer_model', 'customer_model');
        $this->load->model('admin/Common_model','common_model');
 }

 public function index()
    {
        echo "Welcome to Reports";
    }

    public function customers_with_docs()
    {
        $docTypes = $this->db->get('documents_type')->result_array();
        $this->template->load_admin('reports/customers_with_docs', ['docTypes' => $docTypes]);
    }
    
    public function customers_without_bank_detail()
    {
        $this->template->load_admin('reports/customers_without_bank_detail');
    }

    public function customers_with_bank_detail()
    {
        $this->template->load_admin('reports/customers_with_bank_detail');
    }

    public function customers_with_deposit()
    {
        $this->template->load_admin('reports/customers_with_deposit');
    }

    public function live_auction_wise_deposits()
    {
        $liveAuctions = $this->db->select('id, title')->where('access_type', 'live')->get('auctions')->result_array();
        $this->template->load_admin('reports/live_auction_wise_deposits', ['liveAuctions' => $liveAuctions]);
    }

    public function temporary_depost_refund()
    {
        $liveAuctions = $this->db->select('id, title')->where('access_type', 'live')->get('auctions')->result_array();
        $this->template->load_admin('reports/temporary_depost_refund', ['liveAuctions' => $liveAuctions]);
    }

    public function permanent_depost_refund()
    {
        $cashiers = $this->db->get_where('users', ['role' => 8])->result_array();
        $this->template->load_admin('reports/permanent_depost_refund', ['cashiers' => $cashiers]);
    }

    public function receive_ables()
    {
        $auctions = $this->db->select('id, title, registration_no')->order_by('start_time', 'DESC')->get('auctions')->result_array();
        $this->template->load_admin('reports/receiveables', ['auctions' => $auctions]);
    }

    public function payables()
    {
        $auctions = $this->db->select('id, title, registration_no')->order_by('start_time', 'DESC')->get('auctions')->result_array();
        $this->template->load_admin('reports/payables', ['auctions' => $auctions]);
    }

    public function country_wise_customers()
    {
        $countries = $this->db->get('country')->result_array();
        $this->template->load_admin('reports/country_wise_customers', ['countries' => $countries]);
    }

    public function preferred_language_customers()
    {
        $this->template->load_admin('reports/preferred_language_customers');
    }

    public function top_buyer()
    {
        $this->template->load_admin('reports/top_buyer');
    }

    public function top_seller()
    {
        $this->template->load_admin('reports/top_seller');
    }

    public function top_seller_inventory()
    {
        $this->template->load_admin('reports/top_seller_inventory');
    }

    public function registration_wise_customers()
    {
        $this->template->load_admin('reports/registration_wise_customers');
    }
    
    public function controller_sales_summary()
    {
        $this->template->load_admin('reports/controller_sales_summary');
    }
    
    public function seller_wise_inventory()
    {
     $sellers = $this->db->get_where('users', ['role' => 4])->result_array();
     $sellers = json_encode($sellers);
        $this->template->load_admin('reports/seller_wise_inventory', ['sellers' => $sellers]);
    }

    public function top_bidder()
    {
        $this->template->load_admin('reports/top_bidder');
    }

    public function deposit_adjustments()
    {
        $this->template->load_admin('reports/deposit_adjustments');
    }

    public function security_adjustments()
    {
        $this->template->load_admin('reports/security_adjustments');
    }

    public function winner_bidder()
    {
        $this->template->load_admin('reports/winner_bidder');
    }

    public function item_report_reserved_price()
    {
        $this->template->load_admin('reports/item_report_reserved_price');
    }

    public function bidding_summary()
    {
        $auctions = $this->db->select('id, title, registration_no')
            ->where_in('access_type', ['online', 'closed'])
            ->order_by('start_time', 'DESC')
            ->get('auctions')->result_array();
        $this->template->load_admin('reports/bidding_summary', ['auctions' => $auctions]);
    }

    public function live_auction_online_users()
    {
        $liveAuctions = $this->db->select('id, title, registration_no')
            ->where('access_type', 'live')
            ->order_by('start_time', 'DESC')
            ->get('auctions')->result_array();
        $this->template->load_admin('reports/live_auction_online_users', ['liveAuctions' => $liveAuctions]);
    }

    public function live_auction_report()
    {
        $liveAuctions = $this->db->select('id, title, registration_no')
            ->where('access_type', 'live')
            ->order_by('start_time', 'DESC')
            ->get('auctions')->result_array();
        $this->template->load_admin('reports/live_auction_report', ['liveAuctions' => $liveAuctions]);
    }

    public function live_auction_sales_report()
    {
        $liveAuctions = $this->db->select('id, title')->where('access_type', 'live')->get('auctions')->result_array();
        $this->template->load_admin('reports/live_auction_sales_report', ['liveAuctions' => $liveAuctions]);
    }

    public function live_auction_sales_report_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`ai`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        /*$headers = [
            ['col'=>'lot_no', 'header'=>'Lot'],
            ['col'=>'make', 'header'=>'Make'],
            ['col'=>'model', 'header'=>'Model'],
            ['col'=>'item_name', 'header'=>'Item'],
            ['col'=>'item_year', 'header'=>'Year'],
            ['col'=>'registration_no', 'header'=>'Reg No'],
            ['col'=>'auction_name', 'header'=>'Auction'],
            ['col'=>'reserved_price', 'header'=>'Reserved Price'],
            ['col'=>'seller_name', 'header'=>'Seller Name'],
            ['col'=>'seller_code', 'header'=>'Seller Code'],
            ['col'=>'buyer_name', 'header'=>'Buyer Name'],
            ['col'=>'buyer_mobile', 'header'=>'Buyer Mobile'],
            ['col'=>'buyer_code', 'header'=>'Buyer Code'],
            ['col'=>'card_number', 'header'=>'B/No'],
            ['col'=>'sold_price', 'header'=>'Sales Price'],
            ['col'=>'item_status', 'header'=>'Status']
        ];*/

        $select = "
            ai.order_lot_no AS lot_no,
            JSON_UNQUOTE(JSON_EXTRACT(imake.title, '$.english')) AS make, 
            JSON_UNQUOTE(JSON_EXTRACT(imodel.title, '$.english')) AS model,  
            JSON_UNQUOTE(JSON_EXTRACT(i.name, '$.english')) AS item_name, 
            i.year AS item_year,
            i.registration_no AS registration_no, 
            JSON_UNQUOTE(JSON_EXTRACT(a.title, '$.english')) AS auction_name,
            i.price AS reserved_price,
            u2.username AS seller_name,
            u2.id AS seller_code, 
            u1.username AS buyer_name,
            u1.mobile AS buyer_mobile, 
            u1.id AS buyer_code, 
            ad.card_number AS card_number, 
            
            (CASE 
                WHEN si.price IS NOT NULL 
                    THEN si.price
                WHEN si.price IS NULL 
                    THEN IF(ai.sold_status = 'not_sold' OR ai.sold_status = 'return', 0, b.bid_amount)
                ELSE 0
            END) AS sold_price, 

            (CASE ai.sold_status
               WHEN 'sold' THEN 'Sold'
               WHEN 'approval' THEN 'On Approval'
               WHEN 'not' THEN 'Available'
               WHEN 'return' THEN 'Returned'
               WHEN 'not_sold' THEN 'Not Sold'
            END) AS item_status
        ";

        $this->db->select($select)->from('auction_items ai');
        $this->db->join('item i', 'ai.item_id = i.id');
        $this->db->join('auctions a', 'ai.auction_id = a.id');
        $this->db->join('item_makes imake', 'i.make = imake.id', 'LEFT');
        $this->db->join('item_models imodel', 'i.model = imodel.id', 'LEFT');
        $this->db->join('sold_items si', 'ai.auction_id = si.auction_id AND ai.item_id = si.item_id', 'LEFT');
        $this->db->join('live_auction_bid_log b', 'ai.auction_id = b.auction_id AND ai.item_id = b.item_id', 'LEFT');
        $this->db->join('auction_deposit ad', 'ai.buyer_id = ad.user_id', 'LEFT');
        $this->db->join('users u1', 'ai.buyer_id = u1.id', 'LEFT');
        $this->db->join('users u2', 'i.seller_id = u2.id', 'LEFT');
        $this->db->where($filterQuery);
        $this->db->where(['b.bid_status' => 'win']);
        $this->db->group_by('ai.id');
        
        $records = $this->db->get()->result_array();
        
        $charges = $this->db->select("JSON_UNQUOTE(JSON_EXTRACT(title, '$.english')) AS charges_title, commission, apply_vat, type AS charges_type")
        ->from('seller_charges')
        ->where(['user_type' => 'seller', 'status' => 'active'])
        ->get()->result_array();

        $vat = $this->db->get_where('settings', ['code_key' => 'vat'])->row_array();

        $response = $this->load->view('reports/live_auction_sales_report_process', [
            'data' => $records,
            'charges' => $charges,
            'vat' => $vat
        ], true);

        echo $response;
    }

    public function online_auction_sales_report()
    {
        $liveAuctions = $this->db->select('id, title')->where_in('access_type', ['online', 'closed'])->get('auctions')->result_array();
        $this->template->load_admin('reports/online_auction_sales_report', ['liveAuctions' => $liveAuctions]);
    }

    public function online_auction_sales_report_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`ai`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        /*$headers = [
            ['col'=>'lot_no', 'header'=>'Lot'],
            ['col'=>'make', 'header'=>'Make'],
            ['col'=>'model', 'header'=>'Model'],
            ['col'=>'item_name', 'header'=>'Item'],
            ['col'=>'item_year', 'header'=>'Year'],
            ['col'=>'registration_no', 'header'=>'Reg No'],
            ['col'=>'auction_name', 'header'=>'Auction'],
            ['col'=>'reserved_price', 'header'=>'Reserved Price'],
            ['col'=>'seller_name', 'header'=>'Seller Name'],
            ['col'=>'seller_code', 'header'=>'Seller Code'],
            ['col'=>'buyer_name', 'header'=>'Buyer Name'],
            ['col'=>'buyer_mobile', 'header'=>'Buyer Mobile'],
            ['col'=>'buyer_code', 'header'=>'Buyer Code'],
            ['col'=>'card_number', 'header'=>'B/No'],
            ['col'=>'sold_price', 'header'=>'Sales Price'],
            ['col'=>'item_status', 'header'=>'Status']
        ];*/

        $select = "
            ai.order_lot_no AS lot_no,
            JSON_UNQUOTE(JSON_EXTRACT(imake.title, '$.english')) AS make, 
            JSON_UNQUOTE(JSON_EXTRACT(imodel.title, '$.english')) AS model,  
            JSON_UNQUOTE(JSON_EXTRACT(i.name, '$.english')) AS item_name, 
            i.year AS item_year,
            i.registration_no AS registration_no, 
            JSON_UNQUOTE(JSON_EXTRACT(a.title, '$.english')) AS auction_name,
            i.price AS reserved_price,
            u2.username AS seller_name,
            u2.id AS seller_code, 
            u1.username AS buyer_name,
            u1.mobile AS buyer_mobile, 
            u1.id AS buyer_code,
            
            (CASE 
                WHEN si.price IS NOT NULL 
                    THEN si.price
                WHEN si.price IS NULL 
                    THEN IF(ai.sold_status = 'not_sold' OR ai.sold_status = 'return', 0, b.bid_amount)
                ELSE 0
            END) AS sold_price, 

            (CASE ai.sold_status
               WHEN 'sold' THEN 'Sold'
               WHEN 'approval' THEN 'On Approval'
               WHEN 'not' THEN 'Available'
               WHEN 'return' THEN 'Returned'
               WHEN 'not_sold' THEN 'Not Sold'
            END) AS item_status
        ";

        $this->db->select($select)->from('auction_items ai');
        $this->db->join('item i', 'ai.item_id = i.id');
        $this->db->join('auctions a', 'ai.auction_id = a.id');
        $this->db->join('item_makes imake', 'i.make = imake.id', 'LEFT');
        $this->db->join('item_models imodel', 'i.model = imodel.id', 'LEFT');
        $this->db->join('sold_items si', 'ai.auction_id = si.auction_id AND ai.item_id = si.item_id', 'LEFT');
        $this->db->join('bid b', 'ai.auction_id = b.auction_id AND ai.item_id = b.item_id', 'LEFT');
        $this->db->join('users u1', 'ai.buyer_id = u1.id', 'LEFT');
        $this->db->join('users u2', 'i.seller_id = u2.id', 'LEFT');
        $this->db->where($filterQuery);
        $this->db->where(['b.bid_status' => 'won']);
        $this->db->group_by('ai.id');
        
        $records = $this->db->get()->result_array();
        
        $charges = $this->db->select("JSON_UNQUOTE(JSON_EXTRACT(title, '$.english')) AS charges_title, commission, apply_vat, type AS charges_type")
        ->from('seller_charges')
        ->where(['user_type' => 'seller', 'status' => 'active'])
        ->get()->result_array();

        $vat = $this->db->get_where('settings', ['code_key' => 'vat'])->row_array();

        $response = $this->load->view('reports/online_auction_sales_report_process', [
            'data' => $records,
            'charges' => $charges,
            'vat' => $vat
        ], true);

        echo $response;
    }

    public function live_auction_report_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`ai`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();

        $select = "
            ai.order_lot_no AS lot_no,
            i.name AS item_name, 
            i.registration_no AS registration_no, 
            i.created_on AS entered, 
            imake.title AS make, 
            imodel.title AS model,  
            a.title AS auction_name,
            u2.id AS seller_code, 
            u2.username AS seller_name,
            i.price AS reserved_price, 
            u1.id AS buyer_code, 
            u1.username AS buyer_name,

            (CASE 
                WHEN si.price IS NOT NULL 
                    THEN si.price
                WHEN si.price IS NULL 
                    THEN IF(ai.sold_status = 'not_sold', 0, b.bid_amount)
                ELSE 0
            END) AS sold_price, 

            (CASE ai.sold_status
               WHEN 'sold' THEN 'Sold'
               WHEN 'approval' THEN 'On Approval'
               WHEN 'not' THEN 'Available'
               WHEN 'return' THEN 'Returned'
               WHEN 'not_sold' THEN 'Not Sold'
            END) AS status
        ";

        $this->db->select($select)->from('auction_items ai');
        $this->db->join('item i', 'ai.item_id = i.id');
        $this->db->join('auctions a', 'ai.auction_id = a.id');
        $this->db->join('item_makes imake', 'i.make = imake.id', 'LEFT');
        $this->db->join('item_models imodel', 'i.model = imodel.id', 'LEFT');
        $this->db->join('sold_items si', 'ai.auction_id = si.auction_id AND ai.item_id = si.item_id', 'LEFT');
        $this->db->join('live_auction_bid_log b', 'ai.auction_id = b.auction_id AND ai.item_id = b.item_id', 'LEFT');
        $this->db->join('users u1', 'ai.buyer_id = u1.id', 'LEFT');
        $this->db->join('users u2', 'i.seller_id = u2.id', 'LEFT');
        $this->db->where($filterQuery);
        $this->db->where(['b.bid_status' => 'win']);
        $this->db->group_by('ai.item_id,ai.auction_id');

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (lot_no LIKE {$searchValue}) 
                OR (item_name LIKE {$searchValue}) 
                OR (registration_no LIKE {$searchValue}) 
                OR (entered LIKE {$searchValue})
                OR (make LIKE {$searchValue})
                OR (model LIKE {$searchValue})
                OR (auction_name LIKE {$searchValue})
                OR (seller_code LIKE {$searchValue})
                OR (seller_name LIKE {$searchValue})
                OR (reserved_price LIKE {$searchValue})
                OR (sold_price LIKE {$searchValue})
                OR (buyer_code LIKE {$searchValue})
                OR (buyer_name LIKE {$searchValue})
                OR (status LIKE {$searchValue})
            ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $item_name = json_decode($record['item_name']);
            $auction_name = json_decode($record['auction_name']);

            if(!empty($record['make'])){
                $make = json_decode($record['make'])->english;
            }else{
                $make = NULL;
            }

            if(!empty($record['make'])){
                $model = json_decode($record['model'])->english;
            }else{
                $model = NULL;
            }


            $data[] = [
                "lot_no" => $record['lot_no'],
                "item_name" => $item_name->english,
                "registration_no" => $record['registration_no'], 
                "entered" => date('Y-m-d', strtotime($record['entered'])),
                "make" => $make,
                "model" => $model,
                "auction_name" => $auction_name->english,
                "seller_code" => $record['seller_code'],
                "seller_name" => $record['seller_name'],
                "reserved_price" => $record['reserved_price'],
                "sold_price" => $record['sold_price'],
                "buyer_code" => $record['buyer_code'],
                "buyer_name" => $record['buyer_name'],
                "status" => $record['status']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function deposit_adjustments_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['rangeDate']) && !empty($postedData['rangeDate'])) {
            $range = explode(" - ", $postedData['rangeDate']);
            $startDate = date('Y-m-d', strtotime($range[0]));
            $endDate = date('Y-m-d', strtotime($range[1]));
            $filterSQL[] = " ( DATE(`auction_deposit`.`created_on`) BETWEEN '{$startDate}' AND '{$endDate}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "
            users.id as id, 
            users.username as customer, 
            users.email as email, 
            users.mobile as mobile,
            auction_deposit.amount as adjustment_amount,
            auction_deposit.created_on as adjustment_date
        ";

        $where = [
            'auction_deposit.deleted' => 'no',
            'auction_deposit.payment_type' => 'adjustment',
            'auction_deposit.deposit_type' => 'permanent',
            'auction_deposit.status' => 'approved',
            'auction_deposit.account' => 'CR'
        ];

        $this->db->select($select)->from('auction_deposit');
        $this->db->join('users', 'auction_deposit.user_id = users.id', 'INNER');
        $this->db->where($where);
        $this->db->where($filterQuery);
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id like {$searchValue}) 
                OR (customer like {$searchValue}) 
                OR (email like {$searchValue}) 
                OR (mobile like {$searchValue})
                OR (adjustment_amount like {$searchValue})
            ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'],
                "customer" => $record['customer'],
                "email" => $record['email'], 
                "mobile" => $record['mobile'], 
                "adjustment_amount" => $record['adjustment_amount'],
                "adjustment_date" => date('Y-m-d', strtotime($record['adjustment_date']))
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function security_adjustments_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['rangeDate']) && !empty($postedData['rangeDate'])) {
            $range = explode(" - ", $postedData['rangeDate']);
            $startDate = date('Y-m-d', strtotime($range[0]));
            $endDate = date('Y-m-d', strtotime($range[1]));
            $filterSQL[] = " ( DATE(`auction_item_deposits`.`created_on`) BETWEEN '{$startDate}' AND '{$endDate}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "
            users.id as id, 
            users.username as customer, 
            users.email as email, 
            users.mobile as mobile,
            auction_item_deposits.deposit as adjustment_amount,
            auction_item_deposits.created_on as adjustment_date
        ";

        $where = [
            'auction_item_deposits.deleted' => 'no',
            'auction_item_deposits.status' => 'adjusted'
        ];

        $this->db->select($select)->from('auction_item_deposits');
        $this->db->join('users', 'auction_item_deposits.user_id = users.id', 'INNER');
        $this->db->where($where);
        $this->db->where($filterQuery);
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id like {$searchValue}) 
                OR (customer like {$searchValue}) 
                OR (email like {$searchValue}) 
                OR (mobile like {$searchValue})
                OR (adjustment_amount like {$searchValue})
            ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'],
                "customer" => $record['customer'],
                "email" => $record['email'], 
                "mobile" => $record['mobile'], 
                "adjustment_amount" => $record['adjustment_amount'],
                "adjustment_date" => date('Y-m-d', strtotime($record['adjustment_date']))
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function top_bidder_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        /*if (isset($postedData['rangeDate']) && !empty($postedData['rangeDate'])) {
            $range = explode(" - ", $postedData['rangeDate']);
            $startDate = date('Y-m-d', strtotime($range[0]));
            $endDate = date('Y-m-d', strtotime($range[1]));
            $filterSQL[] = " ( DATE(`bid`.`bid_time`) BETWEEN '{$startDate}' AND '{$endDate}') ";
        }*/

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`bid`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "
            users.id as id, 
            users.username as bidder, 
            users.email as email, 
            users.mobile as mobile, 
            auctions.title as auction_name,
            auctions.access_type as auction_type,
            MAX(bid.bid_amount) AS bidAmount,
            bid.bid_time as bid_date
        ";

        $this->db->select($select)->from('bid');
        $this->db->join('users', 'bid.user_id = users.id', 'INNER');
        $this->db->join('auctions', 'bid.auction_id = auctions.id', 'INNER');
        $this->db->where($filterQuery);
        $this->db->group_by('bid.user_id');
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id like {$searchValue}) 
                OR (bidder like {$searchValue}) 
                OR (email like {$searchValue}) 
                OR (mobile like {$searchValue}) 
                OR (bidAmount like {$searchValue}) 
                OR (bid_date like {$searchValue}) 
                OR (auction_type like {$searchValue}) 
                OR (auction_name like {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $auction_name = json_decode($record['auction_name']);
            $data[] = [
                "id" => $record['id'],
                "bidder" => $record['bidder'],
                "email" => $record['email'], 
                "mobile" => $record['mobile'], 
                "auction_name" => $auction_name->english,
                "auction_type" => $record['auction_type'],
                "bidAmount" => $record['bidAmount'],
                "bid_date" => date('Y-m-d H:i:s', strtotime($record['bid_date']))
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function winner_bidder_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        /*if (isset($postedData['rangeDate']) && !empty($postedData['rangeDate'])) {
            $range = explode(" - ", $postedData['rangeDate']);
            $startDate = date('Y-m-d', strtotime($range[0]));
            $endDate = date('Y-m-d', strtotime($range[1]));
            $filterSQL[] = " ( DATE(`bid`.`bid_time`) BETWEEN '{$startDate}' AND '{$endDate}') ";
        }*/

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`bid`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "
            users.id as id, 
            users.username as bidder, 
            users.email as email, 
            users.mobile as mobile, 
            auctions.title as auction_name,
            auctions.access_type as auction_type,
            bid.bid_amount AS bidAmount,
            bid.bid_time as bid_date
        ";

        $this->db->select($select)->from('bid');
        $this->db->join('users', 'bid.user_id = users.id', 'INNER');
        $this->db->join('auctions', 'bid.auction_id = auctions.id', 'INNER');
        $this->db->where($filterQuery);
        $this->db->where('bid.bid_status', 'won');
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id like {$searchValue}) 
                OR (bidder like {$searchValue}) 
                OR (email like {$searchValue}) 
                OR (mobile like {$searchValue}) 
                OR (bidAmount like {$searchValue}) 
                OR (bid_date like {$searchValue}) 
                OR (auction_type like {$searchValue}) 
                OR (auction_name like {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $auction_name = json_decode($record['auction_name']);
            $data[] = [
                "id" => $record['id'],
                "bidder" => $record['bidder'],
                "email" => $record['email'], 
                "mobile" => $record['mobile'], 
                "auction_name" => $auction_name->english,
                "auction_type" => $record['auction_type'],
                "bidAmount" => $record['bidAmount'],
                "bid_date" => date('Y-m-d', strtotime($record['bid_date']))
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function commision_columns_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`auction_items`.`auction_id` = '{$auctionId}') ";
        }

        if (isset($postedData['item_sold_status']) && !empty($postedData['item_sold_status'])) {
            $sold_status = $postedData['item_sold_status'];
            if($sold_status != 'all'){
                $filterSQL[] = " (`auction_items`.`sold_status` = '{$sold_status}') ";
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "users.username as seller,
            users.mobile as mobile,
            users.email as email,
            item.name as item_name,
            CASE auction_items.sold_status
               WHEN 'sold' THEN 'Sold'
               WHEN 'approval' THEN 'On Approval'
               WHEN 'not' THEN 'Available'
               WHEN 'return' THEN 'Returned'
               WHEN 'not_sold' THEN 'Expired'
            END AS status
        ";

        $this->db->select($select)->from('auction_items');
        $this->db->join('item', 'item.id = auction_items.item_id', 'INNER');
        $this->db->join('users', 'users.id = item.seller_id', 'INNER');
        $this->db->where($filterQuery);
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (seller LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (item_name LIKE {$searchValue}) 
                OR (status LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $item_name = json_decode($record['item_name']);
            $data[] = [
                "seller" => $record['seller'],
                "mobile" => $record['mobile'],
                "email" => $record['email'], 
                "item_name" => $item_name->english,
                "status" => $record['status']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function bidding_summary_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            if($auctionId != 'all'){
                $filterSQL[] = " (`bid`.`auction_id` = '{$auctionId}') ";
            }
        }

        if (isset($postedData['itemId']) && !empty($postedData['itemId'])) {
            $itemId = $postedData['itemId'];
            if($itemId != 'all'){
                $filterSQL[] = " (`bid`.`item_id` = '{$itemId}') "; 
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            $this->db->where($filterQuery);
        }
        
        $select = "
            users.id as id, 
            users.username as bidder, 
            users.email as email, 
            users.mobile as mobile, 
            auctions.title as auction_name,
            auctions.access_type as auction_type,
            item.name as item_name,
            bid.bid_amount AS bidAmount,
            bid.bid_time as bid_date
        ";

        $this->db->select($select)->from('bid');
        $this->db->join('users', 'bid.user_id = users.id');
        $this->db->join('auctions', 'bid.auction_id = auctions.id');
        $this->db->join('item', 'bid.item_id = item.id');

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id LIKE {$searchValue}) 
                OR (bidder LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (bidAmount LIKE {$searchValue}) 
                OR (bid_date LIKE {$searchValue}) 
                OR (auction_type LIKE {$searchValue}) 
                OR (auction_name LIKE {$searchValue})
                OR (item_name LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $auction_name = json_decode($record['auction_name']);
            $item_name = json_decode($record['item_name']);
            $data[] = [
                "id" => $record['id'],
                "bidder" => $record['bidder'],
                "email" => $record['email'], 
                "mobile" => $record['mobile'], 
                "auction_name" => $auction_name->english,
                "auction_type" => $record['auction_type'],
                "item_name" => $item_name->english,
                "bidAmount" => $record['bidAmount'],
                "bid_date" => date('Y-m-d H:i:s', strtotime($record['bid_date']))
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function items_against_auction()
    {
        $data = $this->input->post();
        if($data['auction_id'] != 'all'){
            $this->db->select('item.id as item_id, item.name as item_name');
            $this->db->from('item');
            $this->db->where('auction_items.auction_id',$data['auction_id']);
            $this->db->join('auction_items','item.id = auction_items.item_id');
            $this->db->order_by('item_name', 'ASC');
            $items = $this->db->get()->result_array();
      
            $options ='<option selected value="all">All Items</option>';
            if ($items) {
                foreach ($items as $key => $value) {
                    $item_id = $value['item_id'];
                    $item_name = json_decode($value['item_name']);
                    $options .= '<option value="'.$item_id.'" >'.$item_name->english.'</option>';
                }
            }else{
                $options.= '<option value="all" disabled>No item found</option>';
            }
        }else{
            $options.= '<option selected value="all">All Items</option>';
        }
        echo $options;
    }

    public function live_auction_online_users_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`live_auction_bid_log`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }
        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        $this->db->select('users.id, users.username, users.email, users.mobile')->from('users');
        $this->db->join('live_auction_bid_log', 'users.id = live_auction_bid_log.user_id');
        $this->db->where($filterQuery);
        $this->db->where('live_auction_bid_log.bid_type', 'online');
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $this->db->start_cache();
        $this->db->group_by('users.id');
        $this->db->stop_cache();

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "email" => $record['email'],
                "mobile" => $record['mobile']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function controller_sales_summary_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['rangeDate']) && !empty($postedData['rangeDate'])) {
            $range = explode(" - ", $postedData['rangeDate']);
            $startDate = date('Y-m-d', strtotime($range[0]));
            $endDate = date('Y-m-d', strtotime($range[1]));
            $filterSQL[] = " ( DATE(`si`.`updated_on`) BETWEEN '{$startDate}' AND '{$endDate}') ";
        }

        if (isset($postedData['auction_type']) && !empty($postedData['auction_type'])) {
            $auction_type = $postedData['auction_type'];
            if($auction_type != 'all'){
                $filterSQL[] = " (`a`.`access_type` = '{$auction_type}') ";  
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "
            i.name as item_name, 
            u1.username as buyer_name, 
            u2.username as seller_name, 
            a.title as auction_name, 
            a.access_type as auction_type,
            si.price as sell_price,
            si.updated_on as sell_on
        ";
        $this->db->select($select)->from('sold_items si');
        $this->db->join('auctions a', 'a.id = si.auction_id', 'LEFT');
        $this->db->join('item i', 'i.id = si.item_id', 'LEFT');
        $this->db->join('users u1', 'u1.id = si.buyer_id', 'LEFT');
        $this->db->join('users u2', 'u2.id = si.seller_id', 'LEFT');
        $this->db->where($filterQuery);
        //$qw = $this->db->get_compiled_select();
        //print_r($qw);die();
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (item_name like {$searchValue}) 
                OR (buyer_name like {$searchValue}) 
                OR (seller_name like {$searchValue}) 
                OR (auction_name like {$searchValue}) 
                OR (sell_price like {$searchValue}) 
                OR (auction_type like {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        /*$this->db->start_cache();
        $this->db->group_by('users.id');
        $this->db->stop_cache();*/

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $item_name = json_decode($record['item_name']);
            $auction_name = json_decode($record['auction_name']);
            $data[] = [
                "item_name" => $item_name->english, 
                "auction_name" => $auction_name->english,
                "auction_type" => $record['auction_type'],
                "seller_name" => $record['seller_name'],
                "buyer_name" => $record['buyer_name'],
                "sell_price" => $record['sell_price'],
                "sell_on" => date('Y-m-d', strtotime($record['sell_on']))
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }
    
    public function customers_with_docs_processing()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters
        if (isset($postedData['docsType']) && !empty($postedData['docsType'])) {
            $docsType = $postedData['docsType'];
            $docsTypeFilter = $postedData['docsTypeFilter'];
            $have = [];
            $notHave = [];

            $docsTotalTypes = $this->db->count_all('documents_type');
            $users = $this->db->get_where('users', ['role' => 4])->result_array();
            foreach ($users as $key => $user) {
                $this->db->reset_query();
                $this->db->where('`user_documents`.`file_id` IS NOT NULL');
                $this->db->where("`user_documents`.`file_id` != ''");
                $this->db->where('user_id', $user['id']);
                if($docsType != 'all'){
                    $this->db->where('document_type_id', $docsType);
                    $docsTotalTypes = 1;
                }
                $document = $this->db->get('user_documents');

                if($document->num_rows() == $docsTotalTypes){
                    array_push($have, $user['id']);
                }else{
                    array_push($notHave, $user['id']);
                }
            }
        }

        $this->db->flush_cache();
        $this->db->start_cache();
        $this->db->select('users.id, users.username, users.email, users.mobile')->from('users');
        if($docsTypeFilter == 'yes'){
            $have = ($have) ? $have : [0];
            $this->db->where_in('id', $have);
        }else{
            $notHave = ($notHave) ? $notHave : [0];
            $this->db->where_in('id', $notHave);
        }
        $this->db->stop_cache();



        /*if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            $this->db->where($filterQuery);
        }*/

        

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        /*if($searchQuery != ''){
            $this->db->having($searchQuery);
        }*/
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $this->db->group_by('users.id');
        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $docsTypeName = $docsType.' ('.$docsTypeFilter.')';
        if($docsType != 'all'){
            $docsQuery = $this->db->get_where('documents_type', ['id' => $docsType])->row_array();
            $docsTypeName = json_decode($docsQuery['name'])->english.' ('.$docsTypeFilter.')';
        }

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "email" => $record['email'],
                "mobile" => $record['mobile'],
                "docsType" => $docsTypeName
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function customers_without_bank_detail_processing()
    {
        $postedData = $this->input->post();
        $searchQuery = '';

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        $select = "users.id AS id,
            users.username AS username,
            users.mobile AS mobile,
            users.email AS email
        ";

        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();

        $this->db->select($select)->from('users');
        $this->db->join('user_bank_detail', 'users.id = user_bank_detail.user_id', 'LEFT');
        $this->db->where('users.role', 4);
        $this->db->where('user_bank_detail.id IS NULL');
        $this->db->group_by('id');
        //$this->db->where_not_in('id', 'SELECT user_id FROM user_bank_detail');

        $this->db->stop_cache();
        
        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id LIKE {$searchValue}) 
                OR (username LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue})";

            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records = $this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function customers_with_bank_detail_processing()
    {
        $postedData = $this->input->post();
        $searchQuery = '';

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        $user_bank_detail = 'SELECT user_id FROM user_bank_detail';

        //Total number of records without filtering
        $totalRecords = $this->db->select('*')
            ->where('role', 4)
            ->where('id IN (SELECT user_id FROM user_bank_detail)')
            ->get('users')->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue}) OR (user_bank_detail.bank_name like {$searchValue})";
            $this->db->having($searchQuery);
        }

        $totalRecordwithFilter = $this->db->select('*')
            ->join('user_bank_detail', 'users.id = user_bank_detail.user_id')
            ->where('role', 4)
            ->where('users.id IN (SELECT user_id FROM user_bank_detail)')
            ->get('users')->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->select('users.id, users.username, users.mobile, users.email, user_bank_detail.bank_name as bank')->from('users')
            ->join('user_bank_detail', 'users.id = user_bank_detail.user_id')
            ->where('users.role', 4)
            ->where('users.id IN (SELECT user_bank_detail.user_id FROM user_bank_detail)');
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }
        $query = $this->db->get();
        $records = $query->result_array();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "bank" => $record['bank']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function customers_with_deposit_processing()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        // return print_r($postedData);die();

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters
        /*if (isset($postedData['rangeDeposit']) && !empty($postedData['rangeDeposit'])) {
            $range = explode(" - ", $postedData['rangeDeposit']);
            $startDate = date('Y-m-d', strtotime($range[0]));
            $endDate = date('Y-m-d', strtotime($range[1]));
            $filterSQL[] = " ( DATE(`auction_deposit`.`created_on`) BETWEEN '{$startDate}' AND '{$endDate}') ";
        }*/

        /*if (isset($postedData['minRange']) && !empty($postedData['minRange'])) {
            $minRange = $postedData['minRange'];
            $filterSQL[] = " (`auction_deposit`.`amount` > '{$minRange}') ";
        }

        if (isset($postedData['maxRange']) && !empty($postedData['maxRange'])) {
            $maxRange = $postedData['maxRange'];
            $filterSQL[] = " (`auction_deposit`.`amount` < '{$maxRange}') ";
        }*/

        /*if (isset($postedData['depositType']) && !empty($postedData['depositType'])) {
            $depositType = $postedData['depositType'];
            $filterSQL[] = " (`auction_deposit`.`deposit_type` = '{$depositType}') ";
        }*/

        /*if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }*/
        
         $select = "
           `users`.`id` AS `id`, 
           `users`.`username` AS `username`, 
           `users`.`mobile` AS `user_mobile`, 
           `users`.`email` AS `user_email`,
            (
                SUM(IF(`auction_deposit`.`account` = 'DR' AND `auction_deposit`.`deposit_type` = 'permanent', `auction_deposit`.`amount`, 0)) -
                SUM(IF(`auction_deposit`.`account` = 'CR' AND `auction_deposit`.`deposit_type` = 'permanent', `auction_deposit`.`amount`, 0)) 
            ) AS `permanent`,

            (
                SUM(IF(`auction_deposit`.`account` = 'DR' AND `auction_deposit`.`deposit_type` = 'temporary', `auction_deposit`.`amount`, 0)) -
                SUM(IF(`auction_deposit`.`account` = 'CR' AND `auction_deposit`.`deposit_type` = 'temporary', `auction_deposit`.`amount`, 0)) 
            ) AS `temporary`
        ";

        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();

        $this->db->select($select)->from('auction_deposit');
        $this->db->join('users', 'auction_deposit.user_id = users.id','INNER');
        $this->db->where('auction_deposit.status', 'approved');
        $this->db->where('auction_deposit.deleted', 'no');
        //$this->db->where($filterQuery);

        if (isset($postedData['depositType']) && !empty($postedData['depositType'])) {
            $depositType = $postedData['depositType'];
            $this->db->having("{$depositType} > 0");

            if (isset($postedData['minRange']) && !empty($postedData['minRange'])) {
                $minRange = $postedData['minRange'];
                $this->db->having("{$depositType} > '{$minRange}'");
            }

            if (isset($postedData['maxRange']) && !empty($postedData['maxRange'])) {
                $maxRange = $postedData['maxRange'];
                $this->db->having("{$depositType} < '{$maxRange}'");
            }
        }else{
            if (isset($postedData['minRange']) && !empty($postedData['minRange'])) {
                $minRange = $postedData['minRange'];
                $this->db->having("`permanent` > '{$minRange}'");
            }

            if (isset($postedData['maxRange']) && !empty($postedData['maxRange'])) {
                $maxRange = $postedData['maxRange'];
                $this->db->having("`permanent` < '{$maxRange}'");
            }
        }

        $this->db->group_by('auction_deposit.user_id');
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id LIKE {$searchValue}) 
                OR (username LIKE {$searchValue})
                OR (user_mobile LIKE {$searchValue})
                OR (user_email LIKE {$searchValue})
                OR (permanent LIKE {$searchValue})
                OR (temporary LIKE {$searchValue})
                ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();
        // print_r($records);
        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'],
                "username" => $record['username'],
                "user_mobile" => $record['user_mobile'],
                "user_email" => $record['user_email'],
                "permanent" => $record['permanent'],
                "temporary" => $record['temporary']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function live_auction_wise_deposits_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`auction_deposit`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        $depositQuery = $this->db->select('user_id')->where($filterQuery)->get_compiled_select('auction_deposit');

        //Total number of records without filtering
        $totalRecords = $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')')
            ->get('users')->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue})";
            $this->db->having($searchQuery);
        }

        $totalRecordwithFilter = $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')')
            ->get('users')->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')');
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }
        $query = $this->db->get('users');
        $records = $query->result_array();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $user_current_temporary_dposit = $this->customer_model->user_current_temporary_dposit($record['id'],$auctionId);
            $user_current_temporary_dposit = $user_current_temporary_dposit['amount'];
            if($user_current_temporary_dposit <= 0){continue;}

            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "temporaryDeposit" => $user_current_temporary_dposit
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function temporary_depost_refund_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`ad`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        //Total Refund Query
        $totalRefund = $this->db->select('SUM(ad.amount) AS totalRefund')
            ->from('auction_deposit ad')
            ->where($filterQuery)
            ->where(['ad.deleted' => 'no', 'ad.account' => 'DR', 'ad.deposit_type' => 'temporary', 'ad.status' => 'refund'])
            ->get()->row_array();


        $select = "
            u1.id AS userCode,
            u1.username AS userName,
            u1.email AS userEmail,
            u1.mobile AS userMobile,
            u2.id AS cashierCode,
            u2.username AS cashierName,
            ad.amount AS tempDeposit,
            UPPER(ad.payment_type) AS paymentType,
            IF(ad.status = 'refund', 'Yes', 'No') AS tempDepositStatus
        ";

        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();

        $this->db->select($select)->from('auction_deposit ad')
            ->join('users u1', 'ad.user_id = u1.id')
            ->join('users u2', 'ad.created_by = u2.id')
            ->where($filterQuery)
            ->where(['ad.deleted' => 'no', 'ad.account' => 'DR', 'ad.deposit_type' => 'temporary']);

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (userCode LIKE {$searchValue}) 
                OR (userName LIKE {$searchValue}) 
                OR (userEmail LIKE {$searchValue}) 
                OR (userMobile LIKE {$searchValue}) 
                OR (cashierCode LIKE {$searchValue})
                OR (cashierName LIKE {$searchValue})
                OR (paymentType LIKE {$searchValue})
                OR (tempDepositStatus LIKE {$searchValue})
            ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }
        $query = $this->db->get();
        $records = $query->result_array();

        //echo $this->db->last_query(); die();

        $this->db->flush_cache();
        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "userCode" => $record['userCode'], 
                "userName" => $record['userName'],
                "userEmail" => $record['userEmail'],
                "userMobile" => $record['userMobile'],
                "cashierCode" => $record['cashierCode'],
                "cashierName" => $record['cashierName'],
                "tempDeposit" => $record['tempDeposit'],
                "paymentType" => $record['paymentType'],
                "tempDepositStatus" => $record['tempDepositStatus']
            ];
        }

        //total deposit
        $totalRefund = empty($totalRefund) ? '0' : $totalRefund['totalRefund'];
        $data[] = [
            "userCode" => "-", 
            "userName" => "-",
            "userEmail" => "-",
            "userMobile" => "-",
            "cashierCode" => "-",
            "cashierName" => "-",
            "tempDeposit" => "Total Refund: ".$totalRefund,
            "paymentType" => "-",
            "tempDepositStatus" => "-"
        ];
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function permanent_depost_refund_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            if($auctionId != 'all'){
                $filterSQL[] = " (`ad`.`auction_id` = '{$auctionId}') ";
            }
        }

        if (isset($postedData['cashierId']) && !empty($postedData['cashierId'])) {
            $cashierId = $postedData['cashierId'];
            if($cashierId != 'all'){
                $filterSQL[] = " (`u2`.`id` = '{$cashierId}') ";
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        $select = "
            u1.id AS userCode,
            u1.username AS userName,
            u1.email AS userEmail,
            u1.mobile AS userMobile,
            u2.id AS cashierCode,
            u2.username AS cashierName,
            ad.amount AS depositAmount,
            ad.deposit_type AS depositType,
            UPPER(ad.payment_type) AS paymentType
        ";

        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();

        $this->db->select($select)->from('auction_deposit ad')
            ->join('users u1', 'ad.user_id = u1.id')
            ->join('users u2', 'ad.created_by = u2.id')
            ->where(['ad.deleted' => 'no', 'ad.account' => 'DR']);
        if($filterQuery){
            $this->db->where($filterQuery);
        }

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (userCode LIKE {$searchValue}) 
                OR (userName LIKE {$searchValue}) 
                OR (userEmail LIKE {$searchValue}) 
                OR (userMobile LIKE {$searchValue}) 
                OR (cashierCode LIKE {$searchValue})
                OR (cashierName LIKE {$searchValue})
                OR (paymentType LIKE {$searchValue})
                OR (tempDepositStatus LIKE {$searchValue})
            ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }
        $query = $this->db->get();
        $records = $query->result_array();

        //echo $this->db->last_query(); die();

        $this->db->flush_cache();
        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "userCode" => $record['userCode'], 
                "userName" => $record['userName'],
                "userEmail" => $record['userEmail'],
                "userMobile" => $record['userMobile'],
                "cashierCode" => $record['cashierCode'],
                "cashierName" => $record['cashierName'],
                "depositAmount" => $record['depositAmount'],
                "depositType" => $record['depositType'],
                "paymentType" => $record['paymentType']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function receiveables_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`sold_items`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            $this->db->where($filterQuery);
        }

        $depositQuery = $this->db->select('buyer_id')->get_compiled_select('sold_items');
        //echo $depositQuery; die();
        //Total number of records without filtering
        $totalRecords = $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')')
            ->get('users')->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue})";
            $this->db->having($searchQuery);
        }

        $totalRecordwithFilter = $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')')
            ->get('users')->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')');
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }
        $query = $this->db->get('users');
        $records = $query->result_array();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            

            $this->db->select_sum('payable_amount','receiveables');
            $this->db->where(['payment_status' => 0, 'buyer_id' => $record['id']]);
            
            if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
                $auctionId = $postedData['auctionId'];
                $this->db->where('sold_items.auction_id', $auctionId);
            }
            
            $receiveables = $this->db->get('sold_items')->row_array();

            if(!empty($receiveables['receiveables']) && ($receiveables['receiveables'] > 0)){
                $data[] = [
                    "id" => $record['id'], 
                    "username" => $record['username'],
                    "mobile" => $record['mobile'],
                    "email" => $record['email'],
                    "receiveables" => $receiveables['receiveables']
                ];
            }

        }

        //print_r($receiveables); die();
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function country_wise_customers_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        $this->db->reset_query();
        $this->db->start_cache();

        $this->db->select('users.id, users.username, users.email, users.mobile, users.country')->from('users');
        $this->db->where('users.role', 4);

        //filters

        if (isset($postedData['country']) && !empty($postedData['country'])) {
            $country = $postedData['country'];
            $filterSQL[] = " (`users`.`country` LIKE '%{$country}%') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            $this->db->where($filterQuery);
        }

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue}) OR (users.country like {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records = $this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "country" => $record['country']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function preferred_language_customers_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        $this->db->reset_query();
        $this->db->start_cache();

        $this->db->select('users.id, users.username, users.email, users.mobile, users.prefered_language as lang')->from('users');
        $this->db->where('users.role', 4);

        //filters

        if (isset($postedData['prefered_language']) && !empty($postedData['prefered_language'])) {
            $prefered_language = $postedData['prefered_language'];
            $filterSQL[] = " (`users`.`prefered_language` LIKE '%{$prefered_language}%') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            $this->db->where($filterQuery);
        }

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue}) 
            ";
            // OR (users.preferred_language like {$searchValue})
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records = $this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "lang" => ucfirst($record['lang'])
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function top_buyer_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        $this->db->reset_query();
        $this->db->start_cache();

        $select = "
            users.id AS id, 
            users.username AS username, 
            users.email AS email, 
            users.mobile AS mobile,
            COUNT(sold_items.id) AS items
        ";

        $this->db->select($select)->from('sold_items')
            ->join('users', 'sold_items.buyer_id = users.id')
            ->group_by('sold_items.buyer_id');

        $this->db->order_by($columnName, $columnSortOrder);
        //filters
        


        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id like {$searchValue}) 
                OR (username like {$searchValue}) 
                OR (email like {$searchValue}) 
                OR (mobile like {$searchValue}) 
            ";
            // OR (users.preferred_language like {$searchValue})
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        //$this->db->order_by($columnName, $columnSortOrder);
        /*if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }*/

        if (isset($postedData['topers']) && !empty($postedData['topers'])) {
            $topers = $postedData['topers'];
            if($topers == 'all'){
                $this->db->limit($rowPerPage, $start);
            }else{
                $this->db->limit($topers);
            }
        }

        $records = $this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "items" => ucfirst($record['items'])
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function top_seller_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        $this->db->reset_query();
        $this->db->start_cache();

        $select = "
            users.id AS id, 
            users.username AS username, 
            users.email AS email, 
            users.mobile AS mobile,
            COUNT(sold_items.id) AS items
        ";

        $this->db->select($select)->from('sold_items')
            ->join('users', 'sold_items.seller_id = users.id')
            ->group_by('sold_items.seller_id');

        $this->db->order_by($columnName, $columnSortOrder);

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id like {$searchValue}) 
                OR (username like {$searchValue}) 
                OR (email like {$searchValue}) 
                OR (mobile like {$searchValue}) 
            ";
            // OR (users.preferred_language like {$searchValue})
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        //$this->db->order_by($columnName, $columnSortOrder);
        /*if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }*/

        $this->db->start_cache();
        if (isset($postedData['topers']) && !empty($postedData['topers'])) {
            $topers = $postedData['topers'];
            if($topers == 'all'){
                $this->db->limit($rowPerPage, $start);
            }else{
                $this->db->limit($topers);
            }
        }
        $this->db->stop_cache();

        $records = $this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "items" => ucfirst($record['items'])
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function top_seller_inventory_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        $this->db->reset_query();
        $this->db->start_cache();

        $select = "
            u.id AS id, 
            u.username AS username, 
            u.email AS email, 
            u.mobile AS mobile,
            i.name AS item_name,
            i.registration_no AS reg_no,
            i.vin_number AS vin_number,
            i.detail AS detail,
            COUNT(i.id) AS itemsCount 
        ";

        $this->db->select($select)->from('item i')
            ->join('users u', 'i.seller_id = u.id')
            ->group_by('i.seller_id');

        $this->db->order_by($columnName, $columnSortOrder);

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id like {$searchValue}) 
                OR (username like {$searchValue}) 
                OR (email like {$searchValue}) 
                OR (mobile like {$searchValue}) 
                OR (item_name like {$searchValue}) 
                OR (reg_no like {$searchValue}) 
                OR (vin_number like {$searchValue}) 
                OR (detail like {$searchValue}) 
            ";
            // OR (users.preferred_language like {$searchValue})
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        //$this->db->order_by($columnName, $columnSortOrder);
        /*if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }*/

        $this->db->start_cache();
        if (isset($postedData['topers']) && !empty($postedData['topers'])) {
            $topers = $postedData['topers'];
            if($topers == 'all'){
                $this->db->limit($rowPerPage, $start);
            }else{
                $this->db->limit($topers);
            }
        }
        $this->db->stop_cache();

        $records = $this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "item_name" => json_decode($record['item_name'])->english,
                "reg_no" => $record['reg_no'],
                "vin_number" => $record['vin_number'],
                "detail" => json_decode($record['detail'])->english
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function registration_wise_customers_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        $this->db->reset_query();
        $this->db->start_cache();

        $this->db->select('users.id, users.username, users.email, users.mobile, users.reg_type')->from('users');
        $this->db->where('users.role', 4);

        //filters

        if (isset($postedData['reg_type']) && !empty($postedData['reg_type'])) {    
            $reg_type = $postedData['reg_type'];
            $filterSQL[] = " (`users`.`reg_type` LIKE '%{$reg_type}%') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            $this->db->where($filterQuery);
        }

        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue}) OR (users.reg_type like {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records = $this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "reg_type" => ucfirst($record['reg_type'])
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function payables_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`sold_items`.`auction_id` = '{$auctionId}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            $this->db->where($filterQuery);
        }

        $depositQuery = $this->db->select('seller_id')->get_compiled_select('sold_items');
        //echo $depositQuery; die();
        //Total number of records without filtering
        $totalRecords = $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')')
            ->get('users')->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue})";
            $this->db->having($searchQuery);
        }

        $totalRecordwithFilter = $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')')
            ->get('users')->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->select('*')
            ->where('role', 4)
            ->where('id IN ('.$depositQuery.')');
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }
        $query = $this->db->get('users');
        $records = $query->result_array();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            
            if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
                $auctionId = $postedData['auctionId'];
                $this->db->where('sold_items.auction_id', $auctionId);
            }

            $payables = $this->db->select_sum('payable_amount','payables')
                ->where(['seller_payment_status' => '0', 'seller_id' => $record['id']])
                ->get('sold_items')->row_array();


            if(!empty($payables['payables']) && ($payables['payables'] > 0)){
                $data[] = [
                    "id" => $record['id'], 
                    "username" => $record['username'],
                    "mobile" => $record['mobile'],
                    "email" => $record['email'],
                    "payables" => $payables['payables']
                ];
            }

        }

        //print_r($receiveables); die();
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }



    ///// customer with security deposit
    public function customer_with_security_deposit(){
        $allAuctions = $this->db->select('id, title, registration_no')
            ->where_in('access_type', ['online','closed'])
            ->order_by('start_time', 'DESC')
            ->get('auctions')->result_array();
        $this->template->load_admin('reports/customer_with_security_deposit',['allAuctions' => $allAuctions]);
    }

    public function items_deposit_against_auction(){
        $auction_id = $this->input->post('auction_id');

        /// get items against auction id
        $this->db->select('item.id as item_id, item.name as item_name');
        $this->db->from('item');
        $this->db->where('auction_items.auction_id',$auction_id);
        $this->db->where('auction_items.security','yes');
        $this->db->join('auction_items','item.id = auction_items.item_id');
        $itemsDetail_against_auctionId = $this->db->get()->result_array();
  
        $options ='';
        if ($itemsDetail_against_auctionId) {
            foreach ($itemsDetail_against_auctionId as $key => $value) {
                $item_id = $value['item_id'];
                // $auction_id = $value['auction_id'];
                $item_name = json_decode($value['item_name']);
                $options .= '<option value="'.$item_id.'" >'.$item_name->english.'</option>';
            }
        }else{
            $options.= '<option selected value="" disabled>No Item Found</option>';
        }
        echo $options;
    }


    public function item_securityDeposits_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters
        $this->db->reset_query();
        $this->db->flush_cache();
        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`auction_item_deposits`.`auction_id` = '{$auctionId}') ";
        }
        // print_r($filterSQL);die()

        if (isset($postedData['item_id']) && !empty($postedData['item_id'])) {
            $item_id = $postedData['item_id'];
            $filterSQL[] = " (`auction_item_deposits`.`item_id` = '{$item_id}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            $this->db->start_cache();
            $this->db->where($filterQuery);
            $this->db->stop_cache();
        }

        $this->db->start_cache()
            ->select('users.id, users.username, users.email, users.mobile,auction_item_deposits.deposit as security_deposit')
            ->from('auction_item_deposits')
            ->join('users','users.id = auction_item_deposits.user_id')
            ->where('users.role','4')
            ->where_in('auction_item_deposits.status',['active','approved','adjusted']);
            $this->db->stop_cache();
        // echo $depositQuery; die();
        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (users.id like {$searchValue}) OR (users.username like {$searchValue}) OR (users.email like {$searchValue}) OR (users.mobile like {$searchValue})";
            $this->db->having($searchQuery);
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        if($searchQuery != ''){
            $this->db->having($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }
        $query = $this->db->get();
        $this->db->flush_cache();

        $records = $query->result_array();
        // return true;
        // echo $this->db->last_query(); die();
        $data = [];
        foreach ($records as $key => $record) {

            $data[] = [
                "id" => $record['id'], 
                "username" => $record['username'],
                "mobile" => $record['mobile'],
                "email" => $record['email'],
                "security_deposit" => $record['security_deposit']
            ];
        }
        //print_r($receiveables); die();
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];
        echo json_encode($response);
    }

    public function item_report_auction_wise_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`auction_items`.`auction_id` = '{$auctionId}') ";
        }

        if (isset($postedData['item_sold_status']) && !empty($postedData['item_sold_status'])) {
            $sold_status = $postedData['item_sold_status'];
            if($sold_status != 'all'){
             $filterSQL[] = " (`auction_items`.`sold_status` = '{$sold_status}') ";
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "users.username as seller,
         users.mobile as mobile,
         users.email as email,
         item.name as item_name,
            CASE auction_items.sold_status
         WHEN 'sold' THEN 'Sold'
         WHEN 'approval' THEN 'On Approval'
         WHEN 'not' THEN 'Available'
         WHEN 'return' THEN 'Returned'
         WHEN 'not_sold' THEN 'Expired'
      END AS status
        ";

        $this->db->select($select)->from('auction_items');
        $this->db->join('item', 'item.id = auction_items.item_id', 'INNER');
        $this->db->join('users', 'users.id = item.seller_id', 'INNER');
        $this->db->where($filterQuery);
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (seller LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (item_name LIKE {$searchValue}) 
                OR (status LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $item_name = json_decode($record['item_name']);
            $data[] = [
                "seller" => $record['seller'],
                "mobile" => $record['mobile'],
                "email" => $record['email'], 
                "item_name" => $item_name->english,
                "status" => $record['status']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function live_auction_sales_summary_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`auction_items`.`auction_id` = '{$auctionId}') ";
        }

        /*if (isset($postedData['item_sold_status']) && !empty($postedData['item_sold_status'])) {
            $sold_status = $postedData['item_sold_status'];
            if($sold_status != 'all'){
                $filterSQL[] = " (`auction_items`.`sold_status` = '{$sold_status}') ";
            }
        }*/

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "JSON_UNQUOTE(JSON_EXTRACT(auctions.title, '$.english')) AS auction,
            CASE auction_items.sold_status
               WHEN 'sold' THEN 'Sold'
               WHEN 'approval' THEN 'On Approval'
               WHEN 'not' THEN 'Available'
               WHEN 'return' THEN 'Returned'
               WHEN 'not_sold' THEN 'Not Sold'
            END AS item_status, 
            COUNT(*) AS items
        ";

        $this->db->select($select)->from('auction_items');
        $this->db->join('auctions', 'auction_items.auction_id = auctions.id');
        $this->db->where($filterQuery);
        $this->db->group_by('auction_items.sold_status');
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (auction LIKE {$searchValue}) 
                OR (item_status LIKE {$searchValue}) 
                OR (items LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "auction" => $record['auction'],
                "item_status" => $record['item_status'],
                "items" => $record['items']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function seller_wise_inventory_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['sellerId']) && !empty($postedData['sellerId'])) {
            $sellerId = $postedData['sellerId'];
            $filterSQL[] = " (`item`.`seller_id` = '{$sellerId}') ";
        }

        if (isset($postedData['item_sold_status']) && !empty($postedData['item_sold_status'])) {
            $sold_status = $postedData['item_sold_status'];
            if($sold_status != 'all'){
             $filterSQL[] = " (`auction_items`.`sold_status` = '{$sold_status}') ";
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "users.username as seller,
         users.mobile as mobile,
         users.email as email,
         item.name as item_name,
            sold_items.created_on as item_sold_date,
            CASE auction_items.sold_status
         WHEN 'sold' THEN 'Sold'
         WHEN 'approval' THEN 'On Approval'
         WHEN 'not' THEN 'Available'
         WHEN 'return' THEN 'Returned'
         WHEN 'not_sold' THEN 'Expired'
      END AS status
        ";

        $this->db->select($select)->from('auction_items');
        $this->db->join('item', 'item.id = auction_items.item_id', 'INNER');
        $this->db->join('users', 'users.id = item.seller_id', 'INNER');
        $this->db->join('sold_items', 'auction_items.item_id = sold_items.item_id', 'LEFT');
        if($filterQuery){
            $this->db->where($filterQuery);    
        }
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (seller LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (item_name LIKE {$searchValue}) 
                OR (item_sold_date LIKE {$searchValue}) 
                OR (status LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $item_name = json_decode($record['item_name']);
            $data[] = [
                "seller" => $record['seller'],
                "mobile" => $record['mobile'],
                "email" => $record['email'], 
                "item_name" => $item_name->english,
                "status" => $record['status'],
                "item_sold_date" => $record['item_sold_date']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function item_report_reserved_price_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['auctionId']) && !empty($postedData['auctionId'])) {
            $auctionId = $postedData['auctionId'];
            $filterSQL[] = " (`auction_items`.`auction_id` = '{$auctionId}') ";
        }

        if (isset($postedData['reservedPrice']) && !empty($postedData['reservedPrice'])) {
            $reservedPrice = $postedData['reservedPrice'];
            if($reservedPrice == 'higher'){
             $filterSQL[] = " (sold_items.price > item.price ) ";
            }
            if($reservedPrice == 'equal'){
             $filterSQL[] = " (sold_items.price = item.price) ";
            }
            if($reservedPrice == 'all'){
             $filterSQL[] = " (sold_items.price >= item.price) ";
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "users.username as seller,
         users.mobile as mobile,
         users.email as email,
         item.name as item_name,
         item.price as reserved_price,
         sold_items.price as sold_price,
            CASE auction_items.sold_status
         WHEN 'sold' THEN 'Sold'
         WHEN 'approval' THEN 'On Approval'
         WHEN 'not' THEN 'Available'
         WHEN 'return' THEN 'Returned'
         WHEN 'not_sold' THEN 'Expired'
      END AS status
        ";

        $this->db->select($select)->from('auction_items');
        $this->db->join('item', 'item.id = auction_items.item_id', 'INNER');
        $this->db->join('sold_items', 'sold_items.item_id = item.id', 'INNER');
        $this->db->join('users', 'users.id = item.seller_id', 'INNER');
        $this->db->where($filterQuery);
        $this->db->where_in('auction_items.sold_status', ['sold', 'approval']);
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (seller LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (item_name LIKE {$searchValue}) 
                OR (reserved_price LIKE {$searchValue}) 
                OR (sold_price LIKE {$searchValue}) 
                OR (status LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $item_name = json_decode($record['item_name']);
            $data[] = [
                "seller" => $record['seller'],
                "mobile" => $record['mobile'],
                "email" => $record['email'], 
                "item_name" => $item_name->english,
                "reserved_price" => $record['reserved_price'],
                "sold_price" => $record['sold_price'],
                "status" => $record['status']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function auctions_between_dates()
    {
     $postedData = $this->input->post();
        $startDate = date('Y-m-d', strtotime($postedData['startDate']));
        $endDate = date('Y-m-d', strtotime($postedData['endDate']));
        
        $auctions = $this->db->select('id,title,registration_no')
         ->where("( DATE(`auctions`.`start_time`) BETWEEN '{$startDate}' AND '{$endDate}')")
         ->where_in('access_type', ['online', 'closed'])
         ->order_by('start_time', 'DESC')
         ->get('auctions')->result_array();

        $options ='';
        if ($auctions) {
            foreach ($auctions as $key => $auction) {
                $auction_name = json_decode($auction['title']);
                $options .= '<option value="'.$auction['id'].'" >'.$auction_name->english.' - ('.$auction['registration_no'].')</option>';
            }
        }else{
            $options.= '<option selected value="" disabled>No Auction Found</option>';
        }
        echo $options;
    }

    public function live_auctions_between_dates()
    {
        $postedData = $this->input->post();
        $startDate = date('Y-m-d', strtotime($postedData['startDate']));
        $endDate = date('Y-m-d', strtotime($postedData['endDate']));
        
        $auctions = $this->db->select('id,title,registration_no')
            ->where("( DATE(`auctions`.`start_time`) BETWEEN '{$startDate}' AND '{$endDate}')")
            ->where('access_type', 'live')
            ->order_by('start_time', 'DESC')
            ->get('auctions')->result_array();

        $options ='';
        if ($auctions) {
            foreach ($auctions as $key => $auction) {
                $auction_name = json_decode($auction['title']);
                $options .= '<option value="'.$auction['id'].'" >'.$auction_name->english.' - ('.$auction['registration_no'].')</option>';
            }
        }else{
            $options.= '<option selected value="" disabled>No Auction Found</option>';
        }
        echo $options;
    }

    public function item_report_auction_wise()
    {
        $this->template->load_admin('reports/item_report_auction_wise');
    }

    public function live_auction_sales_summary()
    {
        $this->template->load_admin('reports/live_auction_sales_summary');
    }

    public function inventory_report_model_wise()
    {
        $item_makes = $this->db->select('*')->order_by('title')->get('item_makes')->result_array();
        $this->template->load_admin('reports/inventory_report_model_wise',['item_makes' => $item_makes]);
    }

    public function inventory_report_model_wise_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        // return print_r($postedData);die();

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();

        if (isset($postedData['make_id']) && !empty($postedData['make_id'])) {
            $make_id = $postedData['make_id'];
            if($make_id != "all"){
                $filterSQL[] = " (`item`.`make` = '{$make_id}') ";
            }
        }

        if (isset($postedData['model_id']) && !empty($postedData['model_id'])) {
            $model_id = $postedData['model_id'];
            if($model_id != "all"){
                $filterSQL[] = " (`item`.`model` = '{$model_id}') ";
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            // $this->db->where($filterQuery);
        }
        
         $select = "
           users.id as id,
           users.username as username,
           users.mobile as user_mobile,
           users.email as user_email,
           item.name as item_name,
           item.registration_no as registration_no,
           item_models.title as item_model_title,
           item_makes.title as item_makes_title
        ";

        $this->db->select($select)->from('item');
        $this->db->join('item_models', 'item.model = item_models.id','INNER');
        $this->db->join('item_makes', 'item.make = item_makes.id','INNER');
        $this->db->join('users', 'item.seller_id = users.id','INNER');
        if($filterQuery){
            $this->db->where($filterQuery);
        }
        $this->db->where('item.sold','no');
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id LIKE {$searchValue}) 
                OR (item_name LIKE {$searchValue})
                OR (username LIKE {$searchValue})
                OR (user_mobile LIKE {$searchValue})
                OR (item_name LIKE {$searchValue})
                OR (user_email LIKE {$searchValue})
                OR (item_makes_title LIKE {$searchValue})
                OR (item_model_title LIKE {$searchValue})
                OR (registration_no LIKE {$searchValue})
                ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();
        // print_r($records);
        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            // $auction_name = json_decode($record['auction_name']);
            $item_name = json_decode($record['item_name']);
            $title = json_decode($record['item_model_title']);
            $make_title = json_decode($record['item_makes_title']);
            $data[] = [
                "id" => $record['id'],
                "item_name" => $item_name->english,
                "username" => $record['username'],
                "user_mobile" => $record['user_mobile'],
                "user_email" => $record['user_email'],
                "item_model_title" =>  $title->english,
                "item_makes_title" =>  $make_title->english,
                "registration_no" => $record['registration_no']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function ajax_model_list()
    {
        $data = $this->input->post();
        if($data['make_id'] != 'all'){
            $this->db->select('item_models.title, item_models.id as item_model_id');
            $this->db->from('item_models');
            $this->db->where('item_models.make_id',$data['make_id']);
            $this->db->order_by('item_models.title', 'ASC');
            $items = $this->db->get()->result_array();
            // print_r($items);die();
            $options = '<option selected value="all">All Models</option>';
            if ($items) {
                foreach ($items as $key => $value) {
                    $model_id = $value['item_model_id'];
                    $model_name = json_decode($value['title']);
                    $options .= '<option value="'.$model_id.'" >'.$model_name->english.'</option>';
                }
            }else{
                $options.= '<option value="" disabled>No model found</option>';
            }
        }else{
            $options.= '<option selected value="all">All Models</option>';
        }
        echo $options;
    }

    public function item_sold_on_which_auction()
    {
        $this->template->load_admin('reports/item_sold_on_which_auction',[]);
    }

    public function item_sold_on_which_auction_wise_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        // return print_r($postedData);die();

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();

        if (isset($postedData['make_id']) && !empty($postedData['make_id'])) {
            $make_id = $postedData['make_id'];
            if($make_id != "all"){
                $filterSQL[] = " (`item`.`make` = '{$make_id}') ";
            }
        }

        if (isset($postedData['model_id']) && !empty($postedData['model_id'])) {
            $model_id = $postedData['model_id'];
            if($model_id != "all"){
                $filterSQL[] = " (`item`.`model` = '{$model_id}') ";
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
            // $this->db->where($filterQuery);
        }
        
         $select = "
           users.id as id,
           users.username as username,
           users.mobile as user_mobile,
           users.email as user_email,
           item.name as item_name,
           item.registration_no as registration_no,
           item_models.title as item_model_title,
           item_makes.title as item_makes_title
        ";

        $this->db->select($select)->from('item');
        $this->db->join('item_models', 'item.model = item_models.id','INNER');
        $this->db->join('item_makes', 'item.make = item_makes.id','INNER');
        $this->db->join('users', 'item.seller_id = users.id','INNER');
        if($filterQuery){
            $this->db->where($filterQuery);    
        }
        $this->db->where('item.sold','no');
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (id LIKE {$searchValue}) 
                OR (item_name LIKE {$searchValue})
                OR (username LIKE {$searchValue})
                OR (user_mobile LIKE {$searchValue})
                OR (item_name LIKE {$searchValue})
                OR (user_email LIKE {$searchValue})
                OR (item_makes_title LIKE {$searchValue})
                OR (item_model_title LIKE {$searchValue})
                OR (registration_no LIKE {$searchValue})
                ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();
        // print_r($records);
        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            // $auction_name = json_decode($record['auction_name']);
            $item_name = json_decode($record['item_name']);
            $title = json_decode($record['item_model_title']);
            $make_title = json_decode($record['item_makes_title']);
            $data[] = [
                "id" => $record['id'],
                "item_name" => $item_name->english,
                "username" => $record['username'],
                "user_mobile" => $record['user_mobile'],
                "user_email" => $record['user_email'],
                "item_model_title" =>  $title->english,
                "item_makes_title" =>  $make_title->english,
                "registration_no" => $record['registration_no']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function item_movement_history()
    {
        $items = $this->db->get('item')->result_array();
        foreach ($items as $key => $value) {
            $eng_name = json_decode($value['name'])->english;
            $items[$key]['name'] = $eng_name;
        }
        $items = json_encode($items);
        $this->template->load_admin('reports/item_movement_history', ['items' => $items]);
    }

    public function item_movement_history_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['itemId']) && !empty($postedData['itemId'])) {
            $itemId = $postedData['itemId'];
            $filterSQL[] = " (`item`.`id` = '{$itemId}') ";
        } else{
            $itemId = 0;
            $filterSQL[] = " (`item`.`id` = '{$itemId}') ";
        }

        // if (isset($postedData['item_sold_status']) && !empty($postedData['item_sold_status'])) {
        //     $sold_status = $postedData['item_sold_status'];
        //     if($sold_status != 'all'){
        //         $filterSQL[] = " (`auction_items`.`sold_status` = '{$sold_status}') ";
        //     }
        // }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "item.name as item_name,
            item.created_on as item_created_date,
            sold_items.created_on as item_sold_date,
            item.registration_no as item_registration_no,
            auctions.title as auction_name,
            auction_items.id as auction_items_id,
            auction_items.updated_on as auction_items_updated_date,
            auction_items.created_on as auction_items_created_date,
            CASE auction_items.sold_status
               WHEN 'sold' THEN 'Sold'
               WHEN 'approval' THEN 'On Approval'
               WHEN 'not' THEN 'Available'
               WHEN 'return' THEN 'Returned'
               WHEN 'not_sold' THEN 'Expired'
            END AS status
        ";

        $this->db->select($select)->from('item');
        $this->db->join('auction_items', 'item.id = auction_items.item_id', 'LEFT');
        $this->db->join('auctions', 'auctions.id = auction_items.auction_id', 'LEFT');
        // $this->db->join('users', 'users.id = item.seller_id', 'INNER');
        $this->db->join('sold_items', 'auction_items.item_id = sold_items.item_id', 'LEFT');
        if($filterQuery){
            $this->db->where($filterQuery);    
        }
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (item_name LIKE {$searchValue}) 
                OR (item_created_date LIKE {$searchValue}) 
                OR (item_registration_no LIKE {$searchValue}) 
                OR (status LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        $j = 0;
        foreach ($records as $key => $record) {
            $j++;
            $item_name = json_decode($record['item_name']);
            if ($j == 1) {
                $data[] = [ 
                    "item_created_date" => $record['item_created_date'],
                    "status" => 'Created',
                    "item_name" => $item_name->english,
                    "item_registration_no" => $record['item_registration_no']
                ];
            }
            if (!empty($record['auction_items_id'])) {
                $auction_name = json_decode($record['auction_name']);
                $data[] = [ 
                    "item_created_date" => $record['auction_items_created_date'],
                    "status" => $auction_name->english,
                    "item_name" => $item_name->english,
                    "item_registration_no" => $record['item_registration_no']
                ];
            }
            if ($record['status'] == 'Sold') {
                $data[] = [ 
                    "item_created_date" => $record['item_sold_date'],
                    "status" => $record['status'],
                    "item_name" => $item_name->english,
                    "item_registration_no" => $record['item_registration_no']
                ];
            }
            if ($record['status'] == 'On Approval' || $record['status'] == 'Returned' || $record['status'] == 'Expired') {
                $data[] = [ 
                    "item_created_date" => $record['auction_items_updated_date'],
                    "status" => $record['status'],
                    "item_name" => $item_name->english,
                    "item_registration_no" => $record['item_registration_no']
                ];
            }
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function item_sold_by_auction_type()
    {
        $categories = $this->db->get('item_category')->result_array();
        $this->template->load_admin('reports/item_sold_by_auction_type', ['categories' => $categories]);
    }

    public function item_sold_by_auction_type_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['rangeDate']) && !empty($postedData['rangeDate'])) {
            $range = explode(" - ", $postedData['rangeDate']);
            $startDate = date('Y-m-d', strtotime($range[0]));
            $endDate = date('Y-m-d', strtotime($range[1]));
            $filterSQL[] = " ( DATE(`ai`.`created_on`) BETWEEN '{$startDate}' AND '{$endDate}') ";
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "
            i.name as item_name, 
            i.registration_no as registration_no,
            ic.title as categoryTitle,
            a.title AS auction_name,
            a.access_type AS auction_type,
            ai.sold_status as item_sold_status,
            ai.created_on as created_on
        ";

        $this->db->select($select)->from('auction_items ai');
        $this->db->join('auctions a', 'ai.auction_id = a.id');
        $this->db->join('item i', 'ai.item_id = i.id');
        $this->db->join('item_category ic', 'ai.category_id = ic.id');
        $this->db->where(['ai.sold_status' => 'sold']);
        $this->db->where($filterQuery);
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (item_name LIKE {$searchValue}) 
                OR (registration_no LIKE {$searchValue})
                OR (categoryTitle LIKE {$searchValue})
                OR (auction_name LIKE {$searchValue})
                OR (auction_type LIKE {$searchValue})
                OR (item_sold_status LIKE {$searchValue})
                OR (created_on LIKE {$searchValue})
            ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "item_name" => json_decode($record['item_name'])->english,
                "categoryTitle" => json_decode($record['categoryTitle'])->english,
                "registration_no" => $record['registration_no'],
                "auction_name" => json_decode($record['auction_name'])->english,
                "auction_type" => ucfirst($record['auction_type']),
                "item_sold_status" => ucfirst($record['item_sold_status']),
                "created_on" => date('Y-m-d H:i:s', strtotime($record['created_on']))
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function item_stock()
    {
        $categories = $this->db->order_by('title', 'ASC')->get('item_category')->result_array();
        // print_r($categories);die();
        // $categories = json_encode($categories);
        $this->template->load_admin('reports/item_stock', ['categories' => $categories]);
    }

    public function item_stock_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        //return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['rangeDate']) && !empty($postedData['rangeDate'])) {
            $range = explode(" - ", $postedData['rangeDate']);
            $startDate = date('Y-m-d', strtotime($range[0]));
            $endDate = date('Y-m-d', strtotime($range[1]));
            $filterSQL[] = " ( DATE(`item`.`created_on`) BETWEEN '{$startDate}' AND '{$endDate}') ";
        }

        if (isset($postedData['categoryId']) && !empty($postedData['categoryId'])) {
            $categoryId = $postedData['categoryId'];
            if($categoryId != "all"){
                $filterSQL[] = " (`item`.`model` = '{$categoryId}') ";
            }
        }

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "
            item.id as id, 
            item.name as name, 
            item.registration_no as registration_no, 
            item.vin_number as vin_number, 
            item.price as price,
            item.created_on as created_on,
            item.item_status as item_status,
            item.sold as item_sold_status,
            item_category.title as categoryTitle,
            item_makes.title as make_title,
            item_models.title as model_title
        ";

        $this->db->select($select)->from('item');
        $this->db->join('item_category', 'item.category_id = item_category.id', 'LEFT');
        $this->db->join('item_makes', 'item.make = item_makes.id', 'LEFT');
        $this->db->join('item_models', 'item.model = item_models.id', 'LEFT');
        // $this->db->where($where);
        $this->db->where($filterQuery);
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (name like {$searchValue}) 
                OR (registration_no like {$searchValue}) 
                OR (vin_number like {$searchValue}) 
                OR (price like {$searchValue})
                OR (created_on like {$searchValue})
                OR (status like {$searchValue})
                OR (categoryTitle like {$searchValue})
                OR (make_title like {$searchValue})
                OR (model_title like {$searchValue})
                OR (item_sold_status like {$searchValue})
            ";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        //echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [
                "name" => json_decode($record['name'])->english,
                "categoryTitle" => json_decode($record['categoryTitle'])->english,
                "registration_no" => $record['registration_no'],
                "make_title" => ($record['make_title']) ? json_decode($record['make_title'])->english : 'N/A',
                "model_title" => ($record['model_title']) ? json_decode($record['model_title'])->english : 'N/A',
                "vin_number" => $record['vin_number'], 
                "price" => $record['price'],
                "created_on" => $record['created_on'],
                "item_status" => $record['item_status'],
                "item_sold_status" => ucfirst($record['item_sold_status'])
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function customer_cradit_card_deposit()
    {
        $this->template->load_admin('reports/customer_cradit_card_deposit', []);
    }

    public function customer_cradit_card_deposit_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['customerId']) && !empty($postedData['customerId'])) {
            $userId = $postedData['customerId'];
            $filterSQL[] = " (`auction_deposit`.`user_id` = '{$userId}') ";
        } else{
            // $userId = 0;
            // $filterSQL[] = " (`auction_deposit`.`user_id` = '{$userId}') ";
        }
        $filterSQL[] = " (`auction_deposit`.`payment_type` = 'card') ";
        $filterSQL[] = " (`auction_deposit`.`deposit_type` = 'permanent') ";
        $filterSQL[] = " (`auction_deposit`.`status` = 'approved') ";
        $filterSQL[] = " (`auction_deposit`.`account` = 'DR') ";
        $filterSQL[] = " (`auction_deposit`.`deleted` = 'no') ";

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "auction_deposit.amount as amount,
            users.id as userId,
            users.username as username,
            users.email as email,
            users.mobile as mobile
        ";

        $this->db->select($select)->from('auction_deposit');
        $this->db->join('users', 'auction_deposit.user_id = users.id', 'LEFT');
        if($filterQuery){
            $this->db->where($filterQuery);    
        }
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (userId LIKE {$searchValue}) 
                OR (username LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (amount LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [ 
                "userId" => $record['userId'],
                "username" => $record['username'],
                "email" => $record['email'],
                "mobile" => $record['mobile'],
                "amount" => $record['amount']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function customer_bank_transfer()
    {
        $this->template->load_admin('reports/customer_bank_transfer', []);
    }

    public function customer_bank_transfer_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['customerId']) && !empty($postedData['customerId'])) {
            $userId = $postedData['customerId'];
            $filterSQL[] = " (`auction_deposit`.`user_id` = '{$userId}') ";
        } else{
            // $userId = 0;
            // $filterSQL[] = " (`auction_deposit`.`user_id` = '{$userId}') ";
        }
        $filterSQL[] = " (`auction_deposit`.`payment_type` = 'bank_transfer') ";
        $filterSQL[] = " (`auction_deposit`.`deposit_type` = 'permanent') ";
        $filterSQL[] = " (`auction_deposit`.`status` = 'approved') ";
        $filterSQL[] = " (`auction_deposit`.`account` = 'DR') ";
        $filterSQL[] = " (`auction_deposit`.`deleted` = 'no') ";

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "auction_deposit.amount as amount,
            users.id as userId,
            users.username as username,
            users.email as email,
            users.mobile as mobile
        ";

        $this->db->select($select)->from('auction_deposit');
        $this->db->join('users', 'auction_deposit.user_id = users.id', 'LEFT');
        if($filterQuery){
            $this->db->where($filterQuery);    
        }
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (userId LIKE {$searchValue}) 
                OR (username LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (amount LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [ 
                "userId" => $record['userId'],
                "username" => $record['username'],
                "email" => $record['email'],
                "mobile" => $record['mobile'],
                "amount" => $record['amount']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function customer_cash_deposit()
    {
        $this->template->load_admin('reports/customer_cash_deposit', []);
    }

    public function customer_cash_deposit_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['customerId']) && !empty($postedData['customerId'])) {
            $userId = $postedData['customerId'];
            $filterSQL[] = " (`auction_deposit`.`user_id` = '{$userId}') ";
        } else{
            // $userId = 0;
            // $filterSQL[] = " (`auction_deposit`.`user_id` = '{$userId}') ";
        }
        $filterSQL[] = " (`auction_deposit`.`payment_type` = 'cash') ";
        // $filterSQL[] = " (`auction_deposit`.`deposit_type` = 'permanent') ";
        $filterSQL[] = " (`auction_deposit`.`status` = 'approved') ";
        $filterSQL[] = " (`auction_deposit`.`account` = 'DR') ";
        $filterSQL[] = " (`auction_deposit`.`deleted` = 'no') ";

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "auction_deposit.amount as amount,
        auction_deposit.deposit_type as deposit_type,
            users.id as userId,
            users.username as username,
            users.email as email,
            users.mobile as mobile
        ";

        $this->db->select($select)->from('auction_deposit');
        $this->db->join('users', 'auction_deposit.user_id = users.id', 'LEFT');
        if($filterQuery){
            $this->db->where($filterQuery);    
        }
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (userId LIKE {$searchValue}) 
                OR (deposit_type LIKE {$searchValue}) 
                OR (username LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (amount LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [ 
                "userId" => $record['userId'],
                "username" => $record['username'],
                "email" => $record['email'],
                "mobile" => $record['mobile'],
                "amount" => $record['amount'],
                "deposit_type" => $record['deposit_type']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function customer_cheque_deposit()
    {
        $this->template->load_admin('reports/customer_cheque_deposit', []);
    }

    public function customer_cheque_deposit_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['customerId']) && !empty($postedData['customerId'])) {
            $userId = $postedData['customerId'];
            $filterSQL[] = " (`auction_deposit`.`user_id` = '{$userId}') ";
        } else{
            // $userId = 0;
            // $filterSQL[] = " (`auction_deposit`.`user_id` = '{$userId}') ";
        }
        $filterSQL[] = " (`auction_deposit`.`payment_type` = 'cheque') ";
        $filterSQL[] = " (`auction_deposit`.`deposit_type` = 'permanent') ";
        $filterSQL[] = " (`auction_deposit`.`status` = 'approved') ";
        $filterSQL[] = " (`auction_deposit`.`account` = 'DR') ";
        $filterSQL[] = " (`auction_deposit`.`deleted` = 'no') ";

        if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }

        
        $this->db->reset_query();
        $this->db->flush_cache();
        $this->db->start_cache();
        
        $select = "auction_deposit.amount as amount,
            users.id as userId,
            users.username as username,
            users.email as email,
            users.mobile as mobile
        ";

        $this->db->select($select)->from('auction_deposit');
        $this->db->join('users', 'auction_deposit.user_id = users.id', 'LEFT');
        if($filterQuery){
            $this->db->where($filterQuery);    
        }
        $this->db->stop_cache();

        //Total number of records without filtering
        $totalRecords = $this->db->get()->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (userId LIKE {$searchValue}) 
                OR (username LIKE {$searchValue}) 
                OR (email LIKE {$searchValue}) 
                OR (mobile LIKE {$searchValue}) 
                OR (amount LIKE {$searchValue})";
            $this->db->start_cache();
            $this->db->having($searchQuery);
            $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->get()->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->get()->result_array();
        $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            $data[] = [ 
                "userId" => $record['userId'],
                "username" => $record['username'],
                "email" => $record['email'],
                "mobile" => $record['mobile'],
                "amount" => $record['amount']
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }

    public function daily_transections()
    {
        $this->template->load_admin('reports/daily_transections', []);
    }

    public function daily_transections_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];
        // return print_r($postedData);

        //Default DataTable Fields
        $draw = $postedData['draw'];
        $start = (isset($postedData['start']) && !empty($postedData['start'])) ? $postedData['start'] : 0;
        $rowPerPage = (isset($postedData['length']) && !empty($postedData['length'])) ? $postedData['length'] : 10; // Rows display per page
        $columnIndex = $postedData['order'][0]['column'];
        $columnName = $postedData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postedData['order'][0]['dir'];
        $searchValue = $postedData['search']['value'];

        //filters

        if (isset($postedData['invoiceDate']) && !empty($postedData['invoiceDate'])) {
            $invoiceDate = $postedData['invoiceDate'];
            // $filterSQL[] = " (DATE(`trans_date`) = '{$invoiceDate}') ";
        }
            // print_r($invoiceDate);die();

        
        
        $select = "`auction_deposit`.`amount` as `amount`,`auction_deposit`.`created_on` as `trans_date`,`auction_deposit`.`account` as `account_type`,`users`.`username` as `username`,`users`.`id` as `userCode`, `users`.`email` as `email`, `auction_deposit`.`payment_type` AS `transection_mode`, 'deposit' AS `transection_type`";
        
        $select1 = "`invoices`.`payable_amount` AS `amount`, `invoices`.`updated_on` AS `trans_date`, `invoices`.`type` AS `account_type`,`users`.`username` as `username`,`users`.`id` as `userCode`, `users`.`email` as `email`, 'cash' AS `transection_mode`, 'sale' AS `transection_type`";

        $this->db->select($select)->from('auction_deposit');
        $this->db->join('users', '`auction_deposit`.`user_id = `users`.`id`', 'LEFT');
        $invoiceDate = $postedData['invoiceDate'];
        if (isset($postedData['invoiceDate']) && !empty($postedData['invoiceDate'])) {
            $this->db->where(" (DATE(`auction_deposit`.`created_on`) = '{$invoiceDate}') ");
        }

        $query1 = $this->db->get_compiled_select(); 

        $this->db->select($select1)->from('invoices');
        $this->db->join('users', '`invoices`.`user_id = `users`.`id`', 'LEFT');
        if (isset($postedData['invoiceDate']) && !empty($postedData['invoiceDate'])) {
            $this->db->where(" (DATE(`invoices`.`updated_on`) = '{$invoiceDate}') ");
        }


        $query2 = $this->db->get_compiled_select();

        //Total number of records without filtering
        $totalRecords = $this->db->query($query1." UNION ".$query2)->num_rows();

        //Total number of record with filtering
        if($searchValue != '') {
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $searchQuery = " (amount LIKE {$searchValue}) 
                OR (trans_date LIKE {$searchValue})
                OR (account_type LIKE {$searchValue})
                OR (username LIKE {$searchValue})
                OR (userCode LIKE {$searchValue})
                OR (email LIKE {$searchValue})
                OR (transection_mode LIKE {$searchValue})
                OR (transection_type LIKE {$searchValue})";
            // $this->db->start_cache();
            $this->db->having($searchQuery);
            // $this->db->stop_cache();
        }

        $totalRecordwithFilter = $this->db->query($query1." UNION ".$query2)->num_rows();

        //Fetch Filtered Records (Main Query)
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowPerPage != '-1') {
            $this->db->limit($rowPerPage, $start);
        }

        $records =$this->db->query($query1." UNION ".$query2)->result_array();
        // $this->db->flush_cache();

        // echo $this->db->last_query(); die();

        $data = [];
        foreach ($records as $key => $record) {
            if ($record['account_type'] == 'buyer' || $record['account_type'] == 'return') {
                $account_type = 'DR';
            } elseif ($record['account_type'] == 'seller'){
                $account_type = 'CR';
            } else {
                $account_type = $record['account_type'];
            }
            switch ($record['transection_mode']) {
                case 'card':
                    $transection_mode = 'Card';
                break;
                case 'cheque':
                    $transection_mode = 'Cheque';
                break;
                case 'cash':
                    $transection_mode = 'Cash';
                break;
                case 'cash':
                    $transection_mode = 'Cash';
                break;
                case 'bank_transfer':
                    $transection_mode = 'Bank Transfer';
                break;
                
                default:
                    $transection_mode = $record['transection_mode'];
                break;
            }
            $data[] = [
                "userCode" => $record['userCode'],
                "username" => $record['username'],
                "email" => $record['email'],
                "transection_mode" => $transection_mode,
                "account_type" => $account_type,
                "amount" => $record['amount'],
                "trans_date" => $record['trans_date'],
                "transection_type" => ucfirst($record['transection_type']),
            ];
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
    }


    /* ----------------------- filters --------------------*/

    public function itemSelect2()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $searchValue = "'%".$this->db->escape_like_str($_POST['search'])."%' ESCAPE '!'";
                $sql[] = " JSON_EXTRACT(name, '$.english') LIKE {$searchValue}";
                $sql[] = " registration_no LIKE {$searchValue}";
            }
        }
        $query = "";
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') ';
        }
        if (!empty($query)) {
            $this->db->where($query);
        }
        $this->db->where('seller_id', $_POST['sellerId']);
        $row = $this->db->select('id, name AS text, registration_no')->order_by('text', 'ASC')->get('item')->result_array();
        echo json_encode($row);
        // $this->common_model->filterSelect2InTable($select2_array); // get response for select2 JQuery
    }

    public function liveAuctionsSelect2()
    {
        $sql = [];
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $searchValue = "'%".$this->db->escape_like_str($_POST['search'])."%' ESCAPE '!'";
                $sql[] = " JSON_EXTRACT(title, '$.english') LIKE {$searchValue}";
                $sql[] = " registration_no LIKE {$searchValue}";
            }
        }
        $query = "";
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') ';
        }
        if (!empty($query)) {
            $this->db->where($query);
        }
        $this->db->where(['access_type' => 'live']);
        $row = $this->db->select("id, JSON_UNQUOTE(JSON_EXTRACT(title, '$.english')) AS text, registration_no, start_time, expiry_time")->order_by('start_time','DESC')->get('auctions')->result_array();
        echo json_encode($row);
    }

    public function onlineAuctionsSelect2()
    {
        $sql = [];
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $searchValue = "'%".$this->db->escape_like_str($_POST['search'])."%' ESCAPE '!'";
                $sql[] = " JSON_EXTRACT(title, '$.english') LIKE {$searchValue}";
                $sql[] = " registration_no LIKE {$searchValue}";
            }
        }
        $query = "";
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') ';
        }
        if (!empty($query)) {
            $this->db->where($query);
        }
        $this->db->where_in('access_type', ['online', 'closed']);
        $row = $this->db->select("id, JSON_UNQUOTE(JSON_EXTRACT(title, '$.english')) AS text, registration_no, start_time, expiry_time")->order_by('start_time','DESC')->get('auctions')->result_array();
        echo json_encode($row);
    }

    public function userSelect2()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $searchValue = "'%".$this->db->escape_like_str($_POST['search'])."%' ESCAPE '!'";
                $sql[] = " username LIKE {$searchValue}";
                $sql[] = " email LIKE {$searchValue}";
                $sql[] = " mobile LIKE {$searchValue}";
            }
        }
        $query = "";
        $query = "";
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') ';
        }
        if (!empty($query)) {
            $this->db->where($query);
        }
        $this->db->where('role', 4);
        $row = $this->db->select('id, username AS text, email,mobile')->get('users')->result_array();
        echo json_encode($row);
        // $this->common_model->filterSelect2InTable($select2_array); // get response for select2 JQuery
    }

    public function daily_summary()
    {
        $this->template->load_admin('daily_summary');
    }

    public function daily_summary_process()
    {
        $postedData = $this->input->post();
        $searchQuery = '';
        $filterQuery = '';
        $filterSQL = [];

        $selectDate = '';
        if (isset($postedData['selectDate']) && !empty($postedData['selectDate'])) {
            $selectDate = $postedData['selectDate'];
        }

        /*if (!empty($filterSQL)) {
            $filterQuery .= ' ' . implode(' AND ', $filterSQL);
        }*/

        //permanent deposit
        $pDeposit = $this->db->select_sum('amount')->from('auction_deposit')->where([
            'deleted' => 'no',
            'status' => 'approved',
            'account' => 'DR',
            'deposit_type' => 'permanent',
            'DATE(created_on)' => $selectDate
        ])->group_by('created_on')->get()->row_array();

        //temporary deposit
        $tDeposit = $this->db->select_sum('amount')->from('auction_deposit')->where([
            'deleted' => 'no',
            'status' => 'approved',
            'account' => 'DR',
            'deposit_type' => 'temporary',
            'DATE(created_on)' => $selectDate
        ])->group_by('created_on')->get()->row_array();

        //item security
        $iSecurity = $this->db->select_sum('deposit')->from('auction_item_deposits')->where([
            'deleted' => 'no',
            'status' => 'approved',
            'account' => 'DR',
            'DATE(created_on)' => $selectDate
        ])->group_by('created_on')->get()->row_array();

        //sold items
        $soldItems = $this->db->select_sum('payable_amount')->from('sold_items')->where([
            'payment_status' => 1,
            'DATE(created_on)' => $selectDate
        ])->group_by('created_on')->get()->row_array();

        $data = [
            'Deposit' => $pDeposit['amount'], 
            'Temporary' => $tDeposit['amount'],
            'Security' => $iSecurity['deposit'],
            'Sales' => $soldItems['payable_amount']
        ];

        $data['Total'] = array_sum($data);

        $response = $this->load->view('reports/daily_summary_process', ['data' => $data], true);

        echo $response;
    }
}//end controller