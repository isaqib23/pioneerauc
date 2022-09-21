<style type="text/css">
  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .dataTables_filter{
    margin-right: 22px;
  }
  .tag:after {display: none;}
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>
            <?php echo $small_title; ?>
        </h2>
        <div class="clearfix"></div>
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

        </div>
        <div class="clearfix"></div>

      <div class="x_content bulk_actions" style="display: none;">
            <label>Bulk Actions:</label>
            <button onclick="deleteRows_Bulk(this)" id="delete_bulk" type="button" data-table="datatable-responsive_adil" data-obj="valuation_make" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
            <hr>
      </div>

     
      <div class=""> 
        <a type="button" href="<?php echo base_url().'cars/new_makes'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Make</a>
              <!-- <div class="clearfix"></div> -->
      </div>
      <input type="hidden" id="<?= $this->security->get_csrf_token_name();?>" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
      <div class="datatable-responsive_adil" id="checkboxes">
          <table id="datatable-responsive_adil" class="table table-striped jambo_table" cellspacing="0" width="100%">
              <thead>
                  <tr class="headings">
                      <th data-visible="false" data-orderable="false" class="notexport"></th>
                      <th>Title </th>
                      <th>Live </th>
                      <th data-orderable="false">Actions</th>
                     
                  </tr>
              </thead>
              </table>   
          </div>   
          </div>
          </div>   

<script>
        $(document).ready(function() {
        $('#engine_sizes').DataTable();
        });
        $('#delete_button').click(function(){
        var url = '<?php echo base_url();?>';
        var selected = "";
        $('#make_listing input:checked').each(function() {
        selected+=$(this).attr('value')+",";
            
          });

        $.ajax({
        url: url + 'cars/delete_makes/?ids='+selected,
        type: 'POST',
        }).then(function(data) {
          var objData = jQuery.parseJSON(data);
          console.log(objData);
           if (objData.msg == 'success') {
            window.location = url + 'cars/makes';

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



      let exportOptions = {'rows': {selected: true} ,columns: ['1,2']};
      let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
      let dt1 = customeDatatable({
        // index zero must be csrf token
        '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
        'div' : '#datatable-responsive_adil',
        'url' : '<?=base_url()?>cars/ajax_makes_list',
        'orderColumn' : 2,
        'iDisplayLength' : 5,
        'dom' : '<"pull-left top"B><"pull-right"fl><"clear">r<t><"bottom"ip><"clear">',
        'columnCustomeTypeTarget' : 3,
        'defaultSearching': true,
        'responsive': true, 
        'rowId': 'id',
        'stateSave': true,
        'lengthMenu': [[ 5,10, 25, 50, 100, 150], [5,10, 25, 50, 100, 150]],
        'order' : 'desc',
        'selectStyle' : 'multi',
        'collectionButtons' : dtButtons,
        'processing' : true,
        'serverSide' : true,
        'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
        'buttonClassName' : 'btn-sm',
        'columnCustomType' : 'date-eu',
        'customFieldsArray': ['title','Arabic Title','status','created_on'],
        'dataColumnArray': [{'data':'id'},{'data':'title'},{'data':'status'},{'data':'action'}]
      });

       $(document).ready(function(){
          $('#datatable-responsive_adil tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
            } );
          $('.buttons-select-none').on( 'click', function () {
            $('.bulk_actions').css('display','none');
            } );
          $('.buttons-select-all').on( 'click', function () {
            $('.bulk_actions').css('display','block');
            } );
          
          // End filter options 

         $('#datatable-responsive_adil tbody').on( 'click', 'tr', function (e) {
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