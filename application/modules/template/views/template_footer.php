		<!-- footer content -->
        <footer>
          <div class="pull-right">
            Copyright © 2020. All rights reserved.
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->

        <!-- users popup -->
        <div class="modal list-user" id="users" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
              
                    <div class="modal-body" id="data_table">
                        <h4 class="heading"></h4>
                        <p> </p>
                        <div class="button-row">
                            <div class="button-row text-success"></div>
                            <!-- <div class="button-row text-success" id="error-msg_email_success" ></div> -->
                           <!-- <a href="javascript:void(0)" id="confirm-email"   class="btn btn-default"></a> -->
                        </div>
                        <!-- <div class="note">
                            <p>Note: If you did not receive a link, <a href="javascript:void(0)">Request new link</a></p>
                        </div> -->
                    </div>
                    <div class="modal-footer">
                <a id="okbtn" class="btn btn-success btnid">Ok</a></div>
                </div>
            </div>
        </div>

        <script>

            var token_name = '<?= $this->security->get_csrf_token_name();?>';
            var token_value = '<?=$this->security->get_csrf_hash();?>';
            $(document).ready( function(){

                $(document).on("click", "#userdatatable tr.selected", function() {
                    var text = $(this).find("td:nth-child(2)").text();
                    var id = $(this).find("td:nth-child(1)").text();
                    console.log(text);

                    $("#selected_seller").val(text);
                    $("#seller_id").val(id);

                    $("#selected_seller_id option:selected").text(text);
                    $("#selected_seller_id option:selected").val(id);
                    
                    $("#live_item_seller_id option:selected").text(text);
                    $("#live_item_seller_id option:selected").val(id);

                });

                $(document).on("click", "#usertableid tr.selected", function() {
                    var text = $(this).find("td:nth-child(2)").text();
                    var user_id = $(this).find("td:nth-child(1)").text();
                    // console.log(text);

                    $("#username").val(text);
                    $("#user_id").val(user_id);
                    $("#item_buyer_id").val(user_id);
                });

                $(document).on("click", "#buyertable tr.selected", function() {
                    // var text = $(this).find("td:nth-child(2)").text();
                    var user_id = $(this).find("td:nth-child(1)").text();
                    console.log(user_id);
                    $.ajax({   
                        type: "post", //create an ajax request to display.php
                        url: '<?= base_url(); ?>' + "auction/buyer_details",    
                        data:{user_id:user_id,[token_name]:token_value},
                        success: function(data) {
                            objdata = $.parseJSON(data);
                            if(objdata.msg == 'success')
                            {
                                $('#B_code').val(objdata.data['user_row'][0]['id']);
                                $('#B_name').val(objdata.data['user_row'][0]['fname']+' '+objdata.data['user_row'][0]['lname']);
                                $('#buyer_id').val(objdata.data['user_row'][0]['id']);
                                $('#buyer_T_balance').val(objdata.data['balance']['amount']);
                                if ((objdata.data['buyer_per_charges']) != null) {
                                    $('#buyer_percentage_comission').val(objdata.data['buyer_per_charges']['commission']);
                                }
                                if ((objdata.data['buyer_charges']) != null) {
                                    $('#buyer_price_comission').val(objdata.data['buyer_charges']['commission']);
                                }
                                // $('#buyer_tblrows').empty();
                                // $('#buyer_tblrows').append(objdata.tr);

                            }else{
                                // $('.make_case').hide();
                                console.log('Database error occured.');
                            }
                        }
                    });
                });
            });

            $("#okbtn").on('click', function(){
                $("#users").modal("hide");
                update_item_buyer();
            });
            function update_item_buyer(){
    
            var auction_item_id = $('#auction_item_id').val();
            if (auction_item_id != '') {
              var item_buyer_id = $('#item_buyer_id').val();
              // alert(auction_item_id);
              // alert(item_buyer_id);
             // ajax for sale on approva live auction item
              $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/updateitembuyer",
                type: 'post',
                data: {auction_item_id: auction_item_id,item_buyer_id: item_buyer_id, [token_name]:token_value},
                success: function(data) {
                    var objData = jQuery.parseJSON(data);
                    if(objData.error == false){
                        getSoldItems(); 
                        dt1.draw();         
                    }else{
              
                    }
                }
              }); 
            }
          }
           function getSoldItems(){
    
            var auction_id = $('.auction_list_option').val();
            // var item_id = $('#item_id').val();
            if(auction_id){

              $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/getAuctionSoldItems",
                type: 'post',
                data: {auction_id: auction_id, [token_name]:token_value},
                beforeSend: function(){
                  //$('.sold_items_list').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
                },
                success: function(data) {

                  var objData = jQuery.parseJSON(data);
                  if(objData.error == false){
                    $('.sold_item_list').html(objData.response);
                    $('.sold_hide').hide();
                  }else{
                    // $('#auctions_items_list_tab tbody').html('');
                  }
                }
              }); 
            }
          }

            
        </script>