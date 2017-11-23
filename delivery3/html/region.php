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
        } else if (!empty($_POST["visited"])){
            $series_id_err = "Please insert the series ID!";
        }
        if (!empty($_POST["elem_index"])){
            $elem_index = $_POST["elem_index"];
        } else if (!empty($_POST["visited"])){
            $elem_index_err = "Please insert the element index!";
        }
        if (!empty($_POST["x1"])){
            $x1 = $_POST["x1"];
        } 
        if (!empty($_POST["x2"])){
            $x2 = $_POST["x2"];
        } 
        if (!empty($_POST["y1"])){
            $y1 = $_POST["y1"];
        } 
        if (!empty($_POST["y2"])){
            $y2 = $_POST["y2"];
        }

        if (!empty($_POST["series_id"])){
            $series_id = $_POST["series_id"];
        } else {
            // TODO - Error;
        }

        if (!empty($_POST["visited"]) && (!isset($x1) || !isset($x2) || !isset($y1) || !isset($y2))){
            $xy_err = "Please insert the region coordinates!";
        }
    }
    $valid = false;

    // Render header
    $title = "Create region";
    require("../templates/header.php");

    if ($valid)
    {
        // Add study to DB (request number is set to AI)
        $result = query(
            "INSERT INTO region (series_id, elem_index, x1, y1, x2, y2) VALUES
            (?, ?, ?, ?, ?, ?)", $series_id, $elem_index, $x1, $y1, $x2, $y2);
    }
    else
    {

    }

?>

        <div class="container">
            
            <?php if (!isset($result))
            require('../templates/add_region_form.php');
            ?>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>