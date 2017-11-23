<?php
    
    /**
     * @file        study.php
     *
     * @brief       Create a new study
     *
     * @author      João Borrego
     *              Daniel Sousa
     *              Nuno Ferreira 
     */
    
    // Configuration
    require("../includes/config.php"); 

    // Handle POST data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["date"])){
            $date = $_POST["date"];
        // Complain only if there has been an invalid submit attempt
        } else if (isset($_POST["visited"])){
            $date_err = "Please insert a date!";
        }
        if (!empty($_POST["description"])){
            $description = $_POST["description"];
        } else if (isset($_POST["visited"])){
            $description_err = "Please insert a description!";
        }
        if (!empty($_POST["doctor_id"])){
            $doctor_id = $_POST["doctor_id"];
        } else if (isset($_POST["visited"])){
            $doctor_id_err = "Please insert a doctor ID!";
        }
        if (!empty($_POST["series_name"])){
            $series_name = $_POST["series_name"];
        } else if (isset($_POST["visited"])){
            $series_name_err = "Please insert a series name!";
        }
        if (!empty($_POST["series_description"])){
            $series_description = $_POST["series_description"];
        } else if (isset($_POST["visited"])){
            $series_description_err = "Please insert a series description!";
        }
        // Read-only fields; should not never be empty
        if (!empty($_POST["serialnum"])){
            $serialnum = $_POST["serialnum"];
        }
        if (!empty($_POST["manufacturer"])){
            $manufacturer = $_POST["manufacturer"];
        }
    }
    $valid = false;

    // Render header
    $title = "Create study";
    require("../templates/header.php");

    if ($valid)
    {
        // Add study to DB (request number is set to AI)
        $result = query(
            "INSERT INTO study (request_number, description, date, doctor_id, serial_number, manufacturer) VALUES
            (NULL, ?, ?, ?, ?, ?)", $description, $date, $doctor, $serialnum, $manufacturer); 
    }
    else
    {

    }

?>

        <div class="container">
            
            <?php if (!isset($result))
            require('../templates/add_study_form.php');
            ?>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>