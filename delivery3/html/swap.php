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

    // Handle GET data
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!empty($_GET["sn"])){
            $cur_sn = $_GET["sn"];
        }
        if (!empty($_GET["m"])){
            $cur_m = $_GET["m"];
        }
    }

    // Handle POST data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["cur_sn"])){
            $cur_sn = $_POST["cur_sn"];
        }
        if (!empty($_POST["cur_m"])){
            $cur_m = $_POST["cur_m"];
        }
        if (!empty($_POST["new_sn"])){
            $new_sn = $_POST["new_sn"];
        }
        if (!empty($_POST["new_m"])){
            $new_m = $_POST["new_m"];
        }
    }
    // TODO
    $valid = false;

    // Render header
    $title = "Swap device";
    require("../templates/header.php");
    
    if ($valid)
    {
        // TODO - Swap
    }
    else
    {
        // Query database for devices from the same manufacturer
        $result = query(
            "SELECT DISTINCT serialnum, manufacturer, model
            FROM device
            WHERE serialnum != ?
                AND manufacturer = ?", $cur_sn, $cur_m);
        
        // Swap invisible form
        $swap_btn = 
            '<form action="" method="post">' .
            '<input type="hidden" name="cur_sn" value="' . $cur_sn . '">' .
            '<input type="hidden" name="cur_m" value="' . $cur_m . '">' .
            '<input type="hidden" name="new_sn" value="$serialnum">' .
            '<input type="hidden" name="new_m" value="$manufacturer">' .
            '<button type="submit" class="btn btn-primary">Swap</button>' .
            '</form>';

        $dev_table = createTable($result,
            [["Serial number", "serialnum"],
             ["Manufacturer","manufacturer"],
             ["Product model","model"],
             ["Swap","swap", $swap_btn]]
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