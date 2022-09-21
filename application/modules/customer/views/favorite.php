
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<?= $this->load->view('template/auction_cat');?>
<main class="page-wrapper inventory">
      <div class="listing-wrapper">
         <?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
          <section class="datatable">
            <div class="container padding-0">
              <div class="table-head">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h1>FAVOURITES</h1>
                  </div>
                  <div class="col-md-6">
                    <ul>
                      <li>
                        <div class="search-box">
                          <input type="text" class="form-control" placeholder="Search" name="search_fav" id="search_fav">
                          <button type="button">
                            <img src="<?= ASSETS_USER?>/images/search-icon.png">
                          </button>
                        </div>
                      </li>
                      <!-- //// for categories dynamic //// -->

                      <?php 
                     $r= $this->db->get('item_category')->result_array();
                      ?>
                      
                     <!--  <li>
                        <select class="selectpicker sm" id="item_cat">
                          <option value="">Select Category</option>
                            <?php foreach ($r as $key => $value)  {
                                $title= json_decode($value['title']);
                                ?>
                          <option value="1"><?php if(!empty($title))echo $title->english ?></option>
                      <?php } ?>
                        </select>
                      </li> -->
                      <!-- ///// categories dynamic ends here //////// -->

                       <li>
                        <select class="selectpicker sm" id="item_cat">
                          <option value="">Select Category</option>
                          <option value="1">Vehicle</option>
                          <option value="2">Property</option>
                          <option value="5">Number Plate</option>
                        </select>
                      </li>

                    </ul>
                  </div>
                </div>
              </div>
              <table class="bids-table data-table table table-striped dt-responsive nowrap" id="fav_table">
                  <thead>
                      <tr>
                          <th class=""> Id</th>
                          <th data-orderable="false"></th>
                          <th class="user-name">Name</th>
                          <th class="price">Price</th>
                          <th class="status">Status</th>
                          <th class="empty" data-orderable="false"></th>
                      </tr>
                  </thead>

              </table>
            </div>  
          </section>
        </div>
      </div>
    </main>

    <script type="text/javascript">
      

// $(document).ready(function(){

      let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
      let exportOptionsSelect = { modifier: { selected: null} };
      let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
      let dt1 = customeDatatable({
      'div' : '#fav_table',
      'url' : '<?= base_url()?>customer/getFavorite',
      'url' : '<?= base_url()?>customer/getFavorite',
      'orderColumn' : 0,
      'iDisplayLength' : 10,
      // 'dom' : 'fr<t><"bottom"lip><"clear">', // r<t><"bottom"lip><"clear">
      "dom": '<"top"i>rt<"bottom"flp>',
      'columnCustomeTypeTarget' : 2,
      'defaultSearching': false,
      'responsive': true,
      'rowId': 'id',
      'stateSave': true,
      'lengthMenu': [[ 10, 25, 50, 100, 150], [10, 25, 50, 100, 150]], // [5, 10, 25, 50, 100], [5, 10, 25, 50, 100]
      'order' : 'desc',
      'selectStyle' : 'multi',
      'collectionButtons' : dtButtons,
      // 'exportOptionsSelect' : exportOptionsSelect,
      'processing' : true,
      'serverSide' : true,
      'processingGif' : '<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>',
      'buttonClassName' : 'btn-sm',
      'columnCustomType' : '',
      'customFieldsArray': ['item_cat','search_fav'],
      'dataColumnArray': [{'data':'id'},{'data':'image'},{'data':'name'},{'data':'price'},{'data':'status'},{'data':'action'}]
      });
  // });

      $('#item_cat').change(function(){
        dt1.draw();
      });

      $('#search_fav').keyup(function(){
        dt1.draw();
      });

    </script>