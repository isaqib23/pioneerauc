<?php defined('BASEPATH') or exit('No direct script access allowed');
class Items extends Loggedin_Controller
{
    // public $return_url;
    public $language = 'english';
    function __construct()
    {
        parent::__construct();
        $this->load->model('Items_model', 'items_model');
        $this->load->model('users/Users_model','users_model');
        $this->load->model('files/Files_model','files_model');
        $this->load->model('crm/Crm_model','crm_model');
        // $this->load->model('Online_auction_model', 'oam');
        $this->load->helper('general_helper');
        $this->load->model('admin/Common_model','common_model');
        $language = ($this->session->userdata('site_lang')) ? $this->session->userdata('site_lang') : 'english';
    }

    public function index()
    {
        $this->output->enable_profiler(TRUE);
        $data = array();
        $data['small_title'] = 'Items List';
        $data['current_page_list'] = 'current-page';
        $data['formaction_path'] = 'filter_items';
        $data['items_list'] = $this->items_model->get_item_list();
        $data['items_models'] = $this->items_model->get_item_list();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $data['application_user_list'] = $this->users_model->get_application_users();
        $this->template->load_admin('items/items_list', $data);
    }

    public function seller_id_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " fname like '%".$_POST['search']."%' ";
                $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role IN (4) AND status = 1';
        if (!empty($sql)){
             $query .= ' (' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`fname`,' ', `lname`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

    public function itemMakeApi()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " title like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        if (!empty($sql)){
             $query .= ' (' . implode(' OR ', $sql).') ';
        }
        $columns = "id, title AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

