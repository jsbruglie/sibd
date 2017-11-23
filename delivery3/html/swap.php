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
        // TODO - Swap

        // Update end time of the current device to current time

        // Create new entry 
    }
    else
    {
        // TODO - Make sure device is available
        // Query database for devices from the same manufacturer        
        $result = query(
            "SELECT serialnum, manufacturer, model
            FROM device
            WHERE serialnum != ?
                AND manufacturer = ?", $cur_serialnum, $cur_manufacturer);

        // Swap invisible form
        // The currently worn device is needed to generate the available devices table
        $swap_btn = 
            '<form action="" method="post">' .
            '<input type="hidden" name="start" value="' . $start . '">' .
            '<input type="hidden" name="end" value="' . $end . '">' .
            '<input type="hidden" name="patient" value="' . $patient . '>' .
            '<input type="hidden" name="serialnum" value="$serialnum">' .
            '<input type="hidden" name="manufacturer" value="$manufacturer">' .
            '<input type="hidden" name="cur_serialnum" value="' . $cur_serialnum . '">' .
            '<input type="hidden" name="cur_manufacturer" value="' . $cur_manufacturer . '">' .
            '<button type="submit" class="btn btn btn-block btn-primary">Swap</button>' .
            '</form>';

        $dev_table = createTable($result,
            [["Serial number", "serialnum"],
             ["Manufacturer","manufacturer"],
             ["Product model","model"],
             ["Actions","swap", $swap_btn]]
        );
    }

?>

        <div class="container">

        <?php if (isset($dev_table)): ?>
        <h4>Choose an available device</h4>
        <?php echo $dev_table ?>
        <?php endif ?>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>