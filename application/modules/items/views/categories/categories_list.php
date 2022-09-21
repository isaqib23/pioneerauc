
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><?php echo $small_title; ?></h2>

        <div class="clearfix"></div>
    </div>
    <?php 
        $result = $this->db->get('item_category')->result_array();
        foreach ($result as $key => $value) {
        $web[$key] =$value['show_web'];
        }
    ?>
    <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('msg');   ?>
          </div>
      </div>
  <?php }?>
  <div > 
    <div> 
        <?php
        if($this->session->userdata('logged_in')->role == 1){
        ?>
          <div> 
            <a type="button" href="<?php echo base_url().'items/save_category'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
            <div class="clearfix"></div>
          </div>
        <?php
        }
        ?>
      <div class="x_content">
        <br>
        <hr>
        <button onclick="deleteRecord_Bulk(this)" style="display: none;" type="button" data-obj="item_category" id="delete_bulk" data-url="<?php echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>
      </div>

      <div class="x_content">
       <?php if(!empty($items_category_list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <?php $role = $this->session->userdata('logged_in')->role;  ?>
                <tr>
                    <!-- <th>
                    <?php if($role == 1){ ?>
                    <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
                    <?php } ?>
                    </th> -->
                    <th>#</th>
                    <th>English Title</th>
                    <th>English Arabic</th>
                    <th>Status</th>
                    <th>Show On Web</th>
                    <th>Sort Order</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $j = 0;
                $CI =& get_instance();
                foreach($items_category_list as $value){
                    $title = json_decode($value['title']);
                    $j++;
                    $fields_array = $CI->items_model->fields_data($value['id']);
                    $items_query = $this->db->get_where('item', ['category_id' => $value['id']]);
                    $auctions_query = $this->db->get_where('auctions', ['category_id' => $value['id']]);
                    if(isset($fields_array) && !empty($fields_array))
                    {
                        $if_fields = true;
                        $fields_class = 'btn-success';
                    }
                    else
                    {
                        $if_fields = false;
                        $fields_class = 'btn-warning';
                    }
                    $subcategory_array = $CI->items_model->get_item_subcategory_list($value['id']);
                    if(isset($subcategory_array) && !empty($subcategory_array))
                    {
                        $subcategory_class = 'btn-success';
                    }
                    else
                    {
                        $subcategory_class = 'btn-warning';
                    }
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <!-- <td class="a-center ">
                        <?php if($role == 1 && $if_fields == false){  ?>
                        <input type="checkbox" class="flat multiple_rows ozproceed_check" id="<?php //echo  $value['id']; ?>" name="table_records"  value="<?php //echo  $value['id']; ?>"> 
                        <?php } ?>
                        </td> -->
                        <td><?php echo $j ?></td>
                        <td><?php echo $title->english; ?></td>
                        <td><?php echo $title->arabic; ?></td>
                        <td><?php echo ucfirst($value['status']); ?></td>
                        <td>
                            <input type="checkbox" 
                                class="web_class" 
                                value="checked" 
                                <?php if(isset($value) && !empty($value) && $value['show_web'] == 'yes'){ echo 'checked'; }?>
                                data-cid="<?php echo $value['id'];?>">
                            
                        </td>

                        <td><?php echo $value['sort_order']; ?></td>
                        <td>
                           
                              <a href="<?php echo base_url().'items/update_category/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                            <?php
                            if(($this->session->userdata('logged_in')->role == 1) && ($if_fields == false) && ($items_query->num_rows() < 1) && ($auctions_query->num_rows() < 1))
                            { ?>
                                <button onclick="deleteRecord(this)" type="button" data-obj="item_category" data-id="<?php echo $value['id']; ?>" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-url="<?php echo base_url(); ?>items/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                            <?php } ?>
                            <?php
                            if($this->session->userdata('logged_in')->role == 1)
                            {
                            ?>
                              <a href="<?php echo base_url().'items/category_fields/'.$value['id']; ?>" class="btn <?php echo $fields_class; ?> btn-xs"><i class="fa fa-pencil"></i> Items Category Form</a>

                              <a href="<?php echo base_url().'items/subcategories/'.$value['id']; ?>" class="btn <?php echo $subcategory_class; ?> btn-xs"><i class="fa fa-sitemap"></i> Items Sub Category</a>

                              <a href="<?php echo base_url().'items/categories_sort/'.$value['id']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Categories Sort Order</a>

                            <?php
                            }
                            ?>
                        </td>

                          

                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
        <?php 
    }else{
        echo "<h1>No Record Found</h1>";
    }
    ?>
</div>
</div>
</div>
</div>

<script type="text/javascript">
    
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';

    $('input:checkbox').on('ifChecked', function () 
    { 
       if ($("input:checkbox:checked").length > 0){
       $('#delete_bulk').show();
       }
    });
    $('input:checkbox').on('ifUnchecked', function ()
    {
        if ($("input:checkbox:checked").length <= 0){
         $('#delete_bulk').hide();
        }
    });  

    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
</script>


<script>
  $('.web_class').change(function () {
     // alert('changed');
        if ($(this).prop("checked")) { 
            var status = 'yes';
        } else { 
            var status = 'no';
        }
        var cid = $(this).data('cid');
        $.ajax({
                type: 'post',
                url: '<?= base_url('items/web_status'); ?>',
                data: {'id':cid, 'show_web':status, [token_name]:token_value,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
                success: function(msg){

                }
            });
    });
</script>