<script  src="<?= NEW_ASSETS_USER;?>js/datatableCustom.js"></script> 
<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<div class="main-wrapper account-page history-page">
  <div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home')?></a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new')?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('history_new')?>History</li>
    </ol>
    <h2><?= $this->lang->line('my_account_new')?></h2>
    <div class="account-wrapper">
      <?= $this->load->view('template/new/template_user_leftbar') ?>
      <div class="right-col">
        <h3><?= $this->lang->line('history_new')?></h3>
        <h4 class="border-title"><?= $this->lang->line('my_bids_new')?></h4>
        <table class="customtable datatable  table-responsive" id="bidDatatable" cellpadding="0" cellspacing="0" >
            <thead>
                <th class="# hide-on-768">#</th>
                <th class="bid_time hide-on-768" width="24%" data-orderable="false"><?= $this->lang->line('bid_time')?></th>
                <th class="name" width="38%"><?= $this->lang->line('name_of_item')?></th>
                <th class="bid_amount hide-on-768"><?= $this->lang->line('your_bid_new')?></th>
                <th class="last_bid hide-on-768"><?= $this->lang->line('last_bid');?></th>
                <th class="status" width="18%"><?= $this->lang->line('status_new')?></th>
            </thead>
            <tbody>
                <?php $j = 0; foreach ($bids_data as $key => $bid) : $j++; ?>
                  <tr>
                    <td class="hide-on-768"><?= $j; ?></td>
                    <td class="hide-on-768">
                        <p class="date-time"><span style="margin-right: 0px;">
                        <?php 
                        $bidTime = strtotime($bid['bid_time']);
                        if($language == 'arabic'){
                            $fmt = datefmt_create("ar_AE", IntlDateFormatter::LONG, IntlDateFormatter::MEDIUM, 'Asia/Dubai', IntlDateFormatter::GREGORIAN);
                            echo datefmt_format( $fmt , $bidTime);
                        }else{
                            echo date('d/m/Y h:i A', $bidTime); 
                            // echo date('dS M Y H:i', $bidTime); 
                        }
                        ?>
                        <?//= date('d/m/Y h:i A', strtotime($bid['bid_time'])); ?>
                            
                        </span></p>
                    </td>
                    <td>
                      <div class="item">
                        <div class="image">
                            <?= $bid['image'] ?>
                            <!-- <img src="<?= NEW_ASSETS_USER ?>/new/images/bid-item2.jpg" alt=""> -->
                        </div>
                        <div class="desc">
                            <h6><?= json_decode($bid['name'])->$language; ?></h6>
                        </div>
                      </div>
                    </td>
                    <td class="hide-on-768"><?= number_format($bid['bid_amount']); ?></td>
                    <td class="hide-on-768"><?= number_format($bid['last_bid']); ?></td>
                    <td class=""><?= $bid['status_value']; ?></td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    </div>
    <?= $this->load->view('template/new/faq_footer') ?>
  </div>
</div>

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script type="text/javascript">      

$(document).ready(function(){
    $('#bidDatatable').DataTable().destroy();
    $('#bidDatatable').DataTable( {
        "order": [[ 0, "desc" ]],
        "language": {
            "paginate": {
                "previous": '<span class="material-icons">keyboard_arrow_left</span>',
                "next": '<span class="material-icons">keyboard_arrow_right</span>'
            }
        }
    } );
} );
function itemExpire(e){
    var elem = $(e);
    if (elem.attr('href') == '#') {
        PNotify.removeAll();
        new PNotify({
            type: 'error',
            addclass: 'custom-error',
            text: "<?= $this->lang->line('item_is_no_longer_available'); ?>",
            styling: 'bootstrap3',
            title: '<?= $this->lang->line('error'); ?>'
        });
    }
};

</script>
