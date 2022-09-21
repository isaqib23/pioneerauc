<script  src="<?= NEW_ASSETS_USER;?>js/datatableCustom.js"></script>
<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg">
  <div class="row custom-wrapper dashboard">
    <?= $this->load->view('template/template_user_leftbar') ?>
    <div class="right-col">
      <div class="row align-items-center">
        <div class="col-sm-4">
          <h1 class="section-title text-left"><?= $this->lang->line('account');?></h1>
        </div>
        <div class="col-sm-8">
          <p><?= $this->lang->line('account_status');?><span><?= $this->lang->line('active'); ?></span></p>
        </div>
      </div>
      <ul class="account-balance">
        <li>
          <h3>
            <?php echo (!empty($balance)) ? number_format($balance, 0, ".", ",") : '0';?><span><?= $this->lang->line('available_balance');?></span>
          </h3>
          <a href="<?= base_url('deposit');?>"><?= $this->lang->line('increase_balance');?> +</a>
        </li>
        <li>
          <h3>
            <?php echo (!empty($bids['count'])) ? $bids['count'] : '0'; ?><span><?= $this->lang->line('my_bids');?></span>
          </h3>
        </li>
        <li>
          <h3>
            <?php $limit = (isset($percentage_settings) && !empty($percentage_settings['value'])) ? $percentage_settings['value'] : '1'; ?>
             <?= number_format($balance*$limit, 0, ".", ","); ?>
             <span><?= $this->lang->line('bid_limit');?></span>
          </h3>
          <a href="<?= base_url('deposit');?>"><?= $this->lang->line('limit');?> +</a>
        </li>
      </ul>
      <div class="deposit-wrapper">
        <h4><?= $this->lang->line('my_bids_cap');?></h4>
        <table id="dashboard_datatable_id" class="table_id display">
          <thead>
            <tr>
              <th class="id"><?= $this->lang->line('bid_time');?></th>
              <th class="image" data-orderable="false"></th>
              <th class="name"><?= $this->lang->line('bid_name');?></th>
              <th class="bid_amount"><?= $this->lang->line('your_bid');?></th>
              <th class="last_bid"><?= $this->lang->line('last_bid');?></th>
              <th class="status" data-orderable="false"><?= $this->lang->line('bid_status');?></th>
              <!-- <th class="action" data-orderable="false"></th> -->
            </tr>
          </thead>
          <!-- <tbody>
            <tr>
              <td>1</td>
              <td>Card</td>
              <td>12000</td>
              <td>DR</td>
              <td>2020-07-06</td>
              <td>Approved</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Card</td>
              <td>15000</td>
              <td>DR</td>
              <td>2020-07-08</td>
              <td>Approved</td>
            </tr>
          </tbody> -->
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script type="text/javascript">      

$(document).ready(function(){

  // $('#dashboard_datatable_id').DataTable().Clear();
  $('#dashboard_datatable_id').DataTable().destroy();

      let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
      let exportOptionsSelect = { modifier: { selected: null} };
      let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
      let dt1 = customeDatatable({
      // index zero must be csrf token
      '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
      'div' : '#dashboard_datatable_id',
      'url' : '<?= base_url()?>customer/get_bids',
      'orderColumn' : 0,
      'iDisplayLength' : 10,
      // 'dom' : 'fr<t><"bottom"lip><"clear">', // r<t><"bottom"lip><"clear">
      "dom": '<"top"i>rt<"bottom"flp>',
      'columnCustomeTypeTarget' : 2,
      'defaultSearching': false,
      'responsive': true,
      'rowId': 'id',
      'stateSave': false,
      'lengthMenu': [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]], // [5, 10, 25, 50, 100], [5, 10, 25, 50, 100]
      'order' : 'desc',
      'selectStyle' : 'multi',
      'collectionButtons' : dtButtons,
      // 'exportOptionsSelect' : exportOptionsSelect,
      'processing' : true,
      'serverSide' : true,
      'language' : '<?= ($language == "english") ? "" : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json"; ?>',
      'processingGif' : '<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <img src="<?= NEW_ASSETS_IMAGES; ?>load.gif"> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>',
      'buttonClassName' : 'btn-sm',
      'columnCustomType' : '',
      'customFieldsArray': [],
      'dataColumnArray': [{'data':'bid_time'},{'data':'image'},{'data':'name'},{'data':'bid_amount'},{'data':'last_bid'},{'data':'status'}]
      });
  });

    $('#bids_cat').change(function(){
    dt1.draw();
    });

    $('#bids').keyup(function(){
    dt1.draw();
    });

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