 <style type="text/css">
 ul.processList {
  list-style: none !important;
  margin: 0 !important;
  padding: 0 !important;
}
.select_all, #select_whole{
	margin-left:8px;
}
span.check-text {
  position: relative;
  top: 4px;
}
span.check-text1 {
  position: relative;
  top: 0;
}
.processList .checkbox{
	background:none;
}
.processList .parent .checkbox label{padding-left:0;}
li.child {
  padding-left: 20px;
  border-top: 1px solid #ccc;
  padding-bottom: 10px;
}
li.parent{padding-bottom:5px;}
.select_all_new{margin-left:10px !important;}
</style>
<div class="clearfix"></div> 
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Update Permissions For Role <small></small></h2>
      
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      
      <?php if($this->session->flashdata('error')){ ?>
      <div class="alert">
        <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <?php echo $this->session->flashdata('error'); ?>
        </div>
      </div>
      <?php }?>

      <br />
      <form method="post" novalidate="" id="demo-form2" action="<?php echo base_url().'acl/Acl_roles/'.$formaction_path.'/'.$this->uri->segment(4);?>" enctype="multipart/form-data"  class="form-horizontal form-label-left">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
        
       <div class="col-sm-12">
        <?php if(count($all_controllers) > 0){?>
        <?php foreach($all_controllers as  $controller){

          ?>
          <ul class="processList">
           <li class="parent">
             <div class="checkbox">
              <label>
               <strong  class="check-text  check-text1"><?php echo $controller;?></strong><input value="" type="checkbox" class="select_all select_all_new" >
             </label>
           </div>
         </li>
         <?php foreach($all_acl_permissions as $method_list){ ?> 
         <?php if($controller == $method_list['module']){ ?>
         <li class="child">
           <div class="checkbox">
            <label>
              <input <?php if(in_array($method_list['id'],$all_permission_id)){ echo 'Checked';}?> value="<?php echo $method_list['id'];?>" class="checkbox" type="checkbox" name="check[]" type="checkbox"><span class="check-text"><?php echo $method_list['name'];?></span>
            </label>
          </div>
        </li>
        <?php   }  }?>
      </ul>
      <?php }    } ?> 
    </div>
    
    <div class="ln_solid"></div>
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">


        <button type="submit" class="btn btn-success">Submit</button>
        
        <a href="<?php echo base_url().'acl'; ?>" class="btn btn-primary" type="button">Cancel</a>
      </div>
    </div>

  </form>
</div>
</div>
</div>

<script type="text/javascript">
/*var select_all = document.getElementById("select_all"); //select all checkbox
		var checkboxes = document.getElementsByClassName("checkbox"); //checkbox items

		//select all checkboxes
		select_all.addEventListener("change", function(e) {
		    for (i = 0; i < checkboxes.length; i++) {
		        checkboxes[i].checked = select_all.checked;
		    }
		});


		for (var i = 0; i < checkboxes.length; i++) {
		    checkboxes[i].addEventListener('change', function(e) { //".checkbox" change
		        //uncheck "select all", if one of the listed checkbox item is unchecked
		        if (this.checked == false) {
		            select_all.checked = false;
		        }
		        //check "select all" if all checkbox items are checked
		        if (document.querySelectorAll('.checkbox:checked').length == checkboxes.length) {
		            select_all.checked = true;
		        }
		    });
      }*/
      $(document).ready(function(){
       $(".select_all").change(function () {
        if($(this).is(":checked")){
			//var ul = $(this).parent().parent().parent().parent();
			$($(this).parent().parent().parent().parent().find("li")).each(function() {
				$(this).find('input[type=checkbox]').prop('checked', true);
  				//$(this).prop('checked', true);
       });
		}else{
			$($(this).parent().parent().parent().parent().find("li")).each(function() {
				$(this).find('input[type=checkbox]').prop('checked', false);
  				//$(this).prop('checked', true);
       });
		}
	});
     })
   </script>