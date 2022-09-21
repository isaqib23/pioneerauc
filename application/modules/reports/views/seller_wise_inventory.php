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
        <h2>Seller Wise Inventory<small></small></h2>
        <div class="nav navbar-right">
          <!-- <a href="<?//= base_url('admin/projects/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Projects</a> -->
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

        <form method="post" id="rptfrm" action="">

          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group col-md-12 col-sm-12col-xs-12">
            <select name="sellerId" id="sellerId" placeholder="Select Seller" class="form-control col-md-12 col-xs-12">
              <option></option>
            </select>
          </div>
          
          <div class="form-group col-md-3 col-sm-6 col-xs-6">
            <select name="item_sold_status" id="item_sold_status" class="form-control col-md-7 col-xs-12">
              <option selected value="all">All Statuses</option>
              <option value="sold">Sold</option>
              <option value="approval">On Approval</option>
              <option value="not">Available</option>
              <option value="return">Returned</option>
              <option value="not_sold">Expired</option>
            </select>
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
              <th>Seller</th>
              <th>Mobile</th>
              <th>Email</th>
              <th>Item Name</th>
              <th>Status</th>
              <th>Sold Date</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets_admin/js/zee.js');?>"></script>

<script>

  var firstEmptySelect = true;
  function formatSelect(result) {
    if (!result.text) {
      if (firstEmptySelect) {
          //console.log('showing row');
          // firstEmptySelect = false;
          return '<div class="row">' +
                  '<div class="col-xs-4"><b>Seller</b></div>' +
                  '<div class="col-xs-4"><b>Email</b></div>' +
                  '<div class="col-xs-4"><b>Mobile</b></div>' +
                  '</div>';
      } else {
        //console.log('skipping row');
        return false;
      }
    }
    return '<div class="row">' +
            '<div class="col-xs-4">' + result.text + '</div>' +
            '<div class="col-xs-4">' + result.email + '</div>' +
            '<div class="col-xs-4">' + result.mobile + '</div>' +
            '</div>';
  }

    $('#sellerId').select2({
        ajax: {
            url: '<?= base_url();?>reports/userSelect2',
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
  // function matcher(query, options) {
  //   firstEmptySelect = true;
    
  //   // If there are no search terms, return all of the data
  //   if ($.trim(query.term) === '') {
  //     return options;
  //   }

  //   // Do not display the item if there is no 'text' property
  //   if (typeof options.username === 'undefined') {
  //     return null;
  //   }

  //   var has = true;
  //   var words = query.term.toUpperCase().split(" ");
  //   for (var i =0; i < words.length; i++){
  //       has = has && (options.username.toUpperCase().indexOf(words[i]) >= 0); 
  //   }
  //   return (has) ? options : false;
  // }

  


// $(document).ready(function(){
//   $("#sellerId").select2({
//     data: <?= $sellers; ?>,
//     width: '100%',
//     templateResult: formatSelect,
//     templateSelection: formatSelect,
//     escapeMarkup: function(m) { return m; },
//     matcher: matcher
//   });
// });

//search filters
$("#filter").on("click", function(event){
  event.preventDefault();

  var filters = $(this).closest("form").serializeArray();
  //console.log(filters);
  //return false;

  //dataTable builder
  var dtReports = dtBuilder({
    'tableId' : '#dt-reports',
    'orderColumn': 0,
    'order': 'ASC',
    'url' : "<?= base_url('reports/seller_wise_inventory_process'); ?>",
    'ajaxData' : filters,
    'filterFields': [],
    'dbFields': [
      {'data':'seller'},
      {'data':'mobile'},
      {'data':'email'},
      {'data':'item_name'},
      {'data':'status'},
      {'data':'item_sold_date'}
    ]
  });
});
</script>