    public function itemModelApi()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " title like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        if (!empty($sql)){
             $query .= ' (' . implode(' OR ', $sql).') ';
        }
        $columns = "id, title AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

    public function sale_person_id_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " fname like '%".$_POST['search']."%' ";
                $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role = 3 AND status = 1';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`fname`,' ', `lname`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

    public function filter_items()
    {
        $data = array();
        if($this->input->post()){
            $posted_data = $this->input->post();
            unset($sql); 

        if (isset($posted_data['sale_person_id']) && !empty($posted_data['sale_person_id'])) {
 
            $sales_person_id = implode(",",$posted_data['sale_person_id']);
            $sql[] = " item.created_by IN ($sales_person_id) ";
        }

        if (isset($posted_data['keyword']) && !empty($posted_data['keyword'])) {
 
            $sql[] = " item.keyword like '%".$posted_data['keyword']."%' ";
        }

        if (isset($posted_data['lot_id']) && !empty($posted_data['lot_id'])) {
 
            $sql[] = " item.lot_id like '%".$posted_data['lot_id']."%' ";
        }

        if (isset($posted_data['item_status']) && !empty($posted_data['item_status'])) {
 
            $sql[] = " item.item_status = '".$posted_data['item_status']."' ";
        }

        if (isset($posted_data['registration_no']) && !empty($posted_data['registration_no'])) {
 
            $sql[] = " item.registration_no = '".$posted_data['registration_no']."' ";
        }

        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
            $sql[] = " item.category_id = ".$posted_data['category_id']." ";
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
             $query .= ' ' . implode(' OR ', $sql);
        }
            $data['items_list'] = $this->items_model->items_filter_list($query);
            $data_view = $this->load->view('items/items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function inspection_report(){
        $item_id = $this->uri->segment(3);
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

    public function itemList()
    {
        $posted_data = $this->input->post();

         // print_r($posted_data['datastring']);
        ## Read value
         $draw = $posted_data['draw'];
         $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
         $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
         $columnIndex = $posted_data['order'][0]['column']; // Column index
         $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
         $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
         $searchValue = $posted_data['search']['value']; // Search value

        // print_r($posted_data['datastring']);
         // echo '<pre>';
         // foreach ($posted_data['datastring'] as $key => $dataStringValue) {
             // print_r($dataStringValue['name']);
         // }
        // echo '</pre>';
        // Custom search filter 
         $category_id = (isset($posted_data['category_id'])) ? $posted_data['category_id'] : '';
         $seller_id = (isset($posted_data['seller_id']))? $posted_data['seller_id'] : '';
         $sale_person_id = (isset($posted_data['sale_person_id']))? $posted_data['sale_person_id']: '';
         $item_status = (isset($posted_data['item_status']))? $posted_data['item_status'] : '';
         $keyword = (isset($posted_data['keyword']))? $posted_data['keyword'] : '';
         $vin_number = (isset($posted_data['vin_no']))? $posted_data['vin_no'] : '';
         $registration_no = (isset($posted_data['registration_no'])) ? $posted_data['registration_no'] : '';
         $days_filter = (isset($posted_data['days_filter'])) ? $posted_data['days_filter'] : '';

         $make_id = (isset($posted_data['make_id']))? $posted_data['make_id'] : '';
         $model_id = (isset($posted_data['model_id']))? $posted_data['model_id'] : '';
         ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
            $search_arr[] = " (name like '%".$searchValue."%' ) ";
         }
         if($category_id != ''){
            $search_arr[] = " category_id='".$category_id."' ";
         }
         if($seller_id != ''){
            $seller_ids = implode(",",$seller_id);
            $search_arr[] = " item.seller_id IN (".$seller_ids.") ";
         }
         if($make_id != ''){
            $make_ids = implode(",",$make_id);
            $search_arr[] = " item.make IN (".$make_ids.") ";
         }
         if($model_id != ''){
            $model_ids = implode(",",$model_id);
            $search_arr[] = " item.model IN (".$model_ids.") ";
         }
         if($sale_person_id != ''){
            $sales_person_id = implode(",",$sale_person_id);
            $search_arr[] = " item.created_by IN (".$sales_person_id.") ";
         }
         if($item_status != ''){
            $search_arr[] = " item_status like '%".$item_status."%' ";
         }
         if($registration_no != ''){
            $search_arr[] = " registration_no like '%".$registration_no."%' ";
         }
         if($vin_number != ''){
            $search_arr[] = " vin_number like '%".$vin_number."%' ";
         }
         if($keyword != ''){
            $search_arr[] = " keyword like '%".$keyword."%' ";
         }
        if(isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $search_arr[] = " (DATE(item.created_on) between '".$start_date."' and '".$end_date."') ";
        }
        if($days_filter != ''){
            $interval = (($posted_data['days_filter'] == 'today')) ? 0 : $posted_data['days_filter'];
            $search_arr[] = " (DATE(item.created_on)  >= DATE(NOW()) + INTERVAL ".$interval." DAY  AND DATE(item.created_on) <  NOW() + INTERVAL  0 DAY) ";
         }
         if(count($search_arr) > 0){
            $searchQuery = implode(" AND ",$search_arr);
         }

        $total = $this->items_model->get_items();

                ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $records = $this->db->get('item')->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('item')->result();
        $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('item')->result();
        $data = array();
        $have_documents = false;
            // $records = $this->db->get('item',$rowperpage, $start)->result();

     foreach($records as $record ){
        $images_ids = explode(",",$record->item_images);
        $item_id = $record->id;
        $document_form_url =  base_url('items/documents/').$item_id;
        $inspection_url =  base_url('items/inspection_report/').$item_id;
        $expenses_url =  base_url('items/item_expenses/').$item_id;
        $view_document_url =  base_url('items/view_documents/').$item_id;
        $item_detail_url =  base_url('items/details/').urlencode(base64_encode($item_id));

        $image = '';
        $category = $this->db->get_where('item_category',['id'=>$record->category_id])->row_array();
        $seller = $this->db->get_where('users',['id'=>$record->seller_id])->row_array();
        $make = $this->db->get_where('item_makes',['id'=>$record->make])->row_array();
        $model = $this->db->get_where('item_models',['id'=>$record->model])->row_array();
        $auction_item = $this->db->get_where('auction_items',['item_id'=>$record->id]);
        $files_array = $this->files_model->get_multiple_files_by_ids($images_ids);
        if(!empty($record->item_images) || !empty($record->item_attachments)){
             $documents_class = 'btn-success';
             $have_documents = true;
            if(empty($record->item_attachments)){
                $documents_class = 'btn-info';
            }

        }else{
            $documents_class = 'btn-warning';
        }
        $item_name = json_decode($record->name);
        if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
            $file_name = $files_array[0]['name'];
            $urlImg = base_url('uploads/items_documents/').$item_id.'/37x36_'.$file_name;

            if(file_exists($urlImg)){
                $base_url_img = $urlImg;
            }else{
                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
            }

            $image = '<a class="text-success" href="'.$item_detail_url.'"><img style="width: 37px; height: 36px" class="img-responsive avatar-view" src="'.$base_url_img.'" alt="'.@$item_name->english.'"></a>';
        }else{
             $base_url_img =base_url('assets_admin/images/product-default.jpg');
             $image = '<a class="text-success" href="'.$item_detail_url.'"><img style="width: 37px" class="img-responsive avatar-view" src="'.$base_url_img.'" alt="'.@$item_name->english.'"></a>';
        }
        $action = '';

        if($this->loginUser->role == 1 && $record->sold == 'no'){
        $return_url = urlencode(base_url('items'));
        $action .= '<a href="'.base_url().'items/update_item/'.$item_id.'?rurl='.$return_url.'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
        }
        $action .= '<a href="'.$document_form_url.'" class="btn '.$documents_class.' btn-xs"><i class="fa fa-pencil"></i> Documents</a>  ';
        $cat_id = $this->db->get_where('item',['id' => $item_id , 'category_id' => 1])->row_array();
        if ($cat_id) {
        $action .= '<a href="'.$inspection_url.'" class="btn '.$documents_class.' btn-xs"><i class="fa fa-pencil"></i> Inspection Report</a>  ';
        }
        if($have_documents){
            $action .= '<a href="'.$view_document_url.'" class="btn '.$documents_class.' btn-xs"><i class="fa fa-pencil"></i> View Documents</a>';
        }
        $action .= '<button type="button" id="'.$item_id.'" data-toggle="modal" data-target=".bs-example-modal-print" onclick="getQRcode(this);"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_print_qr"><i class="fa fa-qrcode"></i> QR Code</button>';

        if($record->sold == 'no'){
            $action .= '<a id="'.$item_id.'" href="items/return_item/'.$item_id.'" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-reply"></i> Return</a>';
        }
        if($record->sold == 'return'){
            $action .= '<a id="'.$item_id.'" href="items/return_item/'.$item_id.'" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-reply"></i> View Invoice</a>';
        }

        $action .= '<a href="'.$expenses_url.'" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i> Expenses</a>  ';
        if(($this->loginUser->role == 1) && ($auction_item->num_rows() < 1) && $record->sold == 'no'){
            $action .= '<button onclick="deleteRecord(this)" type="button" data-obj="item" data-id="'.$item_id.'" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-url="'.base_url().'items/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';
        }
        $title = '';
        $title = json_decode($category['title']);
        // print_r($title->english);die();
        $make_title = json_decode($make['title']);
        $model_title = json_decode($model['title']);
        $seller_code = (isset($seller['id'])) ? $seller['id'] : '';
        $data[] = array( 
            "id" => $record->id,
            // "image"=>$image.'<input type="hidden" class="" value="'.$record->id.'" id="'.$record->id.'" />',
            "image"=>$image,
            //"input"=> '<input type="checkbox" class="flat multiple_rows ozproceed_check" id="'.$record->id.'" name="table_records"  value="'.$record->id.'">',
            "name"=> '<a class="text-success" href="'.$item_detail_url.'">'.@$item_name->english.'</a>',
            "category_id"=> @$title->english,
            "registration_no"=>$record->registration_no,
            "make"=>@$make_title->english,
            "model"=>@$model_title->english,
            "vin_number"=>$record->vin_number,
            "price"=>$record->price,
            "seller_id"=>$seller_code,
            "created_on"=> date('Y-m-d',strtotime($record->created_on)),
            "updated_on"=> date('Y-m-d',strtotime($record->updated_on)),
            "item_status"=> ucfirst($record->item_status),
            "sold"=> ucfirst($record->sold),
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

    public function return_item($item_id)
    {
        // User deposits by auction items
        // echo $item_id;die();
        if(!empty($item_id)){
            $data['generate_link'] = 'generate_seller_return_invoice';
            $data['small_title'] = 'Seller Return Tex Invoice';
            $data['invoice_link'] = 'view-return-invoice/'.$item_id;
            $data['back_url'] = base_url('items');
            $invoice = $this->db->get_where('invoices', ['item_id' => $item_id]);
            if ($invoice->num_rows() > 0) {
                $data['button_link'] = 'Update Payments';
            } else {
                $data['button_link'] = 'Generate Payments';
            }
            
            $item = $this->db->get_where('item', ['id' => $item_id])->row_array();
            $seller = $this->db->get_where('users', ['id' => $item['seller_id']])->row_array();
            $item_expencses = $this->db->get_where('item_expencses', ['item_id' => $item_id])->result_array();
            $vat_row = $this->db->select('*')->get_where('settings', ['code_key' => 'vat'])->row_array();
            $invoices = $this->db->get_where('invoices', ['item_id' => $item_id])->row_array();

            $today = date('Y-m-d H:i:s');

            $this->template->load_admin('items/return-receipt', 
                [
                    'item_expencses' => $item_expencses,
                    'item' => $item, 
                    'seller' => $seller, 
                    'vat_row' => $vat_row, 
                    'invoices' => $invoices, 
                    'data' => $data
                ]);
        }else{
            show_404();
        }
    }

    public function generate_seller_return_invoice()
    {
        $data = $this->input->post();
        if($data){
            // print_r($data);die();
            $item_id = $data['item_id'];
            $item_expenses_amount = isset($data['item_expenses_amount']) ? $data['item_expenses_amount'] : '';
            $item_expenses = isset($data['item_expenses']) ? $data['item_expenses'] : '';
            $item_description = isset($data['item_description']) ? $data['item_description'] : '';
            unset($data['item_expenses_amount']);
            unset($data['item_expenses']);
            unset($data['item_description']);

            $new_data = [
                'user_id' => $data['user_id'],
                'type' => 'return',
                'user_po_box' => $data['user_po_box'],
                'user_address' => $data['user_address'],
                'item_id' => $data['item_id'],
                'vin_number' => $data['vin_number'],
                'vat' => $data['vat'],
                'customer_name' => $data['customer_name'],
                'purpose' => $data['purpose'],
                'payment_terms' => $data['payment_terms'],
                'payable_amount' => $data['payable_amount'],
                'description' => $data['description'],
                'bank_id' => $data['bank']
            ];
            $apply_vat_array = [];
            $item_expenses = $this->db->get_where('item_expencses', ['item_id' => $data['item_id']])->result_array();
            foreach ($item_expenses as $key1 => $value) {
                $title = json_decode($value['title']);
                $item_field_title = strtolower(str_replace(' ', '_', $title->english));
                $apply_vat_array[$item_field_title] = $value['apply_vat'];
            }
            $new_data['apply_vat'] = json_encode($apply_vat_array);
            $new_data['item_expenses_amount'] = json_encode($item_expenses_amount);
            $new_data['item_expenses'] = json_encode($item_expenses);
            $new_data['item_description'] = json_encode($item_description);

            unset($data['user_id']);
            unset($data['user_po_box']);
            unset($data['user_address']);
            unset($data['item_id']);
            unset($data['vin_number']);
            unset($data['vat']);
            unset($data['customer_name']);
            unset($data['purpose']);
            unset($data['payment_terms']);
            unset($data['payable_amount']);
            unset($data['trn_no']);
            unset($data['description']);
            unset($data['bank']);
            $new_data['other_details'] = json_encode($data);
            $invoice_exist = $this->db->get_where('invoices', ['item_id' => $item_id]);
            if ($invoice_exist->num_rows() == 0) {
                $this->db->insert('invoices', $new_data);
                $this->db->update('item', ['sold' => 'return'], ['id' => $item_id]);
                $this->db->update('auction_items', ['sold_status' => 'return'], ['item_id' => $item_id]);
                // print_r($data);
            } else {
                $this->db->update('invoices', $new_data, ['item_id' => $item_id]);
            }

            $this->session->set_flashdata('success', 'Payment data updated successfully.');
            // print_r($sold_item_id);die();
            redirect(base_url('items/return_item/'.$item_id));
            // $this->load->view('receipts/seller-invoice', ['data' => $data]); 
        }else{
            show_404();
        }
    }



    public function view_return_tex_invoice($item_id)
    {
        // $data = $this->input->post();
        $data1 = $this->db->get_where('invoices', ['item_id' => $item_id])->row_array();
        if($data1){
            $invoice = $this->db->get_where('invoices', ['item_id' => $item_id])->row_array();
            if ($invoice['invoice_status'] == '0') {
                $data['new_recipt'] = 'yes';
                $receipt = date('yM');
                $receipt = array(
                    's_invoice' => $receipt.'-04-'.$item_id
                );
                $receipt_date = array(
                    's_invoice' => date('Y-m-d H:i:s')
                );
                $json_receipt_date = json_encode($receipt_date);
                $json_receipt = json_encode($receipt);
                $this->db->update('invoices', ['invoice_status' => '1','receipt_no' => $json_receipt,'receipt_date' => $json_receipt_date], ['item_id' => $item_id]);
            }
            // print_r($data);

            $data = $this->db->get_where('invoices', ['item_id' => $item_id])->row_array();
            $this->load->view('receipts/seller-invoice', ['data' => $data]); 
        }else{
            show_404();
        }
    }

    public function item_expenses($item_id)
    {
        // User deposits by auction items
        //echo $user_id;
        if(!empty($item_id)){
            $data['back_url'] = base_url('items');
            
            $item = $this->db->get_where('item', ['id' => $item_id])->row_array();
            $item_expencses = $this->db->get_where('item_expencses', ['item_id' => $item_id])->result_array();
            
            $today = date('Y-m-d H:i:s');

            $this->template->load_admin('items/item_expenses', ['item_expencses' => $item_expencses,'item' => $item, 'data' => $data]);
        }else{
            show_404();
        }
    }

    public function add_item_expense()
    {
        $data = $this->input->post();
        if($data){
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = $this->loginUser->id;
        }
        // print_r($data);die();
        $result = $this->db->insert('item_expencses', $data);
        if($result){
            $this->session->set_flashdata('success', 'Item expenses has been successfully added.');
        }else{
            $this->session->set_flashdata('error', 'Item expenses has been failed to add.');
        }
        redirect(base_url('items/item_expenses/'.$data['item_id']));
    }




    public function item_detail()
    {
        $data = array();
        $item_id = base64_decode(urldecode($this->uri->segment(3)));
        $data['item_id'] = $item_id;
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']);
        $data['item_expencses'] = $this->db->get_where('item_expencses',['item_id' => $data['item_id']])->result_array();
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

        $this->template->load_admin('items/items_detail', $data);
    }

    // Update an Item in popup model
    public function edit_item_detail_view()
    {
        $data = array();
        $field_ids_list = array();
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
            if (!empty($multiple_info)) {
                $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
            }
            // print_r($value['fields_id'].'  - '.$multiple_info[0]['multiple'].' ');
          }
            $data['field_ids'] = $field_ids;
            $data['field_values'] = $field_ids_list;
        }

            $data['category_list'] = $this->items_model->get_item_category_active();


            $data_view = $this->load->view('items/item_detail_form', $data, true);
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
            $test_report_ids = explode(",",$data['item_info'][0]['item_test_report']);
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
              // $this->template->load_admin('items/documents_form', $data);
            $data_view = $this->load->view('items/item_detail_documents_form', $data, true);
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
                    'field' => 'item[name_english]',
                    'label' => 'Name',
                    'rules' => 'trim|required'), 
                array(
                    'field' => 'item[name_arabic]',
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
                $name = [
                    'english' => $item_data['name_english'],
                    'arabic' => $item_data['name_arabic'],
                ];
                $detail = [
                    'english' => $item_data['detail_english'],
                    'arabic' => $item_data['detail_arabic'],
                ];
                $terms = [
                    'english' => $item_data['terms_english'],
                    'arabic' => $item_data['terms_arabic'],
                ];
                $additional_info = [
                    'english' => $item_data['additional_info_english'],
                    'arabic' => $item_data['additional_info_arabic'],
                ];

                $posted_data = array(
                'name' => json_encode($name),
                'location' => $item_data['location'],
                'detail' => json_encode($detail),
                'terms' => json_encode($terms),
                'additional_info' => json_encode($additional_info),
                'status' => $item_data['status'],
                'item_status' => $item_data['item_status'],
                'price' => $item_data['price'],
                'category_id' => $item_data['category_id'],
                'seller_id' => $seller_id,
                // 'auction_type' => implode(",", $item_data['auction_type']),
                'updated_by' => $this->loginUser->id
                );

                if(isset($item_data['lat']) && !empty($item_data['lat']))
                {
                    $posted_data['lat'] = $item_data['lat'];
                }
                if(isset($item_data['lng']) && !empty($item_data['lng']))
                {
                    $posted_data['lng'] = $item_data['lng'];
                }
                if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $item_data['subcategory_id'];
                }

                if(isset($item_data['keyword']) && !empty($item_data['keyword']))
                {
                    $posted_data['keyword'] = $item_data['keyword'];
                }

                if(isset($item_data['vin_no']) && !empty($item_data['vin_no']))
                {
                    $posted_data['vin_number'] = $item_data['vin_no'];
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
    public function get_banner_details()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
        {
            $images_ids = explode(",",$data['item_row'][0]['item_images']);
           $data['files_array'] = $this->files_model->get_multiple_files_by_ids($images_ids);
        }
        else
        {
            $data['files_array'] = array();
        }
        // print_r($data);die();

        $data_view = $this->load->view('items/items_details_content', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function get_qrcode()
    {
        $data = array();
        $data_view['item_id'] = $this->input->post('id');
        $item_row = $this->items_model->get_item_byid($data_view['item_id']); 
        $image = base_url()."uploads/items_documents/".$data_view['item_id']."/qrcode/".$item_row[0]['barcode'];
        $data_view = '<div class="row" style=" display: flex;"><img style="" width="75" height="75" src="'.$image.'" alt="" title="QR Code"/><div style="display: flex; flex-wrap: wrap; align-items: stretch;"><h3 class="" style="font-size: 9px; width: 100px; margin-top: 11px; height: 30px; word-wrap: break-word;">'.json_decode($item_row[0]['name'])->english.'</h3><h2 class="" style=" display: flex; align-items: flex-end; width:100%; font-size: 9px !important; margin-bottom: 9px;"> '.$item_row[0]['registration_no'].' </h2></div></div>';
        // $data_view .= '<div class="product_price col-md-4" style="display: flex; align-items: flex-end; transform: translate(90px, 29px); position: absolute; background: none; border: none;"><h2 class="" style="margin-left:10px !important; font-size: 9px !important;"> '.$item_row[0]['registration_no'].' </h2></div>';
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function get_bulk_qrcode()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $item_id_array = explode(",",$data['item_id']);
        // print_r($item_id_array);
        $data_view = '';
        foreach ($item_id_array as $item_id) {
            
        $item_row = $this->items_model->get_item_byid($item_id); 
        $image = base_url()."uploads/items_documents/".$item_id."/qrcode/".$item_row[0]['barcode'];
        $data_view .= '<div class="container"><div class="row" style="margin-left:10px;"><h3 class="col-md-10">'.json_decode($item_row[0]['name'])->english.'</h3> <img width="188.97" height="94.48" class="col-md-10" src="'.$image.'" alt="" title="QR Code"/><div class="product_price col-md-10"><h2 class=""> '.$item_row[0]['registration_no'].' </h2></div></div>';
        $data_view .= '</div>';
        }
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function show_items_content()
    {
          
        $data = array();
        $data['small_title'] = 'Items List';
        $data['items_list'] = $this->items_model->get_items();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql);
        if (isset($posted_data['customer_type_id']) && !empty($posted_data['customer_type_id'])) {
            $customer_id = implode(",",$posted_data['customer_type_id']);
            $sql[] = " crm_detail.customer_type_id IN ($customer_id) ";
        } 
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' OR ', $sql);
        }

            $data['items_list'] = $this->items_model->items_filter_list($query);
            $data_view = $this->load->view('items/items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            $output = array(
             "draw"       =>  intval('draw'),
             "recordsTotal"   =>  count($data['items_list']),
             "recordsFiltered"  =>  10,
             "data"       =>  $data_view
            );
            echo json_encode($output);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }

    }


    public function makes()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Makes';
        $data['current_page_item_make'] = 'current-page';
        $data['makes_list'] = $this->items_model->makes_list();
        $data['language'] = $this->language;
        $this->template->load_admin('items/makes/make_list', $data);
    }


    public function models()
    {
        $data = array();
        $data['small_title'] = 'Model List';
        $data['current_page_item_make'] = 'current-page';
        $make_id = $this->uri->segment(3);
        $data['language'] = $this->language;
        $data['items_model_list'] = $this->items_model->get_item_model_list($make_id);
        $data['items_make'] = $this->db->select('title')->from('item_makes')->where('id',$make_id)->get()->row_array();
        $this->template->load_admin('items/model/model_list', $data);
    }

    public function save_makes()
    {
       $data = array();
        $data['small_title'] = 'Add Item Make';
        $data['current_page_item_make'] = 'current-page';
        $data['formaction_path'] = 'save_makes';
        $data_item = $this->input->post();
        if ($data_item) {
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'title_arabic',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) 
            {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $posted_data = array(
                // 'title' => $this->input->post('title'),
                'status' => $this->input->post('status'),
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->loginUser->id
                );
                $title = [
                    'english' => $data_item['title'],
                    'arabic' => $data_item['title_arabic'],
                ];
                unset($data_item['title']); 
                unset($data_item['title_arabic']);
                $posted_data['title'] = json_encode($title);

                $result = $this->items_model->insert_make($posted_data);
                if (!empty($result)) 
                {
                  $this->session->set_flashdata('msg', 'Make Added Successfully');
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
            $this->template->load_admin('items/makes/make_form', $data);
        }
    }


    // Update a Category
    public function update_makes()
    {
        $data = array();
        $data['small_title'] = 'Update Make';
        $data['current_page_item_make'] = 'current-page';
        $data['formaction_path'] = 'update_makes';
        $data['language'] = $this->language;
        $make_id = $this->uri->segment(3);
        $data['makes_info'] = $this->items_model->get_item_makebyid($make_id);
        $data_item = $this->input->post();
        if ( $data_item) {
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $id = $this->input->post('id');
                $posted_data = array(
                // 'title' => $this->input->post('title'),
                'status' => $this->input->post('status'),
                'updated_by' => $this->loginUser->id
                );

                $title = [
                    'english' => $data_item['title'],
                    'arabic' => $data_item['title_arabic'],
                ];
                unset($data_item['title']); 
                unset($data_item['title_arabic']);
                $posted_data['title'] = json_encode($title);
                
                $result = $this->items_model->update_make($id,$posted_data);
                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Make Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }else{

            $this->template->load_admin('items/makes/make_form', $data);
        }

    }

    // Add a new models
    public function save_models()
    {
        $data = array();
        $data['small_title'] = 'Add Model';
        $data['current_page_item_make'] = 'current-page';
        $data['make_id'] = $this->uri->segment(3);
        $data['formaction_path'] = 'save_models/'.$data['make_id'];
        $data_item = $this->input->post();
        if ($data_item) {
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'title_arabic',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $posted_data = array(
                'make_id' => $this->input->post('make_id'),
                // 'title' => $this->input->post('title'),
                'status' => $this->input->post('status'),
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->loginUser->id
                );

                $title = [
                    'english' => $data_item['title'],
                    'arabic' => $data_item['title_arabic'],
                ];
                unset($data_item['title']); 
                unset($data_item['title_arabic']);
                $posted_data['title'] = json_encode($title);

                $result = $this->items_model->insert_model($posted_data);
                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item model added successfully.');
                  $msg = 'success';
                  redirect(base_url().'items/models/'.$posted_data['make_id']);
                  // echo json_encode(array('msg' => $msg, 'in_id' => $result));
                }
                else
                {
                  $this->session->set_flashdata('error', 'Item model has been failes to add.');
                    $msg = 'DB Error found.';
                    $this->template->load_admin('items/model/model_form', $data);
                }
            }
        }
        else
        {
            $this->template->load_admin('items/model/model_form', $data);
        }

    }
    
     // Update a Category
    public function update_models()
    {
        $data = array();
        $data['small_title'] = 'Update Model';
        $data['current_page_item_make'] = 'current-page';
        $model_id = $this->uri->segment(3);
        $data['make_id'] = $this->uri->segment(4);
        $data['formaction_path'] = 'update_models/'.$data['make_id'];
        $data['model_info'] = $this->items_model->get_item_model_row($model_id);
        $data_item = $this->input->post();
        if ($data_item) {
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $id = $this->input->post('id');
                $posted_data = array(
                'make_id' => $this->input->post('make_id'),
                // 'title' => $this->input->post('title'),
                'status' => $this->input->post('status'),
                'updated_by' => $this->loginUser->id
                );

                $title = [
                    'english' => $data_item['title'],
                    'arabic' => $data_item['title_arabic'],
                ];
                unset($data_item['title']); 
                unset($data_item['title_arabic']);
                $posted_data['title'] = json_encode($title);
                    // print_r($posted_data);
                $result = $this->items_model->update_model($id,$posted_data);
                if (!empty($result)) {
                    $this->session->set_flashdata('msg', 'Item model updated successfully');
                    $msg = 'success';
                    redirect(base_url().'items/models/'.$posted_data['make_id']);
                    // json_encode(array('msg' => $msg, 'in_id' => $result));
                }
                else
                {
                    $this->session->set_flashdata('error', 'Item model has been failed tp update.');
                    $msg = 'DB Error found.';
                    $this->template->load_admin('items/model/model_form', $data);
                    
                }
            }
        }else{

            $this->template->load_admin('items/model/model_form', $data);
        }

    }
   



    function removeItemString($str, $item) {
        $parts = explode(',', $str);
        while(($i = array_search($item, $parts)) !== false) {
        unset($parts[$i]);
        }
        return implode(',', $parts);
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

    public function delete_3d_image($itemId)
    {
        $attach_name = $this->input->post('file_to_be_deleted');
        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {

            $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['threed_images']);

                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0]['threed_images'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'threed_images' => $updated_str,
                'updated_by' => $this->loginUser->id
            ];
            $result_update = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
        $path = FCPATH .  "uploads/items_threed/".$itemId."/";
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
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
                echo json_encode(array('error' => true,'message'=>$uploaded_file_ids['error']));
            } else {
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
        }
        else
        {
            echo json_encode(array("error"=> true,'message' => "No item found to upload."));
        }
    }

    public function save_3d_file_images()
    {

        if( ! empty($_FILES['threed_images']['name'])){
            // print_r($_FILES);
            $item_id = $_POST['item_id'];
            $itemsIds_array = array();
            $ids_concate = '';

            $result_array = $this->items_model->get_item_byid($item_id);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['threed_images']);

                if(!empty($itemsIds_array) && !empty($result_array[0]['threed_images']))
                {
                    $ids_concate = $result_array[0]['threed_images'].",";
                }
            }

            $path = "uploads/items_threed/".$item_id."/qrcode/";
            // make path
            if ( !is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }

            // make path
            $path = './uploads/items_threed/';
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
            $uploaded_file_ids = $this->files_model->multiUpload2('threed_images', $config, $sizes,$item_id); // upload and save to database
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
                echo json_encode(array('error' => true,'message'=>$uploaded_file_ids['error']));
            } else {
                // $uploaded_file_ids = $this->files->upload('images', $config, $sizes);
                // $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $update = [
                    'threed_images' => $ids_concate.$uploaded_file_ids
                ];
                $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';

                if($result == 'true')
                {
                    $this->session->set_flashdata('msg', 'Item 3D Added Successfully');
                    echo json_encode(array("error"=> false,'message' => "Item 3D Added Successfully"));
                }
                else
                {
                    echo json_encode(array("error"=> true,'message' => "Uploading process has been failed."));
                }
            }
        }
        else
        {
            echo json_encode(array("error"=> true,'message' => "No item found to upload."));
        }
    }

    public function save_item_file_documents()
    {
        if( ! empty($_FILES['documents']['name'])){

            // print_r($_FILES);
            // exit;
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
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls|ppt|pptx|txt';
            $uploaded_file_ids = $this->files_model->multiUpload('documents', $config); // upload and save to database
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
                echo json_encode(array('error' => true,'message'=>$uploaded_file_ids['error']));
            } else {
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $update = [
                    'item_attachments' => $ids_concate.$uploaded_file_ids,
                    'item_status' => 'completed',
                ];
                 $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';
                if($result == 'true')
                {
                    $this->session->set_flashdata('msg', 'Item Documents Added Successfully');
                    echo json_encode(array("error"=> false,'message' => "Item Documents Added Successfully"));
                }
                else{
                    echo json_encode(array("error"=> true,'message' => "Uploading process has been failed."));
                }
            }
        }
        else{
            echo json_encode(array("error"=> true,'message' => "No item found to upload."));
        }

    }
    
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
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls|ppt|pptx|txt';
            $uploaded_file_ids = $this->files_model->multiUpload('test_documents', $config); // upload and save to database
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
                echo json_encode(array('error' => true,'message'=>$uploaded_file_ids['error']));
            } else {
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $update = [
                    'item_test_report' => $ids_concate.$uploaded_file_ids,
                    // 'item_status' => 'completed',
                ];
                 $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';
                if($result == 'true')
                {
                    $this->session->set_flashdata('msg', 'Item Test Report Added Successfully');
                     echo json_encode(array("error"=> false,'message' => "Item Test Report Added Successfully"));
                }
            }
        }
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


    public function validate_check_registration_no()
    {
        $check_number = $this->items_model->check_registration_no($this->uri->segment(3));
        if(isset($check_number) && !empty($check_number))
        {
            echo '404';
        }
        else
        {
            echo '200';
        }
    }

    // Check vin number
    public function check_vin_number()
    {
        $vin_number = $this->input->post('vin_number');
        $result = $this->db->get_where('item', ['vin_number' => $vin_number])->row_array();
        if (!empty($result)) {
            echo json_encode(array('success' => true, 'msg' => 'Vin Number already exist.'));
        }
        else
        {
            echo json_encode(array('error' => true));
        }

    }
    // Add a new Item
    public function save_item()
    {
        // $this->output->enable_profiler(TRUE);
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Item';
        $data['formaction_path'] = 'save_item';
        $data['current_page_list'] = 'current-page';
        $data['category_list'] = $this->items_model->get_item_category_active();
        // $data['seller_list'] = $this->users_model->users_list(3);
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        

        if ($this->input->post()) {
        $item_dynamic_data = $this->input->post();

        
        $item_data = $this->input->post('item'); // get basic information 
        unset($item_dynamic_data['item']);  // remove basic information form data

        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'item[name_english]',
                    'label' => 'Name english',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'item[name_arabic]',
                    'label' => 'Name arabic',
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
                $rand_str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomKey = $this->generate_string($rand_str);
                $name = [
                    'english' => $item_data['name_english'],
                    'arabic' => $item_data['name_arabic'],
                ];
                if (empty($item_data['detail_english'])) {
                    $engDescription = array();
                    $arabicDescription = array();
                    foreach ($item_dynamic_data as $key => $value) {
                        $dataValues = array();
                        $ids = explode("-", $key);
                        $fields = $this->db->get_where('item_category_fields', ['id' => $ids[0]])->row_array();
                        $fields['values'] = json_decode($fields['values'],true);  
                        $fields['data-id'] = $fields['id'];
                        if (!empty($fields['values'])) {
                            foreach ($fields['values'] as $key => $options) {
                                if (is_array($value)) {
                                    if (in_array($options['value'], $value)) {
                                        $dataValues[] = $options['label'];
                                    }
                                } else {
                                    if ($options['value'] == $value) {
                                        $dataValue = $options['label'];
                                    }
                                }
                            }
                        }else{
                            $dataValue = $value;
                        }
                        if (!empty($dataValues)) {
                            foreach ($dataValues as $key => $dataVal) {
                                $exp_str = explode('|', $dataVal);
                                $engDescription[] =  $exp_str[0];
                                if (isset($exp_str[1])) {
                                    $arabicDescription[] = $exp_str[1];
                                }
                            }
                        } else {
                            $exp_str = explode('|', $dataValue);
                            $engDescription[] =  $exp_str[0];
                            if (isset($exp_str[1])) {
                                $arabicDescription[] = $exp_str[1];
                            }
                        }
                    }
                    $endes = implode(',', $engDescription);
                    $ardes = implode(',', $arabicDescription);
                    $detail = [
                        'english' => $endes,
                        'arabic' => $ardes,
                    ];
                } else{
                    $detail = [
                        'english' => $item_data['detail_english'],
                        'arabic' => $item_data['detail_arabic'],
                    ];
                }
                $terms = [
                    'english' => $item_data['terms_english'],
                    'arabic' => $item_data['terms_arabic'],
                ];

                $additional_info = [
                    'english' => $item_data['additional_info_english'],
                    'arabic' => $item_data['additional_info_arabic'],
                ];
                
                $posted_data = array(
                    // 'id' => $item_data['id'],
                    'name' => json_encode($name),
                    'location' => $item_data['location'],
                    'year' => $item_data['year'],
                    'detail' => json_encode($detail),
                    'terms' => json_encode($terms),
                    'additional_info' => json_encode($additional_info),
                    'status' => $item_data['status'],
                    'item_status' => $item_data['item_status'],
                    'category_id' => $item_data['category_id'],
                    'feature' => $item_data['feature'],
                    'seller_id' => $seller_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => $this->loginUser->id
                );

                if(isset($item_data['lat']) && !empty($item_data['lat']))
                {
                    $posted_data['lat'] = $item_data['lat'];
                }
                if(isset($item_data['lng']) && !empty($item_data['lng']))
                {
                    $posted_data['lng'] = $item_data['lng'];
                }
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

                $result = $this->items_model->insert_item($posted_data);
                $posted_data['id'] =$result;
 
                $path = "uploads/items_documents/".$result."/qrcode/";

                // make path
                if ( !is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                } 

                $qrcode_name = $this->generate_code($result,$path, ['id' => $posted_data['id']]);
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
                        $dynamic_values_new = implode(",",$dynamic_values);
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
                  $this->session->set_flashdata('msg', 'Item Added Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }else{
            $this->template->load_admin('items/item_form', $data);
        }

    } 

    //users
    public function load_user_popup()
    {
        $posted_data = $this->input->post();


        $users_list = $this->db->get_where('users', ['role' => 4])->result_array();
        $opup_data = $this->load->view('items/users_list', ['users_list' => $users_list], true);
        if ($opup_data) {
            $output = json_encode([
                'success' => true,
                'opup_data' => $opup_data,
                'msg' => 'User data get successfully.' ]);
            return print_r($output);
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
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 5; // Rows display per page
        
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value

        $keyword = (isset($posted_data['searchby']))? $posted_data['searchby'] : '';
        $search_arr = array();
         $searchQuery = "";
        if($keyword != ''){
            $search_arr[] = " ( fname like '%".$keyword."%' or username like '%".$keyword."%' or lname like '%".$keyword."%' or mobile like '%".$keyword."%' ) ";
        }

        if(count($search_arr) > 0){
            $searchQuery = implode(" AND ",$search_arr);
        }
                ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->where('role', 4);
         $records2 = $this->db->get('users')->result();
         $totalRecords = $records2[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('role', 4);
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $this->db->get('users')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $this->db->select('*');
        $this->db->where('role',4);
        if($searchQuery != ''){
            $this->db->where($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $users_list = $this->db->get('users')->result_array();
        foreach ($users_list as $key => $value) {
            # code...

        $data[] = array( 
         "id"=>$value['id'],
         "fname"=>$value['fname'].' '.$value['lname'] ,
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

    // Update a Item
    public function update_item()
    {
        $data = array();
        $field_ids_list = array();
        // $data['small_title'] = 'Update Item';
        $data['formaction_path'] = 'update_item';
        $data['current_page_list'] = 'current-page';
        $data['item_id'] = $this->uri->segment(3);
        $category_id = $this->uri->segment(3);
     
        $data['item_info'] = $this->items_model->get_item_byid($category_id);
        $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid($category_id);
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
            if (!empty($multiple_info)) {
                $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
            }
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
        // print_r($item_data);die();
        unset($item_dynamic_data['item']);  // remove basic information form data
   

        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'item[name_english]',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'item[name_arabic]',
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
                $name = [
                    'english' => $item_data['name_english'],
                    'arabic' => $item_data['name_arabic'],
                ];

                if (empty($item_data['detail_english'])) {
                    $engDescription = array();
                    $arabicDescription = array();
                    foreach ($item_dynamic_data as $key => $value) {
                        $dataValues = array();
                        $ids = explode("-", $key);
                        $fields = $this->db->get_where('item_category_fields', ['id' => $ids[0]])->row_array();
                        $fields['values'] = json_decode($fields['values'],true);  
                        $fields['data-id'] = $fields['id'];
                        if (!empty($fields['values'])) {
                            // print_r($fields['values']);
                            // print_r($value);
                            foreach ($fields['values'] as $key => $options) {
                                if (is_array($value)) {
                                    if (in_array($options['value'], $value)) {
                                        $dataValues[] = $options['label'];
                                    }
                                } else {
                                    if ($options['value'] == $value) {
                                        $dataValue = $options['label'];
                                    }
                                }
                            }
                        }else{
                            $dataValue = $value;
                        }
                        if (!empty($dataValues)) {
                            foreach ($dataValues as $key => $dataVal) {
                                $exp_str = explode('|', $dataVal);
                                $engDescription[] =  $exp_str[0];
                                if (isset($exp_str[1])) {
                                    $arabicDescription[] = $exp_str[1];
                                }
                            }
                        } else {
                            $exp_str = explode('|', $dataValue);
                            $engDescription[] =  $exp_str[0];
                            if (isset($exp_str[1])) {
                                $arabicDescription[] = $exp_str[1];
                            }
                        }
                    }
                        // die();
                    $endes = implode(',', $engDescription);
                    $ardes = implode(',', $arabicDescription);
                    $detail = [
                        'english' => $endes,
                        'arabic' => $ardes,
                    ];
                } else{
                    $detail = [
                        'english' => $item_data['detail_english'],
                        'arabic' => $item_data['detail_arabic'],
                    ];
                }
                
                $terms = [
                    'english' => $item_data['terms_english'],
                    'arabic' => $item_data['terms_arabic'],
                ];
                // additional info
                $additional_info = [
                    'english' => $item_data['additional_info_english'],
                    'arabic' => $item_data['additional_info_arabic'],
                ];

                $posted_data = array(
                'name' => json_encode($name),
                'location' => $item_data['location'],
                'year' => $item_data['year'],
                'detail' => json_encode($detail),
                'terms' => json_encode($terms),
                'additional_info' => json_encode($additional_info),
                'status' => $item_data['status'],
                'item_status' => $item_data['item_status'],
                'price' => $item_data['price'],
                'category_id' => $item_data['category_id'],
                'feature' => $item_data['feature'],
                'seller_id' => $seller_id,
                // 'auction_type' => implode(",", $item_data['auction_type']),
                'updated_by' => $this->loginUser->id
                );

                if(isset($item_data['lat']) && !empty($item_data['lat']))
                {
                    $posted_data['lat'] = $item_data['lat'];
                }
                if(isset($item_data['lng']) && !empty($item_data['lng']))
                {
                    $posted_data['lng'] = $item_data['lng'];
                }
                if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $item_data['subcategory_id'];
                }

                if(isset($item_data['keyword']) && !empty($item_data['keyword']))
                {
                    $posted_data['keyword'] = $item_data['keyword'];
                }
                // if(isset($item_data['other_charges']) && !empty($item_data['other_charges']))
                // {
                //     $posted_data['other_charges'] = $item_data['other_charges'];
                // }

                if(isset($item_data['vin_no']) && !empty($item_data['vin_no']))
                {
                    $posted_data['vin_number'] = $item_data['vin_no'];
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
                    $posted_data['mileage'] = $item_data['mileage'];    
                    $posted_data['mileage_type'] = $item_data['mileage_type'];    
                    $posted_data['specification'] = $item_data['specification'];    
                }

                $result = $this->items_model->update_item($id,$posted_data);
                $item_row_array = $this->items_model->get_item_byid($id);

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
                if(empty($item_row_array[0]['barcode']))
                {
                    $this->files_model->delete_by_id($item_row_array[0]['barcode'], $path);
                }
               



                $result_attachments = array();
  

                foreach ($item_dynamic_data as $dynamic_keys => $dynamic_values) {
                $ids_arr = explode("-", $dynamic_keys);
                if(is_array($dynamic_values))
                    {
                        $dynamic_values_new = implode(",",$dynamic_values);
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

            $this->template->load_admin('items/item_form', $data);
        }

    }

    public function documents()
    {
        $data = array();
        $data['small_title'] = 'Manage Documents';
        $data['current_page_list'] = 'current-page';
        $data['formaction_path'] = 'update_item';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);     

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $test_report_ids = explode(",",$data['item_info'][0]['item_test_report']);
            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $item_test_report_documents = $this->items_model->get_item_documents_byid($test_report_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $threed_imaages = explode(",",$data['item_info'][0]['threed_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            $three_d = $this->items_model->get_item_three_d_images($threed_imaages);
            // print_r($item_images);

            foreach ($three_d as $value)
            {
                $data['three_d'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type']
                );
            }


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

            foreach ($item_documents as $value) {
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
        if ($this->input->post()){

        }
        else
        {
              $this->template->load_admin('items/documents_form', $data);
        }
    }




    public function updateOtherCharges(){
        $data = $this->input->post();
        // print_r($data);
        if(isset($data['id']) && !empty($data['charges'])){
            $response = $this->db->update('item', ['other_charges'=>$data['charges']], ['id'=>$data['id']]);
            if($response){
                $data_message = "Other Charges has been updated";
                echo json_encode(array('error'=>false, 'msg'=>'success', 'response' => $data_message));
            }else{
                $data_message = "unable to update other charges";
                echo json_encode(array( 'error'=>true, 'msg'=>'error', 'response' => $data_message));
            }

        }else{
            $data_message = "unable to update other charges";
            echo json_encode(array( 'error'=>true, 'msg'=>'error', 'response' => $data_message));
        }


    }

    public function update_files_order()
    {
        $data = array();
        $item = array();
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
        if($this->input->post('test_documents_order'))
        {
        $data_array = $this->input->post('test_documents_order');
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



    public function view_documents()
    {
        $data = array();
        $data['small_title'] = 'Documents ';
        $data['formaction_path'] = 'update_item';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);     

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $test_report_ids = explode(",",$data['item_info'][0]['item_test_report']);
            $item_test_report_documents = $this->items_model->get_item_documents_byid($test_report_ids);
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
                    'url' => $value['name'],
                    'type' => $value['type'],
                    'file_order' => $value['file_order'],
                    'status' => $value['status']
                );
            }
            foreach ($item_documents as $value) 
            {
                $data['item_documents'][] = array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'orignal_name' => $value['orignal_name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type'],
                    'file_order' => $value['file_order'],
                    'status' => $value['status']
                );
            }


            foreach ($item_test_report_documents as $value) {
                $data['item_test_documents'][] = array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'orignal_name' => $value['orignal_name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type'],
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
              $this->template->load_admin('items/documents_list', $data);
        }
    }


    public function get_bidding_rules()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['bidding_info'] = $this->items_model->bidding_rule_list($data['item_id']); 
        $data_view = $this->load->view('items/bidding_rules', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

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
                    'field' => 'start_price',
                    'label' => 'Start Price',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'minimum_price',
                    'label' => 'Minimum Price',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'highest_price',
                    'label' => 'Highest Price',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'minimum_increment',
                    'label' => 'Minimum Increment',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'bidding_time',
                    'label' => 'Bidding Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'auction_type[]',
                    'label' => 'Auction Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

            // echo json_encode(array('msg' => validation_errors()));
             echo json_encode(array( 'msg'=>'error', 'data' => validation_errors()));
        } 
        else
        { 

        if($this->input->post('id'))
        {
        $id = $this->input->post('id');
        }
        if(!empty($id))
        {
           $posted_data = array(
                    'item_id' => $this->input->post('item_id'),
                    'auction_type' => implode(",", $this->input->post('auction_type')),
                    'start_price' => $this->input->post('start_price'),
                    'minimum_price' => $this->input->post('minimum_price'),
                    'highest_price' => $this->input->post('highest_price'),
                    'minimum_increment' => $this->input->post('minimum_increment'),
                    'bidding_time' => date('Y-m-d h:i:s',strtotime($this->input->post('bidding_time'))),
                    'status' => $this->input->post('status'),
                    'updated_by' => $this->loginUser->id
                    );
            $result = $this->items_model->update_bidding_rules($id,$posted_data);
            $msg_ = 'Item Bidding Rules Updated Successfully';
        }
        else
        {
            $posted_data = array(
                    'item_id' => $this->input->post('item_id'),
                    'auction_type' => implode(",", $this->input->post('auction_type')),
                    'start_price' => $this->input->post('start_price'),
                    'minimum_price' => $this->input->post('minimum_price'),
                    'highest_price' => $this->input->post('highest_price'),
                    'minimum_increment' => $this->input->post('minimum_increment'),
                    'bidding_time' => date('Y-m-d h:i:s',strtotime($this->input->post('bidding_time'))),
                    'status' => $this->input->post('status'),
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => $this->loginUser->id
                    );
            $result = $this->items_model->insert_bidding_rules($posted_data);
            $msg_ = 'Item Bidding Rules created Successfully';
        }
          
          if($result)
          {
            $this->session->set_flashdata('msg', $msg_);
            echo json_encode(array( 'msg'=>'success', 'data' => $result));
          }
          else
          {
            echo json_encode(array( 'msg'=>'error', 'data' => 'Error Found'));
          }
        }
      }
    

    }

    public function get_item_fields()
    {
        $data = array();
        $category_id = $this->input->post('category_id');
        $datafields = $this->items_model->fields_data($category_id);
        $fdata = array();
        foreach ($datafields as $fields)
        {
            $fields['values'] = json_decode($fields['values'],true);   
            $fields['data-id'] = $fields['id'];
            $fdata[] = $fields;
        }

        $data['category_id'] = $category_id;   
        $data['fields_data'] = $fdata; 
        // print_r($fdata);die();
        
        echo json_encode($fdata);  

    }

    public function get_subcategories()
    {
        $data = array();
        $data_subcategory_array = array();
        $category_id = $this->input->post('category_id');
        if (!empty($category_id)) {
            $data_subcategory_array = $this->items_model->get_item_subcategory_list($category_id);
        }
        $option_data = '';
        foreach ($data_subcategory_array as $value) {
        $title = json_decode($value['title']);
        $option_data.= '<option value="'.$value['id'].'">'.@$title->english.'</option>';
        } 
        $msg = (isset($option_data) && !empty($option_data) ? 'success' : 'error');
        echo json_encode(array('msg' => $msg , 'data' => $option_data));  
    }

    public function get_makes_options()
    {
        $data = array();
        $data_makes_array = $this->items_model->get_makes_list_active();
        $option_data = '';
        $option_data = '<option value="">Select Make</option>';
        foreach ($data_makes_array as $value) {
            $title = json_decode($value['title']);
            $option_data.= '<option value="'.$value['id'].'">'.$title->english.'</option>';
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
        foreach ($data_model_array as $value) {
            $title = json_decode($value['title']);
        $option_data.= '<option value="'.$value['id'].'">'.$title->english.'</option>';
        } 
        $msg = (isset($option_data) && !empty($option_data) ? 'success' : 'error');
        echo json_encode(array('msg' => $msg , 'data' => $option_data));  
    }

    public function categories()
    {
        $this->output->enable_profiler(TRUE);
        $data = array();
        $data['small_title'] = 'Items Categories List';
        $data['current_page_category'] = 'current-page';
        $data['items_category_list']    = $this->items_model->get_item_category_list();
        $this->template->load_admin('categories/categories_list', $data);
    }

    public function subcategories()
    {
        $data = array();
        $category_id = $this->uri->segment(3);
        $category = $this->db->select('title')->get_where('item_category', ['id' => $category_id])->row_array();
        $cat_title = json_decode($category['title']);
        $data['small_title'] = ucwords($cat_title->english).' Sub Categories List';
        $data['current_page_category'] = 'current-page';
        $data['items_subcategory_list'] = $this->items_model->get_item_subcategory_list($category_id);
        $this->template->load_admin('categories/subcategories_list', $data);
    }

    public function categories_sort()
    {
        $cat_id = $this->uri->segment(3);
        $data['cat_data'] = $this->db->get_where('item_category_fields',['category_id' => $cat_id])->result_array();
        $data['sort_data'] = $this->db->get_where('sort_catagories',['category_id' => $cat_id])->result_array();
        $this->template->load_admin('categories/categories_sort', $data);
    }
    public function categories_sort_save()
    {
        $sort_data = $this->input->post();
        $category_id = $sort_data['cat_id'];
        unset($sort_data['cat_id']);
        $has_sort_data = $this->db->get_where('sort_catagories',['category_id' => $category_id]);

        if($has_sort_data->num_rows() > 0) {

            $this->db->update('sort_catagories' , ['field_id' => $this->input->post('1')] ,['category_id' => $category_id , 'sort_id' => 1]);
            $this->db->update('sort_catagories' , ['field_id' => $this->input->post('2')] ,['category_id' => $category_id , 'sort_id' => 2]);
            $this->db->update('sort_catagories' , ['field_id' => $this->input->post('3')] ,['category_id' => $category_id , 'sort_id' => 3]);
            $this->db->update('sort_catagories' , ['field_id' => $this->input->post('4')] ,['category_id' => $category_id , 'sort_id' => 4]);  
            $this->session->set_flashdata('msg', 'Sorting successfully updated.'); 
        }else{

            foreach ($sort_data as $sort_id => $field_id) {
                $data = [
                    'sort_id' => $sort_id,
                    'field_id' => $field_id,
                    'category_id' => $category_id
                ];

                $insert_sort_data = $this->db->insert('sort_catagories',$data);
            }
        }
        if ($insert_sort_data) {
            $this->session->set_flashdata('msg', 'Sorting successfully applied.');
        }else{
            $this->session->set_flashdata('error', 'Sorting not applied.');
        }
        redirect(base_url('items/categories_sort/'. $category_id));
    }
    public function web_status()
    {
        $posted_data = $this->input->post();
        if ($posted_data) {
            $result = $this->db->update('item_category',$posted_data,['id'=>$posted_data['id']],['show_web'=>$posted_data['show_web']]);
            $output=json_encode([
                'status' => $result
            ]);
            echo json_encode($output);
            exit();
        }
    }

    // Add a new Sub Category
    public function save_subcategory()
    {
        $data = array();
        $data['small_title'] = 'Add';
        $data['current_page_category'] = 'current-page';
        $data['category_id'] = $this->uri->segment(3);
        $data['formaction_path'] = 'save_subcategory';
        $post = $this->input->post();
        if ($post) {
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'category_id',
                    'label' => 'Category',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'title_arabic',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $posted_data = array(
                'category_id' => $this->input->post('category_id'),
                // 'title' => $this->input->post('title'),
                'status' => $this->input->post('status'),
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->loginUser->id
                );
                if($this->input->post('description'))
                {
                    $posted_data['description'] = $this->input->post('description');
                }
                $title = [
                    'english' => $post['title'],
                    'arabic' => $post['title_arabic'],
                ];
                unset($post['title']); 
                unset($post['title_arabic']);
                $posted_data['title'] = json_encode($title);

                $result = $this->items_model->insert_subcategory($posted_data);
                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Sub Category Added Successfully');
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
            $this->template->load_admin('categories/subcategory_form', $data);
        }

    }
    
     // Update a Category
    public function update_subcategory()
    {
        $data = array();
        $data['formaction_path'] = 'update_subcategory';
        $data['current_page_category'] = 'current-page';
        $subcategory_id = $this->uri->segment(3);
        $data['category_id'] = $this->uri->segment(4);
        $category = $this->db->select('title')->get_where('item_subcategories', ['id' => $subcategory_id])->row_array();
        $cat_title = json_decode($category['title']);
        $data['small_title'] = 'Update '.ucwords(@$cat_title->english);
        $data['subcategory_info'] = $this->items_model->get_item_subcategory_row($subcategory_id);
        $post = $this->input->post();
        if ($post) {
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $id = $this->input->post('id');
                $posted_data = array(
                'category_id' => $this->input->post('category_id'),
                'title' => $this->input->post('title'),
                'status' => $this->input->post('status'),
                'updated_by' => $this->loginUser->id
                );
                if($this->input->post('description'))
                {
                    $posted_data['description'] = $this->input->post('description');
                }
                $title = [
                    'english' => $post['title'],
                    'arabic' => $post['title_arabic'],
                ];
                unset($post['title']); 
                unset($post['title_arabic']);
                $posted_data['title'] = json_encode($title);
                    // print_r($posted_data);
                $result = $this->items_model->update_subcategory($id,$posted_data);
                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Sub Category Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }else{

            $this->template->load_admin('categories/subcategory_form', $data);
        }

    }
   


    public function show_item_by_category_for_bid()
    {
        $data = array();
        $data['small_title'] = 'Category Items List';
        $category_id = $this->uri->segment(3);
        $data['items_list'] = $this->items_model->get_item_by_categoryid($category_id);
        $this->template->load_admin('items/category_items_list_for_bidding', $data);
    }
    // public function get_item_detail()
    // {
    //     $data = array();
    //     $data['item_info'] = $this->input->post('id');
    //     $data['item_detail'] = $this->items_model->get_item_byid($data['item_info']);
    //     $get_deposite_total = $this->users_model->get_deposit_detail_by_userid($data['item_info']);
    //     echo $data['item_detail'][0]['price']*10; die;
    //     $data_view = $this->load->view('items/item_detail_for_bid', $data, true);
    //     echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    // }

    public function category_fields($cat_id)
    {
        if($cat_id!=""){
            $datafields = $this->items_model->fields_data($cat_id);
            $fdata = array();
            foreach ($datafields as $fields) {
                $fields['values'] = json_decode($fields['values'],true);   
                $fdata[] = $fields;
            }
        $data['category_id'] = $cat_id;   
        $data['current_page_category'] = 'current-page';
        $data['fields_data'] = $fdata;
        //print_r($data['fields_data']);die();
        $this->template->load_admin('items/form_build', $data);    
        }
        
    }

    public function save_form()
    {
        $data = $_REQUEST['formData'];
        //return $data;
        $cat_id = $_REQUEST['category_id'];
        // get table fields
        //$col_names =  $this->get_column_names("item_category_fields");
        foreach ($data as $row) {
            if(($row['type']) == 'checkbox-group')
            {
                $row['values'] = json_encode($row['values']);
                //$dynamic_values['multiple'] = 'true';
            }
             if(($row['type']) == 'autocomplete')
            {
                $row['values'] = json_encode($row['values']);    
            }
            if(($row['type']) == 'radio-group')
            {
                $row['values'] = json_encode($row['values']);    
            }
            if(($row['type']) == 'select')
            {
                $row['values'] = json_encode($row['values']);    
            }

            if(isset($row['multiple'])){
                $row['multiple'] = 'true';
            }else{
                $row['multiple'] = 'false';
            }

            if(isset($row['required'])){
                $row['required'] = 'true';
            }else{
                $row['required'] = 'false';
            }



             $row['category_id'] = $cat_id;   
             $row['created_by'] = $this->loginUser->id;
             $row['updated_by'] = $this->loginUser->id;
             // print_r($data);
             // exit('asdasd');
             $result=$this->db->insert("item_category_fields",$row);
             $this->session->set_flashdata('msg', 'Item Category Fields Added Successfully');

        }
    }
    public function update_form()
    {
        $form_dynamic_data = $this->input->post();
        // return print_r($form_dynamic_data);
        $cat_id = $this->input->get('category_id');
        $all_fields_array = $this->items_model->fields_ids_data($cat_id);
        foreach ($all_fields_array as $value) {
            $all_ids[] = $value['id'];
        }
        // echo '<pre>';
        // print_r($form_dynamic_data);die();
        if (empty($form_dynamic_data['formData'])) {
            $unwanted_ids = $all_ids;
            if(isset($unwanted_ids) && !empty($unwanted_ids))
            {
                $item_fields_result = $this->items_model->delete_item_category_fields_rows($unwanted_ids);
            }

        } else {

            foreach ($form_dynamic_data['formData'] as $dynamic_keys => $dynamic_values) {
                


                if(($dynamic_values['type']) == 'checkbox-group')  // values change in to json
                {
                    $dynamic_values['values'] = json_encode($dynamic_values['values']);    
                    $dynamic_values['multiple'] = 'true';
                }

                if(($dynamic_values['type']) == 'radio-group')
                {
                    $dynamic_values['values'] = json_encode($dynamic_values['values']);    
                }

                if(($dynamic_values['type']) == 'select')
                {
                    $dynamic_values['values'] = json_encode($dynamic_values['values']);    
                }

                if(isset($dynamic_values['multiple'])){
                    $dynamic_values['multiple'] = 'true';
                }else{
                    $dynamic_values['multiple'] = 'false';
                }

                if(isset($dynamic_values['required'])){
                    $dynamic_values['required'] = 'true';
                }else{
                    $dynamic_values['required'] = 'false';
                }

                if (strpos($dynamic_values['name'], '-') !== false) {

                    $ids_arr = explode("-", $dynamic_values['name']);

                    $dynamic_values['name'] = $ids_arr[1];

                    $dynaic_information = array(
                    'field_id' => $ids_arr[0],
                    'value' => $dynamic_values,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->loginUser->id
                    );

                    $new_ids_array[] = $ids_arr[0] ;

                    $result_info = $this->items_model->update_fields_data($ids_arr[0],$dynamic_values);
                }
                else
                {
                    // echo $dynamic_values['name'];
                    $dynamic_values['category_id'] = $cat_id;   
                    $dynamic_values['created_on'] = date('Y-m-d H:i:s'); 
                    $dynamic_values['created_by'] = $this->loginUser->id; 
                     // print_r($data);
                     // exit('asdasd');
                    $result_info = $this->db->insert("item_category_fields",$dynamic_values);

                }
            }
        }
        $unwanted_ids = array_diff($all_ids,$new_ids_array);
        if(isset($unwanted_ids) && !empty($unwanted_ids))
        {
        $item_fields_result = $this->items_model->delete_item_category_fields_rows($unwanted_ids);
        $item_fields_data_result = $this->items_model->delete_item_fields_data_rows($unwanted_ids);    
        }    
        // print_r($unwanted_ids);die();
        // exit('asdasd');
        $this->session->set_flashdata('msg', 'Item Category Fields Updated Successfully');
     
 }
    // Add a new Category
    public function save_category()
    {
        $data = array();
        $data['small_title'] = 'Add';
        $data['current_page_category'] = 'current-page';
        $data['formaction_path'] = 'save_category';
        if ($this->input->post()) {
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
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'sort_order',
                    'label' => 'Sort Order',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $posted_data = array(
                // 'title' => $this->input->post('title'),
                'status' => $this->input->post('status'),
                'include_make_model' => $this->input->post('include_make_model'),
                // 'auction_fee' => $this->input->post('auction_fee'),
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->loginUser->id
                );
                $title = [
                    'english' => $this->input->post('title_english'),
                    'arabic' => $this->input->post('title_arabic'),
                ];
                $posted_data['title'] = json_encode($title);
                $posted_data['sort_order'] = $this->input->post('sort_order');
                $posted_data['show_web'] = $this->input->post('show_web');
                if( !empty($_FILES))
                    {
                        // make path
                        $path = './uploads/category_icon/';
                        if ( ! is_dir($path)) 
                        {
                            mkdir($path, 0777, TRUE);
                        }
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'ico|png|jpg|jpeg';
                        // print_r($config);die();
                        if (isset($_FILES['category_icon']['name']) && !empty($_FILES['category_icon']['name'])) {
                            $uploaded_file_ids = $this->files_model->upload('category_icon', $config);// upload and save to database
                            $posted_data['category_icon'] = implode(',', $uploaded_file_ids);
                        }
                        if (isset($_FILES['category_hover_icon']['name']) && !empty($_FILES['category_hover_icon']['name'])) {
                            $uploaded_icon_ids = $this->files_model->upload('category_hover_icon', $config); // upload and save to database
                            $posted_data['category_hover_icon'] = implode(',', $uploaded_icon_ids);
                        }

                    }

                // if($this->input->post('deposit_security'))
                // {
                //     $posted_data['deposit_security'] = $this->input->post('deposit_security');
                // }
                $result = $this->items_model->insert_category($posted_data);
                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Category Added Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }else{


       $this->template->load_admin('categories/category_form', $data);
        }

    }
    
   
    // Update a Category
    public function update_category()
    {
        
        $data = array();
        $data['small_title'] = 'Update';
        $data['current_page_category'] = 'current-page';
        $data['formaction_path'] = 'update_category';
        $category_id = $this->uri->segment(3);
        $data['category_info'] = $this->db->get_where('item_category', ['id' => $category_id])->row_array();
        if ($this->input->post()) {
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
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $id = $this->input->post('id');

                $posted_data = array(
                'status' => $this->input->post('status'),
                'include_make_model' => $this->input->post('include_make_model'),
                'updated_by' => $this->loginUser->id
                );
                $title = [
                    'english' => $this->input->post('title_english'),
                    'arabic' => $this->input->post('title_arabic'),
                ];
                $posted_data['title'] = json_encode($title);
                $posted_data['sort_order'] = $this->input->post('sort_order');
                $posted_data['slug'] = $this->input->post('slug');
                $posted_data['show_web'] = $this->input->post('show_web');

                if( !empty($_FILES))
                {
                    // make path
                    $path = './uploads/category_icon/';
                    if ( ! is_dir($path)) 
                    {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['max_size'] = 2000;
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    // print_r($config);die();
                    if (isset($_FILES['category_icon']['name']) && !empty($_FILES['category_icon']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('category_icon', $config); // upload and save to database
                        $posted_data['category_icon'] = implode(',', $uploaded_file_ids);
                    }
                    if (isset($_FILES['category_hover_icon']['name']) && !empty($_FILES['category_hover_icon']['name'])) {
                        $uploaded_icon_ids = $this->files_model->upload('category_hover_icon', $config); // upload and save to database
                        $posted_data['category_hover_icon'] = implode(',', $uploaded_icon_ids);
                    }

                }
                
                // print_r($posted_data);die();
                $result = $this->items_model->update_category($id,$posted_data);
                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Category Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }else{

            $this->template->load_admin('categories/category_form', $data);
        }

    }
    
    //Delete Single Row
    public function delete( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "item_expencses") {
            $res = $this->db->delete($table, ['id' => $id]);
        }

        if ($table == "item_category") {

            $res = $this->items_model->delete_item_category_row($id);
        }

        if ($table == "item_makes") {

            $res = $this->items_model->delete_item_model_row_by_make($id);
            $res = $this->items_model->delete_item_make_row($id);
        }

        if ($table == "item_models") {

            $res = $this->items_model->delete_item_model_row($id);
        }

        if ($table == "item_subcategories") {
            
            $res = $this->items_model->delete_item_subcategory_row($id);
        }

        if ($table == "item") {

            $file_path = FCPATH . 'uploads/items_documents/'.$id.'/';
            $QR_path = "uploads/items_documents/".$id."/qrcode/";
            $item_row_array = $this->items_model->get_item_byid($id);

            $item_images_ids_str = $item_row_array[0]['item_images'];
            $item_documents_ids_str = $item_row_array[0]['item_attachments'];

            $images_ids_arr = explode(",",$item_images_ids_str);
            $documents_ids_arr = explode(",",$item_documents_ids_str); 

            foreach ($images_ids_arr as $file_id1) 
            {
                $this->files_model->delete_by_id($file_id1,$file_path); // remove all images 
            }

            foreach ($documents_ids_arr as $file_id2) 
            {
                $this->files_model->delete_by_id($file_id2,$file_path);  // remove all documents 
            }
            if(file_exists($QR_path.$item_row_array[0]['barcode'])){
                unlink($QR_path.$item_row_array[0]['barcode']);
            }

            $remove_item_fields_data = $this->items_model->delete_item_fields_data_by_item($id);

            $res = $this->items_model->delete_item_row($id);

        }

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

    //Delete Single Row
    public function delete_bulk( $ids_array = array() )
    {
        $ids_array = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "item_category") {
             $ids_array = explode(",",$ids_array);
            foreach ($ids_array as $id) 
            {
            $res1 = $this->items_model->delete_item_subcategory_row_by_category($id);
            // $res2 = $this->items_model->delete_item_fields_by_category($ids_array);
            $res = $this->items_model->delete_item_category_row($id);
            }
        }

        if ($table == "item_subcategories") {
            $res = $this->items_model->delete_item_subcategory_MultipleRow($ids_array);
        }

        if ($table == "item_makes") {
            $ids_array = explode(",",$ids_array);
            foreach ($ids_array as $id) 
            {
            $res1 = $this->items_model->delete_item_model_row_by_make($id);
            $res = $this->items_model->delete_item_make_row($id);
            }
        }

        if ($table == "item_models") {
            $res = $this->items_model->delete_item_model_MultipleRow($ids_array);
        }

        if ($table == "item") {
            $ids_array = explode(",",$ids_array);
            foreach ($ids_array as $id) 
            {
                // echo $id;

                $file_path = FCPATH . 'uploads/items_documents/'.$id.'/';
                $QR_path = "uploads/items_documents/".$id."/qrcode/";
                $item_row_array = $this->items_model->get_item_byid($id);
                $item_images_ids_str = $item_row_array[0]['item_images'];
                $item_documents_ids_str = $item_row_array[0]['item_attachments'];

                $images_ids_arr = explode(",",$item_images_ids_str);
                $documents_ids_arr = explode(",",$item_documents_ids_str); 

                foreach ($images_ids_arr as $file_id1) 
                {
                    $this->files_model->delete_by_id($file_id1,$file_path); // remove all images 
                }

                foreach ($documents_ids_arr as $file_id2) 
                {
                    $this->files_model->delete_by_id($file_id2,$file_path);  // remove all documents 
                }
                
                unlink($QR_path.$item_row_array[0]['barcode']);

                $remove_item_fields_data = $this->items_model->delete_item_fields_data_by_item($id);

                $res = $this->items_model->delete_item_row($id);
            }
           

        }

        // exit;

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
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

}
