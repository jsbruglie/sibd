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
        if (!empty($_POST["request_number"])){
            $request_number = $_POST["request_number"];
        // Complain only if there has been an invalid submit attempt
        } else if (isset($_POST["visited"])){
            $request_number_err = "Please insert the request id!";
        }
        if (!empty($_POST["date"])){
            $date = $_POST["date"];
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
        if (!empty($_POST["series_id"])){
            $series_id = $_POST["series_id"];
        } else if (isset($_POST["visited"])){
            $series_id_err = "Please insert a series ID!";
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
    $valid = !empty($request_number) && !empty($date) && !empty($description) && !empty($doctor_id) &&
        !empty($series_id) && !empty($series_name) && !empty($series_description);

    $error = empty($serialnum) || empty($manufacturer);

    // Render header
    $title = "Create study";
    require("../templates/header.php");

    if ($valid)
    {
        $insert_study = [
            'INSERT INTO study (request_number, description, date, doctor_id, serial_number, manufacturer) VALUES
                (?, ?, ?, ?, ?, ?),', [$request_number, $description, $date, $doctor_id, $serialnum, $manufacturer]
        ];

        $base_url = $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . "/series/" . $series_id;

        $insert_series = [
            'INSERT INTO series (series_id, name, base_url, request_number, description) VALUES
                (?, ?, ?, ?, ?, ?),', [$series_id, $series_name, $base_url, $request_number, $series_description]
        ];

        $result = transact([$insert_study, $insert_series]);
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