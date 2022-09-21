    <div class="main gray-bg listing-page grid-view">
    <?= $this->load->view('template/auction_cat');?>
        <div class="container">
            <div class="row">
                <div class="col left-col">
                    <form id="frmfilters">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <input type="hidden" name="auction_id" value="<?= $auction_id; ?>">
                        <div class="price-box">
                            <div class="head show-on-reponsive">
                                <input type="hidden" name="auction_id" value="<?= $auction_id; ?>">
                                Search
                            </div> 
                        </div>  
                        <div class="toggle-wrapper"> 
                            <div class="price-box">
                                <div class="head hide-on-reponsive">
                                    <input type="hidden" name="auction_id" value="<?= $auction_id; ?>">
                                    Search
                                </div>
                                <div class="body">
                                    <div class="filter-head two">
                                        <ul class="h-list">
                                            <li>
                                                <div class="search">
                                                    <input type="text" class="form-control" placeholder="Search" id="search">
                                                      <button type="button" id="search_btn"></button>
                                                </div>
                                            </li>
                                            <!-- <li>
                                                <select class="selectpicker" title="Sort by type" id="sort_by">
                                                    <option value="" selected>Sort by Type</option>
                                                    <option value="">Latest</option>
                                                    <option value="hp">High Price</option>
                                                    <option value="lp">Low Price</option>
                                                </select>
                                            </li> -->
                                        </ul>
                                    </div>
                                    <strong>Price</strong>
                                    <hr>
                                    <div class="row col-gap-10">
                                        <div class="col-6">
                                            <select name="min" class="selectpicker" title="Min">
                                                <option value="0">Min</option>
                                                <option value="1000">1000</option>
                                                <option value="2000">2000</option>
                                                <option value="3000">3000</option>
                                                <option value="4000">4000</option>
                                                <option value="5000">5000</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="max" class="selectpicker"  title="Max">
                                                <option value="0">Max</option>
                                                <option value="100000">100000</option>
                                                <option value="200000">200000</option>
                                                <option value="300000">300000</option>
                                                <option value="400000">400000</option>
                                                <option value="500000">500000</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-accordion">
                                <div class="box active">
                                    <div class="head">
                                        Year
                                    </div>
                                    <div class="body">
                                        <div class="row col-gap-10">
                                            <div class="col-6">
                                                <select name="min_year" size="5"  class="selectpicker" title="From">
                                                    <!-- <option value="0"></option> -->
                                                    <?php $year = date('Y'); ?>
                                                    <?php for ($i=0; $i <= 50 ; $i++) { ?>
                                                        <option  value="<?= $year-$i ?>"><?= $year-$i ?></option>
                                                    <?php }; ?>
                                                </select>
                                            </div>
                                            <div  class="col-6">
                                                <select name="max_year" class="selectpicker" size="5" title="To">
                                                    <!-- <option value="0">From</option> -->
                                                    <?php $year = date('Y'); ?>
                                                    <?php for ($i=0; $i <= 50 ; $i++) { ?>
                                                        <option  value="<?= $year-$i ?>"><?= $year-$i ?></option>
                                                    <?php }; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                if ($selected_auction['category_id'] == 1) { ?>
                                <div class="box active">
                                    <div class="head">
                                        Millage
                                    </div>
                                    <div class="body">
                                        <div class="row col-gap-10">
                                            <div class="col-6">
                                                <select name="min_milage" class="selectpicker" title="To">
                                                    <!-- <option value="0">To</option> -->
                                                    <option value="1000">1000</option>
                                                    <option value="2000">2000</option>
                                                    <option value="3000">3000</option>
                                                    <option value="4000">4000</option>
                                                    <option value="5000">5000</option>
                                                    <option value="5000">50000</option>
                                                </select>
                                            </div>
                                            <div  class="col-6">
                                                <select name="max_milage" class="selectpicker" title="From">
                                                    <!-- <option value="0">From</option> -->
                                                    <option value="100000">100000</option>
                                                    <option value="200000">200000</option>
                                                    <option value="300000">300000</option>
                                                    <option value="400000">400000</option>
                                                    <option value="500000">500000</option>
                                                </select>
                                            </div>
                                            <ul class="h-list radio-items">
                                                <li class="radio">
                                                    <label>
                                                        <input type='radio' name="milage_type" value="km">
                                                        <span for="km">km</span>
                                                    </label>
                                                </li>
                                                <li class="radio">
                                                    <label>
                                                        <input type='radio' name="milage_type" value="miles" >
                                                        <span for="miles">miles</span>
                                                    </label>
                                                </li>
                                            </ul>    
                                            <!-- <div class="custom-radio">
                                                <ul>
                                                    <li>
                                                        <input type='radio' name="milage_type" value="km" checked="" >
                                                        <label for="km">km</label>
                                                    </li>
                                                    <li>
                                                        <input type='radio' name="milage_type" value="miles" >
                                                        <label for="miles">miles</label>
                                                    </li>
                                                </ul>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                                <?php }?>

                                <?php 
                                if($item_category_fields):
                                    foreach ($item_category_fields as $key => $field):
                                     ?>
                                        <div class="box active">
                                            <div class="head">
                                                <?= $field['label']; ?>
                                            </div>
                                            <div class="body">
                                                <ul>
                                                    <?php 
                                                    if(in_array($field['type'], ['select','radio-group','checkbox-group'])):
                                                        $values = json_decode($field['values'],true);
                                                        foreach ($values as $key => $value):
                                                            ?>
                                                            <li class="checkbox red">
                                                                <label>
                                                                    <input type="checkbox"name="<?= $field['id']; ?>[]" value="<?= $value['value']; ?>">
                                                                    <span><?= $value['label']; ?></b></span>
                                                                </label>
                                                            </li>
                                                        <?php endforeach;
                                                    elseif(in_array($field['type'], ['text','textarea'])):
                                                        ?>
                                                        <li class="checkbox red">
                                                            <input type="<?= $field['type']; ?>" 
                                                                id="<?= $field['id']; ?>" 
                                                                name="<?= $field['id']; ?>" 
                                                                class="form-control"
                                                                placeholder="<?= $field['placeholder']; ?>">
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                                <a href="#" class="view-link">View More</a>
                                            </div>
                                        </div>  
                                    <?php endforeach;
                                endif;
                                ?>
                                
                                <div class="button-row">
                                    <button type="button" id="applyFilters" name="apply" value="Apply" class="btn btn-default">Apply</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col right-col">
                    <div class="filter-head">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Search Results (<span id="count">0</span>)</h3>
                            </div>
                            <div class="col-md-8">
                                <ul class="h-list">
                                    <!-- <li>
                                        <div class="search">
                                            <input type="text" class="form-control" placeholder="Search" id="search">
                                              <button type="button" id="search_btn"></button>
                                        </div>
                                    </li> -->
                                    <li>
                                        <select class="selectpicker" title="Sort by type" id="sort_by">
                                            <option value="" selected>Sort by Type</option>
                                            <option value="">Latest</option>
                                            <option value="hp">High Price</option>
                                            <option value="lp">Low Price</option>
                                        </select>
                                    </li>
                                    <li>
                                        <div class="view-box">
                                            <span>View</span>
                                            <a href="#" id="list" class="list active"></a>
                                            <a href="#" class="grid "></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <span id="load-here"></span>
                    <!-- <div class="pager">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="list">
                                    <li class="link active">
                                        <a href="#">
                                            1
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            2
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            3
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            4
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            5
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            6
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            7
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            8
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            9
                                        </a>
                                    </li>
                                    <li class="link">
                                        <a href="#">
                                            10
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">Next ></a>
                                    </li>
                                    <li>
                                        <a href="#">Last page ></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <div class="text-right">
                                    <select class="selectpicker" >
                                        <option>26 results per page</option>
                                        <option>10 results per page</option>
                                        <option>20 results per page</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="button-row" style="text-align: center; margin: 20px 0;">
            <a id="load-more" data-offset="0" href="#" class="btn btn-default load-btn" style="display: none"></a>
        </div>
        
        <section class="our-app">
            <div class="container">
                <div class="inner">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3>Download the Pioneer app for<br> iOS or Android</h3>
                        </div>
                        <div class="col-lg-4">
                            <ul>
                                <li data-aos="fade-down" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                                    <a href="#">
                                        <img src="<?= NEW_ASSETS_IMAGES?>/app-store-icon.png" alt="">
                                    </a>
                                </li>
                                <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                                    <a href="#">
                                        <img src="<?= NEW_ASSETS_IMAGES ?>/play-store-icon.png" alt="">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="app-img" data-aos="fade-right" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                        <img src="<?= NEW_ASSETS_IMAGES?>/app-img.png" alt="">
                    </div>
                </div>
            </div>
        </section>
    </div>                 
   

