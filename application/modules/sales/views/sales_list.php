<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
  .disabledbutton {
    pointer-events: none;
    opacity: 0.4;
}
</style> 
<script type="text/javascript">
  
  function createCountDown(elementId, date) {
  // Set the date we're counting down to
  var countDownDate = new Date(date).getTime();

  // Update the count down every 1 second
  var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();

    // Find the distance between now an the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // var daysSpan = clock.querySelector('.days');
    // var hoursSpan = clock.querySelector('.hours');
    // var minutesSpan = clock.querySelector('.minutes');
    // var secondsSpan = clock.querySelector('.seconds');

    // Display the result in the element with id="demo"
    document.getElementById(elementId).innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";

    // If the count down is finished, write some text 
    if (distance < 0) {
    clearInterval(x);
    document.getElementById(elementId).innerHTML = "SALE EXPIRED";
    $("#"+elementId).parent().parent().addClass('disabledbutton');
    }
  }, 1000);
}
</script>
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
      <div class="clearfix"></div>  
      <div class="x_content">
         <div class="row">
                      <?php
                       if(isset($sales_list) && !empty($sales_list)){ 

                         $role = $this->session->userdata('logged_in')->role; 
                         $CI =& get_instance();
                       foreach ($sales_list as $sales_value) {
                        $sale_title = json_decode($sales_value['title']);
                        $result_items = $CI->sales_model->get_auction_item_ids($sales_value['id']);
                        if($sales_value['status'] == 'active')
                        {

                        ?>
                      <div onclick="get_sales_items(this);" id="<?php echo $sales_value['id']; ?>" class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12" style="cursor: pointer;">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-check-square-o"></i>
                          </div>
                          <div class="count"><?php echo count($result_items); ?></div>

                          <h3><?php if(isset($sales_value['title'])){echo $sale_title->english; } ?></h3>
                          <p id="clocksurp_<?php echo $sales_value['id']; ?>"></p> 
                          </div>
                        </div> 
                      <script type="text/javascript">
                        var id = "<?php echo $sales_value['id']; ?>";
                        var date = "<?php echo date("Y-m-d h:i:s",strtotime($sales_value['expiry_time'])); ?>";
                        createCountDown('clocksurp_'+id, date)
                      </script>
                    <?php 
                      }
                        }
                          } 

                    ?>
                   
                    </div>
      </div>
</div>
 

<script type="text/javascript">
  var url = "<?php echo base_url(); ?>"; 
  var formaction_path = "<?php echo $formaction_path; ?>"; 
  function get_sales_items(e){
      var auction_id = e.id;
        $.ajax({
          url: url + 'sales/' + formaction_path,
          type: 'POST',
          data: {"auction_id":auction_id,"<?=$this->security->get_csrf_token_name();?>":"<?=$this->security->get_csrf_hash();?>"},
          }).then(function(data) {
          var objData = jQuery.parseJSON(data);
          if (objData.msg == 'success') 
          { 
                  
             window.location = url + 'sales/items/'+auction_id;
           
          } 
          else 
          {
              $('.msg-alert').css('display', 'block');
              $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');

                 window.setTimeout(function() {
              $(".alert").fadeTo(500, 0).slideUp(500, function(){
                  $(this).remove(); 
              });
          }, 3000);
          }
          });

  }
</script>