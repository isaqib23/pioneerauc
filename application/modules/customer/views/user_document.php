<?= $this->load->view('template/auction_cat');?>
<main class="page-wrapper document">
	<div class="listing-wrapper">
    <?= $this->load->view('template/template_user_leftbar') ?>
		<!--  <div class="col-md-12">
          <span class=" text-success" id="error-msgsp" ></span>
        </div> -->
        <!-- <div class="col-md-12">
          <span class=" text-success" id="valid-msgs" ></span>
        </div> -->
	<div class="right-col">
		<section class="datatable">
			<div class="container">
				<div class="table-head">
					<div class="row align-items-center">
					 <div class="col-md-12">
	                  <span class=" text-success" id="error-msgsp" ></span>
	                </div>
	               
						<div class="col-md-6">
							<h1>DOCUMENTS</h1>
						</div>
					</div>
				</div>
				<form id="submit" method="post" enctype="multipart/form-data">
					<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
					<div class="form">
					<h2>Required Uploads</h2>
						<div class="row">
							<h2></h2>
							<div class="col-md-6">
								<div class="file-uploader">
									<span class="ozlabel">National ID Card</span>
									<input  id="card" type="file" name="id_card">
								</div>
								<?php if(isset($name['id_card']) && !empty($name['id_card'])){ ?>
								<ul>
									<li class="li" id="hide">
										<?php $query='';  $query=$this->db->query('select * from files where id ="'.$name['id_card'].'"')->row_array(); 
										$name_file_arr = explode('.', basename($query['name'])); ?>
										<a href="javascript:void(0)"  <?= (isset($query['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc')) ? 'data-toggle="modal" data-target="#DocViewModel" onclick="openDocumentViewer(this)"' : 'class="pop"' ?>  data-path="<?= base_url('uploads/users_documents').'/'.$this->session->userdata('logged_in')->id.'/'.$query['name'];?>"><?php echo $query['name'];?> 
										<span><?php echo $query['size']?>Kb</span></a>
										<a  href="#" onclick="removeDocument(this);" data-type="id_card" data-id="<?= $name['id_card'];?>">
											<img src="<?= ASSETS_USER;?>/images/delete-icon.svg" alt="">
										</a>
									</li>
							  	</ul>
							  	<?php } ?>
							</div>
							 <div class="col-md-6">
								<div class="file-uploader">
									<span class="ozlabel">Passport</span>
									<input type="file" name="passport">
								</div>

								<?php if(isset($name['passport']) && !empty($name['passport'])){ ?>
								<ul id='hide'>
									<li>
										<?php $query=''; $query=$this->db->query('select * from files where id ="'.$name['passport'].'"')->row_array();
										$name_file_arr = explode('.', basename($query['name'])); ?>
										<a href="javascript:void(0)" <?= (isset($query['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc')) ? 'data-toggle="modal" data-target="#DocViewModel" onclick="openDocumentViewer(this)"' : 'class="pop"' ?> data-path="<?= base_url('uploads/users_documents').'/'.$this->session->userdata('logged_in')->id.'/'.$query['name'];?>"><?php echo $query['name'];?>
										 <span><?php echo $query['size']?>Kb</span></a>
										<a href="#" onclick="removeDocument(this);" data-type="passport" data-id="<?= $name['passport'];?>">
											<img src="<?= ASSETS_USER;?>/images/delete-icon.svg" alt="">
										</a>
									</li>
							  	</ul>
							  <?php } ?>
							</div>
							<div class="col-md-6">
								<div class="file-uploader">
									<span class="ozlabel">Driver License</span>
									<input type="file" name="driver_license">
								</div>
								<?php if(isset($name['driver_license']) && !empty($name['driver_license'])){ ?>
								<ul>
									<li>
										<?php $query=''; $query=$this->db->query('select * from files where id ="'.$name['driver_license'].'"')->row_array();
										$name_file_arr = explode('.', basename($query['name'])); ?>
										<a href="javascript:void(0)" <?= (isset($query['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc')) ? 'data-toggle="modal" data-target="#DocViewModel" onclick="openDocumentViewer(this)"' : 'class="pop"' ?> data-path="<?= base_url('uploads/users_documents').'/'.$this->session->userdata('logged_in')->id.'/'.$query['name'];?>"><?php  echo $query['name'];?> 
										<span><?php echo $query['size']?>Kb</span></a>
										<a href="#" onclick="removeDocument(this);" data-type="driver_license" data-id="<?= $name['driver_license'];?>">
											<img src="<?= ASSETS_USER;?>/images/delete-icon.svg" alt="">
										</a>
									</li>
							  	</ul>
							  	<?php } ?>
							</div>
							<div class="col-md-6">
								<div class="file-uploader">
									<span class="ozlabel">Trade License</span>
									<input type="file"  name="trade_license">
								</div> 
								<?php if(isset($name['trade_license']) && !empty($name['trade_license'])){ ?>
								<ul>
									<li>
										<?php $query=''; $query=$this->db->query('select * from files where id ="'.$name['trade_license'].'"')->row_array();
										$name_file_arr = explode('.', basename($query['name'])); ?>
										<a href="javascript:void(0)" <?= (isset($query['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc')) ? 'data-toggle="modal" data-target="#DocViewModel" onclick="openDocumentViewer(this)"' : 'class="pop"' ?> data-path="<?= base_url('uploads/users_documents').'/'.$this->session->userdata('logged_in')->id.'/'.$query['name'];?>"><?php echo $query['name'];?> 
										 <span><?php echo $query['size']?>Kb</span></a>
										<a href="#" onclick="removeDocument(this);" data-type="trade_license" data-id="<?= $name['trade_license'];?>">
											<img src="<?= ASSETS_USER;?>/images/delete-icon.svg" alt="">
										</a>
									</li>
							  	</ul>
							  	<?php } ?>
							</div>
						</div>
					</div>	
					<div class="button-row">
						<button type="button" id="docs" class="btn btn-default">SUBMIT</button>
					</div>
				</form>
			</div>	
		</section>
	</div>
</div>
</main>

<div class="modal fade bs-example-modal-docViewer" id="DocViewModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Document Viewer</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
        </div>
        <div id="DivIdToPrintDoc" class="modal-docViewer-body container-fluid">
          <div class="col-md-10">
           <iframe id="viewer" frameborder="0" scrolling="no" width="400" height="600" class="docUrl" src=""></iframe>
          </div>
        </div>
        <div class="modal-footer">
        
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">              
		  <div class="modal-body">
		    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		    <img src="" class="imagepreview" style="width: 300px; height: 300px.;" title="" >
		  </div>
		</div>
	</div>
</div>


