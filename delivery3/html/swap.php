<?php

    /**
     * @file        swap.php
     *
     * @brief       Swap device with another from the same manufacturer
     *
     * @author      João Borrego
     *              Daniel Sousa
     *              Nuno Ferreira
     */

    // Configuration
    require("../includes/config.php");

    // Handle POST data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["patient"])){
            $patient = $_POST["patient"];
        }
        if (!empty($_POST["start"])){
            $start = $_POST["start"];
        }
        if (!empty($_POST["end"])){
            $end = $_POST["end"];
        }
        if (!empty($_POST["serialnum"])){
            $serialnum = $_POST["serialnum"];
        }
        if (!empty($_POST["manufacturer"])){
            $manufacturer = $_POST["manufacturer"];
        }
        if (!empty($_POST["cur_serialnum"])){
            $cur_serialnum = $_POST["cur_serialnum"];
        }
        if (!empty($_POST["cur_manufacturer"])){
            $cur_manufacturer = $_POST["cur_manufacturer"];
        }
    }
    $valid = !empty($patient) && !empty($start) && !empty($end)
    	&& !empty($serialnum) && !empty($manufacturer);
    	// && !empty($cur_serialnum) && !empty($cur_manufacturer);

    // Render header
    $title = "Swap device";
    require("../templates/header.php");

    if ($valid)
    {
        $date = date("Y-m-d H:i:s", time());

        // Close current period
        $update_period = [
            'UPDATE period NATURAL JOIN wears
            SET end = :end WHERE snum = :snum AND manuf = :manuf
                AND timestampdiff(second, wears.end , current_timestamp) <= 0',
            array(':end' => $date, ':snum' => $cur_serialnum, ':manuf' => $cur_manufacturer)
        ];

        // Insert a new time period with undefined end
        $new_period = [
            'INSERT INTO period (start, end) VALUES
            (:start, :end)',
            array(':start' => $date, ':end' => "2999-12-31 00:00:00")
        ];

        $new_wears = [
            'INSERT INTO wears (start, end, snum, manuf, patient) VALUES
            (:start, :end, :snum, :manuf, :patient)',
            array(  ':start' => $date, ':end' => "2999-12-31 00:00:00", 
                    ':snum' => $serialnum, ':manuf' => $manufacturer,
                    ':patient' => $patient)
        ];

        $swap_result = transact([$update_period, $new_period, $new_wears]);
        list($success, $sql_error) = $swap_result;
    }
    else
    {
        // TODO - Make sure device is available? POST data may not be accurate
        // Query database for devices from the same manufacturer
        $result = tryQuery(
            "SELECT serialnum, manufacturer, model
            FROM device
            WHERE serialnum != :snum AND manufacturer = :manuf",
            array(':snum' => $cur_serialnum, ':manuf' => $cur_manufacturer)
        );

        if ($result){

            // Swap invisible form
            // The currently worn device is needed to generate the available devices table
            $swap_btn =
                '<form action="swap.php" method="post">' .
                '<input type="hidden" name="start" value="' . $start . '">' .
                '<input type="hidden" name="end" value="' . $end . '">' .
                '<input type="hidden" name="patient" value="' . $patient . '">' .
                '<input type="hidden" name="serialnum" value="$serialnum">' .
                '<input type="hidden" name="manufacturer" value="$manufacturer">' .
                '<input type="hidden" name="cur_serialnum" value="' . $cur_serialnum . '">' .
                '<input type="hidden" name="cur_manufacturer" value="' . $cur_manufacturer . '">' .
                '<button type="submit" class="btn btn-sm btn-block btn-primary">Swap</button>' .
                '</form>';

            $dev_table = createTable($result,
                [["Serial number", "serialnum"],
                 ["Manufacturer","manufacturer"],
                 ["Product model","model"],
                 ["Actions","swap", $swap_btn]]
            );
        }
    }

?>

        <div class="container">

            <?php if (empty($result)): ?>
            <h4>No devices available for replacement</h4>

            <?php elseif (isset($dev_table)): ?>
            <h4>Choose an available device</h4>
            <?php echo $dev_table ?>

            <?php elseif (isset($success) && $success == false): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> Could not replace device: <p><?= $sql_error ?></p>
            </div>

            <?php elseif (isset($success)): ?>
            <div class="alert alert-success">
                <strong>Success!</strong> Replaced device.
            </div>
            <?php endif ?>

            <a class="btn btn-link" href="index.php">Go Back</a>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>