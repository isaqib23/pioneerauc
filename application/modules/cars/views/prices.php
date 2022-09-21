
<style type="text/css"> 
  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .dataTables_filter{
    margin-right: 22px;
  }
  </style>
    <div class="x_title">
        <h2>
            <?php echo $small_title; ?>
        </h2>
        <div class="clearfix"></div>
    </div>
    <?php if ($this->session->flashdata('msg')) {?>
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                  </button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        <?php }?>

      <!--    <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="valuation_price" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
 -->
      <div class="x_content bulk_actions" style="display: none;">
        <label>Bulk Actions:</label>
        <button onclick="deleteRows_Bulk(this)" id="delete_bulk" type="button" data-table="datatable-responsive-ad3" data-obj="valuation_price" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
        <hr>
      </div>
    <div class=""> 
          <?php 
            $pre_price = $this->uri->segment(3);
            if(isset($pre_price) && !empty($pre_price))
            {
              ?>
            <!-- <a type="button" href="<?php echo base_url().'cars/new_make_price/'.$pre_price; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Price</a> -->
          <?php }
            else
            {

              ?>
            <!-- <a type="button" href="<?php echo base_url().'cars/new_price'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Price</a> -->
          <?php  }  ?>
          <a type="button" href="<?php echo base_url().'cars/new_price'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Price</a>
    </div>
    <input type="hidden" id="<?= $this->security->get_csrf_token_name();?>" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <div class="datatable-responsive-ad3">
          <table id="datatable-responsive-ad3" class="table table-striped jambo_table bulk_action">
              <thead>
                  <tr class="headings">
                      <th data-visible="false" data-orderable="false" class="notexport"></th>
                      <th>Make </th>
                      <th>Model </th>
                      <th>Engine </th>
                      <th>Price </th>
                      <th>Year </th>
                      <th>Created On </th>
                      <th data-orderable="false"><span class="nobr">Action</span>
                      </th>
                     
                  </tr>
              </thead>
            
          </table>
        </div>
          <script>
              $(document).ready(function() {
              $('#price_table').DataTable();
              });

               $('#delete_button').click(function(){
                  var url = '<?php echo base_url();?>';
                  var selected = "";
                  $('#price_listing input:checked').each(function() {
                    selected+=$(this).attr('value')+",";               
                  });
                  $.ajax({
                        url: url + 'cars/delete_prices/?ids='+selected,
                        type: 'POST',                                            
                    }).
                  then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                   if (objData.msg == 'success') {
                    window.location = url + 'cars/prices';

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


  let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4,5']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive-ad3',
    'url' : '<?=base_url()?>cars/ajax_price_list',
    'orderColumn' : 6,
    'iDisplayLength' : 5,
    'dom' : '<"pull-left top"B><"pull-right" fl><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : 6,
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
    'customFieldsArray': ['make_title','model_title','price','year','created_on'],
    'dataColumnArray': [{'data':'id'},{'data':'make_title'},{'data':'model_title'},{'data':'engine_size_title'},{'data':'price'},{'data':'year'},{'data':'created_on'},{'data':'action'}]
  });

  $(document).ready(function(){
      $('#datatable-responsive-ad3 tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        } );
      $('.buttons-select-none').on( 'click', function () {
        $('.bulk_actions').css('display','none');
        } );
      $('.buttons-select-all').on( 'click', function () {
        $('.bulk_actions').css('display','block');
        } );
      
      //  filter options 
       $('#username,#code,#assigned_to,#mobile').change(function(){
         dt1.draw();
       });
      $('.dateto').on('dp.change', function(e){ 
        if($(".datefrom").find("input").val() != ''){
          console.log($("#datefrom").val());
          dt1.draw();
        }
      });
      $('.datefrom').on('dp.change', function(e){ 
        if($(".dateto").find("input").val() != ''){
          console.log($("#dateto").val());
          dt1.draw();
        }
      });
      // End filter options 

     $('#datatable-responsive-ad3 tbody').on( 'click', 'tr', function (e) {
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