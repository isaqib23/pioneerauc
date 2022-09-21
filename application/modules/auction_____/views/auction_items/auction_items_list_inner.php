<div class="col-md-10">
  <div class="message"></div>
</div>
  <!-- <div class="x_panel">
    <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
        <div class="form-group ">   
          <div class="col-md-6 col-sm-6 col-xs-12  col-md-offset-1">
           <select class="form-control col-md-7 col-xs-12" id="category_id" name="category_id" >
                  <option value="">Select Category</option>
                  <?php //foreach ($category_list as $category_value) { ?>
                  <option value="<?php //echo $category_value['id']; ?>"><?php //echo $category_value['title']; ?></option>
                  <?php  //} ?> 
            </select>
          </div>
        </div> 
        <input type="hidden" name="auction_id" value="<?php //echo $auction_id; ?>">
        <input type="hidden" name="category_id" id="category_id" value="<?php //echo $category_id; ?>">
        <div class="form-group">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">
            <button type="button" id="send" class="btn btn-success">Find Category Items</button>
        </div>
      </div>
    </form>
  </div> -->
   <div id="result_add_items"></div>

      <div class="x_panel search_block" style="">
        <?php 
        if(isset($item_ids_list) && !empty($item_ids_list))
        {
          $already_ids = '';
          // $already_ids = implode( ",", $item_ids_list);
        }
        else
        {
          $already_ids = '';
        }
         ?>
          <input type="hidden" name="item_ids_list" id="item_ids_list" value="<?php echo (isset($already_ids) && !empty($already_ids) ? $already_ids : ''); ?>">
        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
        <!--   <input type="text" id="search" name="search" class="form-control" placeholder="Search Items/Products By: keyword,Lot Id, Product Name"> -->

           <div class="item form-group">    
          <div class="col-md-6 col-sm-6 col-xs-12  col-md-offset-1">
          <input type="text" id="search" name="search" class="form-control" placeholder="Search By:Product Name, keyword,Lot Id,Registration# ">
          </div>

            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <select onchange="" class="form-control select2" id="days_filter_inner" name="days_filter">
                <option value="">Choose Days</option>
                <option value="today">Today</option>
                <option value="-1">Yesterday</option>
                <option value="-7">Last week</option>
                <option value="-15">Last 15 Days</option>
                <option value="-30">Last 30 Days</option>
              </select>
            </div>
        </div> 

        <input type="hidden" name="auction_id" id="auction_id" value="<?php echo $auction_id; ?>">
        <input type="hidden" name="category_id" id="category_id" value="<?php echo $category_id; ?>">
        </form>
      </div>   
      <div class="x_panel">

        <table id="datatable-responsive_3" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th> # </th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Registration #</th>
                    <!-- <th>Lot No</th> -->
                    <th>Keywords</th>
                    <th data-orderable="false">Rules</th>
                </tr>
            </thead>
            <tbody class="content_items">  
              <?php if(!empty($items_list)){
                $j = 0;
                $CI =& get_instance();
                foreach($items_list as $value){
                    $j++;
                    $itemid = $value['id'];
                    $item_category_ = json_decode($value['category_name'],true);
                    // $result_if_already = $CI->auction_model->auction_item_bidding_rule_list($auction_id,$itemid);
                    $result_if_already = $CI->auction_model->multi_auction_item_bidding_rule_list($auction_id,$itemid);
                    if(isset($result_if_already) && !empty($result_if_already))
                    {
                        $checked_ = 'checked';
                    }else{
                        $checked_ = '';
                    }
                    if(empty($checked_)){
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>" >
                        <td class="a-center ">
                        <input type="checkbox" <?php echo $checked_; ?> onchange="check_uncheck(this)" class="flat multiple_rows ozproceed_check child_items_<?php echo  $value['id']; ?>" id="<?php echo  $value['id']; ?>" name="table_record_items[]">
                        </td>
                        <?php $item_name = json_decode($value['name']); ?>
                        <td><?php if(isset($value['name'])){echo $item_name->english;} ?></td>
                        <td><?php echo (isset($item_category_['english']) && !empty($item_category_['english'])) ? $item_category_['english'] : '';?></td>
                        <td><?php echo $value['registration_no']; ?></td>
                        <!-- <td><?php //echo $value['lot_id']; ?></td> -->
                        <td><?php echo (isset($value['keyword']) && !empty($value['keyword'])) ? $value['keyword'] : ''; ?></td>
                        <td>
                        <?php if(isset($result_if_already) && !empty($result_if_already)){ ?>
                          <button type="button" id="<?php echo $value['id']; ?>" onclick="get_rules(<?php echo $auction_id.",".$itemid ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_bidding_model"><i class="fa fa-pencil-square-o"></i>Rules</button>
                        <?php }else{ echo 'Not Added Yet';} ?>
                        </td>
                    </tr>
                <?php }?>
                <?php }?>
            
            <?php 
            }else{
                echo '<tr  ><td colspan="8"><h3 class="text-center">No Record Found</h3></td></tr>';
            }
            ?>
            </tbody>
        </table>
        
      </div>
        

       <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width:750px; ">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close inner_bidding_model" title="close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Bidding Rules</h4>
            </div>
            <div class="modal-body_rules">
              
            </div>

          </div>
        </div>
      </div> 