<script>
    $( document ).ready(function() {
    $('#load-more').click();
    // var count = $('#count').val();
    $('#list').click();
});
$('#load-more').on('click', function(event){
    event.preventDefault();

    var btn = $(this);
    var offset = btn.data('offset');
    var auctionID = '<?= $auction_id; ?>';
    var search = $('#search').val();
    var sort = $('#sort_by').val();
    //var next = offset + 3;

    $.ajax({
        method: "POST",
        url: "<?= base_url('auction/OnlineAuction/ai_load_more'); ?>",
        data: {'offset':offset, 'auction_id':auctionID, 'search':search, 'sort':sort},
        beforeSend: function(){
            $(".overlay").show();
        },
        success: function(data){
            //alert(data);
            $('.overlay').hide();
            var response = JSON.parse(data);
            console.log(response);
            if(response.status == 'success'){
              $('#load-here').append(response.items);
              btn.data('offset', response.offset);
              btn.text('Load More '+response.offset+'/'+response.total);
              $('#count').text(response.total);
              $('#countA').text(response.total);
              if(response.btn == false){
                btn.hide();
              }
            }else{
              btn.hide();
            }
        }
    });
});

$('#applyFilters').on('click', function(event){
    event.preventDefault();

    var form = $(this).closest('form').serializeArray();

    $.ajax({
        method: "POST",
        url: "<?= base_url('auction/OnlineAuction/apply_filters'); ?>",
        data: form,
        beforeSend: function(){
            $(".overlay").show();
        },  
        success: function(data){
            $(".overlay").hide();
            //console.log(data);
            var response = JSON.parse(data);
            if(response.status == 'success'){
                $('#load-here').html(response.items);
            }else{
                $('#load-here').html('<h1>No item found.</h1>');
            }
            if(response.btn == false){
                $('#load-more').hide();
            }
        }
    });
});


