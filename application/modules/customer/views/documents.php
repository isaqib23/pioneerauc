<main class="page-wrapper dashboard">
      <div class="listing-wrapper">
        <?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
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
                  <h1>23243</h1>
                  <p>Balance</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="box">
                  <h1>10</h1>
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
            <div class="">
              <table class="data-table table table-responsive table-striped dt-responsive nowrap">
                  <thead>
                      <tr>
                          <th class="id"> Id</th>
                          <th class="user-name">Name</th>
                          <th class="price">Price</th>
                          <th class="type">Type</th>
                          <th class="status">Status</th>
                          <th class="empty"></th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
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
                      </tr>
                  </tbody>
              </table>
            </div>  
          </section>
        </div>
      </div>
    </main>