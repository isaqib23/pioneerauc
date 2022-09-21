 <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="row">

<div id="result" class="message"></div>


                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                        <p class="lead"><?php echo (isset($item_detail[0]['name']) && !empty($item_detail[0]['name'])) ? $item_detail[0]['name'] : 'Product' ?></p>
                          <?php if(isset($item_images[0]['name']) && !empty($item_images[0]['name']))
                          {
                           ?>
                          <img style="max-width: 350px" class="img-responsive avatar-view" src="<?php echo base_url().'uploads/items_documents/'.$item_detail[0]['id'].'/'.$item_images[0]['name']; ?>" alt="Visa">
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
                                  <td><?php echo (isset($item_detail[0]['price']) && !empty($item_detail[0]['price'])) ? $item_detail[0]['price'] : '' ?> AED (Minimum Bid 100) </td>
                                </tr>
                                 <tr>
                                  <th style="width:50%">Registration No:</th>
                                  <td><?php echo (isset($item_detail[0]['registration_no']) && !empty($item_detail[0]['registration_no'])) ? $item_detail[0]['registration_no'] : '' ?></td>
                                </tr>
                                 <tr>
                                  <th style="width:50%">Make:</th>
                                  <td><?php echo (isset($item_detail[0]['make']) && !empty($item_detail[0]['make'])) ? $item_detail[0]['make'] : '' ?> </td>
                                </tr>
                                 <tr>
                                  <th style="width:50%">Model:</th>
                                  <td><?php echo (isset($item_detail[0]['model']) && !empty($item_detail[0]['model'])) ? $item_detail[0]['model'] : '' ?> </td>
                                </tr>
                                <?php 
                                    $role = $this->session->userdata('logged_in')->role; 
                                    $CI =& get_instance(); 
                                 ?>

                                 <tr>
                                  <th style="width:50%">Create Time</th>
                                  <td>
                                    <?php echo (isset($item_detail[0]['created_on']) && !empty($item_detail[0]['created_on'])) ? $item_detail[0]['created_on'] : '' ?>
                                </td>
                                </tr>
                                

                                <tr>
                                  <th style="width:50%"> Remaining Time</th>
                                 
                                  <td style="width:100%">  
                                    <?php
                    
                                    if(isset($time)){
                              $datestr=$time[0]['bid_end_time'];//Your date
                              $date=strtotime($datestr);//Converted to a PHP date (a second count)

                              //Calculate difference
                              $diff=$date-time();//time returns current time in seconds
                              $days=floor($diff/(60*60*24));//seconds/minute*minutes/hour*hours/day)
                              $hours=round(($diff-$days*60*60*24)/(60*60));

                              //Report
                              echo "$days days $hours hours remain<br />";
                                }?>


                           </td>
                              
                                  <td> </td>
                        
                                </tr>
                                  <tr>
                                  <th style="width:50%">Bids</th>
                                  <td>
                                    

                                    <?php print_r($total_bids);  ?>
                                </td>
                                </tr>
                                
                              </tbody>
                            </table>
                            <div><h3>Last Bid Amount</h3> 
                                <h2 style="color: black;"><?php if(!empty($last_bid_amount[0]['bid_amount'])) {echo $last_bid_amount[0]['bid_amount'];} else { echo "0"; } ?></h2>
                              </div>
                          </div>
                        </div>
                    
                        <!-- /.col -->  
                      </div>
                      <div class="form-group">
                        <form method="post"  novalidate="" name="myForm" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left" >
                          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                          <input type="hidden" name="item_id" value="<?php echo $item_detail[0]['id'];?>">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <?php if(!empty($deposite)) {  ?>
                           <input type="number" id="bid_amount" min="<?php if(isset($bid_start_price[0]['bid_start_price']) && !empty($bid_start_price[0]['bid_start_price'])){
                              echo $bid_start_price[0]['allowed_bids'];
                            }else{ echo 1000;}  ?>" name="bid_amount" value="<?php if(isset($bid_start_price[0]['bid_start_price']) && !empty($bid_start_price[0]['bid_start_price'])){
                              echo $bid_start_price[0]['allowed_bids'];
                            }else{ echo 1000;}  ?>" class="form-control col-md-7 col-xs-12 bid_amount" step="1000" readonly>
                            <button onclick="demooz();" type="button" id="increase_amount" class="btn btn-success" > Increase Bid</button>
                            <button type="button" id="decrease_amount" class="btn btn-success" > Decreasee Bid</button>

                             <button type="submit" id="sendbtn" value="<?php echo $item_detail[0]['id'];?>" class="btn btn-danger bid">Bid NOW</button>
                        <?php } else { ?>
                          <p style="margin-left: 20%; margin-right: 20% ">
                          Please note that you must deposit insurance to be able to carry out the process of bidding on vehicles, please click on the link below to be able to deposit and continue the bidding process to </p>
                          <a href="<?php echo base_url();?>transaction/show_deposite_options"></a>
                          <?php } ?>
                    
                    </div> 
                    </form>
                  </div>


                  <div class="modal fade bs-example-modal-banner" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Enter Bidding Amount</h4>
            </div>
            <div id="DivIdToPrintBanner" class="modal-banner-body">
              
            </div>
            <div class="modal-footer">
      
            </div>
          </div>
        </div>
      </div>
                      </div>
 
<script type="text/javascript">
  $('#increase_amount').on('click' ,function(){
    var a = $('#bid_amount').val();
    var new_a = parseInt(a) + 1000;
     $('#bid_amount').val(new_a);
  });

    $('#decrease_amount').on('click' ,function(){
    var a = $('#bid_amount').val();
    if(a > 1000)
    {
    var new_a = parseInt(a) - 1000;
    }
    else{
      var new_a = 1000;
    }

     $('#bid_amount').val(new_a);
  });
     

     $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            var url = "<?php echo base_url(); ?>"
            var id = "<?php echo $item_detail[0]['id']; ?>"
          }) .on('form:submit', function() {
            
                  var formData = new FormData($("#demo-form2")[0]);
                
                    // alert(formaction_path);
                    $.ajax({
                        url: url + 'sales/bid_amount',
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                         var objData = jQuery.parseJSON(data);
                       if(objData.msg == 'bid')
                        {
                           setTimeout(function(){
                              $(".bs-example-modal-banner").modal("hide");
                             },3);                      
                           window.setTimeout(function(){location.reload()},3000);
                           window.location = url + 'sales/items/' + id;
                        }
                        if(objData.msg == 'deposite_error')
                        {
                           // alert('aaaaa');
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + objData.mid + '</div></div>');
                               setTimeout(function(){
                              $(".bs-example-modal-banner").modal("hide");
                             },3);
                            $("#result").fadeTo(2000, 500).slideUp(500, function(){
                            $("#result").slideUp(500);
                            });


                        }
                        //  else {
                        //     // alert('aaaaa');
                        //     $('.msg-alert').css('display', 'block');
                        //     $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + objData.mid + '</div></div>');
                        //       setTimeout(function(){
                        //       $(".bs-example-modal-banner").modal("hide");
                        //      },3);
                        //       $("#result").fadeTo(2000, 500).slideUp(500, function(){
                        //         $("#result").slideUp(500);
                        //         });
                        // }
                        
                    });
                    return false;
          });




</script>