
<main class="page-wrapper dashboard">
  <div class="listing-wrapper">
    <?= $this->load->view('template/template_user_leftbar') ?>
    <div class="right-col">
      <div class="container">
        <div class="table-head">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h1>FAQ'S</h1>
            </div>
            <div class="col-md-6">
            </div>
          </div>
        </div>
        <div class="faq-accordion">
          <div class="accordion" id="accordionExample">
            <?php $j = 0; if (isset($list) && !empty($list)) : ?>
            <?php foreach ($list as $key => $value) : $j++; ?>
              <div class="card <?= ($j == 1) ? 'active' : ''; ?>">
              
                <?php $question = json_decode($value['question']);
                 $answer = json_decode($value['answer']); ?>
                <div class="card-header " id="heading<?= $j; ?>">
                    <h2 class="mb-0">
                      <button class="btn btn-link " type="button" data-toggle="collapse" data-target="#collapse<?= $j; ?>" aria-expanded="true" aria-controls="collapse<?= $j ?>">
                        <span><?= $j; ?>.</span> <?= $question->$language; ?><i class="fa fa-angle-right"></i>
                      </button>
                    </h2>
                </div>
                <div id="collapse<?= $j; ?>" class="collapse <?= ($j == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?= $j; ?>" data-parent="#accordionExample">
                    <div class="card-body">
                      <p><?= $answer->$language; ?></p>
                    </div>
                </div>
              </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <!-- <div class="card">
              <div class="card-header" id="headingTwo">
                  <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      <span>2.</span> How to sell at an auction<i class="fa fa-angle-right"></i>
                    </button>
                  </h2>
              </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                  <div class="card-body">
                
                  </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingThree">
                  <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      <span>3.</span> How to register<i class="fa fa-angle-right"></i>
                    </button>
                  </h2>
              </div>
              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                  <div class="card-body">
                    <p>Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
              </div>  
            </div>
            <div class="card">
              <div class="card-header" id="headingfour">
                  <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
                      <span>4.</span> How to buy online<i class="fa fa-angle-right"></i>
                    </button>
                  </h2>
              </div>
              <div id="collapsefour" class="collapse" aria-labelledby="headingfour" data-parent="#accordionExample">
                  <div class="card-body">
                    <p>Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingfive">
                  <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsefive" aria-expanded="false" aria-controls="collapsefive">
                      <span>5.</span> Bidding options<i class="fa fa-angle-right"></i>
                    </button>
                  </h2>
              </div>
              <div id="collapsefive" class="collapse" aria-labelledby="headingfive" data-parent="#accordionExample">
                  <div class="card-body">
                    <p>Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingsix">
                  <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsesix" aria-expanded="false" aria-controls="collapsesix">
                      <span>6.</span> How can we help?<i class="fa fa-angle-right"></i>
                    </button>
                  </h2>
              </div>
              <div id="collapsesix" class="collapse" aria-labelledby="headingsix" data-parent="#accordionExample">
                  <div class="card-body">
                    <p>Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingseven">
                  <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">
                      <span>7.</span> How to login<i class="fa fa-angle-right"></i>
                    </button>
                  </h2>
              </div>
              <div id="collapseseven" class="collapse" aria-labelledby="headingseven" data-parent="#accordionExample">
                  <div class="card-body">
                    <p>Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
              </div>
            </div> -->
          </div>
        </div>
        </div>
    </div>
  </div> 
</main>