<form action="" method="post">
    <h2>New patient</h2>
    <div class="form-group required row">
        <div class="col-sm-8">
            <label class="col-form-label" for="name">Name</label>
            <input class="form-control" autofocus name="name" placeholder= "Patient name" type="text"
                <?php 
                    if (isset($_POST['name'])) echo 'value="' . $_POST['name'] . '"';
                    else if (isset($name)) echo 'value="' . $name . '"';
                ?>
            />
            <?php if (isset($name_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $name_err;?>
            <?php endif ?></span>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label" for="birthday">Birthday</label>
            <input class="form-control" name="birthday" placeholder="Patient Birthday" type="date"
                <?php if (isset($_POST['birthday'])) echo 'value="' . $_POST['birthday'] . '"' ?> />
            <?php if (isset($birthday_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $birthday_err;?>
            <?php endif ?></span>
        </div>
    </div>
    <div class="form-group required row">
        <div class="col">
            <label class="col-form-label" for="address">Address</label>
            <input class="form-control" name="address" placeholder="Patient Address" type="text"
                <?php if (isset($_POST['address'])) echo 'value="' . $_POST['address'] . '"' ?> />
            <?php if (isset($address_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $address_err;?>
            <?php endif ?></span>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
</form>