<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12" id="">
    <div class="x_panel">
        <div class="x_title">
            <h2>Document Preview</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
			<?php if ($file_type === "application/pdf"){ ?>
			<iframe src="https://docs.google.com/viewer?url=<?=$filePath?>&embedded=true"
					style="width:100%; height:1000px;"
					frameborder="0"></iframe>
			<?php }else{ ?>
			<img src="<?=$filePath?>" width="100%">
			<?php } ?>
        </div>
    </div>
</div>
