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

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Inventory Report<small></small></h2>
        <div class="nav navbar-right">
          <!-- <a href="<?//= base_url('admin/projects/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Projects</a> -->
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">


        <form method="post" id="rptfrm" action="">

          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <select name="make_id" id="make_id" onchange="model_ajax_list(this);" class="form-control col-md-7 col-xs-12">
              <option selected value="all">All Makes</option>
              <?php
              if($item_makes){
                foreach ($item_makes as $key => $item_makes_list) {
                  $name = json_decode($item_makes_list['title']);
                  ?>
                    <option value="<?= $item_makes_list['id']; ?>"><?= $name->english; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            <span class="valid-error text-danger make_id-error"></span>
          </div>

           <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <select name="model_id" id="model_list" class="form-control col-md-7 col-xs-12">
              <option selected value="all">All Models</option>
            </select>
            <span class="valid-error text-danger model_id-error"></span>
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
      <div class="x_content">
        <table id="dt-reports" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Id</th>
              <th>Item Name</th>
              <th>Customer</th>
              <th>Mobile</th>
              <th>Email</th>
              <th>Make</th>
              <th>Model</th>
              <th>Registration Number</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets_admin/js/zee.js');?>"></script>

<script>

$(document).ready(function(){
  // $("#filter").click();
});

//search filters
$("#filter").on("click", function(event){
  event.preventDefault();
  //var validation = false;
  //selectedInputs = ['make_id','model_id'];
  //validation = validateFields(selectedInputs);

  //if (validation == true) {
    var filters = $(this).closest("form").serializeArray();
    //console.log(filters);
    //dataTable builder
    var dtReports = dtBuilder({
    'tableId' : '#dt-reports',
    'orderColumn': 0,
    'order': 'ASC',
    'url' : "<?= base_url('reports/inventory_report_model_wise_process'); ?>",
    'ajaxData' : filters,
    'filterFields': [],
    'dbFields': [
      {'data':'id'},
      {'data':'item_name'},
      {'data':'username'},
      {'data':'user_mobile'},
      {'data':'user_email'},
      {'data':'item_makes_title'},
      {'data':'item_model_title'},
      {'data':'registration_no'}
    ]
  });
//}
});

  function model_ajax_list(e){
    var url = '<?php echo base_url();?>';
    var make_id = document.getElementById("make_id");
    var selectedId = make_id.options[make_id.selectedIndex].value;
    $.ajax({
      url: url + 'reports/ajax_model_list',
        type: 'POST',
        data: {'make_id':selectedId,"<?= $this->security->get_csrf_token_name(); ?>":"<?= $this->security->get_csrf_hash(); ?>"},
    }).then(function(resp){
      if (resp == true) {
        $("#model_list").html('vdf');
      }else{
        $("#model_list").html(resp);
      }
    });
  }
</script>