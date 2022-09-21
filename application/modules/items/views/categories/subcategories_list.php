
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
        <a type="button" href="<?php echo base_url().'items/categories'; ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back categories List</a>
    <div> 
        <br>
        <?php
        if($this->session->userdata('logged_in')->role == 1){
        ?>
          <div> 
            <a type="button" href="<?php echo base_url().'items/save_subcategory/'.$this->uri->segment(3); ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
            <div class="clearfix"></div>
          </div>
        <?php
        }
        ?>
      <div class="x_content">
        <br>
        <hr>
        <button onclick="deleteRecord_Bulk(this)" style="display: none;" type="button" data-obj="item_subcategories" id="delete_bulk" data-url="<?php echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>

      </div>

      <div class="x_content">
       <?php if(!empty($items_subcategory_list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <!-- <input type="checkbox" id="check-all" class="flat"> -->
                        <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
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
                foreach($items_subcategory_list as $value){
                    $j++;
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                         <input type="checkbox" class="flat multiple_rows ozproceed_check" id="<?php echo  $value['id']; ?>" name="table_records"  value="<?php echo  $value['id']; ?>">
                        </td>
                        <?php $title = json_decode($value['title']); ?>
                        <td><?php echo $j ?></td>
                        <td><?php echo ucfirst($title->english); ?></td>
                        <td><?php echo ucfirst($value['status']); ?></td>
                        <td>
                            <?php
                            if($this->session->userdata('logged_in')->role == 1){
                            ?>
                              <a href="<?php echo base_url().'items/update_subcategory/'.$value['id'].'/'.$this->uri->segment(3); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                              <button onclick="deleteRecord(this)" type="button" data-obj="item_subcategories" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>items/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
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