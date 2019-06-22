<div class="container">

<div class="card page-content">
<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-9">
                <h2>Pay Supplier</h2>
            </div>
            <div class="col-3 text-right">
                <a class="btn btn-sm btn-secondary" href="<?php echo base_url(); ?>dashboard/">Home</a>
            </div>
        </div><br>

        <div>
            <div class="instruction">
                <h5>Hint:</h5>
                <ul>
                    <li>Fill out the details below to make a payment.</li>
                    <li>Then click on the "Continue" button.</li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-1">
                    <div class="form-body">
                        <?php echo form_open('payments/confirm_payment/'); ?>
                        
                        <div class="form-group">
                            <label for="title">Select supplier</label>
                            <select class="form-control" name="recipient" required>
                                <option value="">Choose</option>
                                <?php
                                    foreach($suppliers as $supplier){
                                        echo '<option value="'.$supplier['recipient_code'].'">'.$supplier['name'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Amount</label>
                            <input class="form-control" type="text" name="amount" placeholder="minimum of 100001" required />
                            <small><strong>NOTE:</strong> Enter amount in kobo. Eg. 750000 to pay NGN 7,500.</small>
                        </div>

                        <div class="form-group">
                            <label for="title">Item supplied (optional)</label>
                            <input class="form-control" type="text" name="reason" />
                        </div>

                        <div class="text-center">
                            <input class="btn btn-primary" type="submit" name="submit" value="Continue" />
                        </div>

                        <?php form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
</div>
</div>
