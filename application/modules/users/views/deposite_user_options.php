  
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">


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
      <?php if($this->session->flashdata('error')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('error');   ?>
          </div>
      </div>
  <?php }?>
    <div Mileage From="x_content"> 
      <div class="x_content">
        <hr>
        <div Mileage Depreciation="x_content"> 
          <p>Increase your deposit</p>
          <a href="<?php echo base_url().'users/cheque_info/'.$deposite_user_id;?>" paymenttype="cheque" class="btn btn-md btn-success  "> Cheque</a>
          <a href="javascript:Void(0)" onclick="loadViewAsType(this)" paymenttype="credit_card" class="btn btn-md btn-success  "></i> Credit Card</a>
           <a href="<?php echo base_url().'users/manuall_info/'.$deposite_user_id;?>" paymenttype="manuall" class="btn btn-md btn-success  "> Manually Deposite</a>
        </div>
      </div>
    </div>
    <div id="deposit_cheque" style="display: none;">
          <!--start-->

          <div id="ContentPlaceHolder1_divCash">
            <div style="font-size: 18px; color: #e61400;">
                <span id="ContentPlaceHolder1_lblPayCash">Pay Cheque Deposit</span>
            </div>
            <div style="font-size: 14px; color: #484848; padding-top: 10px;">
                <span id="ContentPlaceHolder1_lblVisitOurOffice">Please visit any of our office locations below to finalize your Cheque deposit.</span>
            </div>
            <div style="height: 20px;">
                &nbsp;
            </div>
            <table cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td style="width: 170px;" valign="top">
                        <div style="font-size: 14px; color: #999999;">
                            <span>Location</span>
                        </div>
                        <div style="margin-left: 1px; margin-top: 5px;">
                            <ul style="list-style-type: none" class="branch-names">
                            
                                <li style="margin: 5px 0" class="">
                                  <a href="javascript:Void(0)" class="my-account-loc-bg" style="padding: 5px; margin-top: 10px !important">
                                     ● Dubai
                                  </a>
                                </li>
                                </ul>
                            
                        </div>
                        <div style="font-size: 14px; color: #999999; padding-top: 50px;">
                            <span id="ContentPlaceHolder1_lblTime">Time</span>
                        </div>
                        <div style="font-size: 14px; padding-top: 5px;">
                            <span id="ContentPlaceHolder1_lblTime2">Sunday - Thursday: 8:00 am - 4:00 pm</span>
                            
                            <br>
                        </div>
                    </td>
                    <td valign="top">

                        <div id="ContentPlaceHolder1_adMap">
                            <div style="padding: 10px; border: solid 1px #a3a3a3; background-color: #ffffff;">
                                
                               
                                <iframe style="border: 1px solid #CCC;:0" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d28871.683847264754!2d55.371246!3d25.238256!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2112d5139b5a9a9e!2sPioneer%20Auctions!5e0!3m2!1sen!2sae!4v1567696518420!5m2!1sen!2sae" width="469" height="209" frameborder="0" allowfullscreen="" frameborder="0"></iframe>
                               
                            </div>
                            <br>
                            <span id="ContentPlaceHolder1_OnlyCheque" style="color:Red;">* Only cheque payments are allowed</span>

                        </div>
                        
                        <div style="height: 10px;">
                            &nbsp;
                        </div>
                      
                    </td>
                </tr>
            </tbody></table>
            <div style="height: 20px;">
                &nbsp;
            </div>
        </div>





          <!--end-->

    </div>
    <div id="deposit_credit_card" style="display: none;">
        <td class="my-account-bg-strip" valign="top">
            <div style="height: 15px;">
                &nbsp;
            </div>
            <!-- Content -->
            <div style="padding-left: 10px; padding-right: 10px;">
                <div id="ContentPlaceHolder1_divOnline">
                    <div style="position: absolute; margin-top: 10px; margin-left: 500px;">
                        <img src="https://cdn.emiratesauction.com/static/EASite/App_Themes/Home_en/Images/visa-master.png">
                    </div>
                    <div style="font-size: 18px; padding-top: 10px; color: #e61400;">
                        <span id="ContentPlaceHolder1_lblPayCreditCard">Pay by Credit Card</span></div>
                    
                    <div id="ContentPlaceHolder1_divCreditInfoEn">
                        <div style="font-size: 14px; color: #484848; padding-top: 10px;">
                        Please note that the minimum required amount is 5,000 AED.
                        <br>
                            We accept Visa and Mastercard only.
                        </div>
                        <div style="font-size: 12px; color: #484848; padding-top: 10px;">
                            <em>(Note that we do not accept Electron or ATM cards, only cards mentioned above are
                                accepted)</em>
                        </div>
                        <div style="font-size: 12px; color: #484848; padding-top: 10px;">
                            If you select this option, you will be asked to enter your Credit Card details in
                            the bank secure online payment page. Once your transaction has been processed you
                            will be able to enter an auction and begin bidding.
                            <br>
                            <br>
                          The Amount will be deducted from your card and will show in your bank statement, You can request your deposit refund anytime through our application, Refund will be credited to the same card, It usually takes 7-15 working days to get the credit back, depending on your card issuing bank.
                            <br>
                            <br>
                        </div>
                    </div>
                  <form method="post" id="demo_form" action="<?= base_url() ?>users/place_order">
                    <div style="font-size: 14px;">
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                      <br>
                          <input type="hidden" name="payment_type" value="credit_card">
                          <input type="hidden" name="user_id" value="<?= $this->session->userdata('logged_in')->id;?>">
                          <input type="hidden" name="user_id_for_admin" value="<?php echo $this->uri->segment(3); ?>" >
                          <input type="hidden" name="page_name" value="cheque">
                          <table cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                              <tr>
                                <!-- <td>
                                  <span>Deposit Category</span>
                                </td> -->
                                <td style="width: 220px; height: 25px;">
                                    <span>Deposit Amount</span>
                                </td>
                                <td>
                                    <span>New Bidding Deposit</span>
                                </td>
                              </tr>
                              <tr>
                                <input type="hidden" data-amount="<?= $auction_fee[0]['deposite']; ?>" value="<?php if(!empty($auction_fee)){ echo $auction_fee[0]['deposite']; } ?>" id="category_id" name="">
                               <!--  <td style="width: 220px; height: 25px;">
                                  <select required="required" id="category_id" onchange="appendAmount(this)" name="category_id">
                                      <option disabled selected="">Select Category</option>
                                      <?php
                                      if(count($category_list) > 0)
                                      {
                                          foreach ($category_list as $key => $value) { ?>
                                              <option value="<?= $value['id']; ?>" data-amount="<?= $value['auction_fee']; ?>"><?= $value['title']; ?></option>
                                          <?php
                                          }
                                      }
                                      ?>
                                  </select>
                                </td> -->
                                <td>
                                    <input name="category_amount" id="category_amount" type="number" value="<?php if($auction_fee){
                                      echo $auction_fee[0]['deposite'];
                                    } ?>" onkeyup="showBiddingAmount(this)" class="txt-deposit-amount">

                                    <div id="error" style="color: red;"> </div>
                                </td>
                                <td>
                                    <span id="new-bidding" style="color: #ff0000;"></span>
                                    <span id="ContentPlaceHolder1_lblLimitInfo" style="color:Red;">50,000</span>
                                    AED
                                </td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
                    <div style="padding-top: 20px; padding-bottom: 20px;">

                        <input type="button" onclick="checkEmptyFields(this)" name="ctl00$ContentPlaceHolder1$btnPayOnline" value="Proceed to Payment Site" id="btnPayOnline" class="btn-success">
                    </div>
                  </form>
                </div>
            </div>
            <!-- Content -->
        </td>
    </div>
        <!-- ///////////// sart /////////////// -->


        <!-- /////////////////// END //////////////////// -->


      <div style="font-size: 18px; font-weight: bold; padding-top: 35px;">
        <span>CURRENT BIDDING LIMIT</span>
      </div>
      <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td style="width: 160px; height: 25px;">
                <span>Total Deposit</span>
            </td>
            <td  style="color: #7f7f7f;">
                <?php echo $total; ?>
            </td>
          </tr>
          <tr>
            <td>
                <span>Bidding Limit</span>
            </td>
            <td style="color: #7f7f7f;">
                <?php echo $total*10; ?>
            </td>
          </tr>
        </tbody>
      </table>
  </div>
