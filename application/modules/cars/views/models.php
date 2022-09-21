
<style type="text/css"> 
  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .tag:after {display: none;}
  .dataTables_filter{
    margin-right: 22px;
  }
</style>  

<?php if($this->session->flashdata('error')){ ?>
  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
  </div>
<?php } ?>

<?php if($this->session->flashdata('success')){ ?>
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
  </div>
<?php } ?>


    <div class="x_title">
        <h2>
            <?php echo $small_title; ?>
        </h2>
         <div class="clearfix"></div>
        <?php if ($this->session->flashdata('msg')) {?>
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                  </button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        <?php }?>   
      <!--   <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="valuation_model" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button> -->
       </div>
         <div class="x_content bulk_actions" style="display: none;">
            <label>Bulk Actions:</label>
            <button onclick="deleteRows_Bulk(this)" id="delete_bulk" type="button" data-table="datatable-responsive_ad2" data-obj="valuation_model" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
            <hr>
        </div>

  
        <div class="clearfix"></div>
    </div>  
    <div class=""> 
        <a type="button" href="<?php echo base_url().'cars/new_models'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Model</a>
          <!-- <div class="clearfix"></div> -->
    </div>
    <input type="hidden" id="<?= $this->security->get_csrf_token_name();?>" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <div class="atatable-responsive_ad2">
        <table id="datatable-responsive_ad2" class="table table-striped jambo_table bulk_action">
          <!-- jambo_table bulk_action (for jambo action table) -->
            <thead>
                <tr class="headings">
                    <th data-visible="false" data-orderable="false" >#</th>
                    <th>Title </th>
                    <th>Make </th>
                    <!-- <th>Engine Size </th> -->
                    <th>Created on </th>
                    <th>Status </th>
                    <th data-orderable="false" class="tablet mobile">Action</span>
                    </th>
                </tr>
            </thead>
           
        </table>
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width:750px; ">
              <div class="modal-content">

                <div class="modal-header">
                  <button type="button" data-dismiss="modal" class="close inner_bidding_model" title="close"><span aria-hidden="true">×</span>
                  </button>
                  <h3 class="modal-title" id="myModalLabel">Year Deprecition Detail</h3>
                  <div class="year_depreciation_detail_list">
                    
                  </div>
                </div>

              </div>
            </div>
          </div>
        <script>
    $(document).ready(function() {
    $('#models').DataTable();
    });
     $('#delete_button').click(function(){
        var url = '<?php echo base_url();?>';
        var selected = "";
        $('#model_listing input:checked').each(function() {
            selected+=$(this).attr('value')+",";
            
          });

        $.ajax({
                url: url + 'cars/delete_models/?ids='+selected,
                type: 'POST',              
            }).
          then(function(data) {
          var objData = jQuery.parseJSON(data);
          console.log(objData);
           if (objData.msg == 'success') {
            window.location = url + 'cars/models';

          }
           

        });
    });

        var checkboxes = $("input[type='checkbox']");
        $('input').on('ifChecked', function () {
            if(('checked').length > 0)
            { 
                $('#delete_bulk').show();
            }
        })
        $('input').on('ifUnchecked', function (){
            if(!checkboxes.is(":checked"))
            {
            $('#delete_bulk').hide();
            }
        })

        function get_year_depeciation_detail(e){  
              var url = '<?php echo base_url();?>';
              var id  = $(e).data('id');
               $.ajax({
                url: url + 'cars/get_year_depeciation_detail',
                type: 'POST',
                data: {id:id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    $('.year_depreciation_detail_list').html(objData.data);
                  }

                });
        }

 let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive_ad2',
    'url' : '<?=base_url()?>cars/ajax_model_list',
    'orderColumn' : 3,
    'iDisplayLength': 10,
    'dom' : '<"pull-left top"B><"pull-right"fl><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : 3,
    'defaultSearching': true,
    'responsive': true,
    'rowId': 'id',
    'stateSave': true,
    'lengthMenu': [[ 10, 25, 50, 100, 150], [10, 25, 50, 100, 150]],
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': [],
    'dataColumnArray': [{'data':'id'},{'data':'title'},{'data':'make_title'},{'data':'created_on'},{'data':'status'},{'data':'action'}]
  });

   $(document).ready(function(){
      $('#datatable-responsive_ad2 tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        } );
      $('.buttons-select-none').on( 'click', function () {
        $('.bulk_actions').css('display','none');
        } );
      $('.buttons-select-all').on( 'click', function () {
        $('.bulk_actions').css('display','block');
        } );
      
 
      // End filter options 

     $('#datatable-responsive_ad2 tbody').on( 'click', 'tr', function (e) {
      var rows_selected = dt1.rows('.selected').data();
      if(dt1.rows('.selected').data().length > 0){
        $('.bulk_actions').css('display','block');
      }else{
        $('.bulk_actions').css('display','none');
      }
    });  
      
      // end 

  });
</script>