<div class="container">

<div class="card page-content">
<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-8">
                <h2>New Supplier</h2>
            </div>
            <div class="col-md-4 text-right">
                <a class="btn btn-sm btn-secondary" href="<?php echo base_url(); ?>suppliers/">My Suppliers</a>
            </div>
        </div><br>

        <div>
            <div class="instruction">
                <h5>Hint:</h5>
                <ul>
                    <li>Fill out the details of the new supplier below.</li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-1">
                    <div class="form-body">
                        <?php echo form_open('suppliers/save/'); ?>

                        <div class="form-group">
                            <label for="title">Supplier name</label>
                            <input class="form-control" type="text" name="name" required />
                        </div>

                        <div class="form-group">
                            <label for="title">Supplier bank</label>
                            <select class="form-control" name="bank_code" required>
                                <option value="">Choose bank</option>
                                <?php
                                    foreach($banks as $bank){
                                        echo "<option value='".$bank['code']."'>".$bank['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Supplier account number</label>
                            <input class="form-control" type="text" name="account_number" required />
                            <small><strong>NOTE:</strong> 10 digit account number</small>
                        </div>

                        <div class="text-center">
                            <input class="btn btn-primary" type="submit" name="submit" value="Save" />
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