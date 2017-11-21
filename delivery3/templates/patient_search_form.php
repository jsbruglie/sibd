<form class="form-horizontal" action="" method="post">
    <div class="form-group">
        <input autofocus name="name" placeholder="Patient name" type="text"
            <?php if (isset($_POST['name'])) echo 'value="' . $_POST['name'] . '"' ?> />
        <button type="submit" class="btn btn-primary">Search</button>
    </div> 

    <?php if (isset($name_err)): ?>
    <span class="alert alert-warning"><?php echo $name_err;?>
    <?php endif ?></span>
</form>