<?php
    
    /**
     * @file        patient.php
     * 
     * @brief       Register patient
     *
     * @author      João Borrego
     *              Daniel Sousa
     *              Nuno Ferreira 
     */

    // Configuration
    require("../includes/config.php"); 

    // Handle GET data
    if (!empty($_GET["id"])) {
        $id = $_GET["id"];
    }

    // Get devices associated with a given patient
    if (isset($id)){
        // Query database in order to obtain currently worn devices
        $result = query(
            "SELECT serialnum, manufacturer, model, start, end, patient
            FROM wears, device, patient
            WHERE patient.number = wears.patient
                AND manufacturer = manuf
                AND serialnum = snum
                AND patient.number = ?
                AND datediff(current_date(), cast(wears.end AS date)) <= 0", $id);
        
        // Replace invisible form
        $replace_btn = 
            '<form action="swap.php" method="post">' .
            '<input type="hidden" name="start" value="$start">' .
            '<input type="hidden" name="end" value="$end">' .
            '<input type="hidden" name="patient" value="$patient">' .
            '<input type="hidden" name="cur_serialnum" value="$serialnum">' .
            '<input type="hidden" name="cur_manufacturer" value="$manufacturer">' .
            '<button type="submit" class="btn btn-primary">Replace</button>' .
            '</form>';

        if ($result){
            $cur_dev_table = createTable($result,
                [["Serial number", "serialnum"],
                 ["Manufacturer", "manufacturer"],
                 ["Product model", "model"],
                 ["Actions", "_", $replace_btn]]
            );
        }
        
        // Query database in order to obtain previously worn devices
        $result = query(
            "SELECT serialnum, manufacturer, model
            FROM wears, device, patient
            WHERE patient.number = ?
                AND patient.number = wears.patient
                AND manufacturer = manuf
                AND serialnum = snum
                AND datediff(current_date(), cast(wears.end AS date)) > 0", $id);
        if ($result){
            $old_dev_table = createTable($result,
                [["Serial number", "serialnum"],
                 ["Manufacturer", "manufacturer"],
                 ["Product model", "model"]]
            );
        }
        $no_entries = !isset($cur_dev_table) && !isset($old_dev_table);
    }

    // Render header
    $title = 'Patient devices';
    require("../templates/header.php");
?>

        <div class="container-fluid">

            <?php if (!isset($id)): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> No patient provided.
            </div>
            <?php endif ?>

            <?php if (isset($cur_dev_table)): ?>
            <h4>Current device</h4>
            <?php echo $cur_dev_table ?>
            <?php endif ?>

            <?php if (isset($old_dev_table)): ?>
            <h4>Old devices</h4>
            <?php echo $old_dev_table ?>
            <?php endif ?>

            <?php if (isset($no_entries) && $no_entries): ?>
            <p>No registered device entries for this patient.</p>
            <?php endif ?>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>