<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php echo $small_title; ?>
            </h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content"></div>
          <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('msg');   ?>
          </div>
      </div>
    <?php }?>
    <div> 
    <div> 
     <a type="button" href="<?php echo base_url().'settings/save_charges?type=seller'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
        <div class="clearfix"></div>
    </div>

      <div class="x_content">
        <!-- <button onclick="deleteRecord_Bulk(this)" style="display: none;" type="button" data-obj="item_category" id="delete_bulk" data-url="<?php //echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button> -->
      </div>
    <div class="x_content">
       <?php if(!empty($commissions_info)){?>
           <table id="seller_charges" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Arabic Title</th>
                    <th>Commission (%) / Amount</th>
                    <th>Status</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
               
                $j = 0;
                foreach($commissions_info as $value){
                   $arabic = $value['title'];
                   $arabic_title = json_decode($arabic);
                    $j++;
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>"> 
                        <td><?php echo $j ?></td>
                        <td><?php echo $arabic_title->english; ?></td>
                        <td><?php echo $arabic_title->arabic; ?></td>
                        <td><?php echo $value['commission']; echo (isset($value['type']) && $value['type'] == 'percent') ? '%' : ''; ?></td>
                        <td><?php echo $value['status']; ?></td>
                        <td>
                         <a href="<?php echo base_url().'settings/update_charges/'.$value['id'].'?type=seller'; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
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
  $('#seller_charges').DataTable();
  // $('input:checkbox').on('ifChecked', function () 
  //   { 
  //      if ($("input:checkbox:checked").length > 0){
  //      $('#delete_bulk').show();
  //      }
  //   });
  //   $('input:checkbox').on('ifUnchecked', function ()
  //   {
  //       if ($("input:checkbox:checked").length <= 0){
  //        $('#delete_bulk').hide();
  //       }
  //   });  

</script>