<main class="page-wrapper valuation-page">
    <div class="inner-banner">
      <div class="container">
        <div class="caption">
          <h1 class="page-title">Free Car Valuation</h1>
        </div>
        <ul class="steps">
          <li class="active">
            <span class="step-dot"></span>
            <span class="step-name">Select Car</span>
          </li>
          <li>
            <span class="step-dot"></span>
            <span class="step-name">Model and Conditions</span>
          </li>
          <li>
            <span class="step-dot"></span>
            <span class="step-name">Book an Appointment</span>
          </li>
        </ul>
      </div>
    </div>
    <div class="gray-bg">
      <div class="container">
      <div class="Valuation-form">
        <form>
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          <div class="form-content">
            <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <select class="selectpicker">
                  <option>Audi</option>
                  <option>1</option>
                  <option>2</option>
                </select>
              </div>  
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" name="" class="form-control" placeholder="Model">
              </div>  
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" name="" class="form-control"  placeholder="Year">
              </div>  
            </div>
            </div>
          </div>
          <div class="button-row">
            <a href="#" class="btn btn-default">GET STARTED NOW</a>
          </div>
        </form>
      </div>
      </div>
    </div>  
    </main>