 <div class="row">
              <div class="col-md-12">
                <!-- <div class="x_panel">
                  <div class="x_title">
                    <h2>Transaction Detail </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div> 
                </div> -->
                <div class="x_content">

                  <div class="content invoice" style="margin: 20px;">
                    <!-- title row -->
                    <div class="row">
                      <div class="col-xs-12 invoice-header">
                        <h1>
                                        <i class="fa fa-globe"></i> Detail
                                        <small class="pull-right"><?php echo $current_date ?></small>
                                    </h1>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                      <div class="col-sm-4 invoice-col">
                        <h3> <b>From</b></h3>
                        <address>
                                        <strong><?php echo $admin_detail[0]['username'] ?></strong>
                                        <!-- <br>795 Freedom Ave, Suite 600 -->
                                        <br><?php echo $admin_detail[0]['city']; ?>
                                        <br><?php echo $admin_detail[0]['phone']; ?>
                                        <br><?php echo $admin_detail[0]['email']; ?>
                                    </address>
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-4 invoice-col">
                       <h3> <b>To</b></h3>
                        <address>       <?php if(!empty($user_transaction)){  ?>
                                        <strong><?php echo $user_transaction[0]['username'] ?></strong>
                                        <!-- <br>795 Freedom Ave, Suite 600 -->
                                        <br><?php echo $user_transaction[0]['city']; ?>
                                        <br><?php echo $user_transaction[0]['phone']; ?>
                                        <br><?php echo $user_transaction[0]['email']; ?>
                                    </address>
                                  <?php }?>
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-4 invoice-col">
                        <b><h4> <b>Transaction ID </b></h4> <h3><?php echo $transacton_detail[0]['transaction_id'] ?></h3> </b>
                        <br>
                        <br>
                        <b>Deposit Mode:</b> <?php switch ($transacton_detail[0]['payment_type']) {
                          case 'cash':
                            echo "Cash";
                            break;
                          case 'card':
                            echo "Card";
                            break;
                          case 'bank_transfer':
                            echo "Bank Transfer";
                            break;
                          case 'cheque':
                            echo "Cheque";
                            break;
                          case 'manual_deposit':
                            echo "Manual Deposit";
                            break;
                          
                          default:
                            echo "";
                            break;
                        }
                         ?>
                        <br>
                        <b>Deposit Date:</b> <?= date('Y/m/d', strtotime($transacton_detail[0]['created_on']));//2/22/2014 ?>
                        <br>
                        <b>Deposit Amountamount:</b> <?= $transacton_detail[0]['amount']; //968-34567 ?> AED
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                      <div class="col-xs-12 table">
                      <!--   <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Qty</th>
                              <th>Product</th>
                              <th>Serial #</th>
                              <th style="width: 59%">Description</th>
                              <th>Subtotal</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>Call of Duty</td>
                              <td>455-981-221</td>
                              <td>El snort testosterone trophy driving gloves handsome gerry Richardson helvetica tousled street art master testosterone trophy driving gloves handsome gerry Richardson
                              </td>
                              <td>$64.50</td>
                            </tr>
                            <tr>
                              <td>1</td>
                              <td>Need for Speed IV</td>
                              <td>247-925-726</td>
                              <td>Wes Anderson umami biodiesel</td>
                              <td>$50.00</td>
                            </tr>
                            <tr>
                              <td>1</td>
                              <td>Monsters DVD</td>
                              <td>735-845-642</td>
                              <td>Terry Richardson helvetica tousled street art master, El snort testosterone trophy driving gloves handsome letterpress erry Richardson helvetica tousled</td>
                              <td>$10.70</td>
                            </tr>
                            <tr>
                              <td>1</td>
                              <td>Grown Ups Blue Ray</td>
                              <td>422-568-642</td>
                              <td>Tousled lomo letterpress erry Richardson helvetica tousled street art master helvetica tousled street art master, El snort testosterone</td>
                              <td>$25.99</td>
                            </tr>
                          </tbody>
                        </table> -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->

                   <!--  <div class="row">
                      <div class="col-xs-6">
                        <p class="lead">Payment Methods:</p>
                        <img src="<?php echo base_url(); ?>uploads/visa.png" alt="Visa">
                        <img src="<?php echo base_url(); ?>uploads/mastercard.png" alt="Mastercard">
                        <img src="images/american-express.png" alt="American Express"> 
                        <img src="<?php echo base_url(); ?>uploads/paypal.png" alt="Paypal">
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                          Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                        </p>
                      </div>
                      <div class="col-xs-6">
                       <p class="lead">Amount Due 2/22/2014</p> 
                        <div class="table-responsive">
                          <table class="table">
                            <tbody>
                              <tr>
                                <th style="width:50%">Subtotal:</th>
                                <td>250.30</td>
                              </tr>
                              <tr>
                                <th>Tax (9.3%)</th>
                                <td>10.34</td>
                              </tr>
                              <tr>
                                <th>Shipping:</th>
                                <td>5.80</td>
                              </tr>
                              <tr>
                                <th>Total:</th>
                                <td><?php echo $transacton_detail[0]['amount'].'AED' ?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div> -->
                    <div class="row no-print">
                      <div class="col-xs-12">
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>