$('#search_btn').on('click', function(event){
    event.preventDefault();

    var search = $('#search').val();
    var sort = $('#sort_by').val();
    var auctionID = '<?= $auction_id; ?>';
    //var next = offset + 3;

    $.ajax({
      method: "POST",
      url: "<?= base_url('auction/OnlineAuction/search_online_items'); ?>",
      data: {'search':search, 'auction_id':auctionID, 'sort':sort},
      beforeSend: function(){
        // $('#loading').show();
        $(".overlay").show();
      },
      success: function(data){
        $(".overlay").hide();
        //$('#loading').hide();
        var response = JSON.parse(data);
        console.log(response);
        if(response.status == 'success'){
          $('#load-here').html(response.items);
          $('#load-more').data('offset', response.offset);
          $('#load-more').text('Load More '+response.offset+'/'+response.total);
          $('#count').text(response.total);
          // $('#countA').text(response.total);
          if(response.btn == false){
            $('#load-more').hide();
          }else{
            $('#load-more').show();
          }
        }else{
            $('#load-here').html('<h1>No item found.</h1>');
          $('#load-more').hide();
        }
      }
    });
});


$('#sort_by').on('change', function(event){
    event.preventDefault();

    var search = $('#search').val();
    var sort = $('#sort_by').val();
    var auctionID = '<?= $auction_id; ?>';
    //var next = offset + 3;

    $.ajax({
      method: "POST",
      url: "<?= base_url('auction/OnlineAuction/search_online_items'); ?>",
      data: {'search':search, 'auction_id':auctionID, 'sort':sort},
      beforeSend: function(){
        $(".overlay").show();
      },
      success: function(data){
        //alert(data);
        $(".overlay").hide();
        var response = JSON.parse(data);
        console.log(response);
        if(response.status == 'success'){
          $('#load-here').html(response.items);
          $('#load-more').data('offset', response.offset);
          $('#load-more').text('Load More '+response.offset+'/'+response.total);
          $('#count').text(response.total);
          if(response.btn == false){
            $('#load-more').hide();
          }else{
            $('#load-more').show();
          }
        }else{
            $('#load-here').html('<h1>No item found.</h1>');
            $('#load-more').hide();
        }
      }
    });
});

// function doFavt(e){
//     //alert('dona done');
//     var heart = $(e);
//     var itemID = $(e).data('item');
//     var auction_id = $(e).data('auction-id');
//     var auction_item_id = $(e).data('auction-item-id');
//     $.ajax({
//         type: 'post',
//         url: '<?= base_url('auction/OnlineAuction/do_favt'); ?>',
//         data: {'item_id':itemID, 'auction_id':auction_id, 'auction_item_id':auction_item_id},
//         success: function(msg){
//             console.log(msg);
//             //var response = JSON.parse(msg);
//             if(msg == 'do_heart'){
//                 heart.find('.fa-heart-o').addClass('fa-heart').removeClass('fa-heart-o');           
//             }
//             if(msg == 'remove_heart'){
//                 heart.find('.fa-heart').addClass('fa-heart-o').removeClass('fa-heart');
//             }
//             if(msg == '0'){
//                 window.location.replace('<?= base_url("home/login?rurl="); ?>'+encodeURIComponent(window.location.href));
//             }
//         }
//     });   
// }

$('.pagination_link').on('click', 'a',function(e){
    e.preventDefault();
    var page_hr = $(this).attr('href');  
    var offset = page_hr.replace('#/', ''); 
    var auction_id = '<?= $auction_id ?>'; 
    var search = $('#search').val();
    $.ajax({
        url: '<?= base_url() ?>' + 'auction/OnlineAuction/ai_load_more/'+offset,
        type: 'POST',
        data: {offset:offset, auction_id:auction_id, search:search},
        beforeSend: function(){ 
          // $('.oz-main-content-div').html('<img style="" src="<?//= ASSETS_USER; ?>images/loading.gif" />'); 
        },
    }).then(function(data) {
        var objData = jQuery.parseJSON(data); 
        console.log(objData);
        alert(objData);
        console.log('objData');
        if (objData.status == 'success') 
        {
          $('#load-here').html(''); 
          $('#load-here').html(objData.items);
        }
    });
});

   </script>