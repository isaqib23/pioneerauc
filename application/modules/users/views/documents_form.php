<style type="text/css">
    .dz-image img{
    max-width: 150px;
    max-height: 150px;
    width: 100%;
    height: 100%;
    }
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12" id="">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php if(isset($documents_info) && !empty($documents_info)) echo 'Documents for '.$documents_info[0]['username'];{ ?>
                </h2>
                <?php }?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a type="button" href="<?php echo base_url().'customers'; ?>"  class="btn  btn-info"><i class="fa fa-arrow-left"></i>&nbsp Back</a>
        <div id="result"></div>

            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>
            <?php if ($documents_info) { ?>
                <table id="" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Files</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($documents_info as $key => $value) { 
                            $file_ids = explode(',', $value['file_id']);
                            $files = $this->db->where_in('id', $file_ids)->get('files')->result_array();

                            ?>

                            <tr>
                                <td>
                                    <?php
                                    switch ($value['document_type_id']) {
                                        case 1:
                                            $cat_name =  "ID Card";
                                        break;
                                        case 2:
                                            $cat_name = "Passport";
                                        break;
                                        case 3:
                                            $cat_name = "Driving License";
                                        break;
                                        case 4:
                                            $cat_name = "Trade License";
                                        break;
                                        case 5:
                                            $cat_name = "Others";
                                        break;
                                         
                                        default:
                                            $cat_name = "Document";
                                        break;
                                     } 
                                     echo $cat_name;
                                     ?>
                                     </td>
                                <td>
									<ul>
                                        <?php foreach ($files as $key => $file) { ?>
                                            <li>
                                                <a style="color: #809fff;" href="<?= base_url() ."users/document_preview/".$file['id'] ?>" target="_blank"> <?= $file['name'] ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </td>
                                <!-- <td>
                                    <ul>
                                            <button onclick="deleteRecord(this)" type="button" data-obj="user_documents" data-id="<?= $value['file_id']; ?>" data-url="<?= base_url('users/delete_user_documents/').$user_id; ?>" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                                            <a style="color: red;" href="<?= base_url().$file['path'].$file['name']; ?>" target="_blank"> Click to delete</a>
                                    </ul>
                                </td> -->
                            </tr> 
                        <?php } ?>
                    </tbody>
                </table>
            <?php 
              }else{
                echo "<h1>No Record Found</h1>";
                }
            ?>
        </div>
    </div>
</div>


<script>
    $('.select2').select2();
    if ($('#documents').length) {
    var data_live_documents = '<?php echo (isset($documents)) ? json_encode($documents) : ''; ?>'; 
    var userId = '<?php echo $this->uri->segment(3);?>'; 
    var currentFile = null;
    var status_done_img = 'false';
    var status_done_doc = 'false';
    var args = [];
    var baseurl = '<?php echo base_url();?>uploads/users_documents/'+userId+'/';
    var divID2 = "#documents";
    var error_msg = 'true';
    Dropzone.autoDiscover = false;
    var dz1 = new Dropzone(divID2, {
        url: "<?php echo base_url('users/save_user_file_documents'); ?>",
        paramName: "documents",
        maxFilesize: 5,
        maxFiles: 10,
        acceptedFiles : '.pdf,.doc,.docx,.ppt,.pptx',
        addRemoveLinks : true,
        dictRemoveFile : 'X',
        parallelUploads: 10,
        uploadMultiple: true,
        autoProcessQueue: false,
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        previewsContainer: divID2, 
        newId: '',
        error_msg: 'true',
        init: function() {

             let thisDropzone2 = this; // Closure
            if(data_live_documents != '')
             {
              $.each(JSON.parse(data_live_documents), function(key,value){ //loop through it
                // console.log(value);

                var mockFile = { name: value.name, size: value.size , type: value.type }; // here we get the file name and size as response 

                thisDropzone2.options.addedfile.call(thisDropzone2, mockFile);
                var names = value.name;
                var extension = names.substr( (names.lastIndexOf('.') +1) );
                    if(extension == 'pdf'){

                        thisDropzone2.options.thumbnail.call(thisDropzone2, mockFile, "<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>");//uploadsfolder is the folder where you have all those uploaded files        
                    }else{
                        thisDropzone2.options.thumbnail.call(thisDropzone2, mockFile, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");//uploadsfolder is the folder where you have all those uploaded files

                    }
                    mockFile.previewElement.classList.add('dz-success');
                mockFile.previewElement.classList.add('dz-complete');
                // thisDropzone2.emit("complete", mockFile);
                

            });  
             }

            this.on('addedfile', function(file){
                this.options.addRemoveLinks = true;
                // var error_msg = 'true';
                this.options.dictRemoveFile = 'X';
                if (file.type.match(/.pdf/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>");
                }
                if (file.type.match(/.ai/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ai-icon.png'); ?>");
                }
                if (file.type.match(/.docx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
                }
                if (file.type.match(/.doc/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
                }
                if (file.type.match(/.eps/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/eps-icon.png'); ?>");
                }
                if (file.type.match(/.id/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/id-icon.png'); ?>");
                }
                if (file.type.match(/.ppt/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
                }
                if (file.type.match(/.pptx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
                }
                if (file.type.match(/.psd/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/psd-icon.png'); ?>");
                }
                if (file.type.match(/.xlsx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
                }
                if (file.type.match(/.xls/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
                }
                if(file.size > (1024 * 1024 * 5)) // not more than 5mb
                {
                    this.removeFile(file); 
                }
            });
            this.on('sending', function(file, xhr, formData){
                var user_id = "<?= isset($user_id) ? $user_id : ''; ?>";
                formData.append('user_id', user_id);
                formData.append('newId', this.newId);
            });
            this.on("success", function(file, response) { 
                this.options.addRemoveLinks = false;
                this.options.dictRemoveFile = '';
                
                  console.log("All files have uploaded ");
                   thisDropzone2.error_msg = 'true';
            });
            this.on("maxfilesexceeded", function (file) {
                this.removeAllFiles();
                this.addFile(file);
            });
             this.on("completemultiple", function (file) {
                status_done_doc = 'true';
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    // submitButton.removeAttribute("disabled");
                }
            }); 
            this.on("queuecomplete", function (file) {
                console.log("All files Que have completed ");
                        // console.log(file);
                        // console.log(thisDropzone2.error_msg);
                if($("#buyer_seller").val() === 'byr_sellr'){
                     if (thisDropzone2.error_msg == 'false') {
                        console.log(this.getQueuedFiles().length);

                        } else {
                            // do stuff
                    setTimeout(function(){window.location.href = "<?= base_url('customers'); ?>" },5000);
                        }
                }else{
                    if (thisDropzone2.error_msg == 'false') {
        
                        } else {

                    setTimeout(function(){window.location.href = "<?= base_url('customers'); ?>" },5000);
                        }   
                    
                }
            });
            this.on("removedfile", function(file) {
                  // var server_file = $(file.previewTemplate).children('.server_file').text();
                  // alert(server_file);
                  if (this.files.length == 0) {
                    var name = file.name; 
                    // Do a post request and pass this path and use server-side language to delete the file
                    $.post("<?php echo base_url('users/delete_user_documents/'); ?>"+userId, { file_to_be_deleted: name } );
                    }
                    console.log(name);
            }); 

            this.on("error", function(file, message) { 
                alert(message);
                
                // error_msg = false;
                if (response='false')
             {
                $('.msg-alert').css('display', 'block');
                $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong></strong> Invalid file! Try again.</div></div>');
             }
                thisDropzone2.error_msg = 'false';
            });

          //   this.on("complete", (function(_this) {

          //   return function(file) {

          //     if (_this.getUploadingFiles().length === 0 && _this.getQueuedFiles().length === 0) {

          //       setTimeout(function(){window.location.href='https://www.example.com/redirected-page'},2500);

          //     }
          //   };
          // })(this));
        }// end init function
    });

 }

    var url = '<?php echo base_url();?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        $("#send").on('click', function(e) { //e.preventDefault();
            
                        dz1.newId = userId;
                        dz1.processQueue(); 
            if (dz1.getUploadingFiles().length === 0) 
            {
                console.log('all done');
            }
        });
 
    });
    
 
</script>
