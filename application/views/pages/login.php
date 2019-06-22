<div class="container">

<div class="row">
    <div class="col-md-4 offset-md-4">
        <h2 class="text-center">Login</h2>

        <?php echo form_open('dashboard/sign_in/'); ?>

            <div class="form-group">
                <label for="title">Email</label>
                <input class="form-control" type="email" name="email" required />
            </div>

            <div class="form-group">
                <label for="title">Password</label>
                <input class="form-control" type="password" name="password" required />
            </div>

            <div class="text-center">
                <input class="btn btn-primary" type="submit" name="submit" value="Login" />
            </div>

        <?php form_close(); ?>

    </div>

</div>
</div>