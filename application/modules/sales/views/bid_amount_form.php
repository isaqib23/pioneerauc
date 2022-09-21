<script type="text/javascript">
  function demooz(){

  alert('dddddd');
  console.log('another');

}
</script>
 <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="row">

<div id="result" class="message"></div>
  <?php print_r($item_detail); ?>aaaaaaaaa
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                        <p class="lead"><?php echo (isset($item_detail[0]['name']) && !empty($item_detail[0]['name'])) ? $item_detail[0]['name'] : 'Product' ?></p>
                          <?php if(isset($item_detail[0]['name']) && !empty($item_detail[0]['name']))
                          {
                           ?>
                          <img style="max-width: 350px" class="img-responsive avatar-view" src="<?php echo base_url().'uploads/items_documents/'.$item_detail[0]['id'].'/'.$item_detail[0]['name']; ?>" alt="Visa">
                        <?php }
                            else
                            { ?>
                          <img style="max-width: 350px" class="img-responsive avatar-view" src="<?php echo base_url().'assets_admin/images/product-default.jpg'; ?>" alt="Visa">
                        <?php } ?>
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;max-width: 350px">
                            <?php echo (isset($item_detail[0]['detail']) && !empty($item_detail[0]['detail'])) ? $item_detail[0]['detail'] : '' ?>
                        </p>

                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                          <p class="lead">Created Date: <?php echo (isset($item_detail[0]['created_on']) && !empty($item_detail[0]['created_on'])) ? date('d F Y',strtotime($item_detail[0]['created_on'])) : '' ?></p>
                          <div class="table-responsive">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th style="width:50%">Reserve Price:</th>
                                  <td><?php echo (isset($item_detail[0]['price']) && !empty($item_detail[0]['price'])) ? $item_detail[0]['price'] : '' ?> AED (Minimum Bid 1000) </td>
                                </tr>
                                 <tr>
                                  <th style="width:50%">Registration No:</th>
                                  <td><?php echo (isset($item_detail[0]['registration_no']) && !empty($item_detail[0]['registration_no'])) ? $item_detail[0]['registration_no'] : '' ?></td>
                                </tr>
                                <?php 
                                    $role = $this->session->userdata('logged_in')->role; 
                                    $CI =& get_instance(); 
                                 ?>
                                
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div  class="item form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="bid_amount" min="<?php if($bid_start_price[0]['bid_start_price']){
                              echo $bid_start_price[0]['bid_start_price'];
                            }  ?>" name="bid_amount" value="<?php if($bid_start_price[0]['bid_start_price']){
                              echo $bid_start_price[0]['bid_start_price'];
                            }  ?>" class="form-control col-md-7 col-xs-12 bid_amount" step="1000" readonly>
                           
                            <button onclick="demooz();" type="button" id="increase_amount" class="btn btn-success" > Increase Bid</button>
                        </div>
                      </div>

                      <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                      <button  type="button"  id="bid" class="btn btn-danger bid"   value="<?php echo $item_detail[0]['id'];?>" >Enter Bid Amount</button>
                    </div> 
                  </div>
                      </div>
 
<script type="text/javascript">
 


  // $('#increase_amount').on('click' ,function(){
  //   alert('aaaaaaaaaa');
  //   var a = $('#bid_amount').val();
  //   alert(a);

  // });


    //     $('#bid').click(function(){
    //       var id = '<?php echo $item_detail[0]['id']; ?>';
    //       var url = '<?php echo base_url(); ?>';
    //      $.ajax({
    //       url: url + 'sales/check_bidding_requirments/?id='+id,
    //       type: 'POST',
    //       data:  {id:id},
    //       cache: false,
    //       contentType: false,
    //       processData: false
    //       }).then(function(data) {
    //         var objData = jQuery.parseJSON(data);
    //         console.log(objData);
    //         if (objData.msg == 'success') 
    //         {


    //           $('.msg-alert').css('display', 'block');
    //           $(".message").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
          
    //         }
    //         else
    //         {
    //           $('.msg-alert').css('display', 'block');
    //           $(".message").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
    //         }
    //   });
    // });

</script>