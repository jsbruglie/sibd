<form class="form-inline justify-content-center" action="" method="post">
    <div class="form-group row">
        <div class="col">
            <div class="form-group">
                <input class="form-control" autofocus name="name" placeholder="Patient name" type="text"
                    <?php if (isset($_POST['name'])) echo 'value="' . $_POST['name'] . '"' ?> />
            </div>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>

<?php if (isset($name_err)): ?>
<div style="margin-top:25px">
<span class="alert alert-warning"><?php echo $name_err;?></span>
</div>
<?php endif ?>