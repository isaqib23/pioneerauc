<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
       
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
        <!-- <div> 
          <a type="button" href="<?php echo base_url().'items/save_item'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
          <div class="clearfix"></div>
      </div> -->
        
      <!-- <div class="x_content">
        <br>
        <hr>
         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="item" data-url="<?php echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>
      </div> -->

      <div class="x_content">
       <?php if(!empty($items_list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <!-- <th>
                        <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
                    </th> -->
                    <th>#</th>
                    <th>Name</th>
                    <th>Current Price</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody class="content_items">
                <?php 
                $j = 0;
                foreach($items_list as $value){
                    $j++;
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <!-- <td class="a-center ">
                        <input type="checkbox" class="flat multiple_rows ozproceed_check" id="<?php echo  $value['id']; ?>" name="table_records">
                        </td> -->
                        <td><?php echo $j ?></td>
                        <td><?php echo $value['name']; ?></td>
                        <td><?php echo 'AED '.$value['price']; ?></td>
                        <td>
                            <!-- <a href="javaScript:void(0)" onclick="showBidModal(this)" class="btn btn-danger btn-xs">Bid Now</a> -->
                            <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-sm" class="btn btn-danger btn-xs sd_assignto_model">Bid Now</button>
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
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Bid Now</h4>
      </div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    function show_content(){
        show_items_content
    }

  var url = "<?php echo base_url(); ?>";
    // fill_datatable();
  function fill_datatable(filter_gender = '', filter_country = '')
  {
    var dataTable = $('#datatable-responsive').DataTable({
      "processing" : true,
      "serverSide" : true,
      "order" : [],
      "searching" : false,
      "ajax" : {
        url: url + "items/show_items_content",
        type:"POST",
        data:{
          filter_gender:filter_gender, filter_country:filter_country
        }
      }
    });
  }
  $('.sd_assignto_model').on('click',function(){
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
    $.ajax({
      url: url + 'items/get_item_detail',
      type: 'POST',
      data: {id:id},
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        console.log(objData);
        if (objData.msg == 'success') 
        {
          $('.modal-body').html(objData.data);
        }
    });
  });

</script>