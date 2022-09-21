

    <div class="faq">
                <div class="container">
                    <div class="title-head d-flex align-items-center justify-content-between">
                        <h3 class="seciton-title" ><?=$this->lang->line('faq');?></h3>
                    </div>
                        <?php
                        $faqs = $this->db->get('ques_ans')->result_array();
                        $j = 0; if (isset($faqs) && !empty($faqs)) :?>
                    <div id="accordion">
                        <?php foreach ($faqs as $key => $value) : $j++; 
                        $question = json_decode($value['question']);
                        $answer = json_decode($value['answer']);?>
                        <div class="card border-0 rounded-0 bg-transparent" >
                            <div class="card-header border-0 p-0 bg-transparent" id="headingOne<?= $j; ?>">
                                <button class="btn collapsed" data-toggle="collapse" data-target="#collapseOne<?= $j; ?>" aria-expanded="true" aria-controls="collapseOne">
                                    <?= $question->$language; ?>
                                </button>
                            </div>
                            <div id="collapseOne<?= $j; ?>" class="collapse" aria-labelledby="headingOne<?= $j; ?>" data-parent="#accordion">
                                <div class="card-body">
                                    <p><?= $answer->$language; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                      </div>
                      <?php endif; ?>
                </div>
            </div>  