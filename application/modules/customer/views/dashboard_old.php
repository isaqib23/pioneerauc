<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<main class="page-wrapper dashboard">
      <div class="listing-wrapper">
        <?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
          
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

          <div class="info-head">
            <div class="row align-items-center">
              <div class="col-md-6">
                <h2>My Account</h2>
              </div>
              <div class="col-md-6">
                <ul class="right-items">
                  <li class="status">
                    <p>Account Status:</p>
                  </li>
                  <li>
                    <span class="green-text"> Active</span>
                  </li>
                </ul> 
              </div>
            </div>  
          </div>
          <div class="account-stats">
            <div class="row">
              <div class="col-md-4">
                <div class="box">
                  <h1><?php echo (!empty($balance)) ? $balance : '0';?></h1>
                  <p>Balance</p>
                </div>
              </div>        
              <div class="col-md-4">
                <div class="box">
                  <h1><?php echo (!empty($bids['count'])) ? $bids['count'] : '0'; ?></h1>
                  <p>My Bids</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="box">
                  <h1>284</h1>
                  <p>Some stats here</p>
                </div>
              </div>
            </div>
          </div>
          <section class="datatable">
            <h1>My Bids</h1>
            <div class="container padding-0">
              <table class="bids-table data-table table table-striped dt-responsive nowrap" id="bids_table1">
                  <thead>
                      <tr>
                          <th class="id"> Id</th>
                          <th data-orderable="false"></th>
                          <th class="name">Name</th>
                          <th class="price">Price</th>
                          <!-- <th class="type">Type</th> -->
                          <th class="status">Status</th>
                          <th class="action" data-orderable="false"></th>
                          <!-- <th class="empty"></th> -->
                      </tr>
                  </thead>
                  <!-- <tbody> -->
                      <!-- <tr>
                          <td class="counting">1</td>
                          <td class="name" >
                            <div class="profile">
                              <div class="image"><img src="<?= ASSETS_IMAGES;?>our-team.png"></div>
                              <div class="desc">Chevrolet Camaro 2013</div>
                            </div>
                          </td>
                          <td>10,0000 ADED</td>
                          <td>Timed</td>
                          <td class="green-text">Won</td>
                          <td><i class="fa fa-ellipsis-h"></i></td>
                      </tr>
                      <tr>
                          <td class="counting">2</td>
                          <td class="name" >
                            <div class="profile">
                              <div class="image"><img src="<?= ASSETS_IMAGES;?>bolg-image.png"></div>
                              <div class="desc">Chevrolet Camaro 2013</div>
                            </div>
                          </td>
                          <td>10,0000 ADED</td>
                          <td>Timed</td>
                          <td class="green-text">Won</td>
                          <td><i class="fa fa-ellipsis-h"></i></td>
                      </tr>
                      <tr>
                          <td class="counting">3</td>
                          <td class="name" >
                            <div class="profile">
                              <div class="image"><img src="<?= ASSETS_IMAGES;?>bolg-image.png"></div>
                              <div class="desc">Chevrolet Camaro 2013</div>
                            </div>
                          </td>
                          <td>10,0000 ADED</td>
                          <td>Timed</td>
                          <td class="green-text">Won</td>
                          <td><i class="fa fa-ellipsis-h"></i></td>
                      </tr>
                      <tr>
                          <td class="counting">4</td>
                          <td class="name" >
                            <div class="profile">
                              <div class="image"><img src="<?= ASSETS_IMAGES;?>our-team.png"></div>
                              <div class="desc">Chevrolet Camaro 2013</div>
                            </div>
                          </td>
                          <td>10,0000 ADED</td>
                          <td>Timed</td>
                          <td class="green-text">Won</td>
                          <td><i class="fa fa-ellipsis-h"></i></td>
                      </tr> -->
                  <!-- </tbody> -->
              </table>
            </div>  
          </section>
        </div>
      </div>
    </main> 

     <script type="text/javascript">
      

$(document).ready(function(){

      let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
      let exportOptionsSelect = { modifier: { selected: null} };
      let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
      let dt1 = customeDatatable({
      'div' : '#bids_table1',
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
      'processingGif' : '<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>',
      'buttonClassName' : 'btn-sm',
      'columnCustomType' : '',
      'customFieldsArray': [],
      'dataColumnArray': [{'data':'id'},{'data':'image'},{'data':'name'},{'data':'price'},{'data':'status'},{'data':'action'}]
      });
  });

      $('#bids_cat').change(function(){
      dt1.draw();
      });

      $('#bids').keyup(function(){
      dt1.draw();
      });

    </script>