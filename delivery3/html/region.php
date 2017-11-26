<?php

    /**
     * @file        region.php
     *
     * @brief       Create a region
     *
     * @author      João Borrego
     *              Daniel Sousa
     *              Nuno Ferreira
     */

    // Configuration
    require("../includes/config.php");

    // Handle POST data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["series_id"])){
            $series_id = $_POST["series_id"];
        // Complain only if there has been an invalid submit attempt
        } else if (!empty($_POST["visited"])) {
            $series_id_err = "Please insert the series ID!";
        }
        if (!empty($_POST["elem_index"])) {
            $elem_index = $_POST["elem_index"];
        } else if (!empty($_POST["visited"])) {
            $elem_index_err = "Please insert the element index!";
        }
        if (!empty($_POST["x1"])) {
            $x1 = $_POST["x1"];
        }
        if (!empty($_POST["x2"])) {
            $x2 = $_POST["x2"];
        }
        if (!empty($_POST["y1"])) {
            $y1 = $_POST["y1"];
        }
        if (!empty($_POST["y2"])) {
            $y2 = $_POST["y2"];
        }
        
        // Mandatory parameters
        if (!empty($_POST["patient_number"])) {
            $patient_number = $_POST["patient_number"];
        }
        if (!empty($_POST["request_number"])) {
            $request_number = $_POST["request_number"];
        }
        // TODO - Error if missing?

        if (!empty($_POST["visited"])) {
            if (!isset($x1) || !isset($x2) || !isset($y1) || !isset($y2)) {
                $xy_err = "Please insert the region coordinates!";
            } else if  (!($x1 >= 0 && $x1 <= 1) || !($x2 >= 0 && $x2 <= 1) ||
                        !($y1 >= 0 && $y1 <= 1) || !($y2 >= 0 && $y2 <= 1)) {
                $xy_err = "Please insert normalized coordinates (in [0,1])!";
            }
        }    
    }
    $filled = !empty($series_id) && !empty($elem_index) && empty($xy_err)
        && !empty($patient_number) && !(empty($request_number));

    // Render header
    $title = "Create region";
    require("../templates/header.php");

    if ($filled)
    {
        // TODO - Validate query
        // Ensure the region belongs to a study with the correct request number
        $valid = tryQuery(
            "SELECT patient.name
            FROM patient, request, study, series
            WHERE series.series_id = ?
                AND request.number = ?
                AND patient.number = ?
                AND patient.number = request.patient_id", $series_id, $request_number, $patient_number);

        if ($valid !== false){
            $result = tryQuery(
                "INSERT INTO region (series_id, elem_index, x1, y1, x2, y2) VALUES
                (?, ?, ?, ?, ?, ?)", $series_id, $elem_index, $x1, $y1, $x2, $y2);

            // TODO - Detect overlap - overlap is true if there exists an overlap
            $overlap = tryQuery(
                "SELECT *  
                FROM request,study,series,element, region
                WHERE request.patient_id = ?
                    AND request.number = study.request_number
                    AND request.number = series.request_number
                    AND study.description = series.description
                    AND region.series_id = series.series_id 
                    AND region_overlaps_element(region.series_id, region.elem_index,?,?,?,?)
                    ORDER BY study.date DESC LIMIT 1", $patient_number,$x1,$y1,$x2,$y2);
        }
    }

?>

        <div class="container">

            <?php if (!$filled):
            require('../templates/add_region_form.php');
            ?>

            <?php elseif (!$valid): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> Invalid parameters given.
            </div>

            <?php elseif ($result === false): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> Could not insert region in database. Make sure the data element exists.
            </div>

            <?php else: ?>
            <div class="alert alert-success">
                <strong>Success!</strong> Inserted region in database.
                <?php if ($overlap): ?>
                Overlap with previously acquired regions detected.
                <?php else: ?>
                No overlap with previously acquired regions detected: new clinical evidence.
                <?php endif ?>
            </div>
            <?php endif ?>

            <a class="btn btn-link" href="index.php">Go Back</a>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>