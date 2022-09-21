<!-- Manual deposit fields -->
<div id="payment">
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit_date">Deposit Date <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class='input-group'>
                <input type="text" class="form-control" name="deposit_date" value="<?php if(isset($data) && !empty($data)){ echo $data['deposit_date'];} ?>" id="deposit_date" readonly="" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <div class="deposit_date-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name">Bank Name <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="bank_name" 
                id="bank_name"
                 value="<?php if(isset($data) && !empty($data)){ echo $data['bank_name'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="bank_name-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_branch">Bank Branch <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="bank_branch" 
                id="bank_branch"
                value="<?php if(isset($data) && !empty($data)){ echo $data['bank_branch'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="bank_branch-error text-danger"></div>
        </div>
    </div>
 <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit_type">Deposit Type <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="deposit_type" id="deposit_type" class="select2_single form-control" tabindex="-1">
                <option value="" selected="selected" disabled="disabled">Choose</option>
                <option <?php 
                    if( isset($data['deposit_type']) && !empty($data['deposit_type']) && $data['deposit_type'] == 'cash'){ echo "selected"; }?> value="cash">Cash</option>
                <option <?php 
                    if(isset($data) && !empty($data['deposit_type'])  && $data['deposit_type'] == 'online'){ echo "selected"; }?> value="online">Online</option>
                <option <?php 
                    if(isset($data) && !empty($data['deposit_type'])  && $data['deposit_type'] == 'cheque'){ echo "selected"; }?> value="cheque">Cheque</option>
                <option <?php 
                    if(isset($data) && !empty($data['deposit_type'])  && $data['deposit_type'] == 'online_cheque'){ echo "selected"; }?> value="online_cheque">Online Cheque</option>
            </select>
            <div class="deposit_type-error text-danger"></div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit_currency">Deposit Currency <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="deposit_currency" id="deposit_currency" class="select2_single form-control" tabindex="-1">
                <!-- <option value="" selected="selected" disabled="disabled">Choose</option> -->
                <option <?php 
                    if(isset($data) && !empty($data['deposit_currency'])  && $data['deposit_currency'] == 'AED'){ echo "selected"; }?> value="AED">AED</option>
                <!-- <option value="USD">USD</option> -->
            </select>
            <div class="deposit_currency-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_title">Account Title <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="account_title" 
                id="account_title"
                value="<?php if(isset($data) && !empty($data)){ echo $data['account_title'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="account_title-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_number">Account Number <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="account_number" 
                id="account_number"
                value="<?php if(isset($data) && !empty($data)){ echo $data['account_number'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="account_number-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="depositor_name">Depositor Name <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="depositor_name" 
                id="depositor_name"
                value="<?php if(isset($data) && !empty($data)){ echo $data['depositor_name'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="depositor_name-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="depositor_id">Depositor ID <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="depositor_id" 
                id="depositor_id"
                value="<?php if(isset($data) && !empty($data)){ echo $data['depositor_id'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="depositor_id-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="depositor_phone">Depositor Phone # <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="depositor_phone" 
                id="depositor_phone"
                value="<?php if(isset($data) && !empty($data)){ echo $data['depositor_phone'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="depositor_phone-error text-danger"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit_txn_id">Deposit TXN ID <span class="required"></span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" 
                name="deposit_txn_id" 
                id="deposit_txn_id"
                value="<?php if(isset($data) && !empty($data)){ echo $data['deposit_txn_id'];} ?>"
                class="form-control col-md-7 col-xs-12" />
            <div class="deposit_txn_id-error text-danger"></div>
        </div>
    </div>
</div>


<?php if (isset($data)) { ?>
    <script>
        $( document ).ready(function() {
            $('#payment input,select').attr('disabled', 'disabled');
        });
    </script>
<?php }else{ ?>
    
<script>
    /*selectedInputs = ['auction_id','auction_item_id','deposit','deposit_mode','deposit_date',
        'bank_name','bank_branch','deposit_type','deposit_currency','account_title','account_number',
        'depositor_name','depositor_id','depositor_phone','deposit_txn_id'];*/

    selectedInputs = ['auction_id','auction_item_id','deposit','deposit_mode','deposit_date'];

    $('#deposit_date').datetimepicker({
        format: 'YYYY-MM-DD',
        ignoreReadonly : true
    });
</script>
<?php } ?>