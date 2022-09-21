<body class="gradient-banner">
    <main class="page-wrapper notification">
  <div class="listing-wrapper">
    <?= $this->load->view('template/template_user_leftbar') ?>
    <?= $this->load->view('template/auction_cat');?>
    <div class="right-col">
      <div class="table-head">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h1>NOTIFICATION</h1>
          </div>
        </div>
      </div>
      <?php if (isset($list) && !empty($list)) :
        foreach ($list as $key => $value) : ?>
          <div class="gray-bg">
            <div class="container">
              <div class="noti-list">
                <h3><?= $value['type']; ?></h3>
                <div class="box">
                  <ul>
                    <li><?= $value['message']; ?></li>
                    <li><p><?= $value['created_on']; ?></p></li>
                  </ul>
                </div>
              </div>
            </div>
          </div> 
      <?php endforeach;
        endif; ?>
       
    </div>
  </div>
    </main>