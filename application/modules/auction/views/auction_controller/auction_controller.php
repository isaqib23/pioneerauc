 
<style>
  body {background: #f5f5f5}
  .main-wapper {padding: 40px 0;}
  .auction-nam h2 {font-size: 20px; background: #e68522; text-align: center; padding: 6px 15px; width: 100%; margin-bottom: 2px; }
  .auction-nam h3 {font-size: 15px; background: #f1c370; text-align: center; padding: 3px 15px; width: 100%; margin-bottom: 4px; }
  .summary h3 {color: #2828c1; font-size: 19px; line-height: 1.1; font-weight: normal;}
  .summary h2 {color: #d60e0e; font-size: 28px; line-height: 1; font-weight: normal; }
  .summary h4 {color: gray; font-size: 18px; line-height: 1; font-weight: normal; }

 fieldset {
    border: solid 1px #b3b3b3;
    padding: 0 1.4em 6px 1.4em;
    border-radius: 0px;
    margin: 0 0 15px 0;
    box-shadow: 0px 0px 0px 0px #000;
}

 fieldset legend {
    font-size: 13px;
    text-align: left;
    width: auto;
    padding: 0 8px 0px 2px;
    border-bottom: none;
}

.detail-table {
  border: 2px solid gray;
  height: 250px;
  overflow: scroll;
}
table { width: 100%;}

table th {background: lightgray;}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  font-size: 13px;
  font-weight: normal;
}

table tbody tr:hover {
    background: #F7F8FB;
}

td {padding: 0px 9px}

.lot,
.reg,
.make,
.colour,
.reverse{width: 12%;}

.status {width: 40%;}

.bolder {font-weight: bolder;}

ul {list-style: none; padding: 0; margin: 0;}

.bid-update ul {display: flex; align-items: center; flex-wrap: wrap; justify-content: space-between; width: 100%}
.bid-update li {width: 31%; margin-bottom: 15px;}

.btn-counter {font-size: 16px; padding: 8px 10px; color: gray; background: #f5f5f5; border-radius: 0; width: 100%; min-height: 44px; box-shadow: none; outline: none !important;  }

tbody tr:hover {box-shadow: 0px 0px 6px inset gray}
.detail-table tbody tr:hover .bolder {color: #000 !important;}
.detail-table tbody tr {background: #FFF; }

thead tr, th, td { border: 1px solid gray !important; }

.table table {height: 200px; border: 1px solid lightgray; margin-bottom: 10px; width: 100%;}
.table table tr th:first-child {width: 35%;}
.table table tr th:nth-child(2) {width: 35%;}
.table table tr th:nth-child(3) {width: 15%;}
.table table tr th:nth-child(4) {width: 15%;}
.table tbody .empty {background: #fff; height: 104px;}

.table tbody .empty td{ min-height: 150px; padding: 20px 10px; font-size: 22px; font-weight: 600; line-height: 1.3;}

select {width: 100%; padding: 5px 20px; font-size: 16px; font-weight: 600;}

.sold-items {margin: 10px 0; }

.sold-items ul {display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; width: 90%; margin: auto;}

.sold-items li {width: 19.7%; border: 1px solid gray; text-align: center; font-size: 12px; background: rgba(234, 164, 34, 0.25);}

.green-text {color: green;}
.blue-text {color: blue;}
.red-text {color: red;}

.start-auction {width: 100%; font-size: 13px; line-height: 1; padding: 10px 10px; font-weight: 600;}

/*.bids-increment {display: flex; align-items: center;}*/
.bids-increment p {font-size: 15px; font-weight: 600;}
.bids-increment input {width: 100%; padding: 4px 10px;}
.bids-increment .btn-inc {padding: 6px 10px;}

.btn-inc {width: 100%; color: gray; background: #f5f5f5; padding: 4px 10px; font-size: 12px; outline: none !important;}

.box {height: 250px; background: #fff; overflow: scroll; border: 1.5px solid gray; padding: 5px 15px;}
.box li {font-size: 14px; line-height: 1.4; position: relative; }
.box li:before {content: ''; box-shadow: 0px 0px 1px inset; position: absolute; left: -10px; top: 0; width: 5px; height: 100%; background: lightblue; opacity: 0;}

.box li:hover:before,
.box li:focus:before,
.box li.active:before {opacity: 1;} 

.btn-box li:not(:last-child) {margin-bottom: 15px;}

@media screen and (max-width: 1180px){

  .table table tr th {vertical-align: middle;}
}

@media screen and (max-width: 1024px){

  .btn-box {margin: 20px 0;}

  select {margin-bottom: 20px;}

}

@media screen and (max-width: 991px){

  .bid-update li {width: 49%;}

  .sold-items ul {width: 100%; max-width: 100%;}
}

.aution-table select {width: 70px; padding: 0; font-size: 12px; margin: 10px auto;}

@media only screen and (max-width: 1000px)
{

.table tbody .empty td {font-size: 18px}

.responsive-on-1200 thead {display: none;}
.responsive-on-1200 tr {display: block; border-bottom: 1px solid rgba(35, 57, 79, 0.2); margin-bottom: 10px;}
.responsive-on-1200 tr td {width: 100% !important; display: flex; padding: 12px !important; text-align: left !important; border: 1px solid rgba(35, 57, 79, 0.2); border-bottom: 0;}
.responsive-on-1200 tr td:before {content: attr(data-label); padding-left: 10px; font-size: 14px; text-align: left; width: 35%; border-right: 1px solid rgba(35, 57, 79, 0.2); display: inline-flex; align-items: center; margin-right: 15px; padding: 14px 0; margin: -12px 0; margin-right: 15px; color: #000 !important}
.aution-table select {width: 120px; margin: 10px; padding: 2px;}
}
</style>

  <main class="main-wapper">
    <div class="container">
      <div class="row">
        <div class="col-lg-7">
          <div class="auction-nam">
            <h2>
              Auctions 1619-Vehicles
            </h2>
            <h3>
              Tuesday 11/02/2020 at 19:30
            </h3>
          </div>
          <div class="summary">
            <fieldset>
               <legend>Lot summary</legend>
                <h2>Current Bid : AED 0</h2>
                <h4>Bid Increment : AED 500</h4>
            </fieldset>
          </div>
          <div class="detail-table">
            <table>
              <thead>
                <tr>
                  <th class="lot">Lot</th>
                  <th class="reg">Reg no</th>
                  <th class="make">Make</th>
                  <th class="colour">Colour</th>
                  <th class="reverse">Reverse</th>
                  <th class="status">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
                <tr>
                  <td>3212</td>
                  <td>116228</td>
                  <td>NISAN</td>
                  <td>Red</td>
                  <td>AED 0</td>
                  <td class="bolder">Not Sale</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="sold-items">
            <ul>
              <li>Ent: 23</li>
              <li class="green-text">Sold: 0</li>
              <li class="blue-text">PB: 0</li>
              <li class="red-text">Unsold: 0</li>
              <li>O/S: 23</li>
            </ul>
          </div>
          <div class="table">
            <fieldset>
               <legend>Lot summary</legend>
                <table>
                  <thead>
                    <tr>
                      <th>Company</th>
                      <th>Name</th>
                      <th>Site Code</th>
                      <th>Web Site</th>
                      <!-- <th style="background: #fff; border: none;"></th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="empty">
                      <td colspan="6">Online Conected use will<br> display here - we can block user<br> for bulding.</td>
                    </tr>
                  </tbody>
                </table>
            </fieldset>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="row">
            <div class="col-md-8">
              <select>
                <option>Select Sales for auctions.</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
              </select>
            </div>
            <div class="col-md-4">
              <button class="start-auction">START AUCTION</button>
            </div>
          </div>
          <div class="bid-update">
            <fieldset>
               <legend>Hall bids updates</legend>
                <ul>
                  <li>
                    <button class="btn-counter">AED 3,000</button>
                  </li>
                  <li>
                    <button class="btn-counter">AED 3,000</button>
                  </li>
                  <li>
                    <button class="btn-counter">AED 3,000</button>
                  </li>
                  <li>
                    <button class="btn-counter">AED 3,000</button>
                  </li>
                  <li>
                    <button class="btn-counter">AED 3,000</button>
                  </li>
                  <li>
                    <button class="btn-counter">AED 3,000</button>
                  </li>
                </ul>
            </fieldset>
          </div>
          <div class="bids-increment">
            <div class="row">
              <div class="col-md-7">
                <p><input type="text" name=""></p>
              </div>
              <div class="col-md-5">
                <button class="btn-inc">Intial Hall Bids</button>
              </div>
            </div>
          </div>
          <div class="bids-detail">
            <fieldset>
               <legend>Bids</legend>
                <div class="row">
                  <div class="col-lg-8">
                    <div class="box">
                      <ul>
                        <li class="active">Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                        <li>Lot 321 Retracted</li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="btn-box">
                      <ul>
                        <li>
                          <button class="btn-inc">Retract</button>
                        </li>
                        <li>
                          <button class="btn-inc">Sold</button>
                        </li>
                        <li>
                          <button class="btn-inc">Not Sold</button>
                        </li>
                        <li>
                          <button class="btn-inc">Provisional</button>
                        </li>
                        <li>
                          <button class="btn-inc">Sold On Aproval</button>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
            </fieldset>
          </div>  
        </div>
      </div>
      <div class="aution-table responsive-on-1200">
        <table>
          <thead>
            <tr>
              <th>Entered</th>
              <th>Lot</th>
              <th>Reg No</th>
              <th>Make</th>
              <th>Model</th>
              <th>Saller code</th>
              <th>Saller Name</th>
              <th>Reserve</th>
              <th>Sale price</th>
              <th>Buyer</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>
            <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>
            <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>
            <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>
            <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>
            <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>
            <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>
            <tr>
              <td data-label="Entered">28/02/2020</td>
              <td data-label="Lot">1</td>
              <td data-label="Reg No">1161180</td>
              <td data-label="Make">Office</td>
              <td data-label="Model">Furnitures</td>
              <td data-label="Saller code">001234</td>
              <td data-label="Saller Name">Shaker Mohamed Ismail</td>
              <td data-label="Reserve">6000</td>
              <td data-label="Sale price">0.00</td>
              <td data-label="Buyer">*PB* Mohamed Ali</td>
              <td data-label="Status">
                <select>
                  <option>Sold</option>
                  <option>Un-Sold</option>
                  <option>On approval </option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main> 