<script type="text/javascript">
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

  $('#datefrom').datetimepicker({
      format: 'YYYY-MM-DD'
  });

  $('#dateto').datetimepicker({
      format: 'YYYY-MM-DD'
  });
   
  $("#auction_type").select2({
    placeholder: "Select Auction Type", 
    width: '200px',
  });
 
  $('.inner_bidding_model').on('click', function(){
  $(".bs-example-modal-sm").modal("hide");
  })
    
    var ids_list_auto = $('#item_ids_list').val(); 

    if(ids_list_auto != '')
    {
        var array_with_split = ids_list_auto.split(',');
        $.each(array_with_split, function( key, value ) {
        $("#datatable-responsive_3 input[name='table_record_items[]'] #" + value).prop('checked', true);
            
        });
    } 

    function reload_item_list(){

      var auction_id = '<?php echo $auction_id;?>';
      var category_id = '<?php echo $category_id;?>';
      var name = $('#search').val(); 

       // var days_filter = $('#days_filter').val(); 
       var days_filter = $('#days_filter_inner').val();
      // var category_id = $('#category_id').val(); 
      var formaction_path2 = '<?php echo $formaction_path2;?>'; 
                //AJAX is called.
           $.ajax({
               //AJAX type is "Post".
               type: "POST",
               //Data will be sent to "ajax.php".
               url: url + 'auction/' + formaction_path2,
               //Data, that will be sent to "ajax.php".
               data: {
                   //Assigning value of "name" into "search" variable.
                   search: name,
                   auction_id:auction_id,
                   category_id:category_id,
                   days_filter:days_filter, 
                   [token_name]:token_value
               },
                beforeSend: function(){
                  $('.content_items').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
                },
               //If result found, this funtion will be called.
               success: function(html) {
                   //Assigning result to "display" div in "search.php" file.
                  var objData = jQuery.parseJSON(html);
                   // $("#display").html(html).show();
                  var ids_list_auto = $('#item_ids_list').val(); 
                  if(ids_list_auto != '')
                  { 
                    console.log(ids_list_auto);
                      var array_with_split = ids_list_auto.split(',');

                      $.each(array_with_split, function( key, value ) {
                        console.log(value);
                      // $(".child_items_" + value).attr('checked', true);
                      $("input.child_items_"+value+":checkbox").attr('checked',true);
                      $("input.child_items_"+value+":checkbox").prop('checked',true);
                          
                      });
                  }
                  $('.search_block').show();
                  $('.content_items').html(objData.data);
               }
           }); 

    }

      var CustomerIDArray=[<?php echo $already_ids; ?>];
    function check_uncheck(e){
      var len = $("#datatable-responsive_3 input[name='table_record_items[]']:checked").length;
      var ids_list_str = '';

      if(e.checked) {
      if (len > 0) { 
          var found = jQuery.inArray(e.id, CustomerIDArray);
          if(CustomerIDArray.length != 0)
          {
            if(jQuery.inArray(e.id, CustomerIDArray) !== -1){
            }
            else
            {
              CustomerIDArray.push(e.id);
            }
          }
          else
          {
          CustomerIDArray.push(e.id);
          }
          $('#add_items').show();
        } else {
          $('#add_items').hide(); 
        }

      }
      else
      {
        // console.log(e.id+' unchecked');
        if (len > 0) {
            // CustomerIDArray.slice(e.id);
            CustomerIDArray = jQuery.grep(CustomerIDArray, function (value) {
                return value != e.id;
            });
            $('#add_items').show();
        } 
        else
        {
            CustomerIDArray = jQuery.grep(CustomerIDArray, function (value) {
                return value != e.id;
            });
            $('#add_items').hide();
        }
        
      }
          ids_list_str = CustomerIDArray.join();
          $('#item_ids_list').val(ids_list_str);

    }

     function get_rules(auction_id,item_id){

      console.log('function run '+auction_id+' - '+item_id);

              var url = '<?php echo base_url();?>';
              var auction_id = auction_id;
              var id = item_id;
               $.ajax({
                url: url + 'auction/get_bidding_rules',
                type: 'POST',
                data: {id:id,auction_id:auction_id, [token_name]:token_value},
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    $('.modal-body_rules').html(objData.data);
                  }

          });
     }

   $('.oz_lotting_model').on('click',function(){
              console.log('inner clicked');
    
              var url = '<?php echo base_url();?>';
              var auction_id = '<?php echo $auction_id;?>';
              var id = $(this).attr("id");
               $.ajax({
                url: url + 'auction/get_bidding_rules',
                type: 'POST',
                data: {id:id,auction_id:auction_id, [token_name]:token_value},
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    $('.modal-body_lotting').html(objData.data);
                  }

          });
  });












   
   
   $('.oz_bidding_model').on('click',function(){
              console.log('inner clicked');
    
              var url = '<?php echo base_url();?>';
              var auction_id = '<?php echo $auction_id;?>';
              var id = $(this).attr("id");
               $.ajax({
                url: url + 'auction/get_bidding_rules',
                type: 'POST',
                data: {id:id,auction_id:auction_id,[token_name]:token_value},
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    $('.modal-body_rules').html(objData.data);
                  }

          });
  });

   
  var url = "<?php echo base_url(); ?>";
  var formaction_path = '<?php echo $formaction_path;?>'; 
  var formaction_path2 = '<?php echo $formaction_path2;?>'; 
  var auction_id = '<?php echo $auction_id;?>';
  var category_id = '<?php echo $category_id;?>';

   $("#send").on('click', function(e) { //e.preventDefault();
      var formData = new FormData($("#demo-form2")[0]);
        $.ajax({
          url: url + 'auction/' + formaction_path,
          type: 'POST',
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function(){
            $('.content_items').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
          }
          }).then(function(data) {
          var objData = jQuery.parseJSON(data);
          if (objData.msg == 'success') 
          { 
              $('.search_block').show();
              $('.content_items').html(objData.data);
           
          } 
          else 
          {
              $('.search_block').hide();
              $('.msg-alert').css('display', 'block');
              $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

                 window.setTimeout(function() {
              $(".alert").fadeTo(500, 0).slideUp(500, function(){
                  $(this).remove(); 
              });
          }, 3000);
          }
          });
            });    

