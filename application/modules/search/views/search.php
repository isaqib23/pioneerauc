
<style type="text/css">
    .bg_blink {
        background-color: #fa8500;
        color: #fff;
    }
</style>
<script src="<?= NEW_ASSETS_USER; ?>/new/js/jquery.countdown.js"></script>
<div class="main-wrapper vehicles">
    <div class="container">
        <div class="products-listing">
            <div class="left-bar filter-respnsive">
                <h2 class="filter-title"><?= $this->lang->line('filter'); ?></h2>
                <h2 class="filter-title1"><?= $this->lang->line('filter'); ?></h2>
                <div class="mobile_filter_head show-on-1000">
                    <ul class="h-list justify-content-between">
                        <li class="close-filter">
                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.30566 8.49746L15.2775 2.53027C15.6385 2.16934 15.6385 1.58574 15.2775 1.22715C14.9166 0.866211 14.333 0.866211 13.9744 1.22715L8.0002 7.19199L2.02598 1.2248C1.66504 0.863867 1.08145 0.863867 0.722852 1.2248C0.361914 1.58574 0.361914 2.16934 0.722852 2.52793L6.69473 8.49512L0.722852 14.4646C0.361914 14.8256 0.361914 15.4092 0.722852 15.7678C0.90332 15.9482 1.14004 16.0373 1.37441 16.0373C1.61113 16.0373 1.84551 15.9482 2.02598 15.7678L8.0002 9.80059L13.9744 15.7701C14.1549 15.9506 14.3893 16.0396 14.626 16.0396C14.8627 16.0396 15.0971 15.9506 15.2775 15.7701C15.6385 15.4092 15.6385 14.8256 15.2775 14.467L9.30566 8.49746Z" fill="black" />
                            </svg>
                        </li>
                        <li>
                            <?= $this->lang->line('filter'); ?>
                        </li>
                        <li>
                            <a href="#" id="clear"> <?= $this->lang->line('clear'); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="filter">
                    <form id="filtersForm">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="categoryId" value="<?= $categoryId; ?>">

                        <div class="form-group rtl-trans1">
                            <label><?= $this->lang->line('search'); ?></label>
                            <input type="text" name="searchTerm" value="<?= $searchTerm; ?>" oninput="this.value=this.value.replace(/[^a-zA-Z0-9 ]/g,'');" pattern="[^'\x22]+" title="no quotes!" maxlength="50" placeholder="<?= $this->lang->line('search'); ?>..." class="form-control">
                        </div>

                        <div class="form-group">
                            <label><?= $this->lang->line('price'); ?></label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" name="itemPriceMin" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="6" placeholder="<?= $this->lang->line('min'); ?>" class="form-control">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="itemPriceMax" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="6" placeholder="<?= $this->lang->line('max'); ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?= $this->lang->line('year'); ?></label>
                            <div class="row">
                                <div class="col-6">
                                    <select name="itemYearFrom" id="itemYearFrom" class="selectpicker">
                                        <option class="abcde" value="" selected="selected"><?= $this->lang->line('from'); ?></option>
                                        <?php $year = date('Y'); ?>
                                        <?php for ($i = 0; $i <= 50; $i++) { ?>
                                            <option class="abcde" value="<?= $year - $i ?>"><?= $year - $i ?></option>
                                        <?php }; ?>
                                    </select>
                                </div>
                                <div class="col-6">

                                    <select name="itemYearTo" id="itemYearTo" class="selectpicker">
                                        <option value="" class="abcde" selected="selected"><?= $this->lang->line('select_yearTo'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php if ($selectedCategory['include_make_model'] == 'yes') { ?>
                            <?php if (isset($itemMakes) && !empty($itemMakes)) { ?>
                                <div class="form-group rtl-trans">
                                    <label><?= $this->lang->line('make'); ?></label>
                                    <select name="itemMake" id="itemMake" class="selectpicker">
                                        <option value="" selected="selected"><?= $this->lang->line('select_make'); ?></option>
                                        <?php foreach ($itemMakes as $key => $itemMake) {
                                            $title = json_decode($itemMake['title']);
                                        ?>
                                            <option value="<?= $itemMake['id']; ?>"><?= $title->$language; ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div>

                                <div class="form-group rtl-trans">
                                    <label><?= $this->lang->line('model'); ?></label>
                                    <select name="itemModel" id="itemModel" class="selectpicker">
                                        <option value=""><?= $this->lang->line('select_model'); ?></option>
                                    </select>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label><?= $this->lang->line('odometer'); ?></label>
                                <div class="row">
                                    <div class="col-6">
                                        <select name="itemMilageMin" id="itemMilageMin" class="selectpicker" title="<?= $this->lang->line('from'); ?>">
                                            <!-- <option value="0">To</option> -->
                                            <option value="0">0</option>
                                            <option value="1000">1000</option>
                                            <option value="2000">2000</option>
                                            <option value="3000">3000</option>
                                            <option value="4000">4000</option>
                                            <option value="5000">5000</option>
                                            <option value="5000">50000</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select name="itemMilageMax" id="itemMilageMax" class="selectpicker" title="<?= $this->lang->line('to'); ?>">
                                            <!-- <option value="0">From</option> -->
                                            <option value="100000">100000</option>
                                            <option value="200000">200000</option>
                                            <option value="300000">300000</option>
                                            <option value="400000">400000</option>
                                            <option value="500000">500000</option>
                                        </select>
                                    </div>
                                </div>
                                <ul class="circle-radio">
                                    <li class="radio">
                                        <label>
                                            <input type='radio' name="milageType" value="km">
                                            <span for="km"><?= $this->lang->line('km'); ?></span>
                                        </label>
                                    </li>
                                    <li class="radio">
                                        <label>
                                            <input type='radio' name="milageType" value="miles">
                                            <span for="miles"><?= $this->lang->line('miles'); ?></span>
                                        </label>
                                    </li>
                                </ul>
                            </div>

                            <div class="form-group">
                                <label><?= $this->lang->line('specs'); ?></label>
                                <ul class="circle-radio">
                                    <li class="radio">
                                        <label>
                                            <input type="radio" name="specification" value="GCC">
                                            <?= $this->lang->line('gcc'); ?>
                                        </label>
                                    </li>
                                    <li class="radio">
                                        <label>
                                            <input type="radio" name="specification" value="IMPORTED">
                                            <?= $this->lang->line('imported'); ?>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        <?php } ?>



                        <div class="form-group rtl-trans2">
                            <label> <?= $this->lang->line('lot'); ?> #</label>
                            <input type="text" name="itemLot" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="<?= $this->lang->line('enter_lot'); ?>" class="form-control">
                        </div>

                        <?php
                        if (isset($itemCategoryFields) && !empty($itemCategoryFields)) { ?>
                            <div class="advance-filter">
                                <h3 class="filter-down"><?= $this->lang->line('advance_filter'); ?></h3>
                                <div class="toggle-filter" style="display: none;">

                                    <?php
                                    if ($itemCategoryFields) :
                                        $k = 0;
                                        foreach ($itemCategoryFields as $key => $field) :
                                            $k++;
                                            $j = 0;
                                            if (!in_array($k, [14, 15, 16])) :
                                    ?>
                                                <div class="box active ">
                                                    <div class="head">
                                                        <?= $this->template->make_dual($field['label']); ?>
                                                    </div>
                                                    <div class="body">
                                                        <ul>
                                                            <?php
                                                            if (in_array($field['type'], ['select', 'radio-group', 'checkbox-group'])) :
                                                                $values = json_decode($field['values'], true);
                                                                foreach ($values as $key => $value) :
                                                                    $j++;
                                                            ?>
                                                                    <li class="checkbox red">
                                                                        <label>
                                                                            <input type="checkbox" name="fields[<?= $field['id']; ?>][]" value="<?= $value['value']; ?>">
                                                                            <span><?= $this->template->make_dual($value['label']); ?></b></span>
                                                                        </label>
                                                                    </li>
                                                                <?php endforeach;
                                                            elseif (in_array($field['type'], ['text', 'textarea'])) :
                                                                ?>
                                                                <li class="checkbox red">
                                                                    <input type="<?= $field['type']; ?>" id="<?= $field['id']; ?>" name="fields[<?= $field['id']; ?>]" placeholder="<?= $this->template->make_dual($field['placeholder']); ?>" oninput="this.value=this.value.replace(/[^a-zA-Z0-9. ]/g,'');" maxlength="50" class="form-control">
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                        <?php
                                                        if (in_array($field['type'], ['select', 'radio-group', 'checkbox-group']) && $j > 6) : ?>
                                                            <a href="#" class="view-link"><?= $this->lang->line('view_more'); ?></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                    <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="button-row">
                            <input type="hidden" name="typeCss" value="list" id="typeCss">
                            <button type="button" id="applyFilter" class="btn btn-primary col-md-6"><?= $this->lang->line('apply_filter'); ?></button>
                            <button type="reset" id="reset" class="btn btn-primary col-md-5"><?= $this->lang->line('clear'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="right-bar">

                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>

                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>

                <div class="pager">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= $selectedCategoryName; ?></li>
                        </ol>
                    </nav>
                </div>
                <h1 class="page-title"><?= $selectedCategoryName ?></h1>
                <div class="auction-detail">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link " id="online-tab" data-toggle="tab" data-auction="online" href="#online" role="tab" aria-controls="online" aria-selected="true"><?= $this->lang->line('online_auction'); ?></a>
                        </li>
                        <li class="nav-item wll">
                            <a class="nav-link" id="live-tab" data-toggle="tab" data-auction="live" href="#live" role="tab" aria-controls="live" aria-selected="false"><?= $this->lang->line('live_hall_auction'); ?></a>
                        </li>
                        <?php if (isset($hasClosedAuctions) && ($hasClosedAuctions > 0)) { ?>
                            <li class="nav-item">
                                <a class="nav-link" id="closed-tab" data-toggle="tab" data-auction="closed" href="#closed" role="tab" aria-controls="closed" aria-selected="false"><?= $this->lang->line('close_auction'); ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content" id="myTabContent" style="position:relative" ;>


                        <div class="tab-pane fade show active" id="online" role="tabpanel" aria-labelledby="online-tab">

                            <div class="online-auction" id="onlineResults">

                            </div>
                        </div>

                        <div class="tab-pane fade " id="live" role="tabpanel" aria-labelledby="live-tab">
                            <div class="live-auction" id="liveResults"></div>
                        </div>

                        <div class="tab-pane fade" id="closed" role="tabpanel" aria-labelledby="closed-tab">
                            <div class="online-auction" id="closedResults"></div>
                        </div>

                    </div>



                </div>

            </div>

        </div>

    </div>

</div>


<div class="modal fade login-modal style-2" id="auctionStartAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="increment-modal">
                    <div class="img">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/error-popup.png">
                    </div>
                    <p><?= @$popup_message; ?></p>
                    <div class="button-row">
                        <button class="btn btn-primary" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var token_name = '<?= $this->security->get_csrf_token_name(); ?>';
    var token_value = '<?= $this->security->get_csrf_hash(); ?>';

    var resetReload = true;

    // $("#itemMake").on("change", function() {

    // });

    $(document).ready(function() {

        var auctionType = 'online';
        <?php if (isset($_GET['auctionType']) && !empty($_GET['auctionType'])) { ?>
            auctionType = '<?= $_GET["auctionType"] ?>';
        <?php } ?>
        var eTab = $('#myTab a[href="#' + auctionType + '"]');
        eTab.tab('show');

        if (location.hash === "#live")
            $("#live-tab").trigger("click")
        else
            $("#applyFilter").click();
    });

    $("#itemMake").on("change", function(event) {
        event.preventDefault();
        var makeId = $(this).val();
        console.log(makeId)
        var language = "<?= $language; ?>";

        $.ajax({
            url: "<?= base_url('search/modelsByMake'); ?>",
            type: 'POST',
            data: {
                'makeId': makeId,
                "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>"
            },
            success: function(response) {
                var models = JSON.parse(response);
                $('#itemModel').html('');
                if (models.status) {
                    /* $("#itemModel").append('<option value=""><?= $this->lang->line('all_model'); ?></option>').selectpicker('refresh');
                     $.each(models.data, function (idx, obj) {
                         var modelTitle = JSON.parse(obj.title); 
                         $("#itemModel").append('<option value="' + obj.id + '">' + modelTitle[language] + '</option>').selectpicker('refresh');
                     });*/

                    $("#itemModel").append('<option value=""><?= $this->lang->line('all_model'); ?></option>')
                    $.each(models.data, function(idx, obj) {
                        var modelTitle = JSON.parse(obj.title);
                        $("#itemModel").append('<option value="' + obj.id + '">' + modelTitle[language] + '</option>')
                    });
                    $("#itemModel").selectpicker('refresh');

                } else {
                    $("#itemModel").append('<option value=""><?= $this->lang->line('select_model'); ?></option>').selectpicker('refresh');
                }
            }
        });
    });

    $("#itemYearFrom").on("change", function(event) {
        event.preventDefault();
        var from = $(this).val();
        var language = "<?= $language; ?>";


        $('#itemYearTo').html('');
        /* if (from) {
             var i = (from-1);

             while (i > 1970) {
              $("#itemYearFrom").append('<option value="' + i + '">' + i + '</option>')
               i--;
             }
             $("#itemYearFrom").selectpicker('refresh');

         } else {
             $("#itemYearFrom").append('<option value=""><?= $this->lang->line('select_yearTo'); ?></option>').selectpicker('refresh');
         }*/
        if (from) {
            var y = '<?= date("Y"); ?>';

            for (i = y; i >= from; i--) {
                $("#itemYearTo").append('<option class="abcde" value="' + i + '">' + i + '</option>');
            }
            $("#itemYearTo").selectpicker('refresh');

        } else {
            $("#itemYearTo").append('<option value=""><?= $this->lang->line('select_yearTo'); ?></option>').selectpicker('refresh');
        }


    });

    $('.nav-tabs a').on('shown.bs.tab', function(event) {
        var auctionType = $(event.target).data('auction');
        resetReload = false
        $("#reset").trigger("click")
        $("#filtersForm .selectpicker").each(function() {
            if ($(this).attr("id") !== "itemYearTo") {
                const firstOptionVal = $(this).find("option").first().val()
                $(this).val(firstOptionVal)
            } else if ($(this).attr("id") === "itemModel") {
                $(this).html("<option value=''>Select Model</option>")
            } else {
                $(this).html("<option value=''>Select to</option>")
            }
            $(this).selectpicker("refresh")
        });
        resetReload = true;
        getAuctionItems(auctionType);
    });

    $('#applyFilter').on('click', function(event) {
        event.preventDefault();
        var auctionType = $('.nav-tabs a.active').data('auction');
        getAuctionItems(auctionType);
    });

    function getAuctionItems(auctionType, offset = 0) {
        if (!auctionType) {
            return false;
        }

        var sortBy = $(".tab-pane.active #sortBy").val();
        //console.log(sortBy);

        var filters = $('#filtersForm').serializeArray();
        filters.push({
            'name': 'auctionType',
            'value': auctionType
        });
        filters.push({
            'name': 'offset',
            'value': offset
        });
        filters.push({
            'name': 'sortBy',
            'value': sortBy
        });

        //console.log(sortBy);

        $.ajax({
            method: "POST",
            url: "<?= base_url('search/Search/getAuctionItems'); ?>",
            data: filters,
            beforeSend: function() {
                //$(".overlay").show();
            },
            success: function(responseData) {
                //  console.log(responseData);
                //$(".overlay").hide();
                var outputPane = '#' + auctionType + 'Results';
                var response = JSON.parse(responseData);
                if (response.status == 'success') {
                    $(outputPane).html(response.items)
                        .ready(() => {
                            $('.tab-pane.active #sortBy').val(sortBy);
                            $(".tab-pane.active #" + response.typeCss).trigger('click');
                        })

                    // alert("#" + response.typeCss)

                } else {
                    // if(response.reQ==1){
                    //     var notAjax = '<?= $this->lang->line("no_record_new"); ?>';
                    // }else{
                    var notAjax = '<?= $this->lang->line("no_record"); ?>';
                    // }

                    $(outputPane).html("<div class='norecordfound'><img src='/assets_admin/images/comingsoonimg.png' align='center' /><h1 class='no-record'><?= $this->lang->line("no_record"); ?></h1></div>");
                    console.log("<img src='/assets_admin/images/comingsoonimg.png' align='center' /><h1 class='no-record'><?= $this->lang->line("no_record_new"); ?></h1>")

                    $('.ntfnd').html("<img src='/assets_admin/images/comingsoonimg2.png' align='center' /><h1 class='no-record'><?= $this->lang->line("no_record_new"); ?></h1>");
                }
                //$( "#"+response.typeCss ).trigger( 'click' );
                // $("#" + $('#typeCss').val()).trigger('click');
                if (auctionType === "live")
                    history.pushState("", {}, "#" + auctionType)
                else if (location.hash === "#live")
                    history.pushState("", {}, location.href.replace(location.hash, ""))
            },
            complete: function() {
                $(document).trigger("actionLoaded")
            }
        });
    }

    $(".alert-dismissible").fadeOut(10000);
    $(function() {
        let initContent = true;
        $("button#reset").click(function() {
            //$("#filtersForm")[0].reset()
            resetReload && location.reload();
        });

        $('#online-tab').on('shown.bs.tab', function(e) {
            $(document).on("actionLoaded", function() {
                if (initContent && $(".tab-pane.active .norecordfound").length) {
                    $("#live-tab").trigger("click")
                } else {
                    initContent = false
                }
            })
        })

        $('#live-tab').on('shown.bs.tab', function(e) {
            $(document).on("actionLoaded", function() {
                if (initContent && $(".tab-pane.active .norecordfound").length) {
                    $("#online-tab").trigger("click")
                }
                initContent = false
            })
        })

        // let intervalData = setInterval(() => {
        //     if ($(".tab-pane.active #products").length) {
        //         clearInterval(intervalData)
        //     } else if ($(".tab-pane.active .norecordfound").length) {
        //         clearInterval(intervalData)
        //         if (location.hash !== "live") {
        //             $("#live-tab").trigger("click")

        //             var liveInterval = setInterval(() => {
        //                 if ($(".tab-pane#live.active #products").length) {
        //                     clearInterval(liveInterval)
        //                 } else if ($(".tab-pane.active .norecordfound").length) {
        //                     clearInterval(liveInterval)
        //                     $("#online-tab").trigger("click")

        //                     $('#online-tab').on('shown.bs.tab', function(e) {
        //                         $(".tab-pane#live.active").removeClass("active show")
        //                     });
        //                 }
        //             });
        //         } else {
        //             $("#online-tab").trigger("click")
        //         }
        //     }
        // })
    });
</script>

