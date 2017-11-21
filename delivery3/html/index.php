<?php
    
    /**
     * @file        index.php
     *
     * @brief       Homepage and patient search by name
     *
     * @author      João Borrego
     *              Daniel Sousa
     *              Nuno Ferreira 
     */
    
    // Configuration
    require("../includes/config.php"); 

    // Handle POST data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $name_err = "Please insert a patient name!";
        } else {
            $name = $_POST["name"];
        }
    }
    $valid = !empty($name) && empty($name_err);

    // Render header
    $title = "Home";
    require("../templates/header.php");
    
    if ($valid)
    {
        // Append % to the search string in order to perform partial match in SQL query
        $name_arg = $name . "%";
        // Search for a patient by name and create HTML table
        $result = query("SELECT * FROM patient WHERE name LIKE ?", $name_arg);
        $table = createPatientTable($result, ["name", "birthday", "address"]);
    }
?>

        <div class="container-fluid">

            <?php

                require('../templates/patient_search_form.php');

                if ($valid){
                    if (!$result) {
                        echo 'No matches found. <a href="register.php?name='. $name . '">Register</a> a new patient?';
                    } else {
                        echo '<div>' . $table . '</div>';
                    }
                }
            ?>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>