<style type="text/css">
    .dz-image img{
    max-width: 150px;
    max-height: 150px;
    width: 100%;
    height: 100%;
    }
</style>

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                     <?php if(isset($user_info) && !empty($user_info)) echo 'Documents for '.$user_info[0]['username'];{ ?>
                </h2>
                <?php }?>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a type="button" href="<?php echo base_url().'customers'; ?>"  class="btn  btn-info"><i class="fa fa-arrow-left"></i>Back</a>
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
           
            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if(isset($user_info) && !empty($user_info)){ ?>
                    <input type="hidden" name="user[id]" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>  
                <?php
                $other_user = 0;
                if(!empty($this->uri->segment(4)) && $this->uri->segment(4) === 'byr_sellr'){?>
                    <input type="hidden" name="buyer_seller" id="buyer_seller" value="<?=$this->uri->segment(4);?>">
                <?php
                    $other_user = 1;
                }else{?>
                    <input type="hidden" name="buyer_seller" id="buyer_seller" value="">
                <?php
                }
                ?>
                <hr>
                <?php if (isset($document_types) && !empty($document_types) ) { ?>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="doc_type">Purpose<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control col-md-7 col-xs-12" id="doc_type" name="doc_type" >
                                <?php foreach ($document_types as $key => $value) {

                                    $doc_name = str_replace('_', ' ', $value['name']);
                                    $document_name = json_decode($value['name']);
                                     ?>
                                    <option value="<?= $value['id']; ?>"><?= ucwords($document_name->english) ?></option>
                                <?php } ?>
                                  
                            </select>
                        </div>
                    </div>

                    <!-- <div class="item form-group">  
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="doc_type"> Purpose<span class="required">*</span>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control col-md-7 col-xs-12" id="" name="doc_type" >
                                <?php foreach ($document_types as $key => $value) {
                                    $doc_name = str_replace('_', ' ', $value['name']); ?>
                                    <option value="<?= $value['id']; ?>"><?= ucwords($doc_name) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> -->
                <?php } ?>

                <?php if(isset($user_info) && !empty($user_info)){ ?>
                <div class="form-group">
                <span class="section">Documents</span>   
                <div class="image-gallery">  
                <div id="documents" class="dropzone"></div>
                </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="button" id="send" class="btn btn-success">Submit</button>
                    <?php
                    if($other_user > 0)
                    { 
                        $link = 'customers';
                    }
                    else
                    { 
                        $link = 'customers';
                    }
                    ?>
                        <a href="<?php echo base_url().$link; ?>" class="btn btn-primary" type="button">
                            Cancel
                        </a>
                    </div>
                </div>
                <?php }else{

                    echo '<h3>No Item Found</h3>';
                } ?>
            </form>
        </div>
    </div>
</div>


<script>
    $('.select2').select2();
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
        acceptedFiles : '.pdf,.doc,.docx,.ppt,.pptx, image/*',
        addRemoveLinks : true,
        dictRemoveFile : 'X',
        parallelUploads: 10,
        uploadMultiple: true,
        autoProcessQueue: false,
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        previewsContainer: divID2, 
        newId: '',
        field: '',
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
                var token_name = '<?= $this->security->get_csrf_token_name();?>';
                var token_value = '<?=$this->security->get_csrf_hash();?>';
                formData.append('user_id', user_id);
                formData.append('field', this.field);
                formData.append('newId', this.newId);
                formData.append([token_name], token_value);
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

 

    var url = '<?php echo base_url();?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        $("#send").on('click', function(e) { //e.preventDefault();
            
            var field = $('#doc_type').children("option:selected").val();
            dz1.field = field;
            dz1.newId = userId;
            dz1.processQueue(); 
            if (dz1.getUploadingFiles().length === 0) 
            {
                console.log('all done');
            }
        });
 
    });
    
 
</script>