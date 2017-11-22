<?php
    
    /**
     * @file        register.php
     *
     * @brief       Register patient
     *
     * @author      João Borrego
     *              Daniel Sousa
     *              Nuno Ferreira 
     */
    
    // Configuration
    require("../includes/config.php"); 

    // Handle GET data
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!empty($_GET["name"])){
            $name = $_GET["name"];
        }
    }

    // Handle POST data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $name_err = "Mandatory field!";
        } else {
            $name = $_POST["name"];
        }
        if (empty($_POST["birthday"])) {
            $birthday_err = "Mandatory field!";
        } else {
            $birthday = $_POST["birthday"];
        }
        if (empty($_POST["address"])) {
            $address_err = "Mandatory field!";
        } else {
            $address = $_POST["name"];
        }
    }
    $valid = !empty($name) && empty($name_err) &&
        !empty($birthday) && empty($birthday_err) &&
        !empty($address) && empty($address_err);

    // Render header
    $title = "Register patient";
    require("../templates/header.php");
    
    if ($valid)
    {
        // Add patient to DB (number is set to AI)
        $result = query(
            "INSERT INTO patient (number, name, birthday, address) VALUES
            (NULL, ?, ?, ?)", $name, $birthday, $address);
    }

?>

        <div class="container">

            <?php if (!isset($result)):
                require('../templates/patient_register_form.php');
            ?>
            <?php elseif ($result === false): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> Could not insert patient in database.
                <?php echo count($result) ?>
            </div>
            <?php else: ?>
            <div class="alert alert-success">
                <strong>Success!</strong> Inserted <?= $name ?> in database.
            </div>
            <?php endif ?>

            <button class="btn btn-link" onclick="goBack()">Go Back</button>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>