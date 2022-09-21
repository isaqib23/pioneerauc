
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><?php echo $small_title; ?></h2>

        <div class="clearfix"></div>
    </div>
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
            <a type="button" href="<?php echo base_url().'items/save_makes'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
            <div class="clearfix"></div>
          </div>
        <?php
        }
        ?>
      <div class="x_content">
        <br>
        <hr>
        <button onclick="deleteRecord_Bulk(this)" style="display: none;" type="button" data-obj="item_makes" id="delete_bulk" data-url="<?php echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>
      </div>

      <div class="x_content">
       <?php if(!empty($makes_list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <?php $role = $this->session->userdata('logged_in')->role;  ?>
                <tr>
                    <th>
                    <?php if($role == 1){ ?>
                    <!-- <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records"> -->
                    <?php } ?>
                    </th>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $j = 0;
                $CI =& get_instance();
                foreach($makes_list as $value){
                    $j++;
                    $model_array = $CI->items_model->get_item_model_list($value['id']);
                    if(isset($model_array) && !empty($model_array))
                    {
                        $model_class = 'btn-success';
                    }
                    else
                    {
                        $model_class = 'btn-warning';
                    }
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <?php if($role == 1){ ?>
                        <!-- <input type="checkbox" class="flat multiple_rows ozproceed_check" id="<?php //echo  $value['id']; ?>" name="table_records"  value="<?php //echo  $value['id']; ?>"> -->
                        <?php } ?>
                        </td>
                        <td><?php echo $j ?></td>
                        <?php $title = json_decode($value['title']);
                        ?>
                        <td><?php echo ucfirst($title->$language); ?></td>
                        <td><?php echo ucfirst($value['status']); ?></td>
                        <td>
                           
                            <a href="<?php echo base_url().'items/update_makes/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                            <?php
                            if($this->session->userdata('logged_in')->role == 1)
                            {
                            ?>
                              <button onclick="deleteRecord(this)" type="button" data-obj="item_makes" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>items/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                            <?php
                            }
                            ?>
                             <?php
                            if($this->session->userdata('logged_in')->role == 1)
                            {
                            ?>
                              <a href="<?php echo base_url().'items/models/'.$value['id']; ?>" class="btn <?php echo $model_class; ?> btn-xs"><i class="fa fa-sitemap"></i> Manage Models</a>

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