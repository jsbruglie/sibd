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
    $valid = !empty($date) && !empty($description) && !empty($doctor_id) &&
        !empty($series_name) && !empty($series_description);

    $error = empty($serialnum) || empty($manufacturer);

    // Render header
    $title = "Create study";
    require("../templates/header.php");

    if ($valid)
    {
        // TODO
        
        // Transaction begin
        // Try inserting study
        // Try inserting series
        // Error? Rollback : Commit

        $result = false;
    }

?>

        <div class="container">
            
            <?php if ($error): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> No device provided.
            </div>

            <?php elseif (!$valid):
            require('../templates/add_study_form.php');
            ?>

            <?php elseif ($result === false): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> Could not insert study and associated data series in database.
            </div>
            
            <?php else: ?>
            <div class="alert alert-success">
                <strong>Success!</strong> Inserted study and associated series in database.
            </div>
            <?php endif ?>

            <a class="btn btn-link" href="index.php">Go Back</a>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>