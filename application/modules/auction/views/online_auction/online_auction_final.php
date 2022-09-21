<style>
    h1 {width: 100%; text-align: center;}
</style>
    <div class="main gray-bg listing-page">
        <?= $this->load->view('template/auction_cat', ['category_id', $category_id]);?>
        <div class="container">
            <div class="row">
                <div class="col left-col">
                    <form id="frmfilters">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <input type="hidden" name="auction_id" value="<?= $auction_id; ?>">
                        <!-- <div class="price-box">
                            <div class="head">
                                Price
                            </div>
                            <div class="body">
                                <div class="row col-gap-10">
                                    <div class="col-6">
                                        <select class="selectpicker" size="5" title="Min">
                                            <option>option 1</option>
                                            <option>option 2</option>
                                            <option>option 3</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="selectpicker" size="5" title="Max">
                                            <option>option 1</option>
                                            <option>option 2</option>
                                            <option>option 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="price-box">
                            <div class="head show-on-reponsive">
                                <?= $this->lang->line('search');?>
                            </div> 
                        </div>    
                        <div class="toggle-wrapper">
                            <div class="price-box">
                                <div class="head hide-on-reponsive">
                                   <?= $this->lang->line('search');?>
                                </div>
                                <div class="body">
                                    <div class="filter-head two">
                                        <ul class="h-list">
                                            <li>
                                                <div class="search">
                                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('search');?>" name="search" id="search">
                                                    <!-- <button type="button" id="search_btn"></button> -->
                                                </div>
                                            </li>
                                            <!-- <li>
                                                <select class="selectpicker" title="Sort by type" name="sort" id="sort_by">
                                                    <option value="" selected>Sort by Type</option>
                                                    <option value="">Latest</option>
                                                    <option value="hp">High Price</option>
                                                    <option value="lp">Low Price</option>
                                                </select>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-accordion">
                                
                                <?php 
                                if ($selected_category['include_make_model'] == 'yes') { ?>
                                    <div class="box active disable">
                                        <div class="head">
                                            <?= $this->lang->line('make'); ?>
                                        </div>
                                        <div class="body">
                                            <select name="make" id="make" class="selectpicker" size="5" title="<?= $this->lang->line('select_make');?>">
                                                <?php foreach ($item_makes as $key => $make_value) { 
                                                    $make_title = json_decode($make_value['title']) ?>
                                                    <option value="<?= $make_value['id']; ?>"><?= $make_title->$language; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="box active disable">
                                        <div class="head">
                                            <?= $this->lang->line('model');?>
                                        </div>
                                        <div class="body">
                                            <select name="model" id="model" class="selectpicker" size="5" title="<?= $this->lang->line('select_model');?>">
                                                <option value="0"><?= $this->lang->line('select_make_first');?></option>
                                            </select>
                                        </div>
                                    </div>
                                <?php }?>

                                <div class="box active disable">
                                    <div class="head">
                                        <?= $this->lang->line('price');?>
                                    </div>
                                    <div class="body">
                                        <div class="row col-gap-10">
                                            <div class="col-6">
                                                <select name="min" class="selectpicker" size="5" title="<?= $this->lang->line('min');?>">
                                                    <option value="0"><?= $this->lang->line('min');?></option>
                                                    <option value="1000">1000</option>
                                                    <option value="2000">2000</option>
                                                    <option value="3000">3000</option>
                                                    <option value="4000">4000</option>
                                                    <option value="5000">5000</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <select name="max" class="selectpicker" size="5" title="<?= $this->lang->line('max');?>">
                                                    <option value="0"><?= $this->lang->line('max');?></option>
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

                                <div class="box active disable">
                                    <div class="head">
                                        <?= $this->lang->line('year');?>
                                    </div>
                                    <div class="body">
                                        <div class="row col-gap-10">
                                            <div class="col-6">
                                                <select name="min_year" size="5"  class="selectpicker" title="<?= $this->lang->line('from'); ?>">
                                                    <!-- <option value="0"></option> -->
                                                    <?php $year = date('Y'); ?>
                                                    <?php for ($i=0; $i <= 50 ; $i++) { ?>
                                                        <option  value="<?= $year-$i ?>"><?= $year-$i ?></option>
                                                    <?php }; ?>
                                                </select>
                                            </div>
                                            <div  class="col-6">
                                                <select name="max_year" class="selectpicker" size="5" title="<?= $this->lang->line('to'); ?>">
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
                                if ($selected_category['include_make_model'] == 'yes') { ?>
                                    <div class="box active disable">
                                        <div class="head">
                                            <?= $this->lang->line('millage');?>
                                        </div>
                                        <div class="body">
                                            <div class="row col-gap-10">
                                                <div class="col-6">
                                                    <select name="min_milage" class="selectpicker" title="<?= $this->lang->line('from'); ?>">
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
                                                    <select name="max_milage" class="selectpicker" title="<?= $this->lang->line('to'); ?>">
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
                                                            <span for="km"><?= $this->lang->line('km');?></span>
                                                        </label>
                                                    </li>
                                                    <li class="radio">
                                                        <label>
                                                            <input type='radio' name="milage_type" value="miles" >
                                                            <span for="miles"><?= $this->lang->line('miles');?></span>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box active disable">
                                        <div class="head">
                                            <?= $this->lang->line('specs');?>
                                        </div>
                                        <div class="body">
                                            <div class="row col-gap-10">
                                                <ul class="h-list radio-items">
                                                    <li class="radio">
                                                        <label>
                                                            <input type='radio' name="specification" value="GCC">
                                                            <span for="GCC"><?= $this->lang->line('gcc');?></span>
                                                        </label>
                                                    </li>
                                                    <li class="radio">
                                                        <label>
                                                            <input type='radio' name="specification" value="IMPORTED" >
                                                            <span for="IMPORTED"><?= $this->lang->line('imported');?></span>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>  
                                <?php }?>
                                
                                <div class="box active disable">
                                    <div class="head">
                                        <?= $this->lang->line('lot');?> #
                                    </div>
                                    <div class="body">
                                        <input type="text" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="lot_no" class="form-control" placeholder="<?= $this->lang->line('lot');?>">
                                    </div>
                                </div>
                                <?php 
                                if($item_category_fields): ?>
                                    <a href="#" class="advance-filter"><?= $this->lang->line('ad_filter');?></a>
                                <?php endif;
                                ?>
                                <div class="advance-wrapper">
                                    <?php 
                                    if($item_category_fields):
                                            $k = 0;
                                        foreach ($item_category_fields as $key => $field):
                                            $k++;
                                            $j = 0;
                                            ?>
                                            <div class="box active">
                                                <div class="head">
                                                    <?= $this->template->make_dual($field['label']); ?>
                                                </div>
                                                <div class="body">
                                                    <ul>
                                                        <?php 
                                                        if(in_array($field['type'], ['select','radio-group','checkbox-group'])):
                                                            $values = json_decode($field['values'],true);
                                                            foreach ($values as $key => $value):
                                                                $j++; 
                                                                ?>
                                                                <li class="checkbox red">
                                                                    <label>
                                                                        <input type="checkbox"name="<?= $field['id']; ?>[]" value="<?= $value['value']; ?>">
                                                                        <span><?= $this->template->make_dual($value['label']); ?></b></span>
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
                                                    <?php 
                                                    if(in_array($field['type'], ['select','radio-group','checkbox-group']) && $j > 6): ?>
                                                        <a href="#" class="view-link"><?= $this->lang->line('view_more');?></a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>  
                                        <?php endforeach;
                                    endif;
                                    ?>
                                </div>

                                <div class="button-row">
                                    <button id="applyFilters" name="apply" value="Apply" class="btn btn-default"><?= $this->lang->line('apply');?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col right-col">
                    <div class="detail-tabs">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="online-tab" data-toggle="tab" href="#online" role="tab" aria-controls="online" aria-selected="true"><?= $this->lang->line('online_auction');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="live-tab" data-toggle="tab" href="#live" role="tab" aria-controls="live" aria-selected="false"><?= $this->lang->line('live_auction');?></a>
                            </li>
                            <li class="nav-item close-auction-tab">
                                <a class="nav-link" id="close-tab" data-toggle="tab" href="#close" role="tab" aria-controls="close" aria-selected="true"><?= $this->lang->line('close_auction');?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active grid-view" id="online" role="tabpanel" aria-labelledby="online-tab">
                                <div class="filter-head">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h3><?= $this->lang->line('search_result');?> (<span id="count">0</span>)</h3>
                                        </div>
                                        <div class="col-md-8">
                                            <ul class="h-list">
                                                <!-- <li>
                                                    <div class="search">
                                                        <input type="text" class="form-control" placeholder="Search">
                                                        <button></button>
                                                    </div>
                                                </li> -->
                                                <li>
                                                    <select class="selectpicker" title="<?= $this->lang->line('sort_type');?>" id="online_sort_by">
                                                        <option value=""><?= $this->lang->line('latest');?></option>
                                                        <option value="hp"><?= $this->lang->line('high_price');?></option>
                                                        <option value="lp"><?= $this->lang->line('low_price');?></option>
                                                    </select>
                                                </li>
                                                <li>
                                                    <div class="view-box">
                                                        <span><?= $this->lang->line('view');?></span>
                                                        <a href="#" id="list" class="list active"></a>
                                                        <a href="#" class="grid"></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <span id="load-here"></span>
                            </div>
                            <div class="tab-pane fade list-view " id="live" role="tabpanel" aria-labelledby="live-tab">
                                <div class="filter-head">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h3><?= $this->lang->line('search_result');?> (<span id="live_count">0</span>)</h3>
                                        </div>
                                        <div class="col-md-8">
                                            <ul class="h-list">
                                                <!-- <li>
                                                    <div class="search">
                                                        <input type="text" class="form-control" placeholder="Search">
                                                        <button></button>
                                                    </div>
                                                </li> -->
                                                <li>
                                                    <select class="selectpicker" title="<?= $this->lang->line('sort_type');?>" id="live_sort_by">
                                                        <option value=""><?= $this->lang->line('latest');?></option>
                                                        <option value="hp"><?= $this->lang->line('high_price');?></option>
                                                        <option value="lp"><?= $this->lang->line('low_price');?></option>
                                                    </select>
                                                </li>
                                                <li>
                                                    <div class="view-box">
                                                        <span><?= $this->lang->line('view');?></span>
                                                        <a href="#" class="list active"></a>
                                                        <a href="#" class="grid"></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="filter-head">
                                    <h3>Upcoming live auction</h3>
                                </div> -->
                                <span id="load-live-data-here"></span>   
                            </div>

                            <!-- ///// end -->
                            <div class="tab-pane fade show list-view" id="close" role="tabpanel" aria-labelledby="close-tab">
                                <div class="filter-head">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h3><?= $this->lang->line('search_result');?> (<span id="close_count">0</span>)</h3>
                                        </div>
                                        <div class="col-md-8">
                                            <ul class="h-list">
                                                <!-- <li>
                                                    <div class="search">
                                                        <input type="text" class="form-control" placeholder="Search">
                                                        <button></button>
                                                    </div>
                                                </li> -->
                                                <li>
                                                    <select class="selectpicker" title="Sort by type" id="close_sort_by">
                                                        <option value=""><?= $this->lang->line('latest');?></option>
                                                        <option value="hp"><?= $this->lang->line('high_price');?></option>
                                                        <option value="lp"><?= $this->lang->line('low_price');?></option>
                                                    </select>
                                                </li>
                                                <li>
                                                    <div class="view-box">
                                                        <span><?= $this->lang->line('view');?></span>
                                                        <a href="#" id="list" class="list active"></a>
                                                        <a href="#" class="grid"></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <span id="load-close-data-here"></span>
                            </div>
                        </div>
                    </div>
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
                            <h3><?= $this->lang->line('download_app');?><br><?= $this->lang->line('ios_android');?></h3>
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
                                        <img src="<?= NEW_ASSETS_IMAGES?>/play-store-icon.png" alt="">
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
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
    $( document ).ready(function() {
        $('#load-more').click();
        // var count = $('#count').val();
        $('#list').click();

        // view more and view less functionality
        $(".view-link").on("click", function(e) {
            e.preventDefault();
            $(this).text($(this).text() == "<?= $this->lang->line('view_more'); ?>" ? "<?= $this->lang->line('view_less'); ?>" : "<?= $this->lang->line('view_more'); ?>");
            $(this).parent().find("ul").toggleClass("show-full")
        });

        $('#make').selectpicker();
        $('#model').selectpicker();
        selectSort('#make');
        $("#make").selectpicker('refresh');

        // var options = $('#make option');
        // var arr = options.map(function(_, o) {
        //     return {
        //         t: $(o).text(),
        //         v: o.value
        //     };
        // }).get();
        // arr.sort(function(o1, o2) {
        //     return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0;
        // });
        // options.each(function(i, o) {
        //     console.log(i);
        //     o.value = arr[i].v;
        //     $(o).text(arr[i].t);
        // });
        
    });
    $('#load-more').on('click', function(event){
        event.preventDefault();

        var btn = $(this);
        var offset = btn.data('offset');
        var auctionID = '<?= $auction_id; ?>';
        var category_id = '<?= $category_id; ?>';
        var search = $('#search').val();
        var sort = $('#sort_by').val();
        //var next = offset + 3;

        $.ajax({
            method: "POST",
            url: "<?= base_url('auction/OnlineAuction/ai_load_more'); ?>",
            data: {'offset':offset, 'auction_id':auctionID, 'search':search, 'sort':sort, [token_name] : token_value},

            beforeSend: function(){
                $(".overlay").show();
            },
            success: function(data){
                //alert(data);
                console.log(data);
                $('.overlay').hide();
                var response = JSON.parse(data);
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
                  $('#load-here').append("<h1><?= $this->lang->line('no_record'); ?></h1>");
                  btn.hide();
                }
            }
        });

        $.ajax({
            method: "POST",
            url: "<?= base_url('auction/OnlineAuction/live_auction_items_list'); ?>",
            data: {'category_id':category_id, 'search':search, 'sort':sort, [token_name] : token_value},
            beforeSend: function(){
                $(".overlay").show();
            },
            success: function(data){
                //alert(data);
                $('.overlay').hide();
                var response = JSON.parse(data);
                // console.log(response);
                if(response.status == 'success'){
                  $('#load-live-data-here').append(response.items);
                  // $('#count').text(response.total);
                  $('#live_count').text(response.total);
                } else {
                  $('#load-live-data-here').append("<h1><?= $this->lang->line('no_record'); ?></h1>");
                }
            }
        });

        $.ajax({
            method: "POST",
            url: "<?= base_url('auction/OnlineAuction/close_auction_items_list'); ?>",
            data: {'category_id':category_id,'auction_id':auctionID, 'search':search, 'sort':sort, [token_name] : token_value},
            beforeSend: function(){
                $(".overlay").show();
            },
            success: function(data){
                //alert(data);
                $('.overlay').hide();
                var response = JSON.parse(data);
                console.log(response);
                if(response.status == 'success'){
                  $('#load-close-data-here').append(response.items);
                  // $('#count').text(response.total);
                  $('#close_count').text(response.total);
                } else {
                    if (response.msg == 'hide_auction') {
                        $('.close-auction-tab').hide();
                    }
                    $('#load-close-data-here').append("<h1><?= $this->lang->line('no_record'); ?></h1>");
                }
            }
        });
    });

    $('#online_sort_by').change(function(){
        $('#applyFilters').click();
    });
    $('#live_sort_by').change(function(){
        $('#applyFilters').click();
    });
    $('#close_sort_by').change(function(){
        $('#applyFilters').click();
    });


    $('#applyFilters').on('click', function(event){
        event.preventDefault();
        var online_sort_by = $('#online_sort_by').val();
        var live_sort_by = $('#live_sort_by').val();
        var close_sort_by = $('#close_sort_by').val();

        var form = $(this).closest('form').serializeArray();
        form[form.length] = { name: [token_name] , value: token_value};
        form[form.length] = { name: "online_sort_by", value: online_sort_by };
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
                    $('#load-here').html("<h1><?= $this->lang->line('no_record'); ?></h1>");
                }
                $('#count').text(response.total);
                if(response.btn == false){
                    $('#load-more').hide();
                }
            }
        });

        
        form[form.length-1] = { name: "live_sort_by", value: live_sort_by };
        form[form.length] = { name: "category_id", value: "<?= $category_id; ?>" };
        $.ajax({
            method: "POST",
            url: "<?= base_url('auction/OnlineAuction/apply_filters_live'); ?>",
            data: form,
            beforeSend: function(){
                $(".overlay").show();
            },  
            success: function(data){
                $(".overlay").hide();
                //console.log(data);
                var response = JSON.parse(data);
                if(response.status == 'success'){
                    $('#load-live-data-here').html(response.items);
                    $('#live_count').text(response.total);
                }else{
                    $('#load-live-data-here').html("<h1><?= $this->lang->line('no_record'); ?></h1>");
                    $('#live_count').text('0');
                }
                if(response.btn == false){
                    $('#load-more').hide();
                }
            }
        });


        form[form.length-2] = { name: "close_sort_by", value: close_sort_by };
        $.ajax({
            method: "POST",
            url: "<?= base_url('auction/OnlineAuction/close_auction_items_list'); ?>",
            data: form,
            beforeSend: function(){
                $(".overlay").show();
            },  
            success: function(data){
                $(".overlay").hide();
                //console.log(data);
                var response = JSON.parse(data);
                if(response.status == 'success'){
                    $('#load-close-data-here').html(response.items);
                }else{
                    $('#load-close-data-here').html("<h1><?= $this->lang->line('no_record'); ?></h1>");
                }
                $('#close_count').text(response.total);
                if(response.btn == false){
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
    //         data: {'item_id':itemID, 'auction_id':auction_id, 'auction_item_id':auction_item_id, [token_name] : token_value},
    //         success: function(msg){
    //             console.log(msg);
    //             //var response = JSON.parse(msg);
    //             if(msg == 'do_heart'){
    //                 heart.find('.fa-heart-o').addClass('fa-heart').removeClass('fa-heart-o');  
    //                 heart.text('<?= $this->lang->line('remove_list');?>');     
    //             }
    //             if(msg == 'remove_heart'){
    //                 heart.find('.fa-heart').addClass('fa-heart-o').removeClass('fa-heart');
    //                 heart.text('<?= $this->lang->line('add_list');?>');     
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
            data: {offset:offset, auction_id:auction_id, search:search, [token_name] : token_value},
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

    $('#make').on('change', function() {
        var make_id = $(this).val();
          $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "auction/OnlineAuction/get_model_options",    
            data:{ make_id:make_id, [token_name] : token_value},
            success: function(data) {
                objdata = $.parseJSON(data);
                if(objdata.msg == 'success')
                {
                    // $('.make_case').show();
                    $('#model').attr('disabled',false);
                    $('#model').html(objdata.data);
                    $('#model').selectpicker('refresh');

                }
                else
                {
                    // $('.make_case').hide();
                    $('#model').attr('disabled',false);
                    $('#model').html('<option value=""><?= $this->lang->line("model_not_available"); ?></option>');
                    $('#model').selectpicker('refresh');
                }
            }
        });
     });

</script>