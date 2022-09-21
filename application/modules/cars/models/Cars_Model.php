<?php

//error_reporting(0);
class Cars_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function create($formArray)
    {
        $this->db->insert('valuation_make', $formArray);
    }
    public function makes_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_make');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function active_makes_list()
    {
        $this->db->select('*');
        $this->db->from('valuation_make');
            $this->db->where('status', '1');
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function model_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_model');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        // $this->db->where('created_by', $this->session->userdata('logged_in')->id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function update_makes($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('valuation_make', $data);
        return $query;
    }
    public function getuser($id)
    {
        $this->db->where('id', $id);
        return $user = $this->db->get('valuation_make')->row_array();

    }
    public function updateuser($id, $formArray)
    {

        $this->db->where('id', $id);
        $this->db->update('valuation_make', $formArray);
    }


    public function save_engine_size($data)
    {
        $this->db->insert('valuation_enginesize', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
    public function get_engineBymodel($model_id)
    {
        // select valuation_engine_size_id
        $query = $this->db->query("select id,valuation_engine_size_id from valuation_model where id='" .
            $model_id . "'");
        $res = $query->row_array();
        // print_r($res);die('aaaaaaaaaa');
        if (isset($res['id'])) {
            if ($res['valuation_engine_size_id'] != "") {
                //echo 'SELECT * FROM valuation_enginesize where id in ('.$res['valuation_engine_size_id'].')'
                $query = $this->db->query('SELECT * FROM valuation_enginesize where id in ('.$res['valuation_engine_size_id'].')');
                if ($query->num_rows() > 0) {
                    return $query->result_array();
                }

            }
        }
        return array();

    }


    public function eng_size_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_enginesize');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
    public function get_valuate_Option($id = 0)
    {

        $this->db->select('*');
        $this->db->from('valuate_cars_options');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }

    public function valuation_config($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_config_setting');
        $this->db->where('created_by', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function valuation_year($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_years');
        $this->db->where('created_by', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    } 
    public function valuation_year_luxury($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_years');
        $this->db->where('created_by', $id);
        // $this->db->where('category',1);
        $this->db->order_by('year_from', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    } 

    public function valuation_year_economy($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_years');
        $this->db->where('created_by', $id);
        // $this->db->where('category',2);
        $this->db->order_by('year_from', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function get_options()
    {
        $this->db->select('*');
        $this->db->from('option_');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
    public function valuation_milage($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_millage');
        $this->db->where('created_by', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function insert_valuation_years($data)
    {
        $this->db->insert("valuation_years", $data);
        return $inserted_id = $this->db->insert_id();
    }
    public function insert_valuation_millage($data)
    {
        $this->db->insert("valuation_millage", $data);
        return $inserted_id = $this->db->insert_id();
    }


    public function update_valuation_millage($id, $data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('valuation_millage', $data);
        return $return;

    }

    public function insert_valuation_config_setting($data)
    {
        $this->db->insert("valuation_config_setting", $data);
        return $inserted_id = $this->db->insert_id();
    }

    public function delete_valuation_years_byuserId($id)
    {
        $this->db->where('created_by', $id);
        $this->db->delete("valuation_years");
    }
    public function delete_valuation_millage_byuserId($id)
    {
        $this->db->where('created_by', $id);
        $this->db->delete("valuation_millage");
    }
    public function delete_valuation_config_setting_byuserId($id)
    {
        $this->db->where('created_by', $id);
        $this->db->delete("valuation_config_setting");
    }


    public function update_engine_size($id, $data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('valuation_enginesize', $data);
        return $return;

    }

    public function save_makes($data)
    {
        $this->db->insert('valuation_make', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }

    public function checkRecordDuplication($table, $colunm, $value = null, $byId = null)
    {

        $this->db->where($colunm, $value);
        if ($byId != "") {
            $this->db->where("id", $byId);
        }
        $query = $this->db->get($table);

        $count_row = $query->num_rows();

        if ($count_row > 0) {
            return true;
        } else {
            return false;
        }

    }
    public function models_list_Bymake($make_id)
    {
        $this->db->select('id,title');
        $this->db->from('valuation_model');
        $this->db->where('valuation_make_id', $make_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
    public function models_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_model');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }

    public function save_models($data)
    {
        $this->db->insert('valuation_model', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    public function get_existed_model($title, $valuation_make_id)
    {
        $query = $this->db->get_where('valuation_model', ['title' => $title, 'valuation_make_id' => $valuation_make_id]);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array();
    }
    public function update_models($id, $data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('valuation_model', $data);
        return $return;

    }
    public function price_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_price');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
    public function price_list_bymake($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_price');
        if ($id != 0) {
            $this->db->where('valuation_make_id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
    public function get_makes_data($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_model');
        $this->db->where('valuation_make_id', $id);
        $this->db->where('status', '1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
    public function save_prices($data)
    {
        $this->db->set($data);
        $this->db->insert('valuation_price', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
    public function makes_title($id)
    {
        $this->db->select('title');
        $this->db->from('valuation_make');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array()['title'];
        }
    }
    public function models_title($id)
    {
        $this->db->select('title');
        $this->db->from('valuation_model');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array()['title'];

    }
    public function engine_size_title($id)
    {
        $this->db->select('title');
        $this->db->from('valuation_enginesize');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array()['title'];

    }
    public function engine_size_title_comma($id)
    {

        $query = $this->db->query('SELECT GROUP_CONCAT(title) as title FROM `valuation_enginesize` WHERE id in (' .
            $id . ')');

        return $query->row_array()['title'];

    }

    public function update_price($id, $data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('valuation_price', $data);
        return $return;

    }

    /// save simple form data

    public function saveSimpleData()
    {
        $data = $this->input->post();

        if (!isset($data['table_name'])) {

            $this->session->set_flashdata('msg',
                'Please set table name in which you want save data.<br> <strong>Note: </strong> you just need to add this code 
             <blockquote><code><pre><input type="hidden" value="set table name here" name="table_name"></pre></code></blockquote>
            in your form');
            if (isset($_SERVER['HTTP_REFERER'])) {
                //$redirect_to = str_replace(base_url(), '', $_SERVER['HTTP_REFERER']);
                redirect($_SERVER['HTTP_REFERER']);
            }


        }
        $table = $data['table_name'];


        // data for save
        if (!isset($data['save'])) {

            $this->session->set_flashdata('msg',
                '<strong>Note: </strong> you just need to set fields formate that you need to save in database like this 
             <code><input type="text"  name="save[field name]"></code>
            in your form');
            if (isset($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER']);
            }


        }
        $save_data = $data['save'];
        $validation_fields = $data['validation'];
        foreach ($validation_fields as $valid_fields => $value) {
            $return_res = $this->validation_form($valid_fields, $value);
            print_r($return_res);
            if ($return_res != "true") {
                $this->session->set_flashdata($table, $save_data);
                $this->session->set_flashdata("valid_error", $return_res);
                //redirect($_SERVER['HTTP_REFERER']);
            }
        }
        die();

        // check validation

        /*$empty_message = "";
        foreach ($save_data as $key => $val) {
        if (strpos($key, '_required') !== false) {
        if ($val == "") {
        $empty_message .= $key . " is required field<br>";
        }

        $newkey = str_replace('_required', "", $key);
        unset($save_data[$key]);
        $save_data[$newkey] = $val;

        }

        }

        if ($empty_message != "") {
        $this->session->set_flashdata('msg', $empty_message);
        $this->session->set_flashdata($table, $save_data);
        if (isset($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER']);
        }
        }*/


        //check duplicate record
        if (isset($data['duplicate'])) {
            $colunmDuplicate = $data['duplicate'];
            $valueDuplicate = $save_data[$colunmDuplicate];
            if ($this->cars_model->checkRecordDuplication($table, $colunmDuplicate, $valueDuplicate)) {


                $this->session->set_flashdata('msg', 'This ' . $colunmDuplicate .
                    ' value already exists please try with another value!!');

                if (isset($_SERVER['HTTP_REFERER'])) {
                    redirect($_SERVER['HTTP_REFERER']);
                }


            }
        }

        // save data to database
        $this->db->insert($table, $save_data);
        if ($this->db->insert_id() != "") {
            $this->session->set_flashdata('msg', 'data has been saved');
            if (isset($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata('msg', 'there is some problem with data saving..');
            if (isset($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }


    }

    /// end simple data ///

    public function get_car($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_car');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
    public function eng_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_enginesize');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function detail_save($data)
    {
        $this->db->insert('vehicle_detail', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
    public function car_title($id)
    {
        $this->db->select('title');
        $this->db->from('valuation_car');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array()['title'];
        }
    }
    public function car_datail_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('vehicle_detail');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

    }
    public function save_car($data)
    {
        $this->db->insert('valuation_car', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }
    public function car_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_car');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function update_car($id, $data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('valuation_car', $data);
        return $return;
    }
    public function milleage_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('milleage');
        if ($id != 0) {
            
            $this->db->where('id', $id);
        }
        $this->db->order_by('milleage', 'asc');
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    } 
    public function milleage_list_from($id = 0)
    {
        $this->db->select('*');
        $this->db->from('milleage');
        if ($id != 0) {
            
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'asc');
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    } public function milleage_list_to($id = 0)
    {
        $this->db->select('*');
        $this->db->from('milleage');
        if ($id != 0) {
            
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function insert_milleage($data)
    {
        $this->db->insert('milleage', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }
    public function update_milleage($id,$data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('milleage', $data);
        return $return;
    }
    public function option_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuate_cars_options');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'aesc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function save_car_specs($data)
    {
        $this->db->insert('vehicle_specs', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;

    }


    public function valuation_millage_list($id = 0)
    {


        $this->db->select('*');
        $this->db->from('valuation_millage');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();

        
    }


    public function delete_valuation_millage_row($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_millage');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_millage');
            return true;
        } else {
            return false;
        }
    }
    public function location_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_location');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }


    public function insert_valuation_location($data)
    {
        $this->db->insert("valuation_location", $data);
        return $inserted_id = $this->db->insert_id();
    }


    public function update_valuation_location($id, $data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('valuation_location', $data);
        return $return;
    }

    public function dates_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('valuation_dates');
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    public function insert_dates($data)
    {

        // print_r($data);
        $this->db->insert("valuation_dates", $data);

        return $inserted_id = $this->db->insert_id();
    }
    public function get_location_name($id)
    {
        $this->db->select('name');
        $this->db->from('valuation_location');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array()['name'];

    }
    public function update_valuation_dates($id, $data)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('valuation_dates', $data);
        return $return;
    }


     public function get_millage_to_name($id)
    {
        $this->db->select('milleage');
        $this->db->from('milleage');
        $this->db->where('id',$id); 
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array()['milleage'];
        }
    }

     public function get_millage_from_name($id)
    {
        $this->db->select('milleage');
        $this->db->from('milleage');
        $this->db->where('id',$id); 
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array()['milleage'];
        }
    }
     public function delete_valuation_make_row($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_make');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_make');
            return true;
        } else {
            return false;
        }
    }
     public function delete_valuation_model_row_by_make($make_id)
    {
        $this->db->select('*');
        $this->db->from('valuation_model');
        $this->db->where('valuation_make_id', $make_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_make');
            return true;
        } else {
            return false;
        }
    }
     public function delete_valuation_model_row($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_model');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_model');
            return true;
        } else {
            return false;
        }
    } 
    public function delete_milleage_row($id)
    {
        $this->db->select('*');
        $this->db->from('milleage');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('milleage');
            return true;
        } else {
            return false;
        }
    } 
    public function delete_valuation_location_row($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_location');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_location');
            return true;
        } else {
            return false;
        }
    }
    public function delete_valuation_dates_row($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_dates');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_dates');
            return true;
        } else {
            return false;
        }
    }
    public function delete_engine_size_row($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_enginesize');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_enginesize');
            return true;
        } else {
            return false;
        }
    }
    public function delete_valuation_price_row($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_price');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_price');
            return true;
        } else {
            return false;
        }
    } 
    public function delete_valuation_price_rows($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_price');
        $this->db->where('engine_size_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('engine_size_id', $id);
            $this->db->delete('valuation_price');
            return true;
        } else {
            return false;
        }
    } 
    public function delete_valuation_millage_depriciation_row($id)
    {
        $this->db->select('*');
        $this->db->from('valuation_millage');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->delete('valuation_millage');
            return true;
        } else {
            return false;
        }
    }

}

    
