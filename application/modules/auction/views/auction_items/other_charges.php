<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div> 
          <a type="button" href="<?php echo base_url().'auction/liveitems/'.$this->uri->segment(4); ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back Auction List</a>
          <div class="clearfix"></div>
      </div>
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
      <div id="result"></div>
      
        <div class="ln_solid"></div>
       <div class="clearfix"></div> 
       

      <button type="button" data-toggle="modal" data-target=".bs-example-modal-sm2"  data-backdrop="static" data-keyboard="false" class="btn btn-success btn-sm oz_stock_list"><i class="fa fa-plus"></i> Add </button>
        
      <div class="x_content">
        <br>
        <hr>
         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="item_category" data-url="<?php echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>

      </div>
      
      <div class="x_content">
       <?php if(!empty($list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
                    </th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody class="content_items_inner">
                <?php 
                $j = 0;
                $role = $this->session->userdata('logged_in')->role; 
                $CI =& get_instance();
                $have_documents = false;
                foreach($list as $value){
                    $j++; ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox" class="flat multiple_rows ozproceed_check" id="<?php echo  $value['id']; ?>" name="table_records">
                        </td>
                        <td><?php echo $value['description']; ?></td>
                        <td><?php echo $value['amount']; ?></td>
                        <td>
                          <?php //if($role == 1){ ?>
                            <a href="javascript:void(0)" data-id="<?= $value['id']; ?>" class="btn btn-info btn-xs editbtn"><i class="fa fa-pencil"></i> Edit</a>
                          <?php //} ?>
                              
                          <?php if($role == 1){ ?>
                            <button onclick="deleteRecord(this)" type="button" data-obj="item_other_charges"  data-url="<?php echo base_url(); ?>auction/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                          <?php } ?>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
        <?php 
    }else{
        echo "<h3>Charges Not added yet!</h3>";
    }
    ?>
</div>
    </div>

       <div class="modal fade bs-example-modal-sm2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Add</h4>
            </div>

              <div id="result1"></div>
            <div id="DivIdToPrintBanner" class="modal-banner-body">

              <input type="hidden" id="hiddenid" name="id" disabled>
              <input type="hidden" id="auction_id" name="auction_id" value="<?php if(isset($auction_id)) { echo $auction_id['auction_id']; } ?>">
              <input type="hidden" id="item_id" name="item_id" value="<?php if(isset($auction_id)) { echo $auction_id['item_id']; } ?>">
              <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Title
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="description" name="item[description]"  class="form-control col-md-7 col-xs-12">
                </div>
              </div><br><br>

              <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Amount
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" id="amount" name="amount" class="form-control col-md-7 col-xs-12" oninput="this.value=this.value.replace(/[^0-9]/g,'');"6>
                </div>
              </div>
            </div><br><br>
            <div class="modal-footer">
            <button type="button" id="popupbtn" class="btn btn-info btn-xs"><i></i> Add</button>
            </div>
          </div>
        </div>
      </div>
<!-- 
       <div class="modal fade bs-example-modal-other-charges" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Other Charges</h4>
            </div>
            <div id="DivIdToPrintBanner" class="modal-banner-body">
              
            </div>
            <div class="modal-footer">
            <button type="button" id="<?php echo $value['id']; ?>" onclick="printBannerDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
        </div>
      </div> -->

<script type="text/javascript">
  $("#popupbtn").on('click', function(){
    var auction_id = $("#auction_id").val();
    var item_id = $("#item_id").val();
    var description = $("#description").val();
    var amount = $("#amount").val();
    var id = $("#hiddenid").val();
    console.log(auction_id);
    console.log(item_id);
    var url = "<?php echo base_url(); ?>";
    $.ajax({
        //AJAX type is "Post".
        type: "POST",
        //Data will be sent to "ajax.php".
        url: url + 'auction/add_other_details',
        //Data, that will be sent to "ajax.php".
        data: {id:id,auction_id:auction_id,item_id:item_id,description:description,amount:amount},
        beforeSend: function(){
          $('#popupbtn').html('<img src="'+url+'assets_admin/images/load.gif" align="center" />');
        },
        //If result found, this funtion will be called.
        success: function(html) {
          //Assigning result to "display" div in "search.php" file.
          var objData = jQuery.parseJSON(html);
          if(objData.msg == 'success'){
            location.reload();
          }else{
            $('.msg-alert').css('display', 'block');
            $("#result1").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            $('#popupbtn').html('Add');

          }
          // $('#add_items').hide(); 
        }
     });
  })

  $('.editbtn').on('click', function(){
    var id = $(this).attr("data-id");
    var url = "<?php echo base_url(); ?>";
    console.log(id);
    $.ajax({
        type: "POST",
        url: url + 'auction/get_other_details',
        data: {id:id},
        beforeSend: function(){
        },
        success: function(html) {
          var objData = jQuery.parseJSON(html);
          if(objData.msg == 'success'){
            $("#description").val(objData.data['description']);
            $("#amount").val(objData.data['amount']);
            $('#popupbtn').html('Update');
            $('#myModalLabel').html('Edit');
          }else{
            $('.msg-alert').css('display', 'block');
            $("#result1").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            $('#popupbtn').html('Add');

          }
          // $('#add_items').hide(); 
        }
     });
    $("#hiddenid").val(id);
    $("#hiddenid").removeAttr('disabled');
    $(".bs-example-modal-sm2").modal();

  });

  $('#close').on('click', function(){
    location.reload();
  });

    function add_auction_items(){

      var ids_list = $("#item_ids_list").val();
      var url = '<?php echo base_url();?>';
      // var auction_id = '<?php //echo $auction_id;?>';
      var auction_id = $("#auction_id").val();
      var category_id = $("#category_id").val();
      // console.log(ids_list);

        $.ajax({
               //AJAX type is "Post".
               type: "POST",
               //Data will be sent to "ajax.php".
               url: url + 'auction/add_auction_items',
               //Data, that will be sent to "ajax.php".
               data: {
                   //Assigning value of "name" into "search" variable.
                   ids_list: ids_list,auction_id:auction_id,category_id:category_id
               },
                beforeSend: function(){
                  $('#result_add_items').html('<img src="'+url+'assets_admin/images/load.gif" align="center" />');
                },
               //If result found, this funtion will be called.
               success: function(html) {
                   //Assigning result to "display" div in "search.php" file.
                  var objData = jQuery.parseJSON(html);
                   if(objData.msg == 'success'){
                  $("#result_add_items").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                   }
                   reload_item_list();
                   
                  $('#add_items').hide(); 
               }
           });
    }

</script>