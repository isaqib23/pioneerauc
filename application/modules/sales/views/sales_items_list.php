<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }

    .bid-class{
      background: transparent url(Images/uqbidbtn.png) no-repeat center;
    color: #ffffff;
    display: block;
    font: normal 16px Tahoma;
    height: 34px;
    width: 60px;
    text-decoration: none;
    overflow: hidden;
    vertical-align: baseline;
    text-align: center;
}

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

       <?php if($this->session->flashdata('update_bid')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('update_bid');   ?>
          </div>
      </div>
    <?php }?>


        <?php if($this->session->flashdata('error')){ ?>
        <div class="alert">
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('error');   ?>
          </div>
      </div>
    <?php }?>
    <div> 
     <?php if($this->session->flashdata('success')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('success');   ?>
          </div>
      </div>
    <?php }?>
    <div> 



      <div id="result" class="message"></div>
      
       <div class="clearfix"></div> 
         
      <div class="x_content"> 
        <table class="table table-striped projects">
                      <thead>
                        <tr>
                          <th style="width: 1%">No</th>
                          <th style="width: 20%">Image</th> 
                          <th style="width: 20%">Product Name</th> 
                          <th style="width: 20%">Lot#</th> 
                          <th style="width: 20%">Current Price</th> 
                          <th style="width: 20%">Minimum Increment</th> 
                          <th style="width: 20%">Total Bidding Amount</th>
                          <th style="width: 20%">Total Bids</th>
                          <th style="width: 20%">Remaining Time</th>
                          <th style="width: 20%">End Time</th>
                          <th style="width: 20%">Total Deposite</th>
                          <th style="width: 20%">Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <?php $j = 0; $k = 0;

                        if(isset($items_list) && !empty($items_list))
                        {
                          foreach ($items_list as $value) {
                          $j++;
                         ?>
                        
                        <tr>
                          <td><?php echo $j ?>     </td>


                           <td>


                            <ul class="list-inline">
                              <li>
                                 <img style="max-width: 60px" class="img-responsive avatar-view" src="<?php echo base_url().'uploads/items_documents/'.$items_list[0]['id'].'/'.$item_images[0]['name']; ?>" alt="Visa">
                              </li> 
                            </ul>
                          </td>
                          <?php 
                            $itm_name = json_decode($value['name']);
                          ?>
                          <td>
                            <a> <?php echo $itm_name->english ?></a>
                          </td>

                          <td>
                            <a> <?php $this->db->select('lot_id');
                                        $this->db->from('item');
                                        $this->db->where('id',$value['id']);
                                        $q = $this->db->get();
                                        $result = $q->result_array();
                                        echo $result[0]['lot_id'];
                                         ?></a>
                          </td>

                          <td>
                            <a><?php $this->db->select('price');
                                        $this->db->from('item');
                                        $this->db->where('id',$value['id']);
                                        $q = $this->db->get();
                                        $result = $q->result_array();
                                        echo $result[0]['price'];
                                         ?></a> </a>
                          </td>

                           <td>
                            <a><?php $this->db->select('allowed_bids');
                                        $this->db->from('auction_items');
                                        $this->db->where('item_id',$value['id']);
                                        $q = $this->db->get();
                                        $result = $q->result_array();
                                        echo $result[0]['allowed_bids'];
                                         ?></a> </a>
                          </td>
                          
                          <td>

                            <a> <?php $this->db->select_max('bid_amount');
                                        $this->db->from('bid');
                                        $this->db->where('item_id',$value['id']);
                                        $q = $this->db->get();
                                        $result = $q->result_array();
                                        echo $result[0]['bid_amount'];
                                         ?></a>
                          </td>
                         
                           <td> 

                            <a> <?php  print_r($total_bids[$k]); ?></a>
                          
                        
                          </td>

                           <td> 

                         <?php      
                                $this->db->select('bid_end_time');
                                $this->db->from('auction_items');
                                $this->db->where('item_id',$value['id']);
                                $q = $this->db->get();
                                $time = $q->result_array()[0];
                                
                              if(isset($time)){
                              $datestr=$time['bid_end_time'];//Your date
                              $date=strtotime($datestr);//Converted to a PHP date (a second count)

                              //Calculate difference
                              $diff=$date-time();//time returns current time in seconds
                              $days=floor($diff/(60*60*24));//seconds/minute*minutes/hour*hours/day)
                              $hours=round(($diff-$days*60*60*24)/(60*60));

                              //Report
                              echo "$days days $hours hours remain<br />";
                                }?>
  

                          
                        
                          </td> 
                          <td> 

                            <a> <?php  echo $time['bid_end_time']?></a>
                        
                          </td>

                          <td> 

                            <a> <?php  echo $total_deposite['total_deposite']; ?></a>
                        
                          </td>


                          <td>
                            <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="false" class="btn btn-danger btn-xs oz_banner_pr"><i class="fa fa-file"></i> Bid Now</button>
                          </td>
                        </tr>
                      <?php $k++; } } ?>
                </tbody>
              </table>
      </div>

    </div>
  </div>
 </div> 
</div>
    <div class="modal fade bs-example-modal-banner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Transaction Detail</h4>
            </div>
            <div id="DivIdToPrintBanner" class="modal-banner-body">
              
            </div>
            <div class="modal-footer">
            </div>
          </div>
        </div>
      </div>


<script type="text/javascript">
  var url = "<?php echo base_url(); ?>"; 
  $('.oz_banner_pr').on('click',function(){
         // alert('clicker');
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    var auction_id = "<?php echo $auction_id; ?>"
  
     $.ajax({
      url: url + 'sales/bid_now',
      type: 'POST',
      data: {id:id,"<?=$this->security->get_csrf_token_name();?>":"<?=$this->security->get_csrf_hash();?>"},
      beforeSend: function(){

         $('.modal-banner-body').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
      },
      }).then(function(data) {
        console.log(data);
        var objData = jQuery.parseJSON(data);
        if (objData.msg == 'success') 
        {
          $('.modal-banner-body').html(objData.data);
        }

        if(objData.msg == 'error')
        {
        
          window.location = url + 'sales/items/' + auction_id;
        }
      });
  });



// setInterval(ajaxCall, 3000);



function ajaxCall()
{
   var auction_id = "<?php echo $auction_id; ?>";
   var item_id = "<?php echo $value['id']; ?>";
   $.ajax({
      url: url + 'sales/check_updated_bid/' + item_id,
      type: 'POST',
      data: {auction_id:auction_id,"<?=$this->security->get_csrf_token_name();?>":"<?=$this->security->get_csrf_hash();?>"},
      success: function(data){
        var objData = jQuery.parseJSON(data);
         if(objData.msg == 'update_bid')
        {
        
          window.location = url + 'sales/items/' + auction_id;
        }
         if(objData.msg == 'same_bid')
        {
                             // $('.msg-alert').css('display', 'block');
                             //  $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + objData.bid_amount + '</div></div>');
                             //     $("#result").fadeTo(2000, 500).slideUp(500, function(){
                             //  $("#result").slideUp(500);
                             //  });
        }
      }
    });
  }
   
</script>