<form action="region.php" method="post">
    <h4>New Region</h4>
    <div class="form-group required row">
        <div class="col-sm-6">
            <label class="col-form-label" for="name">Series ID</label>
            <input class="form-control" autofocus name="series_id" placeholder= "Element index" type="series_id"
                <?php 
                    if (isset($_POST['series_id'])) echo 'value="' . $_POST['series_id'] . '"';
                    else if (isset($series_id)) echo 'value="' . $series_id . '"';
                ?>
            />
            <?php if (isset($series_id_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $series_id_err;?>
            <?php endif ?></span>
        </div>
        <div class="col-sm-6">
            <label class="col-form-label" for="name">Element Index</label>
            <input class="form-control" autofocus name="elem_index" placeholder= "Element index" type="elem_index"
                <?php 
                    if (isset($_POST['elem_index'])) echo 'value="' . $_POST['elem_index'] . '"';
                    else if (isset($elem_index)) echo 'value="' . $elem_index . '"';
                ?>
            />
            <?php if (isset($elem_index_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $elem_index_err;?>
            <?php endif ?></span>
        </div>
    </div>
    <div class="form-group required row">
        <div class="col-sm-3">
            <label class="col-form-label" for="doctor_id">x1</label>
            <input class="form-control" name="doctor_id" placeholder="x1" type="text"
                <?php 
                    if (isset($_POST['x1'])) echo 'value="' . $_POST['x1'] . '"';
                    else if (isset($x1)) echo 'value="' . $x1 . '"';
                ?>
            />
        </div>
        <div class="col-sm-3">
            <label class="col-form-label" for="doctor_id">x2</label>
            <input class="form-control" name="doctor_id" placeholder="x2" type="text"
                <?php 
                    if (isset($_POST['x2'])) echo 'value="' . $_POST['x2'] . '"';
                    else if (isset($x2)) echo 'value="' . $x2 . '"';
                ?>
            />
        </div>
        <div class="col-sm-3">
            <label class="col-form-label" for="doctor_id">y1</label>
            <input class="form-control" name="doctor_id" placeholder="y1" type="text"
                <?php 
                    if (isset($_POST['y1'])) echo 'value="' . $_POST['y1'] . '"';
                    else if (isset($y1)) echo 'value="' . $y1 . '"';
                ?>
            />
        </div>
        <div class="col-sm-3">
            <label class="col-form-label" for="doctor_id">y2</label>
            <input class="form-control" name="doctor_id" placeholder="y2" type="text"
                <?php 
                    if (isset($_POST['y2'])) echo 'value="' . $_POST['y2'] . '"';
                    else if (isset($y2)) echo 'value="' . $y2 . '"';
                ?>
            />
        </div>

        <?php if (isset($xy_err)): ?>
        <span class="alert text-danger" role="alert"><?php echo $xy_err;?>
        <?php endif ?></span>
    </div>

    <input type="hidden" name="visited" value="true">

    <button type="submit" class="btn btn-primary">Add Region</button>
</form>