<script type="text/javascript">
  $(document).ready(function(){
    var deposit_amount = $("#category_amount").val();
    deposit_amount = deposit_amount*10;
    $("#ContentPlaceHolder1_lblLimitInfo").text(deposit_amount);

  });
  window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove(); 
      });
  }, 3000);
  var url = '<?php echo base_url();?>';
  function loadViewAsType(xp)
  {
    if($(xp).attr('paymenttype') == 'cheque')
    {
      $("#deposit_cheque").show();
      $("#deposit_credit_card").hide();
    }
    if($(xp).attr('paymenttype') == 'credit_card')
    {
      $("#deposit_cheque").hide();
      $("#deposit_credit_card").show();
    }

  }
  function showBiddingAmount(xp)
  {

    if($(xp).val() < $("#category_id").data('amount'))
    {
      $('#error').text('Fill the amount accordingly');
    }
    if($(xp).val() >= $("#category_id").data('amount'))
    {
       $('#error').hide();
    }

    var total = Number($(xp).val()) * Number(10);
    $("#ContentPlaceHolder1_lblLimitInfo").text(total);
  }
  function checkEmptyFields(xp)
  {
    if ($("#category_amount").val() < $("#category_id").data('amount')) {
      $('#error').text('Fill the amount accordingly').show();
      // alert('Please Fill Required Amount');
      $('#category_amount').focus();
      return false;
    }
    else{
      $("#demo_form").submit();
    }
  }
  
  function appendAmount(xp){
    $("#category_amount").val($('option:selected', xp).data('amount'));
    var category_price = $("#category_amount").val();
    if($("#category_amount").val() <= 0)
    {
      category_price = 5000;
      var total = Number(category_price) * Number(10);

    }
    else
    {
      var total = Number(category_price) * Number(10);
    }
        $("#ContentPlaceHolder1_lblLimitInfo").text(total);
  }
 </script>
