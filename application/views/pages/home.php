<div class="container">

<div class="row">
    <div class="col-md-12">
        <div class="card balance-summary">
            <p>Your account balance is:</p>
            <h2>NGN <?php echo $balance; ?></h2>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card main-action">
                    <a class="btn btn-lg btn-primary" href="<?php echo base_url(); ?>payments/make_payment/">Pay a Supplier</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card main-action">
                    <a class="btn btn-lg btn-primary" href="<?php echo base_url(); ?>payments/bulk_transfer/">Pay Multiple Suppliers</a>
                </div>
            </div>
        </div>

    </div>

</div>
</div>