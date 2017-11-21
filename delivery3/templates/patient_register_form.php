<form action="" method="post">
    <div class="form-group">
        <label for="name">Name*</label>
        <input autofocus name="name" placeholder= "Patient name" type="text"
            <?php 
                if (isset($_POST['name'])) echo 'value="' . $_POST['name'] . '"';
                else if (isset($name)) echo 'value="' . $name . '"';
            ?>
        />
        <?php if (isset($name_err)): ?>
        <span class="alert alert-danger" role="alert"><?php echo $name_err;?>
        <?php endif ?></span>
    </div> 
    <div class="form-group">
        <label for="birthday">Birthday*</label>
        <input autofocus name="birthday" placeholder="Patient Birthday" type="date"
            <?php if (isset($_POST['birthday'])) echo 'value="' . $_POST['birthday'] . '"' ?> />
        <?php if (isset($birthday_err)): ?>
        <span class="alert alert-danger" role="alert"><?php echo $birthday_err;?>
        <?php endif ?></span>
    </div>
    <div class="form-group">
        <label for="address">Address*</label>
        <input autofocus name="address" placeholder="Patient Address" type="text"
            <?php if (isset($_POST['address'])) echo 'value="' . $_POST['address'] . '"' ?> />
        <?php if (isset($address_err)): ?>
        <span class="alert alert-danger" role="alert"><?php echo $address_err;?>
        <?php endif ?></span>
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
</form>