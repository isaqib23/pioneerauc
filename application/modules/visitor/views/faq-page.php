<?= $this->load->view('template/auction_cat') ?>
<div class="main gray-bg faq">
    <div class="container">
        <h1 class="section-title"><?=$this->lang->line('faq');?></h1>
        <div id="accordion">
        	<?php $j = 0; if (isset($list) && !empty($list)) :?>
		    <?php foreach ($list as $key => $value) : $j++; ?>
                <div class="card <?= ($j == 1) ? 'active' : ''; ?>">
                	<?php $question = json_decode($value['question']);
		    		$answer = json_decode($value['answer']); ?>
                    <div class="card-header" id="headingOne<?= $j; ?>">
                      <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne<?= $j; ?>" aria-expanded="true" aria-controls="collapseOne<?= $j ?>">
                          <span><?= $j; ?>.</span> <?= $question->$language; ?>
                        </button>
                      </h5>
                    </div>

                    <div id="collapseOne<?= $j; ?>" class="collapse <?= ($j == 1) ? 'show' : ''; ?>" aria-labelledby="headingOne<?= $j; ?>" data-parent="#accordion">
	                      <div class="card-body">
	                       <p><?= $answer->$language; ?></p>
	                      </div>
                    </div>
                </div>
			<?php endforeach; ?>
	    	<?php endif; ?>
        </div>
    </div>        
</div> 
      