<div class="container">

<div class="card page-content">
<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-8">
                <h2>My Suppliers</h2>
            </div>
            <div class="col-4 text-right">
                <a class="btn btn-sm btn-secondary" href="<?php echo base_url(); ?>suppliers/create/">Add Supplier</a>
            </div>
        </div><br>
        <?php 
            if(count($suppliers) < 1){
                echo "<div class='card'>There are no suppliers yet.</div>";
            }else{
        ?>
        <table class="table">
        <thead>
            <tr>
            <th scope="col">Supplier</th>
            <th scope="col">Bank Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($suppliers as $supplier){
                    echo '<tr><td>'.$supplier['name'].'</td><td>'.$supplier['details']['bank_name'].' ('.$supplier['details']['account_number'].')</td></tr>';
                }
            ?>
        </tbody>
        </table>
        <?php } ?>

    </div>

</div>
</div>
</div>