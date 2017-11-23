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