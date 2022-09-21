<?php defined('BASEPATH') or exit('No direct script access allowed');
class Accounts extends Loggedin_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Accounts_model', 'accountd_model');
    }

    public function index()
    {
        $data = array();
        // $data['small_title'] = 'User Accounts';
        $data['current_page_auction'] = 'current-page';
        // $data['formaction_path'] = 'filter_auction_items';
        $this->template->load_admin('accounts/accounts_list');
    }

    
}
