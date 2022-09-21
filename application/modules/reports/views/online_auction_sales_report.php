<style> .pull-right{margin-right: 22px !important} </style>
<div class="page-title">
  <div class="title_left">
    <h3>Reports Center<small></small></h3>
  </div>
  <div class="title_right"></div>
</div>

<?php if($this->session->flashdata('reports_error')){ ?>
  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong> <?= $this->session->flashdata('reports_error'); ?>
  </div>
<?php } ?>

<?php if($this->session->flashdata('reports_success')){ ?>
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success!</strong> <?= $this->session->flashdata('reports_success'); ?>
  </div>
<?php } ?>

<?php //print_r($data); ?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Online/Closed Auction Sales Report <small></small></h2>
        <div class="nav navbar-right">
          <!-- <a href="<?//= base_url('admin/projects/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Projects</a> -->
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">


        <form method="post" id="rptfrm">

          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />

          <div class="form-group col-md-12 col-sm-12col-xs-12">
            <select name="auctionId" id="auctionId" placeholder="Select Live Auction" class="form-control col-md-12 col-xs-12">
              <option></option>
            </select>
            <span class="valid-error text-danger auctionId-error"></span>
          </div>

          <div class="form-group col-md-3 col-sm-6 col-xs-6">
              <input type="submit" id="filter" class="btn btn-success" value="Filter" />
          </div>

        </form>


      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_content" id="showReport"></div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets_admin/js/zee.js');?>"></script>

<script>

var firstEmptySelect = true;
  function formatSelect(result) {
    if (!result.id) {
      if (firstEmptySelect) {
          //console.log('showing row');
          // firstEmptySelect = false;
          return '<div class="row">' +
                  '<div class="col-xs-3"><b>Auction</b></div>' +
                  '<div class="col-xs-3"><b>Code</b></div>' +
                  '<div class="col-xs-3"><b>Start</b></div>' +
                  '<div class="col-xs-3"><b>End</b></div>' +
                  '</div>';
      } else {
        //console.log('skipping row');
        return false;
      }
    }
    return '<div class="row">' +
            '<div class="col-xs-3">' + result.text + '</div>' +
            '<div class="col-xs-3">' + result.registration_no + '</div>' +
            '<div class="col-xs-3">' + result.start_time + '</div>' +
            '<div class="col-xs-3">' + result.expiry_time + '</div>' +
            '</div>';
  }

  $('#auctionId').select2({
      ajax: {
          url: '<?= base_url();?>reports/onlineAuctionsSelect2',
          type: 'post',
          delay: '250',
          cache: false,
          minimumInputLength: 1,
          data: function (params) {
              return {
                  search: params.term,
                  [token_name] : token_value
              };
          },
          processResults: function (res, params) {
              return {
                  results: JSON.parse(res)
              };
          },
      },
      // placeholder: 'Select User',
      width: '200px',
      templateResult: formatSelect,
      templateSelection: formatSelect,
      escapeMarkup: function(m) { return m; }
  });

//search filters
$("#filter").on("click", function(event){
  event.preventDefault();

  selectedInputs = ['auctionId'];
  var validation = false;
  validation = validateFields(selectedInputs);

  if(validation){
    var filters = $(this).closest("form").serializeArray();

    $.ajax({
      url: "<?php echo base_url('reports/online_auction_sales_report_process'); ?>",
      type: "post",
      data: filters,
      success: function(result){
        //console.log(result);
        $("#showReport").html(result);
      }
    });
  }

});
</script>