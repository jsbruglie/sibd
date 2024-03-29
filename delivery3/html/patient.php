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
        $result = tryQuery(
            "SELECT serialnum, manufacturer, model, start, end, patient
            FROM wears, device, patient
            WHERE patient.number = wears.patient
                AND manufacturer = manuf
                AND serialnum = snum
                AND patient.number = :patient
                AND timestampdiff(second,wears.end,current_timestamp) <= 0", 
            array(':patient' => $id));

        if ($result){

            // Replace invisible form button
            $replace_btn =
                '<form action="swap.php" method="post">' .
                '<input type="hidden" name="start" value="$start">' .
                '<input type="hidden" name="end" value="$end">' .
                '<input type="hidden" name="patient" value="$patient">' .
                '<input type="hidden" name="cur_serialnum" value="$serialnum">' .
                '<input type="hidden" name="cur_manufacturer" value="$manufacturer">' .
                '<button type="submit" class="btn btn-sm btn-block btn-primary">Replace</button>' .
                '</form>';

            // Study invisible form button
            $study_btn =
                '<form action="study.php" method="post">' .
                '<input type="hidden" name="serialnum" value="$serialnum">' .
                '<input type="hidden" name="manufacturer" value="$manufacturer">' .
                '<button type="submit" class="btn btn-sm btn-block btn-primary">Add Study</button>' .
                '</form>';

            // Actions column
            $device_action = '<div class="row"><div class="col">' . $replace_btn . '</div><div class="col">' . $study_btn . '</div></div>';

            $cur_dev_table = createTable($result,
                [["Start", "start"],
                 ["Serial number", "serialnum"],
                 ["Manufacturer", "manufacturer"],
                 ["Product model", "model"],
                 ["Actions", "_", $device_action]]
            );
        }

        // Query database in order to obtain previously worn devices
        $result = tryQuery(
            "SELECT serialnum, manufacturer, model, start, end
            FROM wears, device, patient
            WHERE patient.number = :patient
                AND patient.number = wears.patient
                AND manufacturer = manuf
                AND serialnum = snum
                AND timestampdiff(second,wears.end,current_timestamp) > 0", 
            array(':patient' => $id));

        if ($result){
            $old_dev_table = createTable($result,
                [["Start", "start"],
                 ["End", "end"],
                 ["Serial number", "serialnum"],
                 ["Manufacturer", "manufacturer"],
                 ["Product model", "model"]]
            );
        }
        $no_entries = !isset($cur_dev_table) && !isset($old_dev_table);

        // Query database in order to obtain existing studies
        $result = tryQuery(
            "SELECT date, description, study.doctor_id, serial_number, manufacturer, request_number
            FROM study, request
            WHERE request.patient_id = :patient_id
                AND request.number = study.request_number",
            array(':patient_id' => $id));

        if ($result){

            // Add region invisible form button
            $region_btn =
                '<form action="region.php" method="post">' .
                '<input type="hidden" name="patient_number" value="' . $id . '">' .
                '<input type="hidden" name="request_number" value="$request_number">' .
                '<input type="hidden" name="description" value="$description">' .
                '<button type="submit" class="btn btn-sm btn-block btn-primary btn-space">Add region</button>' .
                '</form>';

            $studies_table = createTable($result,
                [["Date", "date"],
                 ["Description", "description"],
                 ["Doctor ID", "doctor_id"],
                 ["Serial Number","serial_number"],
                 ["Manufacturer","manufacturer"],
                 ["Action","_", $region_btn]]
            );
        }

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

            <?php elseif (!isset($cur_dev_table)): ?>
            <p>No registered device entries for this patient.</p>
            <?php endif ?>

            <?php if (isset($studies_table)): ?>
            <h4>Studies</h4>
            <?php echo $studies_table ?>

            <?php else: ?>
            <p>No registered study entries for this patient.</p>
            <?php endif ?>

            <a class="btn btn-link" href="index.php">Go Back</a>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>