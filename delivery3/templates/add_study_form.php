<form action="study.php" method="post">
    <h4>New Study</h4>
    <div class="form-group required row">
        <div class="col-sm-4">
            <label class="col-form-label" for="name">Date</label>
            <input class="form-control" autofocus name="date" placeholder= "Study date" type="date"
                <?php 
                    if (isset($_POST['date'])) echo 'value="' . $_POST['date'] . '"';
                    else if (isset($date)) echo 'value="' . $date . '"';
                ?>
            />
            <?php if (isset($date_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $date_err;?>
            <?php endif ?></span>
        </div>
        <div class="col">
            <label class="col-form-label" for="description">Description</label>
            <input class="form-control" name="description" placeholder="Study description" type="text"
                <?php 
                    if (isset($_POST['description'])) echo 'value="' . $_POST['description'] . '"';
                    else if (isset($description)) echo 'value="' . $description . '"';
                ?>
            />
            <?php if (isset($description_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $description_err;?>
            <?php endif ?></span>
        </div>
    </div>
    <div class="form-group required row">
        <div class="col sm-4">
            <label class="col-form-label" for="doctor_id">Doctor ID</label>
            <input class="form-control" name="doctor_id" placeholder="Doctor" type="text"
                <?php 
                    if (isset($_POST['doctor_id'])) echo 'value="' . $_POST['doctor_id'] . '"';
                    else if (isset($doctor_id)) echo 'value="' . $doctor_id . '"';
                ?>
            />
            <?php if (isset($doctor_id_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $doctor_id_err;?>
            <?php endif ?></span>
        </div>
        <div class="col">
            <label class="col-form-label" for="serialnum">Device serial number</label>
            <input readonly="readonly" class="form-control" name="serialnum" placeholder="Study device serial number" type="text"
                <?php 
                    if (isset($_POST['serialnum'])) echo 'value="' . $_POST['serialnum'] . '"';
                    else if (isset($serialnum)) echo 'value="' . $serialnum . '"';
                ?>
            />
            <?php if (isset($serialnum_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $serialnum_err;?>
            <?php endif ?></span>
        </div>
        <div class="col">
            <label class="col-form-label" for="manufacturer">Device manufacturer</label>
            <input readonly="readonly" class="form-control" name="manufacturer" placeholder="Study device manufacturer" type="text"
                <?php 
                    if (isset($_POST['manufacturer'])) echo 'value="' . $_POST['manufacturer'] . '"';
                    else if (isset($manufacturer)) echo 'value="' . $manufacturer . '"';
                ?>
            />
            <?php if (isset($manufacturer_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $manufacturer_err;?>
            <?php endif ?></span>
        </div>
    </div>

    <h4>New Series</h4>
    <div class="form-group required row">
        <div class="col-sm-4">
            <label class="col-form-label" for="series_name">Name</label>
            <input class="form-control" name="series_name" placeholder= "Series" type="text"
                <?php if (isset($_POST['series_name'])) echo 'value="' . $_POST['series_name'] . '"'; ?>
            />
            <?php if (isset($series_name_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $series_name_err;?>
            <?php endif ?></span>
        </div>
        <div class="col">
            <label class="col-form-label" for="series_description">Description</label>
            <input class="form-control" name="series_description" placeholder="Series description" type="text"
                <?php if (isset($_POST['series_description'])) echo 'value="' . $_POST['series_description'] . '"' ?> />
            <?php if (isset($series_description_err)): ?>
            <span class="alert text-danger" role="alert"><?php echo $series_description_err;?>
            <?php endif ?></span>
        </div>
    </div>

    <input type="hidden" name="visited" value="true">

    <button type="submit" class="btn btn-primary">Add Study and Series</button>
</form>