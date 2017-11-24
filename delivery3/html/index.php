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
    
    $show_table = (!isset($name) || $valid);
    if ($show_table)
    {
        if (!isset($name)){
            $result = tryQuery("SELECT * FROM patient");
        } else {
            $name_arg = $name . "%";
            $result = tryQuery("SELECT * FROM patient WHERE name LIKE ?", $name_arg);
        } 
        $table = createTable($result,
            [["Name", "name", '<a href="patient.php?id=$number">$name</a>'],
             ["Birthday", "birthday"],
             ["Address", "address"]]
        );
    }
?>

        <div class="container-fluid">

            <?php

                require('../templates/patient_search_form.php');

                if ($show_table){
                    if (!$result) {
                        echo 'No matches found. <a href="register.php?name='. $name . '">Register</a> a new patient?';
                    } else {
                        echo '<div>' . $table . '</div>';
                        if (isset($name)){
                            echo 'Still have not found who you are looking for? <a href="register.php?name='. $name . '">Register</a> a new patient.';
                        }
                    }
                }
            ?>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>