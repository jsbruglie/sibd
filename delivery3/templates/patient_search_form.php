<div class="container text-centered">

    <div class="row">
        <div class="col">
            <form class="form-inline justify-content-center" action="" method="post">
                <div class="form-group">
                    <input class="form-control mr-sm-2" autofocus name="name" placeholder="Patient name" type="text"
                        <?php if (isset($_POST['name'])) echo 'value="' . $_POST['name'] . '"' ?> />
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <?php if (isset($name_err)): ?>
    <div class="row" style="margin:20px">
        <div class="col">
            <span class="alert alert-warning"><?php echo $name_err;?></span>
        </div>
    </div>
    <?php endif ?>

</div>