//Getting value from "ajax.php".
function fill(Value) {
   //Assigning value to "search" div in "search.php" file.
   $('#search').val(Value);
   //Hiding "display" div in "search.php" file.
   // $('#display').hide(); 
}
// $(document).ready(function() {  
   $("#search").keyup(function() { 
       var name = $('#search').val(); 
       // var days_filter = $('#days_filter').val(); 
       var days_filter = $('#days_filter_inner').val(); 
       
       // if (name == "") {
       // }
       // else {
           //AJAX is called.
           $.ajax({
               //AJAX type is "Post".
               type: "POST",
               //Data will be sent to "ajax.php".
               url: url + 'auction/' + formaction_path2,
               //Data, that will be sent to "ajax.php".
               data: {
                   //Assigning value of "name" into "search" variable.
                   search: name,
                   auction_id:auction_id,
                   category_id:category_id,
                   days_filter:days_filter,
                   [token_name]:token_value
               },
                beforeSend: function(){
                  $('.content_items').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
                },
               //If result found, this funtion will be called.
               success: function(html) {
                   //Assigning result to "display" div in "search.php" file.
                  var objData = jQuery.parseJSON(html);
                   // $("#display").html(html).show();
                  var ids_list_auto = $('#item_ids_list').val(); 
                  if(ids_list_auto != '')
                  { 
                    console.log(ids_list_auto);
                      var array_with_split = ids_list_auto.split(',');

                      $.each(array_with_split, function( key, value ) {
                        console.log(value);
                      // $(".child_items_" + value).attr('checked', true);
                      $("input.child_items_"+value+":checkbox").attr('checked',true);
                      $("input.child_items_"+value+":checkbox").prop('checked',true);
                          
                      });
                  }
                  $('.search_block').show();
                  $('.content_items').html(objData.data);
               }
           });
       // }
   });


// });


       $('select#days_filter_inner').on('change', function() {
    // function days_filter_search(e) {
       var name = $('#search').val(); 
       // var category_id = $('#category_id').val(); 
       var category_id = '<?php echo $category_id;?>';
       var days_filter = this.value; 
  
           //AJAX is called.
           $.ajax({
               //AJAX type is "Post".
               type: "POST",
               //Data will be sent to "ajax.php".
               url: url + 'auction/' + formaction_path2,
               //Data, that will be sent to "ajax.php".
               data: {
                   //Assigning value of "name" into "search" variable.
                   search: name,
                   auction_id:auction_id,
                   category_id:category_id,
                   days_filter:days_filter,
                   [token_name]:token_value
               },
                beforeSend: function(){
                  $('.content_items').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
                },
               //If result found, this funtion will be called.
               success: function(html) {
                   //Assigning result to "display" div in "search.php" file.
                  var objData = jQuery.parseJSON(html);
                   // $("#display").html(html).show();
                  var ids_list_auto = $('#item_ids_list').val(); 
                  if(ids_list_auto != '')
                  { 
                    console.log(ids_list_auto);
                      var array_with_split = ids_list_auto.split(',');

                      $.each(array_with_split, function( key, value ) {
                        console.log(value);
                      // $(".child_items_" + value).attr('checked', true);
                      $("input.child_items_"+value+":checkbox").attr('checked',true);
                      $("input.child_items_"+value+":checkbox").prop('checked',true);
                          
                      });
                  }
                  $('.search_block').show();
                  $('.content_items').html(objData.data);
               }
           });
       
   });

</script>